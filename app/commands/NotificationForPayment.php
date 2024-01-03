<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NotificationForPayment extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'notification:payment';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$emailArray = array();
		$mobileNoArray = array();
		$object = new BaseController();

		$applicants = DB::select("select T1.Id,'1' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpcontractor T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 1),T1.RegistrationApprovedDate)),15) = 15 union
select T1.Id,'2' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpconsultant T1 left join crpconsultantfinal T2 on T2.Id = T1.CrpConsultantId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 2),T1.RegistrationApprovedDate)),15) = 15 union
select T1.Id,'3' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crparchitect T1 left join crparchitectfinal T2 on T2.Id = T1.CrpArchitectId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 3),T1.RegistrationApprovedDate)),15) = 15 union
select T1.Id,'4' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpspecializedtrade T1 left join crpspecializedtradefinal T2 on T2.Id = T1.CrpSpecializedTradeId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 4),T1.RegistrationApprovedDate)),15) = 15",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT));
		foreach($applicants as $applicant):
			$count = DB::table('crpapplicantpaymentnotice')->where('ApplicantId',$applicant->Id)->count();
			if((int)$count == 1){
				if((int)$applicant->TypeCode == 1){
					DB::table('crpcontractor')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
				}elseif((int)$applicant->TypeCode == 2){
					DB::table('crpconsultant')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
				}elseif((int)$applicant->TypeCode == 3){
					DB::table('crparchitect')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
				}elseif((int)$applicant->TypeCode == 4){
					DB::table('crpspecializedtrade')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
				}else{
					//ENGINEER
				}
			}else{
				$saveArray['Id'] = $object->UUID();
				$saveArray['ApplicantId'] = $applicant->Id;
				$saveArray['TypeCode'] = $applicant->TypeCode;
				$saveArray['DateOfNotification'] = date('Y-m-d');
				DB::table('crpapplicantpaymentnotice')->insert($saveArray);
				
				$applicantEmail = $applicant->Email;
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

				$applicantMobileNo = $applicant->MobileNo;
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
			}
			
		endforeach;

		$mailData = array(
			'mailMessage' => "Your CDB application is pending payment. Please pay the fees at the Nearest Regional Revenue and Customs Office (RRCO)."
		);
		$mailView = 'emails.crps.mailnoticebyadministrator';
		if(!empty($emailArray)){
			Mail::send($mailView,$mailData,function($message) use ($emailArray){
				$message->to($emailArray,"Applicant")->subject("CDB Payment Pending");
			});
		}
		foreach($mobileNoArray as $singleMobileNo):
			$object->sendSms("Your CDB application is pending payment,please pay at nearest RRCO.",$singleMobileNo);
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
