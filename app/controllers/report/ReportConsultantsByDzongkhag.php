<?php

class ReportConsultantsByDzongkhag extends ReportController{
    public function getIndex(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('NameEn'));
        $parameters = array();
        $reportQuery = "select * from viewconsultantsbydzongkhag where 1";

        $dzongkhag = Input::get('Dzongkhag');
        if((bool)$dzongkhag){
            $reportQuery.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }

        $reportData = DB::select($reportQuery,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Consultants by Dzongkhag', function($excel) use ($reportData,$dzongkhag) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$dzongkhag) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.consultantsbydzongkhag')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('reportData',$reportData);

                    });
                })->export('xlsx');
            }
        }
        return View::make('report.consultantsbydzongkhag')
            ->with('reportData',$reportData)
            ->with('dzongkhags',$dzongkhags);
    }
}