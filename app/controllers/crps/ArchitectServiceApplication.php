<?php
class ArchitectServiceApplication extends CrpsController{
	public function verifyApproveList(){
		$redirectUrl = Request::path();
		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$architectIdAll=Input::get('CrpArchitectIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$architectIdMyTask=Input::get('CrpArchitectIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,C.InitialDate,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType,group_concat(T7.Name separator ',<br /> ') as AppliedService from crparchitect T1 join crparchitectfinal C on C.Id = T1.CrpArchitectId join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,C.InitialDate,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType,group_concat(T7.Name separator ',<br /> ') as AppliedService from crparchitect T1 join crparchitectfinal C on C.Id = T1.CrpArchitectId join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpArchitectId is not null";
		if(Request::path()=="architect/verifyserviceapplicationlist"){
			$pageTitle="Verify Service Application";
			$pageTitleHelper="All the applications listed below are new applications";
			$recordLockReditectUrl="architect/verifyserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		}elseif(Request::path()=="architect/approveserviceapplicationlist"){
			$pageTitle="Approve Service Application";
			$pageTitleHelper="All the applications listed below are verified applications";
			$recordLockReditectUrl="architect/approveserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}elseif(Request::path()=="architect/approveserviceapplicationfeepaymentlist"){
            $query.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
            $queryMyTaskList.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$pageTitle="Approve Fee Payment for Service";
			$pageTitleHelper="All the applications listed below has been approved and awating to receive the payments.";
			$recordLockReditectUrl="architect/approveserviceapplicationfeepaymentlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			App::abort('404');
		}
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$architectIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$architectIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$architectIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$architectIdAll);
			}
			if((bool)$fromDateAll!=NULL){
				$fromDateAll=$this->convertDate($fromDateAll);
				$query.=" and T1.ApplicationDate>=?";
	            array_push($parameters,$fromDateAll);
			}
			if((bool)$toDateAll!=NULL){
				$toDateAll=$this->convertDate($toDateAll);
				$query.=" and T1.ApplicationDate<=?";
	            array_push($parameters,$toDateAll);
			}
			if((bool)$serviceSectorTypeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$architectIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$architectIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateMyTask);
			}
		}
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$architectLists=DB::select($query." group by T1.Id order by ApplicationDate,ReferenceNo,ArchitectName",$parameters);
		$architectMyTaskLists=DB::select($queryMyTaskList." group by T1.Id  order by ApplicationDate,ReferenceNo,ArchitectName",$parametersMyTaskList);
		return View::make('crps.architectserviceapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',$pageTitle)
					->with('recordLockReditectUrl',$recordLockReditectUrl)
					->with('pageTitleHelper',$pageTitleHelper)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('architectIdAll',$architectIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('architectIdMyTask',$architectIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('architectLists',$architectLists)
					->with('architectMyTaskLists',$architectMyTaskLists);
	}
	public function serviceApplicationDetails($architectId,$forReport = false){
		$hasFee=false;
		$hasLateFee=false;
		$applicableFees=array();
		$hasLateFeeAmount=array();
		$serviceApplicationApproved = 0;
		$architectServiceSectorType=ArchitectModel::where('Id',$architectId)->pluck('CmnServiceSectorTypeId');
		$validityYears=CrpServiceFeeStructure::feeStructure(3)->pluck('RegistrationValidity');
		if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT){
			$validityYears=CrpServiceFeeStructure::feeStructure(2)->pluck('RegistrationValidity');
		}
		$appliedServices=ArchitectAppliedServiceModel::appliedService($architectId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ArchitectGovtAmount','T1.ArchitectPvtAmount','T1.ArchitectGovtValidity','T1.ArchitectPvtValidity'));
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				$hasFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
				$hasLateFee=true;
			}
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crparchitect T1 join crparchitectfinal T2 on T1.CrpArchitectId=T2.Id where T1.Id=? LIMIT 1",array($architectId));
		}
		$architectFinalTableId=architectModelArchitectId($architectId);
		$serviceApplicationApprovedForPayment=0;
		if(Route::current()->getUri()=="architect/verifyserviceapplicationprocess/{architectid}"){
			$view="crps.architectverifyserviceapplicationprocess";
			$modelPost="architect/mverifyserviceapplication";
		}elseif(Route::current()->getUri()=="architect/approveserviceapplicationprocess/{architectid}"){
			$view="crps.architectapproveserviceapplicationprocess";
			$modelPost="architect/mapproveserviceapplication";
		}elseif(Route::current()->getUri()=="architect/approveserviceapplicationpaymentprocess/{architectid}"){
			$serviceApplicationApprovedForPayment=1;
			$view="crps.architectserviceapplicationapprovepayment";
			$modelPost="architect/mapprovepaymentserviceapplication";
		}elseif(Route::current()->getUri()=="architect/viewserviceapplicationdetails/{architectid}"){
			$serviceApplicationApprovedForPayment=1;
			$serviceApplicationApproved=1;
			$view="crps.architectserviceapplicationapprovepayment";
			$modelPost="architect/mapprovepaymentserviceapplication";
		}else{
		    if(!$forReport)
			    App::abort('404');
		}
		$architectInformations=ArchitectModel::architect($architectId)->get(array('crparchitect.Id','crparchitect.PaymentReceiptNo', 'crparchitect.PaymentReceiptDate','crparchitect.ARNo','crparchitect.CIDNo','crparchitect.Name','crparchitect.Gewog','crparchitect.Village','crparchitect.Email','crparchitect.MobileNo','crparchitect.EmployerName','crparchitect.EmployerAddress','crparchitect.GraduationYear','crparchitect.NameOfUniversity','crparchitect.RemarksByVerifier','crparchitect.RemarksByApprover','crparchitect.RemarksByPaymentApprover','crparchitect.VerifiedDate','crparchitect.PaymentApprovedDate','crparchitect.RegistrationApprovedDate','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.FullName as Verifier','T9.FullName as Approver','T10.FullName as PaymentApprover',DB::raw('coalesce(crparchitect.WaiveOffLateFee,0) as WaiveOffLateFee'),'crparchitect.NewLateFeeAmount',));
		$architectAttachments=ArchitectAttachmentModel::attachment($architectId)->get(array('Id','DocumentName','DocumentPath'));
		/*------------------------------End of record applied by the applicant----------------------------*/
		$architectInformationsFinal=ArchitectFinalModel::architect($architectFinalTableId)->get(array('crparchitectfinal.ARNo','crparchitectfinal.CIDNo','crparchitectfinal.RegistrationApprovedDate','crparchitectfinal.RegistrationExpiryDate','crparchitectfinal.Name','crparchitectfinal.Gewog','crparchitectfinal.Village','crparchitectfinal.Email','crparchitectfinal.MobileNo','crparchitectfinal.EmployerName','crparchitectfinal.EmployerAddress','crparchitectfinal.GraduationYear','crparchitectfinal.NameOfUniversity','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus'));
		$architectAttachmentsFinal=ArchitectAttachmentFinalModel::attachment($architectFinalTableId)->get(array('Id','DocumentName','DocumentPath'));

		if($forReport){
		    $reportDetails['hasFee'] = $hasFee;
		    $reportDetails['hasLateFee'] = $hasLateFee;
		    $reportDetails['hasLateFeeAmount'] = $hasLateFeeAmount;
		    $reportDetails['architectServiceSectorType'] = $architectServiceSectorType;
		    $reportDetails['appliedServices'] = $appliedServices;
		    $reportDetails['validityYears'] = $validityYears;
		    $reportDetails['architectInformations'] = $architectInformations;
		    $reportDetails['architectInformationsFinal'] = $architectInformationsFinal;
		    return $reportDetails;
        }
		return View::make($view)
					->with('modelPost',$modelPost)
					->with('hasFee',$hasFee)
					->with('hasLateFee',$hasLateFee)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('architectId',$architectId)
					->with('architectServiceSectorType',$architectServiceSectorType)
					->with('appliedServices',$appliedServices)
					->with('validityYears',$validityYears)
					->with('architectInformations',$architectInformations)
					->with('architectAttachments',$architectAttachments)
					->with('architectInformationsFinal',$architectInformationsFinal)
					->with('serviceApplicationApproved',$serviceApplicationApproved)
					->with('architectAttachmentsFinal',$architectAttachmentsFinal);
	}
	public function verifyServiceApplicationRegistration(){
		$postedValues=Input::all();
		DB::beginTransaction();
		try{
			$instance=ArchitectModel::find($postedValues['ArchitectReference']);
			$instance->fill($postedValues);
			$instance->update();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('architect/verifyserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveServiceApplicationRegistration(){
		$postedValues=Input::all();
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			$instance=ArchitectModel::find($postedValues['ArchitectReference']);
			$instance->fill($postedValues);
			$instance->update();
			$architectDetails=ArchitectModel::architectHardList(Input::get('ArchitectReference'))->get(array('CrpArchitectId','Name','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount','RemarksByApprover','RemarksByVerifier'));
			$mailView="emails.crps.mailserviceapplicationapproved";
			$subject="Approval of CDB Services";
			$finalArchitectId=$architectDetails[0]->CrpArchitectId;
			$recipientAddress=$architectDetails[0]->Email;
			$recipientName=$architectDetails[0]->NameOfFirm;
			$applicationNo=$architectDetails[0]->ReferenceNo;
			$applicationDate=$architectDetails[0]->ApplicationDate;
			$mobileNo=$architectDetails[0]->MobileNo;
			$hasWaiver = $architectDetails[0]->WaiveOffLateFee;
			$newLateFeeAmount = $architectDetails[0]->NewLateFeeAmount;
			$remarksByVerifier = $architectDetails[0]->RemarksByVerifier;
			$remarksByApprover = $architectDetails[0]->RemarksByApprover;

			/*Fee Structure */
			$hasFee=false;
			$hasLateFee=false;
			$applicableFees=array();
			$hasLateFeeAmount=array();
			$architectServiceSectorType=ArchitectModel::where('Id',Input::get('ArchitectReference'))->pluck('CmnServiceSectorTypeId');
			$validityYears=CrpServiceFeeStructure::feeStructure(3)->pluck('RegistrationValidity');
			if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT){
				$validityYears=CrpServiceFeeStructure::feeStructure(2)->pluck('RegistrationValidity');
			}
			$appliedServices=ArchitectAppliedServiceModel::appliedService(Input::get('ArchitectReference'))->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ArchitectGovtAmount','T1.ArchitectPvtAmount','T1.ArchitectGovtValidity','T1.ArchitectPvtValidity'));
			foreach($appliedServices as $appliedService){
				if((int)$appliedService->HasFee==1){
					$hasFee=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee=true;
				}
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crparchitect T1 join crparchitectfinal T2 on T1.CrpArchitectId=T2.Id where T1.Id=? LIMIT 1",array(Input::get('ArchitectReference')));
			}

			/*ENd fee structure */

			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$mailIntendedTo=3;
				$feeDetails=array();
				$smsMessage="Your application for avaling services of CDB has been successfully approved.";
				$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services. <br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
				DB::statement("call ProCrpArchitectUpdateFinalData(?,?,?)",array($finalArchitectId,Input::get('ArchitectReference'),Auth::user()->Id));
			}else{
				$mailIntendedTo=3;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
				$architectServiceSectorType=ArchitectModel::where('Id',Input::get('ArchitectReference'))->pluck('CmnServiceSectorTypeId');
				if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
					$feeDetails=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}else{
					$feeDetails=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}
				$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
				$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.  However, you need to pay your fees within one month (30 days) as per the details given below to the CDB Office or the Nearest Regional Revenue and Customs Office (RRCO).Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt <br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
			}
			$mailData=array(
				'mailIntendedTo'=>$mailIntendedTo,
				'feeDetails'=>$feeDetails,
				'applicantName'=>$recipientName,
				'applicationNo'=>$applicationNo,
				'applicationDate'=>$applicationDate,
				'hasFee'=>$hasFee,
				'hasLateFee'=>$hasLateFee,
				'hasLateFeeAmount'=>$hasLateFeeAmount,
				'hasWaiver'=>$hasWaiver,
				'newLateFeeAmount'=>$newLateFeeAmount,
				'architectId'=>Input::get('ArchitectReference'),
				'architectServiceSectorType'=>$architectServiceSectorType,
				'appliedServices'=>$appliedServices,
				'validityYears'=>$validityYears,
				'mailMessage'=>$emailMessage
			);
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('architect/approveserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully approved for payment.');
	}
	public function approvePaymentServiceApplicationRegistration(){
		$postedValues=Input::except('architectservicepayment');
		$paymentValues = Input::get('architectservicepayment');
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		DB::beginTransaction();
		try{
			$postedValues['CmnApplicationRegistrationStatusId'] = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance=ArchitectModel::find($postedValues['ArchitectReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$uuidArchitect=DB::select("select uuid() as Id");
			$uuidArchitectId=$uuidArchitect[0]->Id;
			$architectDetails=ArchitectModel::architectHardList(Input::get('ArchitectReference'))->get(array('CrpArchitectId','Name','ARNo','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount','RemarksByApprover','RemarksByVerifier'));
			/*----------------------Contractor Email Details and New Details------------------*/
			$hasWaiver = $architectDetails[0]->WaiveOffLateFee;
			$newLateFeeAmount = $architectDetails[0]->NewLateFeeAmount;
			$finalArchitectId=$architectDetails[0]->CrpArchitectId;
			$ARNo=$architectDetails[0]->ARNo;
			$recipientAddress=$architectDetails[0]->Email;
			$recipientName=$architectDetails[0]->Name;
			$applicationNo=$architectDetails[0]->ReferenceNo;
			$applicationDate=$architectDetails[0]->ApplicationDate;
			$mobileNo=$architectDetails[0]->MobileNo;
			$remarksByVerifier = $architectDetails[0]->RemarksByVerifier;
			$remarksByApprover = $architectDetails[0]->RemarksByApprover;

			/*Fee Structure */

			$hasFee=false;
			$hasLateFee=false;
			$applicableFees=array();
			$hasLateFeeAmount=array();
			$architectServiceSectorType=ArchitectModel::where('Id',Input::get('ArchitectReference'))->pluck('CmnServiceSectorTypeId');
			$validityYears=CrpServiceFeeStructure::feeStructure(3)->pluck('RegistrationValidity');
			if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT){
				$validityYears=CrpServiceFeeStructure::feeStructure(2)->pluck('RegistrationValidity');
			}
			$appliedServices=ArchitectAppliedServiceModel::appliedService(Input::get('ArchitectReference'))->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ArchitectGovtAmount','T1.ArchitectPvtAmount','T1.ArchitectGovtValidity','T1.ArchitectPvtValidity'));
			foreach($appliedServices as $appliedService){
				if((int)$appliedService->HasFee==1){
					$hasFee=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee=true;
				}
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crparchitect T1 join crparchitectfinal T2 on T1.CrpArchitectId=T2.Id where T1.Id=? LIMIT 1",array(Input::get('ArchitectReference')));
			}

			/*ENd fee structure */

			/*----------------------End of Contractor Email Details and New Details------------------*/
			DB::statement("call ProCrpArchitectUpdateFinalData(?,?,?)",array($finalArchitectId,Input::get('ArchitectReference'),Auth::user()->Id));

			foreach($paymentValues as $key=>$value):
				foreach($value as $a=>$b):
					$paymentArray[$a] = $b;
				endforeach;
				$paymentArray['CrpArchitectId'] = Input::get('ArchitectReference');
				ArchitectServicePayment::create($paymentArray);
				$paymentArray = array();
			endforeach;

		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Approval of Payment";
		$mailData=array(
			'mailIntendedTo'=>3,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'hasFee'=>$hasFee,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'architectId'=>Input::get('ArchitectReference'),
			'architectServiceSectorType'=>$architectServiceSectorType,
			'appliedServices'=>$appliedServices,
			'validityYears'=>$validityYears,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for the requested services.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
		);
		$smsMessage="Your application for avaling services of CDB has been successfully approved.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('architect/approveserviceapplicationfeepaymentlist')->with('savedsuccessmessage','Payment against the registration successfully recorded.');
	}
	public function approveCertificateCancellationList(){
		$ARNoAll=Input::get('ARNoAll');
		$architectIdAll=Input::get('CrpArchitectIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$ARNoMyTask=Input::get('ARNoMyTask');
		$architectIdMyTask=Input::get('CrpArchitectIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.ARNo,T1.MobileNo,T1.Email,T1.Name as ArchitectName,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag from crparchitectfinal T1 join crparchitectcertificatecancellationrequest T10 on T1.Id=T10.CrpArchitectFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId is null";
		$queryMyTaskList="select T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.ARNo,T1.MobileNo,T1.Email,T1.Name as ArchitectName,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag from crparchitectfinal T1 join crparchitectcertificatecancellationrequest T10 on T1.Id=T10.CrpArchitectFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$ARNoAll!=NULL || (bool)$architectIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$ARNoMyTask!=NULL || (bool)$architectIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$ARNoAll!=NULL){
				$query.=" and T1.ARNo=?";
				array_push($parameters,$ARNoAll);
			}
			if((bool)$architectIdAll!=NULL){
				$query.=" and T10.Id=?";
				array_push($parameters,$architectIdAll);
			}
			if((bool)$fromDateAll!=NULL){
				$fromDateAll=$this->convertDate($fromDateAll);
				$query.=" and T10.ApplicationDate>=?";
	            array_push($parameters,$fromDateAll);
			}
			if((bool)$toDateAll!=NULL){
				$toDateAll=$this->convertDate($toDateAll);
				$query.=" and T10.ApplicationDate<=?";
	            array_push($parameters,$toDateAll);
			}
			if((bool)$ARNoMyTask!=NULL){
				$query.=" and T1.ARNo=?";
				array_push($parametersMyTaskList,$ARNoMyTask);
			}
			if((bool)$architectIdMyTask!=NULL){
				$query.=" and T10.Id=?";
				array_push($parametersMyTaskList,$architectIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$query.=" and T10.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$query.=" and T10.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateMyTask);
			}
		}
		$architectLists=DB::select($query." order by T10.ApplicationDate,T10.ReferenceNo,ArchitectName",$parameters);
		$architectMyTaskLists=DB::select($queryMyTaskList." order by T10.ApplicationDate,ArchitectName",$parametersMyTaskList);
		return View::make('crps.architectserviceapplicationcancellationlist')
					->with('ARNoAll',$ARNoAll)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('architectIdAll',$architectIdAll)
					->with('ARNoMyTask',$ARNoMyTask)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('architectIdMyTask',$architectIdMyTask)
					->with('architectLists',$architectLists)
					->with('architectMyTaskLists',$architectMyTaskLists);
	}
	public function approveCancelCertificateRequest($architectId,$cancelRequestId){
		$generalInformation=ArchitectCancelCertificateModel::cancellationList($architectId,$cancelRequestId)->get(array('crparchitectcertificatecancellationrequest.Id as CancelRequestId','crparchitectcertificatecancellationrequest.AttachmentFilePath','crparchitectcertificatecancellationrequest.ReasonForCancellation','crparchitectcertificatecancellationrequest.ReferenceNo','crparchitectcertificatecancellationrequest.ApplicationDate','T1.ARNo','T1.Name as ArchitectName','T1.Email','T1.MobileNo','T2.Name as Country','T3.NameEn as Dzongkhag'));
		return View::make('crps.architectcertificatecancellationconfirmation')
					->with('architectId',$architectId)
					->with('cancelRequestId',$cancelRequestId)
					->with('generalInformation',$generalInformation);
	}
	public function approveCancellation(){
		$cancelRequestId=Input::get('CancelRequestId');
		$architectReference=Input::get("ArchitectReference");
		$architectUserId=ArchitectFinalModel::where('Id',$architectReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			$instance=ArchitectCancelCertificateModel::find($cancelRequestId);
			$instance->SysLockedByUserId="";
			$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance->RemarksByApprover=Input::get('RemarksByApprover');
			$instance->SysApproverUserId=Auth::user()->Id;
			$instance->save();

			$architectFinalReference=ArchitectFinalModel::find($architectReference);
			$architectFinalReference->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED;
			$architectFinalReference->DeregisteredRemarks=Input::get('RemarksByApprover');
			$architectFinalReference->DeRegisteredDate=date('Y-m-d');
			$architectFinalReference->save();

			$userInstance=User::find($architectUserId);
			$userInstance->Status=0;
			$userInstance->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('architect/approvecertificatecancellationrequestlist')->with('savedsuccessmessage','Certificate has been successsfully canceled.');
	}
	public function lockApplicationCancellationRequest($cancelRequestId){
		$cancellationRequest=ArchitectCancelCertificateModel::find($cancelRequestId);
		$cancellationRequest->SysLockedByUserId=Auth::user()->Id;
		$cancellationRequest->save();
		return Redirect::to('architect/approvecertificatecancellationrequestlist');
	}
	public function viewList(){
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$architectIdMyTask=Input::get('CrpArchitectIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType,group_concat(T7.Name separator ',<br /> ') as AppliedService from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
		$pageTitle="Final Approval of Service Application";
		$pageTitleHelper="All the applications listed below has been approved and awating to receive the payments.";
		$recordLockReditectUrl="architect/approveserviceapplicationfeepaymentlist";
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

		if((bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$architectIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdMyTask!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$architectIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parametersMyTaskList,$architectIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$query.=" and T1.ApplicationDate>=?";
				array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$query.=" and T1.ApplicationDate<=?";
				array_push($parametersMyTaskList,$toDateMyTask);
			}
		}
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$architectMyTaskLists=DB::select($query." and T1.SysFinalApproverUserId is null group by T1.Id  order by ApplicationDate,ReferenceNo,ArchitectName",$parametersMyTaskList);
		return View::make('crps.architectserviceapplicationviewlist')
			->with('pageTitle',$pageTitle)
			->with('recordLockReditectUrl',$recordLockReditectUrl)
			->with('pageTitleHelper',$pageTitleHelper)
			->with('fromDateMyTask',$fromDateMyTask)
			->with('toDateMyTask',$toDateMyTask)
			->with('architectIdMyTask',$architectIdMyTask)
			->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
			->with('serviceSectorTypes',$serviceSectorTypes)
			->with('architectMyTaskLists',$architectMyTaskLists);
	}
	public function rejectCancellation($id){
		$instance=ArchitectCancelCertificateModel::find($id);
		$instance->SysLockedByUserId="";
		$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
		$instance->RemarksByRejector=Input::get('RemarksByApprover');
		$instance->SysRejectorUserId=Auth::user()->Id;
		$instance->save();
		return Redirect::to("architect/approvecertificatecancellationrequestlist")->with('savedsuccessmessage','Cancellation request has been rejected!');
	}
}