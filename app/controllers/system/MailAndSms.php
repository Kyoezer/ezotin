<?php
class MailAndSms extends SystemController{
	public function index(){
		$contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code'));
		$contractorClassifications=ContractorClassificationModel::classification()->get(array('Id','Code as Name'));
		$consultantServiceCategories = ConsultantServiceCategoryModel::category()->get(array('Id','Code'));
		$consultantServices = ConsultantServiceModel::service()->get(array('Id','Code'));
		$spCategories = DB::table('cmnspecializedtradecategory')->orderBy('Code')->get(array('Id','Code'));
		$dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		return View::make('sys.mailandsms')
					->with('spCategories',$spCategories)
					->with('contractorCategories',$contractorCategories)
					->with('consultantServiceCategories',$consultantServiceCategories)
					->with('consultantServices',$consultantServices)
					->with('dzongkhags',$dzongkhags)
					->with('contractorClassifications',$contractorClassifications);
	}
	public function save(){

		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$validation = new SysEmailAndSmsModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('sys/sendmailsms')->withErrors($errors)->withInput();
		}
		$messageAs=Input::get('MessageAs');
		$messageFor=Input::get('MessageFor');
		$contractorClassification=Input::get('ContractorClassification');
		$contractorCategory = Input::get("ContractorCategory");
		$consultantServiceCategory = Input::get("ConsultantCategory");
		$consultantService = Input::get('ConsultantService');
		$specializedTradeCategory = Input::get('SPCategory');
		$dzongkhags = Input::get('Dzongkhag');
		$allEtool = Input::get('AllEtool');
		$allCinet = Input::get('AllCinet');
		if(count($dzongkhags) == 0){
			$dzongkhags = DzongkhagModel::dzongkhag()->lists('Id');
		}

