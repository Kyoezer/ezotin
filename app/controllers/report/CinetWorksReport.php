<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/24/2016
 * Time: 2:09 PM
 */
class CinetWorksReport extends ReportController
{
    public function getTenderUploaded(){
        $parameters = array();
        $fromDate = Input::get("FromDate");
        $toDate = Input::get("ToDate");
        $procuringAgency = Input::get('ProcuringAgency');
        $class = Input::get('Class');
        $category = Input::get('Category');

        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Code'));
        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code'));

        $query = "select T1.NameOfWork,case when A.Name is not null then concat(A.Name,'-',T2.name) else T2.Name end as Agency, case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end as WorkId,T1.LastDateAndTimeOfSubmission,T1.TenderOpeningDateAndTime, T1.TentativeStartDate, T1.TentativeEndDate,T1.ContractPeriod,T3.Code as Class, T4.Code as Category, T1.ProjectEstimateCost from etltender T1 join (cmnprocuringagency T2 left join cmnprocuringagency A on A.Id = T2.CmnProcuringAgencyId) on T1.CmnProcuringAgencyId = T2.Id left join cmncontractorclassification as T3 on T3.Id = T1.CmnContractorClassificationId left join cmncontractorworkcategory T4 on T4.Id = T1.CmnContractorCategoryId where T1.TenderSource = 2";

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
            $query .= " and T1.WorkStartDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
        }
        if((bool)$toDate){
            $query.=" and T1.WorkStartDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
        }
        $query.=" order by year(DateOfSaleOfTender) DESC,case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) else T1.migratedworkid end DESC";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/


        $reportData = DB::select("$query$limitOffsetAppend",$parameters);
        return View::make('report.cinettenders')
                ->with('start',$start)
                ->with('noOfPages',$noOfPages)
                ->with('reportData',$reportData)
                ->with('procuringAgencies',$procuringAgencies)
                ->with('contractorClassifications',$classifications)
                ->with('contractorCategories',$categories);
    }

    public function getBidsUploaded(){
        $parameters=array();
        $underProcess=0;
        $procuringAgencyId=Input::get('ProcuringAgency');
        $workStartDateFrom=Input::get('WorkStartDateFrom');
        $workStartDateTo=Input::get('WorkStartDateTo');
        $workOrderNo=Input::get('WorkOrderNo');
        $workStatus=Input::get('WorkExecutionStatus');
        $cdbNo=Input::get('CDBNo');
        $query="select T1.Id,T1.NameOfWork,B.CDBNo,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,T1.WorkCompletionDate,T2.Name as ProcuringAgency,T3.Code as WorkCategory,T4.Code as ContractorClass,T5.Name as WorkExecutionStatus from crpbiddingform T1 left join (crpbiddingformdetail A join crpcontractorfinal B on B.Id = A.CrpContractorFinalId) on A.CrpBiddingFormId = T1.Id and A.CmnWorkExecutionStatusId = ? join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id join cmncontractorworkcategory T3 on T1.CmnContractorProjectCategoryId=T3.Id join cmncontractorclassification T4 on T1.CmnContractorClassificationId=T4.Id join cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where coalesce(T1.ByCDB,0) = 0";
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
        $query.=" and (T1.CmnWorkExecutionStatusId in (?,?,?,?))";
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_UNDERPROCESS);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);

        if((bool)$procuringAgencyId!=NULL || (bool)$workOrderNo!=NULL || (bool)$cdbNo!=NULL || (bool)$workStartDateFrom!=NULL || (bool)$workStartDateTo!=NULL || (bool)$workStatus!=NULL){
            if((bool)$procuringAgencyId!=NULL){
                $query.=" and T1.CmnProcuringAgencyId=?";
                array_push($parameters,$procuringAgencyId);
            }
            if((bool)$workOrderNo!=NULL){
                $query.=" and T1.WorkOrderNo=?";
                array_push($parameters,$workOrderNo);
            }
            if((bool)$workStartDateFrom!=NULL){
                $workStartDateFrom=$this->convertDate($workStartDateFrom);
                $query.=" and T1.WorkStartDate>=?";
                array_push($parameters,$workStartDateFrom);
            }
            if((bool)$workStartDateTo!=NULL){
                $workStartDateTo=$this->convertDate($workStartDateTo);
                $query.=" and T1.WorkStartDate<=?";
                array_push($parameters,$workStartDateTo);
            }
            if((bool)$workStatus!=NULL){
                $query.=" and T1.CmnWorkExecutionStatusId=?";
                array_push($parameters,$workStatus);
            }
            if((bool)$cdbNo){
                $query.=" and B.CDBNo = ?";
                array_push($parameters,$cdbNo);
            }
        }

        $query.=" order by T1.WorkStartDate desc,ProcuringAgency";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $listOfWorks=DB::select($query.$limitOffsetAppend,$parameters);
        $procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
        $workExecutionStatus=CmnListItemModel::workExecutionStatus()->get(array('Id','Name'));
        return View::make('cinet.cinetbiddingforms')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('underProcess',$underProcess)
            ->with('workExecutionStatus',$workExecutionStatus)
            ->with('procuringAgencyId',$procuringAgencyId)
            ->with('workStartDateFrom',$workStartDateFrom)
            ->with('workStartDateTo',$workStartDateTo)
            ->with('workStatus',$workStatus)
            ->with('workOrderNo',$workOrderNo)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('listOfWorks',$listOfWorks);
    }
}