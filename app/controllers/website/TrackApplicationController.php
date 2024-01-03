<?php
class TrackApplicationController extends BaseController{
	public function trackApplication(){
		$applicationHistories=array();
		$searchByApplicationNo=false;
		$searchByCIDNo=false;
		$applicantType=Input::get('ApplicantType');
		$cidNoOrApplicationNo=Input::get('ApplicationReference');
		$feeStructures = array();
		$applicationType = false;
		$hasFee = false;
		$hasLateFee = false;
		$hasLateFeeAmount = false;
		$hasRenewal = false;
		$hasChangeInCategoryClassification = false;
		$appliedServices = '';
		$hasCategoryClassificationsFee = '';
		$serviceCategories = array();
		$appliedCategoryServices = array();
		$verifiedCategoryServices = array();
		$approvedCategoryServices = array();
		$existingCategoryServicesArray = array();
		$appliedCategoryServicesArray = array();
		$verifiedCategoryServicesArray = array();
		$approvedCategoryServicesArray = array();
		$feeAmount = array();
		$architectServiceSectorType = '';
		$engineerServiceSectorType = '';
		$validityYears = 0;
		$maxClassification = '';
		$hasWaiver = 0;
		$newLateFeeAmount = 0;
		switch ($applicantType) {
			case 1:
				//contractor
				$checkByApplicationNo=DB::table('crpcontractor')->where('ReferenceNo',$cidNoOrApplicationNo)->count('Id');
				if($checkByApplicationNo>=1){
					$searchByApplicationNo=true;
					$applicationFor=DB::table("crpcontractor")->where('ReferenceNo',$cidNoOrApplicationNo)->pluck('CrpContractorId');
					if((bool)$applicationFor==NULL){
						//Application for new registration
						$applicationType = 1;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId,T1.WaiveOffLateFee,T1.NewLateFeeAmount from crpcontractor T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.ReferenceNo=? LIMIT 1",array($cidNoOrApplicationNo));
						if(!empty($applicationDetails)){
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT):
								$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join crpcontractor Y on Y.Id = X.CrpContractorId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where Y.ReferenceNo=? order by T1.Code,T1.Name",array($cidNoOrApplicationNo));
							endif;
						}

					}else{
						//Application for services
						$applicationType = 2;
                        $hasReregistration = false;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,T1.Id as CrpContractorId,T1.CrpContractorId as FinalId,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,T1.WaiveOffLateFee,T1.NewLateFeeAmount,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpcontractor T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.ReferenceNo=?",array($cidNoOrApplicationNo));
						$crpContractorId = $applicationDetails[0]->CrpContractorId;
						$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFinalId',$applicationDetails[0]->FinalId)->pluck('MaxClassificationPriority');
						/* FOR LATE FEE WAIVER */
						$hasWaiver = $applicationDetails[0]->WaiveOffLateFee;
						$newLateFeeAmount = $applicationDetails[0]->NewLateFeeAmount;
						/* END */
						$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpcontractor T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpContractorId=?",array($applicationFor));
						if(!empty($applicationDetails)){
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT):
								/* FOR FEE STRUCTURE */
								$hasFee=false;
								$hasRenewal=false;
								$hasLateFee=false;
								$hasChangeInCategoryClassification=false;
								$hasCategoryClassificationsFee=array();
								$hasLateFeeAmount=array();
								$contractorFinalTableId=contractorModelContractorId($crpContractorId);
								$appliedServices=ContractorAppliedServiceModel::appliedService($crpContractorId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));

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
									$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($crpContractorId,$contractorFinalTableId,$crpContractorId,$crpContractorId,$crpContractorId,$contractorFinalTableId));
								}
//								if($hasLateFee){
//									$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($crpContractorId));
//								}
                                $registrationExpiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('RegistrationExpiryDate');
                                $reregistrationDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('ReRegistrationDate');
                                if((bool)$reregistrationDate){
                                    if($reregistrationDate>$registrationExpiryDate){
                                        $hasReregistration = true;
                                    }
                                }
                                if($hasLateFee){
                                    if($hasReregistration) {
                                        $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.ReregistrationDate as RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.ReregistrationDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($crpContractorId));
                                    }else{
                                        $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($crpContractorId));
                                    }
                                }
								/* END FOR FEE STRUCTURE */
							endif;
						}
					}
				}else{
                    $hasReregistration = false;
					$applicationId=DB::table('crpcontractor as T1')->join('crpcontractorhumanresource as T2','T1.Id','=','T2.CrpContractorId')->where('T2.CIDNo',$cidNoOrApplicationNo)->orderBy('T1.ApplicationDate','DESC')->where('T1.RegistrationStatus',1)->where('T2.ShowInCertificate',1)->pluck('T1.Id');
					$searchByCIDNo=true;
					$applicationFor=DB::table("crpcontractor")->where('Id',$applicationId)->pluck('CrpContractorId');
					if((bool)$applicationFor==NULL){
						//Application for new registration
						$applicationType = 1;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId from crpcontractor T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.Id=? LIMIT 1",array($applicationId));
						if(!empty($applicationDetails)){
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT):
								$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join crpcontractor Y on Y.Id = X.CrpContractorId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where Y.Id=? order by T1.Code,T1.Name",array($applicationId));
							endif;
						}
					}else{
						//Application for services
						$applicationType = 2;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.Id as CrpContractorId,T1.CrpContractorId as FinalId,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,T1.WaiveOffLateFee,T1.NewLateFeeAmount,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpcontractor T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.Id=?",array($applicationId));
//						echo "<PRE>"; dd($applicationDetails);
						if(!empty($applicationDetails)){
							$crpContractorId = $applicationDetails[0]->CrpContractorId;
							/* FOR LATE FEE WAIVER */
							$hasWaiver = $applicationDetails[0]->WaiveOffLateFee;
							$newLateFeeAmount = $applicationDetails[0]->NewLateFeeAmount;
							/* END */
							$maxClassification = DB::table('viewcontractormaxclassification')->where('CrpContractorFInalId',$applicationDetails[0]->FinalId)->pluck('MaxClassificationPriority');
							$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpcontractor T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpContractorId=? and T1.RegistrationStatus = 1 group by T1.Id order by T1.ApplicationDate DESC",array($applicationFor));
							if($applicationDetails[0]->StatusId != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT):
								/* FOR FEE STRUCTURE */
								$hasFee=false;
								$hasRenewal=false;
								$hasLateFee=false;
								$hasChangeInCategoryClassification=false;
								$hasCategoryClassificationsFee=array();
								$hasLateFeeAmount=array();
								$contractorFinalTableId=contractorModelContractorId($crpContractorId);
								$appliedServices=ContractorAppliedServiceModel::appliedService($crpContractorId)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));

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
									$hasCategoryClassificationsFee=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($crpContractorId,$contractorFinalTableId,$crpContractorId,$crpContractorId,$crpContractorId,$contractorFinalTableId));
								}
