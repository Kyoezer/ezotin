<?php

class CertifiedbuilderReplaceRelease extends CrpsController{
public function getIndex()
{
   
        $tenderDetails = Session::has('tenderDetails')?Session::get('tenderDetails'):array();
        $cdbNo = Session::has('CDBNo')?Session::get('CDBNo'):'';
        return View::make('crps.cbreplacereleaseindex')
                ->with('tenderDetails',$tenderDetails)
                ->with('cdbNo',$cdbNo);
    }

    public function postFetch()
    {
        $workId = Input::get('WorkId');
        $tenderDetails = array();
        // $hrDetails = array();
        // $equipmentDetails = array();
        if($workId){
            
            $tenderDetails = DB::select("SELECT a.`NameOfWork`,a.`WorkId`,b.`Name`,a.`Id`,a.`ReferenceNo`,a.`ContractPeriod`,b.Name AS ProcuringAgency,c.NameEn AS Dzongkhag FROM `cbbiddingform` a LEFT JOIN cmndzongkhag c ON a.CmnDzongkhagId=c.Id
                                         LEFT JOIN `cmnprocuringagency` b ON a.`CmnProcuringAgencyId`=b.Id
                                        WHERE a.`WorkId`=".$workId);

                    $hrDetails = DB::select(" SELECT a.Id,a.Name,a.CIDNo,d.Name AS Designation FROM `cbbidhumanresource` a  
                    LEFT JOIN  `cbbiddingform` b ON a.`CrpBiddingFormId`=b.`Id` LEFT JOIN cmnlistitem d ON a.CmnDesignationId=d.Id 
                    WHERE b.`WorkId`=".$workId);
                
                    $equipmentDetails = DB::select(" SELECT * FROM `cbbidequipment` a  
                    LEFT JOIN  `cbbiddingform` b ON a.`CrpBiddingFormId`=b.`Id`
                    WHERE b.`WorkId`=".$workId);
                   
                    return View::make('crps.certifiedbuilderreplacereleasedetails')
                    ->with('tenderDetails', $tenderDetails)
                    ->with('WorkId', $workId)
                    ->with('equipmentDetails', $equipmentDetails)
                    ->with('hrDetails', $hrDetails);
        }
      
    }

    

    
    public function getReplaceCinetHR($id)
    {
      
        $hrDetails = DB::select("SELECT * FROM `cbbidhumanresource` a WHERE a.`Id`='".$id."'");
        return View::make('crps.replaceCinetHR')
            ->with('hrDetails', $hrDetails);
    }

    public function releaseCinethr($id)
    {
        $hrDetails = DB::select("SELECT * FROM `cbbidhumanresource` a WHERE a.`Id`='".$id."'");
        return View::make('crps.releaseCinetHR')
            ->with('hrDetails', $hrDetails);
    }

    
    public function replaceCitnetEquipment($Id)
    {
        
        $equipmentDetails = DB::select("SELECT * FROM `cbbidequipment` a WHERE a.`CrpBiddingFormId`='".$Id."'");
        return View::make('crps.replaceCinetequipment')
            ->with('equipmentDetails', $equipmentDetails);
    }

    
    public function getReplaceEquipment($id)
    {


        $workId = DB::table('etlcontractorequipment as T1')
            ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
            ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
            ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
            ->where('T1.Id', $id)
            ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
        if (!$workId) {
            return Redirect::to('etoolsysadm/replacereleasehrequipment')->with('customerrormessage', 'No work exists with this Work Id');
        }
        $registrationNo = DB::table('etlcontractorequipment')->where('Id', $id)->pluck('RegistrationNo');
        return View::make('etool.replaceequipment')
            ->with('registrationNo', $registrationNo)
            ->with('workId', $workId[0]->WorkId)
            ->with('Id', $id);
    }
    

    public function releaseCinetequipment($id)
    {
       
        $equipmentDetails = DB::select("SELECT * FROM `cbbidequipment` a WHERE a.`CrpBiddingFormId`='".$id."'");
        return View::make('crps.releaseCinetequipment')
            ->with('equipmentDetails', $equipmentDetails)
            ->with('Id', $id);
    }

    
    public function getReleaseEquipment($id)
    {
        $workId = DB::table('etlcontractorequipment as T1')
            ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
            ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
            ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
            ->where('T1.Id', $id)
            ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
        if (!$workId) {
            return Redirect::to('etoolsysadm/replacereleasehrequipment')->with('customerrormessage', 'No work exists with this Work Id');
        }
        $registrationNo = DB::table('etlcontractorequipment')->where('Id', $id)->pluck('RegistrationNo');
        return View::make('etool.releaseequipment')
            ->with('registrationNo', $registrationNo)
            ->with('workId', $workId[0]->WorkId)
            ->with('Id', $id);
    }


    


    //HR
    public function getReplaceHR($id)
    {
        $workId = DB::table('etlcontractorhumanresource as T1')
            ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
            ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
            ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
            ->where('T1.Id', $id)
            ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
        if (!$workId) {
            return Redirect::to('etoolsysadm/replacereleasehrequipment')->with('customerrormessage', 'No work exists with this Work Id');
        }
        $cidNo = DB::table('etlcontractorhumanresource')->where('Id', $id)->pluck('CIDNo');
        return View::make('etool.replacehr')
            ->with('cidNo', $cidNo)
            ->with('workId', $workId[0]->WorkId)
            ->with('Id', $id);
    }

    public function getReleaseHR($id)
    {
        $workId = DB::table('etlcontractorhumanresource as T1')
            ->leftjoin('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
            ->leftjoin('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
            ->leftjoin('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
            ->where('T1.Id', $id)
            ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
        if (!$workId) {
            return Redirect::to('etoolsysadm/replacereleasehrequipment')->with('customerrormessage', 'No work exists with this Work Id');
        }
        $cidNo = DB::table('etlcontractorhumanresource')->where('Id', $id)->pluck('CIDNo');
        return View::make('etool.releasehr')
            ->with('cidNo', $cidNo)
            ->with('workId', $workId[0]->WorkId)
            ->with('Id', $id);
    }

    public function getRequest()
    {
        $agencyId = Auth::user()->CmnProcuringAgencyId;
        $module = Request::segment(1);
        if($module == 'etl'){
            $works = DB::table('etltender as T1')
                ->join('cmnprocuringagency as T2', 'T2.Id', '=', 'T1.CmnProcuringAgencyId')
                ->where('T1.CmnProcuringAgencyId', $agencyId)
                ->where('T1.TenderSource',1)
                ->where('T1.CmnWorkExecutionStatusId', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->get(array("T1.Id", DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        }else{
            $works = DB::table('etltender as T1')
                ->join('cmnprocuringagency as T2', 'T2.Id', '=', 'T1.CmnProcuringAgencyId')
                ->join('crpbiddingform as A','A.EtlTenderId','=','T1.Id')
                ->where('T1.CmnProcuringAgencyId', $agencyId)
                ->where('T1.TenderSource',2)
                ->where('A.CmnWorkExecutionStatusId', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->get(array("T1.Id", DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        }

        return View::make('etool.requestreplacerelease')
            ->with('works', $works);
    }

    public function getRequestList()
    {
        $parameters = array();
        $requestType = Input::get("RequestType");
        $workId = Input::get("WorkId");

        $query = "select T1.Id,T1.RequestType,T1.RequestDate, T1.WorkId, T3.Name as Agency, T6.CDBNo, T6.NameOfFirm, A.FullName, A.username from etlreplacereleaserequest T1 join sysuser A on A.Id = T1.CreatedBy join (etltender T2 join cmnprocuringagency T3 on T2.CmnProcuringAgencyId = T3.Id join etltenderbiddercontractor T4 on T4.EtlTenderId = T2.Id join etltenderbiddercontractordetail T5 on T5.EtlTenderBidderContractorId = T4.Id join crpcontractorfinal T6 on T5.CrpContractorFinalId = T6.Id) on T1.EtlTenderId = T2.Id WHERE coalesce(T1.RequestStatus,0)=0 and ((T4.ActualStartDate is not null and T4.ActualStartDate <> '0000-00-00')or (T4.AwardedAmount is not null and coalesce(T4.AwardedAmount,0)>0))";
        if ((bool)$requestType) {
            $query .= " and T1.RequestType = ?";
            array_push($parameters, $requestType);
        }
        if ((bool)$workId) {
            $query .= " and T1.WorkId = ?";
            array_push($parameters, $workId);
        }
        $query .= " union all select T1.Id,T1.RequestType,T1.RequestDate, T1.WorkId, T3.Name as Agency, T6.CDBNo, T6.NameOfFirm, A.FullName, A.username from etlreplacereleaserequest T1 join sysuser A on A.Id = T1.CreatedBy join (crpbiddingform T2 join cmnprocuringagency T3 on T2.CmnProcuringAgencyId = T3.Id join crpbiddingformdetail T4 on T4.CrpBiddingFormId = T2.Id join crpcontractorfinal T6 on T4.CrpContractorFinalId = T6.Id) on T1.EtlTenderId = T2.EtlTenderId WHERE coalesce(T1.RequestStatus,0)=0 and T2.CmnWorkExecutionStatusId = ?";
        array_push($parameters, CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
        if ((bool)$requestType) {
            $query .= " and T1.RequestType = ?";
            array_push($parameters, $requestType);
        }
        if ((bool)$workId) {
            $query .= " and T1.WorkId = ?";
            array_push($parameters, $workId);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page') ? Input::get('page') : 1;
        $pagination = $this->pagination($query, $parameters, 10, $pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $requests = DB::select("$query $limitOffsetAppend", $parameters);
        return View::make('etool.requestreplacelist')
            ->with('start', $start)
            ->with('noOfPages', $noOfPages)
            ->with('requests', $requests);
    }

    public function postFetchHr()
    {
        $id = Input::get('id');
        $module = Input::get('module');
        if($module == 'etl'){
            $hrDetails = DB::table('etlcontractorhumanresource as T1')
                ->join('etltenderbiddercontractor as T2', 'T1.EtlTenderBidderContractorId', '=', "T2.Id")
                ->where("T2.EtlTenderId", $id)
                ->whereRaw("((T2.ActualStartDate is not null and T2.ActualStartDate <> '0000-00-00')or (T2.AwardedAmount is not null and coalesce(T2.AwardedAmount,0)>0))")
                ->get(array(DB::raw('TRIM(T1.CIDNo) as CIDNo')));
        }else{
            $hrDetails = DB::table('cinetbidhumanresource as T1')
                ->join('crpbiddingform as T2', 'T1.CrpBiddingFormId', '=', "T2.Id")
                ->where("T2.EtlTenderId", $id)
                ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->get(array(DB::raw('TRIM(T1.CIDNo) as CIDNo')));
        }

        return Response::json($hrDetails);
    }

    public function postFetchEq()
    {
        $id = Input::get('id');
        $module = Input::get('module');
        if($module == 'etl'){
            $eqDetails = DB::table('etlcontractorequipment as T1')
                ->join('etltenderbiddercontractor as T2', 'T1.EtlTenderBidderContractorId', '=', "T2.Id")
                ->where("T2.EtlTenderId", $id)
                ->whereRaw("((T2.ActualStartDate is not null and T2.ActualStartDate <> '0000-00-00')or (T2.AwardedAmount is not null and coalesce(T2.AwardedAmount,0)>0))")
                ->get(array(DB::raw('TRIM(T1.RegistrationNo) as RegistrationNo')));
        }else{
            $eqDetails = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2', 'T1.CrpBiddingFormId', '=', "T2.Id")
                ->where("T2.EtlTenderId", $id)
                ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->get(array(DB::raw('TRIM(T1.RegistrationNo) as RegistrationNo')));
        }

        return Response::json($eqDetails);
    }

    public function saveRequest()
    {
        $inputs = Input::except('_token', 'RequestLetter','Module');
        $modulePrefix = Input::get('Module');
        $inputs['Id'] = $this->UUID();
        $inputs['RequestDate'] = date('Y-m-d');
        $inputs['RequestStatus'] = 0;
        $inputs['CreatedBy'] = Auth::user()->Id;
        $inputs['CreatedOn'] = date('Y-m-d G:i:s');
        $workId = DB::table('etltender as T1')
            ->join('cmnprocuringagency as T2', 'T2.Id', '=', 'T1.CmnProcuringAgencyId')
            ->where('T1.Id', $inputs['EtlTenderId'])
            ->select(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"))
            ->pluck('WorkId');
        $inputs['WorkId'] = $workId;
        $file = Input::file('RequestLetter');
        $key = randomString() . randomString();
        $fileName = $key . "_" . $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        $directory = "replacereleaserequest/";

        $file->move($directory, $fileName);
        $inputs['DocumentPath'] = $directory . $fileName;
        DB::beginTransaction();
        try {
            DB::table('etlreplacereleaserequest')->insert($inputs);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to($modulePrefix.'/requestreplacerelease')->with('customerrormessage', $e->getMessage());
        }
        DB::commit();
        return Redirect::to($modulePrefix.'/requestreplacerelease')->with('savedsuccessmessage', "Your request has been sent!");

    }

    public function getDetails($id)
    {
        $details = DB::table('etlreplacereleaserequest')->where('Id', $id)->get(array('Id', 'Number', 'RequestType', 'EtlTenderId', 'NewNumber', 'DocumentPath', 'RequestLetterNo', 'WorkId'));
        if (count($details) == 0) {
            return Redirect::to('etoolsysadm/releasereplacerequestlist')->with('customerrormessage', '<strong>ERROR! </strong>No such work!');
        }
        return View::make('etool.rrrequestdetails')
            ->with('details', $details);
    }

    public function postReplaceEquipment()
    {
        
        $id = Input::get('Id');
        $module = Input::has('module')?Input::get('module'):'etl';
        $registrationNo = Input::get('RegistrationNo');
        $referenceLetterNo = Input::get('RequestLetterNo');
        $oldRegistrationNo = DB::table('etlcontractorequipment')->where('Id', $id)->pluck('RegistrationNo');
        $redirectUrl = Input::get('redirectUrl');
        if (!(bool)$redirectUrl) {
            $redirectUrl = 'etoolsysadm/replacereleasehrequipment';
        }else{
            $mainId = Input::get('MainId');
            DB::table('etlreplacereleaserequest')->where('Id',$mainId)->update(array('RequestStatus'=>1));
        }
        if($module == "etl"){
            $cdbNo = DB::table('etlcontractorequipment as T1')
                ->join('etltenderbiddercontractordetail as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.EtlTenderBidderContractorId')
                ->join('crpcontractorfinal as T3', 'T3.Id', '=', 'T2.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T3.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('etlcontractorequipment as T1')
                ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('etlcontractorequipment')->where('Id', $id)->update(array('RegistrationNo' => $registrationNo));
        }else{
            $oldRegistrationNo = DB::table('cinetbidequipment')->where('Id', $id)->pluck('RegistrationNo');
            $cdbNo = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('crpbiddingformdetail as T3', 'T3.CrpBiddingFormId', '=', 'T2.Id')
                ->join('crpcontractorfinal as T4','T4.Id','=','T3.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T4.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('cinetbidequipment')->where('Id', $id)->update(array('RegistrationNo' => $registrationNo));
        }

        DB::table('etlreplacereleasetrack')->insert(array('Id' => $this->UUID(), 'User' => Auth::user()->FullName, 'HrEqOldId'=>$oldRegistrationNo, 'HrEqNewId' => $registrationNo, 'WorkId' => (count($workId)>0)?$workId[0]->WorkId:'-', 'CDBNo' => (count($cdbNo)>0)?$cdbNo[0]->CDBNo:'-', 'Date' => date('Y-m-d G:i:s'), 'Operation' => 'Replace', 'RefDoc' => $referenceLetterNo));
        return Redirect::to($redirectUrl)->with('savedsuccessmessage', 'Equipment Replaced successfully');
    }

    
    public function PostreleaseCinetEquipment()
    {
        $id = Input::get('Id');
        
        DB::delete('Delete from cbbidequipment where Id = "'.$id.'"');
        $redirectUrl = Input::get('redirectUrl');
        return $this->getIndex();
    }
    
    public function postreleaseCinetHR()
    {
        
        $id = Input::get('Id');
        DB::delete('Delete from cbbidhumanresource where Id = "'.$id.'"');
        $redirectUrl = Input::get('redirectUrl');
        return $this->getIndex();
    }

    

    public function replaceCinetEquipment()
    {
        $id = Input::get('Id');
        $registrationNo = Input::get('RegistrationNo');
        $referenceLetterNo = Input::get('RequestLetterNo');
        $redirectUrl = 'certifiedbuilder/replacereleasehrequipment';
        // DB::update('update cbbidequipment set RegistrationNo = "'.$registrationNo.'" , RequestLetterNo =  "'.$referenceLetterNo.'" where Id = "'.$id.'"');
        DB::update('update cbbidequipment set RegistrationNo = "'.$registrationNo.'" where Id = "'.$id.'"');

        $redirectUrl = Input::get('redirectUrl');
        return $this->getIndex();
    }

    public function replaceCinetHR()
    {
        $id = Input::get('Id');
        $CIDNo = Input::get('CID_No');
        $name = Input::get('Name');
        $referenceLetterNo = Input::get('RequestLetterNo');
        $redirectUrl = 'certifiedbuilder/replacereleasehrequipment';
        DB::update('                        
                UPDATE
                cbbidhumanresource
                SET
                CIDNo = "'.$CIDNo.'",
                Name =  "'.$name.'"  WHERE `Id` = "'.$id.'"');

        $redirectUrl = Input::get('redirectUrl');
        return $this->getIndex();
    }

    public function postReleaseEquipment()
    {
        $id = Input::get('Id');
        $referenceLetterNo = Input::get('RequestLetterNo');
        $module = Input::has('module')?Input::get('module'):'etl';

        $registrationNo = Input::get('RegistrationNo');
        $redirectUrl = Input::get('redirectUrl');
        if (!(bool)$redirectUrl) {
            $redirectUrl = 'etoolsysadm/replacereleasehrequipment';
        }else{
            $mainId = Input::get('MainId');
            DB::table('etlreplacereleaserequest')->where('Id',$mainId)->update(array('RequestStatus'=>1));
        }
        if($module == "etl"){
            $cdbNo = DB::table('etlcontractorequipment as T1')
                ->join('etltenderbiddercontractordetail as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.EtlTenderBidderContractorId')
                ->join('crpcontractorfinal as T3', 'T3.Id', '=', 'T2.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T3.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('etlcontractorequipment as T1')
                ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('etlcontractorequipment')->where('Id', $id)->delete();
        }else{
            $cdbNo = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('crpbiddingformdetail as T3', 'T3.CrpBiddingFormId', '=', 'T2.Id')
                ->join('crpcontractorfinal as T4','T4.Id','=','T3.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T4.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('cinetbidequipment')->where('Id', $id)->delete();
        }

        DB::table('etlreplacereleasetrack')->insert(array('Id' => $this->UUID(), 'User' => Auth::user()->FullName, 'HrEqOldId' => $registrationNo, 'HrEqNewId' => $registrationNo, 'WorkId' => (count($workId)>0)?$workId[0]->WorkId:'-', 'CDBNo' => (count($cdbNo)>0)?$cdbNo[0]->CDBNo:'-', 'Date' => date('Y-m-d G:i:s'), 'Operation' => 'Release', 'RefDoc' => $referenceLetterNo));
        return Redirect::to($redirectUrl)->with('savedsuccessmessage', 'Equipment Released successfully');
    }

    public function postReplaceHR()
    {
        $id = Input::get('Id');
        $cidNo = Input::get('CIDNo');
        $name = Input::get('Name');
        $referenceLetterNo = Input::get('RequestLetterNo');
        $module = Input::has('module')?Input::get('module'):'etl';

        $oldCIDNo = DB::table('etlcontractorhumanresource')->where('Id', $id)->pluck('CIDNo');
        $redirectUrl = Input::get('redirectUrl');
        if (!(bool)$redirectUrl) {
            $redirectUrl = 'certifiedbuilder/replacereleasehrequipment';
        }else{
            $mainId = Input::get('MainId');
            DB::table('etlreplacereleaserequest')->where('Id',$mainId)->update(array('RequestStatus'=>1));
        }
        if($module == "etl"){
            $cdbNo = DB::table('etlcontractorhumanresource as T1')
                ->join('etltenderbiddercontractordetail as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.EtlTenderBidderContractorId')
                ->join('crpcontractorfinal as T3', 'T3.Id', '=', 'T2.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T3.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('etlcontractorhumanresource as T1')
                ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('etlcontractorhumanresource')->where('Id', $id)->update(array('CIDNo' => $cidNo,'Name'=>$name));
        }else{
            $oldCIDNo = DB::table('cinetbidhumanresource')->where('Id', $id)->pluck('CIDNo');
            $cdbNo = DB::table('cinetbidhumanresource as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('crpbiddingformdetail as T3', 'T3.CrpBiddingFormId', '=', 'T2.Id')
                ->join('crpcontractorfinal as T4','T4.Id','=','T3.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T4.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('cinetbidhumanresource as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('cinetbidhumanresource')->where('Id', $id)->update(array('CIDNo' => $cidNo,'Name'=>$name));

        }

        DB::table('etlreplacereleasetrack')->insert(array('Id' => $this->UUID(), 'User' => Auth::user()->FullName, 'HrEqOldId' => $oldCIDNo, 'HrEqNewId' => $cidNo, 'WorkId' => (count($workId)>0)?$workId[0]->WorkId:'-', 'CDBNo' => (count($cdbNo)>0)?$cdbNo[0]->CDBNo:'-', 'Date' => date('Y-m-d G:i:s'), 'Operation' => 'Replace', 'RefDoc' => $referenceLetterNo));


        return Redirect::to($redirectUrl)->with('savedsuccessmessage', 'HR Replaced successfully');
    }

    public function postReleaseHR()
    {
        $id = Input::get('Id');
        $referenceLetterNo = Input::get('RequestLetterNo');
        $cidNo = Input::get('CIDNo');
        $redirectUrl = Input::get('redirectUrl');
        $module = Input::has('module')?Input::get('module'):'etl';
        if (!(bool)$redirectUrl) {
            $redirectUrl = 'etoolsysadm/replacereleasehrequipment';
        }else{
            $mainId = Input::get('MainId');
            DB::table('etlreplacereleaserequest')->where('Id',$mainId)->update(array('RequestStatus'=>1));
        }

        if($module == "etl"){
            $cdbNo = DB::table('etlcontractorhumanresource as T1')
                ->join('etltenderbiddercontractordetail as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.EtlTenderBidderContractorId')
                ->join('crpcontractorfinal as T3', 'T3.Id', '=', 'T2.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T3.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('etlcontractorhumanresource as T1')
                ->join('etltenderbiddercontractor as T2', 'T2.Id', '=', 'T1.EtlTenderBidderContractorId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('etlcontractorhumanresource')->where('Id', $id)->delete();
        }else{
            $cdbNo = DB::table('cinetbidhumanresource as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('crpbiddingformdetail as T3', 'T3.CrpBiddingFormId', '=', 'T2.Id')
                ->join('crpcontractorfinal as T4','T4.Id','=','T3.CrpContractorFinalId')
                ->where('T1.Id', $id)
                ->get(array(DB::raw('group_concat(T4.CDBNo SEPARATOR ", ") as CDBNo')));
            $workId = DB::table('cinetbidhumanresource as T1')
                ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                ->join('etltender as T3', 'T3.Id', '=', 'T2.EtlTenderId')
                ->join('cmnprocuringagency as T4', 'T4.Id', '=', 'T3.CmnProcuringAgencyId')
                ->where('T1.Id',$id)
                ->get(array(DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId")));
            DB::table('cinetbidhumanresource')->where('Id', $id)->delete();
        }

        DB::table('etlreplacereleasetrack')->insert(array('Id' => $this->UUID(), 'User' => Auth::user()->FullName, 'HrEqOldId' => $cidNo, 'HrEqNewId' => $cidNo, 'WorkId' => (count($workId)>0)?$workId[0]->WorkId:'-', 'CDBNo' => (count($cdbNo)>0)?$cdbNo[0]->CDBNo:'-', 'Date' => date('Y-m-d G:i:s'), 'Operation' => 'Release', 'RefDoc' => $referenceLetterNo));

        return Redirect::to($redirectUrl)->with('savedsuccessmessage', 'HR Released successfully');
    }

    public function postApproveRequest()
    {
        $id = Input::get('Id');
        $module = Input::get('Module');
        $details = DB::table('etlreplacereleaserequest')->where('Id', $id)->get(array('RequestType', 'RequestLetterNo', 'Number', 'NewNumber', 'EtlTenderId'));
        if (count($details) == 0) {
            return Redirect::to('etoolsysadm/releasereplacerequestlist')->with('customerrormessage', '<strong>ERROR! </strong>');
        }
        $requestType = $details[0]->RequestType;
        switch ((int)$requestType):
            case 101:
                if($module == 'etl'){
                    $detailId = DB::table('etltenderbiddercontractor as T1')
                        ->join('etlcontractorhumanresource as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.Id')
                        ->whereRaw("((T1.ActualStartDate is not null and T1.ActualStartDate <> '0000-00-00')or (T1.AwardedAmount is not null and coalesce(T1.AwardedAmount,0)>0))")
                        ->where('T1.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T2.CIDNo', $details[0]->Number)
                        ->select('T2.Id')->pluck('Id');
                }else{
                    $detailId = DB::table('cinetbidhumanresource as T1')
                        ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                        ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                        ->where('T2.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T1.CIDNo', $details[0]->Number)
                        ->select('T1.Id')->pluck('Id');
                }

                return Redirect::to('etoolsysadm/getreplacehr?Id='.$detailId.'&module='.$module.'&MainId='.$id.'&RequestLetterNo=' . $details[0]->RequestLetterNo . '&CIDNo=' . $details[0]->NewNumber . "&redirectUrl=etoolsysadm/releasereplacerequestlist");
                break;
            case 102:
                if($module == 'etl'){
                    $detailId = DB::table('etltenderbiddercontractor as T1')
                        ->join('etlcontractorhumanresource as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.Id')
                        ->whereRaw("((T1.ActualStartDate is not null and T1.ActualStartDate <> '0000-00-00')or (T1.AwardedAmount is not null and coalesce(T1.AwardedAmount,0)>0))")
                        ->where('T1.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T2.CIDNo', $details[0]->Number)
                        ->select('T2.Id')->pluck('Id');
                }else{
                    $detailId = DB::table('cinetbidhumanresource as T1')
                        ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                        ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                        ->where('T2.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T1.CIDNo', $details[0]->Number)
                        ->select('T1.Id')->pluck('Id');
                }
                return Redirect::to('etoolsysadm/getreleasehr?Id='.$detailId.'&module='.$module.'&MainId='.$id.'&RequestLetterNo=' . $details[0]->RequestLetterNo . '&CIDNo=' . $details[0]->Number . "&redirectUrl=etoolsysadm/releasereplacerequestlist");
                break;
            case 201:
                if($module == 'etl'){
                    $detailId = DB::table('etltenderbiddercontractor as T1')
                        ->join('etlcontractorequipment as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.Id')
                        ->whereRaw("((T1.ActualStartDate is not null and T1.ActualStartDate <> '0000-00-00')or (T1.AwardedAmount is not null and coalesce(T1.AwardedAmount,0)>0))")
                        ->where('T1.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T2.RegistrationNo', $details[0]->Number)
                        ->select('T2.Id')->pluck('Id');
                }else{
                    $detailId = DB::table('cinetbidequipment as T1')
                        ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                        ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                        ->where('T2.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T1.RegistrationNo', $details[0]->Number)
                        ->select('T1.Id')->pluck('Id');
                }
                return Redirect::to('etoolsysadm/getreplaceeq?Id='.$detailId.'&module='.$module.'&MainId='.$id.'&RequestLetterNo=' . $details[0]->RequestLetterNo . '&RegistrationNo=' . $details[0]->NewNumber . "&redirectUrl=etoolsysadm/releasereplacerequestlist");
                break;
            case 202:
                if($module == 'etl'){
                    $detailId = DB::table('etltenderbiddercontractor as T1')
                        ->join('etlcontractorequipment as T2', 'T2.EtlTenderBidderContractorId', '=', 'T1.Id')
                        ->whereRaw("((T1.ActualStartDate is not null and T1.ActualStartDate <> '0000-00-00')or (T1.AwardedAmount is not null and coalesce(T1.AwardedAmount,0)>0))")
                        ->where('T1.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T2.RegistrationNo', $details[0]->Number)
                        ->select('T2.Id')->pluck('Id');
                }else{
                    $detailId = DB::table('cinetbidequipment as T1')
                        ->join('crpbiddingform as T2', 'T2.Id', '=', 'T1.CrpBiddingFormId')
                        ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                        ->where('T2.EtlTenderId', $details[0]->EtlTenderId)
                        ->where('T1.RegistrationNo', $details[0]->Number)
                        ->select('T1.Id')->pluck('Id');
                }
                return Redirect::to('etoolsysadm/getreleaseeq?Id='.$detailId.'&module='.$module.'&MainId='.$id.'&RequestLetterNo=' . $details[0]->RequestLetterNo . '&RegistrationNo=' . $details[0]->Number . "&redirectUrl=etoolsysadm/releasereplacerequestlist");
                break;
            default:
                break;
        endswitch;
    }
    public function postRejectRequest($id){
        DB::table('etlreplacereleaserequest')->where('Id',$id)->update(array('RequestStatus'=>2,'EditedBy'=>Auth::user()->Id,'EditedOn'=>date('Y-m-d G:i:s')));
        return Redirect::to('etoolsysadm/releasereplacerequestlist')->with('savedsuccessmessage','<strong>SUCCESS!</strong> Request has been rejected!');
    }
    public function getReport(){
        $parameters = array();
        $type = Input::get('Type');
        $workId = Input::get('WorkId');
        $cdbNo = Input::get('CDBNo');
        $fromDate = Input::has('FromDate')?$this->convertDate(trim(Input::get('FromDate'))):false;
        $toDate = Input::has('ToDate')?$this->convertDate(trim(Input::get('ToDate'))):false;
        $user = Input::get('User');

        $users = DB::table('etlreplacereleasetrack')->get(array(DB::raw("distinct User")));

        $query = "select User,WorkId,CDBNo,HrEqOldId,HrEqNewId,Date,Operation,RefDoc from etlreplacereleasetrack WHERE 1";

        if((bool)$type){
            $query.=" and Operation like '%$type%'";
        }
        if((bool)$workId){
            $query.=" and WorkId = ?";
            array_push($parameters,trim($workId));
        }
        if((bool)$cdbNo){
            $query.=" and CDBNo = ?";
            array_push($parameters,trim($cdbNo));
        }
        if((bool)$user){
            $query.=" and User = ?";
            array_push($parameters,trim($user));
        }
        if((bool)$fromDate){
            $query.=" and Date >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and Date <= ?";
            array_push($parameters,$toDate);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,20,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select($query." order by Date desc".$limitOffsetAppend,$parameters);

        return View::make('report.replacereleasereport')
                ->with('start',$start)
                ->with('noOfPages',$noOfPages)
                ->with('reportData',$reportData)
                ->with('users',$users);

    }
}