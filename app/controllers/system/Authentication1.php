<?php
class Authentication extends SystemController{
	public function login(){
		$username = Input::get('username');
        $password = Input::get('password');
        $now=date('Y-m-d H:i:s');
        $ipAddress=Request::getClientIp();
        $inputs = Input::all();
        $failureDetails=array('UserName'=>$username,'Password'=>$password,'IpAddress'=>$ipAddress,'AttemptDate'=>$now);
        $rules = array('username' => 'required', 'password' => 'required');
        $validation = Validator::make($inputs, $rules);
        if($validation->fails()) {
            return Redirect::to('ezhotin/home/'.Session::get('UserViewerType'))->withErrors($validation)->withInput();
        }else{
            $userdata = array('username' => $username, 'password' => $password);
            if(Auth::attempt($userdata,Input::has('RememberMe') ? true : false)){
                $defaultRoute="";
                $status=Auth::user()->Status;
                $agencyId = Auth::user()->CmnProcuringAgencyId;
                $agency = 0;
                if($agencyId){
                    $agencyCode = DB::table('cmnprocuringagency')->where('Id',$agencyId)->pluck('Code');
                    if(is_numeric($agencyCode)){
                        $agency = DB::table('cmnprocuringagency')->where('Id',$agencyId)->pluck('Name');
                    }else{
                        $agency = $agencyCode;
                    }
                }
                if((int)$status==1){
                    $userId=Auth::user()->Id;
                    $generatedId=$this->UUID();
                    $logDetails=array('Id'=>$generatedId,'SysUserId'=>$userId,'IpAddress'=>$ipAddress,'LoggedInDate'=>$now);
                    UserLogModel::create($logDetails);
                    Session::put('userLogId',$generatedId);
                    Session::put('agency',$agency);
                    $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$userId)->lists('SysRoleId');
                    if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles,true)){
                        return Redirect::to('ezhotin/adminnavoptions');
                    }else{
                        if(count($userRoles) == 2){
                            if(in_array(CONST_ROLE_PROCURINGAGENCYCINET,$userRoles,true) && in_array(CONST_ROLE_PROCURINGAGENCYETOOL,$userRoles,true)){
                                return Redirect::to('ezhotin/etoolcinetnavoptions');
                            }
                        }
                        $defaultRoute=DB::table('sysuserrolemap as T1')->join('sysrole as T2','T1.SysRoleId','=','T2.Id')->join('sysmenu as T3','T2.SysDefaultMenuId','=','T3.Id')->where('T1.SysUserId','=',$userId)->pluck('T3.MenuRoute');
                        if((bool)$defaultRoute==null){
                            return Redirect::to('ezhotin/dashboard');
                        }
                        return Redirect::to($defaultRoute);
                    }    
                }else{
					return Redirect::to('ezhotin/home/'.Session::get('UserViewerType'))->with('InvalidCredentials', 'Your account has been deactivated. Contact CDB for assistance.');
                }
            }else{
                return Redirect::to('ezhotin/home/'.Session::get('UserViewerType'))->with('InvalidCredentials', 'Invalid Username or Password.');
            }
        }
	}
	public function logout(){
		$errorMessage=Session::get('ExceptionMessage');
        $now=date('Y-m-d G:i:s');
        $newDateTime = date('h:i A', strtotime($now));
        if(Session::has('userLogId')){
            $userLogId=Session::get('userLogId');
            if((bool)$userLogId){
                DB::table('sysuserlog')->where('Id',$userLogId)->update(array('LoggedOutDate'=>date('Y-m-d G:i:s')));
            }
            Session::flush();
            Auth::logout();
            if(isset($errorMessage) && !empty($errorMessage)){
                return Redirect::to('/')->with('savedsuccessmessage',$errorMessage);
            }else{
                return Redirect::to('/')->with('savedsuccessmessage', 'You logged out at '.$newDateTime);
            }
        }else{
            return Redirect::to('/')->with('savedsuccessmessage', 'You logged out at '.$newDateTime);
        }
        
	}
    public function resetAndSendPassword(){
        $username = Input::get('username');
        $isUser = DB::table('sysuser')->where('username',$username)->count();
        if($isUser == 0){
            return Redirect::to('')->with('customerrormessage','This username is not registered with any username');
        }
        $email = DB::table('sysuser')->where('username',$username)->pluck('Email');
        if(!$email){
            $email = $username;
        }
        if($email){
            $fullName = DB::table('sysuser')->where('username',$username)->pluck('FullName');
            $newPassword = randomString().randomString();
            $newHashedPassword = Hash::make($newPassword);
            DB::table('sysuser')->where('username',$username)->update(array('password'=>$newHashedPassword));
            $message = "Your password has been successfully reset. Your new password is:<br/> $newPassword<br/> Please login with this and change it if you wish to";
            $mailData=array(
                'mailMessage'=>$message
            );
            $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Password Reset Successfully",$email,$fullName);
            return Redirect::to('/')->with('savedsuccessmessage','Your password has been reset successfully. Please check the email address associated with your account for the new password');
        }else{
            return Redirect::to('/')->with('customerrormessage','You do not have an email associated with your account. Please contact administrator');
        }
    }
}