<?php
class NewetoolUploadTenderEtool extends EtoolController{

	public function validateEgpTenderId($tenderId = NULL){
        return  DB::select("select count(*)rowCount from etltender where EGPTenderId=".$tenderId);
    }
    

	public function index($tenderId = NULL){
        $auditTrailActionMessage="Viewed upload tender page";
        $tenderSource = 1;
        $savedTenders = array(new TenderModel());
        $tenderAttachments = array(new TenderAttachmentModel());
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name","ReferenceNo"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name','ReferenceNo'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        if((bool)$tenderId){
            $auditTrailActionMessage="Clicked Edit button on list of uploaded tenders to view the details.";
            $savedTenders = DB::table('etltender')
                                ->where('Id','=',$tenderId)
                                ->get(array('Id','ReferenceNo','NameOfWork','WorkId','CmnDzongkhagId','DescriptionOfWork','CmnContractorCategoryId','CmnContractorClassificationId','ContractPeriod','DateOfSaleOfTender','DateOfClosingSaleOfTender','LastDateAndTimeOfSubmission','TenderOpeningDateAndTime','CostOfTender','EMD','ProjectEstimateCost','TentativeStartDate','TentativeEndDate','ContactPerson','ContactNo','ContactEmail','PublishInWebsite','ShowCostInWebsite','TenderSource','EGPTenderId'));
            $tenderAttachments = DB::table('etltenderattachment')->where('EtlTenderId','=',$tenderId)->orderBy('CreatedOn')->get(array('Id','DocumentName','DocumentPath'));
        }
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
		return View::make('new_etool.uploadtender')
            ->with('dzongkhags',$dzongkhags)
            ->with('savedTenders',$savedTenders)
            ->with('tenderAttachments',$tenderAttachments)
            ->with('tenderSource',$tenderSource)
            ->with('contractorCategories',$contractorCategories)
            ->with('contractorClassifications',$contractorClassifications);
    }
    
	public function uploadedList(){
        $auditTrailActionMessage="Viewed list of uploaded tenders";
        $workId = Input::get('WorkId');
        $contractorCategoryId = Input::get('ContractorCategoryId');
        $contractorClassificationId = Input::get('ContractorClassificationId');
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
        $userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');
        $parameters = array($userAgencyId);

        $query = "select distinct T1.Id,T1.Method, T1.ProjectEstimateCost, T1.CmnWorkExecutionStatusId, T1.DateOfClosingSaleOfTender, T1.LastDateAndTimeOfSubmission, T1.TenderOpeningDateAndTime, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod, case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as EtlTenderWorkId from etltender T1 left join etlevaluationscore X on X.EtlTenderId = T1.Id join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId where T1.IsSPRRTender='Y' and T1.CmnProcuringAgencyId= ? and coalesce(T1.DeleteStatus,'N') <> 'Y' and T1.TenderSource = 1";
        $queryForDistinctYears = "select distinct case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end as Year from etltender T1 left join etlevaluationscore X on X.EtlTenderId = T1.Id join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId where   T1.IsSPRRTender='Y' and T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'";

		if((bool)$workId || (bool)$contractorCategoryId || (bool)$contractorClassificationId) {
            if ((bool)$workId) {
                $query .= " and case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = ?";
                $queryForDistinctYears .= " and case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = ?";
                array_push($parameters, $workId);
            }
            if ((bool)$contractorCategoryId) {
                $query .= " and T1.CmnContractorCategoryId = ?";
                $queryForDistinctYears .= " and T1.CmnContractorCategoryId = ?";
                array_push($parameters, $contractorCategoryId);
            }
            if ((bool)$contractorClassificationId) {
                $query .= " and T1.CmnContractorClassificationId = ?";
                $queryForDistinctYears .= " and T1.CmnContractorClassificationId = ?";
                array_push($parameters, $contractorClassificationId);
            }
        }
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
        $distinctYears = DB::select("$queryForDistinctYears and coalesce(T1.CmnWorkExecutionStatusId,0) not in (?,?,?,?) order by case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC",$parameters);
        $uploadedTenders = array();
        $count = 0;
        foreach($distinctYears as $distinctYear):
            if($distinctYear->Year != null):
                $uploadedTenders[$distinctYear->Year] = DB::select("$query and coalesce(T1.CmnWorkExecutionStatusId,0) NOT IN (?,?,?,?) and case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end = '$distinctYear->Year' order by case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC, T1.WorkId DESC",$parameters);
            else:
                unset($distinctYears[$count]);
            endif;
            $count++;
        endforeach;

        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        return View::make('new_etool.uploadedtenderlist')
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
        $nonFileInputs['IsSPRRTender'] = 'Y';

        $id = Input::get('Id');
        $multiAttachments=array();
        $generatedId = $this->UUID();
        $validationObject = new TenderModel();
        if(!$validationObject->validate($nonFileInputs)){
            $errors = $validationObject->errors();
            return Redirect::to('newEtl/uploadtenderetool')->withInput()->withErrors($errors);
        }
        DB::beginTransaction();
        try{
            
            if(empty($nonFileInputs['Id'])){
                
                $nonFileInputs['UploadedDate'] = date('Y-m-d G:i:s');
                $auditTrailActionMessage="Uploaded a tender with Reference No. ".Input::get('ReferenceNo')." and Work Id ".$userProcuringAgency.'/'.date_format(date_create($nonFileInputs['UploadedDate']),'Y').'/'.getWorkId($year,$userProcuringAgencyId);
                $save = true;
                $nonFileInputs['WorkId'] = getWorkId($year,$userProcuringAgencyId);
                $nonFileInputs['Id'] = $generatedId;
                $nonFileInputs['IsSPRRTender'] = 'Y';
                TenderModel::create($nonFileInputs);

               
                
            }else{
                $uploadDate = DB::table('etltender')->where('Id',$nonFileInputs['Id'])->pluck('UploadedDate');
                $auditTrailActionMessage="Edited Tender with Reference No. ".Input::get('ReferenceNo')." and Work Id ".$userProcuringAgency.'/'.date_format(date_create($uploadDate),'Y').'/'.Input::get('WorkId');
                $save = false;
                $nonFileInputs['IsSPRRTender'] = 'Y';
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
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$userProcuringAgency.'/'.date_format(date_create(isset($nonFileInputs['UploadedDate'])?$nonFileInputs['UploadedDate']:$uploadDate),'Y').'/'.getWorkId($year,$userProcuringAgencyId));
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
                    $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Addendum",$person->Email,$fullName);
                }else{
                    $message = "$agency has updated tender(".substr($nonFileInputs['NameOfWork'],0,52).".),check website";
                    $this->sendSms($message,$person->PhoneNo);
                }
            }
        }   

        //SET CRITERIA

        $auditTrailActionMessage="Evaluation Criteria defined for Work Id ".Input::get('HiddenWorkId');
        $etlTenderId = $generatedId;
        $humanResourceInputs = Input::get('etlcriteriahumanresource');
        $EtlContractorHumanResourceInputs = Input::get('EtlContractorHumanResource');
        $equipmentInputs = Input::get('etlcriteriaequipment');
        //$equipmentInputs = Input::get('etlcriteriaequipment');
        
        $inputArray = array();
        $currentTab = Input::get('CurrentTab');
        $currentTabParameter = substr($currentTab,1,strlen($currentTab));
        DB::table('etlcriteriahumanresource')->where('EtlTenderId','=',$etlTenderId)->delete();
        DB::table('etlcriteriaequipment')->where('EtlTenderId','=',$etlTenderId)->delete();
        DB::beginTransaction();
        
        //SINCE EGP IS NOT READY THATS Y COMMENTING HR AND EQUIPMENT CODE, REMOVE $isEGPReady VARIABLE WHEN EGP IS READY
        $isEGPReady =false;
        if($isEGPReady)
        {
            if(count($humanResourceInputs)>0){ 
                $companyName = "";
                foreach($humanResourceInputs as $key=>$value){
                    $inputArray['EtlTenderId'] = $etlTenderId;
                    foreach($value as $x=>$y){
                        $inputArray[$x] = $y;
                    }
                // OLD CODE COMMENTED BY PRAMOD if(!empty($inputArray['EtlTenderId'])){
                    if(!empty($inputArray['CmnDesignationId'])){
                        try{
                            

                        $tierId = DB::table('etltier')->where('Name',$inputArray['EtlTierId'])->pluck('Id');
                        if($tierId=="")
                        {
                            $tierId="0";
                        }
                        $inputArray['EtlTierId'] = $tierId;
                        $des = DB::table('cmnlistitem')->where('Name',$inputArray['CmnDesignationId'])->pluck('Id');
                        if($des!="")
                        {
                            $inputArray['CmnDesignationId'] = $des;
                        }


                            $inputArray['Id'] = $this->UUID();
                            if($companyName=="")
                            {
                                $companyName = $inputArray['companyName'];
                            }
                            if($companyName != $inputArray['companyName'])
                            {
                                break;
                            }
                            CriteriaHumanResourceModel::create($inputArray);
                        }catch(Exception $e){
                            DB::rollback();
                            throw $e;
                        }
                        $inputArray = array();
                    }
                }
            }
            $inputArray = array();
            $companyName = "";
            foreach($equipmentInputs as $key1=>$value1){
                $inputArray['EtlTenderId'] = $etlTenderId;
                foreach($value1 as $x1=>$y1){
                    $inputArray[$x1] = $y1;
                }
                
                //OLD CODE COMMENTED BY PRAMOD if(!empty($inputArray['CmnEquipmentId'])){
                if(!empty($inputArray['CmnEquipmentId'])){
                    try{
                        $tierId = DB::table('etltier')->where('Name',$inputArray['EtlTierId'])->pluck('Id');
                        
                        if($tierId=="")
                        {
                            $tierId="0";
                        }
                        $inputArray['EtlTierId'] = $tierId;
                        $equp = DB::table('cmnequipment')->where('Name',$inputArray['CmnEquipmentId'])->pluck('Id');
                        if($equp!="")
                        {
                            $inputArray['CmnEquipmentId'] = $equp;
                        }

                        $inputArray['Id'] = $this->UUID();
                        if($companyName=="")
                        {
                            $companyName = $inputArray['companyName'];
                        }
                        if($companyName != $inputArray['companyName'])
                        {
                            break;
                        }
                        CriteriaEquipmentModel::create($inputArray);
                    }catch(Exception $e){
                        DB::rollback();
                        throw $e;
                    }
                    $inputArray = array();
                }
            }
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,Input::get('HiddenWorkId'));
        }
        
        
        $commiteeInputs  = Input::get('commiteeMember');
        if(count($commiteeInputs)>0){
            foreach($commiteeInputs as $key=>$value){
                if(gettype($value) == 'array'){
                    $model = $key;
                    $inputArray = array();
                    foreach($value as $x=>$y){
                            $inputArray[$x] = $y;
                    }
                    try{
                        $inputArray['EtlTenderId'] = $etlTenderId;
                        EtlTenderCommitteeModel::create($inputArray);
                    }catch(Exception $e){
                        DB::rollback();
                        throw $e;
                    }
                }
            }
        }



        $contractorInputs = Input::get('Contractor');
        $cdbNo="";
        $companyName = "";
        if(count($contractorInputs)>0){
            foreach($contractorInputs as $key1=>$value1){
                $guid = $this->UUID();
                $parentTableInputs['EtlTenderId'] =$etlTenderId;
                $parentTableInputs['Id'] = $guid;
                foreach($value1 as $x1=>$y1){
                    $parentTableInputs[$x1] = $y1;
                }
                $cdbNo=$parentTableInputs['CDBNo'];
                $contractorId = DB::table('crpcontractorfinal')->where('CDBNo',$parentTableInputs['CDBNo'])->pluck('Id');
                $parentTableInputs['EtlTenderBidderContractorId'] = $contractorId;
                AddContractorModel::create($parentTableInputs);

                DB::table('etltenderbiddercontractordetail')->insert(array("Id"=>$this->UUID(),
                "EtlTenderBidderContractorId"=>$guid,"CrpContractorFinalId"=>$contractorId,
                "CreatedBy"=>Auth::user()->Id,"CreatedOn"=>date('Y-m-d G:i:s')));

            
                DB::table('etlcontractorcapacity')->insert(array("Id"=>$this->UUID(),
                "EtlTenderBidderContractorId"=>$guid,"Sequence"=>'1',
                "CmnBankId"=>'28254fad-a2bf-11e4-b4d2-080027dcfac6',
                "Amount"=>'0',
                "CreatedBy"=>Auth::user()->Id,"CreatedOn"=>date('Y-m-d G:i:s')));

                if($isEGPReady)
                {

                    foreach($humanResourceInputs as $key=>$value){
                        $inputArray['EtlTenderId'] = $etlTenderId;
                        $inputArray['CDBNo'] = $cdbNo;
                        foreach($value as $x=>$y){
                            $inputArray['CDBNo'] = $cdbNo;
                            $inputArray[$x] = $y;
                        }
                        if($companyName=="")
                        {
                            $companyName = $inputArray['companyName'];
                        }
                        if($companyName == $inputArray['companyName'])
                        {
                            if(!empty($inputArray['CmnDesignationId'])){
                                try{
            
                                    $tierId = DB::table('etltier')->where('Name',$inputArray['EtlTierId'])->pluck('Id');
                                    if($tierId!="")
                                    {
                                        $inputArray['EtlTierId'] = '0';
                                        
                                    }
                                    $des = DB::table('cmnlistitem')->where('Name',$inputArray['CmnDesignationId'])->pluck('Id');
                                    if($des!="")
                                    {
                                    $inputArray['CmnDesignationId'] = $des;
                                    }
                                    $inputArray['Id'] = $this->UUID();
                                    $inputArray['EtlTenderBidderContractorId'] = $guid;
                                    EtlContractorHumanResourceModel::create($inputArray);
                                }catch(Exception $e){
                                    DB::rollback();
                                    throw $e;
                                }
                                $inputArray = array();
                            }
                        }
                    }

                    foreach($equipmentInputs as $key1=>$value1){
                        $inputArray['EtlTenderId'] = $etlTenderId;
                        foreach($value1 as $x1=>$y1){
                            $inputArray[$x1] = $y1;
                        }
                        
                        if($companyName=="")
                        {
                            $companyName = $inputArray['companyName'];
                        }
                        if($companyName == $inputArray['companyName'])
                        {
                        //OLD CODE COMMENTED BY PRAMOD if(!empty($inputArray['CmnEquipmentId'])){
                            if(!empty($inputArray['CmnEquipmentId'])){
                                try{
                                    $tierId = DB::table('etltier')->where('Name',$inputArray['EtlTierId'])->pluck('Id');
                                    if($tierId=="")
                                    {
                                        $tierId="0";
                                    }
                                    $inputArray['EtlTierId'] = $tierId;
                                    $equp = DB::table('cmnequipment')->where('Name',$inputArray['CmnEquipmentId'])->pluck('Id');
                                    if($equp!="")
                                    {
                                    $inputArray['CmnEquipmentId'] = $equp;
                                    }

                                    if($inputArray['Owned']=="1")
                                    {
                                        $inputArray['OwnedOrHired']="1";
                                    }
                                    else if($inputArray['Hired']=="1")
                                    {
                                        $inputArray['OwnedOrHired']="2";
                                    }
                                    
                                    $inputArray['Id'] = $this->UUID();
                                    $inputArray['EtlTenderBidderContractorId'] = $guid;
                                    EtlContractorEquipmentModel::create($inputArray);
                                }catch(Exception $e){
                                    DB::rollback();
                                    throw $e;
                                }
                                $inputArray = array();
                            }
                        }
                    }
                }
            }
        }
        DB::commit();
        die($nonFileInputs['IsSPRRTender']);
        if($save){
//            $classification = Input::get('CmnContractorClassificationId');
//            $category = Input::get('CmnContractorCategoryId');
//            $contractors = DB::table('crpcontractorfinal as T1')
//                                ->join('crpcontractorworkclassificationfinal as T2','T1.Id','=','T2.CrpContractorFinalId')
//                                ->where('T2.CmnProjectCategoryId',$category)
//                                ->where('T2.CmnApprovedClassificationId',$classification)
//                                ->whereNotNull('T1.MobileNo')
//                                ->where('T1.MobileNo','<>',"''")
//                                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
//                                ->get(array(DB::raw('distinct T1.Id'),'T1.MobileNo'));
//            foreach($contractors as $contractor):
//                $smsMessage = "A tender with Reference No. ".Input::get('ReferenceNo')." and Work Id ".$userProcuringAgency.'/'.date_format(date_create($nonFileInputs['UploadedDate']),'Y').'/'.getWorkId($year,$userProcuringAgencyId).' has been uploaded';
//                $this->sendSms($smsMessage,$contractor->MobileNo);
//            endforeach;
            return Redirect::to('newEtl/uploadedtenderlistetool')->with('savedsuccessmessage','Etool Tender has been successfully saved.');
        }else{
            return Redirect::to('newEtl/uploadedtenderlistetool')->with('savedsuccessmessage','Etool Tender has been successfully updated.');
        }
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
            } // die('ccc');
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
        return Redirect::to('newEtl/setcriteriaetool/'.$etlTenderId.'?currentTab='.$currentTabParameter.$currentTab)->with('savedsuccessmessage','Criteria has been successfully set.');
    }

    public function postDeleteFile(){
        $auditTrailActionMessage="Edited Uploaded tender";
        $id = Input::get('id');
        $documentPath = DB::table('etltenderattachment')->where('Id','=',$id)->get(array('DocumentPath','EtlTenderId'));
        $tenderDetails=DB::table('etltender as T1')
                            ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                            ->where('T1.Id','=',$documentPath[0]->EtlTenderId)
                            ->get(array('T1.ReferenceNo',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        $auditTrailRemarks="Reference No. ".$tenderDetails[0]->ReferenceNo." and Work Id ".$tenderDetails[0]->WorkId;
        DB::table('etltenderattachment')->where('Id','=',$id)->delete();
        $this->auditTrailEtoolCinet(NULL,$auditTrailRemarks,$auditTrailActionMessage,$tenderDetails[0]->WorkId);
    }
    public function postDelete(){
        $id = Input::get('id');
        $tableName = Input::get('tableName');
        if($tableName == 'etltender'){
            DB::table($tableName)->where('Id',$id)->update(array('DeleteStatus'=>'Y','RemarksForDelete'=>Input::get('remarks')));
        }else{
            DB::table($tableName)->where('Id','=',$id)->delete();
        }
    }
}