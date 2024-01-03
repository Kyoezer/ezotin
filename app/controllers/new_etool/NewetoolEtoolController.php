<?php
class NewetoolEtoolController extends BaseController{
    public function __construct(){
        $route = Request::segment(1).'/'.Request::segment(2);
        if($route != "etl/mydashboard"){
            $email = Auth::user()->Email;
            if(!(bool)$email){
                return Redirect::to('newEtl/mydashboard')->with('customerrormessage',"<strong>WARNING!</strong> To continue using Etool, please update your email address.")->send();
            }
        }
        $userId = Auth::user()->Id;
        $userProcuringAgencyId = DB::table('sysuser')->where('Id',$userId)->pluck('CmnProcuringAgencyId');
        $userEmailId = DB::table('sysuser')->where('Id',$userId)->pluck('Email');
        $fullName = DB::table('sysuser')->where('Id',$userId)->pluck('FullName');
        $worksForUser = DB::select("select distinct T1.Id, case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId, T1.NameOfWork, T2.ActualStartDate, T2.ActualEndDate, T2.AwardedAmount from etltender T1 join cmnprocuringagency A on A.Id = T1.CmnProcuringAgencyId join etltenderbiddercontractor T2 on T1.Id = T2.EtlTenderId and T2.ActualStartDate is not null where T1.CmnWorkExecutionStatusId = ? and T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and ((DATEDIFF(T2.ActualEndDate, CURDATE()) <= 20) && (DATEDIFF(T2.ActualEndDate,CURDATE())>=0)) and T1.Id not in (select EtoolCinetWorkId from sysnewsandnotification where CmnProcuringAgencyId = '$userProcuringAgencyId' and MessageFor = 2 and DisplayIn = 2)",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$userProcuringAgencyId));
        if($worksForUser){
            foreach($worksForUser as $work){
                $message = "Work Id $work->WorkId ($work->NameOfWork) which started on $work->ActualStartDate is nearing completion. Please fill in the completion form when the work is completed.";
                $insertArray['Id'] = $this->UUID();
                $insertArray['MessageFor'] = 2;
                $insertArray['CmnProcuringAgencyId'] = $userProcuringAgencyId;
                $insertArray['DisplayIn'] = 2;
                $insertArray['Date'] = date('Y-m-d');
                $insertArray['Message'] = $message;
                $insertArray['EtoolCinetWorkId'] = $work->Id;
                SysNewsAndNotificationModel::create($insertArray);
                if($userEmailId){
                    $mailData=array(
                        'mailMessage'=>$message
                    );
                    $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Work Completion Notification",$userEmailId,$fullName);
                }
            }
        }else{
            $worksWithNotification = DB::table('sysnewsandnotification as T1')
                                    ->join('etltender as T2','T2.Id','=','T1.EtoolCinetWorkId')
                                    ->where('T1.CmnProcuringAgencyId',$userProcuringAgencyId)
                                    ->where('T1.MessageFor',2)
                                    ->where('T1.DisplayIn',2)
                                    ->where('T2.CmnWorkExecutionStatusId','<>',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                                    ->get(array('T1.Id'));
            if($worksWithNotification){
                foreach($worksWithNotification as $workWithNot){
                    DB::table('sysnewsandnotification')->where('Id',$workWithNot->Id)->delete();
                }
            }
        }

    }
    public function calculatePointsOnSimilarWorkExperience($count, $contractorId, $contractorClassificationId, $contractorCategoryId, $startDate, $endDate,$tenderEstimate,$stake)
    {
        
        $tenderValue = DB::table('etltender as T1')
            ->join('etltenderbiddercontractor as T2', 'T1.Id', '=', 'T2.EtlTenderId')
            ->join('etltenderbiddercontractordetail as T3', 'T3.EtlTenderBidderContractorId', '=', 'T2.Id')
            ->where('T3.CrpContractorFinalId', '=', $contractorId)
            ->where('T1.CmnContractorCategoryId', '=', $contractorCategoryId)
            ->where('T1.TenderSource', 1)
            ->where(DB::raw("coalesce(T1.DeleteStatus,'N')"),'<>','Y')
            ->whereNotNull('T2.ActualStartDate')
            ->whereNotNull('T1.ContractPriceFinal')
            ->whereBetween('T1.CompletionDateFinal', array($startDate, $endDate))
            ->where(DB::raw('coalesce(T1.CmnWorkExecutionStatusId,0)'), '=', CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
            ->orderBy('T1.ContractPriceFinal', 'DESC')
            ->limit(3)
            ->lists('T1.ContractPriceFinal');
        $bidValue = DB::table('crpbiddingform as T1')
            ->join('crpbiddingformdetail as T2', 'T2.CrpBiddingFormId', '=', 'T1.Id')
            ->where('T2.CrpContractorFinalId', '=', $contractorId)
            ->whereBetween('T1.CompletionDateFinal', array($startDate, $endDate)) //COmpletion date is between 2011-01-01 and 2015-12-31
            ->where('T1.CmnContractorProjectCategoryId', '=', $contractorCategoryId)
            ->whereNotNull('T1.ContractPriceFinal')
            ->where('T1.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
            ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->orderBy('T1.ContractPriceFinal', 'DESC')
            ->limit(3)
            ->lists('T1.ContractPriceFinal');
        
        $totalValueArray = array_merge($tenderValue, $bidValue);
        rsort($totalValueArray);

	    $maxValueOfOneProject = isset($totalValueArray[0])?$totalValueArray[0]:0;
        if($count == 0){
            $point = 0;
        }else{
            $valueForOne = $maxValueOfOneProject;
            $ratioForOne = $valueForOne/(int)$tenderEstimate; //Where Ratio is calculated
            $ratioForOne *= 100;
	     $ratioForOne = round($ratioForOne,2);

            $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_SWE1)->get(array('MaxPoints','MinPoints'));
            $upperLimitForOne = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE1)->max('UpperLimit');
            $lowerLimitForOne = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE1)->min('LowerLimit');
            $upperLimitForOne = (float)$upperLimitForOne;
	     $lowerLimitForOne = (float)$lowerLimitForOne;
	     $upperLimitForOne = round($upperLimitForOne,2);
	     $lowerLimitForOne = round($lowerLimitForOne,2);
	     $minMaxForOne = false;
            if($ratioForOne >= $upperLimitForOne){
                $minMaxForOne = true;
                $pointForOne = $maxAndMinPoints[0]->MaxPoints;
            }
            if($ratioForOne < $lowerLimitForOne){
                $minMaxForOne = true;
                $pointForOne = $maxAndMinPoints[0]->MinPoints;
            }
            if(!$minMaxForOne){
                $pointForOne = DB::table('etlpointdefinition as T1')
                    ->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE1)
                    ->where('LowerLimit','<=',$ratioForOne)
                    ->where('UpperLimit','>',$ratioForOne)
                    ->pluck('Points');
            }
            if($count > 3){
                $maxValueOfMoreThanOneProject = $totalValueArray[0] + $totalValueArray[1] + $totalValueArray[2];
            }
            if($count > 1 && $count <= 3){
                $maxValueOfMoreThanOneProject = TenderModel::countContractorTenderCompleted($contractorId,$startDate,$endDate,$contractorClassificationId,$contractorCategoryId)->sum('etltender.ContractPriceFinal') + CrpBiddingFormModel::countContractorWorkCompleted($contractorId,$startDate,$endDate,$contractorClassificationId,$contractorCategoryId)->sum('crpbiddingform.ContractPriceFinal');
            }
            if(!isset($maxValueOfMoreThanOneProject)){
                $maxValueOfMoreThanOneProject = 0;
            }

