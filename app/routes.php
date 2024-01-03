<?php
	/*
	Crafted with love and lots of Coffee
	Name: Kinley Nidup
	Web Name: Zero Cool
	email: nidup.kinley@gmail.com
	facebook link:https://www.facebook.com/kgyel
	*/
	Route::get('noticecheck',function(){
		$emailArray = array();
			$mobileNoArray = array();
			$object = new BaseController();

			// $applicants = DB::select("select T1.Id,'1' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo,coalesce(DATEDIFF(NOW(),(select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = '592d79a2-6b89-11e7-8cbd-c81f66edb959' and A.TypeCode = 1)),15) as difference, coalesce(T1.Email,T2.Email) as Email from crpcontractor T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorId where T1.Id = '592d79a2-6b89-11e7-8cbd-c81f66edb959' and coalesce(DATEDIFF(NOW(),(select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = '592d79a2-6b89-11e7-8cbd-c81f66edb959' and A.TypeCode = 1)),15) = 15");
			$applicants = DB::select("select T1.Id,'1' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpcontractor T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 1),T1.RegistrationApprovedDate)),15) = 15 union
	select T1.Id,'2' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpconsultant T1 left join crpconsultantfinal T2 on T2.Id = T1.CrpConsultantId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 2),T1.RegistrationApprovedDate)),15) = 15 union
	select T1.Id,'3' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crparchitect T1 left join crparchitectfinal T2 on T2.Id = T1.CrpArchitectId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 3),T1.RegistrationApprovedDate)),15) = 15 union
	select T1.Id,'3' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpsurvey T1 left join crpsurveyfinal T2 on T2.Id = T1.CrpSurveyId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 3),T1.RegistrationApprovedDate)),15) = 15 union
	select T1.Id,'4' as TypeCode,coalesce(T1.MobileNo,T2.MobileNo) as MobileNo, coalesce(T1.Email,T2.Email) as Email from crpspecializedtrade T1 left join crpspecializedtradefinal T2 on T2.Id = T1.CrpSpecializedTradeId where T1.CmnApplicationRegistrationStatusId = ? and coalesce(DATEDIFF(NOW(),coalesce((select max(A.DateOfNotification) from crpapplicantpaymentnotice A where A.ApplicantId = T1.Id and A.TypeCode = 4),T1.RegistrationApprovedDate)),15) = 15",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT));
			echo "<PRE>"; //dd($applicants);
			foreach($applicants as $applicant):
				$count = DB::table('crpapplicantpaymentnotice')->where('ApplicantId',$applicant->Id)->count();
				dd($count);
				if((int)$count == 1){
					if((int)$applicant->TypeCode == 1){
						DB::table('crpcontractor')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
					}elseif((int)$applicant->TypeCode == 2){
						DB::table('crpconsultant')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
					}elseif((int)$applicant->TypeCode == 3){
						DB::table('crparchitect')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
					}elseif((int)$applicant->TypeCode == 3){
						DB::table('crpsurvey')->where('Id',$applicant->Id)->update(array('SysLockedByUserId'=>NULL,'CmnApplicationRegistrationStatusId'=>CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED));
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
				'mailMessage' => "Your CDB application is pending payment. Please pay the fees at the Nearest Regional Revenue and Customs Office (RRCO) or at CDB Office within 15 days to avoic cancellation of your application."
			);
			$mailView = 'emails.crps.mailnoticebyadministrator';
			if(!empty($emailArray)){
				// Mail::send($mailView,$mailData,function($message) use ($emailArray){
				//     $message->to($emailArray,"Applicant")->subject("CDB Payment Pending");
				// });
			}
			foreach($mobileNoArray as $singleMobileNo):
				// $object->sendSms("Your CDB application is pending payment,please pay at CDB or nearest RRCO.",$singleMobileNo);
			endforeach;
	});
	Route::get('rcsccheck/{cid}','WebServiceG2C@getHrCheck');
	Route::post('registrationexistingapplicants',array('before'=>'captchacheck','uses'=>'UserManagement@registerExistingApplicant'));
	Route::post('rstawebservice','WebServiceRSTA@getVehicleDetails');
	Route::get('workid',function(){
		dd(getWorkId('2016','40993fff-24ce-11e6-967f-9c2a70cc8e06'));
	});
	Route::post('test','BaseController@postTest');
	Route::get('getunpaidbeforedate/{date}','CrpsController@getUnpaid');
	Route::get('getunpaidconsultantsbeforedate/{date}','CrpsController@getUnpaidConsultants');
	Route::get('sendconsultantservicepaymentapproved/{id}',function($id){
		try{
			$newRegistrationAmount = CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->pluck('ConsultantAmount');
			$consultantDetails=ConsultantModel::consultantHardList($id)->get(array('CrpConsultantId','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount','RemarksByApprover','RemarksByVerifier'));
			$hasWaiver = $consultantDetails[0]->WaiveOffLateFee;
			$newLateFeeAmount = $consultantDetails[0]->NewLateFeeAmount;
			$mailView="emails.crps.mailserviceapplicationapproved";
			$subject="Approval of Service Application";
			$finalConsultantId=$consultantDetails[0]->CrpConsultantId;
			$recipientAddress=$consultantDetails[0]->Email;
			$recipientName=$consultantDetails[0]->NameOfFirm;
			$applicationNo=$consultantDetails[0]->ReferenceNo;
			$applicationDate=$consultantDetails[0]->ApplicationDate;
			$mobileNo=$consultantDetails[0]->MobileNo;
			$remarksByVerifier = $consultantDetails[0]->RemarksByVerifier;
			$remarksByApprover = $consultantDetails[0]->RemarksByApprover;

			/*Start Fee Structure */
			$serviceApplicationApprovedForPayment=0;
			$hasFee=false;
			$hasRenewal=false;
			$hasLateFee=false;
			$hasChangeInCategoryClassification=false;
			$hasCategoryClassificationsFee=array();
			$existingCategoryServicesArray=array();
			$hasLateFeeAmount=array();
			$consultantFinalTableId=consultantModelConsultantId($id);
			/*-----------------------------------------------------------------------------------------------------*/
			$feeAmount=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$appliedServices=ConsultantAppliedServiceModel::appliedService($id)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
			foreach($appliedServices as $appliedService){
				if((int)$appliedService->HasFee==1){
					$hasFee=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
					$hasRenewal=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
					$hasChangeInCategoryClassification=true;
				}
			}
			if($hasRenewal || $hasChangeInCategoryClassification){
				$hasCategoryClassificationsFee=DB::select("select T1.Id as ServiceCategoryId,T1.Name as ServiceCategoryName,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($id,$id,$id));
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($id));
			}
			/* ENd fee structure */

			/*---*/
			foreach($hasCategoryClassificationsFee as $singleCategory):
				$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
				$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
				$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
				$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			endforeach;
			/*---*/

			$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
			$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
			$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService($id)->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Id as ServiceId','T1.Code as ServiceCode','T1.Name as ServiceName'));
			$verifiedCategoryServices=ConsultantWorkClassificationModel::verifiedService($id)->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
			$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnApprovedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array($id));
			$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
			$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.  However, you need to pay your fees within one month (30 days) as per the details given below to CDB office or the Nearest Regional Revenue and Customs Office (RRCO). Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt. We will email you your username and password upon confirmation of your payment by CDB.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
		}catch(Exception $e){
			throw $e;
		}
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'newRegistrationAmount'=>$newRegistrationAmount,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'existingCategoryServicesArray'=>$existingCategoryServicesArray,
			'appliedCategoryServicesArray'=>$appliedCategoryServicesArray,
			'verifiedCategoryServicesArray'=>$verifiedCategoryServicesArray,
			'approvedCategoryServicesArray'=>$approvedCategoryServicesArray,
			'appliedServices'=>$appliedServices,
			'feeAmount'=>$feeAmount,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>$emailMessage
		);
		$baseControllerObject = new BaseController();
		$baseControllerObject->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$baseControllerObject->sendSms($smsMessage,$mobileNo);
		dd('here');
	});
	Route::get('sendmailcontractorpaymentapproved/{id}',function($id){
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$hasWaiver = 0;
		$newLateFeeAmount = 0;

		$contractorFinalTableId=contractorModelContractorId($id);
		$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFinalId',$contractorFinalTableId)->pluck('MaxClassificationPriority');
		$appliedServices=ContractorAppliedServiceModel::appliedService($id)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));

		$contractorDetails=ContractorModel::contractorHardList($id)->get(array('CrpContractorId','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount'));
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Approval of Service Application";
		$finalContractorId=$contractorDetails[0]->CrpContractorId;
		$recipientAddress=$contractorDetails[0]->Email;
		$recipientName=$contractorDetails[0]->NameOfFirm;
		$applicationNo=$contractorDetails[0]->ReferenceNo;
		$applicationDate=$contractorDetails[0]->ApplicationDate;
		$mobileNo=$contractorDetails[0]->MobileNo;
		$hasWaiver = $contractorDetails[0]->WaiveOffLateFee;
		$newLateFeeAmount = $contractorDetails[0]->NewLateFeeAmount;

		/* FOR FEE STRUCTURE */
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				$hasFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
				$hasRenewal=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
				$hasLateFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
				$hasChangeInCategoryClassification=true;
			}
		}
		if($hasRenewal || $hasChangeInCategoryClassification){
			$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($id,$contractorFinalTableId,$id,$id,$id,$contractorFinalTableId));
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($id));
		}
		/* END FOR FEE STRUCTURE */
		$mailIntendedTo=1;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array(Input::get('ContractorReference')));
		$emailMessage="Thank you for your application. We are glad to inform you that your application for availing service has been approved by CDB. However, you need to pay your registration fees to nearest goverment revenue collecting office.";
		$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";

		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'feeStructures'=>$feeStructures,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'appliedServices'=>$appliedServices,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'maxClassification'=>$maxClassification,
			'mailMessage'=>$emailMessage
		);

	//	$recipientAddress = "sangay.wangdi.moktan@gmail.com";
	//	$this->sendEmailMessage($mailView,$mailData,$subject,"sangay.wangdi.moktan@gmail.com",$recipientName);
		Mail::send($mailView,$mailData,function($message) use ($recipientAddress,$recipientName,$subject){
			$message->to($recipientAddress,$recipientName)->subject($subject);
		});
		$message = rawurlencode($smsMessage);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://172.30.16.136/g2csms/push.php?to='.$mobileNo.'&msg=' . $message,
		));
		$resp = curl_exec($curl);
		curl_close($curl);

		dd('done');
	});
	Route::get("sendmailarchitectpaymentapproved",function(){
		$architectDetails=ArchitectModel::architectHardList("d6424df2-766a-11e6-8ceb-c81f66edb959")->get(array('ARNo','CmnServiceSectorTypeId','Name','Email','ReferenceNo','ApplicationDate','MobileNo'));
		$ArNo=$architectDetails[0]->ArNo;
		$recipientAddress=$architectDetails[0]->Email;
		$recipientName=$architectDetails[0]->Name;
		$referenceNo=$architectDetails[0]->ReferenceNo;
		$applicationDate=$architectDetails[0]->ApplicationDate;
		$mobileNo=$architectDetails[0]->MobileNo;
		$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
		$plainPassword.="@#".date('d');
		$password=Hash::make($plainPassword);
		if($architectDetails[0]->CmnServiceSectorTypeId==CONST_CMN_SERVICESECTOR_PVT){
			$feeDetails=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		}else{
			$feeDetails=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		}
		$mailView="emails.crps.mailregistrationpaymentcompletion";
		$subject="Activation of Your CDB Certificate";
		$mailData=array(
			'mailIntendedTo' => 3,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'username'=>$recipientAddress,
			'password'=>$plainPassword,
			'feeStructures'=>$feeDetails,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for architect registration with Construction Development Board (CDB). Your AR No. is ".$ArNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.",
		);
		$smsMessage="Your registration fees for architect registration has been received by CDB and your certificate has been activated. Your AR No. is $ArNo. Your username is $recipientAddress and password is $plainPassword";
		Mail::send($mailView,$mailData,function($message) use ($recipientAddress,$recipientName,$subject){
			$message->to($recipientAddress,$recipientName)->subject($subject);
		});
		$message = rawurlencode($smsMessage);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://172.30.16.136/g2csms/push.php?to=' . $mobileNo . '&msg=' . $message,
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		dd($password);
	});
	Route::get("sendrejectedmailcontractor",function(){
		$contractorDetails=ContractorModel::contractorHardList("d3ff2aa7-709c-11e6-8ceb-c81f66edb959")->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
		/*----------------------Contractor Email Details and New Details------------------*/
		$recipientAddress=$contractorDetails[0]->Email;
		dd($recipientAddress);
		$recipientName=$contractorDetails[0]->NameOfFirm;
		$applicationNo=$contractorDetails[0]->ReferenceNo;
		$applicationDate=$contractorDetails[0]->ApplicationDate;
		$remarksByRejector=$contractorDetails[0]->RemarksByRejector;
		$rejectionSysCode=$contractorDetails[0]->SysRejectionCode;
		$mobileNo=$contractorDetails[0]->MobileNo;
		$mailView="emails.crps.mailapplicationrejected";
		$subject="Rejection of Your Registration with CDB";
		$mailData=array(
			'prefix'=>'contractor',
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'remarksByRejector'=>$remarksByRejector,
			'referenceApplicant'=>Input::get('ContractorReference'),
			'rejectionSysCode'=>$rejectionSysCode,
			'mailMessage'=>"Construction Development Board (CDB) has rejected your application for registration of contractor with CDB. Please read the reason for rejection given below and reapply by making the necessary corrections.",
		);
		Mail::send($mailView,$mailData,function($message) use ($recipientAddress,$recipientName,$subject){
			$message->to($recipientAddress,$recipientName)->subject($subject);
		});
		$smsMessage="Your application for contractor registration has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
		$message = rawurlencode($smsMessage);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://172.30.16.136/g2csms/push.php?to=' . $mobileNo . '&msg=' . $message,
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		return $resp;
	});
	Route::post("checksession","SystemController@checkSession");
	Route::get('sendmailapplicant',function(){
		$consultantId="b055bb60-54b2-11e6-8cbc-c81f66edb959";
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Acknowledgement: Receipt of Application for CDB Service";
		$consultantDetails=ConsultantModel::consultantHardList($consultantId)->get(array('CrpConsultantId','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount'));
		$hasWaiver = $consultantDetails[0]->WaiveOffLateFee;
		$newLateFeeAmount = $consultantDetails[0]->NewLateFeeAmount;
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Approval of Service Application";
		$finalConsultantId=$consultantDetails[0]->CrpConsultantId;
		$recipientAddress=$consultantDetails[0]->Email;
	//	$recipientAddress="kinleytd35@yahoo.com";
		$recipientName=$consultantDetails[0]->NameOfFirm;
		$applicationNo=$consultantDetails[0]->ReferenceNo;
		$applicationDate=$consultantDetails[0]->ApplicationDate;
		$mobileNo=$consultantDetails[0]->MobileNo;

		/*Start Fee Structure */
		$serviceApplicationApprovedForPayment=0;
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$consultantFinalTableId=consultantModelConsultantId($consultantId);
		/*-----------------------------------------------------------------------------------------------------*/
		$feeAmount=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$appliedServices=ConsultantAppliedServiceModel::appliedService($consultantId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				$hasFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
				$hasRenewal=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
				$hasLateFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
				$hasChangeInCategoryClassification=true;
			}
		}
		if($hasRenewal || $hasChangeInCategoryClassification){
			$hasCategoryClassificationsFee=DB::select("select T1.Id as ServiceCategoryId,T1.Name as ServiceCategoryName,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($consultantId,$consultantId,$consultantId));
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($consultantId));
		}
		/* ENd fee structure */

		/*---*/
		foreach($hasCategoryClassificationsFee as $singleCategory):
			$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
			$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
			$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
		endforeach;
		/*---*/

		$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService(Input::get('ConsultantReference'))->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Id as ServiceId','T1.Code as ServiceCode','T1.Name as ServiceName'));
		$verifiedCategoryServices=ConsultantWorkClassificationModel::verifiedService(Input::get('ConsultantReference'))->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
		$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnApprovedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
		$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
		$emailMessage="This is to acknowledge receipt of your application for Construction Development Board (CDB)  service. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=�#�>CDB website</a>. You will also be notified through email when your application is approved.";

		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'existingCategoryServicesArray'=>$existingCategoryServicesArray,
			'appliedCategoryServicesArray'=>$appliedCategoryServicesArray,
			'verifiedCategoryServicesArray'=>$verifiedCategoryServicesArray,
			'approvedCategoryServicesArray'=>$appliedCategoryServicesArray,
			'appliedServices'=>$appliedServices,
			'feeAmount'=>$feeAmount,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'applicationStage'=>true,
			'mailMessage'=>$emailMessage
		);
		Mail::send($mailView,$mailData,function($message) use ($recipientAddress,$recipientName,$subject){
			$message->to($recipientAddress,$recipientName)->subject($subject);
		});

		return Redirect::to('consultant/mydashboard')->with('savedsuccessmessage','Your application was successfully submitted');
	});
	Route::get('checkhumanresourcecount',array('uses'=>'BaseController@tallyHR'));
	Route::get('checkequipmentcount',array('uses'=>'BaseController@tallyEq'));
	Route::post('senddeleterequest','CrpsController@sendDeleteRequest');
	Route::post('sendapplicationback','CrpsController@sendApplicationBack');
	Route::get('dropapplication','CrpsController@dropApplication');
	Route::post('loadcount','EzhotinHomeController@refreshDashboard');
	Route::get('/',function(){
		$hasError = false;
		$hasSuccess = false;
		if(Session::has('customerrormessage')){
			$hasError = true;
			return Redirect::to('web/index')->with('customerrormessage',Session::get('customerrormessage'));
		}
		if(Session::has('savedsuccessmessage')){
			$hasSuccess = true;
			return Redirect::to('web/index')->with('savedsuccessmessage',Session::get('savedsuccessmessage'));
		}
		if(!$hasSuccess && !$hasError){
			return Redirect::to('web/index');
		}

	});
	Route::get('etoolmanual',function(){
	return View::make('etoolmanual');
	});
	Route::get('cinetmanual',function(){
		return View::make('cinetmanual');
	});
	Route::get('reportassign',array('uses'=>'RoleManagement@assignReportsToRole'));
	Route::get('createpagencyuseretool',function(){
		set_time_limit(0);
		$updatepassword=DB::select("select PAName,PAgency,login_name,name,password,email from pa_users");
		foreach($updatepassword as $v){
			$pAgencyId=DB::table('cmnprocuringagency')->where('Code',$v->PAgency)->pluck('Id');
			if((bool)$pAgencyId == NULL){
				$pAgencyId = DB::table('cmnprocuringagency')->where('Name',$v->PAName)->pluck('Id');
			}
			$username=$v->login_name.'@etool.bt';
			$hashedPass=Hash::make($v->password);
			$email = $v->email;
			$fullName=$v->name;
			$cmnProcuringAgencyId=$pAgencyId;
			if((bool)$pAgencyId!=NULL){
				DB::beginTransaction();
				try{
					$uuid=DB::select("select uuid() as Id");
					$generatedId=$uuid[0]->Id;
					$instanceUser=new User;
					$instanceUser->Id=$generatedId;
					$instanceUser->username=$username;
					$instanceUser->password=$hashedPass;
					$instanceUser->FullName=$fullName;
					$instanceUser->Email=$email;
					$instanceUser->Status=1;
					$instanceUser->CmnProcuringAgencyId=$pAgencyId;
					$instanceUser->save();

					$roleInstance= new RoleUserMapModel;
					$roleInstance->SysUserId=$generatedId;
					$roleInstance->SysRoleId=CONST_ROLE_PROCURINGAGENCYETOOL;
					$roleInstance->save();
				}catch(Exception $e){
					DB::rollback();
					throw $e;
				}
				DB::commit();
			}
		}
		echo "Users Created";
	});
	Route::get('createpagencyusercinet',function(){
		set_time_limit(0);
		$updatepassword=DB::select("select PAName,login_name,name,password from users");
		foreach($updatepassword as $v){
			$pAgencyId=DB::table('cmnprocuringagency')->where('Code',$v->PAName)->pluck('Id');
			if((bool)$pAgencyId == NULL){
				$pAgencyId = DB::table('cmnprocuringagency')->where('Name',$v->PAName)->pluck('Id');
			}
			$username=$v->login_name.'@cinet.bt';
			$hashedPass=Hash::make($v->password);
	//        $email = $v->email;
			$fullName=$v->name;
			$cmnProcuringAgencyId=$pAgencyId;
			if((bool)$pAgencyId!=NULL){
				DB::beginTransaction();
				try{
					$uuid=DB::select("select uuid() as Id");
					$generatedId=$uuid[0]->Id;
					$instanceUser=new User;
					$instanceUser->Id=$generatedId;
					$instanceUser->username=$username;
					$instanceUser->password=$hashedPass;
					$instanceUser->FullName=$fullName;
	//                $instanceUser->Email=$email;
					$instanceUser->Status=1;
					$instanceUser->CmnProcuringAgencyId=$pAgencyId;
					$instanceUser->save();

					$roleInstance= new RoleUserMapModel;
					$roleInstance->SysUserId=$generatedId;
					$roleInstance->SysRoleId=CONST_ROLE_PROCURINGAGENCYCINET;
					$roleInstance->save();
				}catch(Exception $e){
					DB::rollback();
					throw $e;
				}
				DB::commit();
			}
		}
		echo "Users Created";
	});
	Route::post('deletedbrow','BaseController@deleteDbRow');
	Route::post('usernameavalibility/{flag?}',array('uses'=>'UserManagement@checkUserNameAvailbality'));
	Route::post('arbitratorusernameavalibility',array('uses'=>'ArbitrationForum@checkUserNameAvailability'));
	Route::post('pullfirmnameregistration',array('uses'=>'UserManagement@pullFirmNameRegistration'));
	Route::post('hrcheck',array('as'=>'etoolrpt.hrcheck','uses'=>'ReportHRCheck@getIndex'));
	Route::get('hrcheck',array('as'=>'etoolrpt.hrcheck','uses'=>'ReportHRCheck@getIndex'));

	Route::get('equipmentcheck',array('as'=>'etoolrpt.equipmentcheck','uses'=>'ReportEquipmentCheck@getIndex'));
	Route::post('equipmentcheck',array('as'=>'etoolrpt.equipmentcheck','uses'=>'ReportEquipmentCheck@getIndex'));
	Route::post('checkeqdbandwebservice','BaseController@postCheckEqDbandWebService');
	Route::post('checkemailavailabilityapplicants',array('uses'=>'CrpsController@checkEmailAvailability'));
	Route::post('checkproposednamecontractor',array('uses'=>'Contractor@checkProposedName'));
	Route::post('checkproposednameconsultant',array('uses'=>'Consultant@checkProposedName'));
	Route::group(array('prefix' => 'ezhotin'),function(){
		Route::get('home/{type}',array('uses'=>'EzhotinHomeController@ezhotinIndex'))->where('type', '[1-4]+');
		Route::get('registrationsuccess',array('as'=>'applicantregistrationsuccess','uses'=>'EzhotinHomeController@ezhotinRegistrationSuccess'));
		Route::get('showcaptcha',array('uses'=>'EzhotinHomeController@showCaptcha'));
		Route::get('rejectedapplicationmessage',array('uses'=>'EzhotinHomeController@rejectedApplicationMessage'));
		Route::get('forgotpassword',array('uses'=>'EzhotinHomeController@getForgotPassword'));
	});
	Route::group(array('prefix' => 'auth'),function(){
		Route::get('logout',array('uses' => 'Authentication@logout'));
		Route::get('showcaptcha',array('uses'=>'Authentication@showCaptcha'));
		Route::post('mauthenticate',array('uses' => 'Authentication@login'));
		Route::post('mresetandsendpassword',array('uses' => 'Authentication@resetAndSendPassword'));
	});
	Route::group(array('prefix'=>'monitoringreport'),function(){
		Route::get('officenew',array('as'=>'monitoringreport.officenew','uses'=>"MonitoringReport@getOfficeIndex"));
		Route::get('officerecord/{id}',array('as'=>'monitoringreport.officerecord','uses'=>"MonitoringReport@getOfficeRecord"));
		Route::post("saveoffice","MonitoringReport@postSaveOffice");
		Route::get('officeedit/{id}',array('as'=>'monitoringreport.officeedit','uses'=>"MonitoringReport@getOfficeEdit"));
		Route::get('officedelete/{id}',array('as'=>'monitoringreport.officedelete','uses'=>"MonitoringReport@getOfficeDelete"));
		Route::get('officeview/{id}',array('as'=>'monitoringreport.officeview','uses'=>"MonitoringReport@getOfficeEdit"));
		Route::get('officeaction',array('as'=>'monitoringreport.officeaction','uses'=>"MonitoringReport@getOfficeList"));
		Route::get('officelist',array('as'=>'monitoringreport.officelist','uses'=>"MonitoringReport@getOfficeList"));
		Route::post('fetchcontractorworkclassification',array('uses'=>"MonitoringReport@fetchContractorWorkClassification"));
		Route::post('suspendcontractor',array('uses'=>"MonitoringReport@postSuspendContractor"));
		Route::post('warngincontractor',array('uses'=>"MonitoringReport@postWarningContractor"));

		Route::get('sitenew',array('as'=>'monitoringreport.sitenew','uses'=>"MonitoringReport@getSiteIndex"));
		Route::get('sitecontractorinfo/{id}',array('as'=>'monitoringreport.sitecontractorinfo','uses'=>"MonitoringReport@getSiteContractorInfo"));
		Route::get('siterecord/{id}/{type}',array('as'=>'monitoringreport.siterecord','uses'=>"MonitoringReport@getSiteRecord"));
		Route::post("savesite","MonitoringReport@postSaveSite");
		Route::get('sitelist',array('as'=>'monitoringreport.sitelist','uses'=>"MonitoringReport@getSiteList"));
		Route::get('siteedit/{id}',array('as'=>'monitoringreport.siteedit','uses'=>"MonitoringReport@getSiteEdit"));
		Route::get('siteview/{id}',array('as'=>'monitoringreport.siteview','uses'=>"MonitoringReport@getSiteEdit"));
	});
	Route::group(array('prefix'=>'contractor'),function(){
		Route::get('apprejected/{contractorreference}/{securitycode}',array('uses'=>'Contractor@checkRejectedSecurityCode'));
		Route::get('printregistration/{contractorId}',array('uses'=>'Contractor@printDetails'));
		Route::get('default',array('uses'=>'Contractor@defaultIndex'));
		//Route::get('generalinforegistration/{contractor?}',array('uses'=>'Contractor@generalInfoRegistration'));
		Route::get('workclassificationregistration/{contractor?}',array('uses'=>'Contractor@workClassificationRegistration'));
		Route::get('humanresourceregistration/{contractor?}',array('uses'=>'Contractor@humanResourceRegistration'));
		Route::get('equipmentregistration/{contractor?}',array('uses'=>'Contractor@equipmentRegistration'));
		Route::get('confirmregistration',array('uses'=>'Contractor@confirmRegistration'));
		Route::post('mcontractorgeneralinfo',array('uses'=>'Contractor@saveGeneralInfo'));
		Route::post('mcontractorworkclassification',array('uses'=>'Contractor@saveWorkClassification'));
		Route::post('mcontractorworkclassificationfinal',array('uses'=>'Contractor@saveWorkClassificationFinal'));
		Route::post('mcontractorhumanresource',array('uses'=>'Contractor@saveHumanResource'));
		Route::post('mcontractorequipments',array('uses'=>'Contractor@saveEquipment'));
		Route::post('fetchcontractoroncdbno',array('uses'=>'Contractor@postFetchContractorOnCDBNo'));
		Route::post('mconfirmregistration',array('before'=>'captchacheck','uses'=>'Contractor@saveConfirmation'));
		Route::get('feestructure/{contractorid?}',array('uses'=>'Contractor@getFeeStructure'));
	});
	Route::group(array('prefix' => 'consultant'),function(){
		Route::get('editfeestructure','FeeStructure@getConsultantFeeStructure');
		Route::get('apprejected/{consultantreference}/{securitycode}',array('uses'=>'Consultant@checkRejectedSecurityCode'));
		Route::get('printregistration/{consultantId}',array('uses'=>'Consultant@printDetails'));
		Route::get('default',array('uses'=>'Consultant@defaultIndex'));
		Route::get('generalinforegistration/{consultant?}',array('uses'=>'Consultant@generalInfoRegistration'));
		Route::get('workclassificationregistration/{consultant?}',array('uses'=>'Consultant@workClassificationRegistration'));
		Route::get('humanresourceregistration/{consulrant?}',array('uses'=>'Consultant@humanResourceRegistration'));
		Route::get('equipmentregistration/{consultant?}',array('uses'=>'Consultant@equipmentRegistration'));
		Route::get('confirmregistration',array('uses'=>'Consultant@confirmRegistration'));
		Route::post('mconsultantgeneralinfo',array('uses'=>'Consultant@saveGeneralInfo'));
		Route::post('mconsultantworkclassification',array('uses'=>'Consultant@saveWorkClassification'));
		Route::post('mconsultanthumanresource',array('uses'=>'Consultant@saveHumanResource'));
		Route::post('mconsultantequipments',array('uses'=>'Consultant@saveEquipment'));
		Route::post('mconfirmregistration',array('before'=>'captchacheck','uses'=>'Consultant@saveConfirmation'));
		Route::post('fetchconsultantoncdbno',array('uses'=>'Consultant@postFetchConsultantOnCDBNo'));
		Route::get('blacklistedconsultant',array('uses'=>'Consultant@postBlackListedConsultant'));
		Route::post('blacklistedconsultant',array('uses'=>'Consultant@postBlackListedConsultant'));
	});

	Route::group(array('prefix' => 'architect'),function(){
		Route::get('editfeestructure','FeeStructure@getArchitectFeeStructure');
		Route::get('apprejected/{architectreference}/{securitycode}',array('uses'=>'Architect@checkRejectedSecurityCode'));
		Route::get('printregistration/{architectId}',array('uses'=>'Architect@printDetails'));
		Route::get('default',array('uses'=>'Architect@defaultIndex'));
		//Route::get('registration/{architect?}',array('uses'=>'Architect@registration'));
		Route::post('mregistration',array('uses'=>'Architect@save'));
		Route::get('confirmregistration',array('uses'=>'Architect@confirmRegistration'));
		Route::post('mconfirmregistration',array('before'=>'captchacheck','uses'=>'Architect@saveConfirmation'));

		Route::post("checkarchitectisregistered","Architect@postCheckIsRegistered");
	});
	Route::group(array('prefix' => 'engineer'),function(){
		Route::get('editfeestructure','FeeStructure@getEngineerFeeStructure');
		Route::get('apprejected/{architectreference}/{securitycode}',array('uses'=>'Engineer@checkRejectedSecurityCode'));
		Route::get('printregistration/{engineerId}',array('uses'=>'Engineer@printDetails'));
		Route::get('default',array('uses'=>'Engineer@defaultIndex'));
		//Route::get('registration/{engineer?}',array('uses'=>'Engineer@registration'));
		Route::post('mregistration',array('uses'=>'Engineer@save'));
		Route::get('confirmregistration',array('uses'=>'Engineer@confirmRegistration'));
		Route::post('mconfirmregistration',array('before'=>'captchacheck','uses'=>'Engineer@saveConfirmation'));
	});
	Route::group(array('prefix' => 'specializedtrade'),function(){
		Route::get('editfeestructure','FeeStructure@getSpecializedTradeFeeStructure');
		Route::get('apprejected/{architectreference}/{securitycode}',array('uses'=>'SpecializedTrade@checkRejectedSecurityCode'));
		Route::get('printregistration/{specializedtradeId}',array('uses'=>'SpecializedTrade@printDetails'));
		Route::get('default',array('uses'=>'SpecializedTrade@defaultIndex'));
		Route::get('registration/{specializedtrade?}',array('uses'=>'SpecializedTrade@registration'));
		Route::post('mregistration',array('uses'=>'SpecializedTrade@save'));
		Route::get('confirmregistration',array('uses'=>'SpecializedTrade@confirmRegistration'));
		Route::post('mconfirmregistration',array('before'=>'captchacheck','uses'=>'SpecializedTrade@saveConfirmation'));
	});

	Route::get('webserviceg2c',array('uses'=>'WebServiceG2C@getIndex'));
	Route::post('webserviceretrievedetails',array('uses'=>'WebServiceG2C@getCitizenDetails'));
	Route::post('checkhumanresourceregistration',array('uses'=>'BaseController@postCheckHumanResourceRegistration'));
	Route::group(array('before' => 'auth'),function(){
		Route::post('checkhumanresourceoccupied',array('uses'=>'BaseController@postCheckHumanResourceOccupied'));
		Route::post('checkhumanresource',array('uses'=>'BaseController@postCheckHumanResource'));

		Route::post('checkequipment',array('uses'=>'BaseController@postCheckEquipment'));
		Route::post('checkequipmentoccupied',array('uses'=>'BaseController@postCheckEquipmentOccupied'));
		Route::get('checkcdbnocontractor',array('uses'=>'Contractor@checkCDBNo'));
		Route::get('checkcdbnoconsultant',array('uses'=>'Consultant@checkCDBNo'));
		Route::get('checkarnoarchitect',array('uses'=>'Architect@checkARNo'));
		Route::get('checkcdbnoengineer',array('uses'=>'Engineer@checkCDBNo'));
		Route::get('checkspnospecializedtrade',array('uses'=>'SpecializedTrade@checkSPNo'));
		Route::post('checkoldpassword',array('uses'=>'UserManagement@checkOldPassword'));

		Route::post('actiondeleterecord',array('uses'=>'BaseController@deleteRecord'));
		Route::post('checkhumanresourcewebservice',array('uses'=>'BaseController@checkHRWebService'));
		/*-----------------------Routes for system home links-------------------*/
		Route::group(array('prefix' => 'ezhotin'),function(){
			Route::get('dashboard',array('uses'=>'EzhotinHomeController@ezhotinDashboard'));
			Route::get('adminnavoptions',array('uses'=>'EzhotinHomeController@adminNavOptions'));
			Route::get('etoolcinetnavoptions',array('uses'=>'EzhotinHomeController@etoolCinetNavOptions'));
			Route::get('individualtaskreport','EzhotinHomeController@individualTaskReport');
		});
		/*-----------------------Routem system home links-------------------*/


		Route::group(array('prefix' => 'master'),function(){
			Route::get('ownershiptype',array('uses'=>'CmnList@ownershipType'));
			Route::get('procuringagency',array('uses'=>'ProcuringAgency@index'));
			Route::get('servicesectortype',array('uses'=>'CmnList@serviceSectorType'));
			Route::get('salutation',array('uses'=>'CmnList@salutation'));
			Route::get('dzongkhag',array('uses'=>'Dzongkhag@index'));
			Route::get('country',array('uses'=>'Country@index'));
			Route::post('mcountry',array('uses'=>'Country@save'));
			Route::get('designation',array('uses'=>'CmnList@designation'));
			Route::get('qualification',array('uses'=>'CmnList@qualification'));
			Route::get('workcompletionstatus',array('uses'=>'CmnList@workCompletionStatus'));
			Route::get('trade',array('uses'=>'CmnList@trade'));
			Route::get('ministry',array('uses'=>'CmnList@ministry'));
			Route::get('division/{id?}',array('uses'=>'Division@getIndex'));
			Route::get('deletedivision/{id}',array('uses'=>'Division@getDelete'));
			Route::get('contractorprojectcategory',array('uses'=>'Contractor@projectCategory'));
			Route::get('financialinstitution',array('uses'=>'CmnList@financialInstitution'));
			Route::get('listofequipment',array('uses'=>'ListOfEquipment@index'));
			Route::get('consultantservicecategory',array('uses'=>'Consultant@serviceCategory'));
			Route::get('consultantservice',array('uses'=>'Consultant@service'));
			Route::get('fees/{id?}',array('uses'=>'FeeStructure@getIndex'));
			Route::get('feestructure','FeeStructure@getStructure');
			Route::get('trainingmodule','CmnList@trainingModule');

			Route::post('savefeestructure',array('uses'=>'FeeStructure@postSave'));
			Route::post('savedivision',array('uses'=>'Division@postSave'));
			Route::post('mdzongkhag',array('uses'=>'Dzongkhag@save'));
			Route::post('mcmnlistitem',array('uses'=>'CmnList@save'));
			Route::post('mprocuringagency',array('uses'=>'ProcuringAgency@save'));
			Route::post('mequipment',array('uses'=>'ListOfEquipment@save'));
			Route::get('specializedtradecategory',array('uses'=>'SpecializedTrade@category'));
			Route::post('mspecializedtradecategory',array('uses'=>'SpecializedTrade@saveCategory'));
			Route::post('deleteitem',array('uses'=>'CmnList@deleteItem'));
			Route::post('deletefromdb',array('uses'=>'BaseController@postDeleteFromDb'));
		});
		Route::group(array('prefix' => 'search'),function(){
			Route::get('cmnlistitemsearch/{listid}',array('uses'=>'CmnList@search'));
			Route::get('dzongkhag/{searchid}',array('uses'=>'Dzongkhag@search'));
			Route::get('country/{searchid}',array('uses'=>'Country@search'));
			Route::get('procuringagency/{searchid}',array('uses'=>'ProcuringAgency@search'));
			Route::get('equipment/{searchid}',array('uses'=>'ListOfEquipment@search'));
			Route::get('biddingformcontractor/{searchid}',array('uses'=>'BiddingForm@searchBiddingFormContrator'));
		});
		/*------------------------Common Routes for System--------------------------*/
		Route::group(array('prefix' => 'all'),function(){
			Route::post('mbiddingform',array('uses'=>'BiddingForm@save'));
			Route::post('cbbiddingform',array('uses'=>'CBuilderController@save'));
			Route::post('certifiedbiddingform',array('uses'=>'CertifiedbuilderBiddingForm@save'));
			Route::post('mworkcompletionform',array('uses'=>'WorkCompletionForm@save'));
			Route::post('cbworkcompletionform',array('uses'=>'CBWorkCompletionForm@save'));
			Route::post('deletebid',array('uses'=>'BiddingForm@delete'));
			Route::get('viewhrandeqforworkid','CrpsController@viewHRandEqForWorkid');
			Route::post('checkhrdbandwebservice','BaseController@postCheckHrDbAndWebService');
			Route::post('savefeestructure','FeeStructure@postSaveAllFees');
			Route::post("changeexpirydate","CrpsController@changeExpiryDateApplicants");
			Route::get('updatedeletedhumanresourcenotification/{id}','BaseController@updateDeleteNotification');
		});
		/*------------------------End Common Routes for System--------------------------*/

		/*------------------------Routes for CRPS Specialized Firm--------------------------*/



		Route::group(array('prefix' => 'specializedfirm'),function(){
			
			Route::get('editlist',array('uses'=>'Specializedfirm@specializedfirmList'));
Route::get('newcommentsadverserecordslist',array('uses'=>'Specializedfirm@specializedfirmList'));
			Route::get('editcommentsadverserecordslist',array('uses'=>'Specializedfirm@specializedfirmList'));
			Route::get('viewprintlist',array('uses'=>'Specializedfirm@specializedfirmList'));
			Route::get('newcomments',array('uses'=>'Specializedfirm@specializedfirmList'));
			Route::get('suspend',array('uses'=>'Specializedfirm@blacklistDeregisterList'));
			Route::get('deregister',array('uses'=>'Specializedfirm@blacklistDeregisterList'));
			Route::get('reregistration',array('uses'=>'Specializedfirm@blacklistDeregisterList'));
			Route::get('editdetails/{specializedtradeid}',array('uses'=>'Specializedfirm@editDetails'));
			Route::post('blacklistandderegister',array('uses'=>'Specializedfirm@deregisterBlackListRegistration'));
			Route::get('editgeneralinfo/{specializedtradeid}',array('uses'=>'Specializedfirm@generalInfoRegistration'));
			Route::get('viewprintdetails/{specializedtradeid}',array('uses'=>'Specializedfirm@printDetails'));
			Route::get('certificate/{specializedtradeId}',array('uses'=>'MySpecializedfirm@myCertificate'));
			Route::post('mcommentadverserecord',array('uses'=>'Specializedfirm@saveCommentAdverseRecord'));
			//Route::get('editregistrationinfo/{specializedtradeid}',array('uses'=>'Specializedfirm@registration'));
			//Route::post('mregistration',array('uses'=>'Specializedfirm@save'));
			Route::get('edithumanresource/{specializedtradeid}',array('uses'=>'Specializedfirm@humanResourceRegistrationEdit'));
			//Route::get('edithumanresource/{consultantid}',array('uses'=>'Consultant@humanResourceRegistrationEdit'));
			Route::get('editequipment/{specializedtradeid}',array('uses'=>'Specializedfirm@equipmentRegistrationEdit'));
			Route::get('editworkclassification/{specializedtradeid}',array('uses'=>'Specializedfirm@workClassificationRegistration'));
            Route::post('mspecializedfirmgeneralinfo',array('uses'=>'Specializedfirm@saveGeneralInfo'));
		//	Route::post('mcontractorgeneralinfo',array('uses'=>'Contractor@saveGeneralInfo'));
		 //   Route::post('confirmregistration',array('uses'=>'Specializedfirm@saveWorkClassification'));
		//	Route::post('mregistration',array('uses'=>'Specializedfirm@saveWorkClassification'));
	    	Route::post('mspecializedfirmhumanresource',array('uses'=>'Specializedfirm@saveHumanResource'));
		//	Route::post('mcontractorhumanresource',array('uses'=>'Contractor@saveHumanResource'));
			Route::post('mspecializedfirmequipments',array('uses'=>'Specializedfirm@saveEquipment'));
			//Route::post('mspecializedtradecategory',array('uses'=>'Specializedfirm@saveCategory'));
			Route::post('mregistration',array('uses'=>'Specializedfirm@saveWorkClassification'));
			//Route::get('confirmregistration',array('uses'=>'Specializedfirm@confirmRegistration'));
			//Route::post('mspecializedfirmworkclassification',array('uses'=>'Specializedfirm@saveWorkClassification'));
			Route::get('newcommentsadverserecords/{specializedtradeid}',array('uses'=>'Specializedfirm@newCommentAdverseRecord'));
			Route::post('mcommentadverserecord',array('uses'=>'Specializedfirm@saveCommentAdverseRecord'));
			Route::get('editcommentsadverserecords/{specializedtradeid}',array('uses'=>'Specializedfirm@editCommentAdverseRecord'));
			Route::post('meditcommentadverserecords',array('uses'=>'Specializedfirm@updateCommentAdverseRecord'));
			Route::post('mspecializedtradeworkclassification',array('uses'=>'Specializedfirm@saveWorkClassification'));
			Route::get('biddingform',array('uses'=>'SpecializedfirmBiddingForm@index'));
            Route::get('editbiddingformlist',array('uses'=>'Specializedfirm@listOfWorks'));
            Route::get('worklist',array('uses'=>'Specializedfirm@listOfWorks'));
            Route::get('editcompletedworklist',array('uses'=>'Specializedfirm@listOfWorks'));
			Route::get('editregistrationinfo/{specializedtradeid}',array('uses'=>'Specializedfirm@workClassificationRegistration'));
			Route::get('workcompletionform/{bidId}',array('uses'=>'Specializedfirm@workCompletionForm'));

		});

		
		/*------------------------Routes for CRPS Certified Builder--------------------------*/

			Route::group(array('prefix' => 'certifiedbuilder'),function(){
			
				Route::get('editlist',array('uses'=>'Certifiedbuilder@certifiedbuilderList'));
				Route::get('newcommentsadverserecordslist',array('uses'=>'Certifiedbuilder@certifiedbuilderList'));
				Route::get('editcommentsadverserecordslist',array('uses'=>'Certifiedbuilder@certifiedbuilderList'));
				Route::get('viewprintlist',array('uses'=>'Certifiedbuilder@certifiedbuilderList'));
				Route::get('newcomments',array('uses'=>'Certifiedbuilder@certifiedbuilderList'));
				Route::get('suspend',array('uses'=>'Certifiedbuilder@blacklistDeregisterList'));
				Route::get('revoke',array('uses'=>'Certifiedbuilder@blacklistDeregisterList'));
				Route::get('deregister',array('uses'=>'Certifiedbuilder@blacklistDeregisterList'));
				Route::get('reregistration',array('uses'=>'Certifiedbuilder@blacklistDeregisterList'));
				Route::get('editdetails/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@editDetails'));
				Route::post('blacklistandderegister',array('uses'=>'Certifiedbuilder@deregisterBlackListRegistration'));
				Route::get('editgeneralinfo/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@generalInfoRegistration'));
				Route::get('viewprintdetails/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@printDetails'));
				Route::get('certificate/{certifiedbuilderid}',array('uses'=>'MyCertifiedbuilder@myCertificate'));
				Route::post('mcommentadverserecord',array('uses'=>'Certifiedbuilder@saveCommentAdverseRecord'));
				//Route::get('editregistrationinfo/{certifiedbuilderid}',array('uses'=>'Specializedfirm@registration'));
				//Route::post('mregistration',array('uses'=>'Specializedfirm@save'));
				Route::get('edithumanresource/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@humanResourceRegistrationEdit'));
				//Route::get('edithumanresource/{consultantid}',array('uses'=>'Consultant@humanResourceRegistrationEdit'));
				Route::get('editequipment/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@equipmentRegistrationEdit'));
				// Route::get('editworkclassification/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@workClassificationRegistration'));
				Route::post('mcertifiedbuildergeneralinfo',array('uses'=>'Certifiedbuilder@saveGeneralInfo'));
			//	Route::post('mcontractorgeneralinfo',array('uses'=>'Contractor@saveGeneralInfo'));
			 //   Route::post('confirmregistration',array('uses'=>'Specializedfirm@saveWorkClassification'));
			//	Route::post('mregistration',array('uses'=>'Specializedfirm@saveWorkClassification'));
				Route::post('mcertifiedbuilderhumanresource',array('uses'=>'Certifiedbuilder@saveHumanResource'));
			//	Route::post('mcontractorhumanresource',array('uses'=>'Contractor@saveHumanResource'));
				Route::post('mspecializedfirmequipments',array('uses'=>'Certifiedbuilder@saveEquipment'));
				//Route::post('mcertifiedbuildercategory',array('uses'=>'Specializedfirm@saveCategory'));
				Route::post('mregistration',array('uses'=>'Certifiedbuilder@saveWorkClassification'));
				//Route::get('confirmregistration',array('uses'=>'Specializedfirm@confirmRegistration'));
				//Route::post('mspecializedfirmworkclassification',array('uses'=>'Specializedfirm@saveWorkClassification'));
				Route::get('newcommentsadverserecords/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@newCommentAdverseRecord'));
				Route::post('mcommentadverserecord',array('uses'=>'Certifiedbuilder@saveCommentAdverseRecord'));
				Route::get('editcommentsadverserecords/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@editCommentAdverseRecord'));
				Route::post('meditcommentadverserecords',array('uses'=>'Certifiedbuilder@updateCommentAdverseRecord'));
				Route::post('mcertifiedbuilderworkclassification',array('uses'=>'Certifiedbuilder@saveWorkClassification'));
				// Route::get('biddingform',array('uses'=>'BiddingForm@index'));
				Route::get('biddingform',array('uses'=>'CertifiedbuilderBiddingForm@index'));
				Route::get('editbiddingformlist',array('uses'=>'Certifiedbuilder@listOfWorks'));
				Route::get('worklist',array('uses'=>'Certifiedbuilder@listOfWorks'));
				Route::get('editcompletedworklist',array('uses'=>'Certifiedbuilder@listOfWorks'));
				Route::get('editregistrationinfo/{certifiedbuilderid}',array('uses'=>'Certifiedbuilder@workClassificationRegistration'));
				Route::get('workcompletionform/{bidId}',array('uses'=>'Certifiedbuilder@workCompletionForm'));

				Route::get('replacereleasehrequipment',array('uses'=>'CertifiedbuilderReplaceRelease@getIndex'));
			    Route::post('replacereleasehrequipment',array('uses'=>'CertifiedbuilderReplaceRelease@postFetch'));
				
				Route::get('replaceCitnetEquipment/{Id}',array('uses'=>'CertifiedbuilderReplaceRelease@replaceCitnetEquipment'));
				Route::post('replaceCinetEquipment',array('uses'=>'CertifiedbuilderReplaceRelease@replaceCinetEquipment'));
				Route::get('releaseCinetequipment/{id}',array('uses'=>'CertifiedbuilderReplaceRelease@releaseCinetequipment'));
				Route::post('releaseCinetEquipment',array('uses'=>'CertifiedbuilderReplaceRelease@PostreleaseCinetEquipment'));

				Route::post('releaseequipment',array('uses'=>'CertifiedbuilderReplaceRelease@postReleaseEquipment'));

				Route::get('replaceCinetHr/{id}',array('uses'=>'CertifiedbuilderReplaceRelease@getReplaceCinetHR'));
				Route::get('replacehr/{id}',array('uses'=>'CertifiedbuilderReplaceRelease@getReplaceHR'));
				
				Route::post('replacehr',array('uses'=>'CertifiedbuilderReplaceRelease@postReplaceHR'));

				
				Route::get('releaseCinethr/{id}',array('uses'=>'CertifiedbuilderReplaceRelease@releaseCinethr'));
				Route::get('releasehr/{id}',array('uses'=>'CertifiedbuilderReplaceRelease@getReleaseHR'));
				Route::post('releasehr',array('uses'=>'CertifiedbuilderReplaceRelease@postReleaseHR'));
				Route::post('releaseCinetHR',array('uses'=>'CertifiedbuilderReplaceRelease@postreleaseCinetHR'));

				Route::post('replaceCinetHR',array('uses'=>'CertifiedbuilderReplaceRelease@replaceCinetHR'));
				
					
		
			});


		
		

		/*------------------------Routes for CRPS Contractors--------------------------*/
		Route::group(array('prefix' => 'contractor'),function(){
			Route::get('editfeestructure','FeeStructure@getContractorFeeStructure');

			Route::post('saveservices','MyContractor@saveServices');
			Route::post('mworkcategory',array('uses'=>'Contractor@saveCategory'));
			Route::get('classification',array('uses'=>'Contractor@classification'));
			Route::post('mclassification',array('uses'=>'Contractor@saveClassification'));
			Route::get('editgeneralinfo/{contractorid}',array('uses'=>'Contractor@generalInfoRegistration'));
			Route::get('editworkclassification/{contractorid}',array('uses'=>'Contractor@workClassificationRegistration'));
			Route::get('edithumanresource/{contractorid}',array('uses'=>'Contractor@humanResourceRegistrationEdit'));
			Route::get('editequipment/{contractorid}',array('uses'=>'Contractor@equipmentRegistrationEdit'));
			Route::get('verifyregistration',array('uses'=>'Contractor@verifyList'));
			Route::get('verifyregistrationprocess/{contractorid}',array('before' => 'newstatuscheck','uses'=>'Contractor@contractorDetails'));
			Route::post('mverifyregistration',array('before'=>'statuscheckbeforeverifying','uses'=>'Contractor@verifyRegistration'));
			Route::post('mapproveregistration',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'Contractor@approveRegistration'));
			Route::post('mrejectregistration',array('uses'=>'Contractor@rejectRegistration'));
			Route::get('approveregistration',array('uses'=>'Contractor@approveList'));
			Route::get('approveregistrationprocess/{contractorid}',array('before' => 'verifiedstatuscheck','uses'=>'Contractor@contractorDetails'));
			Route::get('approvefeepayment',array('uses'=>'Contractor@approveList'));
			Route::get('approvepaymentregistrationprocess/{contractorid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'Contractor@contractorDetails'));
			Route::post('mapprovepaymentforregistration',array('before'=>'statuscheckbeforeapproving','uses'=>'Contractor@approvePayment'));
			Route::get('viewapprovedapplications',array('uses'=>'Contractor@viewRegistrationList'));
			Route::get('viewregistrationprocess/{contractorid}',array('uses'=>'Contractor@contractorDetails'));
			Route::post('msavefinalremarks',array('uses'=>'Contractor@saveFinalRemarks'));
			Route::get('editlist',array('uses'=>'Contractor@contractorList'));
			Route::get('editdetails/{contractorid}',array('uses'=>'Contractor@editDetails'));
			Route::get('viewprintlist',array('uses'=>'Contractor@contractorList'));
			Route::get('newcommentsadverserecordslist',array('uses'=>'Contractor@contractorList'));
			Route::get('newcommentsadverserecords/{contractorid}',array('uses'=>'Contractor@newCommentAdverseRecord'));
			Route::post('mcommentadverserecord',array('uses'=>'Contractor@saveCommentAdverseRecord'));
			

			Route::get('editcommentsadverserecordslist',array('uses'=>'Contractor@contractorList'));
			Route::get('editcommentsadverserecords/{contractorid}',array('uses'=>'Contractor@editCommentAdverseRecord'));
			Route::post('meditcommentadverserecords',array('uses'=>'Contractor@updateCommentAdverseRecord'));
			Route::get('deregister',array('uses'=>'Contractor@blacklistDeregisterList'));
			Route::get('blacklist',array('uses'=>'Contractor@blacklistDeregisterList'));
			Route::get('revoke',array('uses'=>'Contractor@blacklistDeregisterList'));
			Route::get('reregistration',array('uses'=>'Contractor@blacklistDeregisterList'));
			Route::post('blacklistandderegister',array('uses'=>'Contractor@deregisterBlackListRegistration'));

			Route::post('reinstate',array('uses'=>'Contractor@reinstate'));

			Route::get('biddingform',array('uses'=>'BiddingForm@index'));
			Route::get('worklist',array('uses'=>'Contractor@listOfWorks'));
			Route::get('workcompletionform/{bidId}',array('uses'=>'Contractor@workCompletionForm'));
			Route::get('editcompletedworklist',array('uses'=>'Contractor@listOfWorks'));
			Route::get('editbiddingformlist',array('uses'=>'Contractor@listOfWorks'));
			Route::get('lockapplication/{contractorid}',array('uses'=>'Contractor@setRecordLock'));
			Route::get('viewprintdetails/{contractorid}',array('uses'=>'Contractor@printDetails'));
			Route::post('deletecontractorcommentadverserecord',array('uses'=>'Contractor@deleteCommentAdverseRecord'));
			Route::get('changeexpirydate/{contractorid}','Contractor@changeExpiryDate');
			Route::post('changeexpirydate','Contractor@postChangeExpiryDate');
			Route::get('surrender',array('uses'=>'Contractor@blacklistDeregisterList'));
			Route::post('surrender','Contractor@postSaveSurrender');
			/*---------------Start routes for contractor who uses the system---------------------------------*/
			Route::get('mydashboard',array('uses'=>'MyContractor@dashBoard'));
			Route::get('mytrackrecords',array('uses'=>'MyContractor@trackRecords'));
			Route::get('printtrackrecords',array('uses'=>'MyContractor@printTrackRecords'));
			Route::get('profile',array('uses'=>'MyContractor@myProfile'));
			Route::get('certificate/{contractorId}',array('uses'=>'MyContractor@myCertificate'));
			Route::get('applyotherservices',array('uses'=>'MyContractor@applyOtherService'));
			Route::get('applycancellation',array('uses'=>'MyContractor@applyCancellation'));
			Route::get('applyrenewal',array('uses'=>'MyContractor@applyRenewal'));
			Route::get('applyservicegeneralinfo/{contractorId}',array('uses'=>'MyContractor@applyServiceGeneralInformation'));
			Route::get('applyserviceworkclassification/{contractorId}',array('uses'=>'MyContractor@applyServiceWorkClassification'));
			Route::get('applyservicehumanresource/{contractorId}',array('uses'=>'MyContractor@applyServiceHumanResource'));
			Route::get('applyservicehumanresourceedit/{contractorId}',array('uses'=>'MyContractor@applyServicehumanResourceRegistrationEdit'));
			Route::get('applyserviceequipment/{contractorId}',array('uses'=>'MyContractor@applyServiceEquipmentRegistration'));
			Route::get('applyserviceequipmentedit/{contractorId}',array('uses'=>'MyContractor@applyServiceEquipmentRegistration'));
			Route::get('applyserviceconfirmation/{contractorId}',array('uses'=>'MyContractor@applyServiceConfirmation'));
			Route::post('mserviceconfirmation',array('uses'=>'MyContractor@saveConfirmation'));
			Route::post('mcancelcertificate',array('uses'=>'MyContractor@saveCancellation'));
			/*---------------End of process by contractors who uses the system---------------------------------*/
			/*---------------Process for verifying and approcing services applied by contractors---------------*/
			Route::get('verifyserviceapplicationlist',array('uses'=>'ContractorServiceApplication@verifyApproveList'));
			Route::get('verifyserviceapplicationprocess/{contractorid}',array('before' => 'newstatuscheck','uses'=>'ContractorServiceApplication@serviceApplicationDetails'));
			Route::post('mverifyserviceapplication',array('before'=>'statuscheckbeforeverifying','uses'=>'ContractorServiceApplication@verifyServiceApplicationRegistration'));
			Route::get('approveserviceapplicationlist',array('uses'=>'ContractorServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationprocess/{contractorid}',array('before' => 'verifiedstatuscheck','uses'=>'ContractorServiceApplication@serviceApplicationDetails'));
			Route::post('mapproveserviceapplication',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'ContractorServiceApplication@approveServiceApplicationRegistration'));

			Route::get('approveserviceapplicationfeepaymentlist',array('uses'=>'ContractorServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationpaymentprocess/{contractorid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'ContractorServiceApplication@serviceApplicationDetails'));
			Route::post('mapprovepaymentserviceapplication',array('before'=>'statuscheckbeforeapproving','uses'=>'ContractorServiceApplication@approvePaymentServiceApplicationRegistration'));

			Route::get('viewserviceapplication',array('as'=>'contractor.viewserviceapplication','uses'=>'ContractorServiceApplication@viewList'));
			Route::get('viewserviceapplicationdetails/{contractorid}',array('uses'=>'ContractorServiceApplication@serviceApplicationDetails'));
			Route::get('approvecertificatecancellationrequestlist',array('uses'=>'ContractorServiceApplication@approveCertificateCancellationList'));
			Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'ContractorServiceApplication@lockApplicationCancellationRequest'));
			Route::get('approveserviceapplicationcancelcertificateprocess/{contractorId}/{cancelRequestId}',array('uses'=>'ContractorServiceApplication@approveCancelCertificateRequest'));
			Route::post('mapprovecancelcertificate',array('uses'=>'ContractorServiceApplication@approveCancellation'));

			Route::post('meditbasicinfo','MyContractor@editBasicInfo');
			//Ajax Route
			Route::get('fetchcontractorsjson',array('uses'=>'Contractor@fetchContractorsJSON'));
			Route::get('contractorregistrationhistorylist',array('as'=>'contractor.contractorregistrationhistorylist','uses'=>'ReportContractorRegistrationHistory@getIndex'));
			Route::get('contractorregistrationhistory/{id?}',array('as'=>'contractor.contractorregistrationhistory', 'uses'=>'ReportContractorRegistrationHistory@getDetails'));
			Route::get('contractorhistory',array('as'=>'contractor.contractorhistory','uses'=>'ReportContractorHistory@getIndex'));

			//ADDED 24th Feb 2017//
			Route::get('editservices',array("as"=>"contractor.editservices",'uses'=>'ContractorServiceApplication@editServices'));
			Route::get('editservicesdetail/{id}','ContractorServiceApplication@editServicesDetail');
			Route::post('saveeditedservice','ContractorServiceApplication@saveServicesDetail');
			Route::get('deleteservice/{id}','ContractorServiceApplication@deleteApplication');


			Route::get('monitoringtool','Contractor@getMonitoringReportIndex');
			Route::get('monitoringreportoffice','Contractor@getMonitoringReportOffice');
			Route::get('monitoringreportsites','Contractor@getMonitoringReportSites');


			Route::get('training',array('as'=>'contractor.training','uses'=>'ContractorTraining@getIndex'));
			Route::get('addtraining','ContractorTraining@addNew');
			Route::get('trainingdetails/{id}','ContractorTraining@getDetails');
			Route::post('savetraining','ContractorTraining@postSave');
			Route::get('deletetraining','ContractorTraining@getDelete');
			Route::post('deletetraining','ContractorTraining@postDelete');
			Route::get('deletetrainingparticipant/{trainingId}/{id}','ContractorTraining@postDeleteParticipant');
			Route::get('edittrainingparticipant/{trainingType}/{id}','ContractorTraining@getEditParticipant');
			Route::post('saveeditedparticipant','ContractorTraining@saveEditedParticipant');

			Route::post('fetchcontractorsdetails','Contractor@postFetchDetails');

			Route::get('auditmemo',array('as'=>'contractor.auditmemo','uses'=>'ContractorAuditMemo@getIndex'));
			Route::get('addauditrecord','ContractorAuditMemo@addNew');
			Route::post('saveaudit','ContractorAuditMemo@postSave');
			Route::post("fetchauditdetails","ContractorAuditMemo@postFetchDetails");
			Route::get('editaudit/{id}','ContractorAuditMemo@getEdit');
			Route::get('deleteaudit/{id}','ContractorAuditMemo@getDelete');
			Route::post('saveeditedaudit','ContractorAuditMemo@saveEditedAudit');
			//END 24th Feb 2017//
		});

		Route::group(array('prefix' => 'consultant'),function(){
			Route::get('editgeneralinfo/{consultantid}',array('uses'=>'Consultant@generalInfoRegistration'));
			Route::get('editworkclassification/{consultantid}',array('uses'=>'Consultant@workClassificationRegistration'));
			Route::get('edithumanresource/{consultantid}',array('uses'=>'Consultant@humanResourceRegistrationEdit'));
			Route::get('editequipment/{consultantid}',array('uses'=>'Consultant@equipmentRegistrationEdit'));
			Route::get('editlist',array('uses'=>'Consultant@consultantList'));
			Route::get('editdetails/{consultantid}',array('uses'=>'Consultant@editDetails'));
			Route::get('verifyregistration',array('uses'=>'Consultant@verifyList'));
			Route::get('verifyregistrationprocess/{consultantid}',array('before' => 'newstatuscheck','uses'=>'Consultant@consultantDetails'));
			Route::post('mverifyregistration',array('before'=>'statuscheckbeforeverifying','uses'=>'Consultant@verifyRegistration'));
			Route::get('approveregistration',array('uses'=>'Consultant@approveList'));
			Route::get('approveregistrationprocess/{consultantid}',array('before' => 'verifiedstatuscheck','uses'=>'Consultant@consultantDetails'));
			Route::post('mapproveregistration',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'Consultant@approveRegistration'));
			Route::post('mrejectregistration',array('uses'=>'Consultant@rejectRegistration'));
			Route::get('viewprintlist',array('uses'=>'Consultant@consultantList'));
			Route::get('viewprintdetails/{consultantid}',array('uses'=>'Consultant@printDetails'));
			Route::get('newcommentsadverserecordslist',array('uses'=>'Consultant@consultantList'));
			Route::get('newcommentsadverserecords/{consultantid}',array('uses'=>'Consultant@newCommentAdverseRecord'));
			Route::post('mcommentadverserecord',array('uses'=>'Consultant@saveCommentAdverseRecord'));
			Route::get('editcommentsadverserecordslist',array('uses'=>'Consultant@consultantList'));
			Route::get('editcommentsadverserecords/{consultantid}',array('uses'=>'Consultant@editCommentAdverseRecord'));
			Route::post('deleteconsultantcommentadverserecord',array('uses'=>'Consultant@deleteCommentAdverseRecord'));
			Route::post('meditcommentadverserecords',array('uses'=>'Consultant@updateCommentAdverseRecord'));
			Route::get('deregister',array('uses'=>'Consultant@blacklistDeregisterList'));
			Route::get('blacklist',array('uses'=>'Consultant@blacklistDeregisterList'));
			Route::get('reregistration',array('uses'=>'Consultant@blacklistDeregisterList'));
			Route::post('blacklistandderegister',array('uses'=>'Consultant@deregisterBlackListRegistration'));
			Route::get('biddingform',array('uses'=>'BiddingForm@index'));
			Route::get('worklist',array('uses'=>'Consultant@listOfWorks'));
			Route::get('workcompletionform/{bidId}',array('uses'=>'Consultant@workCompletionForm'));
			Route::get('editcompletedworklist',array('uses'=>'Consultant@listOfWorks'));
			Route::get('editbiddingformlist',array('uses'=>'Consultant@listOfWorks'));
			Route::post('mservicecategory',array('uses'=>'Consultant@saveServiceCategory'));
			Route::post('mservice',array('uses'=>'Consultant@saveService'));
			Route::get('approvepaymentregistrationprocess/{consultantid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'Consultant@consultantDetails'));
			Route::post('mapprovepaymentforregistration',array('before'=>'statuscheckbeforeapproving','uses'=>'Consultant@approvePayment'));
			Route::get('lockapplication/{consultantid}',array('uses'=>'Consultant@setRecordLock'));
			Route::get('approvefeepayment',array('uses'=>'Consultant@approveList'));
			Route::get('viewapprovedapplications',array('uses'=>'Consultant@viewRegistrationList'));
			Route::get('viewregistrationprocess/{consultantid}',array('uses'=>'Consultant@consultantDetails'));
			Route::get('viewserviceapplication',array('as'=>'consultant.viewserviceapplication','uses'=>'ConsultantServiceApplication@viewList'));
			Route::get('viewserviceapplicationdetails/{consultantid}',array('uses'=>'ConsultantServiceApplication@serviceApplicationDetails'));
			Route::post('msavefinalremarks',array('uses'=>'Consultant@saveFinalRemarks'));
			/*---------------Start routes for consultant who uses the system---------------------------------*/
			Route::get('mydashboard',array('uses'=>'MyConsultant@dashBoard'));
			Route::get('mytrackrecords',array('uses'=>'MyConsultant@trackRecords'));
			Route::get('printtrackrecords',array('uses'=>'MyConsultant@printTrackRecords'));
			Route::get('profile',array('uses'=>'MyConsultant@myProfile'));
			Route::get('certificate/{consultantId}',array('uses'=>'MyConsultant@myCertificate'));
			Route::get('applyotherservices',array('uses'=>'MyConsultant@applyOtherService'));
			Route::get('applycancellation',array('uses'=>'MyConsultant@applyCancellation'));
			Route::get('applyrenewal',array('uses'=>'MyConsultant@applyRenewal'));
			Route::get('applyservicegeneralinfo/{consultantId}',array('uses'=>'MyConsultant@applyServiceGeneralInformation'));
			Route::get('applyserviceworkclassification/{consultantId}',array('uses'=>'MyConsultant@applyServiceWorkClassification'));
			Route::get('applyservicehumanresource/{consultantId}',array('uses'=>'MyConsultant@applyServiceHumanResource'));
			Route::get('applyserviceequipment/{consultantId}',array('uses'=>'MyConsultant@applyServiceEquipmentRegistration'));
			Route::get('applyserviceequipmentedit/{consultantId}',array('uses'=>'MyConsultant@applyServiceEquipmentRegistrationEdit'));
			Route::get('applyserviceconfirmation/{consultantId}',array('uses'=>'MyConsultant@applyServiceConfirmation'));
			Route::post('mserviceconfirmation',array('uses'=>'MyConsultant@saveConfirmation'));
			Route::post('mcancelcertificate',array('uses'=>'MyConsultant@saveCancellation'));
			/*---------------Process for verifying and approving services applied by contractors---------------*/
			Route::get('verifyserviceapplicationlist',array('uses'=>'ConsultantServiceApplication@verifyApproveList'));
			Route::get('verifyserviceapplicationprocess/{consultantid}',array('before' => 'newstatuscheck','uses'=>'ConsultantServiceApplication@serviceApplicationDetails'));
			Route::post('mverifyserviceapplication',array('before'=>'statuscheckbeforeverifying','uses'=>'ConsultantServiceApplication@verifyServiceApplicationRegistration'));
			Route::get('approveserviceapplicationlist',array('uses'=>'ConsultantServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationprocess/{consultantid}',array('before' => 'verifiedstatuscheck','uses'=>'ConsultantServiceApplication@serviceApplicationDetails'));
			Route::post('mapproveserviceapplication',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'ConsultantServiceApplication@approveServiceApplicationRegistration'));

			Route::get('approveserviceapplicationfeepaymentlist',array('uses'=>'ConsultantServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationpaymentprocess/{consultantid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'ConsultantServiceApplication@serviceApplicationDetails'));
			Route::post('mapprovepaymentserviceapplication',array('before'=>'statuscheckbeforeapproving','uses'=>'ConsultantServiceApplication@approvePaymentServiceApplicationRegistration'));
			Route::get('approvecertificatecancellationrequestlist',array('uses'=>'ConsultantServiceApplication@approveCertificateCancellationList'));
			Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'ConsultantServiceApplication@lockApplicationCancellationRequest'));
			Route::get('approveserviceapplicationcancelcertificateprocess/{consultantId}/{cancelRequestId}',array('uses'=>'ConsultantServiceApplication@approveCancelCertificateRequest'));
			Route::post('mapprovecancelcertificate',array('uses'=>'ConsultantServiceApplication@approveCancellation'));
			Route::post('meditbasicinfo','MyConsultant@editBasicInfo');
			//AJAX routes
			Route::get('fetchconsultantsjson',array('uses'=>'Consultant@fetchConsultantsJSON'));
		});

		Route::group(array('prefix' => 'surveyor'),function(){
			
			Route::get('editlist',array('uses'=>'Survey@surveyList'));
			Route::get('viewprintlist',array('uses'=>'Survey@surveyList'));
			Route::get('newcomments',array('uses'=>'Survey@surveyList'));
			Route::get('editcomments',array('uses'=>'Survey@surveyList'));
			Route::get('suspend',array('uses'=>'Survey@blacklistDeregisterList'));
			Route::get('deregister',array('uses'=>'Survey@blacklistDeregisterList'));
			Route::get('reregistration',array('uses'=>'Survey@blacklistDeregisterList'));
			Route::get('editdetails/{surveyid}',array('uses'=>'Survey@editDetails'));
			Route::post('blacklistandderegister',array('uses'=>'Survey@deregisterBlackListRegistration'));
			Route::get('newcommentsadverserecords/{surveyid}',array('uses'=>'Survey@newCommentAdverseRecord'));
			Route::get('editcommentsadverserecords/{surveyid}',array('uses'=>'Survey@editCommentAdverseRecord'));
			Route::get('viewprintdetails/{surveyid}',array('uses'=>'Survey@printDetails'));
			Route::get('certificate/{surveyId}',array('uses'=>'MySurvey@myCertificate'));
			Route::post('mcommentadverserecord',array('uses'=>'Survey@saveCommentAdverseRecord'));
			Route::get('editregistrationinfo/{surveyid}',array('uses'=>'Survey@registration'));
			Route::post('mregistration',array('uses'=>'Survey@save'));
         	Route::post('meditcommentadverserecords',array('uses'=>'Survey@updateCommentAdverseRecord'));
		//	Route::get('editcommentsadverserecordslist',array('uses'=>'Survey@surveyList'));
			Route::post('deletesurveycommentadverserecord',array('uses'=>'Survey@deleteCommentAdverseRecord'));
			//Route::get('editcommentsadverserecords/{architectid}',array('uses'=>'Architect@editCommentAdverseRecord'));
		});
		


		Route::group(array('prefix' => 'architect'),function(){
			Route::get('editregistrationinfo/{architectid}',array('uses'=>'Architect@registration'));
			Route::get('verifyregistration',array('uses'=>'Architect@verifyList'));
			Route::get('verifyregistrationprocess/{architectid}',array('before' => 'newstatuscheck','uses'=>'Architect@architectDetails'));
			Route::post('mverifyregistration',array('before'=>'statuscheckbeforeverifying','uses'=>'Architect@verifyRegistration'));
			Route::get('approveregistration',array('uses'=>'Architect@approveList'));
			Route::get('approveregistrationprocess/{architectid}',array('before' => 'verifiedstatuscheck','uses'=>'Architect@architectDetails'));
			Route::post('mapproveregistration',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'Architect@approveRegistration'));
			Route::post('mrejectregistration',array('uses'=>'Architect@rejectRegistration'));
			Route::get('editlist',array('uses'=>'Architect@architectList'));
			Route::get('editdetails/{architectid}',array('uses'=>'Architect@editDetails'));
			Route::get('viewprintlist',array('uses'=>'Architect@architectList'));
			Route::get('viewprintdetails/{architectid}',array('uses'=>'Architect@printDetails'));
			Route::get('newcommentsadverserecordslist',array('uses'=>'Architect@architectList'));
			Route::get('newcommentsadverserecords/{architectid}',array('uses'=>'Architect@newCommentAdverseRecord'));
			Route::post('mcommentadverserecord',array('uses'=>'Architect@saveCommentAdverseRecord'));
			Route::get('editcommentsadverserecordslist',array('uses'=>'Architect@architectList'));
			Route::get('editcommentsadverserecords/{architectid}',array('uses'=>'Architect@editCommentAdverseRecord'));
			Route::post('deletearchitectcommentadverserecord',array('uses'=>'Architect@deleteCommentAdverseRecord'));
			Route::post('meditcommentadverserecords',array('uses'=>'Architect@updateCommentAdverseRecord'));
			Route::get('blacklist',array('uses'=>'Architect@blacklistDeregisterList'));
			Route::get('deregister',array('uses'=>'Architect@blacklistDeregisterList'));
			Route::get('reregistration',array('uses'=>'Architect@blacklistDeregisterList'));
			Route::post('blacklistandderegister',array('uses'=>'Architect@deregisterBlackListRegistration'));
			Route::get('lockapplication/{architectid}',array('uses'=>'Architect@setRecordLock'));
			Route::get('approvefeepayment',array('uses'=>'Architect@approveList'));

			Route::get('approvepaymentregistrationprocess/{architectid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'Architect@architectDetails'));
			Route::post('mapprovepaymentforregistration',array('before'=>'statuscheckbeforeapproving','uses'=>'Architect@approvePayment'));

			Route::get('mydashboard',array('uses'=>'MyArchitect@dashboard'));
			Route::get('profile',array('uses'=>'MyArchitect@myProfile'));
			Route::get('certificate/{architectId}',array('uses'=>'MyArchitect@myCertificate'));
			Route::get('applycancellation',array('uses'=>'MyArchitect@applyCancellation'));
			Route::get('applyrenewal',array('uses'=>'MyArchitect@applyRenewal'));
			Route::get('applyrenewalregistrationdetails/{architectId}',array('uses'=>'MyArchitect@applyRenewalDetails'));
			Route::get('applyrenewalconfirmation/{architectId}',array('uses'=>'MyArchitect@applyRenewalConfirmation'));
			Route::post('mcancelcertificate',array('uses'=>'MyArchitect@saveCancellation'));
			Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'ArchitectServiceApplication@lockApplicationCancellationRequest'));
			/*---------------Process for verifying and approcing services applied by architects---------------*/
			Route::get('verifyserviceapplicationlist',array('uses'=>'ArchitectServiceApplication@verifyApproveList'));
			Route::get('verifyserviceapplicationprocess/{architectid}',array('before' => 'newstatuscheck','uses'=>'ArchitectServiceApplication@serviceApplicationDetails'));
			Route::post('mverifyserviceapplication',array('before'=>'statuscheckbeforeverifying','uses'=>'ArchitectServiceApplication@verifyServiceApplicationRegistration'));
			Route::get('approveserviceapplicationlist',array('uses'=>'ArchitectServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationprocess/{architectid}',array('before' => 'verifiedstatuscheck','uses'=>'ArchitectServiceApplication@serviceApplicationDetails'));
			Route::post('mapproveserviceapplication',array('before'=>'statuscheckbeforeapprovingforpayment','before'=>'statuscheckbeforeapprovingforpayment','uses'=>'ArchitectServiceApplication@approveServiceApplicationRegistration'));

			Route::get('approveserviceapplicationfeepaymentlist',array('uses'=>'ArchitectServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationpaymentprocess/{architectid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'ArchitectServiceApplication@serviceApplicationDetails'));
			Route::post('mapprovepaymentserviceapplication',array('before'=>'statuscheckbeforeapproving','uses'=>'ArchitectServiceApplication@approvePaymentServiceApplicationRegistration'));
			Route::get('approvecertificatecancellationrequestlist',array('uses'=>'ArchitectServiceApplication@approveCertificateCancellationList'));
			Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'ArchitectServiceApplication@lockApplicationCancellationRequest'));
			Route::get('approveserviceapplicationcancelcertificateprocess/{architectId}/{cancelRequestId}',array('uses'=>'ArchitectServiceApplication@approveCancelCertificateRequest'));
			Route::post('mapprovecancelcertificate',array('uses'=>'ArchitectServiceApplication@approveCancellation'));
			Route::get('rejectcertificatecancellationrequest/{id}','ArchitectServiceApplication@rejectCancellation');

			Route::get('viewapprovedapplications',array('uses'=>'Architect@viewRegistrationList'));
			Route::get('viewregistrationprocess/{architectid}',array('uses'=>'Architect@architectDetails'));
			Route::get('viewserviceapplication','ArchitectServiceApplication@viewList');
			Route::get('viewserviceapplicationdetails/{architectid}',array('uses'=>'ArchitectServiceApplication@serviceApplicationDetails'));
			Route::post('msavefinalremarks',array('uses'=>'Architect@saveFinalRemarks'));

			//AJAX routes
			Route::get('fetcharchitectsjson',array('uses'=>'Architect@fetchArchitectsJSON'));
		});
		Route::group(array('prefix' => 'engineer'),function(){
			Route::get('editregistrationinfo/{engineerid}',array('uses'=>'Engineer@registration'));
			Route::get('verifyregistration',array('uses'=>'Engineer@verifyList'));
			Route::get('verifyregistrationprocess/{engineerid}',array('before' => 'newstatuscheck','uses'=>'Engineer@engineerDetails'));
			Route::post('mverifyregistration',array('before'=>'statuscheckbeforeverifying','uses'=>'Engineer@verifyRegistration'));
			Route::get('approveregistration',array('uses'=>'Engineer@approveList'));
			Route::get('approveregistrationprocess/{engineerid}',array('before' => 'verifiedstatuscheck','uses'=>'Engineer@engineerDetails'));
			Route::post('mapproveregistration',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'Engineer@approveRegistration'));
			Route::post('mrejectregistration',array('uses'=>'Engineer@rejectRegistration'));
			Route::get('editlist',array('uses'=>'Engineer@engineerList'));
			Route::get('editdetails/{engineerid}',array('uses'=>'Engineer@editDetails'));
			Route::get('viewprintlist',array('uses'=>'Engineer@engineerList'));
			Route::get('viewprintdetails/{engineerid}',array('uses'=>'Engineer@printDetails'));
			Route::get('newcommentsadverserecordslist',array('uses'=>'Engineer@engineerList'));
			Route::get('newcommentsadverserecords/{engineerid}',array('uses'=>'Engineer@newCommentAdverseRecord'));
			Route::post('mcommentadverserecord',array('uses'=>'Engineer@saveCommentAdverseRecord'));
			Route::get('editcommentsadverserecordslist',array('uses'=>'Engineer@engineerList'));
			Route::get('editcommentsadverserecords/{engineerid}',array('uses'=>'Engineer@editCommentAdverseRecord'));
			Route::post('deleteengineercommentadverserecord',array('uses'=>'Engineer@deleteCommentAdverseRecord'));
			Route::post('meditcommentadverserecords',array('uses'=>'Engineer@updateCommentAdverseRecord'));
			Route::get('deregister',array('uses'=>'Engineer@blacklistDeregisterList'));
			Route::get('blacklist',array('uses'=>'Engineer@blacklistDeregisterList'));
			Route::get('reregistration',array('uses'=>'Engineer@blacklistDeregisterList'));
			Route::post('blacklistandderegister',array('uses'=>'Engineer@deregisterBlackListRegistration'));
			Route::get('lockapplication/{engineerid}',array('uses'=>'Engineer@setRecordLock'));
			Route::get('approvefeepayment',array('uses'=>'Engineer@approveList'));
			Route::get('approvepaymentregistrationprocess/{engineerid}',array('before' => 'approvedforpaymentstatuscheck','uses'=>'Engineer@engineerDetails'));
			Route::post('mapprovepaymentforregistration',array('before'=>'statuscheckbeforeapproving','uses'=>'Engineer@approvePayment'));

			Route::get('mydashboard',array('uses'=>'MyEngineer@dashBoard'));
			Route::get('profile',array('uses'=>'MyEngineer@myProfile'));
			Route::get('certificate/{engineerId}',array('uses'=>'MyEngineer@myCertificate'));
			Route::get('applycancellation',array('uses'=>'MyEngineer@applyCancellation'));
			Route::get('applyrenewal',array('uses'=>'MyEngineer@applyRenewal'));
			Route::get('applyrenewalregistrationdetails/{engineerId}',array('uses'=>'MyEngineer@applyRenewalDetails'));
			Route::get('applyrenewalconfirmation/{engineerId}',array('uses'=>'MyEngineer@applyRenewalConfirmation'));
			Route::post('mcancelcertificate',array('uses'=>'MyEngineer@saveCancellation'));
			Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'EngineerServiceApplication@lockApplicationCancellationRequest'));	
			/*---------------Process for verifying and approcing services applied by engineers---------------*/
			Route::get('verifyserviceapplicationlist',array('uses'=>'EngineerServiceApplication@verifyApproveList'));
			Route::get('verifyserviceapplicationprocess/{engineerid}',array('uses'=>'EngineerServiceApplication@serviceApplicationDetails'));
			Route::post('mverifyserviceapplication',array('uses'=>'EngineerServiceApplication@verifyServiceApplicationRegistration'));
			Route::get('approveserviceapplicationlist',array('uses'=>'EngineerServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationprocess/{engineerid}',array('uses'=>'EngineerServiceApplication@serviceApplicationDetails'));
			Route::post('mapproveserviceapplication',array('uses'=>'EngineerServiceApplication@approveServiceApplicationRegistration'));

			Route::get('approveserviceapplicationfeepaymentlist',array('uses'=>'EngineerServiceApplication@verifyApproveList'));
			Route::get('approveserviceapplicationpaymentprocess/{engineerid}',array('uses'=>'EngineerServiceApplication@serviceApplicationDetails'));
			Route::post('mapprovepaymentserviceapplication',array('uses'=>'EngineerServiceApplication@approvePaymentServiceApplicationRegistration'));
			Route::get('approvecertificatecancellationrequestlist',array('uses'=>'EngineerServiceApplication@approveCertificateCancellationList'));
			Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'EngineerServiceApplication@lockApplicationCancellationRequest'));
			Route::get('approveserviceapplicationcancelcertificateprocess/{engineerId}/{cancelRequestId}',array('uses'=>'EngineerServiceApplication@approveCancelCertificateRequest'));
			Route::post('mapprovecancelcertificate',array('uses'=>'EngineerServiceApplication@approveCancellation'));
			//Ajax Route
			Route::get('fetchengineersjson',array('uses'=>'Engineer@fetchEngineersJSON'));

		});
		Route::group(array('prefix' => 'specializedtrade'),function(){
				Route::get('editregistrationinfo/{specializedtradeid}',array('uses'=>'SpecializedTrade@registration'));
				Route::get('verifyregistration',array('uses'=>'SpecializedTrade@verifyList'));
				Route::get('verifyregistrationprocess/{specializedtradeid}',array('before' => 'newstatuscheck','uses'=>'SpecializedTrade@specializedTradeDetails'));
				Route::post('mverifyregistration',array('before'=>'statuscheckbeforeverifying','uses'=>'SpecializedTrade@verifyRegistration'));
				Route::get('approveregistration',array('uses'=>'SpecializedTrade@approveList'));
				Route::get('approveregistrationprocess/{specializedtradeid}',array('before' => 'verifiedstatuscheck','uses'=>'SpecializedTrade@specializedTradeDetails'));
				Route::post('mapproveregistration',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'SpecializedTrade@approveRegistration'));
				Route::post('mrejectregistration',array('uses'=>'SpecializedTrade@rejectRegistration'));
				Route::get('editlist',array('uses'=>'SpecializedTrade@specializedTradeList'));
				Route::get('editdetails/{specializedtradeid}',array('uses'=>'SpecializedTrade@editDetails'));
				Route::get('viewprintlist',array('uses'=>'SpecializedTrade@SpecializedTradeList'));
				Route::get('viewprintdetails/{specializedtradeid}',array('uses'=>'SpecializedTrade@printDetails'));
				Route::get('newcommentsadverserecordslist',array('uses'=>'SpecializedTrade@specializedTradeList'));
				Route::get('newcommentsadverserecords/{specializedtradeid}',array('uses'=>'specializedTrade@newCommentAdverseRecord'));
				Route::post('mcommentadverserecord',array('uses'=>'SpecializedTrade@saveCommentAdverseRecord'));
				Route::get('editcommentsadverserecordslist',array('uses'=>'SpecializedTrade@specializedTradeList'));
				Route::get('editcommentsadverserecords/{specializedtradeid}',array('uses'=>'specializedTrade@editCommentAdverseRecord'));
				Route::post('meditcommentadverserecords',array('uses'=>'SpecializedTrade@updateCommentAdverseRecord'));
				Route::get('blacklist',array('uses'=>'SpecializedTrade@blacklistDeregisterList'));
				Route::get('deregister',array('uses'=>'SpecializedTrade@blacklistDeregisterList'));
				Route::get('reregistration',array('uses'=>'SpecializedTrade@blacklistDeregisterList'));
				Route::post('blacklistandderegister',array('uses'=>'SpecializedTrade@deregisterBlackListRegistration'));
				Route::get('lockapplication/{specializedtradeid}',array('uses'=>'SpecializedTrade@setRecordLock'));	
				Route::post('deletespecializedtradecommentadverserecord',array('uses'=>'SpecializedTrade@deleteCommentAdverseRecord'));
				
				Route::get('mydashboard',array('uses'=>'MySpecializedTrade@dashBoard'));
				Route::get('profile',array('uses'=>'MySpecializedTrade@myProfile'));
				Route::get('certificate/{specializedTradeId}',array('uses'=>'MySpecializedTrade@myCertificate'));
				Route::get('applycancellation',array('uses'=>'MySpecializedTrade@applyCancellation'));
				Route::get('applyrenewal',array('uses'=>'MySpecializedTrade@applyRenewal'));
				Route::get('applyrenewalregistrationdetails/{specializedTradeId}',array('uses'=>'MySpecializedTrade@applyRenewalDetails'));
				Route::get('applyrenewalconfirmation/{specializedTradeId}',array('uses'=>'MySpecializedTrade@applyRenewalConfirmation'));
				Route::post('mcancelcertificate',array('uses'=>'MySpecializedTrade@saveCancellation'));
				Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'SpecializedTradeServiceApplication@lockApplicationCancellationRequest'));	
				
				Route::get('verifyserviceapplicationlist',array('uses'=>'SpecializedTradeServiceApplication@verifyApproveList'));
				Route::get('verifyserviceapplicationprocess/{specializedtradeid}',array('before' => 'newstatuscheck','uses'=>'SpecializedTradeServiceApplication@serviceApplicationDetails'));
				Route::post('mverifyserviceapplication',array('before'=>'statuscheckbeforeverifying','uses'=>'SpecializedTradeServiceApplication@verifyServiceApplicationRegistration'));
				Route::get('approveserviceapplicationlist',array('uses'=>'SpecializedTradeServiceApplication@verifyApproveList'));
				Route::get('approveserviceapplicationprocess/{specializedtradeid}',array('uses'=>'SpecializedTradeServiceApplication@serviceApplicationDetails'));
				Route::post('mapproveserviceapplication',array('uses'=>'SpecializedTradeServiceApplication@approveServiceApplicationRegistration'));

				Route::get('approveserviceapplicationfeepaymentlist',array('uses'=>'SpecializedTradeServiceApplication@verifyApproveList'));
				Route::get('approveserviceapplicationpaymentprocess/{specializedtradeid}',array('before' => 'verifiedstatuscheck','uses'=>'SpecializedTradeServiceApplication@serviceApplicationDetails'));
				Route::post('mapprovepaymentserviceapplication',array('before'=>'statuscheckbeforeapprovingforpayment','uses'=>'SpecializedTradeServiceApplication@approvePaymentServiceApplicationRegistration'));
				Route::get('approvecertificatecancellationrequestlist',array('uses'=>'SpecializedTradeServiceApplication@approveCertificateCancellationList'));
				Route::get('cancelcertificatelockapplication/{cancelRequestId}',array('uses'=>'SpecializedTradeServiceApplication@lockApplicationCancellationRequest'));
				Route::get('approveserviceapplicationcancelcertificateprocess/{specializedTradeId}/{cancelRequestId}',array('uses'=>'SpecializedTradeServiceApplication@approveCancelCertificateRequest'));
				Route::post('mapprovecancelcertificate',array('uses'=>'SpecializedTradeServiceApplication@approveCancellation'));
				//Ajax route
				Route::get('fetchspecializedtradesjson',array('uses'=>'SpecializedTrade@fetchSpecializedTradesJSON'));
			});
		

		Route::group(array('prefix'=>'etl'),function(){
			Route::get('mydashboard',array('uses'=>'MyEtool@dashboard'));
			Route::get('reports',array('uses'=>'EtoolReports@getIndex'));
			Route::post('deleteevaldetail',array('uses'=>'EvaluationEtool@postDelete')); //Route to delete equipment or hr from db (from add contractor)
			Route::post('etooluploadtender',array('uses'=>'UploadTenderEtool@postSaveTender')); //Route to upload tender
			Route::post('addNonResponsive',array('uses'=>'EvaluationEtool@addNonResponsive')); //Route to upload tender
			Route::post('deleteNonResponsive',array('uses'=>'EvaluationEtool@deleteNonResponsive')); //Route to upload tender
			
			Route::post('cancelSmallTender',array('uses'=>'EvaluationEtool@cancelSmallTender')); //Route to upload tender
			Route::post('cancelTender',array('uses'=>'EvaluationEtool@cancelTender')); //Route to upload tender
			Route::post('savecriteria',array('uses'=>'CriteriaEtool@postSaveCriteria')); //Route to set criteria for work
			Route::post('etlsaveaddcontractor',array('uses'=>'EvaluationEtool@postSaveAddContractor')); //Route to save add contractor
			Route::post('awardcontractor',array('uses'=>'EvaluationEtool@postAwardContractor')); //Route to save add contractor
			Route::post('etlworkcompletion',array('uses'=>'WorkCompletionFormEtool@postWorkCompletion')); //Route to save add contractor
			Route::post('etlsavecommittee',array('uses'=>'EvaluationEtool@saveCommittee')); //Route to save add contractor
			Route::post('etlPostSeekClarification',array('uses'=>'EvaluationEtool@etlPostSeekClarification')); //Route to upload tender
            Route::get('seekclarification/{tenderId}/{cdbNo}/{contractorId}',array('uses'=>'MyEtool@seekclarification')); //Route to seek clarification
			Route::get('viewseekclarification/{id?}/{contractorId?}',array('uses'=>'MyEtool@viewseekclarification')); //Route to seek clarification
			Route::get('respondseekclarification/{id?}',array('uses'=>'MyEtool@respondseekclarification')); //Route to seek clarification
		

			Route::get('validateEgpTenderId/{egpTenderId?}',array('as'=>'validateEgpTenderId','uses'=>'UploadTenderEtool@validateEgpTenderId'));


			/*Ajax Routes*/
			Route::post('deletefile',array('uses'=>'UploadTenderEtool@postDeleteFile'));
			Route::post('deletefromdb',array('uses'=>'UploadTenderEtool@postDelete'));
			Route::post('fetchcontractor',array('uses'=>'EvaluationEtool@postFetchContractor'));
			Route::post('fetchcontractoroncdbno',array('uses'=>'EvaluationEtool@postFetchContractorOnCDBNo'));
			Route::post('fetchspecializedtradeoncdbno',array('uses'=>'EvaluationEtool@postFetchSpecializedtradeOnCDBNo'));
			Route::post('fetchcertifiedbuilderoncdbno',array('uses'=>'EvaluationEtool@postFetchCertifiedBuilderOnCDBNo'));
			/*End of Ajax Routes*/
			Route::get('uploadedtenderlistetool',array('as'=>'uploadedtenderlistetool','uses'=>'UploadTenderEtool@uploadedList'));
			Route::get('uploadtenderetool/{tenderid?}',array('as'=>'uploadtenderetool','uses'=>'UploadTenderEtool@index'));
			Route::get('workidetool',array('as'=>'workidetool','uses'=>'WorkIdEtool@index'));
			Route::get('setcriteriaetool/{tenderid?}',array('as'=>'setcriteriaetool','uses'=>'CriteriaEtool@index'));
			Route::get('evaluationetool',array('as'=>'evaluationetool','uses'=>'EvaluationEtool@index'));
			Route::get('evaluationcommiteeetool/{tenderid?}',array('as'=>'evaluationcommiteeetool','uses'=>'EvaluationEtool@evaluationCommittee'));
			Route::get('awardingcommiteeetool/{tenderid?}',array('as'=>'awardingcommiteeetool','uses'=>'EvaluationEtool@awardingCommittee'));
			Route::get('workevaluationdetails/{tenderid?}',array('as'=>'workevaluationdetails','uses'=>'EvaluationEtool@details'));
			Route::get('workevaluationaddcontractors/{tenderid}/{contractorid?}',array('as'=>'workevaluationaddcontractors','uses'=>'EvaluationEtool@addContractors'));
			Route::get('smallWorkevaluationaddcontractors/{tenderid}/{contractorid?}',array('as'=>'workevaluationaddcontractors','uses'=>'EvaluationEtool@addSmallContractors'));
			
 			Route::get('workevaluationprocessresult/{tenderid}',array('uses'=>'EvaluationEtool@processResult'));



			Route::get('workevaluationprocessresultLargetoSmall/{tenderid}',array('uses'=>'EvaluationEtool@processResultLargetoSmall'));
			
			

			Route::get('workevaluationsmallprocessresult/{tenderid}',array('uses'=>'EvaluationEtool@processResultSmall'));
			Route::get('workevaluationresetresult/{tenderid}',array('uses'=>'EvaluationEtool@resetResult'));
			Route::get('workevaluationsmallresetresult/{tenderid}',array('uses'=>'EvaluationEtool@resetResultSmall'));
			Route::get('workevaluationresult/{tenderid}',array('uses'=>'EvaluationEtool@viewResult'));
			Route::get('workevaluationpointdetails/{tenderid}/{contractorid}',array('as'=>'workevaluationpointdetails','uses'=>'EvaluationEtool@pointDetails'));
			Route::get('workevaluationdetailssmallcontractors/{tenderid?}/{contractorid?}',array('as'=>'workevaluationdetailssmallcontractors','uses'=>'EvaluationEtool@detailsSmall'));
			Route::get('listofworksetool',array('as'=>'listofworksetool','uses'=>'WorkCompletionFormEtool@listOfWorks'));
			Route::get('workcompletionformetool/{tenderid?}',array('as'=>'workcompletionformetool','uses'=>'WorkCompletionFormEtool@index'));
			Route::get('etoolresultreport/{tenderid}',array('as'=>'etoolresultreport','uses'=>'EvaluationEtool@getResultReport'));
			Route::get('etoolsmallworksreport/{tenderid}',array('as'=>'etoolsmallworksreport','uses'=>'EvaluationEtool@getSmallWorksReport'));
			
			Route::post('savequalifyingscore',array('uses'=>'SetQualifyingScore@postSave')); //Route to save add contractor
			Route::get('qualifyingscore',array('uses'=>'SetQualifyingScore@getIndex'));

			Route::get('bidevaluationparameters',array('uses'=>'BidEvaluationParameters@getIndex'));
			Route::post('savebidevaluationparameters',array('uses'=>'BidEvaluationParameters@postSave'));
		Route::get('blacklistedcontractor',array('uses'=>'EtoolController@postBlackListedContractor'));
			Route::post('blacklistedcontractor',array('uses'=>'EtoolController@postBlackListedContractor'));

			Route::get('etoolselectchange',array('uses'=>'MyEtool@getSelectChange'));
			Route::get('etoolchangetenderawarded',array('uses'=>'MyEtool@getChangeAwarded'));

			Route::post('saveotherremarks',array('uses'=>'MyEtool@postSaveOtherRemarks'));

			Route::get("tendersbiddingformuploaded","TendersBiddingFormUploaded@getIndex");

			Route::get('requestreplacerelease','EtoolSysReplaceRelease@getRequest');
			Route::post('rrfetchhrdetails','EtoolSysReplaceRelease@postFetchHr');
			Route::post('rrfetcheqdetails','EtoolSysReplaceRelease@postFetchEq');

			Route::post('saverrrequest','EtoolSysReplaceRelease@saveRequest');
		});


		/*------------------------End of Routes for etool-------------------------------------------*/


