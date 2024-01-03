<?php

class UserManagement extends SystemController{
	protected $layout = 'horizontalmenumaster'; 
	public function index($userId=NULL){

        $secondLevelProcuringAgencies = array();
        $thirdLevelProcuringAgencies = array();
		if((bool)$userId!=NULL){
			$oldRole = DB::table('sysuserrolemap as T1')->where('T1.SysUserId',$userId)->lists('SysRoleId');
			$users=User::where('Id','=',$userId)->get();
			$roles=DB::select("select T1.Id,T1.Name,T1.ReferenceNo,case when T2.Id is null then 0 else 1 end as Selected,T2.Id as UserMapId,T3.CmnProcuringAgencyId, T4.Name as ProcuringAgency from sysrole T1 left join sysuserrolemap T2 on T1.Id=T2.SysRoleId and T2.SysUserId=? left join sysuser T3 on T2.SysUserId=T3.Id left join cmnprocuringagency T4 on T4.Id = T3.CmnProcuringAgencyId where coalesce(T1.ReferenceNo,0) not in (2,3,4,5,6) order by T1.Name",array($userId));
		}else{
			$oldRole = array();
			$users=array(new User());
			$roles=RoleModel::whereRaw("coalesce(ReferenceNo,0) not in (2,3,4,5,6)")->orderBy('Name')->get(array('Id','Name','ReferenceNo'));
		}
		$firstLevelProcuringAgencies = DB::table('cmnprocuringagency')
								->whereNull('CmnProcuringAgencyId')
								->orderBy('Name')
								->get(array('Id','Code','Name'));
		foreach($firstLevelProcuringAgencies as $firstLevelProcuringAgency):
			$secondLevelProcuringAgencies[$firstLevelProcuringAgency->Id] = DB::table('cmnprocuringagency')
																				->where('CmnProcuringAgencyId',$firstLevelProcuringAgency->Id)
																				->get(array('Id','Code','Name'));
		    foreach($secondLevelProcuringAgencies[$firstLevelProcuringAgency->Id] as $secondLevelProcuringAgency):
		        $thirdLevelProcuringAgencies[$secondLevelProcuringAgency->Id] = DB::table('cmnprocuringagency')
                    ->where('CmnProcuringAgencyId',$secondLevelProcuringAgency->Id)
                    ->get(array('Id','Code','Name'));
            endforeach;
		endforeach;
		return View::make('sys.usermanagement')
					->with('oldRole',$oldRole)
					->with('firstLevelProcuringAgencies',$firstLevelProcuringAgencies)
					->with('secondLevelProcuringAgencies',$secondLevelProcuringAgencies)
					->with('thirdLevelProcuringAgencies',$thirdLevelProcuringAgencies)
					->with('roles',$roles)
					->with('users',$users);
	}
	public function checkUserNameAvailbality($flag = null){
		$flagEmail=true;
		$email=Input::get('username');
		if((bool)$flag){
			$emailCountUser=User::where('username',$email)->count();
			if($emailCountUser > 0){
				$flagEmail=false;
			}
		}else{
			$emailCountContractor=ContractorModel::contractorHardListAll()->where('Email',DB::raw("'$email'"))->count();
			/*--------------------------------------------------------------------------------------------------------*/
			$emailCountConsultant=ConsultantModel::consultantHardListAll()->where('Email',DB::raw("'$email'"))->count();
			/*--------------------------------------------------------------------------------------------------------*/
			$emailCountArchitect= DB::table('crparchitect')->where('Email',$email)->count();
			/*---------------------------------------------------------------------------------------------------------*/
			$emailCountEngineer=EngineerModel::engineerHardListAll()->where('Email',DB::raw("'$email'"))->count();
			/*---------------------------------------------------------------------------------------------------------*/
			$emailCountSpecializedTrade=SpecializedTradeModel::specializedTradeHardListAll()->where('Email',$email)->count();
			$emailCountUser=User::where('username',$email)->count();

			if($emailCountUser>0 || $emailCountContractor>0 || $emailCountConsultant>0 || $emailCountArchitect>0 || $emailCountEngineer>0 || $emailCountSpecializedTrade>0){
				$flagEmail=false;
			}
		}
		return json_encode(array(
    		'valid' => $flagEmail,
		));
	}
	public function pullFirmNameRegistration(){
		$cdbNo = Input::get('cdbNo');
		$regType = Input::get('regType');

		if($regType == 1){
			$firmName = DB::table('crpcontractorfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("NameOfFirm");
			$email = DB::table('crpcontractorfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Email");
			$userId = DB::table('crpcontractorfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck('SysUserId');
		}elseif($regType == 2){
			$firmName = DB::table('crpconsultantfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("NameOfFirm");
			$email =DB::table('crpconsultantfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Email");
			$userId = DB::table('crpconsultantfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck('SysUserId');
		}elseif($regType == 3){
			$firmName = DB::table('crparchitectfinal')->where(DB::raw('TRIM(ARNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Name");
			$email = DB::table('crparchitectfinal')->where(DB::raw('TRIM(ARNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Email");
			$userId = DB::table('crparchitectfinal')->where(DB::raw('TRIM(ARNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck('SysUserId');
		}elseif($regType == 4){
			$firmName = DB::table('crpengineerfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Name");
			$email = DB::table('crpengineerfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Email");
			$userId = DB::table('crpengineerfinal')->where(DB::raw('TRIM(CDBNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck('SysUserId');
		}else{
			$firmName = DB::table('crpspecializedtradefinal')->where(DB::raw('TRIM(SPNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Name");
			$email = DB::table('crpspecializedtradefinal')->where(DB::raw('TRIM(SPNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck("Email");
			$userId = DB::table('crpspecializedtradefinal')->where(DB::raw('TRIM(SPNo)'),trim($cdbNo))->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->pluck('SysUserId');
		}
		return Response::json(array('Name'=>$firmName,'Email'=>$email,'UserId'=>(bool)$userId?1:0));
	}
	public function registerExistingApplicant(){
		// your secret key
//		$secret = "6LfmmicTAAAAAJ8EU2gYO71Teirx4cJTSerUDy94";

		// empty response
//		$response = null;

		// check secret key
//		$reCaptcha = new ReCaptcha($secret);

//		$response = $reCaptcha->verifyResponse(
//			$_SERVER["REMOTE_ADDR"],
//			Input::get("g-recaptcha-response")
//		);

		if(Session::get('random') == '123xxx') {
			$postedValues = Input::except('_token','CaptchaAnswer');
			$postedValues['CDBNo'] = trim($postedValues['CDBNo']);
			$postedValues['Id'] = $this->UUID();
			$postedValues['password'] = Hash::make($postedValues['password']);
			$postedValues['Status'] = 0;
			$postedValues['AppliedOn'] = date("Y-m-d G:i:s");
			$postedValues['Email'] = $postedValues['username'];

			$existingUsers = 0;
			$existingRegisteredUsers = 0;
			$existingApplicants = 0;
			$type = '';
			switch($postedValues['RegistrationType']){
				case 1:
					$type="Contractor";
					$existingRegisteredUsers = DB::table('crpcontractorfinal')->where(DB::raw('TRIM(CDBNo)'),trim($postedValues['CDBNo']))->whereNotNull('SysUserId')->count();
					$existingApplicants = DB::table('sysregapplication')->where('RegistrationType',$postedValues['RegistrationType'])->where('CDBNo',$postedValues['CDBNo'])->count();
					$existingUsers = $existingApplicants+$existingRegisteredUsers;
					break;
				case 2:
					$type="Consultant";
					$existingRegisteredUsers = DB::table('crpconsultantfinal')->where(DB::raw('TRIM(CDBNo)'),trim($postedValues['CDBNo']))->whereNotNull('SysUserId')->count();
					$existingApplicants = DB::table('sysregapplication')->where('RegistrationType',$postedValues['RegistrationType'])->where('CDBNo',$postedValues['CDBNo'])->count();
					$existingUsers = $existingApplicants+$existingRegisteredUsers;
					break;
				case 3:
					$type="Architect";
					$existingRegisteredUsers = DB::table('crparchitectfinal')->where(DB::raw('TRIM(ARNo)'),trim($postedValues['CDBNo']))->whereNotNull('SysUserId')->count();
					$existingApplicants = DB::table('sysregapplication')->where('RegistrationType',$postedValues['RegistrationType'])->where('CDBNo',$postedValues['CDBNo'])->count();
					$existingUsers = $existingApplicants+$existingRegisteredUsers;
					break;
				case 4:
					$type="Engineer";
					$existingRegisteredUsers = DB::table('crpengineerfinal')->where(DB::raw('TRIM(CDBNo)'),trim($postedValues['CDBNo']))->whereNotNull('SysUserId')->count();
					$existingApplicants = DB::table('sysregapplication')->where('RegistrationType',$postedValues['RegistrationType'])->where('CDBNo',$postedValues['CDBNo'])->count();
					$existingUsers = $existingApplicants+$existingRegisteredUsers;
					break;
				case 5:
					$type="Specialized Trade";
					$existingRegisteredUsers = DB::table('crpspecializedtradefinal')->where(DB::raw('TRIM(SPNo)'),trim($postedValues['CDBNo']))->whereNotNull('SysUserId')->count();
					$existingApplicants = DB::table('sysregapplication')->where('RegistrationType',$postedValues['RegistrationType'])->where('CDBNo',$postedValues['CDBNo'])->count();
					$existingUsers = $existingApplicants+$existingRegisteredUsers;
					break;
				default:
					break;
			}
			if($existingUsers > 0){
				if($existingRegisteredUsers > 0){
					return Redirect::to('ezhotin/home/4')->with('extramessage',"This $type already has a user account. Please contact CDB");
				}else{
					return Redirect::to('ezhotin/home/4')->with('extramessage',"This $type has already applied. Please contact CDB");
				}
			}

			DB::table('sysregapplication')->insert($postedValues);
			return Redirect::to('ezhotin/home/4')->with('savedsuccessmessage','Your application has been submitted');
		} else {
			return Redirect::to('ezhotin/home/4')->with('customerrormessage','---');
		}
	}
	public function getApplicantList(){
		$applicantList = DB::table('sysregapplication as T1')
							->leftJoin('crpcontractorfinal as T2','T2.CDBNo','=','T1.CDBNo')
							->leftJoin('crpconsultantfinal as T3','T3.CDBNo','=','T1.CDBNo')
							->leftJoin('crparchitectfinal as T4','T4.ARNo','=','T1.CDBNo')
							->leftJoin('crpengineerfinal as T5','T5.CDBNo','=','T1.CDBNo')
							->leftJoin('crpspecializedtradefinal as T6','T6.SPNo','=','T1.CDBNo')
							->where(DB::raw('coalesce(T1.Status,0)'),0)
							->orderBy('T1.AppliedOn')
							->get(array('T1.Id',DB::raw("case T1.RegistrationType when 1 then T2.NameOfFirm when 2 then T3.NameOfFirm when 3 then T4.Name when 4 then T5.Name when 5 then T6.Name end as Name"),'T1.FullName','T1.username',DB::raw("case T1.RegistrationType when 1 then 'Contractor' when 2 then 'Consultant' when 3 then 'Architect' when 4 then 'Engineer' when 5 then 'Specialized Trade' end as RegistrationType"),'T1.CDBNo','T1.Email','T1.ContactNo','T1.AppliedOn'));
		return View::make('sys.registrationexistingapplicantlist')
					->with('applicantList',$applicantList);
	}
	public function approveApplicant($id){
		$details = DB::table('sysregapplication')->where('Id',$id)->get(array('Id','CDBNo','RegistrationType','FullName','username','Email','password','ContactNo'));
		DB::beginTransaction();

		try{
			DB::table('sysregapplication')->where('Id',$id)->update(array('Status'=>1,'ApprovedBy'=>Auth::user()->Id,'ApprovedOn'=>date('Y-m-d G:i:s')));
			$insertArray['Id'] = $details[0]->Id;
			$insertArray['FullName'] = $details[0]->FullName;
			$insertArray['username'] = $details[0]->username;
			$insertArray['Email'] = $details[0]->Email;
			$insertArray['ContactNo'] = $details[0]->ContactNo;
			$insertArray['password'] = $details[0]->password;
			$insertArray['Status'] = 1;
			User::create($insertArray);
			$name = '';
			switch((int)$details[0]->RegistrationType){
				case 1:
					$name = DB::table('crpcontractorfinal')->where('CDBNo',$details[0]->CDBNo)->pluck('NameOfFirm');
					DB::table('crpcontractorfinal')->where('CDBNo',$details[0]->CDBNo)->update(array('SysUserId'=>$details[0]->Id,'Email'=>$details[0]->Email,'MobileNo'=>$details[0]->ContactNo));
					DB::table('sysuserrolemap')->insert(array('Id'=>$this->UUID(),'SysUserId'=>$details[0]->Id,'SysRoleId'=>CONST_ROLE_CONTRACTOR,'CreatedBy'=>Auth::user()->Id,'CreatedOn'=>date('Y-m-d G:i:s')));
					break;
				case 2:
					$name = DB::table('crpconsultantfinal')->where('CDBNo',$details[0]->CDBNo)->pluck('NameOfFirm');
					DB::table('crpconsultantfinal')->where('CDBNo',$details[0]->CDBNo)->update(array('SysUserId'=>$details[0]->Id,'Email'=>$details[0]->Email,'MobileNo'=>$details[0]->ContactNo));
					DB::table('sysuserrolemap')->insert(array('Id'=>$this->UUID(),'SysUserId'=>$details[0]->Id,'SysRoleId'=>CONST_ROLE_CONSULTANT,'CreatedBy'=>Auth::user()->Id,'CreatedOn'=>date('Y-m-d G:i:s')));
					break;
				case 3:
					$name = DB::table('crparchitectfinal')->where('ARNo',$details[0]->CDBNo)->pluck('Name');
					DB::table('crparchitectfinal')->where('ARNo',$details[0]->CDBNo)->update(array('SysUserId'=>$details[0]->Id,'Email'=>$details[0]->Email,'MobileNo'=>$details[0]->ContactNo));
					DB::table('sysuserrolemap')->insert(array('Id'=>$this->UUID(),'SysUserId'=>$details[0]->Id,'SysRoleId'=>CONST_ROLE_ARCHITECT,'CreatedBy'=>Auth::user()->Id,'CreatedOn'=>date('Y-m-d G:i:s')));
					break;
				case 4:
					$name = DB::table('crpengineerfinal')->where('CDBNo',$details[0]->CDBNo)->pluck('Name');
					DB::table('crpengineerfinal')->where('CDBNo',$details[0]->CDBNo)->update(array('SysUserId'=>$details[0]->Id,'Email'=>$details[0]->Email,'MobileNo'=>$details[0]->ContactNo));
					DB::table('sysuserrolemap')->insert(array('Id'=>$this->UUID(),'SysUserId'=>$details[0]->Id,'SysRoleId'=>CONST_ROLE_ENGINEER,'CreatedBy'=>Auth::user()->Id,'CreatedOn'=>date('Y-m-d G:i:s')));
					break;
				case 5:
					$name = DB::table('crpspecializedtradefinal')->where('SPNo',$details[0]->CDBNo)->pluck('Name');
					DB::table('crpspecializedtradefinal')->where('SPNo',$details[0]->CDBNo)->update(array('SysUserId'=>$details[0]->Id,'Email'=>$details[0]->Email,'MobileNo'=>$details[0]->ContactNo));
					DB::table('sysuserrolemap')->insert(array('Id'=>$this->UUID(),'SysUserId'=>$details[0]->Id,'SysRoleId'=>CONST_ROLE_SPECIALIZEDTRADE,'CreatedBy'=>Auth::user()->Id,'CreatedOn'=>date('Y-m-d G:i:s')));
					break;
				default:
					break;
			}
			DB::commit();
		}catch(Exception $e){
			DB::rollBack();
			return Redirect::to('sys/approveexistingusersregistration')->with('customerrormessage',$e->getMessage());
		}
		$mailData=array(
			'cdbNo'=>$details[0]->CDBNo,
			'name' => $name,
			'applicantName'=>$details[0]->FullName,
			'mailMessage'=>'Your user account has been created successfully. You can login using '.$details[0]->Email.' and the password you provided.'
		);
		$mailView="emails.auth.userregistrationapproved";
		$subject = "Approval of new user registration";
		$recipientAddress = $details[0]->Email;
		$recipientName = $details[0]->FullName;
		$smsMessage="Your user account has been approved by CDB. You can login using ".$details[0]->Email." and the password you provided. Check email for details.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$details[0]->ContactNo);
		return Redirect::to('sys/approveexistingusersregistration')->with('savedsuccessmessage','Record has been updated');
	}
	public function deleteApplicant($id){
		DB::beginTransaction();
		$mobileNo = DB::table('sysregapplication')->where('Id',$id)->pluck("ContactNo");
		try{
			DB::table('sysregapplication')->where('Id',$id)->delete();
		}catch(Exception $e){
			DB::rollBack();
			return Redirect::to('sys/approveexistingusersregistration')->with('customerrormessage',$e->getMessage());
		}
		DB::commit();
		$this->sendSms("Account rejected, only owner/employees allowed to apply. Or your email is incorrect.",$mobileNo);
		return Redirect::to('sys/approveexistingusersregistration')->with('savedsuccessmessage','Record has been deleted');
	}
	public function save(){
		$insert=false;
		$postedValues=Input::all();
		$validation = new User;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('sys/user')->withErrors($errors)->withInput();
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues['Id'])){
				$insert=true;
				$tobeencrypt=$postedValues["password"];
				$encrypted=Hash::make($tobeencrypt);
				$postedValues['password']=$encrypted;
				$uuid=DB::select("select uuid() as Id");
		        $generatedId=$uuid[0]->Id;
		        $postedValues["Id"]=$generatedId;
				User::create($postedValues);
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['SysUserId']=$generatedId;
							$childTable= new RoleUserMapModel($value1);
							$childTable->save();

							if($value1['SysRoleId'] == CONST_ROLE_PROCURINGAGENCYETOOL){
								$reportMenus = DB::table('sysmenu')->where(DB::raw('coalesce(TypeId,0)'),1)->get(array('Id'));
								foreach($reportMenus as $menu):
									$saveArray = array();
									$saveArray['Id'] = $this->UUID();
									$saveArray['SysUserId'] = $generatedId;
									$saveArray['SysMenuId'] = $menu->Id;
									$saveArray['PageView'] = 1;
									$saveArray['Type'] = 1;
									$saveArray['CreatedOn'] = date('Y-m-d G:i:s');
									$saveArray['CreatedBy'] = "894eba10-885b-11e5-ab33-5cf9dd5fc4f1";

									DB::table('sysuserreportmap')->insert($saveArray);
								endforeach;
							}
							if($value1['SysRoleId'] == CONST_ROLE_PROCURINGAGENCYCINET){
								$reportMenus = DB::table('sysmenu')->where(DB::raw('coalesce(TypeId,0)'),2)->get(array('Id'));
								foreach($reportMenus as $menu):
									$saveArray = array();
									$saveArray['Id'] = $this->UUID();
									$saveArray['SysUserId'] = $generatedId;
									$saveArray['SysMenuId'] = $menu->Id;
									$saveArray['PageView'] = 1;
									$saveArray['Type'] = 2;
									$saveArray['CreatedOn'] = date('Y-m-d G:i:s');
									$saveArray['CreatedBy'] = "894eba10-885b-11e5-ab33-5cf9dd5fc4f1";

									DB::table('sysuserreportmap')->insert($saveArray);
								endforeach;
							}

						}
					}
				}
			}else{
				$oldRole = DB::table('sysuserrolemap as T1')->where('T1.SysUserId',$postedValues['Id'])->lists('SysRoleId');
				if(!(in_array(CONST_ROLE_CONTRACTOR,$oldRole) || in_array(CONST_ROLE_CONSULTANT,$oldRole) || in_array(CONST_ROLE_SPECIALIZEDTRADE,$oldRole) || in_array(CONST_ROLE_ENGINEER,$oldRole) || in_array(CONST_ROLE_ARCHITECT,$oldRole))){
					RoleUserMapModel::where('SysUserId','=',$postedValues['Id'])->delete();
					$instance=User::find($postedValues['Id']);
					$instance->fill($postedValues);
					$instance->update();
					foreach($postedValues as $key=>$value){
						if(gettype($value)=='array'){
							foreach($value as $key1=>$value1){
								$value1['SysUserId']=$postedValues['Id'];
								$childTable= new RoleUserMapModel($value1);
								$childTable->save();
							}
						}
					}
				}else{
					$instance=User::find($postedValues['Id']);
					$instance->fill($postedValues);
					$instance->update();
				}
			}
			if(Input::has('CrpContractorId')){
				$contractor=ContractorFinalModel::find(Input::get('CrpContractorId'));
				$contractor->SysUserId=$generatedId;
				$contractor->Email=$postedValues['username'];
				$contractor->save();
			}elseif(Input::has('CrpConsultantId')){
				$consultant=ConsultantFinalModel::find(Input::get('CrpConsultantId'));
				$consultant->SysUserId=$generatedId;
				$consultant->Email=$postedValues['username'];
				$consultant->save();
			}elseif(Input::has('CrpArchitectId')){
				$architect=ArchitectFinalModel::find(Input::get('CrpArchitectId'));
				$architect->SysUserId=$generatedId;
				$architect->Email=$postedValues['username'];
				$architect->save();
			}elseif(Input::has('CrpEngineerId')){
				$engineer=EngineerFinalModel::find(Input::get('CrpEngineerId'));
				$engineer->SysUserId=$generatedId;
				$engineer->Email=$postedValues['username'];
				$engineer->save();
			}elseif(Input::has('CrpSpecializedTradeId')){
				$engineer=SpecializedTradeFinalModel::find(Input::get('CrpSpecializedTradeId'));
				$engineer->SysUserId=$generatedId;
				$engineer->Email=$postedValues['username'];
				$engineer->save();
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		if($insert){
			return Redirect::to('sys/user')->with('savedsuccessmessage','User has been sucessfully added.');
		}else{
			return Redirect::to('sys/actionsuser')->with('savedsuccessmessage','User has been sucessfully updated.');
		}	
	}
	public function changePasswordIndex($viewType){
		if((int)$viewType==1){
			return View::make('sys.changepassword');	
		}
		$this->layout->content =View::make('sys.changepassword');
		
	}
	public function checkOldPassword(){
		$valid=false;
		$oldPassword=Input::get('OldPassword');
		if(Hash::check($oldPassword, Auth::user()->password)){
			$valid=true;
		}
		return json_encode(array(
    		'valid' => $valid,
		));
	}
	public function changePassword(){
		$id=Auth::user()->Id;
		$plainText=Input::get('password');
		$encrypted=Hash::make($plainText);
		$user = User::find($id);
		$user->password = $encrypted;
		$user->save();
		return Redirect::to('ezhotin/home/1')->with('savedsuccessmessage','Your password has been changed. Please log in again with your new password.');
	}
	public function resetPasswordIndex(){
		$users=User::sysUser()->get(array('Id','FullName','username'));
		$etoolUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, T2.Name as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id =?) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 order by T3.Name, T1.FullName",array(CONST_ROLE_PROCURINGAGENCYETOOL));
		$cinetUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, T2.Name as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id =?) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 order by T3.Name, T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCINET));
		$certifiedbuilderUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, T2.Name as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id =?) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 order by T3.Name, T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCBUILDER));
		$etoolCinetUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, GROUP_CONCAT(T2.Name SEPARATOR ', ') as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id in (?,?)) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 2 group by T1.Id order by T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCINET,CONST_ROLE_PROCURINGAGENCYETOOL));
		$crpsUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, GROUP_CONCAT(T2.Name SEPARATOR ', ') as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id not in (?,?,?,?,?,?,?,'19c400de-9dd2-11eb-96b9-0026b988eaa8')) on T4.SysUserId = T1.Id group by T1.Id order by T2.Name,T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCINET,CONST_ROLE_PROCURINGAGENCYETOOL,CONST_ROLE_CONTRACTOR,CONST_ROLE_CONSULTANT,CONST_ROLE_SPECIALIZEDTRADE,CONST_ROLE_ENGINEER,CONST_ROLE_ARCHITECT));
		$applicantUsers = DB::select("select T1.Id, T3.Name as Agency,coalesce(coalesce(coalesce(coalesce(coalesce(concat(F.NameOfFirm,' (',F.CDBNo,')'), concat(G.NameOfFirm,' (',G.CDBNo,')')), H.ARNo),I.CDBNo),J.SPNo),K.SPNo) as CDBNo, T1.FullName, T1.username, GROUP_CONCAT(T2.Name SEPARATOR ', ') as Role from sysuser T1 left join crpcontractorfinal F on F.SysUserId = T1.Id left join crpconsultantfinal G on G.SysUserId = T1.Id left join crparchitectfinal H on H.SysUserId = T1.Id left join crpengineerfinal I on I.SysUserId = T1.Id left join crpspecializedtradefinal J on J.SysUserId = T1.Id LEFT JOIN `crpspecializedfirmfinal` K ON K.SysUserId = T1.Id left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id in (?,?,?,?,?,'19c400de-9dd2-11eb-96b9-0026b988eaa8')) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 group by T1.Id order by T2.Name, T1.FullName",array(CONST_ROLE_CONTRACTOR,CONST_ROLE_CONSULTANT,CONST_ROLE_SPECIALIZEDTRADE,CONST_ROLE_ENGINEER,CONST_ROLE_ARCHITECT));
		return View::make('sys.resetpassword')
			->with('etoolUsers',$etoolUsers)
			->with('cinetUsers',$cinetUsers)
			->with('certifiedbuilderUsers',$certifiedbuilderUsers)
			->with('etoolCinetUsers',$etoolCinetUsers)
			->with('crpsUsers',$crpsUsers)
			->with('applicantUsers',$applicantUsers);
	}
	public function resetPassword(){
		$id=Input::get('SysUserId');
		$plainText=Input::get('password');
		$postedValues=Input::all();
		$validation = new User;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('sys/resetpassword')->withErrors($errors)->withInput();
		}
		$encrypted=Hash::make($plainText);
		$user = User::find($id);
		$user->password = $encrypted;
		$user->save();
		return Redirect::to('sys/resetpassword')->with('savedsuccessmessage','Password has successfully changed');
	}
	public function userList(){

		$etoolUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, T2.Name as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id =?) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 order by T3.Name, T1.FullName",array(CONST_ROLE_PROCURINGAGENCYETOOL));
		$cinetUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, T2.Name as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id =?) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 order by T3.Name, T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCINET));
		$certifiedbuilderUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, T2.Name as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id =?) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 order by T3.Name, T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCBUILDER));
		$etoolCinetUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, GROUP_CONCAT(T2.Name SEPARATOR ', ') as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id in (?,?)) on T4.SysUserId = T1.Id where (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 2 group by T1.Id order by T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCINET,CONST_ROLE_PROCURINGAGENCYETOOL));
		$crpsUsers = DB::select("select T1.Id, T3.Name as Agency, T1.FullName, T1.username, GROUP_CONCAT(T2.Name SEPARATOR ', ') as Role from sysuser T1 left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id not in (?,?,?,?,?,?,?,'19c400de-9dd2-11eb-96b9-0026b988eaa8')) on T4.SysUserId = T1.Id group by T1.Id order by T2.Name,T1.FullName",array(CONST_ROLE_PROCURINGAGENCYCINET,CONST_ROLE_PROCURINGAGENCYETOOL,CONST_ROLE_CONTRACTOR,CONST_ROLE_CONSULTANT,CONST_ROLE_SPECIALIZEDTRADE,CONST_ROLE_ENGINEER,CONST_ROLE_ARCHITECT));
		$applicantUsers = DB::select("select T1.Id, T3.Name as Agency,coalesce(coalesce(coalesce(coalesce(coalesce(concat(F.NameOfFirm,' (',F.CDBNo,')'), concat(G.NameOfFirm,' (',G.CDBNo,')')), H.ARNo),I.CDBNo),J.SPNo),K.SPNo) as CDBNo, T1.FullName, T1.username, GROUP_CONCAT(T2.Name SEPARATOR ', ') as Role from sysuser T1 left join crpcontractorfinal F on F.SysUserId = T1.Id left join crpconsultantfinal G on G.SysUserId = T1.Id left join crparchitectfinal H on H.SysUserId = T1.Id left join crpengineerfinal I on I.SysUserId = T1.Id left join crpspecializedtradefinal J on J.SysUserId = T1.Id LEFT JOIN `crpspecializedfirmfinal` K ON K.SysUserId = T1.Id left join cmnprocuringagency T3 on T1.CmnProcuringAgencyId = T3.Id join (sysrole T2 join sysuserrolemap T4 on T2.Id = T4.SysRoleId and T2.Id in (?,?,?,?,?,'19c400de-9dd2-11eb-96b9-0026b988eaa8')) on T4.SysUserId = T1.Id where  (select count(*) from sysuserrolemap A where A.SysUserId = T1.Id) = 1 group by T1.Id order by T2.Name, T1.FullName",array(CONST_ROLE_CONTRACTOR,CONST_ROLE_CONSULTANT,CONST_ROLE_SPECIALIZEDTRADE,CONST_ROLE_ENGINEER,CONST_ROLE_ARCHITECT));
		return View::make('sys.useractionlist')
					->with('etoolUsers',$etoolUsers)
					->with('cinetUsers',$cinetUsers)
					->with('certifiedbuilderUsers',$certifiedbuilderUsers)
					->with('etoolCinetUsers',$etoolCinetUsers)
					->with('crpsUsers',$crpsUsers)
					->with('applicantUsers',$applicantUsers);
	}
	public function getDelete($id){
		DB::beginTransaction();
		try{
			DB::table('crpcontractorfinal')->where('SysUserId',$id)->update(array('SysUserId'=>NULL));
			DB::table('crpconsultantfinal')->where('SysUserId',$id)->update(array('SysUserId'=>NULL));
			DB::table('crpengineerfinal')->where('SysUserId',$id)->update(array('SysUserId'=>NULL));
			DB::table('crpspecializedtradefinal')->where('SysUserId',$id)->update(array('SysUserId'=>NULL));
			DB::table('crparchitectfinal')->where('SysUserId',$id)->update(array('SysUserId'=>NULL));
			DB::table('sysuser')->where('Id',$id)->delete();
			DB::commit();
			return Redirect::to('sys/actionsuser')->with('savedsuccessmessage','User has been deleted');
		}catch(Exception $e){
			DB::rollback();
			return Redirect::to('sys/actionsuser')->with('customerrormessage','Error deleting! This record is being used somewhere else.');
		}
	}
	public function updateProfile(){
		$id = Auth::user()->Id;
		$redirectUrl = Input::get("RedirectUrl");
		$instance = User::find($id);
		$instance->fill(Input::except("RedirectUrl"));
		$instance->update();
		return Redirect::to($redirectUrl)->with("savedsuccessmessage","Your profile has been updated");
	}
	public function emailAvailability(){
		$email = Input::get('Email');
		if((bool)$email){
			$email = trim($email);
			$selfEmail = Auth::user()->Email;
			if(trim($selfEmail) != $email){
				$contractorFinalId = DB::table('crpcontractorfinal')->where('SysUserId',Auth::user()->Id)->pluck('Id');
				$contractorFinalId = (bool)$contractorFinalId?$contractorFinalId:'xx';
				$contractorFinalCheck = DB::table('crpcontractorfinal')->where(DB::raw("TRIM(Email)"),$email)->where("Id",'<>',$contractorFinalId)->count();
				$contractorCheck = DB::table('crpcontractor')->where(DB::raw("TRIM(Email)"),$email)->where('Id','<>',$contractorFinalId)->where("CrpContractorId",'<>',$contractorFinalId)->count();

				$consultantFinalId = DB::table('crpconsultantfinal')->where('SysUserId',Auth::user()->Id)->pluck('Id');
				$consultantFinalId = (bool)$consultantFinalId?$consultantFinalId:'xx';
				$consultantFinalCheck = DB::table("crpconsultantfinal")->where("Id",'<>',$consultantFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();
				$consultantCheck = DB::table('crpconsultant')->where('Id','<>',$consultantFinalId)->where("CrpConsultantId",'<>',$consultantFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();

				$architectFinalId = DB::table('crpconsultantfinal')->where('SysUserId',Auth::user()->Id)->pluck('Id');
				$architectFinalId = (bool)$architectFinalId?$architectFinalId:'xx';
				$architectFinalCheck = DB::table("crparchitectfinal")->where(DB::raw("TRIM(Email)"),$email)->where("Id",'<>',$architectFinalId)->count();
				$architectCheck = DB::table('crparchitect')->where('Id','<>',$architectFinalId)->where("CrpArchitectId",'<>',$architectFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();

				$engineerFinalId = DB::table('crpengineerfinal')->where('SysUserId',Auth::user()->Id)->pluck('Id');
				$engineerFinalId = (bool)$engineerFinalId?$engineerFinalId:'xx';
				$engineerFinalCheck = DB::table("crpengineerfinal")->where("Id",'<>',$engineerFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();
				$engineerCheck = DB::table('crpengineer')->where("Id",'<>',$engineerFinalId)->where("CrpEngineerId",'<>',$engineerFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();

				$spFinalId = DB::table('crpspecializedtradefinal')->where('SysUserId',Auth::user()->Id)->pluck('Id');
				$spFinalId = (bool)$spFinalId?$spFinalId:'xx';
				$specializedTradeFinalCheck = DB::table("crpspecializedtradefinal")->where("Id",'<>',$spFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();
				$specializedTradeCheck = DB::table('crpspecializedtrade')->where("Id",'<>',$spFinalId)->where("CrpSpecializedTradeId",'<>',$spFinalId)->where(DB::raw("TRIM(Email)"),$email)->count();

				$userEmailCheck = DB::table("sysuser")->where(DB::raw("TRIM(Email)"),$email)->count();
				$userNameCheck = DB::table("sysuser")->where(DB::raw("TRIM(username)"),$email)->count();

				if(((int)$contractorFinalCheck+(int)$contractorCheck+(int)$consultantFinalCheck+(int)$consultantCheck+(int)$architectFinalCheck+(int)$architectCheck+(int)$engineerFinalCheck+(int)$engineerCheck+(int)$specializedTradeFinalCheck+(int)$specializedTradeCheck+(int)$userEmailCheck+(int)$userNameCheck)>0){
					return Response::json(array('valid'=>false));
				}else{
					return Response::json(array('valid'=>true));
				}
			}else{
				return Response::json(array('valid'=>true));
			}
		}
	}
	public function usernameAvailability(){
		$username = Input::get('username');
		if((bool)$username){
			$username = trim($username);
			$selfUsername = Auth::user()->username;
			if(trim($selfUsername) != $username){
				$userNameCount = DB::table('sysuser')->where(DB::raw("TRIM(username)"),$username)->count();
				if((int)$userNameCount>0){
					return Response::json(array('valid'=>false));
				}else{
					return Response::json(array('valid'=>true));
				}
			}else{
				return Response::json(array('valid'=>true));
			}
		}
	}
}