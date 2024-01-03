<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 3/1/2017
 * Time: 12:20 PM
 */
class ReportBidCapacityCalculator extends ReportController
{
    public function getIndex(){
        return View::make('report.bidcapacitycalculator');
    }
    public function postCalculate(){
        $contractorId = Input::get('ContractorId');
        $startDate = $this->convertDate(Input::get('FromDate'));
        $endDate = $this->convertDate(Input::get('ToDate'));
        $stake = 100;
        $dateDiff = date_diff(date_create($startDate),date_create($endDate));
        $contractPeriod = $dateDiff->format("%a");
        $bidCapacityArray = $this->calculateBidCapacity($contractorId,$startDate,$endDate,'x','x',$stake,$contractPeriod);

        $bidCapacity =  ((2*$bidCapacityArray['A']*$bidCapacityArray['N']) - $bidCapacityArray['B'])*($stake/100);
        $bidCapacityResult = $this->calculateBidCapacityResult($bidCapacity,Input::get('QuotedAmount'));
        return View::make('report.bidcapacitycalculator')
                    ->with('bidCapacityArray',$bidCapacityArray)
                    ->with('result',$bidCapacityResult);
    }
    public function calculateBidCapacity($contractorId,$startDate,$endDate,$contractorClassificationId, $contractorCategoryId,$stake,$contractPeriod){
        $A = $this->calculateA($contractorId,$startDate,$endDate,$contractorClassificationId, $contractorCategoryId);
        if(gettype($A) == 'array'){
            return $A;
        }
        $N = $this->calculateN($contractPeriod);
        $B = $this->calculateB($contractorId,$contractorClassificationId,$contractorCategoryId,$startDate,$endDate);
        $bidCapacityArray['A'] = $A;
        $bidCapacityArray['B'] = $B;
        $bidCapacityArray['N'] = $N;
        $bidCapacity =  ((2*$A*$N) - $B)*($stake/100);

        return $bidCapacityArray;
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
                ->where('T2.CrpContractorFinalId','=',$contractorId)
                ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->whereIn(DB::raw('coalesce(A.CmnWorkExecutionStatusId,0)'),array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
                ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end'),'<',$yearEnd[$i]) //Start Date < 2012-12-31
                ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end'), '>',$yearStart[$i]) //Completion Date > 2012-01-01
                ->get(array('A.Id','A.CmnWorkExecutionStatusId','A.WorkStartDate','A.WorkCompletionDate','A.CommencementDateFinal','A.CompletionDateFinal',DB::raw("'$yearStart[$i]' as YearStart"),DB::raw("'$yearEnd[$i]' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end as CompletionDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T2.EvaluatedAmount else A.ContractPriceFinal end as ContractPriceFinal'),DB::raw("period_diff(date_format(coalesce(A.CompletionDateFinal,A.WorkCompletionDate),'%Y%m'),date_format(coalesce(A.CommencementDateFinal,A.WorkStartDate),'%Y%m')) as Duration")));

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
                if((((int)(date_diff(date_create($y->CompletionDateFinal),date_create($y->CommencementDateFinal))->format('%a')))) == 0){
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
    public function calculateN($contractPeriod){
        //$diffInDays = (int)(date_diff(date_create($diffInMonths[0]->TentativeStartDate),date_create($diffInMonths[0]->TentativeEndDate))->format('%a'));
        $years =  $contractPeriod /12;
        $N = (($years + (ceil(($years/0.5)) - ($years)))*0.5);
        return $N;

    }
    public function calculateB($contractorId,$contractorClassificationId, $contractorCategoryId,$projectStartDate,$projectEndDate){
        $B = 0;

        $contractorTenderProjects = DB::table('etltender as A')
            ->join('etltenderbiddercontractor as T1','T1.EtlTenderId','=','A.Id')
            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->whereNotNull('T1.ActualStartDate')
            ->where(DB::raw("coalesce(A.DeleteStatus,'N')"),'<>','Y')
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualStartDate else A.CommencementDateFinal end'),'<',$projectEndDate)
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualEndDate else A.CompletionDateFinal end'), '>',$projectStartDate)
//            ->where('A.TenderSource',1)
            ->whereIn(DB::raw('coalesce(A.CmnWorkExecutionStatusId,0)'),array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
            ->select('A.Id',DB::raw("'$projectStartDate' as YearStart"),DB::raw("'$projectEndDate' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualStartDate else A.CommencementDateFinal end as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.ActualEndDate else A.CompletionDateFinal end as CompletionDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T1.AwardedAmount else A.ContractPriceFinal end as ContractPriceFinal'))
            ->get();
        $contractorBidProjects = DB::table('crpbiddingform as A')
            ->join('crpbiddingformdetail as T2','T2.CrpBiddingFormId','=','A.Id')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->whereIn('A.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED))
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end'),'<',$projectEndDate)
            ->where(DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end'), '>',$projectStartDate)
            ->get(array('A.Id',DB::raw("'$projectStartDate' as YearStart"),DB::raw("'$projectEndDate' as YearEnd"),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkStartDate else A.CommencementDateFinal end  as CommencementDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then A.WorkCompletionDate else A.CompletionDateFinal end  as CompletionDateFinal'),DB::raw('case A.CmnWorkExecutionStatusId when "'.CONST_CMN_WORKEXECUTIONSTATUS_AWARDED.'" then T2.EvaluatedAmount else A.ContractPriceFinal end as ContractPriceFinal')));
        foreach ($contractorTenderProjects as $tenderProject):
            $yearStart = date_create($tenderProject->YearStart); //TENTATIVE START DATE
            $yearEnd = date_create($tenderProject->YearEnd); //TENTATIVE END DATE
            $commencementDateFinal = date_create($tenderProject->CommencementDateFinal); //CURRENT PROJECT START DATE
            $completionDateFinal = date_create($tenderProject->CompletionDateFinal); //CURRENT PROJECT END DATE
            $projectValuePerDay = $tenderProject->ContractPriceFinal/((int)(date_diff(date_create($tenderProject->CompletionDateFinal),date_create($tenderProject->CommencementDateFinal))->format('%a')));

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
            $projectValuePerDay = $bidProject->ContractPriceFinal/((int)(date_diff(date_create($bidProject->CompletionDateFinal),date_create($bidProject->CommencementDateFinal))->format('%a')));
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
}