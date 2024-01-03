<?php
class EzotinReports extends ReportController{
	public function getApplicantsDueForPayment(){
        $date = Input::has('Date')?$this->convertDate(Input::get('Date')):date('Y-m-d');
        $contractorNewRegistration = DB::select("SELECT Id,NameOfFirm,CDBNo, MobileNo,ReferenceNo as ApplicationNo,ApplicationDate FROM `crpcontractor` WHERE CrpContractorId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
        foreach($contractorNewRegistration as $contractorNew):
            $id = $contractorNew->Id;
            $object = new Contractor;
            $feeStructures = $object->contractorDetails($id,true);
            $totalFeeApplied=0;$totalFeeVerified=0;$totalFeeApproved=0;
            foreach($feeStructures as $feeStructure):
                $totalFeeApplied+=$feeStructure->AppliedRegistrationFee;
                $totalFeeVerified+=$feeStructure->VerifiedRegistrationFee;
                $totalFeeApproved+=$feeStructure->ApprovedRegistrationFee;
            endforeach;
            $contractorNew->Amount = $totalFeeApproved;
        endforeach;

        $contractorService = DB::select("SELECT T1.Id,T1.NameOfFirm,T2.CDBNo, T1.MobileNo,T1.ReferenceNo as ApplicationNo,T1.ApplicationDate FROM `crpcontractor` T1 join crpcontractorfinal T2 on T2.Id = T1.CrpContractorId WHERE T1.CrpContractorId is not null and T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND T1.`RegistrationApprovedDate` <= '$date' ORDER BY T1.`ApplicationDate` DESC");
        foreach($contractorService as $contractorServ):
            $id = $contractorServ->Id;
            $object = new ContractorServiceApplication();
            $feeStructures = $object->serviceApplicationDetails($id,true);

            $hasChangeOfOwner = false; $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;$lateFeeAmount=0; $appliedServiceName = "";
            foreach($feeStructures['appliedServices'] as $appliedService):
                if($appliedService->Id == CONST_SERVICETYPE_CHANGEOWNER):
                    $hasChangeOfOwner=true;
                endif;
                if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_LATEFEE || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION):
                    $appliedServiceName = $appliedService->ServiceName;
                endif;
                if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION):
                    if($feeStructures['hasRenewal'] && $feeStructures['hasChangeInCategoryClassification']):
                        if($appliedService->Id==CONST_SERVICETYPE_RENEWAL):

