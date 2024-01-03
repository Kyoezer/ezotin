<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 4/22/2015
 * Time: 11:18 AM
 */

class ReportDzongkhagWiseWork extends ReportController{
    public function getIndex(){
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $query = "select T1.Dzongkhag, sum(case T1.StatusCode when 3001 then 1 else 0 end) as NoAwarded, sum(case T1.StatusCode when 3003 then 1 else 0 end) as NoCompleted from viewworkhistory T1 where T1.TenderSource = 1";
        $parameters = array();
        if((bool)$fromDate){
            $query.=" and T1.StartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.StartDate <= ?";
            array_push($parameters,$toDate);
        }
        $reportData = DB::select("$query group by T1.Dzongkhag",$parameters);
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Dzongkhag Wise Work', function ($excel) use ($reportData, $fromDate, $toDate) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData, $fromDate, $toDate) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.dzongkhagwisework')
                            ->with('fromDate', $fromDate)
                            ->with('reportData', $reportData)
                            ->with('toDate', $toDate);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.dzongkhagwisework')
            ->with('reportData',$reportData);
    }
}