Route::group(array('prefix' => 'cinet'),function(){
			Route::get('mydashboard',array('uses'=>'MyCiNet@dashboard'));
			Route::get('biddingformoptions',array('uses'=>'CiNetBiddingForm@biddingReportOptions'));
			Route::get('biddingform/{tenderid?}',array('uses'=>'CiNetBiddingForm@biddingReport'));
			Route::get('editbiddingformlist',array('uses'=>'CiNetBiddingForm@listOfWorks'));
			Route::get('worklist',array('uses'=>'CiNetBiddingForm@listOfWorks'));
			Route::get('workcompletionform/{bidId}',array('uses'=>'CiNetBiddingForm@workCompletionForm'));
			Route::get('editcompletedworklist',array('uses'=>'CiNetBiddingForm@listOfWorks'));
			Route::get('uploadedtenderlistcinet',array('uses'=>'UploadTenderCiNet@uploadedList'));
			Route::get('uploadtendercinet/{tenderid?}',array('uses'=>'UploadTenderCiNet@index'));
			Route::get('uploadedtenderlistetool',array('as'=>'uploadedtenderlistetool','uses'=>'UploadTenderCiNet@uploadedList'));
			Route::post('deletefile',array('uses'=>'UploadTenderCiNet@postDeleteFile'));
			Route::post('deletefromdb',array('uses'=>'UploadTenderCiNet@postDelete'));
			Route::post('etooluploadtender',array('uses'=>'UploadTenderCiNet@postSaveTender')); //Route to upload tender
			Route::get('reports',array('uses'=>'CiNETReports@getIndex'));
			Route::get('contractorworkinhand',array('as'=>'cinet.contractorworkinhand','uses'=>'ReportContractorWorkInHand@getIndex'));
			Route::get('contractorhumanresource',array('as'=>'cinet.contractorhumanresource','uses'=>'ReportContractorHumanResource@getIndex'));
			Route::get('contractorequipment',array('as'=>'cinet.contractorequipment','uses'=>'ReportContractorEquipment@getIndex'));
			Route::get('contractorinfo',array('as'=>'cinet.contractorinfo','uses'=>'ReportContractorInformation@getIndex'));
			Route::get('hrcheck',array('as'=>'cinet.hrcheck','uses'=>'ReportHRCheck@getIndex'));
			Route::get('equipmentcheck',array('as'=>'cinet.equipmentcheck','uses'=>'ReportEquipmentCheck@getIndex'));
			Route::get('tendersuploaded',array('as'=>'cinet.tendersuploaded','uses'=>'CinetWorksReport@getTenderUploaded'));
			Route::get('biddingformuploaded',array('as'=>'cinet.biddingformuploaded','uses'=>'CinetWorksReport@getBidsUploaded'));
			Route::get('requestreplacerelease','EtoolSysReplaceRelease@getRequest');
		});
		/*------------------System Administration=--------------------------------------------------*/
		Route::group(array('prefix' => 'sys'),function(){
			Route::get('role/{roleid?}',array('uses'=>'RoleManagement@index'));
			Route::get('actionsrole',array('uses'=>'RoleManagement@roleList'));
			Route::post('mrole',array('uses'=>'RoleManagement@save'));
			Route::get('user/{userid?}',array('uses'=>'UserManagement@index'));
			Route::get('actionsuser',array('uses'=>'UserManagement@userList'));
			Route::post('muser',array('uses'=>'UserManagement@save'));
			Route::get('resetpassword',array('uses'=>'UserManagement@resetPasswordIndex'));
			Route::post('mresetpassword',array('uses'=>'UserManagement@resetPassword'));
			Route::get('changepassword/{viewType}',array('uses'=>'UserManagement@changePasswordIndex'))->where('viewType', '[1-2]+');
			Route::post('mchangepassword',array('uses'=>'UserManagement@changePassword'));
			Route::get('usercontconsarcengspect/{userid?}',array('uses'=>'CreateUserContConsArchEngSpecT@index'));
			Route::get('addnewsnotice/{messageId?}',array('uses'=>'NewsAndNotice@index'));
			Route::post('mnewsandnotifications',array('uses'=>'NewsAndNotice@save'));
			Route::get('editnewsnotice',array('uses'=>'NewsAndNotice@editList'));
			Route::get('sendmailsms',array('uses'=>'MailAndSms@index'));
			Route::post('msendmailsms',array('uses'=>'MailAndSms@save'));
			Route::get('deleterole/{id?}','RoleManagement@getDelete');
			Route::post("editprofile",'UserManagement@updateProfile');

			Route::post('registrationexistingapplicants','UserManagement@registerExistingApplicant');

			Route::get('fetchetoolusers',array('uses'=>'SystemController@fetchEtoolUsers'));
			Route::get('fetchcinetusers',array('uses'=>'SystemController@fetchCinetUsers'));
			Route::get('procuringagencyreportmap',array('uses'=>'RoleManagement@reportMap'));
			Route::get('editpausers',array('uses'=>'RoleManagement@editPAUsersReport'));
			Route::post('muserreportmap',array('uses'=>'RoleManagement@postSaveUserReportMap'));
			Route::get('fetchpausersjson',array('uses'=>'RoleManagement@fetchPaUsersJSON'));

			Route::get('deleteuser/{id?}','UserManagement@getDelete');
			Route::get('approveexistingusersregistration','UserManagement@getApplicantList');
			Route::get('approveregistrationapplication/{id}','UserManagement@approveApplicant');
			Route::get('deleteregistrationapplication/{id}','UserManagement@deleteApplicant');

			Route::post("emailavailability","UserManagement@emailAvailability");
			Route::post("usernameavailability","UserManagement@usernameAvailability");
			Route::get('userforarbitrationforum/{id?}','ArbitrationForum@createUser');
			Route::post('savearbitrationuser','ArbitrationForum@postSave');
			Route::post('resetarbpassword','ArbitrationForum@resetArbPassword');

			Route::get('managearbitrationforum','ArbitrationForum@getForumAdmin');
			Route::get('arbitrationforumadmin','ArbitrationForum@getAdmin');
			Route::get('arbitrationforumcategories','ArbitrationForum@getForumCategoriesAdmin');
			Route::get('arbitrationforumtopics','ArbitrationForum@getForumTopicsAdmin');
			Route::get('arbitrationforumposts','ArbitrationForum@getForumPostsAdmin');
			Route::post('savearbitrationadmin','ArbitrationForum@postSaveAdmin');
			Route::post('editforumcategory','ArbitrationForum@editForumCategory');
			Route::post('editforumtopic','ArbitrationForum@editForumTopic');
			Route::get('forumdelete','ArbitrationForum@deleteForumElement');


		});
		Route::group(array('prefix' => 'etoolsysadm'),function(){

			Route::get('replaceCinetHr/{id}',array('uses'=>'EtoolSysReplaceRelease@getReplaceCinetHR'));
			Route::post('releasehr',array('uses'=>'EtoolSysReplaceRelease@postReleaseHR'));
			Route::post('releaseCinetHR',array('uses'=>'EtoolSysReplaceRelease@postreleaseCinetHR'));
			
			Route::get('manageengprofile',array('uses'=>'EtoolSysManageEngineerProfile@index'));
			Route::post('relieveengineer',array('uses'=>'EtoolSysManageEngineerProfile@relieveEngineer'));
			Route::get('importcorporateengineer',array('uses'=>'EtoolSysManageEngineerProfile@importIndex'));
			Route::post('savecorporateengineer',array('uses'=>'EtoolSysManageEngineerProfile@postSave'));
			Route::get('deletecorporateengineer',array('uses'=>'EtoolSysManageEngineerProfile@deleteIndex'));
			Route::post('deletecorporateengineer',array('uses'=>'EtoolSysManageEngineerProfile@postDelete'));

			Route::get('replacereleasehrequipment',array('uses'=>'EtoolSysReplaceRelease@getIndex'));
			Route::post('replacereleasehrequipment',array('uses'=>'EtoolSysReplaceRelease@postFetch'));
Route::get('releaseCinethr/{id}',array('uses'=>'EtoolSysReplaceRelease@releaseCinethr'));

Route::get('releaseCinetequipment/{id}',array('uses'=>'EtoolSysReplaceRelease@releaseCinetequipment'));

            Route::get('replaceCitnetEquipment/{id}',array('uses'=>'EtoolSysReplaceRelease@replaceCitnetEquipment'));
			Route::post('replaceCinetEquipment',array('uses'=>'EtoolSysReplaceRelease@replaceCinetEquipment'));

			Route::post('releaseCinetEquipment',array('uses'=>'EtoolSysReplaceRelease@PostreleaseCinetEquipment'));


			Route::post('replaceCinetHR',array('uses'=>'EtoolSysReplaceRelease@replaceCinetHR'));



            
			Route::get('replaceequipment/{id}',array('uses'=>'EtoolSysReplaceRelease@getReplaceEquipment'));
			Route::post('replaceequipment',array('uses'=>'EtoolSysReplaceRelease@postReplaceEquipment'));

			Route::get('releaseequipment/{id}',array('uses'=>'EtoolSysReplaceRelease@getReleaseEquipment'));
			Route::post('releaseequipment',array('uses'=>'EtoolSysReplaceRelease@postReleaseEquipment'));

			Route::get('replacehr/{id}',array('uses'=>'EtoolSysReplaceRelease@getReplaceHR'));
			Route::post('replacehr',array('uses'=>'EtoolSysReplaceRelease@postReplaceHR'));

			Route::get('releasehr/{id}',array('uses'=>'EtoolSysReplaceRelease@getReleaseHR'));
			Route::post('releasehr',array('uses'=>'EtoolSysReplaceRelease@postReleaseHR'));

			Route::get('releasereplacerequestlist',array('as'=>'replacereleaselist','uses'=>'EtoolSysReplaceRelease@getRequestList'));

			Route::get('resetetoolresult',array('uses'=>'EtoolSysResetResult@getIndex'));
			Route::post('resetetoolresult',array('uses'=>'EtoolSysResetResult@postFetchWork'));
			Route::get('resetworkresult/{id}',array('uses'=>'EtoolSysResetResult@getResetResult'));
			Route::get('resettoawarded/{id}',array('uses'=>'EtoolSysResetResult@getResetToAwarded'));

			Route::get('editwork',array('as'=>'etoolsysadm.editwork','uses'=>'EtoolSysEditWork@getIndex'));
			
			Route::get('preferenceScore',array('uses'=>'EtoolSysEvaluationParameters@preferenceScore'));
			Route::get('selectevaluationparameters',array('uses'=>'EtoolSysEvaluationParameters@getIndex'));
			Route::get('editevaluationparameters/{id}',array('uses'=>'EtoolSysEvaluationParameters@getParameter'));
			Route::post('saveevaluationparameter',array('uses'=>'EtoolSysEvaluationParameters@postSave'));
			Route::post('postSaveBhutaneseEmploymentPreference',array('uses'=>'EtoolSysEvaluationParameters@postSaveBhutaneseEmploymentPreference'));
			
			Route::get('procuringagencyreport',array('uses'=>'EtoolProcuringAgencyReport@getIndex'));
			Route::get('viewprocuringagencyreport',array('uses'=>'EtoolProcuringAgencyReport@postView'));
			Route::get('processrrrequest/{id}',array('uses'=>'EtoolSysReplaceRelease@getDetails'));
			Route::post('postapproverequest','EtoolSysReplaceRelease@postApproveRequest');

			Route::get('getreplacehr','EtoolSysReplaceRelease@postReplaceHR');
			Route::get('getreleasehr','EtoolSysReplaceRelease@postReleaseHR');
			Route::get('getreplaceeq','EtoolSysReplaceRelease@postReplaceEquipment');
			Route::get('getreleaseeq','EtoolSysReplaceRelease@postReleaseEquipment');

			Route::get('rejectrrrequest/{id}','EtoolSysReplaceRelease@postRejectRequest');

		});
		/*------------------End of System Administartion=--------------------------------------*/
		Route::group(array('prefix'=>'rpt'),function(){
			Route::get('dashboard',array('uses'=>'ReportController@dashboard'));
			Route::get('audittrailetoolcinetreport',array('uses'=>'AuditTrailReport@auditTrailEtoolCinetReport'));
			Route::get('audittrailgeneralreport',array('uses'=>'AuditTrailReport@auditTrailGeneralReport'));
			Route::get('focalpersonsreport',array('as'=>'rpt.focalpersonsreport','uses'=>'ReportFocalPersons@getIndex'));
			Route::get('trackingreport',array('as'=>'rpt.trackingreport','uses'=>'ReportTrackingReport@getIndex'));
			Route::get('costoverrunreport',array('as'=>'rpt.costoverrunreport','uses'=>'ReportCostOverrun@getIndex'));
			Route::get('costoverrunreportsummary',array('as'=>'rpt.costoverrunreportsummary','uses'=>'ReportCostOverrun@getSummary'));
			Route::get('timeoverrunreport',array('as'=>'rpt.timeoverrunreport','uses'=>'ReportTimeOverrun@getIndex'));
			Route::get('timeoverrunreportsummary',array('as'=>'rpt.timeoverrunreportsummary','uses'=>'ReportTimeOverrun@getIndex'));
			Route::get('revenuecollectionreport',array('as'=>'rpt.revenuecollectionreport','uses'=>'ReportRevenueCollection@getIndex'));
			Route::get('revenuecollectiondetailed',array('as'=>'rpt.revenuecollectiondetailed','uses'=>'ReportRevenueCollection@getDetailed'));
			Route::get('auditclearancereport',array('as'=>'rpt.auditclearancereport','uses'=>'ContractorAuditMemo@getAuditClearanceReport'));
			Route::get('auditclearancereportdropped',array('as'=>'rpt.auditclearancereportdropped','uses'=>'ContractorAuditMemo@getAuditClearanceReportDropped'));
		});
		Route::group(array('prefix'=>'contractorrpt'),function(){
			Route::get('trackrecord',array('as'=>'contractorrpt.trackrecord','uses'=>'ReportContractorTrackRecord@getIndex'));
                      Route::get('expiredcontractor',array('as'=>'contractorrpt.expiredcontractor','uses'=>'ReportListOfExpiredContractor@getIndex'));
                     Route::get('contractorinfo',array('as'=>'contractorrpt.contractorinfo','uses'=>'ReportContractorInformation@getIndex'));
			Route::get('listofworkawardedandcompleted',array('as'=>'contractorrpt.listofworkawardedandcompleted','uses'=>'ReportOfTotalWorkAwardedAndCompleted@getIndex'));
			Route::get('listOfContractorwithserviceavail',array('as'=>'contractorrpt.listOfContractorwithserviceavail','uses'=>'ReportListOfContractorwithserviceavail@getIndex'));
			Route::get('workinhand',array('as'=>'contractorrpt.workinhand','uses'=>'ReportListOfWorkinHandContractor@getIndex'));
	              
			Route::get('listofworknotinhand',array('as'=>'contractorrpt.listofworknotinhand','uses'=>'ReportListOfWorkNotinHand@getIndex'));
			Route::get('listofregisteredhrwithcontractors',array('as'=>'contractorrpt.listofregisteredhrwithcontractors','uses'=>'ReportListOfHrContractor@getIndex'));
			Route::get('listofeuipmentregisteredwithcontractor',array('as'=>'contractorrpt.listofeuipmentregisteredwithcontractor','uses'=>'ReportListOfEquipmentContractor@getIndex'));
			Route::get('privatesite',array('as'=>'contractorrpt.privatesite','uses'=>'ReportListOfContractors@privatesite'));
			Route::get('monitoringOffice',array('as'=>'contractorrpt.monitoringOffice','uses'=>'ReportListOfContractors@monitoringOffice'));
			Route::get('publicsite',array('as'=>'contractorrpt.publicsite','uses'=>'ReportListOfContractors@publicsite'));
			Route::get('actionTaken',array('as'=>'contractorrpt.actionTaken','uses'=>'ReportListOfContractors@actionTaken'));
			Route::get('listofcontractorsotparticipatinganywork',array('as'=>'contractorrpt.listofcontractorsotparticipatinganywork','uses'=>'ReportListOfContractors@listofcontractorsotparticipatinganywork'));
			Route::get('listofcontractorswithworkinhand',array('as'=>'contractorrpt.listofcontractorswithworkinhand','uses'=>'ReportListOfContractors@listofcontractorswithworkinhand'));
			Route::get('listofcontractors',array('as'=>'contractorrpt.listofcontractors','uses'=>'ReportListOfContractors@getIndex'));
			Route::get('listofcontractorsnearingexpiry',array('as'=>'contractorrpt.listofexpirycontractor','uses'=>'ReportListOfContractorExpiry@getIndex'));
			Route::get('listofcontractorsrevoked',array('as'=>'contractorrpt.listofcontractorsrevoked','uses'=>'ReportListOfRevokedContractors@getIndex'));
			Route::get('listofcontractorsderegistered',array('as'=>'contractorrpt.listofcontractorsderegistered','uses'=>'ReportListOfDeregisteredContractors@getIndex'));
			Route::get('contractorscategorywise',array('as'=>'contractorrpt.contractorscategorywise','uses'=>'ReportListOfContractors@getCategoryWise'));
			Route::get('listofnonbhutanesecontractors',array('as'=>'contractorrpt.listofnonbhutanesecontractors','uses'=>'ReportListOfNonBhutaneseContractors@getIndex'));
			Route::get('progressreport',array('uses'=>'ProgressReport@getIndex'));
			Route::get('contractorsbydzongkhag',array('as'=>'contractorrpt.contractorsbydzongkhag','uses'=>'ContractorsByDzongkhag@getIndex'));
			Route::get('contractorsbydzongkhagsummary',array('as'=>'contractorrpt.contractorsbydzongkhagsummary','uses'=>'ContractorsByDzongkhag@getSummary'));
			Route::get('workdistribution',array('uses'=>'WorkDistribution@getIndex'));
			Route::get('workdistributionbyclass',array('uses'=>'WorkDistributionByClass@getIndex'));
			Route::get('workdistributionbycategory',array('uses'=>'WorkDistributionByCategory@getIndex'));
			Route::get('categorywisereport',array('as'=>'contractorrpt.categorywisereport', 'uses'=>'CategoryWiseReport@getIndex'));
			Route::get('contractorswithcomments',array('as'=>'contractorrpt.contractorswithcomments', 'uses'=>'ContractorsComments@getIndex'));
			Route::get('contractorswithadvrecords',array('as'=>'contractorrpt.contractorswithadvrecords', 'uses'=>'ContractorsAdvRecords@getIndex'));
			Route::get('noofengineers',array('as'=>'contractorrpt.noofengineers', 'uses'=>'NoOfEngineers@getIndex'));
			Route::get('engineers',array('as'=>'contractorrpt.engineers', 'uses'=>'Engineers@getIndex'));
			Route::get('listofengineersexpired',array('as'=>'contractorrpt.listofengineersexpired','uses'=>'ReportRegistrationExpired@getIndex'));
			Route::get('noofemployees',array('as'=>'contractorrpt.noofemployees','uses'=>'ReportNoOfEmployees@getIndex'));
			Route::get('equipmentreport',array('as'=>'contractorrpt.equipmentreport','uses'=>'ReportEquipmentGroup@getIndex'));
			Route::get('equipmentreport',array('as'=>'contractorrpt.equipmentreport','uses'=>'ReportEquipmentGroup@getIndex'));
			Route::get('contractorhistory',array('as'=>'contractorrpt.contractorhistory','uses'=>'ReportContractorHistory@getIndex'));
			Route::get('contractorregistrationdetail',array('as'=>'contractorrpt.contractorregistrationdetail','uses'=>'ReportContractorRegistrationDetail@getIndex'));
			Route::get('constructionpersonnel',array('as'=>'contractorrpt.constructionpersonnel','uses'=>'ReportConstructionPersonnel@getIndex'));
			Route::get('monitoringlistofsuspendedfirms',array('as'=>'contractorrpt.monitoringlistofsuspendedfirms','uses'=>'ReportMonitoring@getListOfSuspendedFirms'));
			Route::get('monitoringlistoffirmsstatuswise',array('as'=>'contractorrpt.monitoringlistoffirmsstatuswise','uses'=>'ReportMonitoring@getListOfPassedFirms'));
			Route::get('monitoringlistoffirms',array('as'=>'contractorrpt.monitoringlistoffirms','uses'=>'ReportMonitoring@getListOfFirms'));
			Route::get('monitoringlistofsites',array('as'=>'contractorrpt.monitoringlistofsites','uses'=>'ReportMonitoring@getListOfSites'));
			Route::get('personnelqualificationgenderwise',array('as'=>'contractorrpt.personnelqualificationgenderwise','uses'=>'ReportConstructionPersonnel@getQualificationGenderWise'));
			Route::get('topperformingcontractor',array('as'=>'contractorrpt.topperformingcontractor','uses'=>'ReportListOfContractors@topperformingcontractor'));
			Route::get('defaultcontractors',array('as'=>'contractorrpt.defaultcontractors','uses'=>'ReportListOfContractors@defaultcontractors'));
			Route::get('totalparticipant',array('as'=>'contractorrpt.totalparticipant','uses'=>'ReportListOfContractors@totalparticipant'));
			Route::get('actionTakenReport',array('as'=>'contractorrpt.actionTakenReport','uses'=>'ReportListOfContractors@actionTakenReport'));

			Route::get('generateOfficeReport',array('as'=>'contractorrpt.generateOfficeReport','uses'=>'ReportListOfContractors@generateOfficeReport'));

			Route::get('reportDetails',array('as'=>'contractorrpt.reportDetails','uses'=>'ReportListOfContractors@reportDetails'));
			
		});
		Route::group(array('prefix'=>'engineerrpt'),function(){
			Route::get('listofconsultantengineer',array('as'=>'engineerrpt.listofconsultantengineer','uses'=>'ReportListOfEngineers@listofconsultantengineer'));
                     Route::get('expiredengineer',array('as'=>'engineerrpt.expiredengineer','uses'=>'ReportListOfExpiredEngineer@getIndex'));
                     Route::get('listofengineerexpiry',array('as'=>'engineerrpt.listofengineerexpiry', 'uses'=>'ReportListOfEngineerExpiry@getIndex'));
                     Route::get('engineerinformation',array('as'=>'engineerrpt.engineerinformation','uses'=>'ReportEngineerInformation@getIndex'));
			Route::get('listofengineers',array('as'=>'engineerrpt.listofengineers','uses'=>'ReportListOfEngineers@getIndex'));
			Route::get('engineerdetail',array('as'=>'engineerrpt.engineerdetail','uses'=>'ReportEngineerDetail@getIndex'));
			Route::get('listOfEngineerwithserviceavail',array('as'=>'engineerrpt.listOfEngineerwithserviceavail','uses'=>'ReportListOfEngineerwithserviceavail@getIndex'));
			Route::get('engineersbydzongkhag',array('as'=>'engineerrpt.engineersbydzongkhag','uses'=>'ReportEngineersByDzongkhag@getIndex'));
			Route::get('engineerregistrationdetail',array('as'=>'engineerrpt.engineerregistrationdetail','uses'=>'ReportEngineerRegistrationDetail@getIndex'));
		});
		Route::group(array('prefix'=>'surveyorrpt'),function(){
			Route::get('listofsurveyor',array('as'=>'surveyorrpt.listofsurveyor','uses'=>'SurveyorReport@getIndex'));
                     Route::get('expiredsurveyor',array('as'=>'surveyorrpt.expiredsurveyor','uses'=>'ReportListOfExpiredSurveyor@getIndex'));
                     Route::get('listofsurveyorexpiry',array('as'=>'surveyorrpt.listofsurveyorexpiry', 'uses'=>'ReportListOfSurveyorExpiry@getIndex'));
                     Route::get('surveyorinformation',array('as'=>'surveyorrpt.surveyorinformation','uses'=>'ReportSurveyorInformation@getIndex'));
			Route::get('surveyordetail',array('as'=>'surveyorrpt.surveyordetail','uses'=>'ReportSurveyDetail@getIndex'));
			Route::get('listOfSurveywithserviceavail',array('as'=>'surveyorrpt.listOfSurveywithserviceavail','uses'=>'ReportListOfSurveywithserviceavail@getIndex'));
			Route::get('listofsurveyorexpiring',array('as'=>'surveyorrpt.listofsurveyorexpiring','uses'=>'SurveyorReport@getIndex'));
		});
		Route::group(array('prefix'=>'ezotinrpt'),function(){
			Route::get('listofapplicantsdueforpayment',array('as'=>'ezotinrpt.listofapplicantsdueforpayment','uses'=>'EzotinReports@getApplicantsDueForPayment'));
			Route::get('servicesavailed',array('as'=>'ezotinrpt.servicesavailed','uses'=>'EzotinReports@getServicesAvailed'));
			Route::get('auditdroppedmemos',array('as'=>'ezotinrpt.auditdroppedmemos','uses'=>'EzotinReports@auditDroppedMemos'));
			Route::get('etoolworkopportunityreport',array('as'=>'ezotinrpt.etoolworkopportunityreport','uses'=>'EzotinReports@workOpportunityReport'));
			Route::get('applicantscancelledafternonpayment',array('as'=>'ezotinrpt.applicantscancelledafternonpayment','uses'=>'EzotinReports@applicantsCancelledAfterNonPayment'));;
			Route::get('worksmasterreport',array('as'=>'ezotinrpt.worksmasterreport','uses'=>'EzotinReports@worksMasterReport'));;
		});
	
		Route::group(array('prefix'=>'architectrpt'),function(){
			Route::get('listofarchitects',array('as'=>'architectrpt.listofarchitects', 'uses'=>'ReportListOfArchitects@getIndex'));
                     Route::get('expiredarchitect',array('as'=>'architectrpt.expiredarchitect', 'uses'=>'ReportListOfExpiredArchitect@getIndex'));
                     Route::get('architectexpiry',array('as'=>'architectrpt.architectexpiry', 'uses'=>'ReportListOfArchitectExpiry@getIndex'));
                     Route::get('architectinformation',array('as'=>'architectrpt.architectinformation','uses'=>'ReportArchitectInformation@getIndex'));
			Route::get('listOfArchitectwithserviceavail',array('as'=>'architectrpt.listOfArchitectwithserviceavail','uses'=>'ReportListOfArchitectwithserviceavail@getIndex'));
                     Route::get('architectdetail',array('as'=>'architectrpt.architectdetail','uses'=>'ReportArchitectDetail@getIndex'));
			Route::get('architectsbydzongkhag',array('as'=>'architectrpt.architectsbydzongkhag', 'uses'=>'ReportArchitectsByDzongkhag@getIndex'));
			Route::get('architectregistrationdetail',array('as'=>'architectrpt.architectregistrationdetail','uses'=>'ReportArchitectRegistrationDetail@getIndex'));
		});

		Route::group(array('prefix'=>'surveyorrpt'),function(){
			Route::get('listofsurveyors',array('as'=>'surveyorrpt.listofsurveyors', 'uses'=>'ReportListOfSurveyors@getIndex'));
			Route::get('surveyorsbydzongkhag',array('as'=>'surveyorrpt.surveyorsbydzongkhag', 'uses'=>'ReportSurveyorsByDzongkhag@getIndex'));
			Route::get('surveyorregistrationdetail',array('as'=>'surveyorrpt.surveyorregistrationdetail','uses'=>'ReportSurveyorRegistrationDetail@getIndex'));
		});

		Route::group(array('prefix'=>'consultantrpt'),function(){
			Route::get('listofconsultants',array('as'=>'consultantrpt.listofconsultants','uses'=>'ReportListOfConsultants@getIndex'));
                     Route::get('expiredconsultant',array('as'=>'consultantrpt.expiredconsultant','uses'=>'ReportListOfExpiredConsultant@getIndex'));
			Route::get('consultantinformation',array('as'=>'consultantrpt.consultantinformation','uses'=>'ReportConsultantInformation@getIndex'));
			Route::get('consultantdetail',array('as'=>'consultantrpt.consultantdetail','uses'=>'ReportConsultantDetail@getIndex'));

                     Route::get('listOfConsultantwithserviceavail',array('as'=>'consultantrpt.listOfConsultantwithserviceavail','uses'=>'ReportListOfConsultantwithserviceavail@getIndex'));
			Route::get('listofregisteredhrwithconsultants',array('as'=>'consultantrpt.listofregisteredhrwithconsultants','uses'=>'ReportListOfHrConsultant@getIndex'));
			Route::get('listofeuipmentregisteredwithconsultant',array('as'=>'consultantrpt.listofeuipmentregisteredwithconsultant','uses'=>'ReportListOfEquipmentConsultant@getIndex'));
			Route::get('listofconsultantsnearingexpiry',array('as'=>'consultantrpt.listofconsultantsnearingexpiry','uses'=>'ReportListOfConsultants@getIndex'));
			Route::get('listofconsultantsrevoked',array('as'=>'consultantrpt.listofconsultantsrevoked','uses'=>'ReportListOfRevokedConsultants@getIndex'));
			Route::get('listofconsultantsderegistered',array('as'=>'consultantrpt.listofconsultantsderegistered','uses'=>'ReportListOfDeregisteredConsultants@getIndex'));
			Route::get('consultantsbydzongkhag',array('as'=>'consultantrpt.consultantsbydzongkhag','uses'=>'ReportConsultantsByDzongkhag@getIndex'));
			Route::get('consultantregistrationdetail',array('as'=>'consultantrpt.consultantregistrationdetail','uses'=>'ReportConsultantRegistrationDetail@getIndex'));
			Route::get('consultantsservicewisesummary',array('as'=>'consultantrpt.consultantsservicewisesummary','uses'=>'ReportConsultantsServicewiseSummary@getIndex'));
			Route::get('consultantswithadvrecords',array('as'=>'consultantrpt.consultantswithadvrecords', 'uses'=>'ConsultantsAdvRecords@getIndex'));
		});

		Route::group(array('prefix'=>'specializedfirmrpt'),function(){
			Route::get('listofspecializedfirm',array('as'=>'specializedfirmrpt.listofspecializedfirm','uses'=>'ReportListOfSpecializedfirm@getIndex'));
                   Route::get('expiredspecializedfirm',array('as'=>'specializedfirmrpt.expiredspecializedfirm','uses'=>'ReportListOfExpiredSpecializedFirm@getIndex'));
			Route::get('specializedfirmdetail',array('as'=>'specializedfirmrpt.specializedfirmdetail','uses'=>'ReportSpecializedfirmDetail@getIndex'));
                     Route::get('specializedfirminformation',array('as'=>'specializedfirmrpt.specializedfirminformation','uses'=>'ReportSpecializedfirmInformation@getIndex'));
                     Route::get('listOfSpecializedfirmwithserviceavail',array('as'=>'specializedfirmrpt.listOfSpecializedfirmwithserviceavail','uses'=>'ReportListOfSpecializedfirmwithserviceavail@getIndex'));
			Route::get('listofregisteredhrwithspecializedfirm',array('as'=>'specializedfirmrpt.listofregisteredhrwithspecializedfirm','uses'=>'ReportListOfHrSpecializedfirm@getIndex'));
			Route::get('listofeuipmentregisteredwithspecializedfirm',array('as'=>'specializedfirmrpt.listofeuipmentregisteredwithspecializedfirm','uses'=>'ReportListOfEquipmentSpecializedfirm@getIndex'));
			Route::get('listofspecializedfimbynearingexpiry',array('as'=>'specializedfirmrpt.listofspecializedfimbynearingexpiry','uses'=>'ReportListOfSpecializedfirm@getIndex'));
			Route::get('listofspecializedfirmrevoked',array('as'=>'specializedfirmrpt.listofspecializedfirmrevoked','uses'=>'ReportListOfRevokedSpecializedfirm@getIndex'));
			Route::get('listofspecializedfimbydzongkhag',array('as'=>'specializedfirmrpt.listofspecializedfimbydzongkhag','uses'=>'ReportSpecializedfirmByDzongkhag@getIndex'));
			Route::get('specializedfirmregistrationdetail',array('as'=>'specializedfirmrpt.specializedfirmregistrationdetail','uses'=>'ReportSpecializedfirmRegistrationDetail@getIndex'));
			Route::get('listofspecializedfimbycategory',array('as'=>'specializedfirmrpt.listofspecializedfimbycategory','uses'=>'ReportSpecializedfirmServicewiseSummary@getIndex'));
			Route::get('specializedfirmwithcomments',array('as'=>'specializedfirmrpt.specializedfirmwithcomments', 'uses'=>'SpecializedfirmComments@getIndex'));
			Route::get('specializedfirmwithadverserecords',array('as'=>'specializedfirmrpt.specializedfirmwithadverserecords', 'uses'=>'SpecializedfirmAdvRecords@getIndex'));
            Route::get('trackrecords',array('as'=>'specializedfirmrpt.trackrecords','uses'=>'ReportSpecializedfirmTrackRecord@getIndex'));
			Route::get('equipmentreport',array('as'=>'specializedfirmrpt.equipmentreport','uses'=>'ReportEquipmentGroup@getIndex'));
			Route::get('worksummarybyagency',array('uses'=>'WorkDistribution@getIndex'));

		});


		Route::group(array('prefix'=>'specializedtraderpt'),function(){
                     Route::get('specializedtradeinformation',array('as'=>'specializedtraderpt.specializedtradeinformation','uses'=>'ReportSpecializedtradeInformation@getIndex'));
                      Route::get('expiredspecializedtrade',array('as'=>'specializedtraderpt.expiredspecializedtrade','uses'=>'ReportListOfExpiredSpecializedTrade@getIndex'));
Route::get('listofspecializedtradeexpiry',array('as'=>'specializedtraderpt.listofspecializedtradeexpiry', 'uses'=>'ReportListOfSpecializedtradeExpiry@getIndex'));
			Route::get('listofspecializedtrade',array('as'=>'specializedtraderpt.listofspecializedtrade','uses'=>'ReportListOfSpecializedTrade@getIndex'));
			Route::get('specializedtradebydzongkhag',array('as'=>'specializedtraderpt.specializedtradebydzongkhag','uses'=>'ReportSpecializedTradeByDzongkhag@getIndex'));
			Route::get('specializedtradedetail',array('as'=>'specializedtraderpt.specializedtradedetail','uses'=>'ReportSpecializedtradeDetail@getIndex'));
            Route::get('listOfSpecializedtradewithserviceavail',array('as'=>'specializedtraderpt.listOfSpecializedtradewithserviceavail','uses'=>'ReportListOfSpecializedtradewithserviceavail@getIndex'));

			
		});
		Route::group(array('prefix'=>'etoolrpt'),function(){
			Route::get('contractorworkinhand',array('as'=>'etoolrpt.contractorworkinhand','uses'=>'ReportContractorWorkInHand@getIndex'));
                 Route::get('contractorinfo',array('as'=>'etoolrpt.contractorinfo','uses'=>'ReportContractorInformation@getIndex'));
			Route::get('contractorhumanresource',array('as'=>'etoolrpt.contractorhumanresource','uses'=>'ReportContractorHumanResource@getIndex'));
			Route::get('contractorequipment',array('as'=>'etoolrpt.contractorequipment','uses'=>'ReportContractorEquipment@getIndex'));
                     Route::get('evaluationtrack',array('as'=>'etoolrpt.evaluationtrack','uses'=>'EvaluationTrack@getIndex'));
			
			Route::get('agencywisework',array('as'=>'etoolrpt.agencywisework','uses'=>'ReportAgencyWiseWork@getIndex'));
			Route::get('agencycategorywisework',array('as'=>'etoolrpt.agencycategorywisework','uses'=>'ReportAgencyCategoryWiseWork@getIndex'));
			Route::get('dzongkhagwisework',array('as'=>'etoolrpt.dzongkhagwisework','uses'=>'ReportDzongkhagWiseWork@getIndex'));
			Route::get('dzongkhagcategorywisework',array('as'=>'etoolrpt.dzongkhagcategorywisework','uses'=>'ReportDzongkhagCategoryWiseWork@getIndex'));
			Route::get('apscontractor',array('as'=>'etoolrpt.apscontractor','uses'=>'ReportAPSContractor@getIndex'));
                     Route::get('apscontractorcompleted',array('as'=>'etoolrpt.apscontractorcompleted','uses'=>'ReportAPSContractorCompleted@getIndex'));
			Route::get('apscontractorontime',array('as'=>'etoolrpt.apscontractorontime','uses'=>'ReportAPSContractor@getIndex'));
			Route::get('apscontractorquality',array('as'=>'etoolrpt.apscontractorquality','uses'=>'ReportAPSContractor@getIndex'));
			Route::get('listofengagedequipments',array('as'=>'etoolrpt.listofengagedequipments','uses'=>'ReportEngagedEquipments@getIndex'));
			Route::get('listofengagedhretool',array('as'=>'etoolrpt.listofengagedhretool','uses'=>'ReportEngagedHrEtool@getIndex'));
			Route::get('engagedequipmentbydzongkhag',array('as'=>'etoolrpt.engagedequipmentbydzongkhag','uses'=>'ReportEngagedEquipmentByDzongkhag@getIndex'));
			Route::get('agencywisenoofworks',array('as'=>'etoolrpt.agencywisenoofworks','uses'=>'ReportAgencyWiseNoOfWorks@getIndex'));
			Route::get('listofterminatedcancelled',array('as'=>'etoolrpt.listofterminatedcancelled','uses'=>'ReportListOfTerminatedCancelled@getIndex'));
			Route::get('evaluationdetails',array('as'=>'etoolrpt.evaluationdetails','uses'=>'ReportEvaluationDetails@getIndex'));
			Route::get('workdetails',array('as'=>'etoolrpt.workdetails','uses'=>'ReportWorkDetails@getIndex'));
			Route::get('hrcheck',array('as'=>'etoolrpt.hrcheck','uses'=>'ReportHRCheck@getIndex'));
			Route::post('hrcheck',array('as'=>'etoolrpt.hrcheck','uses'=>'ReportHRCheck@getIndex'));
			Route::get('checkequipment',array('as'=>'etoolrpt.checkequipment','uses'=>'ReportEquipmentCheck@getReportPage'));
			Route::get('equipmentcheck',array('as'=>'etoolrpt.equipmentcheck','uses'=>'ReportEquipmentCheck@getIndex'));
			Route::post('equipmentcheck',array('as'=>'etoolrpt.equipmentcheck','uses'=>'ReportEquipmentCheck@getIndex'));    
			Route::get('hrotherfirms',array('as'=>'etoolrpt.hrotherfirms','uses'=>'ReportHRCheck@getOtherFirms'));
			Route::post('hrotherfirms',array('as'=>'etoolrpt.hrotherfirms','uses'=>'ReportHRCheck@getOtherFirms'));
		
			Route::get('tenderuploaded',array('as'=>'etoolrpt.tenderuploaded','uses'=>'ReportTenderUploaded@getIndex'));
			Route::get('reportworkid',array('as'=>'etoolrpt.reportworkid','uses'=>'ReportWorkId@getIndex'));
			Route::get('reportevaluation',array('as'=>'etoolrpt.reportevaluation','uses'=>'ReportEvaluation@getIndex'));
			Route::get('reporttracking',array('as'=>'etoolrpt.reporttracking','uses'=>'ReportTracking@getIndex'));

			Route::get('agencyworkhistory',array('as'=>'etoolrpt.agencyworkhistory','uses'=>'ReportAgencyWorkHistory@getIndex'));
			Route::get('selfworkhistory',array('as'=>'etoolrpt.selfworkhistory','uses'=>'ReportSelfWorkHistory@getIndex'));
			Route::get('tendersdownloadedfromweb',array('as'=>'etoolrpt.tendersdownloadedfromweb','uses'=>'ReportTenders@getDownloadedFromWeb'));
			Route::get('gettenderdownloaddetails/{id}','ReportTenders@getDownloadDetails');
			Route::get('bidcapacitycalculator','ReportBidCapacityCalculator@getIndex');
			Route::post("calculatebidcapacity","ReportBidCapacityCalculator@postCalculate");
			Route::get('replacereleasereport',array('as'=>'etoolrpt.replacereleasereport','uses'=>'EtoolSysReplaceRelease@getReport'));
			Route::get('ldreport',array('as'=>'etoolrpt.ldreport','uses'=>'LDandHindranceReport@getLD'));
			Route::get('hindrancereport',array('as'=>'etoolrpt.hindrancereport','uses'=>'LDandHindranceReport@getHindrance'));

			Route::get('workcompletedsameyear',array('as'=>'etoolrpt.workcompletedsameyear','uses'=>'ReportTenderUploaded@workcompletedsameyear'));
			Route::get('workawardedabovebelowestimate',array('as'=>'etoolrpt.workawardedabovebelowestimate','uses'=>'ReportTenderUploaded@workawardedabovebelowestimate'));
		
		});

		/* Routes for forum */
		Route::group(array('prefix' => 'forum'), function(){

			Route::get('topic', array('uses' => 'TopicController@index'));
			Route::post('save-topic', array('uses' => 'TopicController@Save'));
			Route::get('topic/delete/{id}', array('uses' => 'TopicController@Delete'));
			Route::get('topic/edit/{id}', array('uses' => 'TopicController@Edit'));
			Route::get('comment/approve/{id}', array('uses' => 'TopicController@Approve'));
			Route::get('comment/disapprove/{id}', array('uses' => 'TopicController@Disapprove'));
			Route::get('topic/view/{id}', array('uses'=>'TopicController@ViewForum'));

		});

		/* END */
	});
	Route::get('citcheck/{cid?}','WebServiceG2C@getCitCheck');
	Route::get('vehcheck/{reg}/{type}','WebServiceG2C@getVehCheck');
	/*------------------------Droelma Namgyal's Routes for Website Start------------------------*/
	Route::group(array('prefix' => 'web'),function(){
		Route::post('fetchdetails','WebTrainingController@fetchDetails');
		Route::get('postsearch','WebsiteController@search');
		Route::post('cidnocheckfortraining','WebTrainingController@postCheckCIDNo');
		Route::post('cdbnocheckfortraining','WebTrainingController@postCheckCDBNo');
		Route::get('index',array('as'=>'indexpage','uses'=>'WebsiteController@index'));
		Route::get('titleid/{id}',array('as'=>'pagedetails','uses'=>'WebsiteController@pageDetails'));	//Route to populate pages with their respective details
		Route::get('aboutus',array('as'=>'aboutuspage','uses'=>'WebsiteController@aboutUs'));
		Route::get('organogram',array('as'=>'cdborganogram','uses'=>'WebsiteController@organogram'));
		Route::get('weblinks',array('as'=>'weblinkspage','uses'=>'WebsiteController@webLinks'));
		Route::get('contractorregistrationdetails',array('as'=>'contractorregistrationdetailspage','uses'=>'WebsiteController@contractorRegistrationDetails'));
		Route::get('consultantregistrationdetails',array('as'=>'consultantregistrationdetailspage','uses'=>'WebsiteController@consultantRegistrationDetails'));
		Route::get('architectregistrationdetails',array('as'=>'architectregistrationdetailspage','uses'=>'WebsiteController@architectRegistrationDetails'));
		Route::get('specializedtraderegistrationdetails',array('as'=>'specializedtraderegistrationdetailspage','uses'=>'WebsiteController@specializedTradeRegistrationDetails'));
		Route::get('cbregistrationdetails',array('as'=>'certifiedbuilderregistrationdetailspage','uses'=>'WebsiteController@certifiedbuilderRegistrationDetails'));
		Route::get('arbitrationcommittee',array('as'=>'arbitrationcommitteepage','uses'=>'WebsiteController@arbitrationCommittee'));
		Route::get('codeofethics',array('as'=>'codeofethicspage','uses'=>'WebsiteController@codeOfEthics'));
		Route::get('aboutarbitration',array('as'=>'aboutarbitrationpage','uses'=>'WebsiteController@aboutArbitration'));
		Route::get('arbitration',array('as'=>'arbitration','uses'=>'WebsiteController@arbitration'));








/*Route for journal start*/
		Route::get('/dzo',array('uses'=>'DzongkhaController@index'));
		Route::get('search',array('uses'=>'JournalController@journalsearch'));
		Route::get('journal',array('uses'=>'JournalController@index'));
		Route::get('journallogin',array('uses'=>'JournalController@login'));
		Route::get('gnhjournal',array('uses'=>'JournalMenuController@index'));

		Route::get('optionDownload/{categoryid}',array('uses'=>'JournalMenuController@optionDownload'));
		Route::get('journalverification',array('uses'=>'JournalController@journalverification'));
		
		Route::post('journalusereditprofile',array('uses'=>'JournalController@journalusereditprofile'));
		Route::get('journalauthordashboard',array('uses'=>'JournalController@journalauthordashboard'));
		Route::get('journalresubmission/{Id}',array('uses'=>'JournalController@journalresubmission'));
		Route::get('journaleditprofile/{Id}',array('uses'=>'JournalController@journaleditprofile'));
		Route::get('journalmanuscript/{Id}',array('uses'=>'JournalController@journalmanuscript'));
		Route::post('submitmanuscript',array('uses'=>'JournalController@submitmanuscript'));
		Route::get('journalregistrationsubmitted',array('uses'=>'JournalController@journalregistrationsubmitted'));
		Route::get('aboutthejournal',array('uses'=>'JournalMenuController@aboutthejournal'));
		Route::get('journalaimsandscope',array('uses'=>'JournalMenuController@journalaimsandscope'));
		Route::get('peerreviewjournal',array('uses'=>'JournalMenuController@peerreviewjournal'));
		Route::get('conflictofinterestjournal',array('uses'=>'JournalMenuController@conflictofinterestjournal'));
		Route::get('contactjournal',array('uses'=>'JournalMenuController@contactjournal'));
		Route::get('editorialpoliciesjournal',array('uses'=>'JournalMenuController@editorialpoliciesjournal'));
		Route::get('editorialteamjournal',array('uses'=>'JournalMenuController@editorialteamjournal'));
		Route::get('submissionchecklist',array('uses'=>'JournalMenuController@submissionchecklist'));
		Route::get('manuscriptguideline',array('uses'=>'JournalMenuController@manuscriptguideline'));
		Route::get('journalachieve',array('uses'=>'JournalMenuController@journalachieve'));
		
		Route::get('registrationsubmitteddetails/{Id}',array('uses'=>'JournalController@registrationsubmitteddetails'));
		Route::get('registrationviewdetails/{Id}',array('uses'=>'JournalController@registrationviewdetails'));
		Route::get('registrationapproved/{Id}',array('uses'=>'JournalController@registrationapproved'));
		Route::get('downloadmanuscript/{Application_No}',array('uses'=>'JournalController@getDownload'));

		Route::get('journalcoordinatorverification',array('uses'=>'JournalController@journalcoordinatorverification'));
		Route::get('journalclaimapplication/{Application_No}',array('uses'=>'JournalController@journalclaimapplication'));
		Route::get('journalunclaimapplication/{Application_No}',array('uses'=>'JournalController@journalunclaimapplication'));
		Route::get('journalcoordinatorverificationmytable',array('uses'=>'JournalController@journalcoordinatorverificationmytable'));
		Route::post('journalforwardtoeditorial',array('uses'=>'JournalController@journalforwardtoeditorial'));
		Route::get('journalcoordinatordetails/{Application_No}',array('uses'=>'JournalController@journalcoordinatordetails'));
		Route::get('journalcoordinatormytask',array('uses'=>'JournalController@journalcoordinatormytask'));

		Route::post('journalforwardtojcbyauthor',array('uses'=>'JournalController@journalforwardtojcbyauthor'));
		Route::post('journaldetailsendtoauthor',array('uses'=>'JournalController@journaldetailsendtoauthor'));
		Route::post('journalforwardagaintojcbyauthor',array('uses'=>'JournalController@journalforwardagaintojcbyauthor'));
		Route::post('journalforwardtoauhtor',array('uses'=>'JournalController@journalforwardtoauhtor'));
		Route::post('journalforwardagaintoauhtor',array('uses'=>'JournalController@journalforwardagaintoauhtor'));
		Route::post('journalforwardtoeditorteam',array('uses'=>'JournalController@journalforwardtoeditorteam'));
		Route::post('journalagainforwardtoeditorteam',array('uses'=>'JournalController@journalagainforwardtoeditorteam'));
		Route::post('journalforwardtojcbyeditorial',array('uses'=>'JournalController@journalforwardtojcbyeditorial'));
		Route::post('journalforwardtojcbyeditorteam',array('uses'=>'JournalController@journalforwardtojcbyeditorteam'));
		Route::post('journalagainforwardtojcbyeditorteam',array('uses'=>'JournalController@journalagainforwardtojcbyeditorteam'));
		Route::post('journalforwardtojcafterrejecting',array('uses'=>'JournalController@journalforwardtojcafterrejecting'));

		Route::get('journalcoordinatordetailstoselectreviewer/{Application_No}',array('uses'=>'JournalController@journalcoordinatordetailstoselectreviewer'));
		Route::get('journalcoordinatordetailsforpublication/{Application_No}',array('uses'=>'JournalController@journalcoordinatordetailsforpublication'));
		Route::get('journalcoordinatorreviseforwardtoeditorteam/{Application_No}',array('uses'=>'JournalController@journalcoordinatorreviseforwardtoeditorteam'));
		Route::get('journalcoordinatorreviseforwardagaintoeditorteam/{Application_No}',array('uses'=>'JournalController@journalcoordinatorreviseforwardagaintoeditorteam'));
		Route::get('journaleditorialreviseforwardtojc/{Application_No}',array('uses'=>'JournalController@journaleditorialreviseforwardtojc'));
		Route::get('journaleditorialreviseagainforwardtojc/{Application_No}',array('uses'=>'JournalController@journaleditorialreviseagainforwardtojc'));
		Route::get('journalcoordinatordetailstosendbacktoauthor/{Application_No}',array('uses'=>'JournalController@journalcoordinatordetailstosendbacktoauthor'));
		Route::get('journalcoordinatorreviseforwardfromeditorteam/{Application_No}',array('uses'=>'JournalController@journalcoordinatorreviseforwardfromeditorteam'));
		Route::get('journalcoordinatorreviseagainforwardfromeditorteam/{Application_No}',array('uses'=>'JournalController@journalcoordinatorreviseagainforwardfromeditorteam'));
		Route::get('journalcoordinatorreviseforwardfromeditorteam/{Application_No}',array('uses'=>'JournalController@journalcoordinatorreviseforwardfromeditorteam'));
		Route::get('journalcoordinatorreviserejectedfromeditorteam/{Application_No}',array('uses'=>'JournalController@journalcoordinatorreviserejectedfromeditorteam'));

		Route::post('journalforwardtoreviewer',array('uses'=>'JournalController@journalforwardtoreviewer'));
		Route::get('journaleditorialdetails/{Application_No}',array('uses'=>'JournalController@journaleditorialdetails'));
		Route::get('journaleditorialverification',array('uses'=>'JournalController@journaleditorialverification'));
		Route::get('journalclaimapplicationbyeditorial/{Application_No}',array('uses'=>'JournalController@journalclaimapplicationbyeditorial'));
		Route::get('journalunclaimapplicationbyeditorial/{Application_No}',array('uses'=>'JournalController@journalunclaimapplicationbyeditorial'));
		Route::get('journaleditorialmytask',array('uses'=>'JournalController@journaleditorialmytask'));

		Route::get('journalreviewerverification',array('uses'=>'JournalController@journalreviewerverification'));
		Route::get('journalreviewerdetails/{Application_No}',array('uses'=>'JournalController@journalreviewerdetails'));
		Route::post('reviewerforwardtojc',array('uses'=>'JournalController@reviewerforwardtojc'));
		Route::get('journalclaimapplicationbyreviewer/{Application_No}',array('uses'=>'JournalController@journalclaimapplicationbyreviewer'));
		Route::get('journalunclaimapplicationbyreviewer/{Application_No}',array('uses'=>'JournalController@journalunclaimapplicationbyreviewer'));
		
		Route::get('journalchiefverification',array('uses'=>'JournalController@journalchiefverification'));
		Route::get('journalchiefdetails/{Application_No}',array('uses'=>'JournalController@journalchiefdetails'));
		Route::get('journalchiefeditordetails/{Application_No}',array('uses'=>'JournalController@journalchiefeditordetails'));
		Route::get('journalclaimapplicationbychief/{Application_No}',array('uses'=>'JournalController@journalclaimapplicationbychief'));
		Route::get('journalunclaimapplicationbychief/{Application_No}',array('uses'=>'JournalController@journalunclaimapplicationbychief'));
		Route::post('journalforwardtojc',array('uses'=>'JournalController@journalforwardtojc'));
		Route::post('journalforwardchieftojc',array('uses'=>'JournalController@journalforwardchieftojc'));
		Route::get('journalchiefmytask',array('uses'=>'JournalController@journalchiefmytask'));

		Route::post('journalforwardtocf',array('uses'=>'JournalController@journalforwardtocf'));
		Route::post('journalforwardtochief',array('uses'=>'JournalController@journalforwardtochief'));

		Route::get('journallistfromchief',array('uses'=>'JournalController@journallistfromchief'));
		Route::get('journallistfromreviewer',array('uses'=>'JournalController@journallistfromreviewer'));
		Route::get('journalreviewerforwardtojc/{Application_No}',array('uses'=>'JournalController@journalreviewerforwardtojc'));
		Route::get('journalrejectedbychief/{Application_No}',array('uses'=>'JournalController@journalrejectedbychief'));
		
		Route::post('journaluserlogin',array('uses'=>'JournalController@journallogin'));
		Route::get('journalregistration',array('uses'=>'JournalController@register'));
		Route::post('journalregister',array('uses'=>'JournalController@journalregister'));	

		Route::get('logout',array('uses'=>'JournalController@logout'));
		
		Route::get('journalsuccess',array('uses'=>'JournalController@success'));
		Route::get('journallistjc',array('uses'=>'JournalController@journallistjc'));

		Route::get('localization/{lang}',array('uses'=>'JournalController@localization'));

		Route::get('journalpublishview/{Application_No}',array('uses'=>'JournalMenuController@journalpublishview'));

		Route::get('journalsendbacktoeditorial/{Application_No}',array('uses'=>'JournalController@journalsendbacktoeditorial'));
		Route::post('journalsendbacktoauthor',array('uses'=>'JournalController@journalsendbacktoauthor'));
		Route::post('journalreturnbacktojcfromeditorialteam',array('uses'=>'JournalController@journalreturnbacktojcfromeditorialteam'));
		Route::post('journalapprovedbyjc',array('uses'=>'JournalController@journalapprovedbyjc'));

		Route::get('forgotpassword',array('uses'=>'JournalController@getForgotPassword'));
		Route::post('jresetandsendpassword',array('uses' => 'JournalController@resetAndSendPassword'));
		Route::get('journaltemplate',array('uses'=>'JournalController@journaltemplate'));

		Route::get('reviewerchecklist',array('uses'=>'JournalChecklistController@index'));
		Route::get('editgroupchecklist/{Id}',array('uses'=>'JournalChecklistController@editgroupchecklist'));
		Route::post('uploadeditgroupchecklist',array('uses'=>'JournalChecklistController@uploadeditgroupchecklist'));
		Route::get('addgroupchecklist',array('uses'=>'JournalChecklistController@addgroupchecklist'));
		Route::get('deletegroupchecklist/{Id}',array('uses'=>'JournalChecklistController@deletegroupchecklist'));
		Route::post('journaladdgroupchecklistsave',array('uses'=>'JournalChecklistController@journaladdgroupchecklistsave'));

		Route::get('journalreviewermainchecklist',array('uses'=>'JournalChecklistController@journalreviewermainchecklist'));
		Route::get('editchecklist/{Id}',array('uses'=>'JournalChecklistController@editchecklist'));
		Route::post('uploadeditchecklist',array('uses'=>'JournalChecklistController@uploadeditchecklist'));
		Route::get('addchecklist',array('uses'=>'JournalChecklistController@addchecklist'));
		Route::get('deletechecklist/{Id}',array('uses'=>'JournalChecklistController@deletechecklist'));
		Route::post('journaladdchecklistsave',array('uses'=>'JournalChecklistController@journaladdchecklistsave'));

		Route::get('journalreviewersubchecklist',array('uses'=>'JournalChecklistController@journalreviewersubchecklist'));
		Route::get('editsubchecklist/{Id}',array('uses'=>'JournalChecklistController@editsubchecklist'));
		Route::post('uploadeditsubchecklist',array('uses'=>'JournalChecklistController@uploadeditsubchecklist'));
		Route::get('addsubchecklist',array('uses'=>'JournalChecklistController@addsubchecklist'));
		Route::get('deletesubchecklist/{Id}',array('uses'=>'JournalChecklistController@deletesubchecklist'));
		Route::post('journaladdsubchecklistsave',array('uses'=>'JournalChecklistController@journaladdsubchecklistsave'));
		Route::post('checklistview',array('uses'=>'JournalChecklistController@checklistview'));

		Route::post('journaltemplateupload',array('uses'=>'JournalController@journaltemplateupload'));
		Route::post('journalReviewerChecklistUpload',array('uses'=>'JournalController@journalReviewerChecklistUpload'));
		
		Route::get('journallandingpage/{Id}',array('uses'=>'JournalMenuController@journallandingpage'));
		Route::get('landingpage',array('uses'=>'JournalMenuController@landingpage'));
		Route::post('journalapplicationno',array('uses'=>'JournalMenuController@journalapplicationno'));
		Route::post('editlandingremarks',array('uses'=>'JournalMenuController@editlandingremarks'));

		/*Route for journal end*/



		Route::get('contactus',array('as'=>'contactuspage','uses'=>'WebsiteContactUsController@contactUs'));
		Route::post('contactusmail',array('as'=>'contactusmailpage','before'=>'captchacheck','uses'=>'WebsiteContactUsController@contactUsMail'));

		Route::get('feedback',array('as'=>'feedbackpage','uses'=>'PostFeedbackWebsite@feedback'));
		Route::post('postfeedback',array('as'=>'postfeedback','uses'=>'PostFeedbackWebsite@postFeedback'));	//Route to Post Feedback

		Route::get('tenderlist',array('uses'=>'WebsiteTender@listOfTenders'));	//Route to get the list of tenders
		Route::get('webtenderdetails/{id}',array('as'=>'webtenderdetailspage','uses'=>'WebsiteTender@tenderDetails'));	//Route to view the complete details of a particular tender
		Route::post('webtenderdownload',array('as'=>'webtenderdownload','before'=>'captchacheck','uses'=>'WebsiteTender@downloadTender'));	//Route  to Download Tender Attachments
		Route::get('getdownload/{id}',array('as'=>'getdownloadpage','uses'=>'WebsiteTender@getDownload'));

		Route::get('photogallery',array('as'=>'photogallerypage','uses'=>'PhotoGalleryController@photoGallery'));
		Route::get('photoalbum/{id?}',array('as'=>'photoalbum','uses'=>'PhotoGalleryController@photoAlbum'));

		Route::get('apt','ListAndReportController@getApt');
		Route::get('listofcontractors',array('as'=>'listofcontractorspage','uses'=>'ListAndReportController@listOfContractors'));
		Route::get('listofspecializedfirm',array('as'=>'listofcontractorspage','uses'=>'ListAndReportController@listOfspecializedfirm'));
		Route::get('listofarbitrators',array('as'=>'listofarbitrators','uses'=>'ListAndReportController@listOfArbitrators'));
		
		Route::get('listofcertifiedbuilders',array('as'=>'listofcertifiedbuilders','uses'=>'ListAndReportController@listOfCertifiedBuilders'));

		Route::get('listofcontractorsrevoked',array('uses'=>'ListAndReportController@listOfContractorsRevoked'));
		Route::get('viewcontractordetails/{id}',array('as'=>'contractordetails','uses'=>'ListAndReportController@viewContractorDetails'));
		
		Route::get('listofarchitects',array('as'=>'listofarchitectspage','uses'=>'ListAndReportController@listOfArchitects'));
		Route::get('viewarchitectdetails/{id}',array('as'=>'architectdetails','uses'=>'ListAndReportController@viewArchitectDetails'));
		
		Route::get('listofsurvey',array('as'=>'listofarchitectspage','uses'=>'ListAndReportController@listOfSurvey'));
		
		Route::get('listofconsultants',array('as'=>'listofconsultantspage','uses'=>'ListAndReportController@listOfConsultants'));
		Route::get('viewconsultantdetails/{id}',array('as'=>'consultantdetails','uses'=>'ListAndReportController@viewConsultantDetails'));
		
		Route::get('listofengineers',array('as'=>'listofenginnerspage','uses'=>'ListAndReportController@listOfEngineers'));
		Route::get('viewengineerdetails/{id}',array('as'=>'engineerdetails','uses'=>'ListAndReportController@viewEngineerDetails'));
		
		Route::get('listofspecializedtrades',array('as'=>'listofspecializedtradespage','uses'=>'ListAndReportController@specializedTradeList'));
		Route::get('viewspecializedtradesdetails/{id}',array('as'=>'specializedtradesdetails','uses'=>'ListAndReportController@specializedTradeDetails'));

		Route::get('downloads',array('as'=>'downloadspage','uses'=>'WebsiteDownloadsController@downloads'));
		Route::get('optionsdownloads/{categoryid}',array('uses'=>'WebsiteDownloadsController@optionDownloads'));

		Route::get('circulardetails/{id}',array('as'=>'circulardetailspage','uses'=>'WebsiteAddNewCircular@circularDetails'));
		Route::get('listofcirculars/news',array('as'=>'listofcircularsnews','uses'=>'WebsiteAddNewCircular@listOfNews'));
		Route::get('listofcirculars/notifications',array('as'=>'listofcircularsnews','uses'=>'WebsiteAddNewCircular@listOfNotifications'));
		Route::get('viewarchives',array('as'=>'viewarchivespage','uses'=>'WebsiteAddNewCircular@viewArchives'));
		Route::get('viewarchivedetails/{id}',array('as'=>'viewarchivespage','uses'=>'WebsiteAddNewCircular@viewArchiveDetails'));
		Route::post('changecirculardisplayorder',array('as'=>'changecirculardisplayorder','uses'=>'WebsiteAddNewCircular@postChangeDisplayOrder'));
		Route::post('deletecircularimage',array('as'=>'deletecircularimage','uses'=>'WebsiteAddNewCircular@deleteImage'));
		Route::post('deletecircularfile',array('as'=>'deletecircularfile','uses'=>'WebsiteAddNewCircular@deleteFile'));

		Route::get('cdbsecretariat',array('as'=>'cdbsecretariatpage','uses'=>'CDBSecretariatController@cdbSecretariat'));
		Route::get('boardmeeting',array('as'=>'boardmeeting','uses'=>'WebsiteController@boardMeeting'));
		Route::get('boardmembers',array('as'=>'boardmemberspage','uses'=>'WebsiteController@boardMembers'));
		Route::get('pagedetails/{id}',array('as'=>'pagedetailspage','uses'=>'WebMenuManagementController@pageDetails'));	//Route to delete sub menu item

		Route::get('trackapplication',array('as'=>'trackapplicationpage','uses'=>'TrackApplicationController@trackApplication'));

		Route::get('alladvertisements','WebAddAdvertisement@allAdvertisements');
		Route::get('advertisementdetails/{id}',array('as'=>'advertisementdetailspage','uses'=>'WebAddAdvertisement@advertisementDetails'));
		Route::post('changeadvertisementdisplayorder',array('as'=>'changeadvertisementdisplayorder','uses'=>'WebAddAdvertisement@postChangeDisplayOrder'));

		Route::get('listoftrainings',array('as'=>'listoftrainingspage','uses'=>'WebTrainingController@listOfTrainings'));
		Route::get('viewtrainingdetails/{id}',array('as'=>'viewtrainingdetailspage','uses'=>'WebTrainingController@viewTrainingDetails'));
		Route::post('registrationfortraining',array('uses'=>'WebTrainingController@registrationForTraining'));

		Route::get('eventcalendar',array('as'=>'eventcalendar','uses'=>'WebsiteController@eventCalendar'));

		Route::get('editwebreg/{type}','WebsiteRegistrationPages@getIndex');
		Route::post('saveregistrationpage','WebsiteRegistrationPages@postSave');

		/*Routes For System Administrator*/
		Route::get('arbitratorlist','WebsiteArbitrator@getIndex');
		Route::post('savearbitrators','WebsiteArbitrator@postSave');
		Route::get('editbanner',array('as'=>'editbanner','uses'=>'WebsiteController@editBanner'));
		Route::post('updatebanner',array('as'=>'updatebanner','uses'=>'UpdateBannerWebsite@updateBanner'));	//Route to Edit Page Banner
		Route::get('addtrainingform/{id?}',array('as'=>'addtrainingformpage','uses'=>'WebTrainingController@addTrainingForm'));
		Route::post('addtrainingdetails',array('as'=>'addtrainingdetailspage','uses'=>'WebTrainingController@addTrainingDetails'));
		Route::get('viewedittrainings',array('as'=>'viewedittrainingspage','uses'=>'WebTrainingController@viewTrainings'));
		Route::get('edittrainingdetails/{id}',array('as'=>'edittrainingdetailspage','uses'=>'WebTrainingController@editTrainingDetails'));
		Route::post('updatetrainingdetails',array('as'=>'updatetrainingdetailspage','uses'=>'WebTrainingController@updateTrainingDetails'));
		Route::get('viewadmintrainingdetails/{id}',array('as'=>'viewadmintrainingdetailspage','uses'=>'WebTrainingController@viewAdminTrainingDetails'));
		Route::get('registeredfortraining/{id}',array('as'=>'registeredfortrainingpage','uses'=>'WebTrainingController@registeredForTraining'));
		Route::get('webvisitorreport',array('as'=>'webvisitorreportpage','uses'=>'WebsiteVisitorReport@webVisitorReport'));
		Route::get('addadvertisements/{id?}',array('as'=>'addadvertisementspage','uses'=>'WebAddAdvertisement@addAdvertisements'));
		Route::post('addadvertisementdetails',array('uses'=>'WebAddAdvertisement@addAdvertisementDetails'));
		Route::get('editadvertisements',array('uses'=>'WebAddAdvertisement@advertisementList'));
		Route::post('deleteadvertisement',array('uses'=>'WebAddAdvertisement@deleteAdvertisement'));
		Route::get('listofcdbsecretariat',array('as'=>'listofcdbsecretariatpage','uses'=>'CDBSecretariatController@listOfCDBSecretariat'));
		Route::get('addcdbsecretariat/{id?}',array('as'=>'addcdbsecretariatpage','uses'=>'CDBSecretariatController@addCDBSecretariat'));
		Route::post('addsecretariatdetails',array('as'=>'addsecretariatdetailspage','uses'=>'CDBSecretariatController@addSecretariatDetails'));
		Route::get('cdbsecretariatmoveup/{id}',array('uses'=>'CDBSecretariatController@cdbSecretatiatMoveUp'));
		Route::get('cdbsecretariatmovedown/{id}',array('uses'=>'CDBSecretariatController@cdbSecretatiatMoveDown'));
		Route::get('editcdbsecretariat/{id}',array('as'=>'editcdbsecretariatpage','uses'=>'CDBSecretariatController@editCDBSecretariat'));
		Route::post('updatecdbsecretariat',array('as'=>'updatecdbsecretariatpage','uses'=>'CDBSecretariatController@updateCDBSecretariat'));
		Route::get('deletecdbsecretariat/{id}',array('as'=>'deletecdbsecretariatpage','uses'=>'CDBSecretariatController@deleteCDBSecretariat'));
		Route::post('addcdbdesignation',array('as'=>'addcdbdesignationpage','uses'=>'CDBSecretariatController@addCDBDesignation'));
		Route::get('deletecdbdesignation/{id}',array('as'=>'deletecdbdesignation','uses'=>'CDBSecretariatController@deleteCDBDesignation'));
		Route::post('addcdbdepartment',array('as'=>'addcdbdepartmentpage','uses'=>'CDBSecretariatController@addCDBDDepartment'));
		Route::get('deletecdbdepartment/{id}',array('as'=>'deletecdbdepartment','uses'=>'CDBSecretariatController@deleteCDBDDepartment'));
		Route::post('addcdbdivision',array('as'=>'addcdbdivisionpage','uses'=>'CDBSecretariatController@addCDBDivision'));
		Route::get('deletecdbdivision/{id}',array('as'=>'deletecdbdivision','uses'=>'CDBSecretariatController@deleteDBDivision'));
		Route::get('managesubmenu',array('as'=>'managesubmenupage','uses'=>'WebMenuManagementController@manageSubMenu'));
		Route::post('managesubmenudetails',array('as'=>'managesubmenupage','uses'=>'WebMenuManagementController@manageSubMenuDetails'));
		Route::get('editsubmenu/{id}',array('as'=>'editpage','uses'=>'WebMenuManagementController@editSubMenu'));
		Route::post('updatesubmenudetails',array('as'=>'updatepage','uses'=>'WebMenuManagementController@updateSubMenuDetails'));	//Route to Edit Page Details
		Route::get('submenuitemmoveup/{id}',array('uses'=>'WebMenuManagementController@subMenuItemMoveUp'));	//Route to move sub menu item up
		Route::get('submenuitemmovedown/{id}',array('uses'=>'WebMenuManagementController@subMenuItemMoveDown'));	//Route to move sub menu item down
		Route::get('submenuitemdelete/{id}',array('uses'=>'WebMenuManagementController@subMenuItemDelete'));	//Route to delete sub menu item
		Route::get('submenuactivate/{id}',array('uses'=>'WebMenuManagementController@subMenuActivate'));	//Route to delete sub menu item
		Route::get('submenudeactivate/{id}',array('uses'=>'WebMenuManagementController@subMenuDectivate'));	//Route to delete sub menu item
		Route::get('showinfooter/{id}',array('uses'=>'WebMenuManagementController@showInFooter'));	//Route to Show in Footer
		Route::get('removefromfooter/{id}',array('uses'=>'WebMenuManagementController@removeFromFooter'));	//Route to Show in Footer
		Route::get('footeritemmoveup/{id}',array('uses'=>'WebMenuManagementController@footerItemMoveUp'));	//Route to Show in Footer
		Route::get('footeritemmovedown/{id}',array('uses'=>'WebMenuManagementController@footerItemMoveDown'));	//Route to Show in Footer

		Route::get('managemenus',array('as'=>'managemainmenupage','uses'=>'WebMenuManagementController@manageMenus'));
		Route::post('managemainmenudetails',array('as'=>'managemainmenudetailspage','uses'=>'WebMenuManagementController@manageMainMenuDetails'));
		Route::get('menuitemmoveup/{id}',array('uses'=>'WebMenuManagementController@menuItemMoveUp'));	//Route to move main menu item up
		Route::get('menuitemmovedown/{id}',array('uses'=>'WebMenuManagementController@menuItemMoveDown'));	//Route to move main menu item down
		Route::get('menuitemdelete/{id}',array('uses'=>'WebMenuManagementController@menuItemDelete'));	//Route to delete main menu item
		Route::get('mainmenuactivate/{id}',array('uses'=>'WebMenuManagementController@mainMenuActivate'));	//Route to delete sub menu item
		Route::get('mainmenudeactivate/{id}',array('uses'=>'WebMenuManagementController@mainMenuDectivate'));	//Route to delete sub menu item

		Route::get('addnewcircular/{id?}',array('as'=>'addnewcircularpage','uses'=>'WebsiteAddNewCircular@addNewCircular'));
		Route::post('addnewcirculardata',array('as'=>'addnewcirculardatapage','uses'=>'WebsiteAddNewCircular@addNewCircularData'));
		
		Route::get('archivecircular',array('as'=>'archivecircular','uses'=>'WebsiteAddNewCircular@archiveCircular'));
		Route::get('archivecirculardetail/{id}',array('as'=>'archivecirculardetail','uses'=>'WebsiteAddNewCircular@archiveCircularDetail'));
		Route::get('showhidecircular/{id}','WebsiteAddNewCircular@showHideCircular');

		Route::get('adddownloads/{downloadid?}',array('as'=>'adddownloadspage','uses'=>'WebsiteDownloadsController@addDownloads'));
		Route::post('adddownloaddata',array('as'=>'adddownloaddatapage','uses'=>'WebsiteDownloadsController@addDownloadData'));
		Route::post('adddownloadcategory',array('as'=>'adddownloadcategorypage','uses'=>'WebsiteDownloadsController@addNewCategory'));

		Route::get('editdownload','WebsiteDownloadsController@editDownloads');
		Route::get('deletedocument','WebsiteDownloadsController@deletedocument');

		Route::get('addphotoalbum/{id?}','PhotoGalleryController@addPhotoAlbum');
		Route::post('savephotoalbum','PhotoGalleryController@saveAlbum');
		Route::get('editphotogallery/{id?}',array('as'=>'editphotogallery','uses'=>'PhotoGalleryController@editPhotoGallery'));	//Route to edit photo gallery
		Route::post('updatephotogallery',array('as'=>'updatephotogallery','uses'=>'PhotoGalleryController@updateGallery'));	//Route to update photo gallery
		Route::get('deletephoto/{id}',array('as'=>'deletephoto','uses'=>'PhotoGalleryController@deletePhoto'));

		Route::post('addnewalbum',array('as'=>'addnewalbum','uses'=>'PhotoGalleryController@addNewAlbum'));
		Route::get('deletealbum/{id?}',array('as'=>'deletealbum','uses'=>'PhotoGalleryController@deleteAlbum'));


		Route::get('feedbacklist',array('as'=>'feedbacklist','uses'=>'PostFeedbackWebsite@feedbackList'));
		Route::get('deletefeedback/{id}',array('as'=>'deletefeedback','uses'=>'PostFeedbackWebsite@deleteFeedback'));
		Route::get('feedbackdetails/{id}',array('as'=>'feedbackdetailspage','uses'=>'PostFeedbackWebsite@feedbackDetails'));
		Route::get('showfeebackonwebsite/{id}',array('as'=>'showfeebackonwebsitepage','uses'=>'PostFeedbackWebsite@showFeebackOnWebsite'));

		Route::get('togglemarquee',array('as'=>'togglemarquee','uses'=>'WebsiteMarquee@getIndex'));
		Route::post('savemarqueesetting',array('as'=>'savemarqueesetting','uses'=>'WebsiteMarquee@postSave'));

		Route::get('togglebottom','WebsiteController@getToggleBottom');
		Route::post('savetoggle','WebsiteController@postSaveToggleBottom');


		Route::get('faq',array('as'=>'faqpage','uses'=>'PostFaqWebsite@faq'));
		Route::get('addfaqs',array('uses'=>'PostFaqWebsite@addFAQs'));
		Route::post('postfaq',array('as'=>'postfaqpage','uses'=>'PostFaqWebsite@postQuestion'));//Route to post Frequently Asked Questions
		Route::get('faqquestionlist',array('as'=>'faqquestionlistpage','uses'=>'PostFaqWebsite@faqQuestionList'));

		Route::get('movefaqquestionup/{id}/{order}','PostFaqWebsite@moveFaqUp');
		Route::get('movefaqquestiondown/{id}/{order}','PostFaqWebsite@moveFaqDown');

		Route::get('deletequestion/{id}',array('as'=>'deletequestion','uses'=>'PostFaqWebsite@deleteQuestion'));
		Route::get('faqquestiondetails/{id}',array('as'=>'faqquestiondetailspage','uses'=>'PostFaqWebsite@faqQuestionDetails'));
		Route::post('postfaqanswer',array('as'=>'postfaqanswerpage','uses'=>'PostFaqWebsite@postFaqAnswer'));

		Route::get('viewforum', array('uses' => 'CommentController@getViewForums'));
		Route::get('detailforum/{id}', array('uses' => 'CommentController@detailforums'));
		Route::post('postcomments', array('uses' => 'CommentController@SaveComment'));
		Route::post("arbitratorauth","ArbitrationForum@postAuth");
		Route::get("arbitratorlogout","ArbitrationForum@getLogout");

		//ARBITRATION FORUM
		Route::get('arbitrationforum','ArbitrationForum@getIndex');
		Route::get('forumforarbitrators','ArbitrationForum@getForum');
		Route::get('arbforumnewcategory','ArbitrationForum@getNewCategory');
		Route::post('savearbforumcategory','ArbitrationForum@saveCategory');
		Route::get('arbforumnewtopic','ArbitrationForum@getNewTopic');
		Route::post('savearbforumtopic','ArbitrationForum@saveTopic');
		Route::get('arbforumcategoryview/{id}','ArbitrationForum@viewCategory');
		Route::get('arbforumtopicview/{id}','ArbitrationForum@viewTopic');
		Route::post('savearbforumpost','ArbitrationForum@savePost');
		Route::post('changearbpassword','ArbitrationForum@changeArbPassword');
		Route::get('fetchforumcategories','ArbitrationForum@fetchForumCategories');
		//END ARBITRATION FORUM

		Route::get('newvideo/{id?}','VideoController@getNew');
		Route::get('deletevideo/{id?}','VideoController@getDelete');
		Route::get('showvideo/{id?}','VideoController@getShow');
		Route::post('savevideo','VideoController@postSave');
		Route::get('videolist','VideoController@getList');
		Route::get('allvideos','WebsiteVideo@getList');

		//ADDED NEW on 1st April 2017
		Route::get('sitemap','WebsiteController@getSiteMap');
		//END NEW

		Route::post('deletearbitrator','WebsiteArbitrator@postDelete');

	});

