<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/24/2016
 * Time: 10:10 PM
 */
class ReportConsultantsServicewiseSummary extends ReportController
{
    public function getIndex(){
        $reportData = DB::select("select T1.Code, (select count(*) from crpconsultantfinal A join crpconsultantworkclassificationfinal B on A.Id = B.CrpConsultantFinalId where A.CmnApplicationRegistrationStatusId = ? and B.CmnApprovedServiceId = T1.Id) as NoOfConsultants from cmnconsultantservice T1 order by T1.Code",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED));
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Consultants Service Wise Summary', function ($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.consultantservicewisesummary')
                            ->with('reportData', $reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.consultantservicewisesummary')
                    ->with('reportData',$reportData);
    }
}