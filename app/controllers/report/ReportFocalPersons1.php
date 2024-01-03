<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 9/30/2015
 * Time: 4:04 PM
 */

class ReportFocalPersons extends ReportController{
    public function getIndex(){
        $type = Input::get('Type');
        $parameters = array(7,8);
        if((bool)$type){
            if($type == "All"){
                $parameters = array(7,8);
            }else{
                $parameters = array($type);
            }
        }

        $reportData = DB::table('sysuser as T1')
                        ->join('sysuserrolemap as T2','T1.Id','=','T2.SysUserId')
                        ->join('sysrole as A','A.Id','=','T2.SysRoleId')
                        ->join('cmnprocuringagency as T3','T3.Id','=','T1.CmnProcuringAgencyId')
                        ->whereIn('A.ReferenceNo',$parameters)
                        ->orderBy('A.Id')
                        ->orderBy('T3.Name')
                        ->orderBy('T1.FullName')
                        ->get(array('T1.FullName','T1.Email','T1.ContactNo','T3.Name as Agency','A.ReferenceNo'));
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Ezotin Focal Persons', function ($excel) use ($reportData, $type) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData, $type) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.reportfocalpersons')
                            ->with('reportData', $reportData)
                            ->with('type', $type);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.reportfocalpersons')
                    ->with('reportData',$reportData);
    }
}