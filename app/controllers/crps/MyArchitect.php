<?php
class MyArchitect extends CrpsController{
	protected $layout = 'horizontalmenumaster';
	private $architectId;
	public function __construct(){
		$this->architectId=architectFinalId();
	}
	public function dashboard(){
return Redirect::to('https://www.citizenservices.gov.bt/cdb/login');

		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',6)->where('DisplayIn',2)->orderBy('Date')->get(array('Message','Date'));
		$applicationHistory = DB::table('crparchitect as T1')
			->join('cmnlistitem as T2','T1.CmnApplicationRegistrationStatusId','=','T2.Id')
			->join('crparchitectappliedservice as T3','T3.CrpArchitectId','=','T1.Id')
			->join('crpservice as T4','T4.Id','=','T3.CmnServiceTypeId')
			->where('T1.CrpArchitectId',$this->architectId)
			->whereRaw('DATEDIFF(NOW(),T1.ApplicationDate) <= 30')
			->orderBy('T1.ApplicationDate','DESC')
			->groupBy('T1.Id')
			->get(array(DB::raw('distinct T1.Id'),DB::raw('VerifiedDate as RegistrationVerifiedDate'),'RegistrationApprovedDate','PaymentApprovedDate','RejectedDate','T1.ReferenceNo','T1.RemarksByRejector','T1.ApplicationDate','T1.CmnApplicationRegistrationStatusId', 'T2.Name as Status',DB::raw('group_concat(T4.Name SEPARATOR ", ") as Service')));
		$this->layout->content=View::make('crps.cmnexternaluserdashboard')
							->with('applicationHistory',$applicationHistory)
							->with('newsAndNotifications',$newsAndNotifications);
	}
	public function myCertificate($architectId){
		$architectInfo=ArchitectFinalModel::architect($architectId)->get(array('crparchitectfinal.Name','crparchitectfinal.CIDNo','crparchitectfinal.ARNo','crparchitectfinal.InitialDate as RegistrationApprovedDate','crparchitectfinal.RegistrationExpiryDate','T2.Name as Salutation'));
		$data['architectInfo']=$architectInfo;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.architectcertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function myProfile(){
		$userArchitect=1;
		$registrationApprovedForPayment=0;
		$architectInformations=ArchitectFinalModel::architect($this->architectId)->get(array('crparchitectfinal.Id','crparchitectfinal.ARNo','crparchitectfinal.CIDNo','crparchitectfinal.Name','crparchitectfinal.Gewog','crparchitectfinal.Village','crparchitectfinal.Email','crparchitectfinal.MobileNo','crparchitectfinal.EmployerName','crparchitectfinal.EmployerAddress','crparchitectfinal.GraduationYear','crparchitectfinal.NameOfUniversity','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry'));
		$architectAttachments=ArchitectAttachmentFinalModel::attachment($this->architectId)->get(array('Id','DocumentName','DocumentPath'));
		$this->layout->content=View::make('crps.architectinformation')
							->with('architectServiceSectorType',CONST_CMN_SERVICESECTOR_GOVT)
							->with('architectId',$this->architectId)
							->with('registrationApprovedForPayment',$registrationApprovedForPayment)
							->with('userArchitect',$userArchitect)
							->with('architectInformations',$architectInformations)
							->with('architectAttachments',$architectAttachments);
	}
	public function applyRenewal(){
		$serviceSectorType=ArchitectFinalModel::architectHardList($this->architectId)->pluck('CmnServiceSectorTypeId');
		$registrationExpiryDate = DB::table('crparchitectfinal')->where('Id',$this->architectId)->pluck('RegistrationExpiryDate');
		$registrationExpiryDate = date_format(date_create($registrationExpiryDate),'Y-m-d');
		$today = date('Y-m-d');
		$dateDiff = date_diff(date_create($today),date_create($registrationExpiryDate));
		$dateDiffInDays = $dateDiff->format('%R%a');
		if((int)$dateDiffInDays > 60){
			return Redirect::to('architect/mydashboard')->with('customerrormessage','You cannot apply for renewal as your certificate is still active');
		}else{
			$existingRenewal = DB::table('crparchitectappliedservice as T1')
				->join('crparchitect as T2','T1.CrpArchitectId','=','T2.Id')
				->join('crparchitectfinal as T3','T3.Id','=','T2.CrpArchitectId')
				->whereNotNull('T2.CmnApplicationRegistrationStatusId')
				->where('T3.Id',$this->architectId)
				->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
				->select(DB::raw('max(T1.CreatedOn) as Date'))
				->pluck('Date');
			if((bool)$existingRenewal){
				$dateDiffExistingRenewal = date_diff(date_create($existingRenewal),date_create($registrationExpiryDate));
				$dateDiffExistingRenewalInDays = $dateDiffExistingRenewal->format('%a');
				$status = DB::table('crparchitectappliedservice as T1')
					->join('crparchitect as T2','T1.CrpArchitectId','=','T2.Id')
					->join('crparchitectfinal as T3','T3.Id','=','T2.CrpArchitectId')
					->where('T3.Id',$this->architectId)
					->where('T1.CreatedOn',$existingRenewal)
					->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
					->pluck('T2.CmnApplicationRegistrationStatusId');
				if(((int)$dateDiffExistingRenewalInDays <= 30 ) && ($status != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)){
					return Redirect::to('architect/mydashboard')->with('customerrormessage','You have already applied for renewal');
				}
			}
		}
		if($serviceSectorType==CONST_CMN_SERVICESECTOR_PVT){
			$hasLateRenewal = false;
			$hasLateFeeAmount=DB::select("select RegistrationExpiryDate,DATEDIFF(CURDATE(),RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crparchitectfinal where Id=? LIMIT 1",array($this->architectId));
			if((int)$hasLateFeeAmount[0]->PenaltyNoOfDays>0){
				$hasLateRenewal=true;
			}
			$feeDetails=DB::select("select Name as ServiceName,ArchitectPvtAmount as RenewalFee,ArchitectPvtValidity as RenewalValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_RENEWAL));
		}else{
			$hasLateFeeAmount=array();
			$hasLateRenewal=false;
			$feeDetails=DB::select("select Name as ServiceName,ArchitectGovtAmount as RenewalFee,ArchitectGovtValidity as RenewalValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_RENEWAL));
		}
		return View::make('crps.architectapplyrenewal')
					->with('hasLateRenewal',$hasLateRenewal)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('serviceSectorType',$serviceSectorType)
					->with('architectId',$this->architectId)
					->with('feeDetails',$feeDetails);
	}
	public function applyCancellation(){
		$applicationNo=$this->tableTransactionNo('ArchitectCancelCertificateModel','ReferenceNo');
		$hasAlreadyRequestedForCancellation=ArchitectCancelCertificateModel::where('CrpArchitectFinalId',$this->architectId)->where('CmnApplicationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
		return View::make('crps.architectapplycancellation')
					->with('applicationNo',$applicationNo)
					->with('architectId',$this->architectId)
					->with('hasAlreadyRequestedForCancellation',$hasAlreadyRequestedForCancellation);
	}
	public function saveCancellation(){
		$postedValues=Input::all();
		$object = new ArchitectCancelCertificateModel();
		if(!$object->validate($postedValues)){
			return Redirect::to('architect/applycancellation')->withErrors($object->errors());
		}
		$file = Input::file('Attachment');
		$name = $file->getClientOriginalName();
		$savedName = randomString().'_'.$name;
		$postedValues['AttachmentFilePath'] = "uploads/architect/".$savedName;
		$file->move('uploads/architect',$savedName);
		ArchitectCancelCertificateModel::create($postedValues);
		return Redirect::to('architect/profile')->with('savedsuccessmessage','Your Cancellation request was successfully sent.');
	}
	public function applyRenewalDetails($architectId){
		$architectFinalTableId=architectFinalId();
		$isServiceByArchitect=1;
		$newGeneralInfoSave=1;
		$confirmEdit=Input::get('editconf');
		$redirectUrl=Input::get('redirectUrl');
		$applicationReferenceNo=$this->tableTransactionNo('ArchitectModel','ReferenceNo');
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name','Nationality'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		if((bool)$confirmEdit!=NULL && strlen($confirmEdit)==36){
			$architectRegistration=ArchitectModel::architectHardList($architectId)->get(array('Id','ReferenceNo','ApplicationDate','CmnServiceSectorTypeId','CIDNo','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','TPN'));
			$architectRegistrationAttachments=ArchitectAttachmentModel::attachment($architectId)->get(array('Id','DocumentName','DocumentPath'));
			$oldUploads=ArchitectAttachmentFinalModel::attachment($architectFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		}else{
			$architectRegistration=ArchitectFinalModel::architectHardList($architectFinalTableId)->get(array('CmnServiceSectorTypeId','CIDNo','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','TPN'));
			$architectRegistrationAttachments=ArchitectAttachmentFinalModel::attachment($architectFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
			$oldUploads=array();
		}
		$this->layout->content=View::make('crps.architecteditregistrationinfo')
							->with('newGeneralInfoSave',$newGeneralInfoSave)
							->with('architectFinalTableId',$architectFinalTableId)
							->with('isServiceByArchitect',$isServiceByArchitect)
							->with('redirectUrl',$redirectUrl)
							->with('architectId',$architectId)
							->with('applicationReferenceNo',$applicationReferenceNo)
							->with('serviceSectorTypes',$serviceSectorTypes)
							->with('countries',$country)
							->with('dzongkhags',$dzongkhag)
							->with('salutations',$salutation)
							->with('qualifications',$qualification)
							->with('architectRegistrations',$architectRegistration)
							->with('architectRegistrationAttachments',$architectRegistrationAttachments)
							->with('oldUploads',$oldUploads);
	}
	public function applyRenewalConfirmation($architectId){
		$architectFinalTableId=architectFinalId();
		$isServiceByArchitect=1;
		$architectInformations=ArchitectModel::architect($architectId)->get(array('crparchitect.Id','crparchitect.CIDNo','crparchitect.Name','crparchitect.Gewog','crparchitect.Village','crparchitect.Email','crparchitect.MobileNo','crparchitect.EmployerName','crparchitect.EmployerAddress','crparchitect.GraduationYear','crparchitect.NameOfUniversity','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry'));
		$architectAttachments=ArchitectAttachmentModel::attachment($architectId)->get(array('Id','DocumentName','DocumentPath'));
		$oldUploads=ArchitectAttachmentFinalModel::attachment($architectFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make('crps.architectapplyserviceconfirmation')
					->with('architectId',$architectId)
					->with('isServiceByArchitect',$isServiceByArchitect)
					->with('architectInformations',$architectInformations)
					->with('architectAttachments',$architectAttachments)
					->with('oldUploads',$oldUploads);
	}
}