            $value = $maxValueOfMoreThanOneProject;
            $ratio = $value/(int)$tenderEstimate; //Where Ratio is calculated
            $ratio *= 100;
	     $ratio = (float)$ratio;
	     $ratio = round($ratio,2);

            $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_SWE2)->get(array('MaxPoints','MinPoints'));
            $upperLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE2)->max('UpperLimit');
            $lowerLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE2)->min('LowerLimit');
            $upperLimit = (float)$upperLimit;
	     $lowerLimit = (float)$lowerLimit;
	     $upperLimit = round($upperLimit,2);
	     $lowerLimit = round($lowerLimit,2);

	     $minMax = false;
            if($ratio >= $upperLimit){
                $minMax = true;
                $pointForMoreThanOne = $maxAndMinPoints[0]->MaxPoints;
            }
            if($ratio < $lowerLimit){
                $minMax = true;
                $pointForMoreThanOne = $maxAndMinPoints[0]->MinPoints;
            }
            if(!$minMax){
                $pointForMoreThanOne = DB::table('etlpointdefinition as T1')
                    ->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE2)
                    ->where('LowerLimit','<=',$ratio)
                    ->where('UpperLimit','>',$ratio)
                    ->pluck('Points');
            }
        }

        if($count != 0){
            if($pointForOne > $pointForMoreThanOne){
                $point = $pointForOne;
            }else{
                if($pointForMoreThanOne > $pointForOne){
                    $point = $pointForMoreThanOne;
                }else{
                    $point = $pointForOne;
                }
            }
        }


        $result = $point;
        return $result*($stake/100);
        $totalValueArray = array();
    }
    public function calculatePointsOnPerformance($count, $contractorId, $contractorClassificationId, $contractorCategoryId, $startDate, $endDate,$tenderEstimate,$stake){
        $curYear = date('Y');
        $fiveYearsPrev = (int)$curYear - 4;
        $startDate = "$fiveYearsPrev-01-01";
        $endDate = "$curYear-12-31";
        $count = TenderModel::countContractorAllTenderCompleted($contractorId,$startDate,$endDate)->whereNotNull('etltender.OntimeCompletionScore')->count() + CrpBiddingFormModel::countContractorAllWorkCompleted($contractorId,$startDate,$endDate)->whereNotNull('crpbiddingform.OntimeCompletionScore')->count();
        if($count >= 1){	
            $points = 0;
//            $value = TenderModel::countContractorAllTenderCompleted($contractorId,$startDate,$endDate)->whereNotNull('etltender.OntimeCompletionScore')->sum(DB::raw('coalesce(etltender.OntimeCompletionScore,0)+coalesce(etltender.QualityOfExecutionScore,0)')) + CrpBiddingFormModel::countContractorAllWorkCompleted($contractorId,$startDate,$endDate)->whereNotNull('crpbiddingform.OntimeCompletionScore')->sum(DB::raw('coalesce(crpbiddingform.OntimeCompletionScore,0)+coalesce(crpbiddingform.QualityOfExecutionScore,0)'));
            //$value = $value/$count;
//            if($value == 100) {
//                $points = 10;
//            }
//            if(($value < 100) && ($value >= 50)) {
//                $points = 10 - (ceil((100 - $value) / 5));
//            }
            $allTenders = TenderModel::countContractorAllTenderCompleted($contractorId,$startDate,$endDate)
                            ->whereNotNull('etltender.OntimeCompletionScore')
                            ->get(array(DB::raw('coalesce(etltender.OntimeCompletionScore,0)+coalesce(etltender.QualityOfExecutionScore,0) as APS')));
            foreach($allTenders as $allTender):
                $value = $allTender->APS;
                $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_APS)->get(array('MaxPoints','MinPoints'));
                $upperLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)->max('UpperLimit');
                $lowerLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)->min('LowerLimit');
                $minMax = false;
                if($value >= $upperLimit){
                    $minMax = true;
                    $point = $maxAndMinPoints[0]->MaxPoints;
                }
                if($value < $lowerLimit){
                    $minMax = true;
                    $point = $maxAndMinPoints[0]->MinPoints;
                }
                if(!$minMax){
                    $point = DB::table('etlpointdefinition as T1')
                        ->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)
                        ->where('LowerLimit','<=',$value)
                        ->where('UpperLimit','>',$value)
                        ->pluck('Points');
                }
                $points+=$point;
            endforeach;

            $allBids = CrpBiddingFormModel::countContractorAllWorkCompleted($contractorId,$startDate,$endDate)->whereNotNull('crpbiddingform.OntimeCompletionScore')
                            ->get(array(DB::raw('coalesce(crpbiddingform.OntimeCompletionScore,0)+coalesce(crpbiddingform.QualityOfExecutionScore,0) as APS')));
            foreach($allBids as $allBid):
                $value = $allBid->APS;
                $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_APS)->get(array('MaxPoints','MinPoints'));
                $upperLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)->max('UpperLimit');
                $lowerLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)->min('LowerLimit');
                $minMax = false;
                if($value >= $upperLimit){
                    $minMax = true;
                    $point = $maxAndMinPoints[0]->MaxPoints;
                }
                if($value < $lowerLimit){
                    $minMax = true;
                    $point = $maxAndMinPoints[0]->MinPoints;
                }
                if(!$minMax){
                    $point = DB::table('etlpointdefinition as T1')
                        ->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)
                        ->where('LowerLimit','<=',$value)
                        ->where('UpperLimit','>',$value)
                        ->pluck('Points');
                }
                $points+=$point;
            endforeach;
            $points/=$count;
