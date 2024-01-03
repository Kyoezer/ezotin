<?php

class ReportAgencyCategoryWiseWork extends ReportController{
    public function getIndex(){
        $parameters = array();
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $ministry = Input::get('Ministry');
        $ministries = CmnListItemModel::ministry()->get(array('Name'));
        $query = "select distinct T1.Id,Agency, SUM(case when (T1.Category = 'W1' and T1.StatusCode = 3001) then 1 else 0 end) as W1Awarded, 
                    sum(case when (T1.Category = 'W1' and T1.StatusCode = 3003) then 1 else 0 end) as W1Completed,
                    sum(case when (T1.Category = 'W2' and T1.StatusCode = 3001) then 1 else 0 end) as W2Awarded, 
                    sum(case when (T1.Category = 'W2' and T1.StatusCode = 3003) then 1 else 0 end) as W2Completed,
                    sum(case when (T1.Category = 'W3' and T1.StatusCode = 3001) then 1 else 0 end) as W3Awarded,
                    sum(case when (T1.Category = 'W3' and T1.StatusCode = 3003) then 1 else 0 end) as W3Completed,
                    sum(case when (T1.Category = 'W4' and T1.StatusCode = 3001) then 1 else 0 end) as W4Awarded, 
                    sum(case when (T1.Category = 'W4' and T1.StatusCode = 3003) then 1 else 0 end) as W4Completed 
                    from viewworkhistory T1 where 1";

        if((bool)$fromDate){
            $query.=" and T1.StartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.StartDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$ministry){
            $query.=" and T1.Ministry = ?";
            array_push($parameters,str_replace('&amp;','&',$ministry));
        }
//        $query.=" and T1.Agency = 'Trashi Yangtse'";
        $reportData = DB::select("$query group by T1.CmnProcuringAgencyId order by T1.Agency",$parameters);
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Agency Wise Category Wise Work Awarded and Completed', function ($excel) use ($reportData,$fromDate, $toDate) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData,$fromDate, $toDate) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.agencycategorywisework')
                            ->with('fromDate', $fromDate)
                            ->with('reportData', $reportData)
                            ->with('toDate', $toDate);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.agencycategorywisework')
            ->with('ministries',$ministries)
            ->with('reportData',$reportData);
    }
}

