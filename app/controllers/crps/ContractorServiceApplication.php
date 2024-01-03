<?php
class ContractorServiceApplication extends CrpsController{
	public function verifyApproveList(){
		$contractorIdAll=Input::get('CrpContractorIdAll');
		$CDBNoAll=Input::get('CDBNoAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$contractorIdMyTask=Input::get('CrpContractorIdMyTask');
		$CDBNoMyTask=Input::get('CDBNoMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,T7.CDBNo,T1.ApplicationDate,T7.InitialDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(distinct ST.Name separator ',<br/> ') as ServiceApplied,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (cmncontractorclassification B join viewcontractormaxclassification C on C.MaxClassificationPriority = B.Priority) on T7.Id=C.CrpContractorFinalId left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T7.CDBNo,T1.ApplicationDate,T7.InitialDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(distinct ST.Name separator ',<br/> ') as ServiceApplied,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (cmncontractorclassification B join viewcontractormaxclassification C on C.MaxClassificationPriority = B.Priority) on T7.Id=C.CrpContractorFinalId left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpContractorId is not null";
		$redirectUrl = Request::path();
		if(Request::path()=="contractor/verifyserviceapplicationlist"){
			$pageTitle="Verify Service Application";
			$pageTitleHelper="All the applications listed below are new applications";
			$recordLockReditectUrl="contractor/verifyserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		}elseif(Request::path()=="contractor/approveserviceapplicationlist"){
			$pageTitle="Approve Service Application";
			$pageTitleHelper="All the applications listed below are verified applications";
			$recordLockReditectUrl="contractor/approveserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}elseif(Request::path()=="contractor/approveserviceapplicationfeepaymentlist"){
			$pageTitle="Approve Fee Payment for Service";
			$pageTitleHelper="All the applications listed below has been approved and awating to receive the payments.";
			$recordLockReditectUrl="contractor/approveserviceapplicationfeepaymentlist";
			$query.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$queryMyTaskList.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			App::abort('404');
		}
		if((bool)$contractorIdAll!=NULL || (bool)$CDBNoAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$contractorIdMyTask!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$contractorIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorIdAll);
			}
			if((bool)$CDBNoAll!=NULL){
				$query.=" and T7.CDBNo=?";
				array_push($parameters,$CDBNoAll);
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
			if((bool)$contractorIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$contractorIdMyTask);
			}
			if((bool)$CDBNoMyTask!=NULL){
				$queryMyTaskList.=" and T7.CDBNo=?";
				array_push($parametersMyTaskList,$CDBNoMyTask);
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
		$contractorLists=DB::select($query." group by T1.Id order by ApplicationDate,ReferenceNo,NameOfFirm",$parameters);
		$contractorMyTaskLists=DB::select($queryMyTaskList." group by T1.Id order by ApplicationDate,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.contractorserviceapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',$pageTitle)
					->with('recordLockReditectUrl',$recordLockReditectUrl)
					->with('pageTitleHelper',$pageTitleHelper)
					->with('fromDateAll',convertDateToClientFormat($fromDateAll))
					->with('toDateAll',convertDateToClientFormat($toDateAll))
					->with('contractorIdAll',$contractorIdAll)
					->with('CDBNoAll',$CDBNoAll)
					->with('fromDateMyTask',convertDateToClientFormat($fromDateMyTask))
					->with('toDateMyTask',convertDateToClientFormat($toDateMyTask))
					->with('contractorIdMyTask',$contractorIdMyTask)
					->with('CDBNoMyTask',$CDBNoMyTask)
					->with('contractorLists',$contractorLists)
					->with('contractorMyTaskLists',$contractorMyTaskLists);
	}
	public function serviceApplicationDetails($contractorId,$forReport = false){
		$hasReregistration = false;
		$serviceApplicationApprovedForPayment=0;
		$serviceApplicationApproved = 0;
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$contractorFinalTableId=contractorModelContractorId($contractorId);
		$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFInalId',$contractorFinalTableId)->pluck('MaxClassificationPriority');
		$appliedServices=ContractorAppliedServiceModel::appliedService($contractorId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));

		$generalInformation=ContractorModel::contractor($contractorId)->get(array('crpcontractor.Id',DB::raw('coalesce(crpcontractor.WaiveOffLateFee,0) as WaiveOffLateFee'),'crpcontractor.NewLateFeeAmount','crpcontractor.PaymentReceiptNo', 'crpcontractor.PaymentReceiptDate','crpcontractor.Id','crpcontractor.ReferenceNo','crpcontractor.ApplicationDate','crpcontractor.CDBNo','crpcontractor.NameOfFirm','crpcontractor.RegisteredAddress','crpcontractor.Village','crpcontractor.Gewog','crpcontractor.Address','crpcontractor.Email','crpcontractor.TelephoneNo','crpcontractor.MobileNo','crpcontractor.RemarksByRejector','crpcontractor.FaxNo','crpcontractor.CmnApplicationRegistrationStatusId','crpcontractor.RegistrationVerifiedDate','crpcontractor.ChangeOfOwnershipRemarks','crpcontractor.RemarksByVerifier','crpcontractor.RemarksByApprover','crpcontractor.RegistrationApprovedDate','crpcontractor.RemarksByPaymentApprover','crpcontractor.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T8.NameEn as RegisteredDzongkhag'));

		$registrationExpiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('RegistrationExpiryDate');
		$reregistrationDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('ReRegistrationDate');
		if((bool)$reregistrationDate){
			if($reregistrationDate>$registrationExpiryDate){
				$hasReregistration = true;
				$registrationExpiryDate = $reregistrationDate;
			}
		}
		if($hasReregistration) {
			$generalInformationFinal=ContractorFinalModel::contractor($contractorFinalTableId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.RegistrationApprovedDate','crpcontractorfinal.ReregistrationDate as RegistrationExpiryDate','crpcontractorfinal.CDBNo','crpcontractorfinal.NameOfFirm','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.Village','crpcontractorfinal.Gewog','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
		}else{
			$generalInformationFinal=ContractorFinalModel::contractor($contractorFinalTableId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.RegistrationApprovedDate','crpcontractorfinal.RegistrationExpiryDate','crpcontractorfinal.CDBNo','crpcontractorfinal.NameOfFirm','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.Village','crpcontractorfinal.Gewog','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','crpcontractorfinal.RegistrationExpiryDate','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
		}
		foreach($appliedServices as $appliedService){
			if((int)$appliedService->HasFee==1){
				if($appliedService->Id == CONST_SERVICETYPE_INCORPORATION):
					if($generalInformation[0]->OwnershipType != $generalInformationFinal[0]->OwnershipType):
						$hasFee=true;
					endif;
				else:
					$hasFee=true;
				endif;

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
			$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($contractorId,$contractorFinalTableId,$contractorId,$contractorId,$contractorId,$contractorFinalTableId));
		}
		if($hasLateFee){
			if($hasReregistration) {
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.ReregistrationDate as RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.ReregistrationDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($contractorId));
			}else{
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($contractorId));
			}
		}
		$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_NEW)->pluck('ContractorValidity');
		if(Route::current()->getUri()=="contractor/verifyserviceapplicationprocess/{contractorid}"){
			$view="crps.contractorverifyserviceapplicationprocess";
			$modelPost="contractor/mverifyserviceapplication";
		}elseif(Route::current()->getUri()=="contractor/approveserviceapplicationprocess/{contractorid}"){
			$view="crps.contractorapproveserviceapplicationprocess";
			$modelPost="contractor/mapproveserviceapplication";
		}elseif(Route::current()->getUri()=="contractor/approveserviceapplicationpaymentprocess/{contractorid}"){
			$serviceApplicationApprovedForPayment=1;
			$view="crps.contractorserviceapplicationapprovepayment";
			$modelPost="contractor/mapprovepaymentserviceapplication";
		}elseif(Route::current()->getUri()=="contractor/viewserviceapplicationdetails/{contractorid}"){
			$serviceApplicationApprovedForPayment=1;
			$serviceApplicationApproved=1;
			$view="crps.contractorserviceapplicationapprovepayment";
			$modelPost="contractor/mapprovepaymentserviceapplication";
		}else{
		    if(!$forReport)
			    App::abort('404');
		}
		$referenceNo = DB::table('crpcontractor')->where('Id',$contractorId)->pluck('ReferenceNo');
		$oldPartners = DB::table('crpcontractorhrtrack as T1')
			->join('cmncountry as T2','T2.Id','=','T1.CmnCountryId')
			->join('cmnlistitem as T3','T3.Id','=','T1.CmnDesignationId')
			->where('T1.ReferenceNo',$referenceNo)
			->get(array('T1.Name','T1.CIDNo','T2.Name as Country','T3.Name as Designation'));
		$class=ContractorClassificationModel::classification()->select(DB::raw('Id,Name,coalesce(ReferenceNo,88888888) as ReferenceNo'))->get();

		$ownerPartnerDetails=ContractorHumanResourceModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Name','crpcontractorhumanresource.Sex','crpcontractorhumanresource.ShowInCertificate','crpcontractorhumanresource.Verified','crpcontractorhumanresource.Approved','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		foreach($ownerPartnerDetails as $ownerPartnerDetail):
			$cidNo = $ownerPartnerDetail->CIDNo;
			$checkPartnerDeregistered = DB::table('crpcontractorfinal as T1')
				->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
				->whereIn(DB::raw("coalesce(T1.CmnApplicationRegistrationStatusId,'".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."')"),array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED))
				->where(DB::raw('TRIM(T2.CIDNo)'),trim($cidNo))
				->where(DB::raw('coalesce(T2.IsPartnerOrOwner,0)'),1)
				->get(array(DB::raw("group_concat(concat(T1.NameOfFirm,' (',T1.CDBNo,')') SEPARATOR ', ') as Firms")));
			if((bool)$checkPartnerDeregistered[0]->Firms){
				$ownerPartnerDetail->OtherRemarks = "Owner/Partner of Deregistered Firm(s) - ".$checkPartnerDeregistered[0]->Firms;
			}else{
				$ownerPartnerDetail->OtherRemarks = '--';
			}
		endforeach;
		$contractorWorkClassifications=ContractorWorkClassificationModel::contractorWorkClassification($contractorId)->select(DB::raw('crpcontractorworkclassification.Id,crpcontractorworkclassification.CmnAppliedClassificationId,crpcontractorworkclassification.CmnVerifiedClassificationId,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T2.Name as AppliedClassification,T3.Name as VerifiedClassification'))->get();
		$contractorHumanResources=ContractorHumanResourceModel::contractorHumanResource($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.Name','crpcontractorhumanresource.Verified','crpcontractorhumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		foreach($contractorHumanResources as $contractorHumanResource):
			$cidNo = $contractorHumanResource->CIDNo;
			$checkPartnerDeregistered = DB::table('crpcontractorfinal as T1')
				->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
				->whereIn(DB::raw("coalesce(T1.CmnApplicationRegistrationStatusId,'".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."')"),array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED))
				->where(DB::raw('TRIM(T2.CIDNo)'),trim($cidNo))
				->where(DB::raw('coalesce(T2.IsPartnerOrOwner,0)'),1)
				->get(array(DB::raw("group_concat(concat(T1.NameOfFirm,' (',T1.CDBNo,')') SEPARATOR ', ') as Firms")));
			if((bool)$checkPartnerDeregistered[0]->Firms){
				$contractorHumanResource->OtherRemarks = "Owner/Partner of Deregistered Firm(s) - ".$checkPartnerDeregistered[0]->Firms;
			}else{
				$contractorHumanResource->OtherRemarks = '--';
			}
		endforeach;
		$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','crpcontractorequipment.Verified','crpcontractorequipment.Approved','T1.Name','T1.VehicleType'));
		$contractorHumanResourceAttachments=ContractorHumanResourceAttachmentModel::singleContractorHumanResourceAllAttachments($contractorId)->get(array('crpcontractorhumanresourceattachment.DocumentName','crpcontractorhumanresourceattachment.DocumentPath','crpcontractorhumanresourceattachment.CrpContractorHumanResourceId'));
		$contractorEquipmentAttachments=ContractorEquipmentAttachmentModel::singleContractorEquipmentAllAttachments($contractorId)->get(array('crpcontractorequipmentattachment.DocumentName','crpcontractorequipmentattachment.DocumentPath','crpcontractorequipmentattachment.CrpContractorEquipmentId'));
		$serviceApplicationsAttachments=ContractorAttachmentModel::attachment($contractorId)->get(array('DocumentName','DocumentPath'));
		/*------------------------------End of record applied by the applicant----------------------------*/

		$ownerPartnerDetailsFinal=ContractorHumanResourceFinalModel::contractorPartner($contractorFinalTableId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$contractorWorkClassificationsFinal=ContractorWorkClassificationFinalModel::contractorWorkClassification($contractorFinalTableId)->select(DB::raw('crpcontractorworkclassificationfinal.Id,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T4.Name as ApprovedClassification'))->get();
		$contractorHumanResourcesFinal=ContractorHumanResourceFinalModel::ContractorHumanResource($contractorFinalTableId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.Name',DB::raw('coalesce(crpcontractorhumanresourcefinal.DeleteRequest,0) as DeleteRequest'),'crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$contractorEquipmentsFinal=ContractorEquipmentFinalModel::contractorEquipment($contractorFinalTableId)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.RegistrationNo',DB::raw('coalesce(crpcontractorequipmentfinal.DeleteRequest,0) as DeleteRequest'),'crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name','T1.VehicleType'));
		$contractorHumanResourceAttachmentsFinal=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractorFinalTableId)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId'));
		$contractorEquipmentAttachmentsFinal=ContractorEquipmentAttachmentFinalModel::singleContractorEquipmentAllAttachments($contractorFinalTableId)->get(array('crpcontractorequipmentattachmentfinal.DocumentName','crpcontractorequipmentattachmentfinal.DocumentPath','crpcontractorequipmentattachmentfinal.CrpContractorEquipmentFinalId'));
		$contractorTrackrecords=CrpBiddingFormModel::contractorTrackRecords($contractorFinalTableId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Code as ProjectCategory','T4.Name as classification','T5.NameEn as Dzongkhag'));

		$contractorEmployeesIdsFinal = DB::table('crpcontractorhumanresourcefinal')->where('CrpContractorFinalId',$contractorFinalTableId)->select(DB::raw("TRIM(CIDNo) as EmpCIDNo"))->lists('EmpCIDNo');
		$contractorEmployeesIdsNew = DB::table('crpcontractorhumanresource')->where('CrpContractorId',$contractorId)->select(DB::raw("TRIM(CIDNo) as EmpCIDNo"))->lists('EmpCIDNo');
		$contractorEmployeesIds = array_merge($contractorEmployeesIdsFinal,$contractorEmployeesIdsNew);
		$trainingsAttended = DB::table('crpcontractortrainingdetail as T1')
			->join('crpcontractortraining as T2','T1.CrpContractorTrainingId','=','T2.Id')
			->join('cmnlistitem as A','A.Id','=','T2.CmnTrainingTypeId')
			->leftJoin('cmnlistitem as T3','T3.Id','=','T2.CmnTrainingModuleId')
			->whereIn(DB::raw("TRIM(T1.CIDNo)"),$contractorEmployeesIds)
			->orWhere('T1.CrpContractorFinalId',$contractorFinalTableId)
			->orderBy('TrainingFromDate','Desc')
			->get(array("T1.Participant","TrainingFromDate","TrainingToDate",'T1.CIDNo','T3.Name as Module','A.ReferenceNo as TrainingReference','A.Name as TrainingType'));

		if($forReport){
		    $reportDetails['generalInformation'] = $generalInformation;
		    $reportDetails['generalInformationFinal'] = $generalInformationFinal;
		    $reportDetails['maxClassification'] = $maxClassification;
		    $reportDetails['hasFee'] = $hasFee;
		    $reportDetails['hasLateFee'] = $hasLateFee;
		    $reportDetails['hasLateFeeAmount'] = $hasLateFeeAmount;
		    $reportDetails['registrationValidityYears'] = $registrationValidityYears;
		    $reportDetails['hasRenewal'] = $hasRenewal;
		    $reportDetails['hasChangeInCategoryClassification'] = $hasChangeInCategoryClassification;
		    $reportDetails['classes'] = $class;
		    $reportDetails['appliedServices'] = $appliedServices;
		    $reportDetails['hasCategoryClassificationsFee'] = $hasCategoryClassificationsFee;
		    $reportDetails['serviceApplicationApprovedForPayment'] = $serviceApplicationApprovedForPayment;
		    $reportDetails['serviceApplicationApproved'] = $serviceApplicationApproved;
		    return $reportDetails;
        }
		return View::make($view)
					->with('maxClassification',$maxClassification)
					->with('modelPost',$modelPost)
					->with('oldPartners',$oldPartners)
					->with('hasFee',$hasFee)
					->with('hasLateFee',$hasLateFee)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('registrationValidityYears',$registrationValidityYears)
					->with('hasRenewal',$hasRenewal)
					->with('hasChangeInCategoryClassification',$hasChangeInCategoryClassification)
					->with('classes',$class)
					->with('appliedServices',$appliedServices)
					->with('hasCategoryClassificationsFee',$hasCategoryClassificationsFee)
					->with('serviceApplicationApprovedForPayment',$serviceApplicationApprovedForPayment)
					->with('serviceApplicationApproved',$serviceApplicationApproved)
					->with('contractorId',$contractorId)
					->with('generalInformation',$generalInformation)
					->with('generalInformationFinal',$generalInformationFinal)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('ownerPartnerDetailsFinal',$ownerPartnerDetailsFinal)
					->with('contractorWorkClassifications',$contractorWorkClassifications)
					->with('contractorWorkClassificationsFinal',$contractorWorkClassificationsFinal)
					->with('contractorHumanResources',$contractorHumanResources)
					->with('contractorHumanResourcesFinal',$contractorHumanResourcesFinal)
					->with('contractorEquipments',$contractorEquipments)
					->with('contractorEquipmentsFinal',$contractorEquipmentsFinal)
					->with('contractorHumanResourceAttachments',$contractorHumanResourceAttachments)
					->with('contractorHumanResourceAttachmentsFinal',$contractorHumanResourceAttachmentsFinal)
					->with('contractorEquipmentAttachments',$contractorEquipmentAttachments)
					->with('contractorEquipmentAttachmentsFinal',$contractorEquipmentAttachmentsFinal)
					->with('contractorTrackrecords',$contractorTrackrecords)
					->with('trainingsAttended',$trainingsAttended)
					->with('serviceApplicationsAttachments',$serviceApplicationsAttachments);
	}
	public function verifyServiceApplicationRegistration(){
		$postedValues=Input::all();
		if(!isset($postedValues['WaiveOffLateFee']) || empty($postedValues['WaiveOffLateFee'])){
			$postedValues['WaiveOffLateFee'] = 0;
			$postedValues['NewLateFeeAmount'] = NULL;
		}
		DB::beginTransaction();
		try{
			$instance=ContractorModel::find($postedValues['ContractorReference']);
			$instance->fill($postedValues);
			$instance->update();
			foreach($postedValues as $key=>$value){
				if(gettype($value)=='array'){
					$modelName=$key;
					foreach($value as $key1=>$value1){
						foreach($value1 as $key2=>$value2){
							$val1=trim($value2);
							if(strlen($val1)==0){
								$value1[$key2]=null;
							}
						}
						$childTable= new $modelName();
						$childTable1=$childTable::find($value1['Id']);
						$childTable1->fill($value1);
						$childTable1->update();
					}
				}
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('contractor/verifyserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveServiceApplicationRegistration(){
		$postedValues=Input::except('Id','SendNotification');
		/* FOR FEE STRUCTURE */
		$hasFee=false;
        $hasReregistration=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$hasWaiver = 0;
		$newLateFeeAmount = 0;
		$contractorFinalTableId=contractorModelContractorId($postedValues['ContractorReference']);
		$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFinalId',$contractorFinalTableId)->pluck('MaxClassificationPriority');
		$appliedServices=ContractorAppliedServiceModel::appliedService($postedValues['ContractorReference'])->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));
		/* END FOR FEE STRUCTURE */

		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		if(isset($postedValues['RegistrationExpiryDate']))
			$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			if(Input::has('SendNotification')){
				DB::statement("INSERT INTO crpnotificationfordeletedhumanresource (Id,SysUserId,ApplicationId) VALUES (UUID(),?,?)",array(Auth::user()->Id,$postedValues['ContractorReference']));
			}
			$instance=ContractorModel::find($postedValues['ContractorReference']);
			$instance->fill($postedValues);
			$instance->update();
			foreach($postedValues as $key=>$value){
				if(gettype($value)=='array'){
					$modelName=$key;
					foreach($value as $key1=>$value1){
						foreach($value1 as $key2=>$value2){
							$val1=trim($value2);
							if(strlen($val1)==0){
								$value1[$key2]=null;
							}
						}
						$childTable= new $modelName();
						$childTable1=$childTable::find($value1['Id']);
						$childTable1->fill($value1);
						$childTable1->update();
					}
				}
			}
			$contractorDetails=ContractorModel::contractorHardList(Input::get('ContractorReference'))->get(array('CrpContractorId','NameOfFirm','Email','ReferenceNo','RemarksByVerifier','RemarksByApprover','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount'));
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
			$remarksByVerifier = $contractorDetails[0]->RemarksByVerifier;
			$remarksByApprover = $contractorDetails[0]->RemarksByApprover;

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
				$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($postedValues['ContractorReference'],$contractorFinalTableId,$postedValues['ContractorReference'],$postedValues['ContractorReference'],$postedValues['ContractorReference'],$contractorFinalTableId));
			}
            $registrationExpiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('RegistrationExpiryDate');
            $reregistrationDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('ReRegistrationDate');
            if((bool)$reregistrationDate){
                if($reregistrationDate>$registrationExpiryDate){
                    $hasReregistration = true;
                }
            }
			if($hasLateFee){
                if($hasReregistration) {
                    $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.ReregistrationDate as RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.ReregistrationDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($postedValues['ContractorReference']));
                }else{
                    $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($postedValues['ContractorReference']));
                }
//				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($postedValues['ContractorReference']));
			}
			/* END FOR FEE STRUCTURE */

			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$mailIntendedTo=NULL;
				$feeStructures=array();
				$emailMessage="Your application for avaling services of CDB has been successfully approved.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
				$smsMessage="Your application for avaling services of CDB has been successfully approved.";
				DB::statement("call ProCrpContractorUpdateFinalData(?,?,?)",array($finalContractorId,$postedValues['ContractorReference'],Auth::user()->Id));
			}else{
				$mailIntendedTo=1;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
				$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array(Input::get('ContractorReference')));
				$emailMessage="Thank you for your application. We are glad to inform you that your application for availing service has been approved by CDB. However, you need to pay your registration fees within one month (30 days) to CDB office or Nearest Regional Revenue and Customs Office (RRCO).Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
				$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
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

		if($hasFee){
			$message = "The application has been successfully approved for payment.";
		}else{
			$message = "The application has been successfully approved.";
		}
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('contractor/approveserviceapplicationlist')
						->with('savedsuccessmessage',$message);
	}
	public function approvePaymentServiceApplicationRegistration(){
		$postedValues=Input::except('crpcontractorservicepayment','crpcontractorservicepaymentdetail','latefeedetails');
		$contractorServicePaymentValues = Input::get('crpcontractorservicepayment');
		$contractorServicePaymentDetailValues = Input::get('crpcontractorservicepaymentdetail');
		$lateFeeDetailValues = Input::get('latefeedetails');

		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);

		$contractorAppliedServices = DB::table('crpcontractorappliedservice')->where('CrpContractorId',$postedValues['ContractorReference'])->lists('CmnServiceTypeId');

		/* FOR FEE STRUCTURE */
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
        $hasReregistration=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$hasWaiver = 0;
		$newLateFeeAmount = 0;
		$contractorFinalTableId=contractorModelContractorId($postedValues['ContractorReference']);
		$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFinalId',$contractorFinalTableId)->pluck('MaxClassificationPriority');
		$appliedServices=ContractorAppliedServiceModel::appliedService($postedValues['ContractorReference'])->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));
		/* END FOR FEE STRUCTURE */
		DB::beginTransaction();
		try{
			$postedValues['CmnApplicationRegistrationStatusId'] = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance=ContractorModel::find($postedValues['ContractorReference']);
			$instance->fill($postedValues);
			$instance->update();

			/*To save fee structure*/
			foreach($contractorServicePaymentValues as $key=>$value):
				foreach($value as $x=>$y):
					$feeStructureArray[$x] = $y;
				endforeach;
				$feeStructureArray['Id'] = $this->UUID();
				$feeStructureArray['CrpContractorId'] = $postedValues['ContractorReference'];
				if($feeStructureArray['CmnServiceTypeId'] == CONST_SERVICETYPE_LATEFEE):
					foreach($lateFeeDetailValues as $key=>$value):
						$feeStructureArray[$key] = $value;
					endforeach;
				endif;
				ContractorServicePayment::create($feeStructureArray);

				if(in_array(CONST_SERVICETYPE_RENEWAL,$contractorAppliedServices)){
					if($feeStructureArray['CmnServiceTypeId'] == CONST_SERVICETYPE_RENEWAL)
						$parentTableId = $feeStructureArray['Id'];
				}else{
					if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$contractorAppliedServices)){
						if($feeStructureArray['CmnServiceTypeId'] == CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
							$parentTableId = $feeStructureArray['Id'];
					}
				}

				$feeStructureArray = array();
			endforeach;
			if(count($contractorServicePaymentDetailValues)>0):
				foreach($contractorServicePaymentDetailValues as $key=>$value):
					foreach($value as $a=>$b):
						if($b != NULL)
							$feeStructureDetailArray[$a] = $b;
					endforeach;
					$feeStructureDetailArray['Id'] = $this->UUID();
					$feeStructureDetailArray['CrpContractorServicePaymentId'] = $parentTableId;
					ContractorServicePaymentDetail::create($feeStructureDetailArray);
					$feeStructureDetailArray = array();
				endforeach;
			endif;
			/*END*/
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$uuidContractor=DB::select("select uuid() as Id");
			$uuidContractorId=$uuidContractor[0]->Id;
			$contractorDetails=ContractorModel::contractorHardList(Input::get('ContractorReference'))->get(array('CrpContractorId','CmnOwnershipTypeId','NameOfFirm','CDBNo','Address','CmnCountryId','CmnDzongkhagId','MobileNo','TelephoneNo','TPN','FaxNo','RegistrationApprovedDate','RegistrationExpiryDate','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount'));
			$hasWaiver = $contractorDetails[0]->WaiveOffLateFee;
			$newLateFeeAmount = $contractorDetails[0]->NewLateFeeAmount;
			/*----------------------Contractor Email Details and New Details------------------*/
			$finalContractorId=$contractorDetails[0]->CrpContractorId;
			$CDBNo=$contractorDetails[0]->CDBNo;
			$recipientAddress=$contractorDetails[0]->Email;
			$recipientName=$contractorDetails[0]->NameOfFirm;
			$applicationNo=$contractorDetails[0]->ReferenceNo;
			$applicationDate=$contractorDetails[0]->ApplicationDate;
			$mobileNo=$contractorDetails[0]->MobileNo;
			$tpn=$contractorDetails[0]->TPN;

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
				$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($postedValues['ContractorReference'],$contractorFinalTableId,$postedValues['ContractorReference'],$postedValues['ContractorReference'],$postedValues['ContractorReference'],$contractorFinalTableId));
			}
            $registrationExpiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('RegistrationExpiryDate');
            $reregistrationDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('ReRegistrationDate');
            if((bool)$reregistrationDate){
                if($reregistrationDate>$registrationExpiryDate){
                    $hasReregistration = true;
                }
            }
            if($hasLateFee){
                if($hasReregistration) {
                    $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.ReregistrationDate as RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.ReregistrationDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($postedValues['ContractorReference']));
                }else{
                    $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($postedValues['ContractorReference']));
                }
            }
