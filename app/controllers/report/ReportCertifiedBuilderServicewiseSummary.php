<?php

class ReportCertifiedBuilderServicewiseSummary extends ReportController
{
    public function getIndex(){
        $reportData = DB::select("select T1.Code, T1.Name from cmncertifiedbuildercategory T1 where Code like '%CB%' order by T1.Code",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED));
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Certifiedbuilder Service Wise Summary', function ($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.certifiedbuilderservicewisesummary')
                            ->with('reportData', $reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.certifiedbuilderservicewisesummary')
                    ->with('reportData',$reportData);
    }
}