<?php

class CategoryWiseReport extends ReportController{
    public function getIndex(){
        $parameters = array();
        $limit = Input::get('Limit');
        $dzongkhag = Input::get('Dzongkhag');
        $category = Input::get('Category');
        $class = Input::get('Classification');
        $agency = Input::get('Agency');
        $status = Input::get('Status');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $dzongkhags = DB::table('viewcontractorstrackrecords')->get(array(DB::raw('distinct(Dzongkhag)')));
        $categories = DB::table('viewcontractorstrackrecords')->get(array(DB::raw('distinct(ProjectCategory) as Category')));
        $classes = DB::table('viewcontractorstrackrecords')->get(array(DB::raw('distinct(classification) as Classification')));
        $agencies = DB::table('viewcontractorstrackrecords')->get(array(DB::raw('distinct(ProcuringAgency), ProcuringAgencyCode')));
        $statuses = DB::table('cmnlistitem')->where('ReferenceNo','<>',3002)->where('CmnListId',CONST_CMN_REFERENCE_WORKCOMPLETIONSTATUS)->get(array('ReferenceNo','Name'));
        $query = "select * from viewcontractorstrackrecords where 1";
        if((bool)$dzongkhag){
            $parametersForPrint['Dzongkhag'] =$dzongkhag;
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$status){
            $parametersForPrint['Status'] =DB::table('cmnlistitem')->where('ReferenceNo',$status)->pluck('Name');
            $query.=" and ReferenceNo = ?";
            array_push($parameters,$status);
        }
        if((bool)$category){
            $parametersForPrint['Category'] =$category;
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$category);
        }
        if((bool)$class){
            $parametersForPrint['Class'] =$class;
            $query.=" and classification = ?";
            array_push($parameters,$class);
        }
        if((bool)$fromDate){
            $parametersForPrint['Start Date'] =$fromDate;
            $fromDate = $this->convertDate($fromDate);
            $query.=" and WorkStartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $parametersForPrint['End Date'] =$toDate;
            $toDate = $this->convertDate($toDate);
            $query.=" and WorkStartDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$agency){
            $parametersForPrint['Agency'] =$agency;
            $query.=" and ProcuringAgency = ? or ParentProcuringAgency = ?";
            array_push($parameters,$agency);
            array_push($parameters,$agency);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                $reportData = DB::select($query,$parameters);
                Excel::create('Category Wise Report', function($excel) use ($reportData,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.categorywisereport')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('reportData',$reportData);
                    });
                })->export('xlsx');
            }
        }
        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        return View::make('report.categorywisereport')
            ->with('start',$start)
            ->with('statuses',$statuses)
            ->with('noOfPages',$noOfPages)
            ->with('dzongkhags',$dzongkhags)
            ->with('classifications',$classes)
            ->with('categories',$categories)
            ->with('agencies',$agencies)
            ->with('reportData',$reportData);
    }
}