//			if($hasLateFee){
//				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($postedValues['ContractorReference']));
//			}
			/* END FOR FEE STRUCTURE */

			/*----------------------End of Contractor Email Details and New Details------------------*/
			DB::table('crpcontractorequipmentfinal')->where('CrpContractorFinalId',$finalContractorId)->where('DeleteRequest',1)->update(array('DeleteRequest'=>0));
			DB::table('crpcontractorhumanresourcefinal')->where('CrpContractorFinalId',$finalContractorId)->where('DeleteRequest',1)->update(array('DeleteRequest'=>0));
			DB::statement("call ProCrpContractorUpdateFinalData(?,?,?)",array($finalContractorId,$postedValues['ContractorReference'],Auth::user()->Id));
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailIntendedTo=NULL;
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Confirmation of Your Payment for CDB Services";
		$mailData=array(
			'mailIntendedTo'=>1,
			'tpn'=>$tpn,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'appliedServices'=>$appliedServices,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'maxClassification'=>$maxClassification,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No.".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for registration of your firm (".$recipientName.") with Construction Development Board (CDB). Your CDB No. is ".$CDBNo.". Your CDB certificate has been activated."
		);

		$smsMessage="Your application for avaling services of CDB has been successfully approved.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('contractor/approveserviceapplicationfeepaymentlist')->with('savedsuccessmessage','Payment against the registration successfully recorded.');
	}
	public function approveCertificateCancellationList(){
		$CDBNoAll=Input::get('CrpContractorIdAll');
		$contractorIdAll=Input::get('CrpContractorIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$CDBNoMyTask=Input::get('CrpContractorIdAll');
		$contractorIdMyTask=Input::get('CrpContractorIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select distinct T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 join crpcontractorcertificatecancellationrequest T10 on T1.Id=T10.CrpContractorFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId is null";
		$queryMyTaskList="select distinct T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 join crpcontractorcertificatecancellationrequest T10 on T1.Id=T10.CrpContractorFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$CDBNoAll!=NULL || (bool)$contractorIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$contractorIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$CDBNoAll!=NULL){
				$query.=" and T1.CDBNo=?";
				array_push($parameters,$CDBNoAll);
			}
			if((bool)$contractorIdAll!=NULL){
				$query.=" and T10.Id=?";
				array_push($parameters,$contractorIdAll);
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
			if((bool)$CDBNoMyTask!=NULL){
				$query.=" and T1.CDBNo=?";
				array_push($parametersMyTaskList,$CDBNoMyTask);
			}
			if((bool)$contractorIdMyTask!=NULL){
				$query.=" and T10.Id=?";
				array_push($parametersMyTaskList,$contractorIdMyTask);
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
		$contractorLists=DB::select($query." group by T1.Id order by T10.ApplicationDate,T10.ReferenceNo,NameOfFirm",$parameters);
		$contractorMyTaskLists=DB::select($queryMyTaskList." group by T1.Id order by T10.ApplicationDate,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.contractorserviceapplicationcancellationlist')
					->with('CDBNoAll',$CDBNoAll)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('contractorIdAll',$contractorIdAll)
					->with('CDBNoMyTask',$CDBNoMyTask)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('contractorIdMyTask',$contractorIdMyTask)
					->with('contractorLists',$contractorLists)
					->with('contractorMyTaskLists',$contractorMyTaskLists);
	}
	public function approveCancelCertificateRequest($contractorId,$cancelRequestId){
		$generalInformation=ContractorCancelCertificateModel::cancellationList($contractorId,$cancelRequestId)->get(array('crpcontractorcertificatecancellationrequest.Id as CancelRequestId','crpcontractorcertificatecancellationrequest.AttachmentFilePath','crpcontractorcertificatecancellationrequest.ReasonForCancellation','crpcontractorcertificatecancellationrequest.ReferenceNo','crpcontractorcertificatecancellationrequest.ApplicationDate','T1.CDBNo','T1.NameOfFirm','T1.Address','T1.Email','T1.TelephoneNo','T1.MobileNo','T1.FaxNo','T2.Name as Country','T3.NameEn as Dzongkhag'));
		return View::make('crps.contractorcertificatecancellationconfirmation')
					->with('contractorId',$contractorId)
					->with('cancelRequestId',$cancelRequestId)
					->with('generalInformation',$generalInformation);
	}
	public function approveCancellation(){
		$reject = Input::get('Reject');
		$cancelRequestId=Input::get('CancelRequestId');
		$contractorReference=Input::get("ContractorReference");
		$contractorUserId=ContractorFinalModel::where('Id',$contractorReference)->pluck('SysUserId');
		$message = 'Certificate has been successsfully canceled.';
		DB::beginTransaction();
		try{
			if((int)$reject == 1){
				$instance=ContractorCancelCertificateModel::find($cancelRequestId);
				$instance->SysLockedByUserId="";
				$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
				$instance->RemarksByRejector=Input::get('RemarksByApprover');
				$instance->SysRejectorUserId=Auth::user()->Id;
				$instance->save();
				$message = 'Certificate cancellation has been rejected.';

				$userDetails = DB::table('sysuser')->where('Id',$contractorUserId)->get(array('FullName','Email','ContactNo'));
				$recipientAddress = $userDetails[0]->Email;
				$mobileNo = $userDetails[0]->ContactNo;
				$recipientName = $userDetails[0]->FullName;
				$mailData=array(
					'mailMessage'=>"Construction Development Board (CDB) has rejected your application for cancellation of your CDB certificate.<br/>Reason: ".Input::get('RemarksByApprover')
				);
				$smsMessage="Your application for certificate cancellation has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Rejection of Cancellation Request",$recipientAddress,$recipientName);
				$this->sendSms($smsMessage,$mobileNo);
			}else{
				$instance=ContractorCancelCertificateModel::find($cancelRequestId);
				$instance->SysLockedByUserId="";
				$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
				$instance->RemarksByApprover=Input::get('RemarksByApprover');
				$instance->SysApproverUserId=Auth::user()->Id;
				$instance->save();

				$contractorFinalReference=ContractorFinalModel::find($contractorReference);
				$contractorFinalReference->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED;
				$contractorFinalReference->DeregisteredRemarks=Input::get('RemarksByApprover');
				$contractorFinalReference->DeRegisteredDate=date('Y-m-d');
				$contractorFinalReference->save();

				$userInstance=User::find($contractorUserId);
				$userInstance->Status=0;
				$userInstance->save();

				$userDetails = DB::table('sysuser')->where('Id',$contractorUserId)->get(array('FullName','Email','ContactNo'));
				$recipientAddress = $userDetails[0]->Email;
				$mobileNo = $userDetails[0]->ContactNo;
				$recipientName = $userDetails[0]->FullName;
				$mailData=array(
					'mailMessage'=>"Construction Development Board (CDB) has approved your application for cancellation of your CDB certificate.<br/>Remarks: ".Input::get('RemarksByApprover')
				);
				$smsMessage="Your application for contractor registration has been approved. Please check your email ($recipientAddress) for more details.";
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Approval of Cancellation Request",$recipientAddress,$recipientName);
				$this->sendSms($smsMessage,$mobileNo);
			}

		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('contractor/approvecertificatecancellationrequestlist')->with('savedsuccessmessage',$message);
	}
	public function lockApplicationCancellationRequest($cancelRequestId){
		$cancellationRequest=ContractorCancelCertificateModel::find($cancelRequestId);
		$cancellationRequest->SysLockedByUserId=Auth::user()->Id;
		$cancellationRequest->save();
		return Redirect::to('contractor/approvecertificatecancellationrequestlist');
	}
	public function viewList(){
		$contractorIdMyTask=Input::get('CrpContractorIdMyTask');
		$CDBNoMyTask=Input::get('CDBNoMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');

		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T7.CDBNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(distinct ST.Name separator ',<br/> ') as ServiceApplied,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.CrpContractorId is not null";

		$pageTitle="Final Approval of Service Application";
		$pageTitleHelper="All the applications listed below has been approved and paid.";
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

		if((bool)$contractorIdMyTask!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$contractorIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$contractorIdMyTask);
			}
			if((bool)$CDBNoMyTask!=NULL){
				$queryMyTaskList.=" and T7.CDBNo=?";
				array_push($parameters,$CDBNoMyTask);
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
		$queryMyTaskList.=" and T1.SysFinalApproverUserId is null group by T1.Id order by ApplicationDate,NameOfFirm";
		/*PAGINATION*/
		$pageNo = Input::has('page')?Input::get('page'):1;
		$pagination = $this->pagination($queryMyTaskList,$parametersMyTaskList,10,$pageNo);
		$limitOffsetAppend = $pagination['LimitAppend'];
		$noOfPages = $pagination['NoOfPages'];
		$start = $pagination['Start'];
		/*END PAGINATION*/
		$contractorMyTaskLists=DB::select("$queryMyTaskList$limitOffsetAppend",$parametersMyTaskList);
		return View::make('crps.contractorserviceapplicationviewlist')
			->with('start',$start)
			->with('noOfPages',$noOfPages)
			->with('route',"contractor.viewserviceapplication")
			->with('pageTitle',$pageTitle)
			->with('pageTitleHelper',$pageTitleHelper)
			->with('fromDateMyTask',convertDateToClientFormat($fromDateMyTask))
			->with('toDateMyTask',convertDateToClientFormat($toDateMyTask))
			->with('contractorIdMyTask',$contractorIdMyTask)
			->with('CDBNoMyTask',$CDBNoMyTask)
			->with('contractorMyTaskLists',$contractorMyTaskLists);
	}
	public function editServices(){
		$contractorIdMyTask=Input::get('ContractorId');
		$CDBNoMyTask=Input::get('CDBNoMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');

		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T7.CDBNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(distinct ST.Name separator ',<br/> ') as ServiceApplied,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.RegistrationStatus = 1 and T1.CrpContractorId is not null";

		$pageTitle="Edit Service Applications";
		$pageTitleHelper="";
		$parametersMyTaskList = array();
		if((bool)$contractorIdMyTask!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$contractorIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CrpContractorId=?";
				array_push($parametersMyTaskList,$contractorIdMyTask);
			}
			if((bool)$CDBNoMyTask!=NULL){
				$queryMyTaskList.=" and T7.CDBNo=?";
				array_push($parameters,$CDBNoMyTask);
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
		$queryMyTaskList.=" and T1.SysFinalApproverUserId is null group by T1.Id order by ApplicationDate,NameOfFirm";
		/*PAGINATION*/
		$pageNo = Input::has('page')?Input::get('page'):1;
		$pagination = $this->pagination($queryMyTaskList,$parametersMyTaskList,10,$pageNo);
		$limitOffsetAppend = $pagination['LimitAppend'];
		$noOfPages = $pagination['NoOfPages'];
		$start = $pagination['Start'];
		/*END PAGINATION*/
		$contractorMyTaskLists=DB::select("$queryMyTaskList$limitOffsetAppend",$parametersMyTaskList);
		return View::make('crps.contractorserviceapplicationviewlist')
			->with('start',$start)
			->with('noOfPages',$noOfPages)
			->with('isEditService',true)
			->with('route',"contractor.editservices")
			->with('pageTitle',$pageTitle)
			->with('pageTitleHelper',$pageTitleHelper)
			->with('fromDateMyTask',convertDateToClientFormat($fromDateMyTask))
			->with('toDateMyTask',convertDateToClientFormat($toDateMyTask))
			->with('contractorIdMyTask',$contractorIdMyTask)
			->with('CDBNoMyTask',$CDBNoMyTask)
			->with('contractorMyTaskLists',$contractorMyTaskLists);
	}
	public function editServicesDetail($id){
		$applicationDetails = DB::table('crpcontractor as T1')
								->join('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorId')
								->where('T1.Id',$id)
								->get(array('T1.Id','T2.CDBNo','T2.NameOfFirm','T1.ReferenceNo','T1.ApplicationDate'));
		if(count($applicationDetails) == 0){
			return Redirect::to("contractor/editservices")->with('customerrormessage','Application does not exist');
		}
		$services = DB::table('crpservice as T1')
						->leftJoin('crpcontractorappliedservice as T2',function($join) use($id){
							$join->on('T2.CmnServiceTypeId','=',"T1.Id")
								 ->on("T2.CrpContractorId",'=',DB::raw("'$id'"));
						})
						->orderBy('T1.ReferenceNo')
						->whereNotIn('T1.ReferenceNo',array(1,3))
						->get(array('T1.Name as Service','T1.Id','T2.CmnServiceTypeId as Check'));
		return View::make('crps.contractoreditservicesdetail')
					->with('applicationDetails',$applicationDetails)
					->with('services',$services);
	}
	public function saveServicesDetail(){
		$crpContractorId = Input::get('Id');
		$detailInputs = Input::get('detailtable');
		DB::beginTransaction();
		try{
			DB::table('crpcontractorappliedservice')->where('CrpContractorId',$crpContractorId)->delete();
			foreach($detailInputs as $key=>$value):
				foreach($value as $x=>$y):
					$detailArray[$x] = $y;
					$detailArray['Id'] = $this->UUID();
					$detailArray['CrpContractorId'] = $crpContractorId;
					$detailArray['CreatedBy'] = Auth::user()->Id;
					$detailArray['CreatedOn'] = date('Y-m-d G:i:s');
					DB::table('crpcontractorappliedservice')->insert($detailArray);
				endforeach;
			endforeach;
		}catch(Exception $e){
			DB::rollBack();
		}
		DB::commit();
		return Redirect::to('contractor/editservices')->with('savedsuccessmessage','<strong>SUCCESS! </strong> Application has been updated');
	}
	public function deleteApplication($id){
		DB::beginTransaction();
		try{
			$applicationDetails = DB::table('crpcontractor as T1')
				->join('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorId')
				->where('T1.Id',$id)
				->get(array('T1.Id','T2.CDBNo','T2.NameOfFirm','T1.ReferenceNo','T1.ApplicationDate'));
			$applicationNo = $applicationDetails[0]->ReferenceNo;
			$applicationDate = convertDateToClientFormat($applicationDetails[0]->ApplicationDate);
			$firm = $applicationDetails[0]->NameOfFirm." (".$applicationDetails[0]->CDBNo.")";
			DB::table('crpcontractorhumanresource')->where('CrpContractorId',$id)->delete();
			DB::table('crpcontractorequipment')->where('CrpContractorId',$id)->delete();
			DB::table('crpcontractorworkclassification')->where('CrpContractorId',$id)->delete();
			DB::table('crpcontractor')->where('Id',$id)->delete();
		}catch(Exception $e){
			DB::rollBack();
			return Redirect::to('contractor/editservices')->with('customerrormessage','<strong>ERROR! </strong>',$e->getMessage());
		}
		DB::commit();
		return Redirect::to('contractor/editservices')->with('savedsuccessmessage',"<strong>SUCCESS! </strong> Application $applicationNo applied on $applicationDate of $firm has been deleted");
	}
}