/*------------------------Start of Routes for new etool-------------------------------------------*/

	Route::group(array('prefix'=>'newEtl'),function(){
		Route::get('mydashboard',array('uses'=>'NewetoolMyEtool@dashboard'));
		Route::get('reports',array('uses'=>'NewetoolEtoolReports@getIndex'));
		Route::post('deleteevaldetail',array('uses'=>'NewetoolEvaluationEtool@postDelete')); //Route to delete equipment or hr from db (from add contractor)
		Route::post('etooluploadtender',array('uses'=>'NewetoolUploadTenderEtool@postSaveTender')); //Route to upload tender
		Route::post('addNonResponsive',array('uses'=>'NewetoolEvaluationEtool@addNonResponsive')); //Route to upload tender
		
		Route::post('pushToNonResponsive',array('uses'=>'NewetoolEvaluationEtool@pushToNonResponsive')); //Route to upload tender
		Route::post('pushToResponsive',array('uses'=>'NewetoolEvaluationEtool@pushToResponsive')); //Route to upload tender
				


		Route::post('cancelSmallTender',array('uses'=>'NewetoolEvaluationEtool@cancelSmallTender')); //Route to upload tender
		Route::post('deleteNonResponsive',array('uses'=>'NewetoolEvaluationEtool@deleteNonResponsive')); //Route to upload tender
		Route::post('cancelTender',array('uses'=>'NewetoolEvaluationEtool@cancelTender')); //Route to upload tender
		Route::post('savecriteria',array('uses'=>'NewetoolCriteriaEtool@postSaveCriteria')); //Route to set criteria for work
		Route::post('etlsaveaddcontractor',array('uses'=>'NewetoolEvaluationEtool@postSaveAddContractor')); //Route to save add contractor
		Route::post('awardcontractor',array('uses'=>'NewetoolEvaluationEtool@postAwardContractor')); //Route to save add contractor
		Route::post('etlworkcompletion',array('uses'=>'NewetoolWorkCompletionFormEtool@postWorkCompletion')); //Route to save add contractor
		Route::post('etlsavecommittee',array('uses'=>'NewetoolEvaluationEtool@saveCommittee')); //Route to save add contractor
		Route::post('etlPostSeekClarification',array('uses'=>'NewetoolEvaluationEtool@etlPostSeekClarification')); //Route to upload tender
		Route::get('seekclarification/{tenderId}/{cdbNo}/{contractorId}',array('uses'=>'NewetoolMyEtool@seekclarification')); //Route to seek clarification
		Route::get('viewseekclarification/{id?}/{contractorId?}',array('uses'=>'NewetoolMyEtool@viewseekclarification')); //Route to seek clarification
		Route::get('respondseekclarification/{id?}',array('uses'=>'NewetoolMyEtool@respondseekclarification')); //Route to seek clarification
	

		Route::get('validateEgpTenderId/{egpTenderId?}',array('as'=>'NewetoolvalidateEgpTenderId','uses'=>'NewetoolUploadTenderEtool@validateEgpTenderId'));


		/*Ajax Routes*/
		Route::post('deletefile',array('uses'=>'NewetoolUploadTenderEtool@postDeleteFile'));
		Route::post('deletefromdb',array('uses'=>'NewetoolUploadTenderEtool@postDelete'));
		Route::post('fetchcontractor',array('uses'=>'NewetoolEvaluationEtool@postFetchContractor'));
		Route::post('fetchcontractoroncdbno',array('uses'=>'NewetoolEvaluationEtool@postFetchContractorOnCDBNo'));
		Route::post('fetchspecializedtradeoncdbno',array('uses'=>'NewetoolEvaluationEtool@postFetchSpecializedtradeOnCDBNo'));
		/*End of Ajax Routes*/
		Route::get('uploadedtenderlistetool',array('as'=>'Newetooluploadedtenderlistetool','uses'=>'NewetoolUploadTenderEtool@uploadedList'));
		Route::get('uploadtenderetool/{tenderid?}',array('as'=>'Newetooluploadtenderetool','uses'=>'NewetoolUploadTenderEtool@index'));
		Route::get('workidetool',array('as'=>'Newetoolworkidetool','uses'=>'NewetoolWorkIdEtool@index'));
		Route::get('setcriteriaetool/{tenderid?}',array('as'=>'Newetoolsetcriteriaetool','uses'=>'NewetoolCriteriaEtool@index'));
		Route::get('evaluationetool',array('as'=>'Newetoolevaluationetool','uses'=>'NewetoolEvaluationEtool@index'));
		Route::get('evaluationcommiteeetool/{tenderid?}',array('as'=>'Newetoolevaluationcommiteeetool','uses'=>'NewetoolEvaluationEtool@evaluationCommittee'));
		Route::get('awardingcommiteeetool/{tenderid?}',array('as'=>'Newetoolawardingcommiteeetool','uses'=>'NewetoolEvaluationEtool@awardingCommittee'));
		Route::get('workevaluationdetails/{tenderid?}',array('as'=>'Newetoolworkevaluationdetails','uses'=>'NewetoolEvaluationEtool@details'));
		Route::get('workevaluationaddcontractors/{tenderid}/{contractorid?}',array('as'=>'Newetoolworkevaluationaddcontractors','uses'=>'NewetoolEvaluationEtool@addContractors'));
		Route::get('smallWorkevaluationaddcontractors/{tenderid}/{contractorid?}',array('as'=>'Newetoolworkevaluationaddcontractors','uses'=>'NewetoolEvaluationEtool@addSmallContractors'));
		
		Route::get('workevaluationprocessresult/{tenderid}',array('uses'=>'NewetoolEvaluationEtool@processResult'));



		Route::get('workevaluationprocessresultLargetoSmall/{tenderid}',array('uses'=>'NewetoolEvaluationEtool@processResultLargetoSmall'));
		
		

		Route::get('workevaluationsmallprocessresult/{tenderid}',array('uses'=>'NewetoolEvaluationEtool@processResultSmall'));
		Route::get('workevaluationresetresult/{tenderid}',array('uses'=>'NewetoolEvaluationEtool@resetResult'));
		Route::get('workevaluationsmallresetresult/{tenderid}',array('uses'=>'NewetoolEvaluationEtool@resetResultSmall'));
		Route::get('workevaluationresult/{tenderid}',array('uses'=>'NewetoolEvaluationEtool@viewResult'));
		Route::get('workevaluationpointdetails/{tenderid}/{contractorid}',array('as'=>'Newetoolworkevaluationpointdetails','uses'=>'NewetoolEvaluationEtool@pointDetails'));
		Route::get('workevaluationdetailssmallcontractors/{tenderid?}/{contractorid?}',array('as'=>'Newetoolworkevaluationdetailssmallcontractors','uses'=>'NewetoolEvaluationEtool@detailsSmall'));
		Route::get('listofworksetool',array('as'=>'Newetoollistofworksetool','uses'=>'NewetoolWorkCompletionFormEtool@listOfWorks'));
		Route::get('workcompletionformetool/{tenderid?}',array('as'=>'Newetoolworkcompletionformetool','uses'=>'NewetoolWorkCompletionFormEtool@index'));
		Route::get('etoolresultreport/{tenderid}',array('as'=>'Newetooletoolresultreport','uses'=>'NewetoolEvaluationEtool@getResultReport'));
		Route::get('etoolsmallworksreport/{tenderid}',array('as'=>'Newetooletoolsmallworksreport','uses'=>'NewetoolEvaluationEtool@getSmallWorksReport'));
		
		Route::post('savequalifyingscore',array('uses'=>'NewetoolSetQualifyingScore@postSave')); //Route to save add contractor
		Route::get('qualifyingscore',array('uses'=>'NewetoolSetQualifyingScore@getIndex'));

		Route::get('bidevaluationparameters',array('uses'=>'NewetoolBidEvaluationParameters@getIndex'));
		Route::post('savebidevaluationparameters',array('uses'=>'NewetoolBidEvaluationParameters@postSave'));
	Route::get('blacklistedcontractor',array('uses'=>'NewetoolEtoolController@postBlackListedContractor'));
		Route::post('blacklistedcontractor',array('uses'=>'NewetoolEtoolController@postBlackListedContractor'));

		Route::get('etoolselectchange',array('uses'=>'NewetoolMyEtool@getSelectChange'));
		Route::get('etoolchangetenderawarded',array('uses'=>'NewetoolMyEtool@getChangeAwarded'));

		Route::post('saveotherremarks',array('uses'=>'NewetoolMyEtool@postSaveOtherRemarks'));

		Route::get("tendersbiddingformuploaded","TendersBiddingFormUploaded@getIndex");

		Route::get('requestreplacerelease','NewetoolEtoolSysReplaceRelease@getRequest');
		Route::post('rrfetchhrdetails','NewetoolEtoolSysReplaceRelease@postFetchHr');
		Route::post('rrfetcheqdetails','NewetoolEtoolSysReplaceRelease@postFetchEq');

		Route::post('saverrrequest','NewetoolEtoolSysReplaceRelease@saveRequest');
	});
	/*------------------------End of Routes for new etool-------------------------------------------*/


	/*------------------------Start of Routes for (cb) certified Builder-------------------------------------------*/

	Route::group(array('prefix' => 'cb'),function(){
		Route::get('mydashboard',array('uses'=>'MyCertifiedBuilder@dashboard'));
		Route::get('biddingform/{tenderid?}',array('uses'=>'CBBiddingForm@biddingReport'));
		Route::get('editbiddingformlist',array('uses'=>'CBBiddingForm@listOfWorks'));
		Route::get('worklist',array('uses'=>'CBBiddingForm@listOfWorks'));
		Route::get('workcompletionform/{bidId}',array('uses'=>'CBBiddingForm@workCompletionForm'));
		Route::get('editcompletedworklist',array('uses'=>'CBBiddingForm@listOfWorks'));

		Route::get('reports',array('uses'=>'CBReports@getIndex'));
		Route::get('certifiedbuilderworkinhand',array('as'=>'cb.certifiedbuilderworkinhand','uses'=>'ReportCertifiedBuilderWorkInHand@getIndex'));
		Route::get('certifiedbuilderhumanresource',array('as'=>'cb.certifiedbuilderhumanresource','uses'=>'ReportCertifiedBuilderHumanResource@getIndex'));
		Route::get('certifiedbuilderequipment',array('as'=>'cb.certifiedbuilderequipment','uses'=>'ReportCertifiedBuilderEquipment@getIndex'));
		Route::get('certifiedbuilderinfo',array('as'=>'cb.certifiedbuilderinfo','uses'=>'ReportCertifiedBuilderInformation@getIndex'));
		Route::get('hrcheck',array('as'=>'cb.hrcheck','uses'=>'ReportHRCheck@getIndex'));
		Route::get('equipmentcheck',array('as'=>'cb.equipmentcheck','uses'=>'ReportEquipmentCheck@getIndex'));
		Route::get('tendersuploaded',array('as'=>'cinet.tendersuploaded','uses'=>'CinetWorksReport@getTenderUploaded'));
		Route::get('biddingformuploaded',array('as'=>'cinet.biddingformuploaded','uses'=>'CinetWorksReport@getBidsUploaded'));
		Route::get('requestreplacerelease','EtoolSysReplaceRelease@getRequest');
	});

	Route::group(array('prefix'=>'cbrpt'),function(){
		Route::get('listofcertifiedbuilder',array('as'=>'cbrpt.listofcertifiedbuilder','uses'=>'ReportListOfCertifiedBuilder@getIndex'));
		Route::get('expiredcertifiedbuilder',array('as'=>'cbrpt.expiredcertifiedbuilder','uses'=>'ReportListOfExpiredCertifiedBuilder@getIndex'));
		Route::get('certifiedbuilderdetails',array('as'=>'cbrpt.certifiedbuilderdetails','uses'=>'ReportCertifiedBuildeDetail@getIndex'));
		Route::get('certifiedbuilderinformation',array('as'=>'cbrpt.certifiedbuilderinformation','uses'=>'ReportCertifiedBuilderInformation@getIndex'));
		Route::get('listofcertifiedbuilderwithserviceavail',array('as'=>'cbrpt.listofcertifiedbuilderwithserviceavail','uses'=>'ReportListOfCertifiedBuilderwithserviceavail@getIndex'));
		Route::get('listofhrregisteredcertifiedbuilders',array('as'=>'cbrpt.listofhrregisteredcertifiedbuilders','uses'=>'ReportListOfHrCertifiedBuilder@getIndex'));
		Route::get('listofequipmentregisteredcertifiedbuilder',array('as'=>'cbrpt.listofequipmentregisteredcertifiedbuilder','uses'=>'ReportListOfEquipmentCertifiedBuilder@getIndex'));
		Route::get('listofcertifiedbuilderbynearingexpiry',array('as'=>'cbrpt.listofcertifiedbuilderbynearingexpiry','uses'=>'ReportListOfCertifiedBuilder@getIndex'));
		Route::get('listofcertifiedbuilderrevoked',array('as'=>'cbrpt.listofcertifiedbuilderrevoked','uses'=>'ReportListOfRevokedCertifiedBuilder@getIndex'));
		Route::get('listofcertifiedbuilderbydzongkhag',array('as'=>'cbrpt.listofcertifiedbuilderbydzongkhag','uses'=>'ReportCertifiedBuilderByDzongkhag@getIndex'));
		Route::get('specializedfirmregistrationdetail',array('as'=>'cbrpt.specializedfirmregistrationdetail','uses'=>'ReportSpecializedfirmRegistrationDetail@getIndex'));
		Route::get('listofcertifiedbuilderbycategory',array('as'=>'cbrpt.listofcertifiedbuilderbycategory','uses'=>'ReportCertifiedBuilderServicewiseSummary@getIndex'));
		Route::get('certifiedbuilderwithcomments',array('as'=>'cbrpt.certifiedbuilderwithcomments', 'uses'=>'CertifiedBuilderComments@getIndex'));
		Route::get('certifiedbuilderwithadverserecords',array('as'=>'cbrpt.certifiedbuilderwithadverserecords', 'uses'=>'CertifiedBuilderAdvRecords@getIndex'));
		Route::get('trackrecords',array('as'=>'cbrpt.trackrecords','uses'=>'ReportSpecializedfirmTrackRecord@getIndex'));
		Route::get('equipmentreport',array('as'=>'cbrpt.equipmentreport','uses'=>'ReportEquipmentGroup@getIndex'));
		Route::get('worksummarybyagency',array('uses'=>'WorkDistribution@getIndex'));

	});


	/*------------------------End of Routes for (cb) certified Builder-------------------------------------------*/



	/*------------------------Droelma Namgyal's Routes for Website End------------------------*/

	Route::group(array('prefix' => 'etoolApi'),function() {
		Route::get('getMethod/{id}',array('uses'=>'ApiController@getMethod'));
		Route::post('uploadTender',array('uses'=>'ApiController@uploadTender'));
		Route::get('sendMail',array('uses'=>'ApiController@sendMail'));

		//array('uses'=>'MyEtool@dashboard')
		//Route::post('uploadTender',array('uses'=>'ApiController@uploadTender'));
		//Route::post('postMethod',array('uses'=>'ApiController@postMethod'));
		Route::get('userValidation/{username}/{password}',array('uses'=>'ApiController@userValidation'));


		
	}); 

