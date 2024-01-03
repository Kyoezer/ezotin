<?php

class ReportListOfTerminatedCancelled extends ReportController{
    public function getIndex(){
        $parameters = array();
        $statuses = CmnListItemModel::workExecutionStatus()->whereIn('ReferenceNo',array(3004,3005))->get(array('Name','Id'));
        $query = "select T1.ProcuringAgency, T1.WorkId, T1.NameOfWork, T1.ContractPeriod, T1.Contractors, T1.StartDate, T1.EndDate,T1.Status from viewlistofterminatedcancelled T1 where 1";
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $status = Input::get('Status');
        if((bool)$status){
            $query.=" and T1.Status = ?";
            array_push($parameters,$status);
        }
        if((bool)$fromDate){
            $query.=" and T1.StartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.StartDate <= ?";
            array_push($parameters,$toDate);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('List of Terminated/Cancelled', function ($excel) use ($reportData,$status,$fromDate, $toDate) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData,$status,$fromDate, $toDate) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofterminatedcancelled')
                            ->with('fromDate', $fromDate)
                            ->with('toDate',$toDate)
                            ->with('status',$status)
                            ->with('reportData', $reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofterminatedcancelled')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('statuses',$statuses)
            ->with('reportData',$reportData);
    }
}