                            foreach($feeStructures['hasCategoryClassificationsFee'] as $hasCategoryClassificationFee):
                                if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL):
                                    if($hasCategoryClassificationFee->VerifiedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId):
                                        $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->VerifiedRenewalFee;
                                    else:
                                        $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->VerifiedRegistrationFee;
                                    endif;
                                else:
                                endif;
                            endforeach;
                            $appliedService->ContractorAmount=$categoryClassificationFeeTotal;
                        else:
                        endif;
                    else:
                        foreach($feeStructures['hasCategoryClassificationsFee'] as $hasCategoryClassificationFee):

                            if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL):
                                if($hasCategoryClassificationFee->VerifiedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId):
                                    if($feeStructures['hasRenewal']):
                                        $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->VerifiedRenewalFee;
                                    else:
                                    endif;
                                else:
                                    $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->VerifiedRegistrationFee;
                                endif;
                            else:
                            endif;
                        endforeach;
                        $appliedService->ContractorAmount=$categoryClassificationFeeTotal;

                    endif;
                elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE):
                    $lateFeeAfterGracePeriod=$feeStructures['hasLateFeeAmount'][0]->PenaltyNoOfDays-30-1;
                    if(($feeStructures['hasLateFeeAmount'][0]->PenaltyNoOfDays-1)>30):
                        $lateFeeAfterGracePeriod=$feeStructures['hasLateFeeAmount'][0]->PenaltyNoOfDays-30-1;
                    else:
                        $lateFeeAfterGracePeriod = 0;
                    endif;
                    $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$feeStructures['hasLateFeeAmount'][0]->PenaltyLateFeeAmount;
                    if((int)$feeStructures['maxClassification'] == 998):
                        if($lateFeeAmount > 3000):
                            $lateFeeAmount = 3000;
                        endif;
                    endif;
                    $appliedService->ContractorAmount=$lateFeeAmount;
                endif;
                if((bool)$appliedService->ContractorAmount!=NULL):
                    if($appliedService->Id == CONST_SERVICETYPE_INCORPORATION):
                        if($feeStructures['generalInformation'][0]->OwnershipType!=$feeStructures['generalInformationFinal'][0]->OwnershipType):
                            $totalFeeApplicable+=$appliedService->ContractorAmount;
                        else:
                        endif;
                    else:
                        $totalFeeApplicable+=$appliedService->ContractorAmount;
                    endif;
                else:
                endif;
            endforeach;
            if($feeStructures['generalInformation'][0]->WaiveOffLateFee == 1):
                $contractorServ->Amount = (int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$feeStructures['generalInformation'][0]->NewLateFeeAmount);
            else:
                $contractorServ->Amount = $totalFeeApplicable;
            endif;
        endforeach;

        $consultantNewRegistration = DB::select("SELECT Id,NameOfFirm,CDBNo,MobileNo,ReferenceNo as ApplicationNo,ApplicationDate FROM `crpconsultant` WHERE CrpConsultantId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
        foreach($consultantNewRegistration as $consultantNew):
            $id = $consultantNew->Id;
            $object = new Consultant;
            $reportDetails = $object->consultantDetails($id,true);
            $noOfServicePerCategory=0;$overAllTotalAmount=0;
            foreach($reportDetails['serviceCategories'] as $serviceCategory):
                foreach($reportDetails['approvedCategoryServices'] as $approvedServiceFee):
                    if($approvedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id):
                        $noOfServicePerCategory+=1;

                    endif;
                endforeach;
                $curTotalAmount=$noOfServicePerCategory*$reportDetails['feeStructures'][0]->ConsultantAmount;$overAllTotalAmount+=$curTotalAmount;
                $noOfServicePerCategory=0;
            endforeach;
            $consultantNew->Amount = $overAllTotalAmount;
        endforeach;

        $consultantService = DB::select("SELECT T1.Id,T1.NameOfFirm,T1.MobileNo,T2.CDBNo,T1.ReferenceNo as ApplicationNo,T1.ApplicationDate FROM `crpconsultant` T1 join crpconsultantfinal T2 on T2.Id = T1.CrpConsultantId WHERE T1.CrpConsultantId is not null and T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND T1.`RegistrationApprovedDate` <= '$date' ORDER BY T1.`ApplicationDate` DESC");
        foreach($consultantService as $consultantServ):
            $id = $consultantServ->Id;
            $object = new ConsultantServiceApplication();
            $reportDetails = $object->serviceApplicationDetails($id,true);
            $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;$lateFeeAmount=0;
            foreach($reportDetails['appliedServices'] as $appliedService):
                if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION):
                    if($reportDetails['hasRenewal'] && $reportDetails['hasChangeInCategoryClassification']):
                        if($appliedService->Id==CONST_SERVICETYPE_RENEWAL):
                            foreach($reportDetails['hasCategoryClassificationsFee'] as $hasCategoryClassificationFee):
                                $approvedServiceCount = $newServiceCount = $oldServiceCount = 0; $feeString = ""; $randomKey3 = randomString();
                                if($appliedService->Id==CONST_SERVICETYPE_RENEWAL):
                                    foreach($reportDetails['approvedCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual):
                                        $approvedServiceCount++;
                                        if(!in_array($approvedServiceIndividual,$reportDetails['existingCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId])):
                                            $newServiceCount++;
                                        else:
                                            $oldServiceCount++;
                                        endif;
                                    endforeach;
                                else:
                                    foreach($reportDetails['approvedCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual):
                                        if(!in_array($approvedServiceIndividual,$reportDetails['existingCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId])):
                                            $approvedServiceCount++;
                                        endif;
                                    endforeach;
                                endif;
                                if($appliedService->Id==CONST_SERVICETYPE_RENEWAL):
                                    $categoryClassificationFeeTotal+=$oldServiceCount* $reportDetails['feeAmount'][0]->ConsultantAmount;
                                    $categoryClassificationFeeTotal+=$newServiceCount* $reportDetails['newRegistrationAmount'];
                                else:
                                    $categoryClassificationFeeTotal+=$approvedServiceCount* $reportDetails['feeAmount'][0]->ConsultantAmount;
                                endif;
                            endforeach;
                            $appliedService->ConsultantAmount=$categoryClassificationFeeTotal;
                        else:
                        endif;
                    else:
                        foreach($reportDetails['hasCategoryClassificationsFee'] as $hasCategoryClassificationFee):
                            $approvedServiceCount = $newServiceCount = $oldServiceCount = 0; $feeString = "";

                            if($appliedService->Id==CONST_SERVICETYPE_RENEWAL):
                                foreach($reportDetails['approvedCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual):
                                    $approvedServiceCount++;
                                    if(!in_array($approvedServiceIndividual,$reportDetails['existingCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId])):
                                        $newServiceCount++;
                                    else:
                                        $oldServiceCount++;
                                    endif;
                                endforeach;
                            else:
                                foreach($reportDetails['approvedCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual):
                                    if(!in_array($approvedServiceIndividual,$reportDetails['existingCategoryServicesArray'][$hasCategoryClassificationFee->ServiceCategoryId])):
                                       $approvedServiceCount++;
                                    endif;
                                endforeach;
                            endif;
                            if($appliedService->Id==CONST_SERVICETYPE_RENEWAL):
                                $categoryClassificationFeeTotal+=$oldServiceCount* $reportDetails['feeAmount'][0]->ConsultantAmount;
                                $categoryClassificationFeeTotal+=$newServiceCount* $reportDetails['newRegistrationAmount'];
                            else:
                                $categoryClassificationFeeTotal+=$approvedServiceCount* $reportDetails['feeAmount'][0]->ConsultantAmount;
                            endif;
                        endforeach;
                        $appliedService->ConsultantAmount=$categoryClassificationFeeTotal;
                    endif;
                elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE):
                    $lateFeeAfterGracePeriod=$reportDetails['hasLateFeeAmount'][0]->PenaltyNoOfDays-30-1;
                    if(($reportDetails['hasLateFeeAmount'][0]->PenaltyNoOfDays-1)>30):
                        $lateFeeAfterGracePeriod=$reportDetails['hasLateFeeAmount'][0]->PenaltyNoOfDays-30-1;
                    else:
                        $lateFeeAfterGracePeriod = 0;
                    endif;
                    $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$reportDetails['hasLateFeeAmount'][0]->PenaltyLateFeeAmount;
                    $appliedService->ConsultantAmount=$lateFeeAmount;
                endif;
                if((bool)$appliedService->ConsultantAmount!=NULL):
                    if($appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION):
                        if(!$reportDetails['hasRenewal']):
                            $totalFeeApplicable+=$appliedService->ConsultantAmount;
                        endif;
                    else:
                        $totalFeeApplicable+=$appliedService->ConsultantAmount;
                    endif;
                else:
                endif;
            endforeach;
            if((int)$reportDetails['generalInformation'][0]->WaiveOffLateFee == 1):
                $consultantServ->Amount = (int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$reportDetails['generalInformation'][0]->NewLateFeeAmount);
            else:
                $consultantServ->Amount = $totalFeeApplicable;
            endif;
        endforeach;

        $architectNewRegistration = DB::select("SELECT Id,Name,ARNo,MobileNo,ReferenceNo,ApplicationDate FROM `crparchitect` WHERE CrpArchitectId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
        foreach($architectNewRegistration as $architectNew):
            $id = $architectNew->Id;
            $object = new Architect;
            $feeDetails = $object->architectDetails($id,true);
            $totalFeesApplicable=0;
            foreach($feeDetails as $feeDetail):
                $totalFeesApplicable+=$feeDetail->NewRegistrationFee;
            endforeach;
            $architectNew->Amount = $totalFeesApplicable;
        endforeach;
        $architectService = DB::select("SELECT T1.Id,T1.Name,T2.ARNo,T1.MobileNo,T1.ReferenceNo,T1.ApplicationDate FROM `crparchitect` T1 join crparchitectfinal T2 on T2.Id = T1.CrpArchitectId WHERE T1.CrpArchitectId is not null and T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND T1.`RegistrationApprovedDate` <= '$date' ORDER BY T1.`ApplicationDate` DESC");
        foreach($architectService as $architectServ):
            $id = $architectServ->Id;
            $object = new ArchitectServiceApplication();
            $reportDetails = $object->serviceApplicationDetails($id,true);
            $totalFeesApplicable=0;
            $lateFeeAmount=0;
            foreach($reportDetails['appliedServices'] as $appliedService):
                if($reportDetails['architectServiceSectorType']==CONST_CMN_SERVICESECTOR_GOVT):
                    $totalFeesApplicable+=$appliedService->ArchitectGovtAmount;
                else:
                    if($appliedService->Id==CONST_SERVICETYPE_LATEFEE):
                        $lateFeeAfterGracePeriod=$reportDetails['hasLateFeeAmount'][0]->PenaltyNoOfDays-30-1;
                        if(($reportDetails['hasLateFeeAmount'][0]->PenaltyNoOfDays-1)>30):
                            $lateFeeAfterGracePeriod=$reportDetails['hasLateFeeAmount'][0]->PenaltyNoOfDays-30-1;
                        else:
                            $lateFeeAfterGracePeriod = 0;
                        endif;
                        $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$reportDetails['hasLateFeeAmount'][0]->PenaltyLateFeeAmount;
                        if($lateFeeAmount > 3000):
                            $lateFeeAmount = 3000;
                        endif;
                    endif;
                    $totalFeesApplicable+=$appliedService->ArchitectPvtAmount;
                endif;
            endforeach;
            if($reportDetails['architectInformations'][0]->WaiveOffLateFee == 1):
            endif;
            $architectServ->Amount = $totalFeesApplicable;
            if($reportDetails['architectInformations'][0]->WaiveOffLateFee == 1):
            $architectServ->Amount = (int)$totalFeesApplicable-((int)$lateFeeAmount-(int)$reportDetails['architectInformations'][0]->NewLateFeeAmount);
            endif;
        endforeach;
        return View::make('report.unpaidapplications')
                ->with('date',$date)
                ->with('contractorNewRegistration',$contractorNewRegistration)
                ->with('contractorService',$contractorService)
                ->with('consultantNewRegistration',$consultantNewRegistration)
                ->with('consultantService',$consultantService)
                ->with('architectNewRegistration',$architectNewRegistration)
                ->with('architectService',$architectService);
    }
    public function getUnpaidConsultants($date){
        $consultantService = DB::select("SELECT Id,NameOfFirm,CDBNo FROM `crpconsultant` WHERE CrpConsultantId is not null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
        foreach($consultantService as $consultantServ):
            $serviceApplicationApprovedForPayment[$consultantServ->Id]=0;
            $serviceApplicationApproved[$consultantServ->Id] = 0;
            $hasFee[$consultantServ->Id]=false;
            $hasRenewal[$consultantServ->Id]=false;
            $hasLateFee[$consultantServ->Id]=false;
            $hasChangeInCategoryClassification[$consultantServ->Id]=false;
            $hasCategoryClassificationsFee[$consultantServ->Id]=array();
            $hasLateFeeAmount[$consultantServ->Id]=array();
            $existingCategoryServicesArray[$consultantServ->Id]  = array();
            $appliedCategoryServicesArray[$consultantServ->Id]  = array();
            $verifiedCategoryServicesArray[$consultantServ->Id]  = array();
            $approvedCategoryServicesArray[$consultantServ->Id]  = array();
            $consultantFinalTableId[$consultantServ->Id]=consultantModelConsultantId($consultantServ->Id);
            /*-----------------------------------------------------------------------------------------------------*/
            $feeAmount[$consultantServ->Id]=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
            $appliedServices[$consultantServ->Id]=ConsultantAppliedServiceModel::appliedService($consultantServ->Id)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
            foreach($appliedServices[$consultantServ->Id] as $appliedService){
                if((int)$appliedService->HasFee==1){
                    $hasFee[$consultantServ->Id]=true;
                }
                if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
                    $hasRenewal[$consultantServ->Id]=true;
                }
                if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
                    $hasLateFee[$consultantServ->Id]=true;
                }
                if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
                    $hasChangeInCategoryClassification[$consultantServ->Id]=true;
                }
            }
            if($hasRenewal[$consultantServ->Id] || $hasChangeInCategoryClassification[$consultantServ->Id]){
                $hasCategoryClassificationsFee[$consultantServ->Id]=DB::select("select T1.Name as ServiceCategoryName,T1.Id as ServiceCategoryId,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(distinct T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(distinct T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($consultantServ->Id,$consultantServ->Id,$consultantServ->Id));
            }
            if($hasLateFee){
                $hasLateFeeAmount[$consultantServ->Id]=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($consultantServ->Id));
            }
            /*------------------------------------------------------------------------------------------------------*/

            /*---*/
            foreach($hasCategoryClassificationsFee[$consultantServ->Id] as $singleCategory):
                $existingCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
                $appliedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
                $verifiedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
                $approvedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
            endforeach;
            /*---*/
            $consultantServices[$consultantServ->Id]=ConsultantServiceModel::service()->get(array('Id','Code','Name','CmnConsultantServiceCategoryId'));
            $appliedCategories[$consultantServ->Id]=ConsultantWorkClassificationModel::serviceCategory($consultantServ->Id)->get(array('T1.Id','T1.Name as Category'));
            $appliedCategoryServices[$consultantServ->Id]=ConsultantWorkClassificationModel::appliedService($consultantServ->Id)->get(array(DB::raw('distinct T1.Id as ServiceId'),'crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Code as ServiceCode','T1.Name as ServiceName'));
            $verifiedCategoryServices[$consultantServ->Id]=ConsultantWorkClassificationModel::verifiedService($consultantServ->Id)->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
            $currentServiceClassifications[$consultantServ->Id]=ConsultantWorkClassificationFinalModel::services($consultantFinalTableId)->select(DB::raw("T1.Name as Category,group_concat(distinct T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
            $generalInformation[$consultantServ->Id]=ConsultantModel::consultant($consultantServ->Id)->get(array('crpconsultant.Id','crpconsultant.PaymentReceiptNo', 'crpconsultant.PaymentReceiptDate','crpconsultant.ReferenceNo','crpconsultant.ApplicationDate','crpconsultant.CDBNo','crpconsultant.NameOfFirm','crpconsultant.RegisteredAddress','crpconsultant.Village','crpconsultant.Gewog','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','crpconsultant.VerifiedDate','crpconsultant.RemarksByVerifier','crpconsultant.RemarksByApprover','crpconsultant.RegistrationApprovedDate','crpconsultant.RemarksByPaymentApprover','crpconsultant.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T8.NameEn as RegisteredDzongkhag','crpconsultant.WaiveOffLateFee','crpconsultant.NewLateFeeAmount',));
            /*------------------------------End of record applied by the applicant----------------------------*/
            $generalInformationFinal[$consultantServ->Id]=ConsultantFinalModel::consultant($consultantFinalTableId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.RegistrationApprovedDate','crpconsultantfinal.RegistrationExpiryDate','crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.RegisteredAddress','crpconsultantfinal.Village','crpconsultantfinal.Gewog','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','crpconsultantfinal.RegistrationExpiryDate','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
        endforeach;

        return View::make('report.unpaidconsultants')

            ->with('consultantsService',$consultantService)
            ->with('date',$date)
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
            ->with('appliedCategories',$appliedCategories)
            ->with('consultantServices',$consultantServices)
            ->with('appliedCategoryServices',$appliedCategoryServices)
            ->with('verifiedCategoryServices',$verifiedCategoryServices)
            ->with('appliedServices',$appliedServices)
            ->with('serviceApplicationApprovedForPayment',$serviceApplicationApprovedForPayment)
            ->with('serviceApplicationApproved',$serviceApplicationApproved)
            ->with('currentServiceClassifications',$currentServiceClassifications)
            ->with('generalInformation',$generalInformation)
            ->with('generalInformationFinal',$generalInformationFinal);
    }
    public function getServicesAvailed(){
        $fromDate = Input::has('FromDate')?$this->convertDate(Input::get('FromDate')):'2016-06-01';
        if($fromDate<'2016-06-01'){
            $fromDate = '2016-06-01';
        }
        $toDate = Input::has('ToDate')?$this->convertDate(Input::get('ToDate')):'--';

        $contractorServices = $consultantServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Update General Info"=>CONST_SERVICETYPE_GENERALINFORMATION,"Change of Location"=>CONST_SERVICETYPE_CHANGELOCATION,"Change of Owner"=>CONST_SERVICETYPE_CHANGEOWNER,"Category/Class Change"=>CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,"Update HR"=>CONST_SERVICETYPE_UPDATEHUMANRESOURCE,"Update Equipment"=>CONST_SERVICETYPE_UPDATEEQUIPMENT,"Late Fee"=>CONST_SERVICETYPE_LATEFEE,"Change of Firm Name"=>CONST_SERVICETYPE_CHANGEOFFIRMNAME,"Incorporation"=>CONST_SERVICETYPE_INCORPORATION);
        $architectServices = $engineerServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Late Fee"=>CONST_SERVICETYPE_LATEFEE);
        $spServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Late Fee"=>CONST_SERVICETYPE_LATEFEE,"Category/Class Change"=>CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION);
        //CONTRACTOR
        $contractorNewRegistrations = DB::table('crpcontractor as T1')
                                        ->join('crpcontractorfinal as T2','T2.Id','=','T1.Id')
                                        ->whereNull('T1.CrpContractorId')
                                        ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                                        ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                                        ->count(); //REGISTRATION

        $contractorServiceV = array();
        foreach($contractorServices as $contractorServiceName => $contractorService):
            $count= DB::table('crpcontractor as T1')
                    ->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
                    ->whereNotNull('T1.CrpContractorId')
                    ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                    ->where('T2.CmnServiceTypeId',$contractorService)
                    ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();
        $contractorServiceV[$contractorService]['Count'] = $count;
        $contractorServiceV[$contractorService]['Name'] = $contractorServiceName;
        endforeach; //SERVICES
        //CONSULTANT
        $consultantNewRegistrations = DB::table('crpconsultant as T1')
            ->join('crpconsultantfinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpConsultantId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $consultantServiceV = array();
        foreach($consultantServices as $consultantServiceName => $consultantService):
            $count= DB::table('crpconsultant as T1')
                ->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
                ->whereNotNull('T1.CrpConsultantId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$consultantService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $consultantServiceV[$consultantService]['Count'] = $count;
            $consultantServiceV[$consultantService]['Name'] = $consultantServiceName;
        endforeach; //SERVICES

        //ARCHITECT
        $architectNewRegistrations = DB::table('crparchitect as T1')
            ->join('crparchitectfinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpArchitectId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $architectServiceV = array();
        foreach($architectServices as $architectServiceName => $architectService):
            $count= DB::table('crparchitect as T1')
                ->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
                ->whereNotNull('T1.CrpArchitectId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$architectService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $architectServiceV[$architectService]['Count'] = $count;
            $architectServiceV[$architectService]['Name'] = $architectServiceName;
        endforeach; //SERVICES
        //ENGINEER
        $engineerNewRegistrations = DB::table('crpengineer as T1')
            ->join('crpengineerfinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpEngineerId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $engineerServiceV = array();
        foreach($engineerServices as $engineerServiceName => $engineerService):
            $count= DB::table('crpengineer as T1')
                ->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
                ->whereNotNull('T1.CrpEngineerId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$engineerService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $engineerServiceV[$engineerService]['Count'] = $count;
            $engineerServiceV[$engineerService]['Name'] = $engineerServiceName;
        endforeach; //SERVICES

        //SP
        $spNewRegistrations = DB::table('crpspecializedtrade as T1')
            ->join('crpspecializedtradefinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpSpecializedTradeId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $spServiceV = array();
        foreach($spServices as $spServiceName => $spService):
            $count= DB::table('crpspecializedtrade as T1')
                ->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
                ->whereNotNull('T1.CrpSpecializedTradeId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$spService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $spServiceV[$spService]['Count'] = $count;
            $spServiceV[$spService]['Name'] = $spServiceName;
        endforeach; //SERVICES
        return View::make('report.servicesavailed')
                    ->with('contractorNewRegistrations',$contractorNewRegistrations)
                    ->with('contractorServices',$contractorServices)
                    ->with('contractorServiceV',$contractorServiceV)
                    ->with('consultantNewRegistrations',$consultantNewRegistrations)
                    ->with('consultantServiceV',$consultantServiceV)
                    ->with('consultantServices',$consultantServices)
                    ->with('architectNewRegistrations',$architectNewRegistrations)
                    ->with('architectServices',$architectServices)
                    ->with('architectServiceV',$architectServiceV)
                    ->with('engineerNewRegistrations',$engineerNewRegistrations)
                    ->with('engineerServices',$engineerServices)
                    ->with('engineerServiceV',$engineerServiceV)
                    ->with('spNewRegistrations',$spNewRegistrations)
                    ->with('spServices',$spServices)
                    ->with('spServiceV',$spServiceV);

    }
    public function auditDroppedMemos(){
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $cdbNo = Input::get('CDBNo');
        $type = Input::get('Type'); //1 for contractor, 2 for consultant

        $parameters = array();
        $parametersForPrint = array();

        $query = "select concat(T4.FullName,' (',T4.username,')') as Dropper,T1.Agency, T1.AuditedPeriod,T1.AIN,T1.ParoNo,T1.AuditObservation,T1.DroppedDate, case when T1.Type = 1 then concat(T2.NameOfFirm,' (Contractor)') else concat(T3.NameOfFirm,' (Consultant)') end as NameOfFirm, case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo, T1.Remarks from crpcontractorauditclearance T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorConsultantId left join crpconsultantfinal T3 on T3.Id = T1.CrpContractorConsultantId join sysuser T4 on T4.Id = T1.SysDroppedByUserId where coalesce(T1.Dropped,0)=1";

        if((bool)$fromDate){
            $query.=" and T1.DroppedDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
            $parametersForPrint['From Date'] = $this->convertDate($fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.DroppedDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
            $parametersForPrint['To Date'] = $this->convertDate($toDate);
        }
        if((bool)$cdbNo){
            $query.=" and (T2.CDBNo = ? or T3.CDBNo = ?)";
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            $parametersForPrint['CDBNo'] = $cdbNo;
        }
        if((bool)$type){
            $query.=" and T1.Type = ?";
            array_push($parameters,$type);
            if($type == 1){
                $parametersForPrint['Type'] = "Contractors";
            }else{
                $parametersForPrint['Type'] = "Consultants";
            }
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/



        if(Input::get('export') == 'excel'){
            $reportData = DB::select($query." order by DroppedDate desc",$parameters);
            Excel::create('Audit Memo Report (Dropped)', function($excel) use ($reportData,$parametersForPrint) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportData,$parametersForPrint) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->setPaperSize(1);
                    $sheet->loadView('reportexcel.auditdroppedmemos')
                        ->with('parametersForPrint',$parametersForPrint)
                        ->with('reportData',$reportData);

                });

            })->export('xlsx');
        }else{
            $reportData = DB::select($query." order by DroppedDate desc".$limitOffsetAppend,$parameters);
        }
        return View::make('report.auditdroppedmemos')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('parametersForPrint',$parametersForPrint)
                    ->with('reportData',$reportData);
    }
    public function workOpportunityReport(){
        $fromDate = Input::has('FromDate')?$this->convertDate(Input::get('FromDate')):false;
        $toDate = Input::has('ToDate')?$this->convertDate(Input::get('ToDate')):false;
        $show = false;
        if(Input::has('x')){
            if(!(bool)$fromDate && !(bool)$toDate){
                return Redirect::to('ezotinrpt/etoolworkopportunityreport')->with('customerrormessage','Please select dates to view report');
            }else{
                $show =true;
            }
        }

        $totalNoOfWorks = DB::table('etltender as T1')
                            ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
                            ->whereNotNull('T2.AwardedAmount')
                            ->whereRaw("coalesce(T1.TenderSource,0) = 1")
                            ->whereRaw("case when T1.CmnWorkExecutionStatusId = ? then (T2.ActualStartDate >= ? and T2.ActualStartDate <= ?) else (T1.CommencementDateFinal >= ? and T1.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
                            ->whereIn('T1.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
                            ->get(array(DB::raw("distinct T1.Id")));

        $worksByClass = DB::table('etltender as T1')
                        ->join('cmncontractorclassification as A','T1.CmnContractorClassificationId','=','A.Id')
                        ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
                        ->whereNotNull('T2.AwardedAmount')
                        ->whereRaw("coalesce(T1.TenderSource,0) = 1")
                        ->whereRaw("case when T1.CmnWorkExecutionStatusId = ? then (T2.ActualStartDate >= ? and T2.ActualStartDate <= ?) else (T1.CommencementDateFinal >= ? and T1.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
                        ->whereIn('T1.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
                        ->groupBy('A.Id')
                        ->get(array('A.Code',DB::raw("count(T1.Id) as WorkCount")));

        $designations = DB::table('etltender as T1')
            ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
            ->join('etlcriteriahumanresource as T3','T3.EtlTenderId','=','T1.Id')
            ->join('cmnlistitem as T4','T4.Id','=','T3.CmnDesignationId')
            ->whereNotNull('T2.AwardedAmount')
            ->whereRaw("coalesce(T1.TenderSource,0) = 1")
            ->whereRaw("case when T1.CmnWorkExecutionStatusId = ? then (T2.ActualStartDate >= ? and T2.ActualStartDate <= ?) else (T1.CommencementDateFinal >= ? and T1.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
            ->whereIn('T1.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
            ->orderBy("T4.Name")
            ->get(array(DB::raw("distinct T3.CmnDesignationId"),"T4.Name as Designation"));

        foreach($designations as $designation):
            $designationId = $designation->CmnDesignationId;
            $countForDesignation = DB::table('etlcriteriahumanresource as T1')
                                    ->join('etltender as T2','T2.Id','=','T1.EtlTenderId')
                                    ->join('etltenderbiddercontractor as T3','T3.EtlTenderId','=','T2.Id')
                                    ->whereNotNull('T3.AwardedAmount')
                                    ->whereRaw("coalesce(T2.TenderSource,0) = 1")
                                    ->whereRaw("case when T2.CmnWorkExecutionStatusId = ? then (T3.ActualStartDate >= ? and T3.ActualStartDate <= ?) else (T2.CommencementDateFinal >= ? and T2.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
                                    ->whereIn('T2.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
                                    ->where('T1.CmnDesignationId',$designationId)
                                    ->whereRaw("T1.Points = (select Max(A.Points) from etlcriteriahumanresource A where A.EtlTenderId = T1.EtlTenderId and A.EtlTierId = T1.EtlTierId)")
                                    ->count();
            $designation->HRCount = $countForDesignation;
            unset($designation->CmnDesignationId);
        endforeach;

//        echo "<PRE>"; dd($designations);
        return View::make('report.workopportunityreport')
                ->with('show',$show)
                ->with('worksByClass',$worksByClass)
                ->with('designations',$designations)
                ->with('totalNoOfWorks',$totalNoOfWorks);
    }
    public function applicantsCancelledAfterNonPayment(){
        $type = Input::get('Type'); //1 - Contractor, 2 - Consultant, 3 - Architect, 4 - SP
        $cdbNo = Input::get('CDBNo');

        $parameters = array();
        $parametersForPrint = array();

        $query = "select case when T1.TypeCode = 1 then T2.CDBNo else case when T1.TypeCode = 2 then T4.CDBNo else case when T1.TypeCode = 3 then T6.ARNo else T8.SPNo end end end as CDBNo,case when T1.TypeCode = 1 then T2.ReferenceNo else case when T1.TypeCode = 2 then T4.ReferenceNo else case when T1.TypeCode = 3 then T6.ReferenceNo else T8.ReferenceNo end end end as ReferenceNo,
case when T1.TypeCode = 1 then T2.NameOfFirm else case when T1.TypeCode = 2 then T4.NameOfFirm else case when T1.TypeCode = 3 then T6.Name else T8.Name end end end as Applicant,
T1.DateOfNotification, T1.TypeCode from crpapplicantpaymentnotice T1 left join (crpcontractor T2 left join crpcontractorfinal T3 on T3.Id = T2.CrpContractorId) on T2.Id = T1.ApplicantId left JOIN (crpconsultant T4 left join crpconsultantfinal T5 on T5.Id = T4.CrpConsultantId) on T4.Id = T1.ApplicantId left JOIN (crparchitect T6 left join crparchitectfinal T7 on T7.Id = T6.CrpArchitectId) on T6.Id = T1.ApplicantId left JOIN (crpspecializedtrade T8 left join crpspecializedtradefinal T9 on T9.Id = T8.CrpSpecializedTradeId) on T8.Id = T1.ApplicantId WHERE 1";

        if((bool)$type){
            $query.=" and T1.TypeCode = ?";
            array_push($parameters,(int)$type);
            $parametersForPrint['Type'] = ($type == 1)?"Contractor":(($type==2)?"Consultant":(($type==3)?"Architect":"Specialized Trade"));
        }
        if((bool)$cdbNo){
            $query.=" and case when T1.TypeCode = 1 then T2.CDBNo = ? else case when T1.TypeCode = 2 then T4.CDBNo = ? else case when T1.TypeCode = 3 then T6.ARNo = ? else T8.SPNo = ? end end end";
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            $parametersForPrint['CDBNo'] = $cdbNo;
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select("$query order by DateOfNotification DESC$limitOffsetAppend",$parameters);

        if(Input::get('export') == 'excel'){
            $reportData = DB::select("$query order by DateOfNotification DESC",$parameters);
            Excel::create('Applications cancelled after non payment', function($excel) use ($reportData,$parametersForPrint) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportData,$parametersForPrint) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->setPaperSize(1);
                    $sheet->loadView('reportexcel.applicantscancelledafternonpayment')
                        ->with('parametersForPrint',$parametersForPrint)
                        ->with('reportData',$reportData);

                });

            })->export('xlsx');
        }else{
            $reportData = DB::select("$query order by DateOfNotification DESC$limitOffsetAppend",$parameters);
        }

        return View::make('report.applicantscancelledafternonpayment')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('reportData',$reportData);

    }
}