<?php
class WebsiteContactUsController extends BaseController{
	public function contactUs(){
		return View::make('website.contactus');
	}

	public function contactUsMail(){
		$FullName=Input::get('FullName');
		$Email=Input::get('Email');
		$Message=Input::get('Message');
		$toEmail = Input::get('Subject');

		$mailData = array(
			'mailMessage' =>	'From: '.$FullName.'<br />Email: '.$Email.'<br />Message: '.$Message
		);
//		try{
			Mail::queue('emails.crps.mailnoticebyadministrator', $mailData, function($message) use($Email) {
				$message->to($Email)->subject('From Contact Us Page');
			});
//		}catch(Exception $e){
//			dd($e->getMessage());
//		}


		return Redirect::to('web/contactus');
	}
}