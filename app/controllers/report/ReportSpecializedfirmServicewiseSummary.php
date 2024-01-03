<?php

class ReportSpecializedfirmServicewiseSummary extends ReportController
{
    public function getIndex(){
        $reportData = DB::select("select T1.Code, T1.Name,(select count(*) from crpspecializedfirmfinal A join crpspecializedfirmworkclassificationfinal B on A.Id = B.CrpSpecializedTradeFinalId where A.CmnApplicationRegistrationStatusId = ? and B.CmnApprovedCategoryId = T1.Id) as NoOfSpecializedfirm from cmnspecializedtradecategory T1 where Code like '%SF%' order by T1.Code",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED));
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Specializedfirm Service Wise Summary', function ($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.specializedfirmservicewisesummary')
                            ->with('reportData', $reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.specializedfirmservicewisesummary')
                    ->with('reportData',$reportData);
    }
}