<?php
class MyContractor extends CrpsController{
	protected $layout = 'horizontalmenumaster'; 
	private $contractorId;
	public function __construct(){
		$this->contractorId=contractorFinalId();
	}
	public function dashBoard(){
		
return Redirect::to('https://www.citizenservices.gov.bt/cdb/login');
$rejectionDetails = array();
		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',4)->where('DisplayIn',2)->orderBy('Date')->get(array('Message','Date'));
		$applicationHistory = DB::table('crpcontractor as T1')
								->join('cmnlistitem as T2','T1.CmnApplicationRegistrationStatusId','=','T2.Id')
								->join('crpcontractorappliedservice as T3','T3.CrpContractorId','=','T1.Id')
								->join('crpservice as T4','T4.Id','=','T3.CmnServiceTypeId')
								->where('T1.CrpContractorId',$this->contractorId)
								->whereRaw('DATEDIFF(NOW(),T1.ApplicationDate) <= 30')
								->orderBy('T1.ApplicationDate','DESC')
								->orderBy('T1.EditedOn','DESC')
								->groupBy('T1.Id')
								->get(array(DB::raw('distinct T1.Id'),'RegistrationVerifiedDate','RegistrationApprovedDate','PaymentApprovedDate','RejectedDate','T1.ReferenceNo','T1.CrpContractorId','T1.RemarksByRejector', 'T1.ApplicationDate','T1.CmnApplicationRegistrationStatusId', 'T2.Name as Status',DB::raw('group_concat(T4.Name SEPARATOR ", ") as Service')));
		foreach($applicationHistory as $application):
			$rejectionDetails = array();
			if($application->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED){
				$contractorDetails=ContractorModel::contractorHardList($application->Id)->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
				/*----------------------Contractor Email Details and New Details------------------*/
				$application->rejectionSysCode=$contractorDetails[0]->SysRejectionCode;
				$application->prefix = 'contractor';
				$application->referenceApplicant = $application->Id;
			}
		endforeach;
		$lastApplication = DB::table('crpcontractor')
								->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)
								->orderBy('EditedOn','DESC')
								->take(1)
								->pluck('Id');
		$route = "contractor/applyservicegeneralinfo";
		$type = 1;
		$this->layout->content=View::make('crps.cmnexternaluserdashboard')
					->with('type',$type)
					->with('lastApplication',$lastApplication)
					->with('route',$route)
					->with('rejectionDetails',$rejectionDetails)
					->with('applicationHistory',$applicationHistory)
					->with('newsAndNotifications',$newsAndNotifications);
	}
	public function myCertificate($contractorId){
		$nonBhutanese = false;
		$contractorName=ContractorHumanResourceFinalModel::where('CrpContractorFinalId',$contractorId)->where('IsPartnerOrOwner',1)->where(DB::raw('coalesce(ShowInCertificate,0)'),1)->limit(1)->pluck('Name');
		$contractorCIDNo=ContractorHumanResourceFinalModel::where('CrpContractorFinalId',$contractorId)->where('IsPartnerOrOwner',1)->where(DB::raw('coalesce(ShowInCertificate,0)'),1)->limit(1)->pluck('CIDNo');
		$info=ContractorFinalModel::contractor($contractorId)->get(array('crpcontractorfinal.NameOfFirm','T1.Id as CountryId','T1.Name as Country','crpcontractorfinal.CDBNo','crpcontractorfinal.RegistrationApprovedDate','crpcontractorfinal.RegistrationExpiryDate','crpcontractorfinal.ApplicationDate','T5.NameEn as Dzongkhag'));
		$initialDate = DB::table('crpcontractor')->where('Id',$contractorId)->pluck('ApplicationDate');
		$contractorCountryId = $info[0]->CountryId;
		if($contractorCountryId != CONST_COUNTRY_BHUTAN){
			$nonBhutanese = true;
		}
		$contractorWorkClassifications=DB::select("select T1.Code,T1.Name as Category,T2.Name as ApprovedClassification from crpcontractorworkclassificationfinal X join cmncontractorclassification T2 on T2.Id=X.CmnApprovedClassificationId and X.CrpContractorFinalId=? right join cmncontractorworkcategory T1 on T1.Id=X.CmnProjectCategoryId",array($contractorId));
		$data['contractorName']=$contractorName;
		$data['nonBhutanese'] = $nonBhutanese;
		$data['contractorCIDNo']=$contractorCIDNo;
		$data['info']=$info;
		$data['InitialDate']=$initialDate;
		$data['contractorWorkClassifications']=$contractorWorkClassifications;
		$pdf = App::make('dompdf');
//		return View::make('printpages.contractorcertificate',$data);
		$pdf->loadView('printpages.contractorcertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function myProfile(){
		$userContractor=1;
		$registrationApprovedForPayment=0;
		$userId=Auth::user()->Id;
		$contractorId=ContractorFinalModel::contractorIdAfterAuth($userId)->pluck('Id');
		$generalInformation=ContractorFinalModel::contractor($contractorId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.Village','crpcontractorfinal.Gewog','crpcontractorfinal.Address','crpcontractorfinal.SurrenderedDate','crpcontractorfinal.SurrenderedRemarks','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.CDBNo','crpcontractorfinal.ApplicationDate','crpcontractorfinal.RegistrationExpiryDate','crpcontractorfinal.NameOfFirm','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.TPN','crpcontractorfinal.TradeLicenseNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','crpcontractorfinal.RegistrationApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag','T4.Name as OwnershipType'));
		$ownerPartnerDetails=ContractorHumanResourceFinalModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$contractorWorkClassifications=ContractorWorkClassificationFinalModel::contractorWorkClassification($contractorId)->select(DB::raw('crpcontractorworkclassificationfinal.Id,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T2.Name as AppliedClassification,T3.Name as VerifiedClassification,T4.Name as ApprovedClassification'))->get();
		$contractorHumanResources=ContractorHumanResourceFinalModel::contractorHumanResource($contractorId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.EditedOn','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$contractorEquipments=ContractorEquipmentFinalModel::contractorEquipment($contractorId)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.EditedOn','crpcontractorequipmentfinal.RegistrationNo','crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name'));
		$contractorTrackrecords = DB::select("select WorkId,CDBNo,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,WorkCompletionDate,WorkStartDate,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where CrpContractorFinalId = ? order by case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end,ProcuringAgency",array($contractorId));

		$contractorHumanResourceAttachments=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractorId)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId as CrpContractorHumanResourceId'));
		$contractorEquipmentAttachments=ContractorEquipmentAttachmentFinalModel::singleContractorEquipmentAllAttachments($contractorId)->get(array('crpcontractorequipmentattachmentfinal.DocumentName','crpcontractorequipmentattachmentfinal.DocumentPath','crpcontractorequipmentattachmentfinal.CrpContractorEquipmentFinalId as CrpContractorEquipmentId'));
		$contractorAttachments=DB::table('crpcontractorattachmentfinal')->where('DocumentName',"Refresher Course Certificate")->where('CrpContractorFinalId',$contractorId)->get(array('DocumentName','DocumentPath'));
		$contractorComments = ContractorCommentAdverseRecordModel::commentList($contractorId)->get(array('Id','Date','Remarks'));
		$contractorAdverseRecords = ContractorCommentAdverseRecordModel::adverseRecordList($contractorId)->get(array('Id','Date','Remarks'));
		$this->layout->content = View::make('crps.contractorinformation')
								->with('contractorAttachments',$contractorAttachments)
								->with('registrationApprovedForPayment',$registrationApprovedForPayment)
								->with('userContractor',$userContractor)
								->with('contractorId',$contractorId)
								->with('generalInformation',$generalInformation)
								->with('ownerPartnerDetails',$ownerPartnerDetails)
								->with('contractorWorkClassifications',$contractorWorkClassifications)
								->with('contractorHumanResources',$contractorHumanResources)
								->with('contractorEquipments',$contractorEquipments)
								->with('contractorTrackrecords',$contractorTrackrecords)
								->with('contractorHumanResourceAttachments',$contractorHumanResourceAttachments)
								->with('contractorEquipmentAttachments',$contractorEquipmentAttachments)
								->with('contractorComments',$contractorComments)
								->with('contractorAdverseRecords',$contractorAdverseRecords);
	}
	public function trackRecords(){
		$generalInformation=ContractorFinalModel::contractor($this->contractorId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.CDBNo','crpcontractorfinal.NameOfFirm','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','crpcontractorfinal.RegistrationApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType'));
		$trackRecords = DB::select("select WorkId,CDBNo,year(WorkStartDate) as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where CrpContractorFinalId = ? order by year(WorkStartDate),ProcuringAgency",array($this->contractorId));
		return View::make('crps.contractortrackrecords')
					->with('contractorId',$this->contractorId)
					->with('generalInformation',$generalInformation)
					->with('trackRecords',$trackRecords);
	}
	public function printTrackRecords(){
		$generalInformations=ContractorFinalModel::contractor($this->contractorId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.CDBNo','crpcontractorfinal.NameOfFirm','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','crpcontractorfinal.RegistrationApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType'));
		$trackRecords = DB::select("select WorkId,CDBNo,year(WorkStartDate) as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where CrpContractorFinalId = ? order by year(WorkStartDate),ProcuringAgency",array($this->contractorId));
		$data['printTitle']="Track Records";
		$data['generalInformation']=$generalInformations;
		$data['trackRecords']=$trackRecords;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.contractorprinttrackrecords',$data)->setPaper('a4')->setOrientation('landscape');
		return $pdf->stream();
	}
	public function applyOtherService(){
		$contractorId=$this->contractorId;
		$hasAuditMemo = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$contractorId)->where('Type',1)->whereRaw("coalesce(Dropped,0)=0")->count();
		if($hasAuditMemo > 0){
			$auditDetail = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$contractorId)->where('Type',1)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(AuditObservation SEPARATOR '<br/>') as Detail"))->pluck('Detail');
			$auditAgency = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$contractorId)->where('Type',1)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(Agency SEPARATOR ', ') as Agency"))->pluck('Agency');
			return Redirect::to('contractor/mydashboard')->with('extramessage',"Your registration services are temporarily suspended for: <br/>$auditDetail<br/>Therefore please follow up with:<br/> $auditAgency");
		}
//		$expiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorId)->pluck('RegistrationExpiryDate');
//		if(date('Y-m-d')>$expiryDate){
//			return Redirect::to('contractor/mydashboard')->with('extramessage','<strong>SORRY! </strong>You cannot apply for other services because your registration has expired. Please apply for Renewal.');
//		}

		return view::make('crps.contractorapplyotherservices')
//					->with('expiryDate',$expiryDate)
					->with('contractorId',$contractorId);
	}
//	public function saveServices(){
//		$postedValues = Input::except('_token');
//		DB::beginTransaction();
//		try{
//			foreach($postedValues as $key=>$value):
//				foreach($value as $x=>$y):
//					$dbArray['Id'] = $this->UUID();
//					$dbArray['CrpContractorId'] = $this->contractorId;
//					$dbArray['CmnServiceTypeId'] = $y;
//					ContractorAppliedServiceModel::create($dbArray);
//				endforeach;
//			endforeach;
//		}catch(Exception $e){
//			throw $e;
//			DB::rollback();
//			return Redirect::to('contractor/applyotherservices')->with('customerrormessage','Something went wrong!');
//		}
//		DB::commit();
//	}
	public function applyRenewal(){
		$hasLateRenewal=false;
		$hasReregistration = false;
		$contractorId=$this->contractorId;
		$hasAuditMemo = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$contractorId)->where('Type',1)->whereRaw("coalesce(Dropped,0)=0")->count();
		if($hasAuditMemo > 0){
			$auditDetail = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$contractorId)->where('Type',1)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(AuditObservation SEPARATOR '<br/>') as Detail"))->pluck('Detail');
			$auditAgency = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$contractorId)->where('Type',1)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(Agency SEPARATOR ', ') as Agency"))->pluck('Agency');
			return Redirect::to('contractor/mydashboard')->with('extramessage',"Your registration services are temporarily suspended for: <br/>$auditDetail<br/>Therefore please follow up with:<br/> $auditAgency");
		}
		$registrationExpiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorId)->pluck('RegistrationExpiryDate');
		$reregistrationDate = DB::table('crpcontractorfinal')->where('Id',$contractorId)->pluck('ReRegistrationDate');
		if((bool)$reregistrationDate){
			if($reregistrationDate>$registrationExpiryDate){
				$hasReregistration = true;
				$registrationExpiryDate = $reregistrationDate;
			}
		}

		$registrationExpiryDate = date_format(date_create($registrationExpiryDate),'Y-m-d');
		$today = date('Y-m-d');
		$dateDiff = date_diff(date_create($today),date_create($registrationExpiryDate));
		$dateDiffInDays = $dateDiff->format('%R%a');
		if((int)$dateDiffInDays > 60){
			return Redirect::to('contractor/mydashboard')->with('customerrormessage','You cannot apply for renewal as your certificate is still active');
		}else{
			$existingRenewal = DB::table('crpcontractorappliedservice as T1')
				->join('crpcontractor as T2','T1.CrpContractorId','=','T2.Id')
				->join('crpcontractorfinal as T3','T3.Id','=','T2.CrpContractorId')
				->where('T3.Id',$contractorId)
				->whereNotNull('T2.CmnApplicationRegistrationStatusId')
				->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
				->select(DB::raw('max(T1.CreatedOn) as Date'))
				->pluck('Date');
			if((bool)$existingRenewal){
				$dateDiffExistingRenewal = date_diff(date_create($existingRenewal),date_create($registrationExpiryDate));
				$dateDiffExistingRenewalInDays = $dateDiffExistingRenewal->format('%a');
				$status = DB::table('crpcontractorappliedservice as T1')
					->join('crpcontractor as T2','T1.CrpContractorId','=','T2.Id')
					->join('crpcontractorfinal as T3','T3.Id','=','T2.CrpContractorId')
					->where('T3.Id',$contractorId)
					->where('T1.CreatedOn',$existingRenewal)
					->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
					->pluck('T2.CmnApplicationRegistrationStatusId');
				if(((int)$dateDiffExistingRenewalInDays <= 30 ) && ($status != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)){
					return Redirect::to('contractor/mydashboard')->with('customerrormessage','You have already applied for renewal');
				}
			}
		}
		$feeStructures=ContractorClassificationModel::classification()->get(array('Code','Name','RenewalFee','RegistrationFee'));
		$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_RENEWAL)->pluck('ContractorValidity');
		if($hasReregistration){
			$hasLateFeeAmount=DB::select("select ReregistrationDate as RegistrationExpiryDate,DATEDIFF(CURDATE(),ReregistrationDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractorfinal where Id=? LIMIT 1",array($contractorId));
		}else{
			$hasLateFeeAmount=DB::select("select RegistrationExpiryDate,DATEDIFF(CURDATE(),RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractorfinal where Id=? LIMIT 1",array($contractorId));
		}

		if((int)$hasLateFeeAmount[0]->PenaltyNoOfDays>0){
			$hasLateRenewal=true;
		}
		$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFInalId',$contractorId)->pluck('MaxClassificationPriority');
		return View::make('crps.contractorapplyrenewal')
					->with('maxClassification',$maxClassification)
					->with('hasLateRenewal',$hasLateRenewal)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('contractorId',$contractorId)
					->with('feeStructures',$feeStructures)
					->with('registrationValidityYears',$registrationValidityYears);
	}
	public function applyCancellation(){
		$contractorId=$this->contractorId;
		$applicationNo=$this->tableTransactionNo('ContractorCancelCertificateModel','ReferenceNo');
		$hasAlreadyRequestedForCancellation=ContractorCancelCertificateModel::where('CrpContractorFinalId',$contractorId)->where('CmnApplicationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
		return View::make('crps.contractorapplycancellation')
					->with('applicationNo',$applicationNo)
					->with('contractorId',$contractorId)
					->with('hasAlreadyRequestedForCancellation',$hasAlreadyRequestedForCancellation);
	}
	public function saveCancellation(){
		$postedValues=Input::all();
		$object = new ContractorCancelCertificateModel();
		if(!$object->validate($postedValues)){
			return Redirect::to('contractor/applycancellation')->withErrors($object->errors());
		}
		$file = Input::file('Attachment');
		$name = $file->getClientOriginalName();
		$savedName = randomString().'_'.$name;
		$postedValues['AttachmentFilePath'] = "uploads/contractors/".$savedName;
		$file->move('uploads/contractors',$savedName);
		ContractorCancelCertificateModel::create($postedValues);
		return Redirect::to('contractor/profile')->with('savedsuccessmessage','Your Cancellation request was successfully sent.');
	}
	public function applyServiceGeneralInformation($contractorId){
		$hasExpired = false;
		$contractorFinalTableId=contractorFinalId();

		$expiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('RegistrationExpiryDate');
		if(date('Y-m-d')>$expiryDate){
			$hasExpired = true;
		}

		$redirectUrl=Input::get('redirectUrl');
		$isRenewalService=Input::get('srenew');
		$serviceByContractor=1;
		$newGeneralInfoSave=1;
		$isRejectedApp = 0;
		$isServiceByContractor=$contractorId;
		$postRouteReference='contractor/mcontractorgeneralinfo';
		$refreshersCourseCertificate = '';
		if(!Input::has('confedit') && !Input::has('edit')){
			$countId=ContractorFinalModel::contractorHardList($contractorFinalTableId)->count();
			if($countId==1){
				$contractorGeneralInfo=ContractorFinalModel::contractorHardList($contractorFinalTableId)->get(array(DB::raw("NULL as LocationChangeReason"),'NameOfFirm','CmnRegisteredDzongkhagId','RegisteredAddress','Gewog','Village','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','CmnOwnershipTypeId','TPN','TradeLicenseNo'));
				$contractorPartnerDetails=ContractorHumanResourceFinalModel::contractorPartnerHardList($contractorFinalTableId)->get(array('CIDNo','Name','Sex','ShowInCertificate','CmnCountryId','CmnSalutationId','JoiningDate','CmnDesignationId'));
			}else{
				App::abort('404');
			}
		}else{
			if(Input::has('oldapplicationid')){
				$isRejectedApp = 1;
				$contractorId = Input::get('oldapplicationid'); //HAS REAPPLIED
				DB::table('crpcontractor')->where('Id',$contractorId)->update(array('ApplicationDate'=>date('Y-m-d')));
				$refreshersCourseCertificate = DB::table('crpcontractorattachment')->where('CrpContractorId',$contractorId)->where('DocumentName',"Refresher Course Certificate")->pluck('DocumentPath');
			}
			$isServiceByContractor=$contractorFinalTableId;
			$contractorGeneralInfo=ContractorModel::contractorHardList($contractorId)->get(array('Id','LocationChangeReason','ReferenceNo','ApplicationDate','NameOfFirm','CmnRegisteredDzongkhagId','RegisteredAddress','Gewog','Village','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','CmnOwnershipTypeId','TPN','TradeLicenseNo'));
			$contractorPartnerDetails=ContractorHumanResourceModel::contractorPartnerHardList($contractorId)->get(array('Id','CrpContractorId','CIDNo','JoiningDate','Name','Sex','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('ContractorModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Name','Nationality'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name','ReferenceNo'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$ownershipTypes=CmnListItemModel::ownershipType()->get(array('Id','ReferenceNo','Name'));
		$this->layout->content=View::make('crps.contractoreditgeneralinfo')
					->with('refreshersCourseCertificate',$refreshersCourseCertificate)
					->with('isRejectedApp',$isRejectedApp)
					->with('isRenewalService',$isRenewalService)
					->with('hasExpired',$hasExpired)
					->with('isEdit',$contractorId)
					->with('isServiceByContractor',$isServiceByContractor)
					->with('newGeneralInfoSave',$newGeneralInfoSave)
					->with('redirectUrl',$redirectUrl)
					->with('serviceByContractor',$serviceByContractor)
					->with('postRouteReference',$postRouteReference)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('contractorGeneralInfo',$contractorGeneralInfo)
					->with('contractorPartnerDetails',$contractorPartnerDetails)
					->with('countries',$country)
					->with('dzongkhags',$dzongkhag)
					->with('designations',$designation)
					->with('salutations',$salutation)
					->with('ownershipTypes',$ownershipTypes);
	}
	public function applyServiceWorkClassification($contractorId){
		$serviceByContractor=1;
		$redirectUrl=Input::get('redirectUrl');
		if(!empty($redirectUrl)){
			$redirectUrl=Input::get('redirectUrl');
		}else{	
			$redirectUrl='contractor/applyservicehumanresource';
		}
		$contractorIdFinal=contractorFinalId();
		$classes=ContractorClassificationModel::classification()->select(DB::raw('Id,Name,coalesce(ReferenceNo,999999) as ReferenceNo'))->get();
		if(!Input::has('confedit') && !Input::has('edit')){
			$countId=ContractorFinalModel::contractorHardList($contractorIdFinal)->count();
			if($countId==1){
				$projectCategory=DB::select("select T1.Id,concat(coalesce(T1.Code,''),'-',T1.Name) as Name,coalesce(T1.ReferenceNo,999999) as ReferenceNo,T2.CmnApprovedClassificationId as CmnAppliedClassificationId from cmncontractorworkcategory T1 left join crpcontractorworkclassificationfinal T2 on T1.Id=T2.CmnProjectCategoryId and T2.CrpContractorFinalId=? order by T1.Code,T1.Name",array($contractorIdFinal));
			}else{
				App::abort('404');
			}
		}else{
			$projectCategory=DB::select("select T1.Id,concat(coalesce(T1.Code,''),'-',T1.Name) as Name,coalesce(T1.ReferenceNo,999999) as ReferenceNo,T2.CmnAppliedClassificationId from cmncontractorworkcategory T1 left join crpcontractorworkclassification T2 on T1.Id=T2.CmnProjectCategoryId and T2.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
		}
		$this->layout->content=View::make('crps.contractoreditworkclassification')
					->with('serviceByContractor',$serviceByContractor)
					->with('redirectUrl',$redirectUrl)
					->with('contractorId',$contractorId)
					->with('isEdit',$contractorId)
					->with('classes',$classes)
					->with('projectCategories',$projectCategory);
	}
	public function applyServiceHumanResource($contractorId){
		$contractorFinalTableId=contractorFinalId();
		$isEdit=$contractorId;
		$contractorId=ContractorModel::contractorHardList($contractorId)->pluck('CrpContractorId');
		$serviceByContractor=1;
		$newHumanResourceSave=1;
		$serviceAvailCancel=1;
		$humanResourceEditRoute='contractor/applyservicehumanresource';
		$editPage='contractor/applyservicehumanresource';
		$redirectUrl=Input::get('redirectUrl');
		if(!empty($redirectUrl)){
			$redirectUrl=Input::get('redirectUrl');
		}else{	
			$redirectUrl='contractor/applyserviceequipment';
		}
		$humanResourceEdit=array(new ContractorHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceEditFinalAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEdit=ContractorHumanResourceFinalModel::contractorHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','JoiningDate','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','CmnServiceTypeId','CmnCountryId'));
			$humanResourceEditFinalAttachments=ContractorHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			if(count($humanResourceEdit) == 0 || Input::has('initial')){
				$humanResourceEdit=ContractorHumanResourceModel::contractorHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','JoiningDate','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','CmnServiceTypeId','CmnCountryId'));
			}
			$humanResourceEditAttachments=ContractorHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));

		}
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->get(array('Id','Name'));
		$serviceTypes=CmnListItemModel::serviceType()->get(array('Id','Name'));
		$contractorHumanResources=ContractorHumanResourceModel::contractorHumanResource($isEdit)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpcontractorhumanresource.JoiningDate','crpcontractorhumanresource.Sex','crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country','T6.Name as ServiceType'));
		$humanResourcesAttachments=ContractorHumanResourceModel::humanResourceAttachments($isEdit)->get(array('T1.Id','T1.CrpContractorHumanResourceId','T1.DocumentName','T1.DocumentPath'));
  		$contractorHumanResourcesFinal=ContractorHumanResourceFinalModel::contractorHumanResource($contractorFinalTableId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.Name',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country','T6.Name as ServiceType'));
  		$humanResourcesAttachmentsFinal=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractorFinalTableId)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId'));

		$nextUrl = "contractor/applyserviceconfirmation/";
		$servicesAppliedByContractor = DB::table('crpcontractorappliedservice')->where('CrpContractorId',$isEdit)->lists('CmnServiceTypeId');
		if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByContractor)):
			$nextUrl = 'contractor/applyserviceequipment/';
		endif;
		if(Input::get('redirectUrl')!='' && Input::get('redirectUrl')!='contractor/applyserviceequipment' && Input::get('redirectUrl')!='contractor/applyservicehumanresource'){
			$nextUrl = "$redirectUrl/";
		}
		$this->layout->content=View::make('crps.contractoredithumanresource')
					->with('nextUrl',$nextUrl)
  					->with('serviceTypes',$serviceTypes)
  					->with('serviceByContractor',$serviceByContractor)
  					->with('serviceAvailCancel',$serviceAvailCancel)
  					->with('newHumanResourceSave',$newHumanResourceSave)
  					->with('humanResourceEditRoute',$humanResourceEditRoute)
  					->with('editPage',$editPage)
  					->with('redirectUrl',$redirectUrl)
  					->with('isEdit',$isEdit)
  					->with('contractorId',$isEdit)
  					->with('countries',$country)
  					->with('salutations',$salutation)
  					->with('qualifications',$qualification)
  					->with('designations',$designation)
					->with('trades',$trades)
					->with('contractorHumanResources',$contractorHumanResources)
					->with('humanResourcesAttachments',$humanResourcesAttachments)
					->with('contractorHumanResourcesFinal',$contractorHumanResourcesFinal)
					->with('humanResourcesAttachmentsFinal',$humanResourcesAttachmentsFinal)
					->with('humanResourceEdit',$humanResourceEdit)
					->with('humanResourceEditFinalAttachments',$humanResourceEditFinalAttachments)
					->with('humanResourceEditAttachments',$humanResourceEditAttachments);
	}
	public function applyServiceEquipmentRegistration($contractorId){
		$contractorFinalTableId=contractorFinalId();
		$serviceByContractor=1;
		$newEquipmentSave=1;
		$equipmentEditRoute='contractor/applyserviceequipmentedit';
		$editPage='contractor/applyserviceequipment';
		$redirectUrl=Input::get('redirectUrl');
		if(!empty($redirectUrl)){
			$redirectUrl=Input::get('redirectUrl');
		}else{	
			$redirectUrl='contractor/applyserviceconfirmation';
		}
		$equipmentEdit=array(new ContractorHumanResourceModel());
		$equipmentAttachments=array();
		$equipmentFinalAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			$equipmentEdit=ContractorEquipmentFinalModel::ContractorEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
			$equipmentFinalAttachments=ContractorEquipmentAttachmentFinalModel::EquipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			if(count($equipmentEdit) == 0 || Input::has('initial')){
				$equipmentEdit=ContractorEquipmentModel::ContractorEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));

			}
			$equipmentAttachments=ContractorEquipmentAttachmentModel::EquipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
		}
		$equipments=CmnEquipmentModel::equipment()->get(array('Id','Name','Code','IsRegistered'));
		$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
		$equipmentsAttachments=ContractorEquipmentModel::equipmentAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorEquipmentId','T1.DocumentName','T1.DocumentPath'));
		$contractorEquipmentsFinal=ContractorEquipmentFinalModel::contractorEquipment($contractorFinalTableId)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.RegistrationNo','crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name'));
		$contractorEquipmentAttachmentsFinal=ContractorEquipmentAttachmentFinalModel::singleContractorEquipmentAllAttachments($contractorFinalTableId)->get(array('crpcontractorequipmentattachmentfinal.DocumentName','crpcontractorequipmentattachmentfinal.DocumentPath','crpcontractorequipmentattachmentfinal.CrpContractorEquipmentFinalId'));
		$this->layout->content=View::make('crps.contractoreditequipment')
					->with('serviceByContractor',$serviceByContractor)
					->with('newEquipmentSave',$newEquipmentSave)
					->with('equipmentEditRoute',$equipmentEditRoute)
					->with('redirectUrl',$redirectUrl)
					->with('editPage',$editPage)
					->with('isEdit',$contractorId)
					->with('contractorId',$contractorId)
					->with('equipments',$equipments)
					->with('contractorEquipments',$contractorEquipments)
					->with('equipmentsAttachments',$equipmentsAttachments)
					->with('contractorEquipmentsFinal',$contractorEquipmentsFinal)
					->with('contractorEquipmentAttachmentsFinal',$contractorEquipmentAttachmentsFinal)
					->with('equipmentEdit',$equipmentEdit)
					->with('equipmentFinalAttachments',$equipmentFinalAttachments)
					->with('equipmentAttachments',$equipmentAttachments);
	}
	public function applyServiceConfirmation($contractorId){
		$generalInformation=ContractorModel::contractor($contractorId)->get(array('crpcontractor.Id','crpcontractor.NameOfFirm','crpcontractor.Address','crpcontractor.Email','crpcontractor.TelephoneNo','crpcontractor.MobileNo','crpcontractor.FaxNo','T1.Name as Country','T2.NameEn as Dzongkhag'));
		$ownerPartnerDetails=ContractorHumanResourceModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Name','crpcontractorhumanresource.Sex','crpcontractorhumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$appliedWorkClassifications=ContractorWorkClassificationModel::contractorWorkClassification($contractorId)->get(array('crpcontractorworkclassification.Id','T1.Code','T1.Name as Category','T2.Name as Classification'));
		$contractorHumanResources=ContractorHumanResourceModel::ContractorHumanResource($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
		$humanResourcesAttachments=ContractorHumanResourceModel::humanResourceAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		$equipmentsAttachments=ContractorEquipmentModel::equipmentAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorEquipmentId','T1.DocumentName','T1.DocumentPath'));
		$servicesApplied = DB::table('crpcontractorappliedservice')->where('CrpContractorId',$contractorId)->lists("CmnServiceTypeId");

		return View::make('crps.contractorapplyserviceconfirmation')
					->with('contractorId',$contractorId)
					->with('generalInformation',$generalInformation)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('appliedWorkClassifications',$appliedWorkClassifications)
					->with('contractorHumanResources',$contractorHumanResources)
					->with('contractorEquipments',$contractorEquipments)
					->with('humanResourcesAttachments',$humanResourcesAttachments)
					->with('servicesApplied',$servicesApplied)
					->with('equipmentsAttachments',$equipmentsAttachments);
	}
	public function saveConfirmation(){
		$contractorId=Input::get('ContractorId');
//		$contractor = ContractorModel::find($contractorId);
//		$contractor->RegistrationStatus=1;
//		$contractor->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
//		$contractor->save();
		DB::table('crpcontractor')->where('Id',$contractorId)->update(array('EditedOn'=>date('Y-m-d'),'RegistrationStatus'=>1,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW));
		$contractorDetails=ContractorModel::contractorHardList($contractorId)->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate'));
		$mailView="emails.crps.mailregistrationsuccess";
		$subject="Acknowledgement: Receipt of Application for CDB Service";
		$recipientAddress=$contractorDetails[0]->Email;
		$recipientName=$contractorDetails[0]->NameOfFirm;
		$referenceNo=$contractorDetails[0]->ReferenceNo;
		$applicationDate=$contractorDetails[0]->ApplicationDate;
		$mailIntendedTo=1;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
//		$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
		/* FOR FEE STRUCTURE */
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$contractorFinalTableId=contractorModelContractorId($contractorId);
		$appliedServices=ContractorAppliedServiceModel::appliedService($contractorId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));
		/* END FOR FEE STRUCTURE */
		/* FOR FEE STRUCTURE */
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				$hasFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
				$hasRenewal=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
				$hasLateFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
				$hasChangeInCategoryClassification=true;
			}
		}
		if($hasRenewal || $hasChangeInCategoryClassification){
			$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($contractorId,$contractorFinalTableId,$contractorId,$contractorId,$contractorId,$contractorFinalTableId));
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($contractorId));
		}
		/* END FOR FEE STRUCTURE */

		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'applicationType' => 2,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'appliedServices'=>$appliedServices,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'mailMessage'=>"This is to acknowledge receipt of your application for Construction Development Board (CDB)  service. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.",
		);
//		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
//        $this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('contractor/profile')->with('savedsuccessmessage','Your application was successfully submitted');
	}
	public function editBasicInfo(){
		$postedValues = Input::except('CrpContractorFinalId');
		$id = Input::get('CrpContractorFinalId');
		try{
			$instance = ContractorFinalModel::find($id);
			$instance->fill($postedValues);
			$instance->update();
		}catch(Exception $e){
			return Redirect::to('contractor/profile')->with('customerrormessage',$e->getMessage());
		}

		return Redirect::to('contractor/profile')->with('savedsuccessmessage','Your profile has been updated');

	}
}
