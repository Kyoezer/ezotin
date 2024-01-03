<?php
class ConsultantServiceApplication extends CrpsController{
	public function verifyApproveList(){
		$consultantIdAll=Input::get('CrpConsultantIdAll');
		$CDBNoAll=Input::get('CDBNoAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$consultantIdMyTask=Input::get('CrpConsultantIdMyTask');
		$CDBNoMyTask=Input::get('CDBNoMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T7.InitialDate,T7.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(T6.Name separator ',<br /> ') as ServiceApplied from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T7.InitialDate,T7.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(T6.Name separator ',<br /> ') as ServiceApplied from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where coalesce(T1.CmnApplicationRegistrationStatusId,'".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."')=? and T1.SysLockedByUserId=? and T1.CrpConsultantId is not null";
		$redirectUrl = Request::path();
		if(Request::path()=="consultant/verifyserviceapplicationlist"){
			$pageTitle="Verify Service Application";
			$pageTitleHelper="All the applications listed below are new applications";
			$recordLockReditectUrl="consultant/verifyserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		}elseif(Request::path()=="consultant/approveserviceapplicationlist"){
			$pageTitle="Approve Service Application";
			$pageTitleHelper="All the applications listed below are verified applications";
			$recordLockReditectUrl="consultant/approveserviceapplicationlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}elseif(Request::path()=="consultant/approveserviceapplicationfeepaymentlist"){
            $query.=" and case when T1.Id = '59e984bd-80b9-11e7-a051-0026b988eaa2' then 1=1 else case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end end";
            $queryMyTaskList.=" and case when T1.Id = '59e984bd-80b9-11e7-a051-0026b988eaa2' then 1=1 else case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end end";
			$pageTitle="Approve Fee Payment for Service";
			$pageTitleHelper="All the applications listed below has been approved and awating to receive the payments.";
			$recordLockReditectUrl="consultant/approveserviceapplicationfeepaymentlist";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			App::abort('404');
		}
		if((bool)$consultantIdAll!=NULL || (bool)$CDBNoAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$consultantIdMyTask!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$consultantIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantIdAll);
			}
			if((bool)$CDBNoAll!=NULL){
				$query.=" and T7.CDBNo=?";
				array_push($parameters,$CDBNoAll);
			}
			if((bool)$fromDateAll!=NULL){
				$fromDateAll=$this->convertDate($fromD->sendateAll);
				$query.=" and T1.ApplicationDate>=?";
	            array_push($parameters,$fromDateAll);
			}
			if((bool)$toDateAll!=NULL){
				$toDateAll=$this->convertDate($toDateAll);
				$query.=" and T1.ApplicationDate<=?";
	            array_push($parameters,$toDateAll);
			}
			if((bool)$consultantIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$consultantIdMyTask);
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
		$consultantrLists=DB::select($query." group by T1.Id order by ApplicationDate,ReferenceNo,NameOfFirm",$parameters);
		$consultantMyTaskLists=DB::select($queryMyTaskList." group by T1.Id order by ApplicationDate,ReferenceNo,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.consultantserviceapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',$pageTitle)
					->with('recordLockReditectUrl',$recordLockReditectUrl)
					->with('pageTitleHelper',$pageTitleHelper)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('consultantIdAll',$consultantIdAll)
					->with('CDBNoAll',$CDBNoAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('consultantIdMyTask',$consultantIdMyTask)
					->with('CDBNoMyTask',$CDBNoMyTask)
					->with('consultantLists',$consultantrLists)
					->with('consultantMyTaskLists',$consultantMyTaskLists);
	}
	public function serviceApplicationDetails($consultantId,$forReport = false){
		$serviceApplicationApprovedForPayment=0;
		$serviceApplicationApproved = 0;
		$hasFee=false;
		$hasRenewal=false;
		$hasLateFee=false;
		$hasChangeInCategoryClassification=false;
		$hasCategoryClassificationsFee=array();
		$hasLateFeeAmount=array();
		$existingCategoryServicesArray  = array();
		$appliedCategoryServicesArray  = array();
		$verifiedCategoryServicesArray  = array();
		$approvedCategoryServicesArray  = array();
		$consultantFinalTableId=consultantModelConsultantId($consultantId);
		/*-----------------------------------------------------------------------------------------------------*/
		$feeAmount=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
                $changeFeeAmount = CrpService::serviceDetails(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)->pluck('ConsultantAmount');
		$newRegistrationAmount = CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->pluck('ConsultantAmount');
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
			$hasCategoryClassificationsFee=DB::select("select T1.Name as ServiceCategoryName,T1.Id as ServiceCategoryId,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(distinct T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(distinct T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($consultantId,$consultantId,$consultantId));
		}
		if($hasLateFee){
			$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($consultantId));
		}
		/*------------------------------------------------------------------------------------------------------*/
		//$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_RENEWAL)->pluck('ConsultantValidity');
		if(Route::current()->getUri()=="consultant/verifyserviceapplicationprocess/{consultantid}"){
			$view="crps.consultantverifyserviceapplicationprocess";
			$modelPost="consultant/mverifyserviceapplication";
		}elseif(Route::current()->getUri()=="consultant/approveserviceapplicationprocess/{consultantid}"){
			$view="crps.consultantapproveserviceapplicationprocess";
			$modelPost="consultant/mapproveserviceapplication";
		}elseif(Route::current()->getUri()=="consultant/approveserviceapplicationpaymentprocess/{consultantid}"){
			$serviceApplicationApprovedForPayment=1;
			$view="crps.consultantserviceapplicationapprovepayment";
			$modelPost="consultant/mapprovepaymentserviceapplication";
		}elseif(Route::current()->getUri()=="consultant/viewserviceapplicationdetails/{consultantid}"){
			$serviceApplicationApprovedForPayment=1;
			$serviceApplicationApproved=1;
			$view="crps.consultantserviceapplicationapprovepayment";
			$modelPost="consultant/mapprovepaymentserviceapplication";
		}else{
		    if(!$forReport){
                App::abort('404');
            }
		}

		/*---*/
		foreach($hasCategoryClassificationsFee as $singleCategory):
			$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
			$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
			$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
		endforeach;
                //echo "<PRE>"; dd($existingCategoryServicesArray);
		/*---*/
		$consultantServices=ConsultantServiceModel::service()->get(array('Id','Code','Name','CmnConsultantServiceCategoryId'));
		$appliedCategories=ConsultantWorkClassificationModel::serviceCategory($consultantId)->get(array('T1.Id','T1.Name as Category'));
		$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService($consultantId)->get(array(DB::raw('distinct T1.Id as ServiceId'),'crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Code as ServiceCode','T1.Name as ServiceName'));
		$verifiedCategoryServices=ConsultantWorkClassificationModel::verifiedService($consultantId)->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
		$currentServiceClassifications=ConsultantWorkClassificationFinalModel::services($consultantFinalTableId)->select(DB::raw("T1.Name as Category,group_concat(distinct T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
		$generalInformation=ConsultantModel::consultant($consultantId)->get(array('crpconsultant.Id','crpconsultant.PaymentReceiptNo', 'crpconsultant.PaymentReceiptDate','crpconsultant.ReferenceNo','crpconsultant.ApplicationDate','crpconsultant.CDBNo','crpconsultant.NameOfFirm','crpconsultant.RegisteredAddress','crpconsultant.Village','crpconsultant.Gewog','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','crpconsultant.VerifiedDate','crpconsultant.RemarksByVerifier','crpconsultant.RemarksByApprover','crpconsultant.RegistrationApprovedDate','crpconsultant.RemarksByPaymentApprover','crpconsultant.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T8.NameEn as RegisteredDzongkhag','crpconsultant.WaiveOffLateFee','crpconsultant.NewLateFeeAmount',));
		$ownerPartnerDetails=ConsultantHumanResourceModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Name','crpconsultanthumanresource.Sex','crpconsultanthumanresource.ShowInCertificate','crpconsultanthumanresource.Verified','crpconsultanthumanresource.Approved','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','crpconsultanthumanresource.Verified','crpconsultanthumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','crpconsultantequipment.Verified','crpconsultantequipment.Approved','T1.Name','T1.VehicleType'));
		$consultantHumanResourceAttachments=ConsultantHumanResourceAttachmentModel::singleConsultantHumanResourceAllAttachments($consultantId)->get(array('crpconsultanthumanresourceattachment.DocumentName','crpconsultanthumanresourceattachment.DocumentPath','crpconsultanthumanresourceattachment.CrpConsultantHumanResourceId'));
		$consultantEquipmentAttachments=ConsultantEquipmentAttachmentModel::singleConsultantEquipmentAllAttachments($consultantId)->get(array('crpconsultantequipmentattachment.DocumentName','crpconsultantequipmentattachment.DocumentPath','crpconsultantequipmentattachment.CrpConsultantEquipmentId'));
		$serviceApplicationsAttachments=ConsultantAttachmentModel::attachment($consultantId)->get(array('DocumentName','DocumentPath'));
		/*------------------------------End of record applied by the applicant----------------------------*/
		$generalInformationFinal=ConsultantFinalModel::consultant($consultantFinalTableId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.RegistrationApprovedDate','crpconsultantfinal.RegistrationExpiryDate','crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.RegisteredAddress','crpconsultantfinal.Village','crpconsultantfinal.Gewog','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','crpconsultantfinal.RegistrationExpiryDate','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetailsFinal=ConsultantHumanResourceFinalModel::consultantPartner($consultantFinalTableId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResourcesFinal=ConsultantHumanResourceFinalModel::consultantHumanResource($consultantFinalTableId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.DeleteRequest','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$consultantEquipmentsFinal=ConsultantEquipmentFinalModel::consultantEquipment($consultantFinalTableId)->get(array('crpconsultantequipmentfinal.Id','crpconsultantequipmentfinal.RegistrationNo','crpconsultantequipmentfinal.ModelNo','crpconsultantequipmentfinal.Quantity','crpconsultantequipmentfinal.DeleteRequest','T1.Name','T1.VehicleType'));
		$consultantHumanResourceAttachmentsFinal=ConsultantHumanResourceAttachmentFinalModel::singleConsultantHumanResourceAllAttachments($consultantFinalTableId)->get(array('crpconsultanthumanresourceattachmentfinal.DocumentName','crpconsultanthumanresourceattachmentfinal.DocumentPath','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId'));
		$consultantEquipmentAttachmentsFinal=ConsultantEquipmentAttachmentFinalModel::singleConsultantEquipmentAllAttachments($consultantFinalTableId)->get(array('crpconsultantequipmentattachmentfinal.DocumentName','crpconsultantequipmentattachmentfinal.DocumentPath','crpconsultantequipmentattachmentfinal.CrpConsultantEquipmentFinalId'));

		if($forReport){
		    $reportDetails['hasFee'] = $hasFee;
		    $reportDetails['hasLateFee'] = $hasLateFee;
		    $reportDetails['hasLateFeeAmount'] = $hasLateFeeAmount;
		    $reportDetails['hasRenewal'] = $hasRenewal;
		    $reportDetails['existingCategoryServicesArray'] = $existingCategoryServicesArray;
		    $reportDetails['appliedCategoryServicesArray'] = $appliedCategoryServicesArray;
		    $reportDetails['verifiedCategoryServicesArray'] = $verifiedCategoryServicesArray;
		    $reportDetails['approvedCategoryServicesArray'] = $approvedCategoryServicesArray;
		    $reportDetails['hasChangeInCategoryClassification'] = $hasChangeInCategoryClassification;
		    $reportDetails['hasCategoryClassificationsFee'] = $hasCategoryClassificationsFee;
		    $reportDetails['feeAmount'] = $feeAmount;
		    $reportDetails['newRegistrationAmount'] = $newRegistrationAmount;
		    $reportDetails['appliedCategories'] = $appliedCategories;
		    $reportDetails['consultantServices'] = $consultantServices;
		    $reportDetails['appliedCategoryServices'] = $appliedCategoryServices;
		    $reportDetails['verifiedCategoryServices'] = $verifiedCategoryServices;
		    $reportDetails['appliedServices'] = $appliedServices;
		    $reportDetails['currentServiceClassifications'] = $currentServiceClassifications;
		    $reportDetails['generalInformation'] = $generalInformation;
		    $reportDetails['generalInformationFinal'] = $generalInformationFinal;

		    return $reportDetails;
        }

		return View::make($view)
					->with('modelPost',$modelPost)
					->with('hasFee',$hasFee)
					->with('hasLateFee',$hasLateFee)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('hasRenewal',$hasRenewal)
					->with('existingCategoryServicesArray',$existingCategoryServicesArray)
					->with('appliedCategoryServicesArray',$appliedCategoryServicesArray)
					->with('verifiedCategoryServicesArray',$verifiedCategoryServicesArray)
					->with('approvedCategoryServicesArray',$approvedCategoryServicesArray)
					->with('hasChangeInCategoryClassification',$hasChangeInCategoryClassification)
					->with('hasCategoryClassificationsFee',$hasCategoryClassificationsFee)
					->with('feeAmount',$feeAmount)
					->with('newRegistrationAmount',$newRegistrationAmount)
					->with('appliedCategories',$appliedCategories)
					->with('consultantServices',$consultantServices)				
					->with('appliedCategoryServices',$appliedCategoryServices)
					->with('verifiedCategoryServices',$verifiedCategoryServices)
					->with('appliedServices',$appliedServices)
					->with('serviceApplicationApprovedForPayment',$serviceApplicationApprovedForPayment)
					->with('serviceApplicationApproved',$serviceApplicationApproved)
					->with('consultantId',$consultantId)
					->with('currentServiceClassifications',$currentServiceClassifications)
					->with('generalInformation',$generalInformation)
					->with('generalInformationFinal',$generalInformationFinal)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('ownerPartnerDetailsFinal',$ownerPartnerDetailsFinal)
					->with('consultantHumanResources',$consultantHumanResources)
					->with('consultantHumanResourcesFinal',$consultantHumanResourcesFinal)
					->with('consultantEquipments',$consultantEquipments)
					->with('consultantEquipmentsFinal',$consultantEquipmentsFinal)
					->with('consultantHumanResourceAttachments',$consultantHumanResourceAttachments)
					->with('consultantHumanResourceAttachmentsFinal',$consultantHumanResourceAttachmentsFinal)
					->with('consultantEquipmentAttachments',$consultantEquipmentAttachments)
					->with('consultantEquipmentAttachmentsFinal',$consultantEquipmentAttachmentsFinal)
					->with('serviceApplicationsAttachments',$serviceApplicationsAttachments);
	}
	public function verifyServiceApplicationRegistration(){
		$postedValues=Input::all();
		DB::beginTransaction();
		try{
			$instance=ConsultantModel::find($postedValues['ConsultantReference']);
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
		return Redirect::to('consultant/verifyserviceapplicationlist')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveServiceApplicationRegistration(){
		$postedValues=Input::except('Id');
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		//Commented by SWM
		if(isset($postedValues['RegistrationExpiryDate']))
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		//END
		DB::beginTransaction();
		try{
			$instance=ConsultantModel::find($postedValues['ConsultantReference']);
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
			$newRegistrationAmount = CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->pluck('ConsultantAmount');
			$consultantDetails=ConsultantModel::consultantHardList(Input::get('ConsultantReference'))->get(array('CrpConsultantId','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount','RemarksByApprover','RemarksByVerifier'));
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
			$consultantFinalTableId=consultantModelConsultantId(Input::get('ConsultantReference'));
			/*-----------------------------------------------------------------------------------------------------*/
			$feeAmount=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$appliedServices=ConsultantAppliedServiceModel::appliedService(Input::get('ConsultantReference'))->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
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
				$hasCategoryClassificationsFee=DB::select("select T1.Id as ServiceCategoryId,T1.Name as ServiceCategoryName,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array(Input::get('ConsultantReference'),Input::get('ConsultantReference'),Input::get('ConsultantReference')));
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array(Input::get('ConsultantReference')));
			}
			/* ENd fee structure */

			/*---*/
            foreach($hasCategoryClassificationsFee as $singleCategory):
                $existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
                $appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',Input::get('ConsultantReference'))->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
                $verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',Input::get('ConsultantReference'))->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
                $approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',Input::get('ConsultantReference'))->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
            endforeach;
			/*---*/

			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$mailIntendedTo=2;
				$feeStructures=array();
				$serviceCategories=array();
				$approvedCategoryServices=array();
				$verifiedCategoryServices=array();
				$appliedCategoryServices=array();
				$smsMessage="Your application for avaling services of CDB has been successfully approved.";
				$emailMessage="This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for the requested services of your firm.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
				DB::statement("call ProCrpConsultantUpdateFinalData(?,?,?)",array($finalConsultantId,Input::get('ConsultantReference'),Auth::user()->Id));
			}else{
				$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
				$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
				$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
				$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService(Input::get('ConsultantReference'))->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Id as ServiceId','T1.Code as ServiceCode','T1.Name as ServiceName'));
				$verifiedCategoryServices=ConsultantWorkClassificationModel::verifiedService(Input::get('ConsultantReference'))->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
				$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnApprovedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
				$smsMessage="Your application for avaling services of CDB has been successfully approved. Please check your email for detailed information regarding your fees.";
				$emailMessage="Construction Development Board (CDB) has verified and approved your application for the requested services.  However, you need to pay your fees within one month (30 days) as per the details given below to CDB office or the Nearest Regional Revenue and Customs Office (RRCO). Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt. We will email you your username and password upon confirmation of your payment by CDB.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover";
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
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
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		if($hasFee){
			$message = "The application has been successfully approved for payment.";
		}else{
			$message = "The application has been successfully approved.";
		}
		return Redirect::to('consultant/approveserviceapplicationlist')
						->with('savedsuccessmessage',$message);
	}
	public function approvePaymentServiceApplicationRegistration(){
		$postedValues=Input::except('crpconsultantservicepaymentdetail','crpconsultantservicepayment','latefeedetails');

		$consultantServicePaymentValues = Input::get('crpconsultantservicepayment');
		$consultantServicePaymentDetails = Input::get('crpconsultantservicepaymentdetail');


		$lateFeeDetailValues = Input::get('latefeedetails');

		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		$consultantAppliedServices = DB::table('crpconsultantappliedservice')->where('CrpConsultantId',$postedValues['ConsultantReference'])->lists('CmnServiceTypeId');
		DB::beginTransaction();
		try{
			$postedValues['CmnApplicationRegistrationStatusId'] = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance=ConsultantModel::find($postedValues['ConsultantReference']);
			$instance->fill($postedValues);
			$instance->update();

			/*To save fee structure*/
			foreach($consultantServicePaymentValues as $key=>$value):
				foreach($value as $x=>$y):
					$feeStructureArray[$x] = $y;
				endforeach;
				$feeStructureArray['Id'] = $this->UUID();
				$feeStructureArray['CrpConsultantId'] = $postedValues['ConsultantReference'];
				if($feeStructureArray['CmnServiceTypeId'] == CONST_SERVICETYPE_LATEFEE):
					foreach($lateFeeDetailValues as $key=>$value):
						$feeStructureArray[$key] = $value;
					endforeach;
				endif;
				//Code to save
				ConsultantServicePayment::create($feeStructureArray);

				if(in_array(CONST_SERVICETYPE_RENEWAL,$consultantAppliedServices)){
					$parentTableId = $feeStructureArray['Id'];
				}else{
					if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$consultantAppliedServices)){
						$parentTableId = $feeStructureArray['Id'];
					}
				}

				$feeStructureArray = array();
			endforeach;
			if(isset($parentTableId)){
				foreach($consultantServicePaymentDetails as $key=>$value):
					foreach($value as $a=>$b):
						$paymentDetailsArray[$a] = $b;
					endforeach;
					$paymentDetailsArray['CrpConsultantServicePaymentId'] = $parentTableId;
					ConsultantServicePaymentDetail::create($paymentDetailsArray);
					$paymentDetailsArray = array();
				endforeach;
			}
			/*END*/

			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$uuidConsultant=DB::select("select uuid() as Id");
			$uuidConsultantId=$uuidConsultant[0]->Id;
			$consultantDetails=ConsultantModel::consultantHardList(Input::get('ConsultantReference'))->get(array('CrpConsultantId','CmnOwnershipTypeId','NameOfFirm','CDBNo','Address','CmnCountryId','CmnDzongkhagId','MobileNo','TelephoneNo','FaxNo','RegistrationApprovedDate','RegistrationExpiryDate','Email','ReferenceNo','ApplicationDate','MobileNo','WaiveOffLateFee','NewLateFeeAmount'));
			/*---------------------------------Contractor Email Details and New Details------------------*/
			$hasWaiver = $consultantDetails[0]->WaiveOffLateFee;
			$newLateFeeAmount = $consultantDetails[0]->NewLateFeeAmount;
			$finalConsultantId=$consultantDetails[0]->CrpConsultantId;
			$CDBNo=$consultantDetails[0]->CDBNo;
			$recipientAddress=$consultantDetails[0]->Email;
			$recipientName=$consultantDetails[0]->NameOfFirm;
			$applicationNo=$consultantDetails[0]->ReferenceNo;
			$applicationDate=$consultantDetails[0]->ApplicationDate;
			$mobileNo=$consultantDetails[0]->MobileNo;

			/*Fee Structure*/
			$hasFee=false;
			$hasRenewal=false;
			$hasLateFee=false;
			$hasChangeInCategoryClassification=false;
			$hasCategoryClassificationsFee=array();
			$hasLateFeeAmount=array();
			$consultantFinalTableId=consultantModelConsultantId(Input::get('ConsultantReference'));
			/*-----------------------------------------------------------------------------------------------------*/
			$newRegistrationAmount = CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->pluck('ConsultantAmount');
			$feeAmount=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$appliedServices=ConsultantAppliedServiceModel::appliedService(Input::get('ConsultantReference'))->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
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
				$hasCategoryClassificationsFee=DB::select("select T1.Id as ServiceCategoryId,T1.Name as ServiceCategoryName,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array(Input::get('ConsultantReference'),Input::get('ConsultantReference'),Input::get('ConsultantReference')));
			}
			if($hasLateFee){
				$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array(Input::get('ConsultantReference')));
			}
			/* END Fee Structure */

			/*---*/
			foreach($hasCategoryClassificationsFee as $singleCategory):
				$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
				$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',Input::get('ConsultantReference'))->groupBy('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
				$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',Input::get('ConsultantReference'))->groupBy('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
				$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',Input::get('ConsultantReference'))->groupBy('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			endforeach;
			/*---*/

			/*------------------------------End of Contractor Email Details and New Details---------------------*/

			DB::table('crpconsultantequipmentfinal')->where('CrpConsultantFinalId',$finalConsultantId)->where('DeleteRequest',1)->update(array('DeleteRequest'=>0));
			DB::table('crpconsultanthumanresourcefinal')->where('CrpConsultantFinalId',$finalConsultantId)->where('DeleteRequest',1)->update(array('DeleteRequest'=>0));
			DB::statement("call ProCrpConsultantUpdateFinalData(?,?,?)",array($finalConsultantId,Input::get('ConsultantReference'),Auth::user()->Id));
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailIntendedTo=2;
		$mailView="emails.crps.mailserviceapplicationapproved";
		$subject="Confirmation of Your Payment for CDB Services";
		$mailData=array(
			'applicantName'=>$recipientName,
			'mailIntendedTo'=>$mailIntendedTo,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'hasFee'=>$hasFee,
			'hasLateFee'=>$hasLateFee,
			'hasLateFeeAmount'=>$hasLateFeeAmount,
			'hasRenewal'=>$hasRenewal,
			'hasWaiver'=>$hasWaiver,
			'newLateFeeAmount'=>$newLateFeeAmount,
			'newRegistrationAmount'=>$newRegistrationAmount,
			'hasChangeInCategoryClassification'=>$hasChangeInCategoryClassification,
			'hasCategoryClassificationsFee'=>$hasCategoryClassificationsFee,
			'existingCategoryServicesArray'=>$existingCategoryServicesArray,
			'appliedCategoryServicesArray'=>$appliedCategoryServicesArray,
			'verifiedCategoryServicesArray'=>$verifiedCategoryServicesArray,
			'approvedCategoryServicesArray'=>$approvedCategoryServicesArray,
			'appliedServices'=>$appliedServices,
			'feeAmount'=>$feeAmount,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for the requested services of your firm (".$recipientName.") ."
		);
		$smsMessage="Your application for avaling services of CDB has been successfully approved.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('consultant/approveserviceapplicationfeepaymentlist')->with('savedsuccessmessage','Payment against the registration successfully recorded.');
	}
	public function approveCertificateCancellationList(){
		$CDBNoAll=Input::get('CrpConsultantIdAll');
		$consultantIdAll=Input::get('CrpConsultantdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$CDBNoMyTask=Input::get('CrpConsultantIdAll');
		$consultantIdMyTask=Input::get('CrpConsultantIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag from crpconsultantfinal T1 join crpconsultantcertificatecancellationrequest T10 on T1.Id=T10.CrpConsultantFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId is null";
		$queryMyTaskList="select T1.Id,T10.Id as CancelRequestId,T10.ReferenceNo,T10.ApplicationDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag from crpconsultantfinal T1 join crpconsultantcertificatecancellationrequest T10 on T1.Id=T10.CrpConsultantFinalId join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T10.CmnApplicationStatusId=? and T10.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$CDBNoAll!=NULL || (bool)$consultantIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$consultantIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$CDBNoAll!=NULL){
				$query.=" and T1.CDBNo=?";
				array_push($parameters,$CDBNoAll);
			}
			if((bool)$consultantIdAll!=NULL){
				$query.=" and T10.Id=?";
				array_push($parameters,$consultantIdAll);
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
			if((bool)$consultantIdMyTask!=NULL){
				$query.=" and T10.Id=?";
				array_push($parametersMyTaskList,$consultantIdMyTask);
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
		$consultantLists=DB::select($query." group by T1.Id order by T10.ApplicationDate,T10.ReferenceNo,NameOfFirm",$parameters);
		$consultantMyTaskLists=DB::select($queryMyTaskList." group by T1.Id order by T10.ApplicationDate,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.consultantserviceapplicationcancellationlist')
					->with('CDBNoAll',$CDBNoAll)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('consultantIdAll',$consultantIdAll)
					->with('CDBNoMyTask',$CDBNoMyTask)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('consultantIdMyTask',$consultantIdMyTask)
					->with('consultantLists',$consultantLists)
					->with('consultantMyTaskLists',$consultantMyTaskLists);
	}
	public function approveCancelCertificateRequest($consultantId,$cancelRequestId){
		$generalInformation=ConsultantCancelCertificateModel::cancellationList($consultantId,$cancelRequestId)->get(array('crpconsultantcertificatecancellationrequest.Id as CancelRequestId','crpconsultantcertificatecancellationrequest.AttachmentFilePath','crpconsultantcertificatecancellationrequest.ReasonForCancellation','crpconsultantcertificatecancellationrequest.ReferenceNo','crpconsultantcertificatecancellationrequest.ApplicationDate','T1.CDBNo','T1.NameOfFirm','T1.Address','T1.Email','T1.TelephoneNo','T1.MobileNo','T1.FaxNo','T2.Name as Country','T3.NameEn as Dzongkhag'));
		return View::make('crps.consultantcertificatecancellationconfirmation')
					->with('consultantId',$consultantId)
					->with('cancelRequestId',$cancelRequestId)
					->with('generalInformation',$generalInformation);
	}
	public function approveCancellation(){
		$cancelRequestId=Input::get('CancelRequestId');
		$consultantReference=Input::get("ConsultantReference");
		$consultantUserId=ConsultantFinalModel::where('Id',$consultantReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			$instance=ConsultantCancelCertificateModel::find($cancelRequestId);
			$instance->SysLockedByUserId="";
			$instance->CmnApplicationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$instance->RemarksByApprover=Input::get('RemarksByApprover');
			$instance->SysApproverUserId=Auth::user()->Id;
			$instance->save();

			$contractorFinalReference=ConsultantFinalModel::find($consultantReference);
			$contractorFinalReference->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED;
			$contractorFinalReference->DeregisteredRemarks=Input::get('RemarksByApprover');
			$contractorFinalReference->DeRegisteredDate=date('Y-m-d');
			$contractorFinalReference->save();

			$userInstance=User::find($consultantUserId);
			$userInstance->Status=0;
			$userInstance->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('consultant/approvecertificatecancellationrequestlist')->with('savedsuccessmessage','Certificate has been successsfully canceled.');
	}
	public function lockApplicationCancellationRequest($cancelRequestId){
		$cancellationRequest=ConsultantCancelCertificateModel::find($cancelRequestId);
		$cancellationRequest->SysLockedByUserId=Auth::user()->Id;
		$cancellationRequest->save();
		return Redirect::to('consultant/approvecertificatecancellationrequestlist');
	}
	public function viewList(){
		$consultantIdMyTask=Input::get('CrpConsultantIdMyTask');
		$CDBNoMyTask=Input::get('CDBNoMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');

		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T7.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,group_concat(T6.Name separator ',<br /> ') as ServiceApplied from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";

		$pageTitle="Final Approval of Service Application";
		$pageTitleHelper="All the applications listed below has been approved and paid.";
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

		if((bool)$consultantIdMyTask!=NULL || (bool)$CDBNoMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$consultantIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$consultantIdMyTask);
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
		$consultantMyTaskLists=DB::select($queryMyTaskList." and T1.SysFinalApproverUserId is null group by T1.Id order by ApplicationDate,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.consultantserviceapplicationviewlist')
			->with('pageTitle',$pageTitle)
			->with('pageTitleHelper',$pageTitleHelper)
			->with('fromDateMyTask',convertDateToClientFormat($fromDateMyTask))
			->with('toDateMyTask',convertDateToClientFormat($toDateMyTask))
			->with('consultantIdMyTask',$consultantIdMyTask)
			->with('CDBNoMyTask',$CDBNoMyTask)
			->with('consultantMyTaskLists',$consultantMyTaskLists);
	}
}
