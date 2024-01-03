<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 3/15/2016
 * Time: 9:12 PM
 */
class NewetoolEtoolProcuringAgencyReport extends EtoolController
{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Code'));
        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code'));
        return View::make('new_etool.procuringagencyreport')
            ->with('procuringAgencies',$procuringAgencies)
            ->with('classifications',$classifications)
            ->with('categories',$categories);
    }

    public function postView(){
        $parameters = array();
        $reportType = Input::get('ReportType');
        $fromDate = Input::get("FromDate");
        $toDate = Input::get("ToDate");
        $procuringAgency = Input::get('ProcuringAgency');
        $class = Input::get('Class');
        $category = Input::get('Category');

        if($reportType == 1){
            $title = "Tenders Uploaded";
            $query = "select T1.NameOfWork,T2.Name as Agency, case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end as WorkId,T1.LastDateAndTimeOfSubmission,T1.TenderOpeningDateAndTime, T1.TentativeStartDate, T1.TentativeEndDate,T1.ContractPeriod,T3.Code as Class, T4.Code as Category, T1.ProjectEstimateCost from etltender T1 join cmnprocuringagency T2 on T1.CmnProcuringAgencyId = T2.Id left join cmncontractorclassification as T3 on T3.Id = T1.CmnContractorClassificationId left join cmncontractorworkcategory T4 on T4.Id = T1.CmnContractorCategoryId where 1";
        }
        if($reportType == 2){
            $title = "Work Id";
            $query = "select T1.NameOfWork,T2.Name as Agency, case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end as WorkId,T1.LastDateAndTimeOfSubmission,T1.TenderOpeningDateAndTime, T1.TentativeStartDate, T1.TentativeEndDate,T1.ContractPeriod,T3.Code as Class, T4.Code as Category, T1.ProjectEstimateCost from etltender T1 join cmnprocuringagency T2 on T1.CmnProcuringAgencyId = T2.Id join cmncontractorclassification as T3 on T3.Id = T1.CmnContractorClassificationId join cmncontractorworkcategory T4 on T4.Id = T1.CmnContractorCategoryId where 1";
        }
        if($reportType == 3){
            $title = "Evaluation";
            $query = "select distinct T1.Id,T1.NameOfWork,T2.Name as Agency, case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end as WorkId,T1.LastDateAndTimeOfSubmission,T1.TenderOpeningDateAndTime, T1.TentativeStartDate, T1.TentativeEndDate,T1.ContractPeriod,T3.Code as Class, T4.Code as Category, T1.ProjectEstimateCost from etltender T1 join etlevaluationscore A on A.EtlTenderId = T1.Id join cmnprocuringagency T2 on T1.CmnProcuringAgencyId = T2.Id join cmncontractorclassification as T3 on T3.Id = T1.CmnContractorClassificationId join cmncontractorworkcategory T4 on T4.Id = T1.CmnContractorCategoryId where 1";
        }
        if((bool)$procuringAgency){
            $query .= " and T2.Id = ?";
            array_push($parameters,$procuringAgency);
        }
        if((bool)$class){
            $query .= " and T3.Id = ?";
            array_push($parameters,$class);
        }
        if((bool)$category){
            $query .= " and T4.Id = ?";
            array_push($parameters,$category);
        }
        if((bool)$fromDate){
            $query .= " and T1.TentativeStartDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
        }
        if((bool)$toDate){
            $query.=" and T1.TentativeStartDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
        }
        if(($reportType == 1)||($reportType == 3)){
            $reportData = DB::select("$query order by year(DateOfSaleOfTender) DESC,case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end DESC",$parameters);
        }
        if($reportType == 2){
            $reportData = DB::select("$query and (T1.migratedworkid is not null or T1.WorkId is not null) group by T1.Id order by year(DateOfSaleOfTender) DESC,case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end DESC",$parameters);
        }
        return View::make('new_etool.viewprocuringagencyreport')
            ->with('title',$title)
            ->with('reportType',$reportType)
            ->with('reportData',$reportData);
    }
}