//            $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_APS)->get(array('MaxPoints','MinPoints'));
//            $upperLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)->max('UpperLimit');
//            $lowerLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)->min('LowerLimit');
//            $minMax = false;
//            if($value >= $upperLimit){
//                $minMax = true;
//                $points = $maxAndMinPoints[0]->MaxPoints;
//            }
//            if($value < $lowerLimit){
//                $minMax = true;
//                $points = $maxAndMinPoints[0]->MinPoints;
//            }
//            if(!$minMax){
//                $points = DB::table('etlpointdefinition as T1')
//                    ->where('EtlPointTypeId',CONST_ETLPARAMETER_APS)
//                    ->where('LowerLimit','<=',$value)
//                    ->where('UpperLimit','>',$value)
//                    ->pluck('Points');
//            }

		/*if($value <100 && $value >=95){
		   $points = 9;
		}
		if($value <95 && $value >=90){
		   $points = 8;
		}
		if($value <90 && $value >=85){
		   $points = 7;
		}
		if($value <85 && $value >=80){
		   $points = 6;
		}
		if($value <80 && $value >=75){
		   $points = 5;
		}
		if($value <75 && $value >=70){
		   $points = 4;
		}
		if($value <70 && $value >=65){
		   $points = 3;
		}
		if($value <65 && $value >=60){
		   $points = 2;
		}
		if($value <60 && $value >=55){
		   $points = 1;
		}
		if($value < 55){
		   $points = 0;
		}*/
        }else{
            $points = 10;
        }
        return $points*($stake/100);
    }
    public function calculatePointsOnEquipment($tenderId, $contractorId,$maxPoints){
        $points = DB::table('etlcontractorequipment as T1')
            ->join('etltenderbiddercontractor as T2','T1.EtlTenderBidderContractorId','=','T2.Id')
            ->join('etltenderbiddercontractordetail as T3','T3.EtlTenderBidderContractorId','=','T2.Id')
            ->where('T2.EtlTenderId','=',$tenderId)
            ->where('T3.CrpContractorFinalId','=',$contractorId)
            ->sum('T1.Points');
        if($maxPoints == 0){
            return 0;
        }
        $finalPoints = (($points/0.25)/$maxPoints * 25);
        if($finalPoints > 25){
            $finalPoints = 25;
        }
        return $finalPoints;
    }
    public function calculatePointsOnHR($tenderId,$contractorId,$maxPoints){
        $points = DB::table('etlcontractorhumanresource as T1')
                            ->join('etltenderbiddercontractor as T2','T1.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('etltenderbiddercontractordetail as T3','T3.EtlTenderBidderContractorId','=','T2.Id')
                            ->where('T2.EtlTenderId','=',$tenderId)
                            ->where('T3.CrpContractorFinalId','=',$contractorId)
                            ->sum('T1.Points');
        if($maxPoints == 0){
            return 0;
        }
        $finalPoints = (($points/0.25)/$maxPoints * 25);
        if($finalPoints>25){
            $finalPoints = 25;
        }
        return $finalPoints;
    }
    public function calculateBidCapacity($contractorId,$startDate,$endDate,$contractorClassificationId, $contractorCategoryId,$stake,$tenderId,$tenderValue){
        $A = $this->calculateA($contractorId,$startDate,$endDate,$contractorClassificationId, $contractorCategoryId);
        if(gettype($A) == 'array'){
            return $A;
        }
        $N = $this->calculateN($tenderId);
        $B = $this->calculateB($contractorId,$contractorClassificationId,$contractorCategoryId,$tenderId);
        $bidCapacity =  ((2.5*$A*$N) - $B)*($stake/100);

	    return $bidCapacity;
    }
    public function calculateBidCapacityResult($bidCapacity,$quotedAmount){
        if((float)$bidCapacity==0){
            $ratio=0;
        }else{
            $ratio = $bidCapacity/$quotedAmount;
        }
        $ratio *= 100;
        $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_BC)->get(array('MaxPoints','MinPoints'));
        $upperLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_BC)->max('UpperLimit');
        $lowerLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_SWE1)->min('LowerLimit');
        $minMax = false;
        if($ratio >= $upperLimit){
            $minMax = true;
            $points = $maxAndMinPoints[0]->MaxPoints;
        }
        if($ratio < $lowerLimit){
            $minMax = true;
            $points = $maxAndMinPoints[0]->MinPoints;
        }
        if(!$minMax){
            $points = DB::table('etlpointdefinition as T1')
                ->where('EtlPointTypeId',CONST_ETLPARAMETER_BC)
                ->where('LowerLimit','<=',$ratio)
                ->where('UpperLimit','>',$ratio)    // 40 <= $ratio > 60
                ->pluck('Points');
        }
