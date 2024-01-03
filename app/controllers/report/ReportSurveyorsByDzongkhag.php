<?php

class ReportSurveyorsByDzongkhag extends ReportController{
    public function getIndex(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $parameters = array();
        $reportQuery =  "select T1.Id,T3.NameEn as Dzongkhag , count(*) as NoOfSurveyors  from crpsurveyfinal T1 join  cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id Group by Dzongkhag";
    
        $dzongkhag = Input::get('Dzongkhag');
        if((bool)$dzongkhag){
            $reportQuery.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }

        $reportData = DB::select($reportQuery,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Surveyors by Dzongkhag', function($excel) use ($reportData,$dzongkhag) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$dzongkhag) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.surveyorsbydzongkhag')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.surveyorsbydzongkhag')
            ->with('reportData',$reportData)
            ->with('dzongkhags',$dzongkhags);
    }
}