        $sysUserId = Input::get('SysUserId');
        $userIds = "";
        $count = 1;
		if((int)$messageFor == 9){
			$postedValues['SysUserId'] = NULL;
		}else{
			if($sysUserId){
				foreach($sysUserId as $userId){
					$userIds.="'$userId'";
					if($count < count($sysUserId)){
						$userIds.=",";
					}
					$count++;
				}
			}

		}

//		if($classification=="All" || $classification==""){
//			$postedValues['CrpContractorClassificationId']=NULL;
//		}
        if((bool)$sysUserId){
            $postedValues['SysUserId'] = implode(",",$sysUserId);
        }
		SysEmailAndSmsModel::create($postedValues);
		if((int)$messageAs==1){
			$single = false;
			$multiple=false;
			switch ((int)$messageFor) {
				case 1:
					$emailList=DB::select("select T1.Email, T1.FullName from sysuser T1 join sysuserrolemap T2 on T1.Id=T2.SysUserId join sysrole T3 on T2.SysRoleId=T3.Id where T1.Status=1 and T3.Id not in (?,?,?,?,?,?,?)",array(CONST_ROLE_CONTRACTOR,CONST_ROLE_CONSULTANT,CONST_ROLE_SPECIALIZEDTRADE,CONST_ROLE_ENGINEER,CONST_ROLE_ARCHITECT,CONST_ROLE_PROCURINGAGENCYETOOL,CONST_ROLE_PROCURINGAGENCYCINET));
					break;
				case 2:
					$multiple = true;
					if((bool)$allCinet){
						$emailArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYCINET)
							->whereNotNull('T1.Email')
							->whereRaw("T1.Email <> ''")
							->whereRaw("T1.Email like '%@%'")
							->distinct()
							->lists('T1.Email');
					}else{
						$emailArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYCINET)
							->whereIn('T1.Id',$sysUserId)
							->whereNotNull('T1.Email')
							->whereRaw("T1.Email <> ''")
							->whereRaw("T1.Email like '%@%'")
							->distinct()
							->lists('T1.Email');
					}
					break;
				case 3:
					$multiple = true;
					if((bool)$allEtool){
						$emailArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYETOOL)
							->whereNotNull('T1.Email')
							->whereRaw("T1.Email <> ''")
							->whereRaw("T1.Email like '%@%'")
							->distinct()
							->lists('T1.Email');
					}else{
						$emailArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYETOOL)
							->whereIn('T1.Id',$sysUserId)
							->whereNotNull('T1.Email')
							->whereRaw("T1.Email <> ''")
							->whereRaw("T1.Email like '%@%'")
							->distinct()
							->lists('T1.Email');
					}
					break;
				case 4:
					$multiple = true;
					if(count($contractorClassification)==0){
						$contractorClassification = ContractorClassificationModel::classification()->lists('Id');
					}
					if(count($contractorCategory)==0){
						$contractorCategory = ContractorWorkCategoryModel::contractorProjectCategory()->lists('Id');
					}
					$emailArray = DB::table('crpcontractorfinal as T2')
									->join('crpcontractorworkclassificationfinal as T3','T2.Id','=','T3.CrpContractorFinalId')
									->where("T2.CmnApplicationRegistrationStatusId",CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
									->where('T2.CmnCountryId','=',CONST_COUNTRY_BHUTAN)
									->whereNotNull('T2.Email')
									->whereRaw("T2.Email <> ''")
									->whereRaw("TRIM(SUBSTRING_INDEX(T2.Email,',',-1)) like '%@%'")
									->whereIn("CmnApprovedClassificationId",$contractorClassification)
									->whereIn("CmnProjectCategoryId",$contractorCategory)
									->whereIn("T2.CmnDzongkhagId",$dzongkhags)
									->distinct()
									->select(DB::raw("TRIM(SUBSTRING_INDEX(T2.Email,',',-1)) as Email"))
									->lists('Email');
					break;
				case 5:
					$multiple = true;
					if(count($consultantServiceCategory)==0){
						 $consultantServiceCategory = ConsultantServiceCategoryModel::category()->lists('Id');
					}
					if(count($consultantService)==0){
						 $consultantService = ConsultantServiceModel::service()->lists('Id');
					}
					$emailArray = DB::table('crpconsultantfinal as T2')
						->join('crpconsultantworkclassificationfinal as T3','T2.Id','=','T3.CrpConsultantFinalId')
						->where("T2.CmnApplicationRegistrationStatusId",CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->where('T2.CmnCountryId','=',CONST_COUNTRY_BHUTAN)
						->whereNotNull('T2.Email')
						->whereRaw("T2.Email <> ''")
						->whereRaw("TRIM(SUBSTRING_INDEX(T2.Email,',',-1)) like '%@%'")
						->whereIn("CmnApprovedServiceId",$consultantService)
						->whereIn("CmnServiceCategoryId",$consultantServiceCategory)
						->whereIn("T2.CmnDzongkhagId",$dzongkhags)
						->distinct()
						->select(DB::raw("TRIM(SUBSTRING_INDEX(T2.Email,',',-1)) as Email"))
						->lists('Email');
					break;
				case 6:
					$multiple = true;
					$emailArray = DB::table('crparchitectfinal as T1')
									->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
									->whereNotNull('T1.Email')
									->whereRaw("T1.Email <> ''")
									->whereRaw("T1.Email like '%@%'")
									->whereIn('T1.CmnDzongkhagId',$dzongkhags)
									->select(DB::raw("TRIM(SUBSTRING_INDEX(T1.Email,',',-1)) as Email"))
									->lists('Email');
					break;
				case 7:
					$multiple = true;
					$emailArray = DB::table('crpengineerfinal as T1')
						->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->whereNotNull('T1.Email')
						->whereRaw("T1.Email <> ''")
						->whereRaw("T1.Email like '%@%'")
						->whereIn('T1.CmnDzongkhagId',$dzongkhags)
						->select(DB::raw("TRIM(SUBSTRING_INDEX(T1.Email,',',-1)) as Email"))
						->lists('Email');
					break;
				case 8:
					$emailList=DB::select("select Email,Name as FullName from crpspecializedtradefinal where CmnApplicationRegistrationStatusId=?",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED));
					break;
				case 9:
					$addressNo = Input::get('AddressNo');
					if(strpos($addressNo,',') > -1){
						$multiple = true;
						$emailArray = explode(',',$addressNo);
						foreach($emailArray as $key=>$value):
							$emailArray[$key] = trim($value);
						endforeach;
					}else{
						$single = true;
					}

				default:
					break;
			}
            $mailData = array(
                'mailMessage' => $postedValues['Message']
            );
			if($single){  
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",Input::get('AddressNo'),Input::get('Name'));
			}else{
				if($multiple){
					$count = 1;
					Mail::send("emails.crps.mailnoticebyadministrator",$mailData,function($message) use ($emailArray,$count){
						$message->to($emailArray,Input::get('Name'))->subject("Message From CDB");
					});
				}else{
					foreach($emailList as $emailId):
						if($emailId->Email){
							$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$emailId->Email,$emailId->FullName);
						}
					endforeach;
				}

			}


		}else{
			$single = false;
			switch ((int)$messageFor) {
                case 2:
//                    $mobileNos=DB::select("select ContactNo as MobileNo from sysuser T1 where T1.Status=1 and T1.Id in ($userIds)");
					$multiple = true;
					if((bool)$allCinet){
						$mobileNosArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYETOOL)
							->whereNotNull('T1.ContactNo')
							->whereRaw("T1.ContactNo <> ''")
							->distinct()
							->lists('T1.ContactNo');
					}else{
						$mobileNosArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYETOOL)
							->whereIn('T1.Id',$sysUserId)
							->whereNotNull('T1.ContactNo')
							->whereRaw("T1.ContactNo <> ''")
							->distinct()
							->lists('T1.ContactNo');
					}
					break;
                case 3:
