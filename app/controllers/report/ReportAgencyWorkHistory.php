<?php

class ReportAgencyWorkHistory extends ReportController{
    public function getIndex(){
        $year = Input::get('Year');
        $class = Input::get('classification');
        $category = Input::get('Category');
        $agency = Input::get('Agency');
        $type = Input::get('Type');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $classifications = DB::table('cmncontractorclassification')->get(array('Name as classification'));
        $categories = DB::table('cmncontractorworkcategory')->get(array('Code as ProjectCategory'));
        $procuringAgencies = DB::table('cmnprocuringagency')->get(array('Name as ProcuringAgency','Code'));

        $query = "select T1.NameOfWork,T1.ProcuringAgencyCode, T1.Type, T1.DescriptionOfWork,T1.ContractPeriod,year(T1.WorkCompletionDate) as Year,T1.FinalAmount,T1.Contractor,T1.ProjectCategory,T1.classification,T1.Dzongkhag,T1.CDBNo,T1.WorkStatus from viewcontractorstrackrecords T1 where 1";
        $parameters = array();
        $hasParams = false;

        if((bool)$fromDate || (bool)$toDate){
            if((bool)$fromDate){
                $hasParams = true;
                $query.=" and T1.WorkStartDate >= ?";
                array_push($parameters,$this->convertDate($fromDate));
            }
            if((bool)$toDate){
                $hasParams = true;
                $query.=" and T1.WorkStartDate <= ?";
                array_push($parameters,$this->convertDate($toDate));
            }
        }else{
            if((bool)$year){
                $hasParams = true;
                $query.=" and year(T1.WorkCompletionDate) = ?";
                array_push($parameters,$year);
            }
        }
        
        if((bool)$class){
            $hasParams = true;
            $query.=" and classification = ?";
            array_push($parameters,$class);
        }
        if((bool)$type){
            $hasParams = true;
            $query.=" and Type = ?";
            array_push($parameters,(int)$type);
        }
        if((bool)$agency){
            $hasParams = true;
            $query.=" and (ProcuringAgency = ? or ParentProcuringAgency = ?)";
            array_push($parameters,$agency);
            array_push($parameters,$agency);
        }
        if((bool)$category){
            $hasParams = true;
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$category);
        }
        $noOfPages = 0;
        $start = 0;
        if($hasParams){
            $query.="";
            /*PAGINATION*/
            $pageNo = Input::has('page')?Input::get('page'):1;
            $pagination = $this->pagination($query,$parameters,10,$pageNo);
            $limitOffsetAppend = $pagination['LimitAppend'];
            $noOfPages = $pagination['NoOfPages'];
            $start = $pagination['Start'];
            /*END PAGINATION*/

            $reportData = DB::select("$query$limitOffsetAppend",$parameters);
        }

        else{
            $reportData = array();
        }
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Agency Work History', function ($excel) use ($reportData, $year,$class,$category) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData, $year,$class,$category) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.agencyworkhistory')
                            ->with('year', $year)
                            ->with('class', $class)
                            ->with('reportData', $reportData)
                            ->with('category', $category);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.agencyworkhistory')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('classifications',$classifications)
            ->with('categories',$categories)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('reportData',$reportData);
    }
}