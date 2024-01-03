<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NotificationForExpiry extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'notification:expiry';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sends email and sms notification to applicants whose registration will expire in a month.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$object = new BaseController();

		$emailArray = array();
		$mobileNoArray = array();
		$today = date_create(date('Y-m-d'));
		date_add($today,date_interval_create_from_date_string("30 days"));
		$contractors = DB::table('crpcontractorfinal as T1')
			->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
			->where('T1.RegistrationExpiryDate',date_format($today,'Y-m-d'))
			->get(array('T1.CDBNo','T1.MobileNo','T1.Email'));
		$expiringApplicants = DB::select("select T1.CDBNo, T1.MobileNo, T1.Email from crpcontractorfinal T1 where T1.CmnApplicationRegistrationStatusId = ? and T1.RegistrationExpiryDate = ? union all select T1.CDBNo, T1.MobileNo, T1.Email from crpconsultantfinal T1 where T1.CmnApplicationRegistrationStatusId = ? and T1.RegistrationExpiryDate = ? union all select T1.ArNo as CDBNo, T1.MobileNo, T1.Email from crparchitectfinal T1 where T1.CmnApplicationRegistrationStatusId = ? and T1.RegistrationExpiryDate = ? union all select T1.SPNo as CDBNo, T1.MobileNo, T1.Email from crpspecializedtradefinal T1 where T1.CmnApplicationRegistrationStatusId = ? and T1.RegistrationExpiryDate = ?",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,date_format($today,'Y-m-d'),CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,date_format($today,'Y-m-d'),CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,date_format($today,'Y-m-d'),CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,date_format($today,'Y-m-d')));
		foreach($expiringApplicants as $expiringApplicant):
			$applicantEmail = $expiringApplicant->Email;
			if((bool)$applicantEmail && $applicantEmail != "" && strpos($applicantEmail,'@')>-1){
				if(strpos($applicantEmail,'/')>-1){
					$indexOfSlash = strpos($applicantEmail,'/');
					$firstEmail = substr($applicantEmail,0,$indexOfSlash);
					array_push($emailArray,$firstEmail);
				}elseif(strpos($applicantEmail,',')>-1){
					$indexOfComma = strpos($applicantEmail,',');
					$firstEmail = substr($applicantEmail,0,$indexOfComma);
					array_push($emailArray,$firstEmail);
				}else{
					array_push($emailArray,trim($applicantEmail));
				}
			}

			$applicantMobileNo = $expiringApplicant->MobileNo;
			if((bool)$applicantMobileNo && $applicantMobileNo != ""){
				if(strpos($applicantMobileNo,'/')>-1){
					$indexOfSlash = strpos($applicantMobileNo,'/');
					$firstMobileNo = substr($applicantMobileNo,0,$indexOfSlash);
					array_push($mobileNoArray,$firstMobileNo);
				}elseif(strpos($applicantMobileNo,',')>-1){
					$indexOfComma = strpos($applicantMobileNo,',');
					$firstMobileNo = substr($applicantMobileNo,0,$indexOfComma);
					array_push($mobileNoArray,$firstMobileNo);
				}else{
					array_push($mobileNoArray,trim($applicantMobileNo));
				}
			}
		endforeach;
		$mailData = array(
			'mailMessage' => "Your CDB certificate is going to expire in a month. Please apply for renewal."
		);
		$mailView = 'emails.crps.mailnoticebyadministrator';

		if(!empty($emailArray)){
			Mail::send($mailView,$mailData,function($message) use ($emailArray){
				$message->to($emailArray,"Applicant")->subject("CDB Certificate nearing Expiry");
			});
		}
		foreach($mobileNoArray as $singleMobileNo):
			$object->sendSms("Your CDB Certificate is going to expire. Please renew.",$singleMobileNo);
		endforeach;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