//                    $mobileNos=DB::select("select ContactNo as MobileNo from sysuser T1 where T1.Status=1 and T1.Id in ($userIds)");
					$multiple = true;
					if((bool)$allEtool){
						$mobileNosArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYCINET)
							->whereNotNull('T1.ContactNo')
							->whereRaw("T1.ContactNo <> ''")
							->distinct()
							->lists('T1.ContactNo');
					}else{
						$mobileNosArray = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYCINET)
							->whereIn('T1.Id',$sysUserId)
							->whereNotNull('T1.ContactNo')
							->whereRaw("T1.ContactNo <> ''")
							->distinct()
							->lists('T1.ContactNo');
					}
					break;
				case 4:
					$multiple = true;
					if(count($contractorClassification)==0){
						$contractorClassification = ContractorClassificationModel::classification()->lists('Id');
					}
					if(count($contractorCategory)==0){
						$contractorCategory = ContractorWorkCategoryModel::contractorProjectCategory()->lists('Id');
					}
					$mobileNosArray = DB::table('crpcontractorfinal as T2')
						->join('crpcontractorworkclassificationfinal as T3','T2.Id','=','T3.CrpContractorFinalId')
						->where("T2.CmnApplicationRegistrationStatusId",CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->where('T2.CmnCountryId','=',CONST_COUNTRY_BHUTAN)
						->whereNotNull('T2.MobileNo')
						->whereRaw("T2.MobileNo <> ''")
						->whereIn("CmnApprovedClassificationId",$contractorClassification)
						->whereIn("CmnProjectCategoryId",$contractorCategory)
						->whereIn("T2.CmnDzongkhagId",$dzongkhags)
						->distinct()
						->select(DB::raw("SUBSTRING_INDEX(REPLACE(T2.MobileNo,',','/'),'/',-1) as MobileNo"))
						->lists('MobileNo');
					break;
				case 5:
					$multiple = true;
					if(count($consultantServiceCategory)==0){
						$consultantServiceCategory = ConsultantServiceCategoryModel::category()->lists('Id');
					}
					if(count($consultantService)==0){
						$consultantService = ConsultantServiceModel::service()->lists('Id');
					}
					$mobileNosArray = DB::table('crpconsultantfinal as T2')
						->join('crpconsultantworkclassificationfinal as T3','T2.Id','=','T3.CrpConsultantFinalId')
						->where("T2.CmnApplicationRegistrationStatusId",CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->where('T2.CmnCountryId','=',CONST_COUNTRY_BHUTAN)
						->whereNotNull('T2.MobileNo')
						->whereRaw("T2.MobileNo <> ''")
						->whereIn("CmnApprovedServiceId",$consultantService)
						->whereIn("CmnServiceCategoryId",$consultantServiceCategory)
						->whereIn("T2.CmnDzongkhagId",$dzongkhags)
						->distinct()
						->select(DB::raw("SUBSTRING_INDEX(REPLACE(T2.MobileNo,',','/'),'/',-1) as MobileNo"))
						->lists('MobileNo');
					break;
				case 6:
					$multiple = true;
					$mobileNosArray = DB::table('crparchitectfinal as T1')
						->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->whereNotNull('T1.MobileNo')
						->whereRaw("T1.MobileNo <> ''")
						->whereIn('T1.CmnDzongkhagId',$dzongkhags)
						->select(DB::raw("SUBSTRING_INDEX(REPLACE(T1.MobileNo,',','/'),'/',-1) as MobileNo"))
						->lists('MobileNo');
					break;
				case 7:
					$multiple = true;
					$mobileNosArray = DB::table('crpengineerfinal as T1')
						->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->whereNotNull('T1.MobileNo')
						->whereRaw("T1.MobileNo <> ''")
						->whereIn('T1.CmnDzongkhagId',$dzongkhags)
						->select(DB::raw("SUBSTRING_INDEX(REPLACE(T1.MobileNo,',','/'),'/',-1) as MobileNo"))
						->lists('MobileNo');

					break;
				case 8:
					$multiple = true;
					if(count($specializedTradeCategory) == 0){
						$specializedTradeCategory = DB::table('cmnspecializedtradecategory')->lists('Id');
					}
					$mobileNosArray = DB::table('crpspecializedtradefinal as T1')
						->join('crpspecializedtradeworkclassificationfinal as T2','T1.Id','=','T2.CrpSpecializedTradeFinalId')
						->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
						->whereNotNull('T1.MobileNo')
						->whereRaw("T1.MobileNo <> ''")
						->whereIn('T1.CmnDzongkhagId',$dzongkhags)
						->whereIn('T2.CmnApprovedCategoryId',$specializedTradeCategory)
						->distinct()
						->select(DB::raw("SUBSTRING_INDEX(REPLACE(T1.MobileNo,',','/'),'/',-1) as MobileNo"))
						->lists('MobileNo');

					break;
				case 9:
					$addressNo = Input::get('AddressNo');
					if(strpos($addressNo,',') > -1){
						$multiple = true;
						$mobileNosArray = explode(',',$addressNo);
					}else{
						$single = true;
					}
				default:
					break;
			}
			if($single){
				$this->sendSms($postedValues['Message'],Input::get('AddressNo'));
			}else{
				if($multiple){
					foreach($mobileNosArray as $value):
						$no = trim($value);
						if(strlen($no)==8){
							$this->sendSms($postedValues['Message'],$no);
						}
					endforeach;
				}else{
					foreach($mobileNos as $mobileNo):
						if($mobileNo->MobileNo):
							$this->sendSms($postedValues['Message'],trim($mobileNo->MobileNo));
						endif;
					endforeach;
				}

			}
		}
		$mailData = array(
                	'mailMessage' => $postedValues['Message']
            	);
		$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB","pramodpho@gmail.com","pramod Nepal");
		return Redirect::to('sys/sendmailsms')->with('savedsuccessmessage','Message has been sucessfully added.');
	}
}