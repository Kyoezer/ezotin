<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CronForMonitoringAction extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron:monitoring';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Executes the action taking by monitoring section.';

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
		$id = $this->argument('id');
		$object = new BaseController();
		$mobileNo = NULL;
		$emailArray = array();
		$mobileNoArray = array();

		/*CORE*/
		$today = date('Y-m-d');
		if((bool)$id){
			$monitoringActions = DB::table('crpmonitoringoffice as T1')
				->join('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorFinalId')
				->whereNotNull("T1.ActionTaken")
				->where('T1.Id',$id)
				->where('T1.FromDate','<=',$today)
				->whereRaw("coalesce(T1.CronSuccessful,0)=0")
				->get(array('T1.Id','FromDate','ToDate','T2.Email','T2.MobileNo','T1.CrpContractorFinalId','T2.CDBNo','T1.ActionTaken'));
		}else{
			$monitoringActions = DB::table('crpmonitoringoffice as T1')
				->join('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorFinalId')
				->whereNotNull("T1.ActionTaken")
				->where('T1.FromDate','<=',$today)
				->whereRaw("coalesce(T1.CronSuccessful,0)=0")
				->get(array('T1.Id','FromDate','ToDate','T2.Email','T2.MobileNo','T1.CrpContractorFinalId','T2.CDBNo','T1.ActionTaken'));
		}

		DB::beginTransaction();
		try{
			foreach($monitoringActions as $monitoringAction):
				$fromDate = convertDateToClientFormat($monitoringAction->FromDate);
				$toDate = convertDateToClientFormat($monitoringAction->ToDate);
				$actionTaken = $monitoringAction->ActionTaken;
				$cdbNo = $monitoringAction->CDBNo;
				$id = $monitoringAction->Id;
				$contractorFinalId = $monitoringAction->CrpContractorFinalId;

				$applicantEmail = $monitoringAction->Email;
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

				$applicantMobileNo = $monitoringAction->MobileNo;
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
				if((int)$actionTaken == 1){
					//DOWNGRADE
					$applicationHistoryArray = array('ai_key'=>$object->UUID(),'ai_appDate'=>date('Y-m-d G:i:s'),'ai_CDBRegNum'=>trim($cdbNo),'ai_rg_type'=>'Downgrade as a result of Monitoring');
					$workClassification = DB::table('crpcontractorworkclassificationmonitoring')->where('CrpMonitoringOfficeId',$id)->get(array("CmnProjectCategoryId","CmnClassificationId"));
					DB::table('crpcontractorworkclassificationfinal')->where('Id',$contractorFinalId)->delete();

					foreach($workClassification as $categoryClass):
						$category = $categoryClass->CmnProjectCategoryId;
						$class = $categoryClass->CmnClassificationId;
						if($category == CONST_CATEGORY_W1){
							$applicationHistoryArray['ai_w1_Approved'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w1_Assessed'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w1_Applied'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
						}
						if($category == CONST_CATEGORY_W2){
							$applicationHistoryArray['ai_w2_Approved'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w2_Assessed'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w2_Applied'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
						}
						if($category == CONST_CATEGORY_W3){
							$applicationHistoryArray['ai_w3_Approved'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w3_Assessed'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w3_Applied'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
						}
						if($category == CONST_CATEGORY_W4){
							$applicationHistoryArray['ai_w4_Approved'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w4_Assessed'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
							$applicationHistoryArray['ai_w4_Applied'] = DB::table('cmncontractorclassification')->where('Id',$class)->pluck('Code');
						}

						$saveArray["CrpContractorFinalId"] = $contractorFinalId;
						$saveArray['CmnAppliedClassificationId'] = $class;
						$saveArray['CmnAppliedClassificationId'] = $class;
						$saveArray['CmnVerifiedClassificationId'] = $class;
						$saveArray['CmnProjectCategoryId'] = $category;
						$saveArray['Id'] = $object->UUID();

						ContractorWorkClassificationFinalModel::create($saveArray);
					endforeach;

					DB::table('applicationhistory')->insert($applicationHistoryArray);
					DB::table('crpmonitoringoffice')->where('Id',$id)->update(array('CronSuccessful'=>1));


					//RECORD ADV RECORD
					$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
					$contractorAdverserecordInstance->CrpContractorFinalId = $contractorFinalId;
					$contractorAdverserecordInstance->Date=date('Y-m-d');
					$contractorAdverserecordInstance->Remarks="Downgraded as a result of monitoring from $fromDate to $toDate";
					$contractorAdverserecordInstance->Type=1;
					$contractorAdverserecordInstance->CreatedBy = '894eba10-885b-11e5-ab33-5cf9dd5fc4f1';
					$contractorAdverserecordInstance->save();
					$object->sendSms("Your firm has been downgraded after monitoring.",$mobileNo);
				}else{
					//SUSPENSION
					DB::table('crpcontractorfinal')->where('Id',$contractorFinalId)->update(array("CmnApplicationRegistrationStatusId"=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED));
					DB::table('crpmonitoringoffice')->where('Id',$id)->update(array('CronSuccessful'=>1));
					$contractorUserId=ContractorFinalModel::where('Id',$contractorFinalId)->pluck('SysUserId');
					if((bool)$contractorUserId){
						$userInstance=User::find($contractorUserId);
						$userInstance->Status=0;
						$userInstance->save();
					}
					//RECORD ADV RECORD
					$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
					$contractorAdverserecordInstance->CrpContractorFinalId = $contractorFinalId;
					$contractorAdverserecordInstance->Date=date('Y-m-d');
					$contractorAdverserecordInstance->Remarks="Suspended as a result of monitoring from $fromDate to $toDate";
					$contractorAdverserecordInstance->Type=1;
					$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED;
					$contractorAdverserecordInstance->CreatedBy = '894eba10-885b-11e5-ab33-5cf9dd5fc4f1';
					$contractorAdverserecordInstance->save();
					$object->sendSms("Your firm has been suspended after monitoring.",$mobileNo);
				}

			endforeach;
		}catch(Exception $e){
			DB::rollBack();
			dd($e->getMessage());
		}

		DB::commit();

		/*END CORE*/
		$mailData = array(
			'mailMessage' => "Your firm has been downgraded/suspended after Monitoring. Contact CDB for more details."
		);
		$mailView = 'emails.crps.mailnoticebyadministrator';

		if(!empty($emailArray) && count($monitoringActions)>0){
			Mail::send($mailView,$mailData,function($message) use ($emailArray){
				$message->to($emailArray,"Applicant")->subject("Your firm has been downgraded/suspended after monitoring. Contact CDB for more details.");
			});
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('id', InputArgument::OPTIONAL, 'Id of Monitoring', NULL)
		);
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
