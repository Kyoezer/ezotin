<?php

class ReportSpecializedfirmTrackRecord extends ReportController{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name as ProcuringAgency'));
        $categories = SpecializedfirmServiceCategoryModel::Category()->get(array('Code as ProjectCategory'));
       
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('NameEn as Dzongkhag'));
        $procuringAgencyId = Input::get('ProcuringAgency');
      
        $categoryId = Input::get('ProjectCategory');
        $dzongkhagId = Input::get('Dzongkhag');
        
        $cdbNo = Input::get('SPNo');
        $limit = Input::get('Limit');
        $curYear = date('Y');
        $tenYearsAgo = (int)$curYear - 10;
        $class = Input::get("Class");
        $parameters = array();
        $query = "Select T9.SPNo as SPNo, T1.WorkOrderNo,T1.OntimeCompletionScore,T1.QualityOfExecutionScore,T1.NameOfWork,T1.DescriptionOfWork,T1.ContractPeriod,T1.ApprovedAgencyEstimate,T1.WorkStartDate,T1.Remarks,T1.WorkCompletionDate,T2.Name as Agency, T3.Name as Category, T5.NameEn as Dzongkhag ,T6.Name as WorkExecutionStatus from  crpbiddingform T1 join  cmnprocuringagency as T2 on T1.CmnProcuringAgencyId = T2.Id join cmnspecializedtradecategory as T3 on T1.CmnSpecializedfirmCategoryId = T3.Id join cmndzongkhag as T5 on T1.CmnDzongkhagId = T5.Id left join cmnlistitem as T6 on T6.Id = T1.CmnWorkExecutionStatusId join crpspecializedfirmfinal as T9 on T1.CmnSpecializedfirmCategoryId = T9.Id";
        
        if((bool)$categoryId){
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$categoryId);
        }
        if((bool)$procuringAgencyId){
            $query.=" and ProcuringAgency = ?";
            array_push($parameters,$procuringAgencyId);
        }
        if((bool)$cdbNo){
            $query.=" and SPNo = ?";
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
                Excel::create("Specializedfirm's Track Record", function($excel) use ($reportData,$cdbNo,$limit,$dzongkhag,$procuringAgency,$category) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$cdbNo,$limit,$dzongkhag,$procuringAgency,$category) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(11);
                        $sheet->loadView('reportexcel.specializedfirmtrackrecord')
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
        return View::make('report.specializedfirmtrackrecord')
            ->with('workDetails',$reportData)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('categories',$categories)
            
            ->with('dzongkhags',$dzongkhags);
    }
}