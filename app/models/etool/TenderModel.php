<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/16/2015
 * Time: 4:22 PM
 */

class TenderModel extends BaseModel{
    protected $table = 'etltender';
    protected $fillable = array('Id','ReferenceNo','NameOfWork','APSFormPath','APSFormType','WorkId','CmnProcuringAgencyId','DescriptionOfWork','CmnDzongkhagId','CmnContractorCategoryId','CmnContractorClassificationId','ContractPeriod','DateOfSaleOfTender','DateOfClosingSaleOfTender','LastDateAndTimeOfSubmission','TenderOpeningDateAndTime','CostOfTender','EMD','ProjectEstimateCost','TentativeStartDate','TentativeEndDate','ContactPerson','ContactNo','ContactEmail','PublishInWebsite','ShowCostInWebsite','TenderSource','CmnWorkExecutionStatusId','ContractPriceInitial','ContractPriceFinal','CommencementDateOfficial','CommencementDateFinal','CompletionDateOfficial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','Remarks','LDImposed','NoOfDays','Hindrance','HindranceAmount','DeleteStatus','CreatedBy','EditedBy','CreatedOn','UploadedDate','EditedOn','EGPTenderId','Method','IsSPRRTender');
    protected $rules = array(
        'ReferenceNo' => 'required',
        'NameOfWork' => 'required',
        'DescriptionOfWork' => 'required',
        'CmnDzongkhagId' => 'required',
        'CmnContractorCategoryId' => 'required',
        'CmnContractorClassificationId' => 'required',
        'ContractPeriod' => 'required',
        'DateOfSaleOfTender' => 'required',
        'DateOfClosingSaleOfTender' => 'required|after:DateOfSaleOfTender',
        'LastDateAndTimeOfSubmission' => 'required|after:DateOfClosingSaleOfTender',
        'TenderOpeningDateAndTime' => 'required|after:LastDateAndTimeOfSubmission',
        'CompletionDateOfficial' => 'after:CommencementDateOfficial',
        'CompletionDateFinal' => 'after:CommencementDateFinal',
        'CostOfTender' => 'required',
        'EMD' => 'required',
        'ProjectEstimateCost' => 'required|numeric',
        'TentativeStartDate' => 'required',
        'TentativeEndDate' => 'required|after:TentativeStartDate',
        'ContactEmail' => 'email',
        'ShowCostInWebsite' => 'required',
        'PublishInWebsite' => 'required' 
    );
    protected $messages = array(
        'ReferenceNo.required' => 'Reference Number field is required',
        'NameOfWork.required' => 'Name of Work field is required',
        'DescriptionOfWork.required' => 'Description of Work field is required',
        'CmnDzongkhagId.required' => 'Dzongkhag field is required',
        'CmnContractorCategoryId.required' => 'Category field is required',
        'CmnContractorClassificationId.required' => 'Classification field is required',
        'ContractPeriod.required' => 'Contract Period field is required',
        'DateOfSaleOfTender.required' => 'Date of Sale of Tender field is required',
        'DateOfClosingSaleOfTender.required' => 'Closing Date of Sale of Tender field is required',
        'DateOfClosingSaleOfTender.after' => 'Closing Date of Sale of Tender must be after Date of Sale of Tender',
        'LastDateAndTimeOfSubmission.required' => 'Last Date and Time of Submission field is required',
        'LastDateAndTimeOfSubmission.after' => 'Last Date and Time of Submission must be after Closing Date of Sale of Tender',
        'TenderOpeningDateAndTime.required' => 'Opening Date and Time field is required',
        'TenderOpeningDateAndTime.after' => 'Opening Date and Time field must be after Last Date and Time of Submission',
        'CostOfTender.required' => 'Cost of Tender field is required',
        'EMD.required' => 'Earnest Money Deposit field is required',
        'ShowCostInWebsite.required' => 'Show Cost in Website field is required',
        'PublishInWebsite.required' => 'Publish in Website field is required',
        'ProjectEstimateCost.required' => 'Project Estimate Cost field is required',
        'ProjectEstimateCost.numeric' => 'Project Estimate Cost field must be a number',
        'TentativeStartDate.required' => 'Tentative Start Date field is required',
        'TentativeEndDate.required' => 'Tentative End Date field is required',
        'TentativeEndDate.after' => 'Tentative End Date must be after Tentative Start Date',
        'ContactEmail.email' => 'Invalid email format for Contact Email'
    );
    public function scopeCountContractorTenderCompleted($query,$contractorId,$startDate,$endDate,$contractorClassificationId,$contractorCategoryId){
        return $query
            ->join('etltenderbiddercontractor as T1','T1.EtlTenderId','=','etltender.Id')
            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->whereNotNull('T1.ActualStartDate')
            ->where(DB::raw("coalesce(etltender.DeleteStatus,'N')"),'<>','Y')
            // ->where('etltender.CmnContractorClassificationId','=',$contractorClassificationId)
            ->where('etltender.CmnContractorCategoryId','=',$contractorCategoryId)
//            ->where('etltender.TenderSource',1)
            ->whereNotNull('etltender.ContractPriceFinal')
            ->whereBetween('etltender.CompletionDateFinal',array($startDate,$endDate))
            ->where(DB::raw('coalesce(etltender.CmnWorkExecutionStatusId,0)'),'=',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
    }
    public function scopeCountContractorAllTenderCompleted($query,$contractorId,$startDate,$endDate){
        return $query
            ->join('etltenderbiddercontractor as T1','T1.EtlTenderId','=','etltender.Id')
            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
            ->where(DB::raw("coalesce(etltender.DeleteStatus,'N')"),'<>','Y')
            ->where('T2.CrpContractorFinalId','=',$contractorId)
            ->whereNotNull('T1.ActualStartDate')
//            ->where('etltender.TenderSource',1)
            ->whereNotNull('etltender.ContractPriceFinal')
            ->whereBetween('etltender.CompletionDateFinal',array($startDate,$endDate))
            ->where(DB::raw('coalesce(etltender.CmnWorkExecutionStatusId,0)'),'=',CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
    }
}