<?php
class MySpecializedTrade extends CrpsController{
	protected $layout = 'horizontalmenumaster';
	private $specializedTradeId;
	public function __construct(){
		$this->specializedTradeId=specializedTradeFinalId();
	}
	public function dashBoard(){
		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',8)->where('DisplayIn',2)->orderBy('Date')->get(array('Message','Date'));
		$applicationHistory = DB::table('crpspecializedtrade as T1')
			->join('cmnlistitem as T2','T1.CmnApplicationRegistrationStatusId','=','T2.Id')
			->join('crpspecializedtradeappliedservice as T3','T3.CrpSpecializedTradeId','=','T1.Id')
			->join('crpservice as T4','T4.Id','=','T3.CmnServiceTypeId')
			->where('T1.CrpSpecializedTradeId',$this->specializedTradeId)
			->whereRaw('DATEDIFF(NOW(),T1.ApplicationDate) <= 30')
			->orderBy('T1.ApplicationDate','DESC')
			->groupBy('T1.Id')
			->get(array(DB::raw('distinct T1.Id'),DB::raw('VerifiedDate as RegistrationVerifiedDate'),'RegistrationApprovedDate','PaymentApprovedDate','RejectedDate','T1.ReferenceNo','T1.ApplicationDate', 'T1.RemarksByRejector','T1.CmnApplicationRegistrationStatusId', 'T2.Name as Status',DB::raw('group_concat(T4.Name SEPARATOR ", ") as Service')));
		$this->layout->content=View::make('crps.cmnexternaluserdashboard')
								->with('applicationHistory',$applicationHistory)
								->with('newsAndNotifications',$newsAndNotifications);
	}
	public function myCertificate($specializedTradeId){
		$info=SpecializedTradeFinalModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtradefinal.Name','crpspecializedtradefinal.CIDNo','crpspecializedtradefinal.SPNo','crpspecializedtradefinal.RegistrationApprovedDate','crpspecializedtradefinal.ApplicationDate','crpspecializedtradefinal.RegistrationExpiryDate','T1.Name as Salutation','T2.NameEn as Dzongkhag'));
		$specializedTradeWorkClassifications=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnApprovedCategoryId and T2.CrpSpecializedTradeFinalId=? where T1.Code like '%SP%'",array($specializedTradeId));
		$data['info']=$info;
		$data['specializedTradeWorkClassifications']=$specializedTradeWorkClassifications;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.specializedtradecertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function myProfile(){
		$userSpecializedTrade=1;
		$registrationApprovedForPayment=0;
		$specializedTradeInformations=SpecializedTradeFinalModel::specializedTrade($this->specializedTradeId)->get(array('crpspecializedtradefinal.SPNo','crpspecializedtradefinal.CIDNo','crpspecializedtradefinal.Name','crpspecializedtradefinal.Gewog','crpspecializedtradefinal.Village','crpspecializedtradefinal.Email','crpspecializedtradefinal.MobileNo','crpspecializedtradefinal.EmployerName','crpspecializedtradefinal.EmployerAddress','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
		$specializedTradeAttachments=SpecializedTradeAttachmentFinalModel::attachment($this->specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		$workClasssifications=DB::select("select T1.Code,T1.Name,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeFinalId=? left join crpspecializedtradeworkclassificationfinal T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeFinalId=? left join crpspecializedtradeworkclassificationfinal T4 on T1.Id=T4.CmnVerifiedCategoryId and T4.CrpSpecializedTradeFinalId=? order by T1.Code,T1.Name",array($this->specializedTradeId,$this->specializedTradeId,$this->specializedTradeId));
		$this->layout->content= View::make('crps.specializedtradeinformation')
							->with('specializedTradeId',$this->specializedTradeId)
							->with('workClasssifications',$workClasssifications)
							->with('registrationApprovedForPayment',$registrationApprovedForPayment)
							->with('userSpecializedTrade',$userSpecializedTrade)
							->with('specializedTradeInformations',$specializedTradeInformations)
							->with('specializedTradeAttachments',$specializedTradeAttachments);
	}
	public function applyRenewal(){
		$hasLateFeeAmount=array();
		$hasLateRenewal=false;
		$countRenewalApplications=SpecializedTradeAppliedServiceModel::serviceRenewalCount($this->specializedTradeId)->count();
		$registrationExpiryDate = DB::table('crpspecializedtradefinal')->where('Id',$this->specializedTradeId)->pluck('RegistrationExpiryDate');
		$registrationExpiryDate = date_format(date_create($registrationExpiryDate),'Y-m-d');
		$today = date('Y-m-d');
		$dateDiff = date_diff(date_create($today),date_create($registrationExpiryDate));
		$dateDiffInDays = $dateDiff->format('%R%a');
		if((int)$dateDiffInDays > 60){
			return Redirect::to('specializedtrade/mydashboard')->with('customerrormessage','You cannot apply for renewal as your certificate is still active');
		}else{
			$existingRenewal = DB::table('crpspecializedtradeappliedservice as T1')
				->join('crpspecializedtrade as T2','T1.CrpSpecializedTradeId','=','T2.Id')
				->join('crpspecializedtradefinal as T3','T3.Id','=','T2.CrpSpecializedTradeId')
				->where('T3.Id',$this->specializedTradeId)
				->whereNotNull('T2.CmnApplicationRegistrationStatusId')
				->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
				->select(DB::raw('max(T1.CreatedOn) as Date'))
				->pluck('Date');
			if((bool)$existingRenewal){
				$dateDiffExistingRenewal = date_diff(date_create($existingRenewal),date_create($registrationExpiryDate));
				$dateDiffExistingRenewalInDays = $dateDiffExistingRenewal->format('%a');
				$status = DB::table('crpspecializedtradeappliedservice as T1')
					->join('crpspecializedtrade as T2','T1.CrpSpecializedTradeId','=','T2.Id')
					->join('crpspecializedtradefinal as T3','T3.Id','=','T2.CrpSpecializedTradeId')
					->where('T3.Id',$this->specializedTradeId)
					->where('T1.CreatedOn',$existingRenewal)
					->where('T1.CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)
					->pluck('T2.CmnApplicationRegistrationStatusId');
				if(((int)$dateDiffExistingRenewalInDays <= 30 ) && ($status != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)){
					return Redirect::to('specializedtrade/mydashboard')->with('customerrormessage','You have already applied for renewal');
				}
			}
		}
		$feeDetails=DB::select("select Name as ServiceName,SpecializedTradeFirstRenewAmount as RenewalFee,SpecializedTradeValidity as RenewalValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_RENEWAL));
		if((int)$countRenewalApplications>=1){
			$hasLateFeeAmount=DB::select("select RegistrationExpiryDate,DATEDIFF(CURDATE(),RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crparchitectfinal where Id=? LIMIT 1",array($this->architectId));
			if((int)$hasLateFeeAmount[0]->PenaltyNoOfDays>0){
				$hasLateRenewal=true;
			}
			$feeDetails=DB::select("select Name as ServiceName,SpecializedTradeAfterFirstRenewAmount as RenewalFee,SpecializedTradeValidity as RenewalValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_RENEWAL));
		}
		return View::make('crps.specializedtradeapplyrenewal')
					->with('hasLateRenewal',$hasLateRenewal)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('feeDetails',$feeDetails)
					->with('specializedTradeId',$this->specializedTradeId);
	}
	public function applyCancellation(){
		$applicationNo=$this->tableTransactionNo('SpecializedTradeCancelCertificateModel','ReferenceNo');
		$hasAlreadyRequestedForCancellation=SpecializedTradeCancelCertificateModel::where('CrpSpecializedTradeFinalId',$this->specializedTradeId)->where('CmnApplicationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
		return View::make('crps.specializedtradeapplycancellation')
					->with('applicationNo',$applicationNo)
					->with('specializedTradeId',$this->specializedTradeId)
					->with('hasAlreadyRequestedForCancellation',$hasAlreadyRequestedForCancellation);
	}
	public function saveCancellation(){
		$postedValues=Input::all();
		$object = new SpecializedTradeCancelCertificateModel();
		if(!$object->validate($postedValues)){
			return Redirect::to('specializedtrade/applycancellation')->withErrors($object->errors());
		}
		$file = Input::file('Attachment');
		$name = $file->getClientOriginalName();
		$savedName = randomString().'_'.$name;
		$postedValues['AttachmentFilePath'] = "uploads/specializedtrades/".$savedName;
		$file->move('uploads/specializedtrades',$savedName);
		SpecializedTradeCancelCertificateModel::create($postedValues);
		return Redirect::to('specializedtrade/profile')->with('savedsuccessmessage','Your Cancellation request was successfully sent.');
	}
	public function applyRenewalDetails($specializedTradeId){
		$specializedTradeFinalTableId=specializedTradeFinalId();
		$isServiceBySpecializedTrade=1;
		$newGeneralInfoSave=1;
		$confirmEdit=Input::get('editconf');
		$redirectUrl=Input::get('redirectUrl');
		$applicationReferenceNo=$this->tableTransactionNo('SpecializedTradeModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Name','Nationality'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		if((bool)$confirmEdit!=NULL && strlen($confirmEdit)==36){
			$specializedTradeRegistrations=SpecializedTradeModel::specializedTradeHardList($specializedTradeId)->get(array('Id','ReferenceNo','ApplicationDate','CIDNo','CmnSalutationId','Name','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','TPN'));
			$specializedtradeRegistrationAttachments=SpecializedTradeAttachmentModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
			$categories=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? order by T1.Code,T1.Name",array($specializedTradeId));
			$oldUploads=SpecializedTradeAttachmentFinalModel::attachment($specializedTradeFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		}else{
			$specializedTradeRegistrations=SpecializedTradeFinalModel::specializedTradeHardList($specializedTradeFinalTableId)->get(array('CIDNo','CmnSalutationId','Name','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','TPN'));
			$specializedtradeRegistrationAttachments=SpecializedTradeAttachmentFinalModel::attachment($specializedTradeFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
			$categories=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnApprovedCategoryId and T2.CrpSpecializedTradeFinalId=? order by T1.Code,T1.Name",array($specializedTradeFinalTableId));
			$oldUploads=array();
		}
		$this->layout->content= View::make('crps.specializedtradeeditregistrationinfo')
							->with('isServiceBySpecializedTrade',$isServiceBySpecializedTrade)
							->with('newGeneralInfoSave',$newGeneralInfoSave)
							->with('specializedTradeFinalTableId',$specializedTradeFinalTableId)
							->with('redirectUrl',$redirectUrl)
							->with('specializedTradeId',$specializedTradeId)
							->with('applicationReferenceNo',$applicationReferenceNo)
							->with('countries',$country)
							->with('dzongkhags',$dzongkhag)
							->with('salutations',$salutation)
							->with('qualifications',$qualification)
							->with('specializedtradeRegistrations',$specializedTradeRegistrations)
							->with('specializedtradeRegistrationAttachments',$specializedtradeRegistrationAttachments)
							->with('categories',$categories);
	}
	public function applyRenewalConfirmation($specializedTradeId){
		$isServiceBySpecializedTrade=1;
		$specializedTradeInformations=SpecializedTradeModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtrade.ReferenceNo','crpspecializedtrade.ApplicationDate','crpspecializedtrade.SPNo','crpspecializedtrade.CIDNo','crpspecializedtrade.Name','crpspecializedtrade.Gewog','crpspecializedtrade.Village','crpspecializedtrade.Email','crpspecializedtrade.MobileNo','crpspecializedtrade.EmployerName','crpspecializedtrade.EmployerAddress','T1.Name as Salutation','T2.NameEn as Dzongkhag'));
		$specializedTradeAttachments=SpecializedTradeAttachmentModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		$workClasssifications=DB::select("select T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=?",array($specializedTradeId));
		return View::make('crps.specializedtradeapplyserviceconfirmation')
					->with('specializedTradeId',$specializedTradeId)
					->with('isServiceBySpecializedTrade',$isServiceBySpecializedTrade)
					->with('specializedTradeInformations',$specializedTradeInformations)
					->with('specializedTradeAttachments',$specializedTradeAttachments)
					->with('workClasssifications',$workClasssifications);
	}
}