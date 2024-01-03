<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/3/2016
 * Time: 4:03 PM
 */
class ReportCostOverrun extends BaseController
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
        $curYear = date('Y');
        $tenYearsAgo = (int)$curYear - 10;
        $parameters = array();
        $class = Input::get("Class");
        $query = "select WorkId,CDBNo,Contractor,Classification,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,AwardedAmount,ContractPriceFinal as FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where 1=1";
        if((bool)$categoryId){
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$categoryId);
        }
        if((bool)$procuringAgencyId){
            $query.=" and ProcuringAgency = ?";
            array_push($parameters,$procuringAgencyId);
        }
        if((bool)$cdbNo){
            $query.=" and CDBNo = ?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$dzongkhagId){
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query.=" and WorkCompletionDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query.=" and WorkStartDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$class){
            $query.=" and Classification = ?";
            array_push($parameters,$class);
        }
        $query.=" and ContractPriceFinal > AwardedAmount and ReferenceNo = 3003";
        $query.=" and WorkCompletionDate >= '$tenYearsAgo-01-01' order by case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end,ProcuringAgency";

        $reportData = DB::select($query, $parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = $dzongkhagId;
            $procuringAgency = $procuringAgencyId;
            $category = $categoryId;
            $cdbNo = $cdbNo;
            if(Input::get("export") == "excel"){
                Excel::create('Cost Overrun Report (Detailed)', function($excel) use ($limit,$dzongkhag,$class,$procuringAgency,$category,$cdbNo,$reportData) {
                    $excel->sheet('Sheet 1', function($sheet) use ($limit,$dzongkhag,$class,$procuringAgency,$category,$cdbNo,$reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.contractortrackrecord')
                            ->with('overrunReport',1)
                            ->with('limit',$limit)
                            ->with('dzongkhag',$dzongkhag)
                            ->with('class',$class)
                            ->with('procuringAgency',$procuringAgency)
                            ->with('category',$category)
                            ->with('cdbNo',$cdbNo)
                            ->with('workDetails',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.contractortrackrecord')
            ->with('overrunReport',1)
            ->with('classes',$classes)
            ->with('workDetails',$reportData)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('categories',$categories)
            ->with('dzongkhags',$dzongkhags);
    }
    public function getSummary(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name as ProcuringAgency'));
        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Code as ProjectCategory'));
        $classes = ContractorClassificationModel::classification()->get(array('Code as Class'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('NameEn as Dzongkhag'));
        $procuringAgencyId = Input::get('ProcuringAgency');
        $classificationId = Input::get('Classification');
        $categoryId = Input::get('ProjectCategory');
        $dzongkhagId = Input::get('Dzongkhag');
        $cdbNo = Input::get('CDBNo');
        $limit = Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $curYear = date('Y');
        $tenYearsAgo = (int)$curYear - 10;
        $class = Input::get("Class");
        $parameters = array();
        $query = "select WorkId,CDBNo,Classification,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,sum(AwardedAmount) as AwardedAmount,sum(ContractPriceFinal) as FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where 1=1";
        if((bool)$categoryId){
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$categoryId);
        }
        if((bool)$class){
            $query.=" and Classification = ?";
            array_push($parameters,$class);
        }
        if((bool)$procuringAgencyId){
            $query.=" and ProcuringAgency = ?";
            array_push($parameters,$procuringAgencyId);
        }
        if((bool)$cdbNo){
            $query.=" and CDBNo = ?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$dzongkhagId){
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query.=" and WorkCompletionDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query.=" and WorkStartDate <= ?";
            array_push($parameters,$toDate);
        }
        $query.=" and ContractPriceFinal > AwardedAmount and ReferenceNo = 3003";
        $query.=" and WorkCompletionDate >= '$tenYearsAgo-01-01' group by ProcuringAgency order by case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end,ProcuringAgency";
        $reportData = DB::select($query, $parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = $dzongkhagId;
            $procuringAgency = $procuringAgencyId;
            $category = $categoryId;
            $cdbNo = $cdbNo;
            if(Input::get("export") == "excel"){

                Excel::create('Cost Overrun Report (Summary)', function($excel) use ($limit,$dzongkhag,$class,$procuringAgency,$category,$cdbNo,$reportData) {
                    $excel->sheet('Sheet 1', function($sheet) use ($limit,$dzongkhag,$class,$procuringAgency,$category,$cdbNo,$reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.contractortrackrecord')
                            ->with('overrunReport',2)
                            ->with('limit',$limit)
                            ->with('dzongkhag',$dzongkhag)
                            ->with('class',$class)
                            ->with('procuringAgency',$procuringAgency)
                            ->with('category',$category)
                            ->with('cdbNo',$cdbNo)
                            ->with('workDetails',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.contractortrackrecord')
            ->with('overrunReport',2)
            ->with('workDetails',$reportData)
            ->with('classes',$classes)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('categories',$categories)
            ->with('dzongkhags',$dzongkhags);
    }
}