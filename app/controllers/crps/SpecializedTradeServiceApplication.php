<?php
class SpecializedTradeServiceApplication extends CrpsController{
	public function verifyApproveList(){
		$redirectUrl = Request::path();
		$SPNoAll=Input::get('SPNoAll');
		$specializedTradeIdAll=Input::get('CrpSpecializedTradeIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$SPNoMyTask=Input::get('SPNoMyTask');
		$specializedTradeIdMyTask=Input::get('CrpSpecializedTradeIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,C.InitialDate,T1.ApplicationDate,T1.SPNo,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T2.NameEn as Dzongkhag,T3.Name as Salutation,group_concat(T4.Name separator ',<br /> ') as AppliedService from crpspecializedtrade T1 join crpspecializedtradefinal C on C.Id = T1.CrpSpecializedTradeId join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,C.InitialDate,T1.ApplicationDate,T1.SPNo,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T2.NameEn as Dzongkhag,T3.Name as Salutation,group_concat(T4.Name separator ',<br /> ') as AppliedService from crpspecializedtrade T1 join crpspecializedtradefinal C on C.Id = T1.CrpSpecializedTradeId join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpSpecializedTradeId is not null";
		if(Request::path()=="specializedtrade/verifyserviceapplicationlist"){
			$pageTitle="Verify Service Application";
			$pageTitleHelper="All the applications listed below are new applications";
			$recordLockReditectUrl="specializedtrade/verifyserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		}elseif(Request::path()=="specializedtrade/approveserviceapplicationlist"){
			$pageTitle="Approve Service Application";
			$pageTitleHelper="All the applications listed below are verified applications";
			$recordLockReditectUrl="specializedtrade/approveserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}elseif(Request::path()=="specializedtrade/approveserviceapplicationfeepaymentlist"){
            $query.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
            $queryMyTaskList.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$pageTitle="Approve Fee Payment for Service";
			$pageTitleHelper="All the applications listed below has been approved and awating to receive the payments.";
			$recordLockReditectUrl="specializedtrade/approveserviceapplicationfeepaymentlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			App::abort('404');
		}
		if((bool)$SPNoAll!=NULL || (bool)$specializedTradeIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$SPNoMyTask!=NULL || (bool)$specializedTradeIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$SPNoAll!=NULL){
				$query.=" and T1.SPNo=?";
				array_push($parameters,$SPNoAll);
			}
			if((bool)$specializedTradeIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedTradeIdAll);
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
			if((bool)$SPNoMyTask!=NULL){
				$queryMyTaskList.=" and T1.SPNo=?";
				array_push($parametersMyTaskList,$SPNoMyTask);
			}
			if((bool)$specializedTradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$specializedTradeIdMyTask);
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
		$specializedTradeLists=DB::select($query." group by T1.Id order by ApplicationDate,ReferenceNo,SpecializedTradeName",$parameters);
		$specializedTradeMyTaskLists=DB::select($queryMyTaskList." group by T1.Id order by ApplicationDate,ReferenceNo,SpecializedTradeName",$parametersMyTaskList);
		return View::make('crps.specializedtradeserviceapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',$pageTitle)
					->with('recordLockReditectUrl',$recordLockReditectUrl)
					->with('pageTitleHelper',$pageTitleHelper)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('specializedTradeIdAll',$specializedTradeIdAll)
					->with('SPNoAll',$SPNoAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('specializedTradeIdMyTask',$specializedTradeIdMyTask)
					->with('SPNoMyTask',$SPNoMyTask)
					->with('specializedTradeLists',$specializedTradeLists)
					->with('specializedTradeMyTaskLists',$specializedTradeMyTaskLists);
	}
	public function serviceApplicationDetails($specializedTradeId){
		$firstRenewal=true;
		$hasFee=false;
		$hasLateFee=false;
		$hasLateFeeAmount=array();
		$serviceApplicationApprovedForPayment=0;
		$specializedTradeFinalTableId=specializedTradeModelspecializedTradeId($specializedTradeId);
		$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_RENEWAL)->pluck('SpecializedTradeValidity');
		$countRenewalApplications=SpecializedTradeAppliedServiceModel::serviceRenewalCount($specializedTradeFinalTableId)->count();
		$appliedServices=SpecializedTradeAppliedServiceModel::appliedService($specializedTradeId)->get(array('T1.Name as ServiceName','T1.HasFee','T1.SpecializedTradeFirstRenewAmount as ServiceFee','T1.SpecializedTradeValidity'));
		if((int)$countRenewalApplications>=1){
			$appliedServices=SpecializedTradeAppliedServiceModel::appliedService($specializedTradeId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.SpecializedTradeAfterFirstRenewAmount as ServiceFee','T1.SpecializedTradeValidity'));
		}
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				$hasFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
				$hasLateFee=true;
			}
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpspecializedtrade T1 join crpspecializedtradefinal T2 on T1.CrpSpecializedTradeId=T2.Id where T1.Id=? LIMIT 1",array($specializedTradeId));
		}
		if(Route::current()->getUri()=="specializedtrade/verifyserviceapplicationprocess/{specializedtradeid}"){
			$view="crps.specializedtradeverifyserviceapplicationprocess";
			$modelPost="specializedtrade/mverifyserviceapplication";
		}elseif(Route::current()->getUri()=="specializedtrade/approveserviceapplicationprocess/{specializedtradeid}"){
			$view="crps.specializedtradeapproveserviceapplicationprocess";
			$modelPost="specializedtrade/mapproveserviceapplication";
		}elseif(Route::current()->getUri()=="specializedtrade/approveserviceapplicationpaymentprocess/{specializedtradeid}"){
			$serviceApplicationApprovedForPayment=1;
			$view="crps.specializedtradeserviceapplicationapprovepayment";
			$modelPost="specializedtrade/mapprovepaymentserviceapplication";
		}else{
			App::abort('404');
		}
		$specializedTradeInformations=SpecializedTradeModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtrade.Id','crpspecializedtrade.ReferenceNo','crpspecializedtrade.ApplicationDate','crpspecializedtrade.SPNo','crpspecializedtrade.CIDNo','crpspecializedtrade.Name','crpspecializedtrade.Gewog','crpspecializedtrade.Village','crpspecializedtrade.Email','crpspecializedtrade.MobileNo','crpspecializedtrade.EmployerName','crpspecializedtrade.EmployerAddress','crpspecializedtrade.TelephoneNo','crpspecializedtrade.RemarksByVerifier','crpspecializedtrade.VerifiedDate','crpspecializedtrade.RemarksByApprover','crpspecializedtrade.RegistrationApprovedDate','T1.Name as Salutation','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover'));
		$specializedTradeAttachments=SpecializedTradeAttachmentModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		/*------------------------------End of record applied by the applicant----------------------------*/
		$specializedTradeInformationsFinal=SpecializedTradeFinalModel::specializedTrade($specializedTradeFinalTableId)->get(array('crpspecializedtradefinal.Id','crpspecializedtradefinal.RegistrationApprovedDate','crpspecializedtradefinal.RegistrationExpiryDate','crpspecializedtradefinal.ReferenceNo','crpspecializedtradefinal.ApplicationDate','crpspecializedtradefinal.SPNo','crpspecializedtradefinal.CIDNo','crpspecializedtradefinal.Name','crpspecializedtradefinal.Gewog','crpspecializedtradefinal.Village','crpspecializedtradefinal.Email','crpspecializedtradefinal.MobileNo','crpspecializedtradefinal.EmployerName','crpspecializedtradefinal.EmployerAddress','crpspecializedtradefinal.TelephoneNo','T1.Name as Salutation','T2.NameEn as Dzongkhag'));
		$specializedTradeAttachmentsFinal=SpecializedTradeAttachmentFinalModel::attachment($specializedTradeFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		$workClasssifications=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.Id as AppliedTableId,T4.Id as VerifiedTableId,T2.CmnAppliedCategoryId,T3.CmnApprovedCategoryId,T4.CmnVerifiedCategoryId,T5.CmnApprovedCategoryId as ApplicationApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? left join crpspecializedtradeworkclassification T4 on T1.Id=T4.CmnVerifiedCategoryId and T4.CrpSpecializedTradeId=? left join crpspecializedtradeworkclassification T5 on T1.Id=T5.CmnApprovedCategoryId and T5.CrpSpecializedTradeId=? left join crpspecializedtradeworkclassificationfinal T3 on T1.Id=T3.CmnApprovedCategoryId and T3.CrpSpecializedTradeFinalId=? order by T1.Code,T1.Name",array($specializedTradeId,$specializedTradeId,$specializedTradeId,$specializedTradeFinalTableId));
		return View::make($view)
					->with('modelPost',$modelPost)
					->with('countRenewalApplications',$countRenewalApplications)
					->with('hasFee',$hasFee)
					->with('hasLateFee',$hasLateFee)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('registrationValidityYears',$registrationValidityYears)
					->with('firstRenewal',$firstRenewal)
					->with('specializedTradeId',$specializedTradeId)
					->with('appliedServices',$appliedServices)
					->with('specializedTradeInformations',$specializedTradeInformations)
					->with('specializedTradeAttachments',$specializedTradeAttachments)
					->with('workClasssifications',$workClasssifications)
					->with('specializedTradeInformationsFinal',$specializedTradeInformationsFinal)
					->with('specializedTradeAttachmentsFinal',$specializedTradeAttachmentsFinal);
	}
	public function verifyServiceApplicationRegistration(){
		$postedValues=Input::all();
		DB::beginTransaction();
		try{
			$verifiedCategory=Input::get('CmnVerifiedCategoryId');
			if(!empty($verifiedCategory)){
				$instance=SpecializedTradeModel::find($postedValues['SpecializedTradeReference']);
				$instance->fill($postedValues);
				$instance->update();
				for($idx = 0; $idx < count($verifiedCategory); $idx++){
				    $instance=SpecializedTradeWorkClassificationModel::find($postedValues['WorkCategoryTableId'][$idx]);
				    $instance->CmnVerifiedCategoryId = $postedValues['CmnVerifiedCategoryId'][$idx];
				    $instance->save();
				}
			}else{
				return Redirect::to('specializedtrade/verifyserviceapplicationprocess/'.$postedValues['SpecializedTradeReference'])->withInputs()->with('customerrormessage','You need to at least verify one category.');
			}

		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('specializedtrade/verifyserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveServiceApplicationRegistration(){
		$postedValues=Input::all();
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			$approvedCategory=Input::get('CmnApprovedCategoryId');
			if(!empty($approvedCategory)){
				$instance=SpecializedTradeModel::find($postedValues['SpecializedTradeReference']);
				$instance->fill($postedValues);
				$instance->update();
				for($idx = 0; $idx < count($approvedCategory); $idx++){
				    $instance=SpecializedTradeWorkClassificationModel::find($postedValues['WorkCategoryTableId'][$idx]);
				    $instance->CmnApprovedCategoryId = $postedValues['CmnApprovedCategoryId'][$idx];
				    $instance->save();
				}
				$specializedTradeDetails=SpecializedTradeModel::specializedTradeHardList(Input::get('SpecializedTradeReference'))->get(array('CrpSpecializedTradeId','Name','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
				$mailView="emails.crps.mailapplicationapproved";
				$subject="Approval of Service Application";
				$recipientAddress=$specializedTradeDetails[0]->Email;
				$recipientName=$specializedTradeDetails[0]->Name;
				$applicationNo=$specializedTradeDetails[0]->ReferenceNo;
				$applicationDate=$specializedTradeDetails[0]->ApplicationDate;
				$finalSpecializedTradeId=$specializedTradeDetails[0]->CrpSpecializedTradeId;
				$mobileNo=$specializedTradeDetails[0]->MobileNo;
				$remarksByVerifier = $specializedTradeDetails[0]->RemarksByVerifier;
				$remarksByApprover = $specializedTradeDetails[0]->RemarksByApprover;
				if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
					$mailIntendedTo=NULL;
					$smsMessage="Your application for avaling services of CDB has been successfully approved.";
					$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
					DB::statement("call ProCrpSpecializedTradeUpdateFinalData(?,?,?)",array($finalSpecializedTradeId,$postedValues['SpecializedTradeReference'],Auth::user()->Id));
				}else{
					$mailIntendedTo=1;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
					$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
					$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.  However, you need to pay your fees as per the details given below to the Nearest Regional Revenue and Customs Office (RRCO).<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
				}
			}else{
				return Redirect::to('specializedtrade/approveserviceapplicationprocess/'.$postedValues['SpecializedTradeReference'])->withInput()->with('customerrormessage','You need to at least approve one category.');
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>$emailMessage
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('specializedtrade/approveserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully approved for payment.');
	}
	public function approvePaymentServiceApplicationRegistration(){
		$postedValues=Input::all();
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		DB::beginTransaction();
		try{
			$postedValues['CmnApplicationRegistrationStatusId'] = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance=SpecializedTradeModel::find($postedValues['SpecializedTradeReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$uuidSpecializedTrade=DB::select("select uuid() as Id");
			$uuidSpecializedTradeId=$uuidSpecializedTrade[0]->Id;
			$specializedTradeDetails=SpecializedTradeModel::specializedTradeHardList(Input::get('SpecializedTradeReference'))->get(array('Name','SPNo','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
			/*----------------------Contractor Email Details and New Details------------------*/
			$finalSpecializedTradeId=$specializedTradeDetails[0]->CrpSpecializedTradeId;
			$SPNo=$specializedTradeDetails[0]->SPNo;
			$recipientAddress=$specializedTradeDetails[0]->Email;
			$recipientName=$specializedTradeDetails[0]->Name;
			$applicationNo=$specializedTradeDetails[0]->ReferenceNo;
			$applicationDate=$specializedTradeDetails[0]->ApplicationDate;
			$mobileNo=$specializedTradeDetails[0]->MobileNo;
			$remarksByVerifier = $specializedTradeDetails[0]->RemarksByVerifier;
			$remarksByApprover = $specializedTradeDetails[0]->RemarksByApprover;
			/*----------------------End of Contractor Email Details and New Details------------------*/
			DB::statement("call ProCrpSpecializedTradeUpdateFinalData(?,?,?)",array($finalSpecializedTradeId,$postedValues['SpecializedTradeReference'],Auth::user()->Id));
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailView="emails.crps.mailregistrationpaymentcompletion";
		$subject="Acknowledgement of receipt of Your Payment";
		$mailData=array(
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No.".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for registration of your firm (".$recipientName.") with Construction Development Board (CDB). Your SP No. is ".$SPNo.". Your CDB certificate has been updated.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
		);
		$smsMessage="Your application for avaling services of CDB has been successfully approved.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('specializedtrade/approveserviceapplicationfeepaymentlist')->with('savedsuccessmessage','Payment aganist the registration successfully recorded.');
	}
	public function approveCertificateCancellationList(){
		$SPNoAll=Input::get('SPNoAll');
		$specializedTradeIdAll=Input::get('CrpSpecializedTradeIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$SPNoMyTask=Input::get('SPNoMyTask');
		$specializedTradeIdMyTask=Input::get('CrpSpecializedTradeIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.SPNo,T1.CIDNo,T1.MobileNo,T1.Email,T1.Name as SpecializedTradeName,T2.Id as CancelRequestId,T2.ReferenceNo,T2.ApplicationDate,T3.NameEn as Dzongkhag,T4.Name as Salutation from crpspecializedtradefinal T1 join crpspecializedtradecertificatecancellationrequest T2 on T1.Id=T2.CrpSpecializedTradeFinalId join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T2.CmnApplicationStatusId=? and T2.SysLockedByUserId is null";
		$queryMyTaskList="select T1.Id,T1.SPNo,T1.CIDNo,T1.MobileNo,T1.Email,T1.Name as SpecializedTradeName,T2.Id as CancelRequestId,T2.ReferenceNo,T2.ApplicationDate,T3.NameEn as Dzongkhag,T4.Name as Salutation from crpspecializedtradefinal T1 join crpspecializedtradecertificatecancellationrequest T2 on T1.Id=T2.CrpSpecializedTradeFinalId join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T2.CmnApplicationStatusId=? and T2.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$SPNoAll!=NULL || (bool)$specializedTradeIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$SPNoMyTask!=NULL || (bool)$specializedTradeIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$SPNoAll!=NULL){
				$query.=" and T1.SPNo=?";
				array_push($parameters,$SPNoAll);
			}
			if((bool)$specializedTradeIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedTradeIdAll);
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
			if((bool)$SPNoMyTask!=NULL){
				$queryMyTaskList.=" and T1.SPNo=?";
				array_push($parametersMyTaskList,$SPNoMyTask);
			}
			if((bool)$specializedTradeIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parametersMyTaskList,$specializedTradeIdMyTask);
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
		$specializedTradeLists=DB::select($query." order by T2.ApplicationDate,T2.ReferenceNo,SpecializedTradeName",$parameters);
		$specializedTradeMyTaskLists=DB::select($queryMyTaskList." order by T2.ApplicationDate,T2.ReferenceNo,SpecializedTradeName",$parametersMyTaskList);
		return View::make('crps.specializedtradeserviceapplicationcancellationlist')
					->with('SPNoAll',$SPNoAll)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('specializedTradeIdAll',$specializedTradeIdAll)
					->with('SPNoMyTask',$SPNoMyTask)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('specializedTradeIdMyTask',$specializedTradeIdMyTask)
					->with('specializedTradeLists',$specializedTradeLists)
					->with('specializedTradeMyTaskLists',$specializedTradeMyTaskLists);
	}
	public function approveCancelCertificateRequest($specializedTradeId,$cancelRequestId){
		$specializedTradeInformationsFinal=SpecializedTradeCancelCertificateModel::cancellationList($specializedTradeId,$cancelRequestId)->get(array('crpspecializedtradecertificatecancellationrequest.Id as CancelRequestId','crpspecializedtradecertificatecancellationrequest.ReasonForCancellation','crpspecializedtradecertificatecancellationrequest.AttachmentFilePath','crpspecializedtradecertificatecancellationrequest.ReferenceNo','crpspecializedtradecertificatecancellationrequest.ApplicationDate','T1.SPNo','T1.Name as SpecializedTradeName','T1.Email','T1.MobileNo','T1.CIDNo','T1.Gewog','T1.Village','T1.EmployerName','T1.EmployerAddress','T2.NameEn as Dzongkhag','T3.Name as Salutation'));
		$specializedTradeAttachmentsFinal=SpecializedTradeAttachmentFinalModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		$workClasssifications=DB::select("select T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeFinalId=?",array($specializedTradeId));
		return View::make('crps.specializedtradecertificatecancellationconfirmation')
					->with('specializedTradeId',$specializedTradeId)
					->with('cancelRequestId',$cancelRequestId)
					->with('specializedTradeInformationsFinal',$specializedTradeInformationsFinal)
					->with('specializedTradeAttachmentsFinal',$specializedTradeAttachmentsFinal)
					->with('workClasssifications',$workClasssifications);
	}
	public function approveCancellation(){
		$cancelRequestId=Input::get('CancelRequestId');
		$specializedTradeReference=Input::get("SpecializedTradeReference");
		$specializedTradeUserId=SpecializedTradeFinalModel::where('Id',$specializedTradeReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			$instance=SpecializedTradeCancelCertificateModel::find($cancelRequestId);
			$instance->SysLockedByUserId="";
			$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance->RemarksByApprover=Input::get('RemarksByApprover');
			$instance->SysApproverUserId=Auth::user()->Id;
			$instance->save();

			$specializedTradeFinalReference=SpecializedTradeFinalModel::find($specializedTradeReference);
			$specializedTradeFinalReference->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED;
			$specializedTradeFinalReference->DeregisteredRemarks=Input::get('RemarksByApprover');
			$specializedTradeFinalReference->DeRegisteredDate=date('Y-m-d');
			$specializedTradeFinalReference->save();

			$userInstance=User::find($specializedTradeUserId);
			$userInstance->Status=0;
			$userInstance->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('specializedtrade/approvecertificatecancellationrequestlist')->with('savedsuccessmessage','Certificate has been successsfully canceled.');
	}
	public function lockApplicationCancellationRequest($cancelRequestId){
		$cancellationRequest=SpecializedTradeCancelCertificateModel::find($cancelRequestId);
		$cancellationRequest->SysLockedByUserId=Auth::user()->Id;
		$cancellationRequest->save();
		return Redirect::to('specializedtrade/approvecertificatecancellationrequestlist');
	}
}