<?php

class ReportAPSContractorCompleted extends ReportController{
    public function getIndex(){
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $cdbNo = Input::get('CDBNo');
        $to = Input::get('To');
        $from = Input::get('From');
        $class = Input::get('Class');
        $category = Input::get('Category');
        $classes = DB::table('cmncontractorclassification')->orderBy('Priority','DESC')->get(array('Code'));
        $categories = DB::table('cmncontractorworkcategory')->orderBy('Code')->get(array('Code'));
        $query = "select T1.CDBNo,T1.Contractor, T1.WorkId, year(T1.WorkCompletionDate) as Year,T1.WorkCompletionDate,T1.WorkStartDate, T1.ProcuringAgency as Agency,T1.NameOfWork,T1.ProjectCategory as Category,T1.AwardedAmount,T1.ContractPriceFinal as FinalAmount,T1.Dzongkhag, T1.WorkStatus as Status, coalesce(T1.OntimeCompletionScore,0) as OntimeCompletionScore, coalesce(T1.QualityOfExecutionScore) as QualityOfExecutionScore from viewcontractorstrackrecords T1 where T1.ReferenceNo = 3003";
        $parameters = array();
        if((bool)$fromDate){
            $query.=" and T1.WorkCompletionDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
        }
        if((bool)$toDate){
            $query.=" and T1.WorkCompletionDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
        }
        if(Request::path() == "etoolrpt/apscontractorcompleted"){
            $type = 1;
            if((bool)$to){
                $query.=" and (coalesce(T1.OntimeCompletionScore,0) + coalesce(T1.QualityOfExecutionScore,0)) <= ?";
                array_push($parameters,$to);
            }
            if((bool)$from){
                $query.=" and (coalesce(T1.OntimeCompletionScore,0) + coalesce(T1.QualityOfExecutionScore,0)) >= ?";
                array_push($parameters,$from);
            }
        }elseif(Request::path() =="etoolrpt/apscontractorontime"){
            $type = 2;
            if((bool)$to){
                $query.=" and coalesce(T1.OntimeCompletionScore,0) <= ?";
                array_push($parameters,$to);
            }
            if((bool)$from){
                $query.=" and coalesce(T1.OntimeCompletionScore,0) >= ?";
                array_push($parameters,$from);
            }
        }elseif(Request::path() == "etoolrpt/apscontractorquality"){
            $type = 3;
            if((bool)$to){
                $query.=" and coalesce(T1.QualityOfExecutionScore,0) <= ?";
                array_push($parameters,$to);
            }
            if((bool)$from){
                $query.=" and coalesce(T1.QualityOfExecutionScore,0) >= ?";
                array_push($parameters,$from);
            }
        }else{
            App::abort('404');
        }

        if((bool)$class){
            $query.=" and T1.classification = ?";
            array_push($parameters,$class);
        }
        if((bool)$category){
            $query.=" and T1.ProjectCategory = ?";
            array_push($parameters,$category);
        }
        if((bool)$cdbNo){
            $query.=" and T1.CDBNo = ?";
            array_push($parameters,$cdbNo);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select("$query order by T1.CDBNo $limitOffsetAppend",$parameters);

        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Top Performing Contractor', function ($excel) use ($reportData, $fromDate, $toDate,$type) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData, $fromDate, $toDate,$type) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.apsofcontractorcompleted')
                            ->with('type',$type)
                            ->with('fromDate', $fromDate)
                            ->with('reportData', $reportData)
                            ->with('toDate', $toDate);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.apsofcontractorcompleted')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('type',$type)
            ->with('classes',$classes)
            ->with('categories',$categories)
            ->with('reportData',$reportData);
    }
}