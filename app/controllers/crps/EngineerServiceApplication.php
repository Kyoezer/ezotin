<?php
class EngineerServiceApplication extends CrpsController{
	public function verifyApproveList(){
		$redirectUrl = Request::path();
		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$tradeIdAll=Input::get('CmnTradeIdAll');
		$engineerIdAll=Input::get('CrpEngineerIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$tradeIdMyTask=Input::get('CmnTradeIdMyTask');
		$engineerIdMyTask=Input::get('CrpEngineerIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,group_concat(T7.Name separator ',<br /> ') as AppliedService,T8.Name as Trade from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,group_concat(T7.Name separator ',<br /> ') as AppliedService,T8.Name as Trade from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpEngineerId is not null";
		if(Request::path()=="engineer/verifyserviceapplicationlist"){
			$pageTitle="Verify Service Application";
			$pageTitleHelper="All the applications listed below are new applications";
			$recordLockReditectUrl="engineer/verifyserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		}elseif(Request::path()=="engineer/approveserviceapplicationlist"){
			$pageTitle="Approve Service Application";
			$pageTitleHelper="All the applications listed below are verified applications";
			$recordLockReditectUrl="engineer/approveserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}elseif(Request::path()=="engineer/approveserviceapplicationfeepaymentlist"){
			$pageTitle="Approve Fee Payment for Service";
			$pageTitleHelper="All the applications listed below has been approved and awating to receive the payments.";
			$recordLockReditectUrl="engineer/approveserviceapplicationfeepaymentlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			App::abort('404');
		}
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$tradeIdAll!=NULL || (bool)$engineerIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$tradeIdMyTask!=NULL || (bool)$engineerIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$tradeIdAll!=NULL){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeIdAll);
			}
			if((bool)$engineerIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$engineerIdAll);
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
			if((bool)$tradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnTradeId=?";
				array_push($parametersMyTaskList,$tradeIdMyTask);
			}
			if((bool)$engineerIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$engineerIdMyTask);
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
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$engineerLists=DB::select($query." group by T1.Id order by ApplicationDate,ReferenceNo,EngineerName",$parameters);
		$engineerMyTaskLists=DB::select($queryMyTaskList." group by T1.Id order by ApplicationDate,ReferenceNo,EngineerName",$parametersMyTaskList);
		return View::make('crps.engineerserviceapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',$pageTitle)
					->with('trades',$trades)
					->with('recordLockReditectUrl',$recordLockReditectUrl)
					->with('pageTitleHelper',$pageTitleHelper)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('engineerIdAll',$engineerIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('tradeIdAll',$tradeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('engineerIdMyTask',$engineerIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('tradeIdMyTask',$tradeIdMyTask)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('engineerLists',$engineerLists)
					->with('engineerMyTaskLists',$engineerMyTaskLists);
	}
	public function serviceApplicationDetails($engineerId){
		$hasFee=false;
		$hasLateFee=false;
		$applicableFees=array();
		$hasLateFeeAmount=array();
		$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
		$validityYears=CrpServiceFeeStructure::feeStructure(3)->pluck('RegistrationValidity');
		if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT){
			$validityYears=CrpServiceFeeStructure::feeStructure(4)->pluck('RegistrationValidity');
		}
		$appliedServices=EngineerAppliedServiceModel::appliedService($engineerId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.EngineerGovtAmount','T1.EngineerPvtAmount','T1.EngineerGovtValidity','T1.EngineerPvtValidity'));
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				$hasFee=true;
			}
			if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
				$hasLateFee=true;
			}
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpengineer T1 join crpengineerfinal T2 on T1.CrpEngineerId=T2.Id where T1.Id=? LIMIT 1",array($engineerId));
		}
		$engineerFinalTableId=engineerModelEngineerId($engineerId);
		$serviceApplicationApprovedForPayment=0;
		if(Route::current()->getUri()=="engineer/verifyserviceapplicationprocess/{engineerid}"){
			$view="crps.engineerverifyserviceapplicationprocess";
			$modelPost="engineer/mverifyserviceapplication";
		}elseif(Route::current()->getUri()=="engineer/approveserviceapplicationprocess/{engineerid}"){
			$view="crps.engineerapproveserviceapplicationprocess";
			$modelPost="engineer/mapproveserviceapplication";
		}elseif(Route::current()->getUri()=="engineer/approveserviceapplicationpaymentprocess/{engineerid}"){
			$serviceApplicationApprovedForPayment=1;
			$view="crps.engineerserviceapplicationapprovepayment";
			$modelPost="engineer/mapprovepaymentserviceapplication";
		}else{
			App::abort('404');
		}
		$engineerInformations=EngineerModel::engineer($engineerId)->get(array('crpengineer.Id','crpengineer.CDBNo','crpengineer.CIDNo','crpengineer.Name','crpengineer.Gewog','crpengineer.Village','crpengineer.Email','crpengineer.MobileNo','crpengineer.EmployerName','crpengineer.EmployerAddress','crpengineer.GraduationYear','crpengineer.NameOfUniversity','crpengineer.RemarksByVerifier','crpengineer.RemarksByApprover','crpengineer.RemarksByPaymentApprover','crpengineer.VerifiedDate','crpengineer.PaymentApprovedDate','crpengineer.RegistrationApprovedDate','crpengineer.WaiveOffLateFee','crpengineer.NewLateFeeAmount','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade','T10.FullName as Verifier','T11.FullName as Approver','T12.FullName as PaymentApprover'));
		$engineerAttachments=EngineerAttachmentModel::attachment($engineerId)->get(array('Id','DocumentName','DocumentPath'));
		/*------------------------------End of record applied by the applicant----------------------------*/
		$engineerInformationsFinal=EngineerFinalModel::engineer($engineerFinalTableId)->get(array('crpengineerfinal.CDBNo','crpengineerfinal.CIDNo','crpengineerfinal.Name','crpengineerfinal.Gewog','crpengineerfinal.Village','crpengineerfinal.Email','crpengineerfinal.MobileNo','crpengineerfinal.EmployerName','crpengineerfinal.EmployerAddress','crpengineerfinal.GraduationYear','crpengineerfinal.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus','T8.Name as Trade'));
		$engineerAttachmentsFinal=EngineerAttachmentFinalModel::attachment($engineerFinalTableId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make($view)
					->with('modelPost',$modelPost)
					->with('hasFee',$hasFee)
					->with('hasLateFee',$hasLateFee)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('engineerId',$engineerId)
					->with('engineerServiceSectorType',$engineerServiceSectorType)
					->with('appliedServices',$appliedServices)
					->with('validityYears',$validityYears)
					->with('engineerInformations',$engineerInformations)
					->with('engineerAttachments',$engineerAttachments)
					->with('engineerInformationsFinal',$engineerInformationsFinal)
					->with('engineerAttachmentsFinal',$engineerAttachmentsFinal);
	}
	public function verifyServiceApplicationRegistration(){
		$postedValues=Input::all();
		DB::beginTransaction();
		try{
			$instance=EngineerModel::find($postedValues['EngineerReference']);
			$instance->fill($postedValues);
			$instance->update();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('engineer/verifyserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveServiceApplicationRegistration(){
		$postedValues=Input::all();
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			$instance=EngineerModel::find($postedValues['EngineerReference']);
			$instance->fill($postedValues);
			$instance->update();
			$engineerDetails=EngineerModel::engineerHardList(Input::get('EngineerReference'))->get(array('Name','Email','ReferenceNo','ApplicationDate'));
			$mailView="emails.crps.mailserviceapplicationapproved";
			$subject="Approval of Service Application";
			$finalEngineerId=$engineerDetails[0]->CrpEngineerId;
			$recipientAddress=$engineerDetails[0]->Email;
			$recipientName=$engineerDetails[0]->NameOfFirm;
			$applicationNo=$engineerDetails[0]->ReferenceNo;
			$applicationDate=$engineerDetails[0]->ApplicationDate;
			$mobileNo=$engineerDetails[0]->MobileNo;

			/* Start fee structure */
			$engineerId = Input::get('EngineerReference');
			$hasFee=false;
			$hasLateFee=false;
			$applicableFees=array();
			$hasLateFeeAmount=array();
			$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
			$validityYears=CrpServiceFeeStructure::feeStructure(3)->pluck('RegistrationValidity');
			if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT){
				$validityYears=CrpServiceFeeStructure::feeStructure(4)->pluck('RegistrationValidity');
			}
			$appliedServices=EngineerAppliedServiceModel::appliedService($engineerId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.EngineerGovtAmount','T1.EngineerPvtAmount','T1.EngineerGovtValidity','T1.EngineerPvtValidity'));
			foreach($appliedServices as $appliedService){
				if((int)$appliedService->HasFee==1){
					$hasFee=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee=true;
				}
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpengineer T1 join crpengineerfinal T2 on T1.CrpEngineerId=T2.Id where T1.Id=? LIMIT 1",array($engineerId));
			}
			/* End fee structure */

			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$mailIntendedTo=4;
				$feeDetails=array();
				$smsMessage="Your application for avaling services of CDB has been successfully approved.";
				$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.";
				DB::statement("call ProCrpEngineerUpdateFinalData(?,?,?)",array($finalEngineerId,$postedValues['EngineerReference'],Auth::user()->Id));
			}else{
				$mailIntendedTo=4;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
				$engineerServiceSectorType=EngineerModel::where('Id',Input::get('EngineerReference'))->pluck('CmnServiceSectorTypeId');
				if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
					$feeDetails=DB::select("select 'Private' as SectorType,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}else{
					$feeDetails=DB::select("select 'Goverment' as SectorType,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}
				$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
				$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.  However, you need to pay your fees as per the details given below to the Nearest Regional Revenue and Customs Office (RRCO).";
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'engineerId'=>$engineerId,
			'engineerServiceSectorType'=>$engineerServiceSectorType,
			'appliedServices'=>$appliedServices,
			'validityYears'=>$validityYears,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>$emailMessage
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('engineer/approveserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully approved for payment.');
	}
	public function approvePaymentServiceApplicationRegistration(){
		$postedValues=Input::all();
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		DB::beginTransaction();
		try{
			$postedValues['CmnApplicationRegistrationStatusId'] = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance=EngineerModel::find($postedValues['EngineerReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$uuidEngineer=DB::select("select uuid() as Id");
			$uuidEngineerId=$uuidEngineer[0]->Id;
			$engineerDetails=EngineerModel::engineerHardList(Input::get('EngineerReference'))->get(array('Name','CDBNo','Email','ReferenceNo','ApplicationDate','MobileNo'));
			/*----------------------Contractor Email Details and New Details------------------*/

			/* Start fee structure */
			$engineerId = Input::get('EngineerReference');
			$hasFee=false;
			$hasLateFee=false;
			$applicableFees=array();
			$hasLateFeeAmount=array();
			$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
			$validityYears=CrpServiceFeeStructure::feeStructure(3)->pluck('RegistrationValidity');
			if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT){
				$validityYears=CrpServiceFeeStructure::feeStructure(4)->pluck('RegistrationValidity');
			}
			$appliedServices=EngineerAppliedServiceModel::appliedService($engineerId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.EngineerGovtAmount','T1.EngineerPvtAmount','T1.EngineerGovtValidity','T1.EngineerPvtValidity'));
			foreach($appliedServices as $appliedService){
				if((int)$appliedService->HasFee==1){
					$hasFee=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee=true;
				}
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpengineer T1 join crpengineerfinal T2 on T1.CrpEngineerId=T2.Id where T1.Id=? LIMIT 1",array($engineerId));
			}
			/* End fee structure */

			$finalEngineerId=$engineerDetails[0]->CrpEngineerId;
			$CDBNo=$engineerDetails[0]->CDBNo;
			$recipientAddress=$engineerDetails[0]->Email;
			$recipientName=$engineerDetails[0]->Name;
			$applicationNo=$engineerDetails[0]->ReferenceNo;
			$applicationDate=$engineerDetails[0]->ApplicationDate;
			$mobileNo=$engineerDetails[0]->MobileNo;
			/*----------------------End of Contractor Email Details and New Details------------------*/
			DB::statement("call ProCrpEngineerUpdateFinalData(?,?,?)",array($finalEngineerId,$postedValues['EngineerReference'],Auth::user()->Id));
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailIntendedTo=4;
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Acknowledgement of receipt of Your Payment";
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'engineerId'=>$engineerId,
			'engineerServiceSectorType'=>$engineerServiceSectorType,
			'appliedServices'=>$appliedServices,
			'validityYears'=>$validityYears,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for the requested services. Your CDB certificate has been updated."
		);
		$smsMessage="Your application for avaling services of CDB has been successfully approved.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('engineer/approveserviceapplicationfeepaymentlist')->with('savedsuccessmessage','Payment aganist the registration successfully recorded.');
	}
	public function approveCertificateCancellationList(){
		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$tradeIdAll=Input::get('CmnTradeIdAll');
		$engineerIdAll=Input::get('CrpEngineerIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$tradeIdMyTask=Input::get('CmnTradeIdMyTask');
		$engineerIdMyTask=Input::get('CrpEngineerIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.CDBNo,T1.CIDNo,T1.MobileNo,T1.Email,T1.Name as EngineerName,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineerfinal T1 join crpengineercertificatecancellationrequest T10 on T1.Id=T10.CrpEngineerFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem  T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId is null";
		$queryMyTaskList="select T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.CDBNo,T1.CIDNo,T1.MobileNo,T1.Email,T1.Name as EngineerName,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineerfinal T1 join crpengineercertificatecancellationrequest T10 on T1.Id=T10.CrpEngineerFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem  T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$tradeIdAll!=NULL || (bool)$engineerIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$tradeIdMyTask!=NULL || (bool)$engineerIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$tradeIdAll!=NULL){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeIdAll);
			}
			if((bool)$engineerIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$engineerIdAll);
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
			if((bool)$tradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnTradeId=?";
				array_push($parametersMyTaskList,$tradeIdMyTask);
			}
			if((bool)$engineerIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parametersMyTaskList,$engineerIdMyTask);
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
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$engineerLists=DB::select($query." order by T10.ApplicationDate,T10.ReferenceNo,EngineerName",$parameters);
		$engineerMyTaskLists=DB::select($queryMyTaskList." order by T10.ApplicationDate,EngineerName",$parametersMyTaskList);
		return View::make('crps.engineerserviceapplicationcancellationlist')
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('engineerIdAll',$engineerIdAll)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('engineerIdAll',$engineerIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('tradeIdAll',$tradeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('engineerIdMyTask',$engineerIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('tradeIdMyTask',$tradeIdMyTask)
					->with('trades',$trades)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('engineerLists',$engineerLists)
					->with('engineerMyTaskLists',$engineerMyTaskLists);
	}
	public function approveCancelCertificateRequest($engineerId,$cancelRequestId){
		$generalInformation=EngineerCancelCertificateModel::cancellationList($engineerId,$cancelRequestId)->get(array('crpengineercertificatecancellationrequest.Id as CancelRequestId','crpengineercertificatecancellationrequest.ReasonForCancellation','crpengineercertificatecancellationrequest.AttachmentFilePath','crpengineercertificatecancellationrequest.ReferenceNo','crpengineercertificatecancellationrequest.ApplicationDate','T1.CDBNo','T1.Name as EngineerName','T1.Email','T1.MobileNo','T2.Name as Country','T3.NameEn as Dzongkhag','T4.Name as EngineerType','T5.Name as Trade'));
		return View::make('crps.engineercertificatecancellationconfirmation')
					->with('engineerId',$engineerId)
					->with('cancelRequestId',$cancelRequestId)
					->with('generalInformation',$generalInformation);
	}
	public function approveCancellation(){
		$cancelRequestId=Input::get('CancelRequestId');
		$engineerReference=Input::get("EngineerReference");
		$engineerUserId=EngineerFinalModel::where('Id',$engineerReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			$instance=EngineerCancelCertificateModel::find($cancelRequestId);
			$instance->SysLockedByUserId="";
			$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance->RemarksByApprover=Input::get('RemarksByApprover');
			$instance->SysApproverUserId=Auth::user()->Id;
			$instance->save();

			$engineerFinalReference=EngineerFinalModel::find($engineerReference);
			$engineerFinalReference->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED;
			$engineerFinalReference->DeregisteredRemarks=Input::get('RemarksByApprover');
			$engineerFinalReference->DeRegisteredDate=date('Y-m-d');
			$engineerFinalReference->save();

			$userInstance=User::find($engineerUserId);
			$userInstance->Status=0;
			$userInstance->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('engineer/approvecertificatecancellationrequestlist')->with('savedsuccessmessage','Certificate has been successsfully canceled.');
	}
	public function lockApplicationCancellationRequest($cancelRequestId){
		$cancellationRequest=EngineerCancelCertificateModel::find($cancelRequestId);
		$cancellationRequest->SysLockedByUserId=Auth::user()->Id;
		$cancellationRequest->save();
		return Redirect::to('engineer/approvecertificatecancellationrequestlist');
	}
}