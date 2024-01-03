<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 8/24/2015
 * Time: 4:05 PM
 */

class NewetoolEtoolSysEditWork extends EtoolController{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name','Code'));
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));

        $workId = Input::get('WorkId');
        $contractorCategoryId = Input::get('ContractorCategoryId');
        $contractorClassificationId = Input::get('ContractorClassificationId');
        $cmnProcuringAgencyId = Input::get('CmnProcuringAgencyId');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $tenders = array();
        $parameters = array();
        $noOfPages = 0;
        $start = 0;
        $query = "select distinct T1.Id, T1.UploadedDate, T1.TenderSource, T1.ReferenceNo, T1.ProjectEstimateCost, T1.CmnWorkExecutionStatusId, T1.DateOfClosingSaleOfTender, T1.LastDateAndTimeOfSubmission, T1.TenderOpeningDateAndTime, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, A.Name as Agency, T1.ContractPeriod, case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as EtlTenderWorkId from etltender T1 join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join cmnprocuringagency A on A.Id = T1.CmnProcuringAgencyId where 1";
        if((bool)$workId || (bool)$contractorCategoryId || (bool)$fromDate || (bool)$toDate || (bool)$contractorClassificationId || (bool)$cmnProcuringAgencyId) {
            if ((bool)$workId) {
                $query .= " and case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = ?";
                array_push($parameters, $workId);
            }
            if ((bool)$contractorCategoryId) {
                $query .= " and T1.CmnContractorCategoryId = ?";
                array_push($parameters, $contractorCategoryId);
            }
            if ((bool)$contractorClassificationId) {
                $query .= " and T1.CmnContractorClassificationId = ?";
                array_push($parameters, $contractorClassificationId);
            }
            if ((bool)$cmnProcuringAgencyId) {
                $query .= " and T1.CmnProcuringAgencyId = ?";
                array_push($parameters, $cmnProcuringAgencyId);
            }
            if((bool)$fromDate){
                $fromDate = $this->convertDate($fromDate);
                $query .= " and CAST(T1.UploadedDate as Date) >= ?";
                array_push($parameters,$fromDate);
            }
            if((bool)$toDate){
                $toDate = $this->convertDate($toDate);
                $query .=" and CAST(T1.UploadedDate as Date) <= ?";
                array_push($parameters,$toDate);
            }
            array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED);
            array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);

            $query.=" and coalesce(T1.CmnWorkExecutionStatusId,0) NOT IN (?,?) order by year(T1.UploadedDate) desc, T1.WorkId DESC";
            /*PAGINATION*/
            $pageNo = Input::has('page')?Input::get('page'):1;
            $pagination = $this->pagination($query,$parameters,10,$pageNo);
            $limitOffsetAppend = $pagination['LimitAppend'];
            $noOfPages = $pagination['NoOfPages'];
            $start = $pagination['Start'];
            /*END PAGINATION*/

            $tenders = DB::select("$query$limitOffsetAppend",$parameters);
        }

        return View::make('new_etool.editworklist')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('uploadedTenders',$tenders)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('contractorCategories',$contractorCategories)
            ->with('contractorClassifications',$contractorClassifications);
    }
}