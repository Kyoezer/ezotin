<?php
class CriteriaEtool extends EtoolController{
	public function index($tenderId){
        $criteriaEquipments = array(new CriteriaEquipmentModel());
        $criteriaHR = array(new CriteriaHumanResourceModel());
        $etlTiersEqp = DB::table('etltier')->where('EQ_Points','>','0')->get(array('Id','Name','Eq_Points'));
        $etlTiersHR = DB::table('etltier')->where('HR_Points','>','0')->get(array('Id','Name','HR_Points'));
        //$etlTiersHR = DB::table('etltier')->get(array('Id','Name','MaxPoints'));
        $designations = CmnListItemModel::designation()->get(array('Id','Name'));
        $equipments = CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name'));
        $savedTender = DB::table('etltender as T1')
                            ->join('cmncontractorclassification as T2','T1.CmnContractorClassificationId','=','T2.Id')
                            ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                            ->join('sysuser as T4','T4.CmnProcuringAgencyId','=','T1.CmnProcuringAgencyId')
                            ->join('cmnprocuringagency as T5','T5.Id','=','T4.CmnProcuringAgencyId')
                            ->join('cmndzongkhag as T7','T1.CmnDzongkhagId','=','T7.Id')
                            ->where('T1.Id','=',$tenderId)
                            ->where('T4.Id','=',Auth::user()->Id)
                            ->get(array(DB::raw("case when T1.migratedworkid is null 
                            then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),
                            "T5.Name as ProcuringAgency","T1.ProjectEstimateCost","T1.TentativeStartDate","T1.TentativeEndDate",
                            "T1.DescriptionOfWork","T1.Method","T1.TenderStatus","T1.NameOfWork",DB::raw("concat(T2.Name,' (',T2.Code,')') as Classification , T2.Code as classficationCode"),
                            "T3.Code as Category","T7.NameEn as Dzongkhag"));



		$hasHRCriteria = DB::table('etlcriteriahumanresource')->where('EtlTenderId','=',$tenderId)->count('Id');
		$hasEQCriteria = DB::table('etlcriteriaequipment')->where('EtlTenderId','=',$tenderId)->count('Id');
        if($hasHRCriteria > 0){
            /**OLD CODE COMMENTED BY PRAMOD $criteriaHR = DB::table('etlcriteriahumanresource as T1')
                                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                ->join('cmnlistitem as A','A.Id','=','T1.CmnDesignationId')
                                ->orderBy('T2.MaxPoints','DESC')
                                ->orderBy('A.Name')
                                ->orderBy('T1.Points','DESC')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->get(array('T1.EtlTenderId','T1.EtlTierId','T1.Qualification','T1.CmnDesignationId','T1.Points'));
        
            */
            $criteriaHR = DB::table('etlcriteriahumanresource as T1')
                                ->join('cmnlistitem as A','A.Id','=','T1.CmnDesignationId')
                                ->orderBy('A.Name')
                                ->orderBy('T1.Points','DESC')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->get(array('T1.EtlTenderId','T1.EtlTierId','T1.Qualification','T1.CmnDesignationId','T1.Points'));
                            }
        if($hasEQCriteria > 0){
          /**OLD CODE COMMENETED BY PRAMOD   $criteriaEquipments = DB::table('etlcriteriaequipment as T1')
                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                ->join('cmnequipment as A','A.Id','=','T1.CmnEquipmentId')
                ->orderBy('T2.MaxPoints','DESC')
                ->orderBy('A.Name')
                ->orderBy('T1.Points','DESC')
                ->where('T1.EtlTenderId','=',$tenderId)
                ->get(array('T1.EtlTenderId','T1.EtlTierId','T1.CmnEquipmentId','T1.Points','T1.Quantity'));
            */

            $criteriaEquipments = DB::table('etlcriteriaequipment as T1')
            ->join('cmnequipment as A','A.Id','=','T1.CmnEquipmentId')
            ->orderBy('A.Name')
            ->orderBy('T1.Points','DESC')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->get(array('T1.EtlTenderId','T1.EtlTierId','T1.CmnEquipmentId','T1.Points','T1.Quantity'));
        }
        
        $auditTrailActionMessage="setting of criteria for work Id ".$savedTender[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$savedTender[0]->WorkId);
        return View::make('etool.setcriteria')
                ->with('criteriaEquipments',$criteriaEquipments)
                ->with('criteriaHR',$criteriaHR)
                ->with('tenderId',$tenderId)
                ->with('equipments',$equipments)
                ->with('designations',$designations)
                ->with('etlTiersEqp',$etlTiersEqp)
                ->with('etlTiersHR',$etlTiersHR)
                
                ->with('savedTender',$savedTender);
	}
    public function postSaveCriteria(){
        $auditTrailActionMessage="Evaluation Criteria defined for Work Id ".Input::get('HiddenWorkId');
        $etlTenderId = Input::get('EtlTenderId');
        $humanResourceInputs = Input::get('etlcriteriahumanresource');
        $equipmentInputs = Input::get('etlcriteriaequipment');
        $inputArray = array();
        $currentTab = Input::get('CurrentTab');
        $currentTabParameter = substr($currentTab,1,strlen($currentTab));
        DB::table('etlcriteriahumanresource')->where('EtlTenderId','=',$etlTenderId)->delete();
        DB::table('etlcriteriaequipment')->where('EtlTenderId','=',$etlTenderId)->delete();
        DB::beginTransaction();
        if(count($humanResourceInputs)>0){
            foreach($humanResourceInputs as $key=>$value){
                $inputArray['EtlTenderId'] = $etlTenderId;
                foreach($value as $x=>$y){
                    $inputArray[$x] = $y;
                }
            // OLD CODE COMMENTED BY PRAMOD if(!empty($inputArray['EtlTenderId'])){
                
                if(!empty($inputArray['CmnDesignationId'])){
                    try{
                        $inputArray['Id'] = $this->UUID();
                        CriteriaHumanResourceModel::create($inputArray);
                    }catch(Exception $e){
                        DB::rollback();
                        throw $e;
                    }
                    $inputArray = array();
                }
            }
        }
       // die('ccc');
        $inputArray = array();
        foreach($equipmentInputs as $key1=>$value1){
            $inputArray['EtlTenderId'] = $etlTenderId;
            foreach($value1 as $x1=>$y1){
                $inputArray[$x1] = $y1;
            }
            
            //OLD CODE COMMENTED BY PRAMOD if(!empty($inputArray['CmnEquipmentId'])){
            if(!empty($inputArray['CmnEquipmentId'])){
                try{
                    $tierId = DB::table('etltier')->where('Name',$inputArray['EtlTierId'])->pluck('Id');
                    if($tierId!="")
                    {
                        $inputArray['EtlTierId'] = $tierId;
                        $equp = DB::table('cmnequipment')->where('Name',$inputArray['CmnEquipmentId'])->pluck('Id');
                        if($equp!="")
                        {
                           $inputArray['CmnEquipmentId'] = $equp;
                        }
                    }

                    $inputArray['Id'] = $this->UUID();
                    CriteriaEquipmentModel::create($inputArray);
                }catch(Exception $e){
                    DB::rollback();
                    throw $e;
                }
                $inputArray = array();
            }
        }
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,Input::get('HiddenWorkId'));
        DB::commit();
        return Redirect::to('etl/setcriteriaetool/'.$etlTenderId.'?currentTab='.$currentTabParameter.$currentTab)->with('savedsuccessmessage','Criteria has been successfully set.');
    }
}