//								if($hasLateFee){
//									$hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($crpContractorId));
//								}
                                $registrationExpiryDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('RegistrationExpiryDate');
                                $reregistrationDate = DB::table('crpcontractorfinal')->where('Id',$contractorFinalTableId)->pluck('ReRegistrationDate');
                                if((bool)$reregistrationDate){
                                    if($reregistrationDate>$registrationExpiryDate){
                                        $hasReregistration = true;
                                    }
                                }
                                if($hasLateFee){
                                    if($hasReregistration) {
                                        $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.ReregistrationDate as RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.ReregistrationDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($crpContractorId));
                                    }else{
                                        $hasLateFeeAmount=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($crpContractorId));
                                    }
                                }
								/* END FOR FEE STRUCTURE */
							endif;
						}

					}
				}
				break;
			case 2:
				//consultant
				$checkByApplicationNo=DB::table('crpconsultant')->where('ReferenceNo',$cidNoOrApplicationNo)->count('Id');
				if($checkByApplicationNo>=1){
					$searchByApplicationNo=true;
					$applicationFor=DB::table("crpconsultant")->where('ReferenceNo',$cidNoOrApplicationNo)->pluck('CrpConsultantId');
					if((bool)$applicationFor==NULL){
						//Application for new registration
						$applicationType = 1;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.Id as CrpConsultantId,T1.ApplicationDate,T1.NameOfFirm,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId from crpconsultant T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.ReferenceNo=? LIMIT 1",array($cidNoOrApplicationNo));
						if(!empty($applicationDetails)){
							$consultantId = $applicationDetails[0]->CrpConsultantId;
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT):
								$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
								$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
								$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
								$verifiedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
								$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
							endif;
						}

					}else{
						//Application for services
						$applicationType = 2;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.Id as CrpConsultantId,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,group_concat(T3.Name separator ',<br/> ') as ApplicationType,T1.WaiveOffLateFee,T1.NewLateFeeAmount from crpconsultant T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.ReferenceNo=?",array($cidNoOrApplicationNo));
						$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpconsultant T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpConsultantId=?",array($applicationFor));

						/* FOR LATE FEE WAIVER */
						$hasWaiver = $applicationDetails[0]->WaiveOffLateFee;
						$newLateFeeAmount = $applicationDetails[0]->NewLateFeeAmount;
						/* END */
						if(!empty($applicationDetails)){
							$consultantId = $applicationDetails[0]->CrpConsultantId;
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
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

								foreach($hasCategoryClassificationsFee as $singleCategory):
									$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
									$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
									$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
									$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
								endforeach;
								/* ENd fee structure */
							}
						}
					}
				}else{
					$applicationId=DB::table('crpconsultant as T1')->join('crpconsultanthumanresource as T2','T1.Id','=','T2.CrpConsultantId')->orderBy('T1.ReferenceNo','DESC')->whereRaw('coalesce(T1.RegistrationStatus,0)=1')->orderBy('T1.ApplicationDate','DESC')->where('T1.RegistrationStatus',1)->where('T2.CIDNo',$cidNoOrApplicationNo)->where('T2.ShowInCertificate',1)->pluck('T1.Id');
					$searchByCIDNo=true;
					$applicationFor=DB::table("crpconsultant")->where('Id',$applicationId)->pluck('CrpConsultantId');
					if((bool)$applicationFor==NULL){
						//Application for new registration
						$applicationType = 1;
						$applicationDetails=DB::select("select T1.Id as CrpConsultantId,T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId from crpconsultant T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.Id=? LIMIT 1",array($applicationId));
						if(!empty($applicationDetails)){
							$consultantId = $applicationDetails[0]->CrpConsultantId;
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT):
								$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
								$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
								$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
								$verifiedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
								$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
							endif;
						}


					}else{
						//Application for services
						$applicationType = 2;
						$applicationDetails=DB::select("select T1.ReferenceNo,T1.WaiveOffLateFee,T1.NewLateFeeAmount,T1.Id as CrpConsultantId,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpconsultant T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.Id=?",array($applicationId));
						$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpconsultant T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpConsultantId=? and T1.RegistrationStatus = 1 group by T1.Id order by T1.ApplicationDate DESC",array($applicationFor));
						/* FOR LATE FEE WAIVER */
						$hasWaiver = $applicationDetails[0]->WaiveOffLateFee;
						$newLateFeeAmount = $applicationDetails[0]->NewLateFeeAmount;
						/* END */

						if(!empty($applicationDetails)){
							$consultantId = $applicationDetails[0]->CrpConsultantId;
							if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
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

								foreach($hasCategoryClassificationsFee as $singleCategory):
									$existingCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
									$appliedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
									$verifiedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
									$approvedCategoryServicesArray[$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantId)->groupBy('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
								endforeach;

								/* ENd fee structure */
							}
						}
					}
				}
				break;
			case 3:
				//Architect
				$applicationId=DB::table('crparchitect as T1')
						->where('T1.CIDNo',$cidNoOrApplicationNo)
						->orWhere('T1.ReferenceNo',$cidNoOrApplicationNo)
						->pluck('T1.Id');
				$searchByCIDNo=true;
				$applicationFor=DB::table("crparchitect")->where('Id',$applicationId)->pluck('CrpArchitectId');
				if((bool)$applicationFor==NULL){
					//Application for new registration
					$applicationType = 1;
					$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Name as NameOfFirm,T1.CmnServiceSectorTypeId,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId from crparchitect T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.Id=? LIMIT 1",array($applicationId));
					if(!empty($applicationDetails)){
						if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
							if($applicationDetails[0]->CmnServiceSectorTypeId==CONST_CMN_SERVICESECTOR_PVT){
								$feeStructures=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
							}else{
								$feeStructures=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
							}
						}
					}
				}else{
					//Application for services
					$applicationType = 2;
					$applicationDetails=DB::select("select T1.ReferenceNo,T1.WaiveOffLateFee,T1.NewLateFeeAmount,T1.Id as CrpArchitectId,T1.ApplicationDate,T1.Name as NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crparchitect T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crparchitectappliedservice T5 on T1.Id=T5.CrpArchitectId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.Id=?",array($applicationId));
					$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Name as NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crparchitect T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crparchitectappliedservice T5 on T1.Id=T5.CrpArchitectId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpArchitectId=? and T1.RegistrationStatus = 1 group by T1.Id order by T1.RegistrationDate DESC",array($applicationFor));

					/* FOR LATE FEE WAIVER */
					$hasWaiver = $applicationDetails[0]->WaiveOffLateFee;
					$newLateFeeAmount = $applicationDetails[0]->NewLateFeeAmount;
					/* END */

					if(!empty($applicationDetails)){
						if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
							/*Fee Structure */
							$architectId = $applicationDetails[0]->CrpArchitectId;
							$hasFee=false;
							$hasLateFee=false;
							$applicableFees=array();
							$hasLateFeeAmount=array();
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

							/*ENd fee structure */
						}
					}
				}
				break;
			case 4:
				//Engineer
				$applicationId=DB::table('crpengineer as T1')
					->where('T1.CIDNo',$cidNoOrApplicationNo)
					->orWhere('T1.ReferenceNo',$cidNoOrApplicationNo)
					->pluck('T1.Id');
				$searchByCIDNo=true;
				$applicationFor=DB::table("crpengineer")->where('Id',$applicationId)->pluck('CrpEngineerId');
				if((bool)$applicationFor==NULL){
					//Application for new registration
					$applicationType = 1;
					$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Id as CrpEngineerId,T1.Name as NameOfFirm,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId from crpengineer T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.Id=? LIMIT 1",array($applicationId));
					if(!empty($applicationDetails)){
						if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
							$engineerId = $applicationDetails[0]->CrpEngineerId;
							$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
							if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
								$feeStructures=DB::select("select Name as ServiceName,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
							}else{
								$feeStructures=DB::select("select Name as ServiceName,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
							}
						}
					}
				}else{
					//Application for services
					$applicationType = 2;
					$applicationDetails=DB::select("select T1.ReferenceNo,T1.Id as CrpEngineerId,T1.WaiveOffLateFee,T1.NewLateFeeAmount,T1.ApplicationDate,T1.Name as NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpengineer T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpengineerappliedservice T5 on T1.Id=T5.CrpEngineerId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.Id=?",array($applicationId));
					$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Name as NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpengineer T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpengineerappliedservice T5 on T1.Id=T5.CrpEngineerId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpEngineerId=? and T1.RegistrationStatus = 1 group by T1.Id order by T1.RegistrationDate DESC",array($applicationFor));

					/* FOR LATE FEE WAIVER */
					$hasWaiver = $applicationDetails[0]->WaiveOffLateFee;
					$newLateFeeAmount = $applicationDetails[0]->NewLateFeeAmount;
					/* END */

					if(!empty($applicationDetails)){
						if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
							/* Start fee structure */
							$engineerId = $applicationDetails[0]->CrpEngineerId;
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
						}
					}

				}
				break;
			case 5:
				//Specialized Trades
				$applicationId=DB::table('crpspecializedtrade as T1')
									->where('T1.CIDNo',$cidNoOrApplicationNo)
									->orWhere('T1.ReferenceNo',$cidNoOrApplicationNo)
									->pluck('T1.Id');
				$searchByCIDNo=true;
				$applicationFor=DB::table("crpspecializedtrade")->where('Id',$applicationId)->pluck('CrpSpecializedTradeId');
				if((bool)$applicationFor==NULL){
					//Application for new registration
					$applicationType = 1;
					$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Name as NameOfFirm,'New Registration' as ApplicationType,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus, T2.Id as StatusId from crpspecializedtrade T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id where T1.Id=? LIMIT 1",array($applicationId));
				}else{
					//Application for services
					$applicationType = 2;
					$applicationDetails=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Name as NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,T2.Id as StatusId,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpspecializedtrade T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.Id=?",array($applicationId));
					$applicationHistories=DB::select("select T1.ReferenceNo,T1.ApplicationDate,T1.Name as NameOfFirm,T1.RegistrationExpiryDate,T1.PaymentApprovedDate,T2.Name as ApplicationStatus,group_concat(T3.Name separator ',<br/> ') as ApplicationType from crpspecializedtrade T1 join cmnlistitem T2 on T1.CmnApplicationRegistrationStatusId=T2.Id join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T3 on T5.CmnServiceTypeId=T3.Id where T1.CrpSpecializedTradeId=? and T1.RegistrationStatus = 1 group by T1.Id order by T1.ApplicationDate DESC",array($applicationFor));
				}
				break;
			default:
				App::abort(404);
				break;
		}
		return View::make('website.trackapplication')
					->with('hasWaiver',$hasWaiver)
					->with('newLateFeeAmount',$newLateFeeAmount)
					->with('maxClassification',$maxClassification)
					->with('hasFee',$hasFee)
					->with('hasLateFee',$hasLateFee)
					->with('hasLateFeeAmount',$hasLateFeeAmount)
					->with('hasRenewal',$hasRenewal)
					->with('hasChangeInCategoryClassification',$hasChangeInCategoryClassification)
					->with('appliedServices',$appliedServices)
					->with('hasCategoryClassificationsFee',$hasCategoryClassificationsFee)
					->with('applicationType',$applicationType)
					->with('applicantType',$applicantType)
					->with('feeStructures',$feeStructures)
					->with('feeAmount',$feeAmount)
					->with('existingCategoryServicesArray',$existingCategoryServicesArray)
					->with('appliedCategoryServicesArray',$appliedCategoryServicesArray)
					->with('verifiedCategoryServicesArray',$verifiedCategoryServicesArray)
					->with('approvedCategoryServicesArray',$approvedCategoryServicesArray)
					->with('serviceCategories',$serviceCategories)
					->with('appliedCategoryServices',$appliedCategoryServices)
					->with('verifiedCategoryServices',$verifiedCategoryServices)
					->with('approvedCategoryServices',$approvedCategoryServices)
					->with('applicationDetails',$applicationDetails)
					->with('architectServiceSectorType',$architectServiceSectorType)
					->with('engineerServiceSectorType',$engineerServiceSectorType)
					->with('appliedServices',$appliedServices)
					->with('validityYears',$validityYears)
					->with('applicationHistories',$applicationHistories);
	}
}