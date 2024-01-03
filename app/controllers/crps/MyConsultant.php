<?php
class MyConsultant extends CrpsController{
	protected $layout = 'horizontalmenumaster';
	private $consultantId;
	public function __construct(){
		$this->consultantId=consultantFinalId();
	}
	public function dashBoard(){
return Redirect::to('https://www.citizenservices.gov.bt/cdb/login');

		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',5)->where('DisplayIn',2)->orderBy('Date')->get(array('Message','Date'));
		$applicationHistory = DB::table('crpconsultant as T1')
			->join('cmnlistitem as T2','T1.CmnApplicationRegistrationStatusId','=','T2.Id')
			->join('crpconsultantappliedservice as T3','T3.CrpConsultantId','=','T1.Id')
			->join('crpservice as T4','T4.Id','=','T3.CmnServiceTypeId')
			->where('T1.CrpConsultantId',$this->consultantId)
			->whereRaw('DATEDIFF(NOW(),T1.ApplicationDate) <= 30')
			->orderBy('T1.ApplicationDate','DESC')
			->groupBy('T1.Id')
			->get(array(DB::raw('distinct T1.Id'),'T1.SysRejectionCode',DB::raw("'consultant' as prefix"),DB::raw('VerifiedDate as RegistrationVerifiedDate'),'RegistrationApprovedDate','PaymentApprovedDate','RejectedDate','T1.ReferenceNo','T1.RemarksByRejector','T1.ApplicationDate','T1.CmnApplicationRegistrationStatusId', 'T2.Name as Status',DB::raw('group_concat(T4.Name SEPARATOR ", ") as Service')));
		$this->layout->content=View::make('crps.cmnexternaluserdashboard')
					->with('applicationHistory',$applicationHistory)
					->with('type',2)
					->with('newsAndNotifications',$newsAndNotifications);
	}
	public function myCertificate($consultantId){
		$consultantName=ConsultantHumanResourceFinalModel::where('CrpConsultantFinalId',$consultantId)->where('ShowInCertificate',1)->pluck('Name');
		$consultantCIDNo=ConsultantHumanResourceFinalModel::where('CrpConsultantFinalId',$consultantId)->where('ShowInCertificate',1)->pluck('CIDNo');
		$info=ConsultantFinalModel::consultant($consultantId)->get(array('crpconsultantfinal.NameOfFirm','crpconsultantfinal.CDBNo','crpconsultantfinal.RegistrationApprovedDate','crpconsultantfinal.RegistrationExpiryDate','crpconsultantfinal.ApplicationDate','T2.NameEn as Dzongkhag'));
		$services=DB::select("select group_concat(concat(T1.Name,' (',T1.Code,')') SEPARATOR ', ') as Service from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnApprovedServiceId and T2.CrpConsultantFinalId=? where T2.CmnApprovedServiceId is not null",array($consultantId));
		$initialDate = DB::table('crpconsultant')->where('Id',$consultantId)->pluck('ApplicationDate');
//		$electricalEngineeringServices=DB::select("select T1.Code,T1.Name,T2.CmnApprovedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnApprovedServiceId and T2.CrpConsultantFinalId=? where X.ReferenceNo=? and T2.CmnApprovedServiceId is not null",array($consultantId,3));
//		$architecturalServices=DB::select("select T1.Code,T1.Name,T2.CmnApprovedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnApprovedServiceId and T2.CrpConsultantFinalId=? where X.ReferenceNo=? and T2.CmnApprovedServiceId is not null",array($consultantId,1));
		$services = DB::table('cmnconsultantservicecategory as X')
						->join('cmnconsultantservice as T1','T1.CmnConsultantServiceCategoryId','=','X.Id')
						->leftJoin('crpconsultantworkclassificationfinal as T2','T2.CmnApprovedServiceId','=','T1.Id')
						->where('T2.CrpConsultantFinalId',$consultantId)
						->whereNotNull('T2.CmnApprovedServiceId')
						->select('T1.Code as Service')
						->lists('Service');
		$serviceCategories = DB::table('cmnconsultantservicecategory')
								->orderBy('DisplayOrder')
								->get(array('Id','Code','Name'));
		foreach($serviceCategories as $serviceCategory):
			$consultantServices[$serviceCategory->Id] = DB::table('cmnconsultantservice')
														->where('CmnConsultantServiceCategoryId',$serviceCategory->Id)
														->orderBy('Code')
														->get(array('Code','Name'));
		endforeach;
		$data['consultantName']=$consultantName;
		$data['consultantCIDNo']=$consultantCIDNo;
		$data['info']=$info;
		$data['services']=$services;
		$data['InitialDate']=$initialDate;
		$data['serviceCategories'] =$serviceCategories;
		$data['consultantServices'] = $consultantServices;
		$data['services'] = $services;
		$pdf = App::make('dompdf');
//		return View::make('printpages.consultantcertificate',$data);
		$pdf->loadView('printpages.consultantcertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
		/*return View::make('printpages.consultantcertificate')
					->with('consultantName',$consultantName)
					->with('info',$info)
					->with('civilEngineeringServices',$civilEngineeringServices)
					->with('electricalEngineeringServices',$electricalEngineeringServices)
					->with('architecturalServices',$architecturalServices);*/
	}
	public function myProfile(){
		$userConsultant=1;
		$registrationApprovedForPayment=0;
		$consultantId=$this->consultantId;
		$generalInformation=ConsultantFinalModel::consultant($consultantId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.Address','crpconsultantfinal.RegisteredAddress','crpconsultantfinal.RegisteredAddress','crpconsultantfinal.Gewog','crpconsultantfinal.Village','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','crpconsultantfinal.RegistrationApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType'));
		$ownerPartnerDetails=ConsultantHumanResourceFinalModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResources=ConsultantHumanResourceFinalModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$consultantEquipments=ConsultantEquipmentFinalModel::consultantEquipment($consultantId)->get(array('crpconsultantequipmentfinal.Id','crpconsultantequipmentfinal.RegistrationNo','crpconsultantequipmentfinal.ModelNo','crpconsultantequipmentfinal.Quantity','T1.Name'));
		$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecords($consultantId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Code as ProjectCategory','T4.Name as classification','T5.NameEn as Dzongkhag'));
		$consultantHumanResourceAttachments=ConsultantHumanResourceAttachmentFinalModel::singleConsultantHumanResourceAllAttachments($consultantId)->get(array('crpconsultanthumanresourceattachmentfinal.DocumentName','crpconsultanthumanresourceattachmentfinal.DocumentPath','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId as CrpConsultantHumanResourceId'));
		$consultantEquipmentAttachments=ConsultantEquipmentAttachmentFinalModel::singleConsultantEquipmentAllAttachments($consultantId)->get(array('crpconsultantequipmentattachmentfinal.DocumentName','crpconsultantequipmentattachmentfinal.DocumentPath','crpconsultantequipmentattachmentfinal.CrpConsultantEquipmentFinalId as CrpConsultantEquipmentId'));
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		$verifiedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		$consultantComments = ConsultantCommentAdverseRecordModel::commentList($consultantId)->get(array('Id','Date','Remarks'));
		$consultantAdverseRecords = ConsultantCommentAdverseRecordModel::adverseRecordList($consultantId)->get(array('Id','Date','Remarks'));
//		echo "<pre>"; dd($generalInformation);
		$this->layout->content=View::make('crps.consultantinformation')
									->with('registrationApprovedForPayment',$registrationApprovedForPayment)
									->with('userConsultant',$userConsultant)
									->with('serviceCategories',$serviceCategories)
									->with('appliedCategoryServices',$appliedCategoryServices)
									->with('verifiedCategoryServices',$verifiedCategoryServices)
									->with('approvedCategoryServices',$approvedCategoryServices)
									->with('consultantId',$consultantId)
									->with('generalInformation',$generalInformation)
									->with('ownerPartnerDetails',$ownerPartnerDetails)
									->with('consultantHumanResources',$consultantHumanResources)
									->with('consultantEquipments',$consultantEquipments)
									->with('consultantTrackrecords',$consultantTrackrecords)
									->with('consultantHumanResourceAttachments',$consultantHumanResourceAttachments)
									->with('consultantComments',$consultantComments)
									->with('consultantAdverseRecords',$consultantAdverseRecords)
									->with('consultantEquipmentAttachments',$consultantEquipmentAttachments);

	}
	public function trackRecords(){
		$generalInformation=ConsultantFinalModel::consultant($this->consultantId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','crpconsultantfinal.RegistrationApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType'));
		$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecordsAll($this->consultantId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Code as ProjectCategory','T4.Name as classification','T5.NameEn as Dzongkhag','T6.Name as CurrentWorkStatus'));
		return View::make('crps.consultanttrackrecords')
					->with('generalInformation',$generalInformation)
					->with('consultantTrackrecords',$consultantTrackrecords);
	}
	public function printTrackRecords(){
		$generalInformation=ConsultantFinalModel::consultant($this->consultantId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','crpconsultantfinal.RegistrationApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType'));
		$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecordsAll($this->consultantId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Code as ProjectCategory','T4.Name as classification','T5.NameEn as Dzongkhag','T6.Name as CurrentWorkStatus'));
		$data['printTitle']="Track Records";
		$data['generalInformation']=$generalInformation;
		$data['consultantTrackrecords']=$consultantTrackrecords;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.consultantprinttrackrecords',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function applyOtherService(){
		$consultantId=$this->consultantId;
		$hasAuditMemo = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$consultantId)->where('Type',2)->whereRaw("coalesce(Dropped,0)=0")->count();
		if($hasAuditMemo > 0){
			$auditDetail = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$consultantId)->where('Type',2)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(AuditObservation SEPARATOR '<br/>') as Detail"))->pluck('Detail');
			$auditAgency = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$consultantId)->where('Type',2)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(Agency SEPARATOR ', ') as Agency"))->pluck('Agency');
			return Redirect::to('consultant/mydashboard')->with('extramessage',"Your registration services are temporarily suspended for: <br/>$auditDetail<br/>Therefore please follow up with:<br/> $auditAgency");
		}
		$feeStructures=DB::select('select Id,Name,ConsultantAmount,ConsultantValidity from crpservice where Id not in(?,?,?) order by Name',array(CONST_SERVICETYPE_NEW,CONST_SERVICETYPE_RENEWAL,CONST_SERVICETYPE_CANCELREGISTRATION));
		$consultantId=$this->consultantId;
		return view::make('crps.consultantapplyotherservices')
					->with('consultantId',$consultantId)
					->with('feeStructures',$feeStructures);
	}
	public function applyRenewal(){
		$hasLateRenewal=false;
		$consultantId=$this->consultantId;
		$hasAuditMemo = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$consultantId)->where('Type',2)->whereRaw("coalesce(Dropped,0)=0")->count();
		if($hasAuditMemo > 0){
			$auditDetail = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$consultantId)->where('Type',2)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(AuditObservation SEPARATOR '<br/>') as Detail"))->pluck('Detail');
			$auditAgency = DB::table('crpcontractorauditclearance')->where('CrpContractorConsultantId',$consultantId)->where('Type',2)->whereRaw("coalesce(Dropped,0)=0")->select(DB::raw("GROUP_CONCAT(Agency SEPARATOR ', ') as Agency"))->pluck('Agency');
			return Redirect::to('consultant/mydashboard')->with('extramessage',"Your registration services are temporarily suspended for: <br/>$auditDetail<br/>Therefore please follow up with:<br/> $auditAgency");
		}
		$registrationExpiryDate = DB::table('crpconsultantfinal')->where('Id',$consultantId)->pluck('RegistrationExpiryDate');
		$registrationExpiryDate = date_format(date_create($registrationExpiryDate),'Y-m-d');
		$today = date('Y-m-d');
		$dateDiff = date_diff(date_create($today),date_create($registrationExpiryDate));
		$dateDiffInDays = $dateDiff->format('%R%a');
		if((int)$dateDiffInDays > 60){
			return Redirect::to('consultant/mydashboard')->with('customerrormessage','You cannot apply for renewal as your certificate is still active');
		}else{
			$existingRenewal = DB::table('crpconsultantappliedservice as T1')
				->join('crpconsultant as T2','T1.CrpConsultantId','=','T2.Id')
				->join('crpconsultantfinal as T3','T3.Id','=','T2.CrpConsultantId')
				->whereNotNull('T2.CmnApplicationRegistrationStatusId')
				->where('T3.Id',$consultantId)
				->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
				->select(DB::raw('max(T1.CreatedOn) as Date'))
				->pluck('Date');
			if((bool)$existingRenewal){
				$dateDiffExistingRenewal = date_diff(date_create($existingRenewal),date_create($registrationExpiryDate));
				$dateDiffExistingRenewalInDays = $dateDiffExistingRenewal->format('%a');
				$status = DB::table('crpconsultantappliedservice as T1')
					->join('crpconsultant as T2','T1.CrpConsultantId','=','T2.Id')
					->join('crpconsultantfinal as T3','T3.Id','=','T2.CrpConsultantId')
					->where('T3.Id',$consultantId)
					->where('T1.CreatedOn',$existingRenewal)
					->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
					->pluck('T2.CmnApplicationRegistrationStatusId');
				if(((int)$dateDiffExistingRenewalInDays <= 30 ) && ($status != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)){
					return Redirect::to('consultant/mydashboard')->with('customerrormessage','You have already applied for renewal');
				}
			}
		}
		$feeStructures=DB::select('select Id,Name,ConsultantAmount,ConsultantValidity from crpservice where Id not in(?,?) order by Name',array(CONST_SERVICETYPE_NEW,CONST_SERVICETYPE_CANCELREGISTRATION));
		$hasLateFeeAmount=DB::select("select RegistrationExpiryDate,DATEDIFF(CURDATE(),RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultantfinal where Id=? LIMIT 1",array($consultantId));
		if((int)$hasLateFeeAmount[0]->PenaltyNoOfDays>0){
			$hasLateRenewal=true;
		}
		return View::make('crps.consultantapplyrenewal')
					->with('consultantId',$consultantId)
					->with('hasLateRenewal',$hasLateRenewal)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('feeStructures',$feeStructures);
	}
	public function applyCancellation(){
		$consultantId=$this->consultantId;
		$applicationNo=$this->tableTransactionNo('ConsultantCancelCertificateModel','ReferenceNo');
		$hasAlreadyRequestedForCancellation=ConsultantCancelCertificateModel::where('CrpConsultantFinalId',$consultantId)->where('CmnApplicationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
		return View::make('crps.consultantapplycancellation')
					->with('applicationNo',$applicationNo)
					->with('consultantId',$consultantId)
					->with('hasAlreadyRequestedForCancellation',$hasAlreadyRequestedForCancellation);
	}
	public function saveCancellation(){
		$postedValues=Input::all();
		$object = new ConsultantCancelCertificateModel();
		if(!$object->validate($postedValues)){
			return Redirect::to('consultant/applycancellation')->withErrors($object->errors());
		}
		$file = Input::file('Attachment');
		$name = $file->getClientOriginalName();
		$savedName = randomString().'_'.$name;
		$postedValues['AttachmentFilePath'] = "uploads/consultant/".$savedName;
		$file->move('uploads/consultant',$savedName);
		ConsultantCancelCertificateModel::create($postedValues);
		return Redirect::to('consultant/profile')->with('savedsuccessmessage','Your Cancellation request was successfully sent.');
	}
	public function applyServiceGeneralInformation($consultantId){
		$hasExpired = false;
		$consultantFinalTableId=consultantFinalId();

		$expiryDate = DB::table('crpconsultantfinal')->where('Id',$consultantFinalTableId)->pluck('RegistrationExpiryDate');
		if(date('Y-m-d')>$expiryDate){
			$hasExpired = true;
		}
		$redirectUrl=Input::get('redirectUrl');;
		$isRenewalService=Input::get('srenew');
		$serviceByConsultant=1;
		$newGeneralInfoSave=1;
		$isServiceByConsultant=$consultantId;
		$postRouteReference='contractor/mcontractorgeneralinfo';
		if(!Input::has('confedit') && !Input::has('edit')){
			$countId=ConsultantFinalModel::consultantHardList($consultantFinalTableId)->count();
			if($countId==1){
				$consultantGeneralInfo=ConsultantFinalModel::consultantHardList($consultantId)->get(array('ApplicationDate','CmnOwnershipTypeId','CmnRegisteredDzongkhagId','NameOfFirm','Gewog','Village','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','TPN','TradeLicenseNo'));
				$consultantPartnerDetails=ConsultantHumanResourceFinalModel::consultantPartnerHardList($consultantId)->get(array('CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
			}else{
				App::abort('404');
			}
		}else{
			$isServiceByConsultant=$consultantFinalTableId;
			$consultantGeneralInfo=ConsultantModel::consultantHardList($consultantId)->get(array('ApplicationDate','CmnOwnershipTypeId','CmnRegisteredDzongkhagId','NameOfFirm','Gewog','Village','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','TPN','TradeLicenseNo'));
			$consultantPartnerDetails=ConsultantHumanResourceModel::consultantPartnerHardList($consultantId)->get(array('CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('ConsultantModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Name','Nationality'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$ownershipTypes=CmnListItemModel::ownershipType()->get(array('Id','Name','ReferenceNo'));
		$this->layout->content=View::make('crps.consultanteditgeneralinfo')
									->with('isRenewalService',$isRenewalService)
									->with('hasExpired',$hasExpired)
									->with('isEdit',$consultantId)
									->with('isServiceByConsultant',$consultantId)
									->with('newGeneralInfoSave',$newGeneralInfoSave)
									->with('redirectUrl',$redirectUrl)
									->with('serviceByConsultant',$serviceByConsultant)
									->with('postRouteReference',$postRouteReference)
									->with('applicationReferenceNo',$applicationReferenceNo)
									->with('consultantGeneralInfo',$consultantGeneralInfo)
									->with('consultantPartnerDetails',$consultantPartnerDetails)
									->with('countries',$country)
									->with('dzongkhags',$dzongkhag)
									->with('designations',$designation)
									->with('salutations',$salutation)
									->with('ownershipTypes',$ownershipTypes);
	}
	public function applyServiceWorkClassification($consultantId){
		$serviceByConsultant=1;
		if(!empty(Input::get('redirectUrl'))){
			$redirectUrl=Input::get('redirectUrl');
		}else{	
			$redirectUrl='consultant/applyservicehumanresource';
		}
		$consultantIdFinal=consultantFinalId();
		$currentServiceClassifications=ConsultantWorkClassificationFinalModel::services($this->consultantId)->select(DB::raw("T1.Name as Category,group_concat(T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		if(!Input::has('confedit') && !Input::has('edit')){
			$countId=ConsultantFinalModel::consultantHardList($consultantIdFinal)->count();
			if($countId==1){
				$services=DB::select("select distinct T1.Id,T1.Code,T1.Name,T1.CmnConsultantServiceCategoryId,T2.CmnApprovedServiceId as CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnApprovedServiceId and T2.CrpConsultantFinalId=? order by T1.Code",array($this->consultantId));
			}else{
				App::abort('404');
			}
		}else{
			$services=DB::select("select distinct T1.Id,T1.Code,T1.Name,T1.CmnConsultantServiceCategoryId,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		}
		$this->layout->content=View::make('crps.consultanteditworkclassification')
									->with('serviceByConsultant',$serviceByConsultant)
									->with('redirectUrl',$redirectUrl)
									->with('consultantId',$consultantId)
									->with('isEdit',$consultantId)
									->with('currentServiceClassifications',$currentServiceClassifications)
									->with('serviceCategories',$serviceCategories)
									->with('services',$services);
	}
	public function applyServiceHumanResource($consultantId){
		$consultantFinalTableId=$this->consultantId;
		$isEdit=$consultantId;
		$consultantId=consultantModelConsultantId($consultantId);
		$serviceByConsultant=1;
		$newHumanResourceSave=1;
		$serviceAvailCancel=1;
		$humanResourceEditRoute='consultant/applyservicehumanresource';
		$editPage='consultant/applyservicehumanresource';
		if(!empty(Input::get('redirectUrl'))){
			$redirectUrl=Input::get('redirectUrl');
		}else{	
			$redirectUrl='consultant/applyserviceequipment';
		}
		$humanResourceEdit=array(new ConsultantHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEdit=ConsultantHumanResourceFinalModel::consultantHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','JoiningDate','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','CmnServiceTypeId','CmnCountryId'));
			$humanResourceEditAttachments=ConsultantHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			if(count($humanResourceEdit) == 0 || Input::has('initial')){
				$humanResourceEdit=ConsultantHumanResourceModel::consultantHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','JoiningDate','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','CmnServiceTypeId','CmnCountryId'));
				$humanResourceEditAttachments=ConsultantHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			}
		}
//		echo "<pre>"; dd($humanResourceId,$humanResourceEditAttachments);
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->get(array('Id','Name'));
		$serviceTypes=CmnListItemModel::serviceType()->get(array('Id','Name'));
		$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($isEdit)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.JoiningDate','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country','T6.Name as ServiceType'));
		$humanResourcesAttachments=ConsultantHumanResourceModel::humanResourceAttachments($isEdit)->get(array('T1.Id','T1.CrpConsultantHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		
		$consultantHumanResourcesFinal=ConsultantHumanResourceFinalModel::consultantHumanResource($consultantFinalTableId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country','T6.Name as ServiceType'));
		$humanResourcesAttachmentsFinal=ConsultantHumanResourceAttachmentFinalModel::singleConsultantHumanResourceAllAttachments($consultantFinalTableId)->get(array('crpconsultanthumanresourceattachmentfinal.DocumentName','crpconsultanthumanresourceattachmentfinal.DocumentPath','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId'));

		$nextUrl = "consultant/applyserviceconfirmation/";
		$servicesAppliedByConsultant = DB::table('crpconsultantappliedservice')->where('CrpConsultantId',$isEdit)->lists('CmnServiceTypeId');
		if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByConsultant)):
			$nextUrl = 'consultant/applyserviceequipment/';
		endif;
		if(Input::get('redirectUrl')!='' && Input::get('redirectUrl')!='consultant/applyserviceequipment' && Input::get('redirectUrl')!='consultant/applyservicehumanresource'){
			$nextUrl = "$redirectUrl/";
		}

		$this->layout->content=View::make('crps.consultantedithumanresource')
									->with('nextUrl',$nextUrl)
				  					->with('serviceByConsultant',$serviceByConsultant)
				  					->with('serviceAvailCancel',$serviceAvailCancel)
				  					->with('newHumanResourceSave',$newHumanResourceSave)
				  					->with('humanResourceEditRoute',$humanResourceEditRoute)
				  					->with('editPage',$editPage)
				  					->with('redirectUrl',$redirectUrl)
				  					->with('isEdit',$isEdit)
				  					->with('consultantId',$isEdit)
				  					->with('countries',$country)
				  					->with('salutations',$salutation)
				  					->with('qualifications',$qualification)
				  					->with('serviceTypes',$serviceTypes)
				  					->with('designations',$designation)
									->with('trades',$trades)
									->with('consultantHumanResources',$consultantHumanResources)
									->with('humanResourcesAttachments',$humanResourcesAttachments)
									->with('consultantHumanResourcesFinal',$consultantHumanResourcesFinal)
									->with('humanResourcesAttachmentsFinal',$humanResourcesAttachmentsFinal)
									->with('humanResourceEdit',$humanResourceEdit)
									->with('humanResourceEditAttachments',$humanResourceEditAttachments);
	}
	public function applyServiceEquipmentRegistration($consultantId){
		$consultantFinalTableId=consultantFinalId();
		$serviceByConsultant=1;
		$newEquipmentSave=1;
		if(!empty(Input::get('redirectUrl'))){
			$redirectUrl=Input::get('redirectUrl');
		}else{	
			$redirectUrl='consultant/applyserviceconfirmation';
		}
		$equipmentEditRoute='consultant/applyserviceequipment';
		$editPage='consultant/applyserviceequipment';
		$equipmentEdit=array(new ConsultantHumanResourceModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			$equipmentEdit=ConsultantEquipmentFinalModel::consultantEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
			$equipmentAttachments=ConsultantEquipmentAttachmentFinalModel::EquipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			if(count($equipmentEdit) == 0 || Input::has('initial')){
				$equipmentEdit=ConsultantEquipmentModel::consultantEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=ConsultantEquipmentAttachmentModel::EquipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
		}
		$equipments=CmnEquipmentModel::equipment()->get(array('Id','Name','Code','IsRegistered'));
		$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','T1.Name'));
		$equipmentsAttachments=ConsultantEquipmentModel::equipmentAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantEquipmentId','T1.DocumentName','T1.DocumentPath'));
		
		$consultantEquipmentsFinal=ConsultantEquipmentFinalModel::consultantEquipment($consultantFinalTableId)->get(array('crpconsultantequipmentfinal.Id','crpconsultantequipmentfinal.RegistrationNo','crpconsultantequipmentfinal.ModelNo','crpconsultantequipmentfinal.Quantity','T1.Name'));
		$consultantEquipmentAttachmentsFinal=ConsultantEquipmentAttachmentFinalModel::singleConsultantEquipmentAllAttachments($consultantFinalTableId)->get(array('crpconsultantequipmentattachmentfinal.DocumentName','crpconsultantequipmentattachmentfinal.DocumentPath','crpconsultantequipmentattachmentfinal.CrpConsultantEquipmentFinalId'));

		$this->layout->content=View::make('crps.consultanteditequipment')
									->with('serviceByConsultant',$serviceByConsultant)
									->with('newEquipmentSave',$newEquipmentSave)
									->with('equipmentEditRoute',$equipmentEditRoute)
									->with('redirectUrl',$redirectUrl)
									->with('editPage',$editPage)
									->with('isEdit',$consultantId)
									->with('consultantId',$consultantId)
									->with('equipments',$equipments)
									->with('consultantEquipments',$consultantEquipments)
									->with('equipmentsAttachments',$equipmentsAttachments)
									->with('consultantEquipmentsFinal',$consultantEquipmentsFinal)
									->with('consultantEquipmentAttachmentsFinal',$consultantEquipmentAttachmentsFinal)
									->with('equipmentEdit',$equipmentEdit)
									->with('equipmentAttachments',$equipmentAttachments);
	}
	public function applyServiceConfirmation($consultantId){
		$appliedCategories=ConsultantWorkClassificationModel::serviceCategory($consultantId)->get(array('T1.Id','T1.Name as Category'));
		$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService($consultantId)->get(array('crpconsultantworkclassification.CmnServiceCategoryId','T1.Code as ServiceCode','T1.Name as ServiceName'));
		$generalInformation=ConsultantModel::consultant($consultantId)->get(array('crpconsultant.Id','crpconsultant.NameOfFirm','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','T1.Name as Country','T2.NameEn as Dzongkhag'));
		$ownerPartnerDetails=ConsultantHumanResourceModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.JoiningDate','crpconsultanthumanresource.Name','crpconsultanthumanresource.Sex','crpconsultanthumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.JoiningDate','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','T1.Name'));
		$equipmentsAttachments=ConsultantEquipmentModel::equipmentAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantEquipmentId','T1.DocumentName','T1.DocumentPath'));
		$humanResourcesAttachments=ConsultantHumanResourceModel::humanResourceAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		return View::make('crps.consultantapplyserviceconfirmation')
					->with('consultantId',$consultantId)
					->with('categories',$appliedCategories)
					->with('services',$appliedCategoryServices)
					->with('generalInformation',$generalInformation)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('consultantHumanResources',$consultantHumanResources)
					->with('consultantEquipments',$consultantEquipments)
					->with('equipmentsAttachments',$equipmentsAttachments)
					->with('humanResourcesAttachments',$humanResourcesAttachments);
	}
	public function saveConfirmation(){
		$consultantId=Input::get('ConsultantId');
		$consultant = ConsultantModel::find($consultantId);
		$consultant->RegistrationStatus=1;
		$consultant->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
		$consultant->save();
//		$consultantDetails=ConsultantModel::consultantHardList($consultantId)->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate'));
//		$mailView="emails.crps.mailregistrationsuccess";
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Acknowledgement: Receipt of Application for CDB Service";
//		$recipientAddress=$consultantDetails[0]->Email;
//		$recipientName=$consultantDetails[0]->NameOfFirm;
//		$referenceNo=$consultantDetails[0]->ReferenceNo;
//		$applicationDate=$consultantDetails[0]->ApplicationDate;
//		$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
//		$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
//		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
//		$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
//		$mailData=array(
//			'mailIntendedTo'=>$mailIntendedTo,
//			'feeStructures'=>$feeStructures,
//			'serviceCategories'=>$serviceCategories,
//			'appliedCategoryServices'=>$appliedCategoryServices,
//			'applicantName'=>$recipientName,
//			'applicationNo'=>$referenceNo,
//			'applicationDate'=>$applicationDate,
//			'mailMessage'=>"This is to acknowledge receipt of your application for Construction Development Board (CDB)  service. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.",
//		);
		$consultantDetails=ConsultantModel::consultantHardList($consultantId)->get(array('CrpConsultantId','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount'));
		$hasWaiver = $consultantDetails[0]->WaiveOffLateFee;
		$newLateFeeAmount = $consultantDetails[0]->NewLateFeeAmount;
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Approval of Service Application";
		$finalConsultantId=$consultantDetails[0]->CrpConsultantId;
		$recipientAddress=$consultantDetails[0]->Email;
		$recipientName=$consultantDetails[0]->NameOfFirm;
		$applicationNo=$consultantDetails[0]->ReferenceNo;
		$applicationDate=$consultantDetails[0]->ApplicationDate;
		$mobileNo=$consultantDetails[0]->MobileNo;

		/*Start Fee Structure */
		$serviceApplicationApprovedForPayment=0;
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$existingCategoryServicesArray = array();
		$appliedCategoryServicesArray = array();
		$verifiedCategoryServicesArray = array();
		$approvedCategoryServicesArray = array();

		$consultantFinalTableId=consultantModelConsultantId($consultantId);
		/*-----------------------------------------------------------------------------------------------------*/
		$feeAmount=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$appliedServices=ConsultantAppliedServiceModel::appliedService($consultantId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
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
			$hasCategoryClassificationsFee=DB::select("select T1.Id as ServiceCategoryId,T1.Name as ServiceCategoryName,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($consultantId,$consultantId,$consultantId));
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($consultantId));
		}
		/* ENd fee structure */

		/*---*/
		foreach($hasCategoryClassificationsFee as $singleCategory):
			$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
			$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
			$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
		endforeach;
		/*---*/

		$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService(Input::get('ConsultantReference'))->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Id as ServiceId','T1.Code as ServiceCode','T1.Name as ServiceName'));
		$verifiedCategoryServices=ConsultantWorkClassificationModel::verifiedService(Input::get('ConsultantReference'))->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
		$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnApprovedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
		$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
		$emailMessage="This is to acknowledge receipt of your application for Construction Development Board (CDB)  service. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";

		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'existingCategoryServicesArray'=>$existingCategoryServicesArray,
			'appliedCategoryServicesArray'=>$appliedCategoryServicesArray,
			'verifiedCategoryServicesArray'=>$verifiedCategoryServicesArray,
			'approvedCategoryServicesArray'=>$appliedCategoryServicesArray,
			'appliedServices'=>$appliedServices,
			'feeAmount'=>$feeAmount,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'applicationStage'=>true,
			'mailMessage'=>$emailMessage
		);

		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		return Redirect::to('consultant/mydashboard')->with('savedsuccessmessage','Your application was successfully submitted');
	}
	public function editBasicInfo(){
		$postedValues = Input::except('CrpConsultantFinalId');
		$id = Input::get('CrpConsultantFinalId');
		try{
			$instance = ConsultantFinalModel::find($id);
			$instance->fill($postedValues);
			$instance->update();
		}catch(Exception $e){
			return Redirect::to('consultant/profile')->with('customerrormessage',$e->getMessage());
		}

		return Redirect::to('consultant/profile')->with('savedsuccessmessage','Your profile has been updated');

	}

}