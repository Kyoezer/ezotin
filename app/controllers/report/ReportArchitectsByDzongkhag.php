<?php

class ReportArchitectsByDzongkhag extends ReportController{
    public function getIndex(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $parameters = array();
        $reportQuery = "select Dzongkhag, NoOfArchitects from viewarchitectsbydzongkhag where 1";

        $dzongkhag = Input::get('Dzongkhag');
        if((bool)$dzongkhag){
            $reportQuery.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }

        $reportData = DB::select($reportQuery,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Architects by Dzongkhag', function($excel) use ($reportData,$dzongkhag) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$dzongkhag) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.architectsbydzongkhag')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.architectsbydzongkhag')
            ->with('reportData',$reportData)
            ->with('dzongkhags',$dzongkhags);
    }
}