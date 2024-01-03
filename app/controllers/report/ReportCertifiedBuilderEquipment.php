<?php

class ReportCertifiedBuilderEquipment extends ReportController{
    public function getIndex(){
        $contractors = CertifiedbuilderFinalModel::certifiedBuilderHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $contractorId = Input::get('CertifiedBuilderId');
        $cdbNo = Input::get('CDBNo');
        $reportData = array();
        if((bool)$contractorId || (bool)$cdbNo){
            if($cdbNo){
                DB::table('tblworkidtrack')->insert(array('workid'=>$cdbNo,'username'=>Auth::user()->username,'operation'=>'Report 3','op_time'=>date('Y-m-d G:i:s')));
            }
            $reportData = DB::table('crpcertifiedbuilderequipmentfinal as T1')
                            ->join('crpcertifiedbuilderfinal as A','A.Id','=','T1.CrpCertifiedBuilderFinalId')
                            ->join('cmnequipment as T2','T1.CmnEquipmentId','=','T2.Id')
                            ->where('T1.CrpCertifiedBuilderFinalId',$contractorId)
                            ->orWhere('A.CDBNo',$cdbNo)
                            ->get(array('T2.Name as Equipment','T1.RegistrationNo','T1.ModelNo','T1.Quantity'));
            if(Input::has('export')){
                $export = Input::get('export');
                $contractor = DB::table('crpcertifiedbuilderfinal')->where('Id',$contractorId)->get(array('NameOfFirm','CDBNo'));
                if($export == 'excel'){
                    Excel::create("CertifiedBuilder's Equipment", function($excel) use ($reportData, $contractor) {
                        $excel->sheet('Sheet 1', function($sheet) use ($reportData, $contractor) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.certifiedbuilderequipment')
                                ->with('contractor',$contractor)
                                ->with('reportData',$reportData);
                        });
                    })->export('xlsx');
                }
            }
        }
        return View::make('report.certifiedbuilderequipment')
            ->with('contractors',$contractors)
            ->with('reportData',$reportData);
    }
}