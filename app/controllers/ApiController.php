<?php
class ApiController extends BaseController {

	
	public function getMethod($id)
	{
		var_dump($id.'getMethod');
		return Response::json(['success'=>true,'desc'=>$id]);
	}
	public function uploadTender()
	{
		$inputs = Input::all();
		TenderModel::create($inputs);
		return Response::json(['success'=>true,'desc'=>$inputs]);
	}
	public function userValidation($username,$password)
	{
		$inputs = Input::all();
		$userdata = array('username' => $username, 'password' => $password);
		$validation = Auth::attempt($userdata,Input::has('RememberMe'));
		if($validation)
		{
			$roles=DB::select("SELECT COUNT(*) userExist,a.FullName FROM `sysuser` a, sysuserrolemap b WHERE a.username='".$username."' AND b.SysRoleId='c6bc83ae-b140-11ea-9f40-bc305be5439f' AND a.`Id`=b.`SysUserId`");
			if($roles[0]->userExist>0)
			{
				return Response::json(['success'=>true,'FULL_NAME'=>$roles[0]->FullName , 'VALIDATION'=>"SUCCESS"]); 
			}
			else
			{
				return Response::json(['success'=>true, 'VALIDATION'=>"FAILED"]);   
			}
		}
		else
		{  
			return Response::json(['success'=>true, 'VALIDATION'=>"FAILED"]); 

		}
	}

   
	public function sendMail($name,$email,$subject,$message){  
		
		 $mailData = array('mailMessage' =>$message);
		$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,$subject,$email,$name);
		return "Successfully sent";
	}
	
}
