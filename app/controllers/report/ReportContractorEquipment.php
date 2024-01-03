<?php

class ReportContractorEquipment extends ReportController{
    public function getIndex(){
        $contractors = ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $contractorId = Input::get('ContractorId');
        $cdbNo = Input::get('CDBNo');
        $reportData = array();
        if((bool)$contractorId || (bool)$cdbNo){
            if($cdbNo){
                DB::table('tblworkidtrack')->insert(array('workid'=>$cdbNo,'username'=>Auth::user()->username,'operation'=>'Report 3','op_time'=>date('Y-m-d G:i:s')));
            }
            $reportData = DB::table('crpcontractorequipmentfinal as T1')
                            ->join('crpcontractorfinal as A','A.Id','=','T1.CrpContractorFinalId')
                            ->join('cmnequipment as T2','T1.CmnEquipmentId','=','T2.Id')
                            ->where('T1.CrpContractorFinalId',$contractorId)
                            ->orWhere('A.CDBNo',$cdbNo)
                            ->get(array('T2.Name as Equipment','T1.RegistrationNo','T1.ModelNo','T1.Quantity'));
            if(Input::has('export')){
                $export = Input::get('export');
                $contractor = DB::table('crpcontractorfinal')->where('Id',$contractorId)->get(array('NameOfFirm','CDBNo'));
                if($export == 'excel'){
                    Excel::create("Contractor's Equipment", function($excel) use ($reportData, $contractor) {
                        $excel->sheet('Sheet 1', function($sheet) use ($reportData, $contractor) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.contractorequipment')
                                ->with('contractor',$contractor)
                                ->with('reportData',$reportData);
                        });
                    })->export('xlsx');
                }
            }
        }
        return View::make('report.contractorequipment')
            ->with('contractors',$contractors)
            ->with('reportData',$reportData);
    }
}