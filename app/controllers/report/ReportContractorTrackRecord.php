<?php

class ReportContractorTrackRecord extends ReportController{
    public function getIndex(){
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
        $curYear = date('Y');
        $tenYearsAgo = (int)$curYear - 10;
        $class = Input::get("Class");
        $parameters = array();
        $query = "select WorkId,CDBNo,Classification,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,coalesce(ContractPriceFinal,FinalAmount) as FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where 1=1";
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
        if((bool)$class){
            $query.=" and Classification = ?";
            array_push($parameters,$class);
        }
        $query.=" and WorkCompletionDate >= '$tenYearsAgo-01-01' order by case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end,ProcuringAgency";
        if((bool)$cdbNo!=NULL){
            $reportData = DB::select($query, $parameters);
        }else{
            $reportData = array();
        }
        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = $dzongkhagId;
            $procuringAgency = $procuringAgencyId;
            $category = $categoryId;
            $cdbNo = $cdbNo;
            if($export == 'excel'){
                Excel::create("Contractor's Track Record", function($excel) use ($reportData,$cdbNo,$limit,$dzongkhag,$procuringAgency,$category) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$cdbNo,$limit,$dzongkhag,$procuringAgency,$category) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(11);
                        $sheet->loadView('reportexcel.contractortrackrecord')
                            ->with('limit',$limit)
                            ->with('dzongkhag',$dzongkhag)
                            ->with('procuringAgency',$procuringAgency)
                            ->with('category',$category)
                            ->with('cdbNo',$cdbNo)
                            ->with('workDetails',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.contractortrackrecord')
            ->with('workDetails',$reportData)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('categories',$categories)
            ->with('classes',$classes)
            ->with('dzongkhags',$dzongkhags);
    }
}