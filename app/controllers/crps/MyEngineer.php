<?php
class MyEngineer extends CrpsController{
	protected $layout = 'horizontalmenumaster';
	private $engineerId;
	public function __construct(){
		$this->engineerId=engineerFinalId();
	}
	public function dashBoard(){
return Redirect::to('https://www.citizenservices.gov.bt/cdb/login');

		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',7)->where('DisplayIn',2)->orderBy('Date')->get(array('Message','Date'));
		$applicationHistory = DB::table('crpengineer as T1')
			->join('cmnlistitem as T2','T1.CmnApplicationRegistrationStatusId','=','T2.Id')
			->join('crpengineerappliedservice as T3','T3.CrpEngineerId','=','T1.Id')
			->join('crpservice as T4','T4.Id','=','T3.CmnServiceTypeId')
			->where('T1.CrpEngineerId',$this->engineerId)
			->whereRaw('DATEDIFF(NOW(),T1.ApplicationDate) <= 30')
			->orderBy('T1.ApplicationDate','DESC')
			->groupBy('T1.Id')
			->get(array(DB::raw('distinct T1.Id'),'T1.RemarksByRejector','T1.ApplicationDate','T1.CmnApplicationRegistrationStatusId', 'T2.Name as Status',DB::raw('group_concat(T4.Name SEPARATOR ", ") as Service')));
		$this->layout->content=View::make('crps.cmnexternaluserdashboard')
					->with('applicationHistory',$applicationHistory)
					->with('newsAndNotifications',$newsAndNotifications);
	}
	public function myCertificate($engineerId){
		$engineerInfo=EngineerFinalModel::engineer($engineerId)->get(array('crpengineerfinal.Name','crpengineerfinal.CIDNo','crpengineerfinal.CDBNo','crpengineerfinal.RegistrationApprovedDate','crpengineerfinal.RegistrationExpiryDate','T2.Name as Salutation'));
		$data['engineerInfo']=$engineerInfo;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.engineercertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function myProfile(){
		$userEngineer=1;
		$registrationApprovedForPayment=0;
		$engineerInformations=EngineerFinalModel::engineer($this->engineerId)->get(array('crpengineerfinal.CDBNo','crpengineerfinal.CIDNo','crpengineerfinal.Name','crpengineerfinal.Gewog','crpengineerfinal.Village','crpengineerfinal.Email','crpengineerfinal.MobileNo','crpengineerfinal.EmployerName','crpengineerfinal.EmployerAddress','crpengineerfinal.GraduationYear','crpengineerfinal.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus','T8.Name as Trade'));
		$engineerAttachments=EngineerAttachmentFinalModel::attachment($this->engineerId)->get(array('Id','DocumentName','DocumentPath'));
		$this->layout->content=View::make('crps.engineerinformation')
							->with('engineerId',$this->engineerId)
							->with('registrationApprovedForPayment',$registrationApprovedForPayment)
							->with('userEngineer',$userEngineer)
							->with('engineerInformations',$engineerInformations)
							->with('engineerAttachments',$engineerAttachments);
	}
	public function applyRenewal(){
		$serviceSectorType=EngineerFinalModel::engineerHardList($this->engineerId)->pluck('CmnServiceSectorTypeId');
		$registrationExpiryDate = DB::table('crpengineerfinal')->where('Id',$this->engineerId)->pluck('RegistrationExpiryDate');
		$registrationExpiryDate = date_format(date_create($registrationExpiryDate),'Y-m-d');
		$today = date('Y-m-d');
		$dateDiff = date_diff(date_create($today),date_create($registrationExpiryDate));
		$dateDiffInDays = $dateDiff->format('%R%a');
		if((int)$dateDiffInDays > 60){
			return Redirect::to('engineer/mydashboard')->with('customerrormessage','You cannot apply for renewal as your certificate is still active');
		}else{
			$existingRenewal = DB::table('crpengineerappliedservice as T1')
				->join('crpengineer as T2','T1.CrpEngineerId','=','T2.Id')
				->join('crpengineerfinal as T3','T3.Id','=','T2.CrpEngineerId')
				->whereNotNull('T2.CmnApplicationRegistrationStatusId')
				->where('T3.Id',$this->engineerId)
				->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
				->select(DB::raw('max(T1.CreatedOn) as Date'))
				->pluck('Date');
			if((bool)$existingRenewal){
				$dateDiffExistingRenewal = date_diff(date_create($existingRenewal),date_create($registrationExpiryDate));
				$dateDiffExistingRenewalInDays = $dateDiffExistingRenewal->format('%a');
				$status = DB::table('crpengineerappliedservice as T1')
					->join('crpengineer as T2','T1.CrpEngineerId','=','T2.Id')
					->join('crpengineerfinal as T3','T3.Id','=','T2.CrpEngineerId')
					->where('T3.Id',$this->engineerId)
					->where('T1.CreatedOn',$existingRenewal)
					->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
					->pluck('T2.CmnApplicationRegistrationStatusId');
				if(((int)$dateDiffExistingRenewalInDays <= 30 ) && ($status != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)){
					return Redirect::to('engineer/mydashboard')->with('customerrormessage','You have already applied for renewal');
				}
			}
		}
		if($serviceSectorType==CONST_CMN_SERVICESECTOR_PVT){
			$hasLateRenewal = false;
			$hasLateFeeAmount=DB::select("select RegistrationExpiryDate,DATEDIFF(CURDATE(),RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpengineerfinal where Id=? LIMIT 1",array($this->engineerId));
			if((int)$hasLateFeeAmount[0]->PenaltyNoOfDays>0){
				$hasLateRenewal=true;
			}
			$feeDetails=DB::select("select Name as ServiceName,EngineerPvtAmount as RenewalFee,EngineerPvtValidity as RenewalValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_RENEWAL));
		}else{
			$hasLateFeeAmount=array();
			$hasLateRenewal=false;
			$feeDetails=DB::select("select Name as ServiceName,EngineerGovtAmount as RenewalFee,EngineerGovtValidity as RenewalValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_RENEWAL));
		}
		return View::make('crps.engineerapplyrenewal')
					->with('hasLateRenewal',$hasLateRenewal)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('serviceSectorType',$serviceSectorType)
					->with('engineerId',$this->engineerId)
					->with('feeDetails',$feeDetails);
	}
	public function applyCancellation(){
		$applicationNo=$this->tableTransactionNo('EngineerCancelCertificateModel','ReferenceNo');
		$hasAlreadyRequestedForCancellation=EngineerCancelCertificateModel::where('CrpEngineerFinalId',$this->engineerId)->where('CmnApplicationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
		return View::make('crps.engineerapplycancellation')
					->with('applicationNo',$applicationNo)
					->with('engineerId',$this->engineerId)
					->with('hasAlreadyRequestedForCancellation',$hasAlreadyRequestedForCancellation);
	}
	public function saveCancellation(){
		$postedValues=Input::all();
		$object = new EngineerCancelCertificateModel();
		if(!$object->validate($postedValues)){
			return Redirect::to('engineer/applycancellation')->withErrors($object->errors());
		}
		$file = Input::file('Attachment');
		$name = $file->getClientOriginalName();
		$savedName = randomString().'_'.$name;
		$postedValues['AttachmentFilePath'] = "uploads/engineer/".$savedName;
		$file->move('uploads/engineer',$savedName);
		EngineerCancelCertificateModel::create($postedValues);
		return Redirect::to('engineer/profile')->with('savedsuccessmessage','Your Cancellation request was successfully sent.');
	}
	public function applyRenewalDetails($engineerId){
		$engineerFinalTableId=engineerFinalId();
		$isServiceByEngineer=1;
		$newGeneralInfoSave=1;
		$confirmEdit=Input::get('editconf');
		$redirectUrl=Input::get('redirectUrl');
		$applicationReferenceNo=$this->tableTransactionNo('EngineerModel','ReferenceNo');
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name','Nationality'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		if((bool)$confirmEdit!=NULL && strlen($confirmEdit)==36){
			$engineerRegistration=EngineerModel::engineerHardList($engineerId)->get(array('Id','ReferenceNo','ApplicationDate','CmnServiceSectorTypeId','CmnTradeId','CIDNo','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','TPN'));
			$engineerRegistrationAttachments=EngineerAttachmentModel::attachment($engineerId)->get(array('Id','DocumentName','DocumentPath'));
			$oldUploads=EngineerAttachmentFinalModel::attachment($engineerFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		}else{
			$engineerRegistration=EngineerFinalModel::engineerHardList($engineerFinalTableId)->get(array('CmnServiceSectorTypeId','CmnTradeId','CIDNo','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','TPN'));
			$engineerRegistrationAttachments=EngineerAttachmentFinalModel::attachment($engineerFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
			$oldUploads=array();
		}
		$this->layout->content=View::make('crps.engineereditregistrationinfo')
								->with('isServiceByEngineer',$isServiceByEngineer)
								->with('newGeneralInfoSave',$newGeneralInfoSave)
								->with('engineerFinalTableId',$engineerFinalTableId)
								->with('redirectUrl',$redirectUrl)
								->with('engineerId',$engineerId)
								->with('applicationReferenceNo',$applicationReferenceNo)
								->with('serviceSectorTypes',$serviceSectorTypes)
								->with('countries',$country)
								->with('dzongkhags',$dzongkhag)
								->with('salutations',$salutation)
								->with('qualifications',$qualification)
								->with('trades',$trades)
								->with('engineerRegistrations',$engineerRegistration)
								->with('engineerRegistrationAttachments',$engineerRegistrationAttachments)
								->with('oldUploads',$oldUploads);
	}
	public function applyRenewalConfirmation($engineerId){
		$engineerFinalTableId=engineerFinalId();
		$isServiceByEngineer=1;
		$engineerInformations=EngineerModel::engineer($engineerId)->get(array('crpengineer.Id','crpengineer.CIDNo','crpengineer.Name','crpengineer.Gewog','crpengineer.Village','crpengineer.Email','crpengineer.MobileNo','crpengineer.EmployerName','crpengineer.EmployerAddress','crpengineer.GraduationYear','crpengineer.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade'));
		$engineerAttachments=EngineerAttachmentModel::attachment($engineerId)->get(array('Id','DocumentName','DocumentPath'));
		$oldUploads=ArchitectAttachmentFinalModel::attachment($engineerFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make('crps.engineerapplyserviceconfirmation')
					->with('engineerId',$engineerId)
					->with('isServiceByEngineer',$isServiceByEngineer)
					->with('engineerInformations',$engineerInformations)
					->with('engineerAttachments',$engineerAttachments)
					->with('oldUploads',$oldUploads);
	}
}