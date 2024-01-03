<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/3/2016
 * Time: 4:03 PM
 */
class ReportTimeOverrun extends BaseController
{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name as ProcuringAgency'));
        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Code as ProjectCategory'));
        $classes = ContractorClassificationModel::classification()->get(array('Code as Class'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('NameEn as Dzongkhag'));
        $procuringAgencyId = Input::get('ProcuringAgency');
        $categoryId = Input::get('ProjectCategory');
        $dzongkhagId = Input::get('Dzongkhag');
        $cdbNo = Input::get('CDBNo');
        $limit = Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $class = Input::get("Class");
        $curYear = date('Y');
        $tenYearsAgo = (int)$curYear - 10;
        $parameters = array();
        $parametersForPrint = array();
        if(Request::path() == "rpt/timeoverrunreportsummary"){
            $summary = true;
            $query = "select WorkId,CDBNo,ActualEndDate,CompletionDateFinal,Classification,sum(DATEDIFF(CompletionDateFinal,ActualEndDate)) as DateDiff,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,sum(AwardedAmount) as AwardedAmount,sum(ContractPriceFinal) as FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where 1=1";
        }else{
            $summary = false;
            $query = "select WorkId,CDBNo,Contractor,ActualEndDate,CompletionDateFinal,LDImposed,LDNoOfDays,LDAmount,Hindrance,HindranceNoOfDays,Classification,DATEDIFF(CompletionDateFinal,ActualEndDate) as DateDiff,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,AwardedAmount,ContractPriceFinal as FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where 1=1";
        }
        if((bool)$categoryId){
            $query.=" and ProjectCategory = ?";
            $parametersForPrint['Category'] = $categoryId;
            array_push($parameters,$categoryId);
        }
        if((bool)$procuringAgencyId){
            $query.=" and ProcuringAgency = ?";
            $parametersForPrint['ProcuringAgency'] = $procuringAgencyId;
            array_push($parameters,$procuringAgencyId);
        }
        if((bool)$cdbNo){
            $query.=" and CDBNo = ?";
            $parametersForPrint['CDBNo'] = $cdbNo;
            array_push($parameters,$cdbNo);
        }
        if((bool)$dzongkhagId){
            $query.=" and Dzongkhag = ?";
            $parametersForPrint['Dzongkhag'] = $dzongkhagId;
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and WorkCompletionDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and WorkCompletionDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$class){
            $query.=" and Classification = ?";
            $parametersForPrint['Classification'] = $class;
            array_push($parameters,$class);
        }
        $append = "group by coalesce(WorkId,RecordId)";
        if(Request::path() == "rpt/timeoverrunreportsummary"){
            $append = "group by ProcuringAgency";
        }
        $query.=" and CompletionDateFinal > ActualEndDate and ReferenceNo = 3003";
        $query.=" and WorkCompletionDate >= '$tenYearsAgo-01-01' and ActualEndDate <> '0000-00-00' $append order by case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end,ProcuringAgency";
        $reportData = DB::select($query, $parameters);
        if(Input::get('export') == 'excel'){
            Excel::create('Time Overrun Report', function($excel) use ($summary,$parametersForPrint,$reportData) {
                $excel->sheet('Report', function($sheet) use ($summary,$parametersForPrint,$reportData) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->setPaperSize(1);
                    $sheet->loadView('reportexcel.timeoverrunreport')
                        ->with('summary',$summary)
                        ->with('parametersForPrint',$parametersForPrint)
                        ->with('workDetails',$reportData);

                });

            })->export('xlsx');
        }
        return View::make('report.timeoverrunreport')
            ->with('summary',$summary)
            ->with('workDetails',$reportData)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('categories',$categories)
            ->with('classes',$classes)
            ->with('dzongkhags',$dzongkhags);
    }
}