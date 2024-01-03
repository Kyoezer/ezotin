<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 2/25/2017
 * Time: 12:00 PM
 */
class ContractorAuditMemo extends CrpsController
{
    public function getIndex(){
        $parameters = array();

        $contractorId = Input::get('ContractorId');
        $cdbNo = Input::get('CDBNo');
        $type = Input::get('Type');

        $query = "select T1.Id,concat(case when Type=1 then T2.NameOfFirm else T3.NameOfFirm end, ' (',case when T1.Type = 1 then 'Contractor' else 'Consultant' end,')') as NameOfFirm, case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo, T1.Agency,T1.AuditObservation,T1.AuditedPeriod,T1.AIN,T1.ParoNo,DATE_FORMAT(T1.CreatedOn, '%d-%M-%Y') as CreatedOn from crpcontractorauditclearance T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorConsultantId left join crpconsultantfinal T3 on T3.Id = T1.CrpContractorConsultantId where coalesce(T1.Dropped,0) <> 1";
        if((bool)$contractorId || (bool)$cdbNo || (bool)$type){
            if((bool)$contractorId){
                $query.=" and T2.Id = ?";
                array_push($parameters,$contractorId);
            }

            if((bool)$type){
                $query.=" and T1.Type = ?";
                array_push($parameters,$type);
            }
            if((bool)$cdbNo){
                $query.=" and case when T1.Type = 1 then T2.CDBNo = ? else T3.CDBNo = ? end";
                array_push($parameters,$cdbNo);
                array_push($parameters,$cdbNo);
            }
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $records = DB::select("$query order by T1.CreatedOn desc$limitOffsetAppend",$parameters);
        return View::make('crps.contractorauditindex')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('records',$records);
    }
    public function addNew(){
        return View::make('crps.contractoraudit');
    }
    public function postSave(){
        if(!Input::hasFile('Excel')){
            return Redirect::to('contractor/addauditrecord')->with('customerrormessage','Please upload an excel file!');
        }

        $arrayNames = array("Type","CrpContractorConsultantId","Agency","AuditedPeriod","AIN","ParoNo","AuditObservation");
        DB::beginTransaction();
        try{
            Excel::load(Input::file('Excel'), function($reader) use($arrayNames) {
                $results = $reader->toArray();
                foreach($results as $key=>$value):
                    $count = 0;
                    $postedValue['Id'] = $this->UUID();
                    foreach($value as $x=>$y):
                        if($arrayNames[$count] == "Type"){
                            $type = $y;
                        }
                        if($arrayNames[$count] == "CrpContractorConsultantId"){
                            if((int)$type == 1){
                                $crpContractorFinalId = DB::table('crpcontractorfinal')->where(DB::raw("TRIM(CDBNo)"),trim($y))->pluck('Id');
                            }else{

                                $crpContractorFinalId = DB::table('crpconsultantfinal')->where(DB::raw("TRIM(CDBNo)"),trim($y))->pluck('Id');
                            }

                            if((bool)$crpContractorFinalId){
                                $y = $crpContractorFinalId;
                            }else{
                                DB::rollBack();
                                return Redirect::to('contractor/addauditrecord')->with('customerrormessage',"Contractor $y does not exist");
                            }
                        }
                        $postedValue[$arrayNames[$count]] = ($y==NULL)?$y:trim($y);
                        $count++;
                    endforeach;
                    $postedValue['CreatedBy'] = Auth::user()->Id;
                    $postedValue['CreatedOn'] = date('Y-m-d G:i:s');
                    DB::table('crpcontractorauditclearance')->insert(array($postedValue));
                endforeach;
            });
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('contractor/addauditrecord')->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('contractor/addauditrecord')->with("savedsuccessmessage","Audit has been recorded!");
    }
    public function getDelete($id){
        DB::beginTransaction();
        try{
            DB::table('crpcontractorauditclearance')->where('Id',$id)->delete();
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('contractor/auditmemo')->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('contractor/auditmemo')->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Audit detail has been deleted!");
    }
    public function getEdit($id){
        $details = DB::table('crpcontractorauditclearance as T1')
                        ->leftJoin('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorConsultantId')
                        ->leftJoin('crpconsultantfinal as T3','T3.Id','=','T1.CrpContractorConsultantId')
                        ->where('T1.Id',$id)
                        ->get(array('T1.Id','T1.CrpContractorConsultantId','T1.Agency','T1.AuditObservation','T1.AIN','T1.ParoNo','T1.AuditedPeriod',
                            DB::raw("case when Type = 1 then concat(TRIM(T2.NameOfFirm),' (',T2.CDBNo,')') else concat(TRIM(T3.NameOfFirm),' (',T3.CDBNo,')') end as Contractor")));
        if(count($details)==0){
            App::abort(404);
        }
        return View::make('crps.editcontractoraudit')
                    ->with('details',$details);
    }
    public function saveEditedAudit(){
        $id = Input::get('Id');
        $inputs = Input::except('_token');
        if(Input::has("DroppedDate")){
            $inputs['DroppedDate'] = $this->convertDate($inputs['DroppedDate']);
            $inputs['SysDroppedByUserId'] = Auth::user()->Id;
        }
        foreach($inputs as $key=>$value){
            if($value == ''){
                $value = NULL;
            }
        }
        DB::table('crpcontractorauditclearance')->where('Id',$id)->update($inputs);
        return Redirect::to("contractor/auditmemo")->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Audit details have been updated!");
    }
    public function postFetchDetails(){
        $id = Input::get('id');
//        $auditDetails = DB::table('crpcontractorauditclearance as T1')
//                            ->join('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorFinalId')
//                            ->where('T1.Id',$id)
//                            ->get(array('T2.NameOfFirm','T2.CDBNo','T1.AuditObservation'));
        $auditDetails = DB::select("select case when Type=1 then T2.NameOfFirm else T3.NameOfFirm end as NameOfFirm, case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo, T1.AuditObservation from crpcontractorauditclearance T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorConsultantId left join crpconsultantfinal T3 on T3.Id = T1.CrpContractorConsultantId where T1.Id = ?",array($id));

        if(count($auditDetails) == 0){
            return Response::json(array('response'=>0));
        }else{
            return Response::json(array('response'=>1,'NameOfFirm'=>$auditDetails[0]->NameOfFirm,'CDBNo'=>$auditDetails[0]->CDBNo,'AuditObservation'=>$auditDetails[0]->AuditObservation));
        }
    }
    public function getAuditClearanceReport(){
        $parameters = array();
        $parametersForPrint = array();
        $firmId = Input::get('FirmId');
        $dropped = Input::get('Dropped');

        $query = "select case when T1.Type = 1 then T2.NameOfFirm else T3.NameOfFirm end as NameOfFirm, case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo, T1.Agency,T1.Type,T1.AuditedPeriod,T1.AIN,T1.ParoNo,T1.AuditObservation,T1.Dropped from crpcontractorauditclearance T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorConsultantId left join crpconsultantfinal T3 on T3.Id = T1.CrpContractorConsultantId where 1";

        $contractorsConsultants = DB::table('crpcontractorauditclearance as T1')
                                    ->leftJoin('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorConsultantId')
                                    ->leftJoin('crpconsultantfinal as T3','T3.Id','=','T1.CrpContractorConsultantId')
                                    ->get(array(DB::raw('distinct T1.CrpContractorConsultantId as Id'),DB::raw("case when T1.Type = 1 then 'Contractor' else 'Consultant' end as FirmType"),DB::raw("case when T1.Type = 1 then T2.NameOfFirm else T3.NameOfFirm end as NameOfFirm"),DB::raw("case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo")));

        if((bool)$firmId){
            $query.=" and T1.CrpContractorConsultantId = ?";
            array_push($parameters,$firmId);
            $firmDetails = DB::table('crpcontractorauditclearance as T1')
                ->leftJoin('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorConsultantId')
                ->leftJoin('crpconsultantfinal as T3','T3.Id','=','T1.CrpContractorConsultantId')
                ->where('T1.CrpContractorConsultantId',$firmId)
                ->get(array(DB::raw("case when T1.Type = 1 then 'Contractor' else 'Consultant' end as FirmType"),DB::raw("case when T1.Type = 1 then T2.NameOfFirm else T3.NameOfFirm end as NameOfFirm"),DB::raw("case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo")));
            $parametersForPrint['Firm'] = $firmDetails[0]->NameOfFirm." (".$firmDetails[0]->CDBNo." ".$firmDetails[0]->FirmType.")";
        }
        if((bool)$dropped){
            if($dropped == 2){
                $dropped = 0;
            }
            $query.=" and T1.Dropped = ?";
            array_push($parameters,$dropped);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select("$query order by T1.CreatedOn$limitOffsetAppend",$parameters);

        return View::make('report.auditclearancereport')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('parametersForPrint',$parametersForPrint)
                    ->with('contractorConsultants',$contractorsConsultants)
                    ->with('reportData',$reportData);
    }
    public function getAuditClearanceReportDropped(){
        $parameters = array();
        $firmId = Input::get('FirmId');

        $query = "select case when T1.Type = 1 then T2.NameOfFirm else T3.NameOfFirm end as NameOfFirm, case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo, T1.Agency,T1.Type,T1.AuditedPeriod,T1.AIN,T1.ParoNo,T1.AuditObservation,T1.Remarks,T1.DroppedDate,T4.FullName as Dropper from crpcontractorauditclearance T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorConsultantId left join crpconsultantfinal T3 on T3.Id = T1.CrpContractorConsultantId join sysuser T4 on T4.Id = T1.SysDroppedByUserId where coalesce(T1.Dropped,0) = 1";

        $contractorsConsultants = DB::table('crpcontractorauditclearance as T1')
            ->leftJoin('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorConsultantId')
            ->leftJoin('crpconsultantfinal as T3','T3.Id','=','T1.CrpContractorConsultantId')
            ->whereRaw("coalesce(T1.Dropped,0) = 1")
            ->get(array(DB::raw('distinct T1.CrpContractorConsultantId as Id'),DB::raw("case when T1.Type = 1 then 'Contractor' else 'Consultant' end as FirmType"),DB::raw("case when T1.Type = 1 then T2.NameOfFirm else T3.NameOfFirm end as NameOfFirm"),DB::raw("case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo")));

        if((bool)$firmId){
            $query.=" and T1.CrpContractorConsultantId = ?";
            array_push($parameters,$firmId);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select("$query order by T1.CreatedOn$limitOffsetAppend",$parameters);

        return View::make('report.auditclearancereportdropped')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('contractorConsultants',$contractorsConsultants)
            ->with('reportData',$reportData);
    }
}