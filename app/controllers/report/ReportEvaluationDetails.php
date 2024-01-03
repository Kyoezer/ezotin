<?php

class ReportEvaluationDetails extends ReportController{
    public function getIndex(){
        $contractorStatuses = array();
        $parameters = array();
        $loggedInUser=Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $condition = " 1=1";
        if(!in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
            $userAgency = Auth::user()->CmnProcuringAgencyId;
            $userProcuringAgencyReference = DB::table('cmnprocuringagency')->where('Id',$userAgency)->pluck('ReferenceNo');
            if((int)$userProcuringAgencyReference != 901){
                $condition = " T1.CmnProcuringAgencyId = '$userAgency'";
            }
        }
        $workId = Input::get('WorkId');
        $tenderDetails = array();
        if(Input::has('WorkId')) {
            DB::table('tblworkidtrack')->insert(array('workid'=>$workId,'username'=>Auth::user()->username,'operation'=>'Report 8','op_time'=>date('Y-m-d G:i:s')));
            $tenderDetails = DB::table('etltender as T1')
                ->join('cmncontractorclassification as T2', 'T1.CmnContractorClassificationId', '=', 'T2.Id')
                ->join('cmncontractorworkcategory as T3', 'T1.CmnContractorCategoryId', '=', 'T3.Id')
                ->leftjoin('cmnlistitem as A', 'A.Id', '=', 'T1.CmnWorkExecutionStatusId')
                ->join('cmnprocuringagency as T5', 'T5.Id', '=', 'T1.CmnProcuringAgencyId')
                ->join('cmndzongkhag as T7', 'T1.CmnDzongkhagId', '=', 'T7.Id')
                ->whereRaw("case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = '$workId'")
                ->whereIn('T2.ReferenceNo', array(1, 2))
                ->whereRaw($condition)
                ->limit(1)
                ->get(array(DB::raw('distinct(T1.Id) as TenderId'), DB::raw("case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"), "T1.NameOfWork", "T2.Code as Classification", "T3.Code as Category", "T7.NameEn as Dzongkhag", "T1.ProjectEstimateCost", "T1.ContractPeriod",'T5.Name as ProcuringAgency','A.Name as Status','T1.CommencementDateFinal','T1.CompletionDateFinal'));
        }
        if(!$tenderDetails){
            return Redirect::to('etl/reports')->with('customerrormessage','<b>CAUTION</b> <br/>
You do not have access to this Work Id.
You can only view the Work Ids you have created .
Viewing the Work Id created by other agencies is not allowed and you could be charged for breach of confidentiality.
Note that all the information viewed from your account is being traced.');
        }else{
            $tenderId = $tenderDetails[0]->TenderId;
            $qualifyingScore = DB::table('etlqualifyingscore')->pluck('QualifyingScore');
            $lowestBid = DB::table('etltenderbiddercontractor as T1')
                ->join('etlevaluationscore as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
                ->where('T1.EtlTenderId','=',$tenderId)
                ->min('T1.FinancialBidQuoted');
            $hrCriteria = DB::table('etlcriteriahumanresource as T1')
                            ->join('cmnlistitem as T2','T2.Id','=','T1.CmnDesignationId')
                            ->join('etltier as T3','T3.Id','=','T1.EtlTierId')
                            ->where('T1.EtlTenderId',$tenderId)
                            ->orderBy('T3.MaxPoints','DESC')
                            ->orderBy('T2.Name')
                            ->orderBy('T1.Points','DESC')
                            ->get(array('T3.Name as Tier','T2.Name as Designation','T1.Qualification','T1.Points'));
            $eqCriteria = DB::table('etlcriteriaequipment as T1')
                            ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                            ->join('etltier as T3','T3.Id','=','T1.EtlTierId')
                            ->where('T1.EtlTenderId',$tenderId)
                            ->orderBy('T3.MaxPoints','DESC')
                            ->orderBy('T2.Name')
                            ->orderBy('T1.Points','DESC')
                            ->get(array('T3.Name as Tier','T2.Name as Equipment','T1.Quantity','T1.Points'));
            $bidContractors = DB::table('etltenderbiddercontractor as T1')
                                ->join('etltenderbiddercontractordetail as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
                                ->join('crpcontractorfinal as T3','T3.Id','=','T2.CrpContractorFinalId')
                                ->join('etlevaluationscore as T4','T4.EtlTenderBidderContractorId','=','T1.Id')
                                ->where('T1.EtlTenderId',$tenderId)
                                ->orderBy(DB::raw('coalesce(T4.Score10,0)'),'DESC')
                                ->get(array(DB::raw('distinct T1.Id'),'T1.JointVenture'));
            $cdbNos = array();
            $contractorEquipments = array();
            $contractorHRs = array();
            $contractorScores = array();
            $contractorAmounts = array();
            foreach($bidContractors as $bidContractor):
                $cdbNos[$bidContractor->Id] = DB::table('crpcontractorfinal as T1')
                                ->join('etltenderbiddercontractordetail as T2','T2.CrpContractorFinalId','=','T1.Id')
                                ->where('T2.EtlTenderBidderContractorId',$bidContractor->Id)
                                ->get(array(DB::raw('group_concat(T1.CDBNo SEPARATOR ", ") as CDBNo')));
                $contractorEquipments[$bidContractor->Id] = DB::table('etlcontractorequipment as T1')
                                                            ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                                                            ->join('etltier as T3','T3.Id','=','T1.EtlTierId')
                                                            ->where('T1.EtlTenderBidderContractorId',$bidContractor->Id)
                                                            ->orderBy('T3.MaxPoints')
                                                            ->orderBy('T2.Name')
                                                            ->orderBy('T1.Points','DESC')
                                                            ->get(array('T1.RegistrationNo','T3.Name as Tier', 'T2.Name as Equipment','T1.Points','T1.OwnedOrHired'));

                $contractorHRs[$bidContractor->Id] = DB::table('etlcontractorhumanresource as T1')
                                                        ->join('cmnlistitem as T2','T2.Id','=','T1.CmnDesignationId')
                                                        ->join('etltier as T3','T3.Id','=','T1.EtlTierId')
                                                        ->where('T1.EtlTenderBidderContractorId',$bidContractor->Id)
                                                        ->orderBy('T3.MaxPoints')
                                                        ->orderBy('T2.Name')
                                                        ->orderBy('T1.Points','DESC')
                                                        ->get(array('T2.Name as Designation','T1.Name','T1.CIDNo','T1.Qualification','T3.Name as Tier','T1.Points'));


                $contractorScores[$bidContractor->Id] = DB::table('etlevaluationscore')
                                                            ->where('EtlTenderBidderContractorId',$bidContractor->Id)
                                                            ->orderBy('Score10')
                                                            ->get(array('Score1','Score2','Score3','Score4','Score5','Score6','Score7','Score8','Score9',DB::raw('coalesce(Score10,0) as Score10')));
                $contractorAmounts[$bidContractor->Id] = DB::table('etltenderbiddercontractor as T1')
                                                            ->join('etltender as T2','T2.Id','=','T1.EtlTenderId')
                                                            ->where('T1.Id',$bidContractor->Id)
                                                            ->get(array('T1.FinancialBidQuoted',DB::raw('coalesce(T2.ContractPriceFinal,0) as ContractPriceFinal'),DB::raw('coalesce(T2.ContractPriceFinal,FinancialBidQuoted) as Amount')));
                $contractorStatuses[$bidContractor->Id] = DB::table('etltenderbiddercontractor as T1')
                                                            ->join('etltender as T2','T2.Id','=','T1.EtlTenderId')
                                                            ->join('cmnlistitem as T4','T4.Id','=','T2.CmnWorkExecutionStatusId')
                                                            ->where('T1.Id',$bidContractor->Id)
                                                            ->get(array('T1.AwardedAmount'));
            endforeach;
        }

        return View::make('report.evaluationdetails')
            ->with('qualifyingScore',$qualifyingScore)
            ->with('lowestBid',$lowestBid)
            ->with('tenderDetails',$tenderDetails)
            ->with('hrCriteria',$hrCriteria)
            ->with('eqCriteria',$eqCriteria)
            ->with('bidContractors',$bidContractors)
            ->with('cdbNos',$cdbNos)
            ->with('contractorEquipments',$contractorEquipments)
            ->with('contractorHRs',$contractorHRs)
            ->with('contractorScores',$contractorScores)
            ->with('contractorAmounts',$contractorAmounts)
            ->with('contractorStatuses',$contractorStatuses);
    }
}