//        if($ratio > 1){
//            $points = 10;
//        }
//        if($ratio > 0.8 && $ratio <= 1){
//            $points = 8;
//        }
//        if($ratio > 0.6 && $ratio <= 0.8){
//            $points = 6;
//        }
//        if($ratio > 0.4 && $ratio <= 0.6){
//            $points = 4;
//        }
//        if($ratio <= 0.4){
//            $points = 0;
//        }
        return $points?$points:0;
    }
    public function calculateA($contractorId,$startDate,$endDate,$contractorClassificationId, $contractorCategoryId)
    {
        $tenderA = array();
        $startYear = date_format(date_create($startDate),'Y');
        $year1 = $startYear;
        $yearStart[0] = "$year1-01-01";
        $yearEnd[0] = "$year1-12-31";
        $year2 = $year1 + 1;
        $yearStart[1] = "$year2-01-01";
        $yearEnd[1] = "$year2-12-31";
        $year3 = $year2 + 1;
        $yearStart[2] = "$year3-01-01";
        $yearEnd[2] = "$year3-12-31";
        $tenderA[$year1] = 0;
        $tenderA[$year2] = 0;
        $tenderA[$year3] = 0;
        $bidA[$year1] = 0;
        $bidA[$year2] = 0;
        $bidA[$year3] = 0;
        for($i = 0; $i<3; $i++){
            $contractorTenderProjects[$i] = DB::table('etltender as A')
                ->join('etltenderbiddercontractor as T1','T1.EtlTenderId','=','A.Id')
                ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
                ->join('crpcontractorfinal as C','C.Id','=','T2.CrpContractorFinalId')
                ->join('cmnprocuringagency as D','D.Id','=','A.CmnProcuringAgencyId')
                ->where('T2.CrpContractorFinalId','=',$contractorId)
                ->whereNotNull('T1.ActualStartDate')
                ->where(DB::raw("coalesce(A.DeleteStatus,'N')"),'<>','Y')
                ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualStartDate else A.CommencementDateFinal end'),'<',$yearEnd[$i])
                ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualEndDate else A.CompletionDateFinal end'), '>',$yearStart[$i])
                ->whereIn(DB::raw('coalesce(A.CmnWorkExecutionStatusId,0)'),array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
                ->get(array('A.Id',DB::raw("case when A.migratedworkid is null then concat(D.Code,'/',year(A.UploadedDate),'/',A.WorkId) else A.migratedworkid end as WorkId"),'C.CDBNo',DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualStartDate else A.CommencementDateFinal end as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualEndDate else A.CompletionDateFinal end as CompletionDateFinal'),DB::raw("'$yearStart[$i]' as YearStart"),DB::raw("'$yearEnd[$i]' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.AwardedAmount else A.ContractPriceFinal end as ContractPriceFinal'),DB::raw("period_diff(date_format(coalesce(A.CompletionDateFinal,T1.ActualEndDate),'%Y%m'),date_format(coalesce(A.CommencementDateFinal,T1.ActualStartDate),'%Y%m')) as Duration")));
            $contractorBidProjects[$i] = DB::table('crpbiddingform as A')
                ->join('crpbiddingformdetail as T2','T2.CrpBiddingFormId','=','A.Id')
                ->join('crpcontractorfinal as C','C.Id','=','T2.CrpContractorFinalId')
                ->where('T2.CrpContractorFinalId','=',$contractorId)
                ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->whereIn(DB::raw('coalesce(A.CmnWorkExecutionStatusId,0)'),array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
                ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end'),'<',$yearEnd[$i]) //Start Date < 2012-12-31
                ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end'), '>',$yearStart[$i]) //Completion Date > 2012-01-01
                ->get(array('A.Id','A.CmnWorkExecutionStatusId','A.NameOfWork','C.CDBNo','A.WorkStartDate','A.WorkCompletionDate','A.CommencementDateFinal','A.CompletionDateFinal',DB::raw("'$yearStart[$i]' as YearStart"),DB::raw("'$yearEnd[$i]' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end as CompletionDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T2.EvaluatedAmount else A.ContractPriceFinal end as ContractPriceFinal'),DB::raw("period_diff(date_format(coalesce(A.CompletionDateFinal,A.WorkCompletionDate),'%Y%m'),date_format(coalesce(A.CommencementDateFinal,A.WorkStartDate),'%Y%m')) as Duration")));
                 
        }
        foreach($contractorTenderProjects as $key=>$value){
            foreach($value as $x=>$y){
                $commencementDateFinal = date_create($y->CommencementDateFinal);
                $start = date_create($y->YearStart);
                $completionDateFinal = date_create($y->CompletionDateFinal);
                $end = date_create($y->YearEnd);
                $startYear = date_format($commencementDateFinal,'Y');
                $endYear = date_format($completionDateFinal,'Y');

                if($commencementDateFinal >= $start){
                    $startOfProjectForYear = $commencementDateFinal;
                }
                if($commencementDateFinal < $start){
                    $startOfProjectForYear = $start;
                }
                if($completionDateFinal >= $end){
                    $endOfProjectForYear = $end;
                }
                if($completionDateFinal < $end){
                    $endOfProjectForYear = $completionDateFinal;
                }
                $diff = (int)(date_diff($startOfProjectForYear,$endOfProjectForYear)->format('%a'));
                if((((int)(date_diff(date_create($y->CompletionDateFinal),date_create($y->CommencementDateFinal))->format('%a')))) <= 0){
                    $response['error'] = 1;
                    $response['message'] = "<strong>Error! Could not process result.</strong> Contractor <em>$y->CDBNo</em> has wrong Completion and Commencement Dates for <u>$y->WorkId</u>. Please contact administrator";
                    $response['CompletionDateFinal'] = $y->CompletionDateFinal;
                    $response['CommencementDateFinal'] = $y->CommencementDateFinal;
                    return $response;
                }
		  /*if((date_diff(date_create($y->CompletionDateFinal),date_create($y->CommencementDateFinal))->format('%a') == 0)){
		      dd($contractorId,$y->Id);
		  }*/
                $tenderA[date_format($start,'Y')] += ($y->ContractPriceFinal/(((int)(date_diff(date_create($y->CompletionDateFinal),date_create($y->CommencementDateFinal))->format('%a'))))) * $diff;
            }
        }
        foreach($contractorBidProjects as $key2=>$value2){
            foreach($value2 as $x2=>$y2){
                $commencementDateFinal = date_create($y2->CommencementDateFinal);
                $start = date_create($y2->YearStart);
                $completionDateFinal = date_create($y2->CompletionDateFinal);
                $end = date_create($y2->YearEnd);
                if($commencementDateFinal >= $start){
                    $startOfProjectForYear = $commencementDateFinal;
                }
                if($commencementDateFinal < $start){
                    $startOfProjectForYear = $start;
                }
                if($completionDateFinal >= $end){
                    $endOfProjectForYear = $end;
                }
                if($completionDateFinal < $end){
                    $endOfProjectForYear = $completionDateFinal;
                }
                $diff = (int)(date_diff($startOfProjectForYear,$endOfProjectForYear)->format('%a'));
                if((((int)(date_diff($completionDateFinal,$commencementDateFinal)->format('%a')))) <= 0){
                    $response['error'] = 1;
                    $response['message'] = "<strong>Error! Could not process result.</strong> Contractor <em>$y2->CDBNo</em> has wrong Completion and Commencement Dates for <u>$y2->NameOfWork</u>. Please contact administrator";
                    $response['CompletionDateFinal'] = $y2->CompletionDateFinal;
                    $response['CommencementDateFinal'] = $y2->CommencementDateFinal;
                    return $response;
                }

                $bidA[date_format($start,'Y')] += ($y2->ContractPriceFinal/((int)(date_diff(date_create($y2->CompletionDateFinal),date_create($y2->CommencementDateFinal))->format('%a')))) * $diff;
            }
        }
        $tenderA[$year1] *= 1.05 * 1.05;
        $tenderA[$year2] *= 1.05;
        $bidA[$year1] *= 1.05 * 1.05;
        $bidA[$year2] *= 1.05;
        $A = ($tenderA[$year1] + $tenderA[$year2] + $tenderA[$year3] + $bidA[$year1] +$bidA[$year2] + $bidA[$year3])/3;
		return round($A,3);
    }
    public function calculateN($tenderId){
        $contractPeriod = DB::table('etltender')
                            ->where('Id','=',$tenderId)
                            ->pluck('ContractPeriod');
        $years =  $contractPeriod /12;
        $N = (($years + (ceil(($years/0.5)) - ($years)))*0.5);
        return $N;
        
    }
    public function calculateB($contractorId,$contractorClassificationId, $contractorCategoryId,$tenderId){
        $B = 0;
        $currentProjectDates = DB::table('etltender')->where('Id','=',$tenderId)->get(array('TentativeStartDate','TentativeEndDate'));
        $projectStartDate = date_format(date_create($currentProjectDates[0]->TentativeStartDate),'Y-m-d');
        $projectEndDate = date_format(date_create($currentProjectDates[0]->TentativeEndDate),'Y-m-d');
        
        $contractorTenderProjects = DB::table('etltender as A')
            ->join('etltenderbiddercontractor as T1','T1.EtlTenderId','=','A.Id')
            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
            ->join('crpcontractorfinal as C','C.Id','=','T2.CrpContractorFinalId')
            ->join('cmnprocuringagency as D','D.Id','=','A.CmnProcuringAgencyId')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->whereNotNull('T1.ActualStartDate')
            ->where(DB::raw("coalesce(A.DeleteStatus,'N')"),'<>','Y')
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualStartDate else A.CommencementDateFinal end'),'<',$projectEndDate)
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualEndDate else A.CompletionDateFinal end'), '>',$projectStartDate)
//            ->where('A.TenderSource',1)
            ->whereIn(DB::raw('coalesce(A.CmnWorkExecutionStatusId,0)'),array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
            ->select('A.Id','C.CDBNo',DB::raw("case when A.migratedworkid is null then concat(D.Code,'/',year(A.UploadedDate),'/',A.WorkId) else A.migratedworkid end as WorkId"),DB::raw("'$projectStartDate' as YearStart"),DB::raw("'$projectEndDate' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualStartDate else A.CommencementDateFinal end as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualEndDate else A.CompletionDateFinal end as CompletionDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.AwardedAmount else A.ContractPriceFinal end as ContractPriceFinal'))
            ->get();
        $contractorBidProjects = DB::table('crpbiddingform as A')
            ->join('crpbiddingformdetail as T2','T2.CrpBiddingFormId','=','A.Id')
            ->join('crpcontractorfinal as C','C.Id','=','T2.CrpContractorFinalId')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->whereIn('A.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end'),'<',$projectEndDate)
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end'), '>',$projectStartDate)
            ->get(array('A.Id','C.CDBNo','A.NameOfWork',DB::raw("'$projectStartDate' as YearStart"),DB::raw("'$projectEndDate' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end  as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end  as CompletionDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T2.EvaluatedAmount else A.ContractPriceFinal end as ContractPriceFinal')));
       foreach ($contractorTenderProjects as $tenderProject):
            $yearStart = date_create($tenderProject->YearStart); //TENTATIVE START DATE
            $yearEnd = date_create($tenderProject->YearEnd); //TENTATIVE END DATE
            $commencementDateFinal = date_create($tenderProject->CommencementDateFinal); //CURRENT PROJECT START DATE
            $completionDateFinal = date_create($tenderProject->CompletionDateFinal); //CURRENT PROJECT END DATE
            $diff = (int)(date_diff($completionDateFinal,$commencementDateFinal)->format('%a'));
            if($diff <= 0){
                $response['error'] = 1;
                $response['message'] = "<strong>Error! Could not process result.</strong> Contractor <em>$tenderProject->CDBNo</em> has wrong Completion and Commencement Dates for <u>$tenderProject->WorkId</u>. Please contact administrator";
                $response['CompletionDateFinal'] = $tenderProject->CompletionDateFinal;
                $response['CommencementDateFinal'] = $tenderProject->CommencementDateFinal;
                return $response;
            }
            $projectValuePerDay = $tenderProject->ContractPriceFinal/($diff);


            /*if($commencementDateFinal > $yearStart){ //Old Project started after New Project 
		$B+=((int)(date_diff($commencementDateFinal,$completionDateFinal)->format('%a'))) * $projectValuePerDay;
            }

            if(($yearStart > $commencementDateFinal) && ($completionDateFinal > $yearStart)){
                $B+=((int)(date_diff($yearStart,$completionDateFinal)->format('%a'))) * $projectValuePerDay;
            }

            if(($yearStart>$commencementDateFinal) && ($completionDateFinal >= $yearEnd)){
                $B+=((int)(date_diff($yearStart,$yearEnd)->format('%a'))) * $projectValuePerDay;
            }*/

	        if($commencementDateFinal > $yearStart){ //Old Project started after New Project
                $start = $commencementDateFinal;
            }
            if($commencementDateFinal == $yearStart){ //Old Project and New Project start at same time
                $start = $yearStart;
            }
            if($commencementDateFinal < $yearStart){ //Old Project started before New Project
                $start = $yearStart;
            }
            if($completionDateFinal > $yearEnd){ //Old Project ends after New Project Ends
                $end = $yearEnd;
            }
            if($completionDateFinal == $yearEnd){ //Old Project and New Project end at same time
                $end = $yearEnd;
            }
            if($completionDateFinal < $yearEnd){ //Old Project ends before New Project
                $end = $completionDateFinal;
            }
		$B += ((int)(date_diff($start,$end)->format('%a'))+1) * $projectValuePerDay;
				
        endforeach;
        foreach ($contractorBidProjects as $bidProject):
            $yearStart = date_create($bidProject->YearStart);
            $yearEnd = date_create($bidProject->YearEnd);
            $commencementDateFinal = date_create($bidProject->CommencementDateFinal);
            $completionDateFinal = date_create($bidProject->CompletionDateFinal);
            $diff = (int)(date_diff($completionDateFinal,$commencementDateFinal)->format('%a'));
            if($diff <= 0){
                $response['error'] = 1;
                $response['message'] = "<strong>Error! Could not process result.</strong> Contractor <em>$bidProject->CDBNo</em> has wrong Completion and Commencement Dates for <u>$bidProject->NameofWork</u>. Please contact administrator";
                $response['CompletionDateFinal'] = $bidProject->CompletionDateFinal;
                $response['CommencementDateFinal'] = $bidProject->CommencementDateFinal;
                return $response;
            }
            $projectValuePerDay = $bidProject->ContractPriceFinal/($diff);
            if($commencementDateFinal > $yearStart){ //Old Project started after New Project
                $B+=((int)(date_diff($commencementDateFinal,$completionDateFinal)->format('%a'))) * $projectValuePerDay;
            }


            if(($yearStart > $commencementDateFinal) && ($completionDateFinal > $yearStart)){
                $B+=((int)(date_diff($yearStart,$completionDateFinal)->format('%a'))) * $projectValuePerDay;
            }

            if(($yearStart>$commencementDateFinal) && ($completionDateFinal >= $yearEnd)){
                $B+=((int)(date_diff($yearStart,$yearEnd)->format('%a'))) * $projectValuePerDay;
            }
        endforeach;
        return $B;
    }
    public function calculateCreditLine($contractorId, $tenderId,$stake,$sequence){
	 
        $creditLineAvailable = DB::table('etltenderbiddercontractor as T1')
                                ->join('etlcontractorcapacity as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
                                ->join('etltenderbiddercontractordetail as T3','T1.Id','=','T3.EtlTenderBidderContractorId')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->where('T2.Sequence','=',$sequence)
                                ->where('T3.CrpContractorFinalId','=',$contractorId)
                                ->sum('T2.Amount');
        return ($creditLineAvailable*($stake/100));
    }
    public function calculatePointsOnCreditLine($tenderId,$tenderValue,$amount){
        $duration = DB::table('etltender')->where('Id','=',$tenderId)->pluck('ContractPeriod');
        $estimateThreeMonthCashFlow = ($tenderValue/$duration) * 3;
        $ratio = $amount/$estimateThreeMonthCashFlow;
        $ratio *= 100;
	    $ratio = round($ratio,2);
        $maxAndMinPoints = DB::table('etlpointtype')->where('Id',CONST_ETLPARAMETER_CL)->get(array('MaxPoints','MinPoints'));
        $upperLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_CL)->max('UpperLimit');
        $lowerLimit = DB::table('etlpointdefinition')->where('EtlPointTypeId',CONST_ETLPARAMETER_CL)->min('LowerLimit');
        $upperLimit = (float)$upperLimit;
	 $lowerLimit = (float)$lowerLimit;
	 $upperLimit = round($upperLimit,2);
	 $lowerLimit = round($lowerLimit,2);
	 
	 $minMax = false;
        if($ratio >= $upperLimit){ //Greater than or equal to 100
            $minMax = true;
            $points = $maxAndMinPoints[0]->MaxPoints;
        }
        if($ratio < $lowerLimit){ //Lesser than 60
            $minMax = true;
            $points = $maxAndMinPoints[0]->MinPoints;
        }
        if(!$minMax){
            $points = DB::table('etlpointdefinition as T1')
                ->where('EtlPointTypeId',CONST_ETLPARAMETER_CL)
                ->where('LowerLimit','<=',$ratio)
                ->where('UpperLimit','>',$ratio)
                ->pluck('Points');
        }
        return $points;
    }
    public function calculateBidEvaluationPoints1($contractorTenderId){
	
        $points = DB::table('etltenderbiddercontractor')
                        ->where('Id','=',$contractorTenderId)
                        ->pluck('CmnOwnershipTypeId');

        return $points;
    }
    public function calculateBidEvaluationPoints2($contractorTenderId){
        $points = DB::table('etltenderbiddercontractor')
            ->where('Id','=',$contractorTenderId)
            ->pluck('EmploymentOfVTI');
        return $points;
    }
    public function calculateBidEvaluationPoints3($contractorTenderId){
        $points = DB::table('etltenderbiddercontractor')
            ->where('Id','=',$contractorTenderId)
            ->pluck('CommitmentOfInternship');
        return $points;
    }
    public function calculateBidEvaluationPoints4($contractorTenderId){
        $points = DB::table('etltenderbiddercontractor')
            ->where('Id','=',$contractorTenderId)
            ->pluck('BhutaneseEmployement');
        return $points;
    }

    
    public function getPrintMessage($message){
        return View::make('printpages.message')
            ->with('message',$message);
    }
    public function postBlackListedContractor(){
        $cdbNo = Input::get('cdbNo');
        $contractor = DB::table('viewlistofcontractors')
                        ->where('CDBNo',$cdbNo)
                        ->get(array('NameOfFirm','Status'));
        $contractorName = $contractor[0]->NameOfFirm;
        $status = $contractor[0]->Status;
        $message = "This contractor $contractorName ($cdbNo) is $status";
        return View::make('report.message')
            ->with('cdbNo',$cdbNo)
            ->with('message',$message);
    }
}