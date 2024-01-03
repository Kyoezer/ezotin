<?php
class CrpBiddingFormModel extends BaseModel{
	protected $table="crpbiddingform";
	protected $fillable=array('Id','EtlTenderId','ReferenceNo','Type','CmnProcuringAgencyId','NameOfWork','APSFormPath','APSFormType','DescriptionOfWork','CmnContractorProjectCategoryId','CmnContractorClassificationId','CmnConsultantServiceCategoryId','CmnSpecializedfirmCategoryId','CmnConsultantServiceId','ApprovedAgencyEstimate','NitInMediaDate','BidSaleClosedDate','CmnDzongkhagId','BidOpeningDate','AcceptanceDate','ContractSigningDate','WorkOrderNo','ContractPeriod','WorkStartDate','WorkCompletionDate','ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','Remarks','CmnWorkExecutionStatusId','ByCDB','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnProcuringAgencyId'=>'required',
        'NameOfWork'=>'required',
        'DescriptionOfWork'=>'required',
        'CmnContractorProjectCategoryId'=>'required_if:Type,0',
        'CmnContractorClassificationId'=>'required_if:Type,0',
        'CmnConsultantServiceCategoryId'=>'required_if:Type,1',
        'CmnSpecializedfirmCategoryId'=>'required_if:Type,2',
        'CmnConsultantServiceId'=>'required_if:Type,1',
        'ApprovedAgencyEstimate'=>'required|numeric',
        'CmnDzongkhagId'=>'required',
        'BidSaleClosedDate'=>'required',
        'BidOpeningDate'=>'required|after:BidSaleClosedDate',
        'AcceptanceDate'=>'required',
        'ContractSigningDate'=>'required',
        'WorkOrderNo'=>'required',
        'WorkStartDate'=>'required',
        'WorkCompletionDate'=>'required|after:WorkStartDate',
        'ContractPeriod'=>'required|numeric',
     //   'ByCDB'=>'boolean',
    );
    protected $messages=array(
        'CmnProcuringAgencyId.required'=>'Procuring Agency field is required',
        'NameOfWork.required'=>'Name of the Contract Work field is required',
        'DescriptionOfWork.required'=>'Contract Description field is required',
        'CmnContractorProjectCategoryId.required_if'=>'Category of Work field is required',
        'CmnContractorClassificationId.required_if'=>'Classification of Contractors field is required',
        'CmnConsultantServiceCategoryId.required_if'=>'Service Category field is required',
        'CmnSpecializedfirmCategoryId.required_if'=>'Category field is required',
        'CmnConsultantServiceId.required_if'=>'Service Name field is required',
        'ApprovedAgencyEstimate.required'=>'Approved Agency Estimate field is required',
        'ApprovedAgencyEstimate.numeric'=>'Approved Agency Estimate field must be a number',
        'CmnDzongkhagId.required'=>'Dzongkhag field is required',
        'BidSaleClosedDate.required'=>'Date of Bids Closed for Sale field is required',
        'BidOpeningDate.required'=>'Bid Opening Date field is required',
        'BidOpeningDate.after'=>'Bid Opening Date field must be after the Date of Bids Closed for Sale',
        'AcceptanceDate.required'=>'Date of Letter of Acceptance field is required',
        'ContractSigningDate.required'=>'Date of Signing of Contract field required',
        'WorkOrderNo.required'=>'Work Order No. field is required',
        'WorkStartDate.required'=>'Start Date field is required',
        'ContractPeriod.required'=>'Contract Period (In Months) field is required',
        'ContractPeriod.numeric'=>'Contract Period (In Months) field must be a number',
        'WorkCompletionDate.required'=>'Completion Date field is required',
        'WorkCompletionDate.after'=>'Completion Date field must be after the Start Date',
       // 'ByCDB.boolean'=>'You are not allowed to change the source',
    );
    public function scopeBiddingFormContractorCdbAll($query){
        return $query->join('cmnprocuringagency as T1','crpbiddingform.CmnProcuringAgencyId','=','T1.Id')
                    ->join('cmncontractorworkcategory as T2','crpbiddingform.CmnContractorProjectCategoryId','=','T2.Id')
                    ->join('cmncontractorclassification as T3','crpbiddingform.CmnContractorClassificationId','=','T3.Id')
                    ->join('cmndzongkhag as T4','crpbiddingform.CmnDzongkhagId','=','T4.Id')
                    ->where('crpbiddingform.Type',0)
                    ->orderBy('T1.Name');
    }
     public function scopeBiddingFormConsultantCdbAll($query){
        return $query->join('cmnprocuringagency as T1','crpbiddingform.CmnProcuringAgencyId','=','T1.Id')
                    ->join('cmnconsultantservicecategory as T2','crpbiddingform.CmnConsultantServiceCategoryId','=','T2.Id')
                    ->join('cmnconsultantservice as T3','crpbiddingform.CmnConsultantServiceId','=','T3.Id')
                    ->join('cmndzongkhag as T4','crpbiddingform.CmnDzongkhagId','=','T4.Id')
                    ->where('crpbiddingform.Type',1)
                    ->orderBy('T1.Name');
    }

