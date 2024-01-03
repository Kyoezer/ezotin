<?php
class UploadTenderCiNet extends CiNetController{
	public function index($tenderId = NULL){
        $tenderSource = 2;
        $savedTenders = array(new TenderModel());
        $tenderAttachments = array(new TenderAttachmentModel());
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name","ReferenceNo"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name','ReferenceNo'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        if((bool)$tenderId){
            $savedTenders = DB::table('etltender')->where('Id','=',$tenderId)->get(array('Id','ReferenceNo','NameOfWork','WorkId','CmnDzongkhagId','DescriptionOfWork','CmnContractorCategoryId','CmnContractorClassificationId','ContractPeriod','DateOfSaleOfTender','DateOfClosingSaleOfTender','LastDateAndTimeOfSubmission','TenderOpeningDateAndTime','CostOfTender','EMD','ProjectEstimateCost','TentativeStartDate','TentativeEndDate','ContactPerson','ContactNo','ContactEmail','PublishInWebsite','ShowCostInWebsite','TenderSource'));
            $tenderAttachments = DB::table('etltenderattachment')->where('EtlTenderId','=',$tenderId)->orderBy('CreatedOn')->get(array('Id','DocumentName','DocumentPath'));
        }
        $auditTrailActionMessage="Viewed upload tender page";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
		return View::make('etool.uploadtender')
            ->with('dzongkhags',$dzongkhags)
            ->with('savedTenders',$savedTenders)
            ->with('tenderAttachments',$tenderAttachments)
            ->with('tenderSource',$tenderSource)
            ->with('contractorCategories',$contractorCategories)
            ->with('contractorClassifications',$contractorClassifications);
	}
	public function uploadedList(){
        $workId = Input::get('WorkId');
        $contractorCategoryId = Input::get('ContractorCategoryId');
        $contractorClassificationId = Input::get('ContractorClassificationId');
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
        $parameters = array(Auth::user()->Id);
        $query = "select distinct T1.Id, T1.Method,T1.ProjectEstimateCost, T1.CmnWorkExecutionStatusId, T1.DateOfClosingSaleOfTender, T1.LastDateAndTimeOfSubmission, T1.TenderOpeningDateAndTime, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod, concat(A.Code,'/',DATE_FORMAT(T1.DateOfSaleOfTender,'%Y'),'/',coalesce(T1.WorkId,'')) as EtlTenderWorkId from etltender T1 join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId where B.Id = ? and T1.TenderSource = 2 and coalesce(T1.DeleteStatus,'N') <> 'Y'";
		$queryForDistinctYears = "select distinct case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end as Year from etltender T1 join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId where B.Id = ? and T1.TenderSource = 2 and coalesce(T1.DeleteStatus,'N') <> 'Y'";
        if((bool)$workId || (bool)$contractorCategoryId || (bool)$contractorClassificationId) {
            if ((bool)$workId) {
                $query .= " and concat(A.Code,'/',DATE_FORMAT(T1.DateOfSaleOfTender,'%Y'),'/',T1.WorkId) LIKE '%$workId%'";
            }
            if ((bool)$contractorCategoryId) {
                $query .= " and T1.CmnContractorCategoryId = ?";
                array_push($parameters, $contractorCategoryId);
            }
            if ((bool)$contractorClassificationId) {
                $query .= " and T1.CmnContractorClassificationId = ?";
                array_push($parameters, $contractorClassificationId);
            }
        }
        $query .= " and coalesce(T1.CmnWorkExecutionStatusId,0) <> ? and coalesce(T1.CmnWorkExecutionStatusId,0) <> ? and coalesce(T1.CmnWorkExecutionStatusId,0) <> ? and coalesce(T1.CmnWorkExecutionStatusId,0) <> ?";
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        $distinctYears = DB::select("$queryForDistinctYears and coalesce(T1.CmnWorkExecutionStatusId,0) not in (?,?,?,?) order by year(T1.DateOfSaleOfTender) DESC",$parameters);
        $uploadedTenders = array();
        foreach($distinctYears as $distinctYear):
           
            $uploadedTenders[$distinctYear->Year] = DB::select("$query and year(T1.DateOfSaleOfTender) = '$distinctYear->Year' order by year(T1.DateOfSaleOfTender) DESC, T1.WorkId DESC",$parameters);
        endforeach;
        // $uploadedTenders = DB::select("$query order by year(T1.DateOfSaleOfTender) DESC, T1.WorkId DESC",$parameters);
        $auditTrailActionMessage="Viewed list of uploaded tenders";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        return View::make('etool.uploadedtenderlist')
            ->with('distinctYears',$distinctYears)
            ->with('uploadedTenders',$uploadedTenders)
            ->with('contractorCategories',$contractorCategories)
            ->with('contractorClassifications',$contractorClassifications);
	}
    public function postSaveTender(){
        $userId = Auth::user()->Id;
        $userProcuringAgencyId = DB::table('sysuser as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')->where('T1.Id','=',$userId)->pluck('T2.Id');
        $userProcuringAgency = DB::table('sysuser as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')->where('T1.Id','=',$userId)->pluck('T2.Code');
        $nonFileInputs = Input::except('attachments');
        $year = date_format(date_create($nonFileInputs['DateOfSaleOfTender']),'Y');
        $nonFileInputs['CmnProcuringAgencyId'] = $userProcuringAgencyId;
        $nonFileInputs['DateOfSaleOfTender'] = $this->convertDate($nonFileInputs['DateOfSaleOfTender']);
        $nonFileInputs['DateOfClosingSaleOfTender'] = $this->convertDate($nonFileInputs['DateOfClosingSaleOfTender']);
        $nonFileInputs['LastDateAndTimeOfSubmission'] = $this->convertDateTime($nonFileInputs['LastDateAndTimeOfSubmission']);
        $nonFileInputs['TenderOpeningDateAndTime'] = $this->convertDateTime($nonFileInputs['TenderOpeningDateAndTime']);
        $nonFileInputs['TentativeStartDate'] = $this->convertDateTime($nonFileInputs['TentativeStartDate']);
        $nonFileInputs['TentativeEndDate'] = $this->convertDateTime($nonFileInputs['TentativeEndDate']);
        $nonFileInputs['UploadedDate'] = date('Y-m-d G:i:s');
        $id = Input::get('Id');
        $multiAttachments=array();
        $generatedId = $this->UUID();
        $validationObject = new TenderModel();
        if(!$validationObject->validate($nonFileInputs)){
            $errors = $validationObject->errors();
            return Redirect::to('cinet/uploadtendercinet')->withInput()->withErrors($errors);
        }
        DB::beginTransaction();
        try{
            if(empty($nonFileInputs['Id'])){
                $auditTrailActionMessage="Uploaded a tender with Reference No. ".Input::get('ReferenceNo')." and Work Id ".$userProcuringAgency.'/'.date_format(date_create($nonFileInputs['DateOfSaleOfTender']),'Y').'/'.getWorkId($year,$userProcuringAgencyId,1);
                $save = true;
                $nonFileInputs['WorkId'] = getWorkId($year,$userProcuringAgencyId,1);
                $nonFileInputs['Id'] = $generatedId;
                TenderModel::create($nonFileInputs);
            }else{
                $auditTrailActionMessage="Edited Tender with Reference No. ".Input::get('ReferenceNo')." and Work Id ".$userProcuringAgency.'/'.date_format(date_create($nonFileInputs['DateOfSaleOfTender']),'Y').'/'.Input::get('WorkId');
                $save = false;
                $object = TenderModel::find($nonFileInputs['Id']);
                $object->fill($nonFileInputs);
                $object->update();
            }
            if(Input::hasFile('attachments')){
                DB::table('etltenderattachment')->where('EtlTenderId','=',$nonFileInputs['Id'])->delete();
                $count = 0;
                foreach(Input::file('attachments') as $attachment){
                    $documentName = Input::get("DocumentName");
                    $documentId = Input::get('DocumentId');
                    $attachmentType=$attachment->getMimeType();
                    $attachmentName=$userProcuringAgency."_$year"."_".$nonFileInputs['WorkId'].date('Y_m_d_G_i_s').".".$attachment->getClientOriginalExtension();
                    $destination=public_path().'/uploads';
                    $destinationDB='/uploads/'.$attachmentName;
                    $multiAttachments1["Id"]=$documentId[$count];
                    $multiAttachments1["DocumentName"]=$documentName[$count];
                    $multiAttachments1["DocumentPath"]=$destinationDB;
                    $multiAttachments1["FileType"]=$attachmentType;
                    array_push($multiAttachments, $multiAttachments1);
                    $uploadAttachments=$attachment->move($destination, $attachmentName);
                    $count++;
                }
                foreach($multiAttachments as $k=>$v){
                    if(empty($id)){
                        $multiAttachments[$k]["Id"] = $this->UUID();
                        $multiAttachments[$k]["EtlTenderId"]=$generatedId;
                        $saveUploads=new TenderAttachmentModel($multiAttachments[$k]);
                        $saveUploads->save();
                    }else{
                        $multiAttachments[$k]["EtlTenderId"]=$nonFileInputs['Id'];
                        $saveUploads=new TenderAttachmentModel($multiAttachments[$k]);
                        $saveUploads->save();
                    }
                }
            }
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        }catch(Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit();
        if(!$save){
            $userProcuringAgencyName = DB::table('cmnprocuringagency')->where('Id',$userProcuringAgencyId)->pluck('Name');
            if(is_numeric($userProcuringAgency)){
                $agency = $userProcuringAgencyName;
            }else{
                $agency = $userProcuringAgency;
            }
            $personsDownloadingTender = DB::table('webtenderdownload')->where('TenderId',$nonFileInputs['Id'])->get(array('Email','PhoneNo'));
            $fullName = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('FullName');
            foreach($personsDownloadingTender as $person){
                if($person->Email){
                    $message = "$userProcuringAgencyName has updated the tender <b>".$nonFileInputs['NameOfWork']."</b> Please check the CDB website for more details.";
                    $mailData=array(
                        'mailMessage'=>$message
                    );
                    $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Work Completion Notification",$person->Email,$fullName);
                }else{
                    $message = "$agency has updated tender(".substr($nonFileInputs['NameOfWork'],0,52).".),check website";
                    $this->sendSms($message,$person->PhoneNo);
                }
            }
        }
        if($save){
            return Redirect::to('cinet/uploadedtenderlistcinet')->with('savedsuccessmessage','CiNet Tender has been successfully saved.');
        }else{
            return Redirect::to('cinet/uploadedtenderlistcinet')->with('savedsuccessmessage','CiNet Tender has been successfully updated.');
        }
    }
    public function postDeleteFile(){
        $auditTrailActionMessage="Edited Uploaded tender";
        $id = Input::get('id');
        $documentPath = DB::table('etltenderattachment')->where('Id','=',$id)->get(array('DocumentPath','EtlTenderId'));
        $tenderDetails=DB::table('etltender as T1')
            ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
            ->where('T1.Id','=',$documentPath[0]->EtlTenderId)
            ->get(array('T1.ReferenceNo',DB::raw('concat(T2.Code,"/",year(T1.DateOfSaleOfTender),"/",T1.WorkId) as WorkId')));
        $auditTrailRemarks="Reference No. ".$tenderDetails[0]->ReferenceNo." and Work Id ".$tenderDetails[0]->WorkId;
        DB::table('etltenderattachment')->where('Id','=',$id)->delete();
        $this->auditTrailEtoolCinet(NULL,$auditTrailRemarks,$auditTrailActionMessage);
    }
    public function postDelete(){
        $id = Input::get('id');
        $tableName = Input::get('tableName');
        DB::table($tableName)->where('Id','=',$id)->delete();
    }
}