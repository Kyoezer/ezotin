<?php

class ReportSelfWorkHistory extends ReportController{
    public function getIndex(){
        $year = Input::get('Year');
        $class = Input::get('classification');
        $category = Input::get('Category');
        $status = Input::get('Status');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $classifications = DB::table('cmncontractorclassification')->get(array('Name as classification'));
        $categories = DB::table('cmncontractorworkcategory')->get(array('Code as ProjectCategory'));
        $query = "select T1.NameOfWork, T1.DescriptionOfWork,T1.ContractPeriod,T1.WorkId,year(T1.WorkCompletionDate) as Year,T1.FinalAmount,T1.Contractor,T1.ProjectCategory,T1.classification,T1.Dzongkhag,T1.CDBNo,T1.WorkStatus from viewcontractorstrackrecords T1 where 1";
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
        if((bool)$status){
            $hasParams = true;
            $query.=" and ReferenceNo = ?";
            array_push($parameters,$status);
        }
        if((bool)$class){
            $hasParams = true;
            $query.=" and classification = ?";
            array_push($parameters,$class);
        }
        if((bool)$category){
            $hasParams = true;
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$category);
        }
        $start = 0;
        $noOfPages = 0;

        $selfProcuringAgencyId = DB::table('sysuser as T1')->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')->where('T1.Id',Auth::user()->Id)->pluck('T2.Name');
        array_push($parameters,$selfProcuringAgencyId);
        if($hasParams){
            $query.=" and T1.ProcuringAgency = ? order by year(T1.WorkCompletionDate) DESC";
            /*PAGINATION*/
            $pageNo = Input::has('page')?Input::get('page'):1;
            $pagination = $this->pagination($query,$parameters,10,$pageNo);
            $limitOffsetAppend = $pagination['LimitAppend'];
            $noOfPages = $pagination['NoOfPages'];
            $start = $pagination['Start'];
            /*END PAGINATION*/

            $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        }else
            $reportData = array();
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
        return View::make('report.selfworkhistory')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('classifications',$classifications)
            ->with('categories',$categories)
            ->with('reportData',$reportData);
    }
}