    public function scopeBiddingFormSpecializedfirmCdbAll($query){
        return $query->join('cmnprocuringagency as T1','crpbiddingform.CmnProcuringAgencyId','=','T1.Id')
                    ->join('cmnspecializedtradecategory as T2','crpbiddingform.CmnSpecializedfirmCategoryId','=','T2.Id')
                    ->join('cmndzongkhag as T4','crpbiddingform.CmnDzongkhagId','=','T4.Id')
                    ->where('crpbiddingform.Type',2)
                    ->orderBy('T1.Name');
    }
    public function scopeWorkCompletionDetails($query,$bidId){
        return $query->where('Id',$bidId);
    }
    public function scopeContractorTrackRecords($query,$reference){
        return $query->join('crpbiddingformdetail as T1','crpbiddingform.Id','=','T1.CrpBiddingFormId')
                     ->join('cmnprocuringagency as T2','crpbiddingform.CmnProcuringAgencyId','=','T2.Id')
                     ->join('cmncontractorworkcategory as T3','crpbiddingform.CmnContractorProjectCategoryId','=','T3.Id')
                     ->join('cmncontractorclassification as T4','crpbiddingform.CmnContractorClassificationId','=','T4.Id')
                     ->join('cmndzongkhag as T5','crpbiddingform.CmnDzongkhagId','=','T5.Id')
                     ->where('crpbiddingform.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                     ->where('T1.CrpContractorFinalId',$reference)
                     ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                     ->orderBy('T2.Name')
                     ->orderBy('crpbiddingform.WorkStartDate');

    }
    public function scopeContractorTrackRecordsAll($query,$reference){
        return $query->join('crpbiddingformdetail as T1','crpbiddingform.Id','=','T1.CrpBiddingFormId')
                     ->join('cmnprocuringagency as T2','crpbiddingform.CmnProcuringAgencyId','=','T2.Id')
                     ->join('cmncontractorworkcategory as T3','crpbiddingform.CmnContractorProjectCategoryId','=','T3.Id')
                     ->join('cmncontractorclassification as T4','crpbiddingform.CmnContractorClassificationId','=','T4.Id')
                     ->join('cmndzongkhag as T5','crpbiddingform.CmnDzongkhagId','=','T5.Id')
                     ->join('cmnlistitem as T6','crpbiddingform.CmnWorkExecutionStatusId','=','T6.Id')
                     ->where('T1.CrpContractorFinalId',$reference)
                     ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                     ->orderBy('T2.Name')
                     ->orderBy('crpbiddingform.WorkStartDate');

    }
    public function scopeConsultantTrackRecords($query,$reference){
        return $query->join('crpbiddingformdetail as T1','crpbiddingform.Id','=','T1.CrpBiddingFormId')
                     ->join('cmnprocuringagency as T2','crpbiddingform.CmnProcuringAgencyId','=','T2.Id')
                     ->join('cmnconsultantservicecategory as T3','crpbiddingform.CmnConsultantServiceCategoryId','=','T3.Id')
                     ->join('cmnconsultantservice as T4','crpbiddingform.CmnConsultantServiceId','=','T4.Id')
                     ->join('cmndzongkhag as T5','crpbiddingform.CmnDzongkhagId','=','T5.Id')
                     ->leftJoin('cmnlistitem as T6','T6.Id','=','crpbiddingform.CmnWorkExecutionStatusId')
                     ->where('crpbiddingform.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                     ->where('T1.CrpConsultantFinalId',$reference)
                     ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                     ->orderBy('T2.Name')
                     ->orderBy('crpbiddingform.WorkStartDate');

    }
    public function scopeConsultantTrackRecordsAll($query,$reference){
        return $query->join('crpbiddingformdetail as T1','crpbiddingform.Id','=','T1.CrpBiddingFormId')
                     ->join('cmnprocuringagency as T2','crpbiddingform.CmnProcuringAgencyId','=','T2.Id')
                     ->join('cmnconsultantservicecategory as T3','crpbiddingform.CmnConsultantServiceCategoryId','=','T3.Id')
                     ->join('cmnconsultantservice as T4','crpbiddingform.CmnConsultantServiceId','=','T4.Id')
                     ->join('cmndzongkhag as T5','crpbiddingform.CmnDzongkhagId','=','T5.Id')
                     ->join('cmnlistitem as T6','crpbiddingform.CmnWorkExecutionStatusId','=','T6.Id')
                     ->where('T1.CrpConsultantFinalId',$reference)
                     ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                     ->orderBy('T2.Name')
                     ->orderBy('crpbiddingform.WorkStartDate');

    }

    public function scopeSpecializedtradeTrackrecords($query,$reference){
        return $query->join('crpbiddingformdetail as T1','crpbiddingform.Id','=','T1.CrpBiddingFormId')
                     ->join('cmnprocuringagency as T2','crpbiddingform.CmnProcuringAgencyId','=','T2.Id')
                     ->join('cmnspecializedtradecategory as T3','crpbiddingform.CmnSpecializedfirmCategoryId','=','T3.Id')
          
                     ->join('cmndzongkhag as T5','crpbiddingform.CmnDzongkhagId','=','T5.Id')
                     ->leftJoin('cmnlistitem as T6','T6.Id','=','crpbiddingform.CmnWorkExecutionStatusId')
                     ->where('crpbiddingform.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                     ->where('T1.CrpSpecializedtradeFinalId',$reference)
                     ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                     ->orderBy('T2.Name')
                     ->orderBy('crpbiddingform.WorkStartDate');

    }
    public function scopespecializedtradeTrackrecordsAll($query,$reference){
        return $query->join('crpbiddingformdetail as T1','crpbiddingform.Id','=','T1.CrpBiddingFormId')
                     ->join('cmnprocuringagency as T2','crpbiddingform.CmnProcuringAgencyId','=','T2.Id')
                     ->join('cmnspecializedtradecategory as T3','crpbiddingform.CmnSpecializedfirmCategoryId','=','T3.Id')
      
                     ->join('cmndzongkhag as T5','crpbiddingform.CmnDzongkhagId','=','T5.Id')
                     ->join('cmnlistitem as T6','crpbiddingform.CmnWorkExecutionStatusId','=','T6.Id')
                     ->where('T1.CrpSpecializedtradeFinalId',$reference)
                     ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                     ->orderBy('T2.Name')
                     ->orderBy('crpbiddingform.WorkStartDate');

    }
    /*Scope by Sangay Wangdi Moktan*/
    public function scopeCountContractorWorkCompleted($query,$contractorId,$startDate,$endDate,$contractorClassificationId,$contractorCategoryId){
        return $query
            ->join('crpbiddingformdetail as T2','T2.CrpBiddingFormId','=','crpbiddingform.Id')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->whereBetween('crpbiddingform.CompletionDateFinal',array($startDate,$endDate))
            ->whereNotNull('crpbiddingform.ContractPriceFinal')
            // ->where('crpbiddingform.CmnContractorClassificationId','=',$contractorClassificationId)
            ->where('crpbiddingform.CmnContractorProjectCategoryId','=',$contractorCategoryId)
            ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->where('crpbiddingform.CmnWorkExecutionStatusId','=',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
    }
    public function scopeCountContractorAllWorkCompleted($query,$contractorId,$startDate,$endDate){
        return $query
            ->join('crpbiddingformdetail as T2','T2.CrpBiddingFormId','=','crpbiddingform.Id')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->whereBetween('crpbiddingform.CompletionDateFinal',array($startDate,$endDate))
            ->whereNotNull('crpbiddingform.ContractPriceFinal')
            ->where('T2.CmnWorkExecutionStatusId', '=', CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->where('crpbiddingform.CmnWorkExecutionStatusId','=',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
    }
    public function scopeCountConsultantWorkCompleted($query,$consultantId,$startDate,$endDate,$contractorClassificationId,$contractorCategoryId){
//        return $query
//            ->join('crpbiddingformdetail as T2','T2.CrpBiddingFormId','=','crpbiddingform.Id')
//            ->where('T2.CrpConsultantFinalId','=',$consultantId)
//            ->whereBetween('crpbiddingform.WorkCompletionDate',array($startDate,$endDate))
//            ->where('crpbiddingform.CmnContractorClassificationId','=',$contractorClassificationId)
//            ->where('crpbiddingform.CmnContractorProjectCategoryId','=',$contractorCategoryId)
//            ->where('crpbiddingform.CmnWorkExecutionStatusId','=',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
    }

    
    /*End of Scope by SWM*/
}