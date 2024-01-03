<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 8/24/2015
 * Time: 11:04 AM
 */

class NewetoolEtoolSysResetResult extends EtoolController{
    public function getIndex(){
        $auditTrailActionMessage="Opened Reset Result Page (Administrator)";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        return View::make('new_etool.selectreset');
    }
    public function postFetchWork(){
        $workId = Input::get('WorkId');
        $auditTrailActionMessage="Opened result reset page for Work Id $workId (Administrator)";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        if($workId){
            $tender = DB::table('etltender as T1')
                ->join('cmncontractorclassification as X','X.Id','=','T1.CmnContractorClassificationId')
                ->join('cmncontractorworkcategory as Y','Y.Id','=','T1.CmnContractorCategoryId')
                ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                ->join('cmndzongkhag as T3','T3.Id','=','T1.CmnDzongkhagId')
                ->whereRaw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = '$workId'")
                ->where('TenderSource',1)
                ->get(array('T1.Id',DB::raw('coalesce(T1.CmnWorkExecutionStatusId,"XXX") as CmnWorkExecutionStatusId'),'T1.DescriptionOfWork','T3.NameEn as Dzongkhag','T1.NameOfWork','X.Code as Class','Y.Code as Category','T1.TentativeStartDate','T1.TentativeEndDate','T1.ProjectEstimateCost','T1.ContractPeriod'));
            if(!$tender){
                return Redirect::to('etoolsysadm/resetetoolresult')->with('customerrormessage','No work exists with that Work Id');
            }
            if($tender[0]->CmnWorkExecutionStatusId == 'XXX'){
                return Redirect::to('etoolsysadm/resetetoolresult')->with('customerrormessage','Work is not yet awarded');
            }
            $awardedTo = DB::table('crpcontractorfinal as T1')
                            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.CrpContractorFinalId')
                            ->join('etltenderbiddercontractor as T3','T3.Id','=','T2.EtlTenderBidderContractorId')
                            ->join('etltender as T4','T4.Id','=','T3.EtlTenderId')
                            ->join('cmnprocuringagency as T5','T5.Id','=','T4.CmnProcuringAgencyId')
                            ->whereNotNull('T3.ActualStartDate')
                            ->whereRaw("case when T4.migratedworkid is null then concat(T5.Code,'/',year(T4.UploadedDate),'/',T4.WorkId) else T4.migratedworkid end = '$workId'")
                            ->get(array(DB::raw('group_concat(concat(T1.NameOfFirm," (CDB No.",T1.CDBNo,")") SEPARATOR ", ") as Contractor')));
            return View::make('new_etool.resetresult')
                ->with('workId',$workId)
                ->with('awardedTo',$awardedTo)
                ->with('tender',$tender);
        }else{
            return Redirect::to('etoolsysadm/resetetoolresult');
        }
    }
    public function getResetResult($id){
        $workId = DB::table('etltender as T1')->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')->where('T1.Id',$id)->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        DB::table('etltender')->where('Id',$id)->update(array('CmnWorkExecutionStatusId'=>NULL));
        DB::table('etltenderbiddercontractor')->where('EtlTenderId',$id)->update(array('ActualStartDate'=>NULL,'ActualEndDate'=>NULL,'AwardedAmount'=>NULL,'Remarks'=>NULL));
        $auditTrailActionMessage="Reset result for Work Id ".$workId[0]->WorkId." (Administrator)";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workId[0]->WorkId);
        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workId[0]->WorkId,'Operation'=>'Reset Result','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));
        return Redirect::to('etoolsysadm/resetetoolresult')->with('savedsuccessmessage','Result Reset Successfully');
    }
    public function getResetToAwarded($id){
        $workId = DB::table('etltender as T1')->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')->where('T1.Id',$id)->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        DB::table('etltender')->where('Id',$id)->update(array('CmnWorkExecutionStatusId'=>CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,'ContractPriceFinal'=>NULL,'CommencementDateOfficial'=>NULL,'ContractPriceInitial'=>NULL,'Remarks'=>NULL,'OntimeCompletionScore'=>NULL,'QualityOfExecutionScore'=>NULL,'CommencementDateFinal'=>NULL,'CompletionDateOfficial'=>NULL,'CompletionDateFinal'=>NULL,'LDImposed'=>0,'NoOfDays'=>NULL,'Hindrance'=>0,'HindranceAmount'=>NULL,'Remarks'=>NULL));
        $auditTrailActionMessage="Reset completed work to Awarded for Work Id ".$workId[0]->WorkId." (Administrator)";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workId[0]->WorkId);
        return Redirect::to('etoolsysadm/resetetoolresult')->with('savedsuccessmessage','Reset to Awarded Successfully');
    }
}