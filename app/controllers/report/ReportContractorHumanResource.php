<?php

class ReportContractorHumanResource extends ReportController{
    public function getIndex(){
        $contractors = ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $contractorId = Input::get('ContractorId');
        $cdbNo = Input::get('CDBNo');
        $reportData = array();
        if((bool)$contractorId || (bool)$cdbNo){
            $reportData = DB::table("crpcontractorhumanresourcefinal as T1")
                                ->join('crpcontractorfinal as A','A.Id','=','T1.CrpContractorFinalId')
                                ->join('cmnlistitem as T2','T1.CmnDesignationId','=','T2.Id')
                                ->leftJoin('cmnlistitem as T3','T1.CmnQualificationId','=','T3.Id')
                                ->leftJoin('cmnlistitem as T4','T1.CmnTradeId','=','T4.Id')
                                ->join('cmncountry as T5','T5.Id','=','T1.CmnCountryId')
                                ->where('T1.CrpContractorFinalId',$contractorId)
                                ->orWhere('A.CDBNo',$cdbNo)
                                ->orderBy(DB::raw('coalesce(T1.IsPartnerOrOwner,0)'), "DESC")
                                ->get(array('T1.Name','T2.Name as Designation','T1.CIDNo','T3.Name as Qualification','T4.Name as Trade','T5.Name as Country'));
            if($cdbNo){
                DB::table('tblworkidtrack')->insert(array('workid'=>$cdbNo,'username'=>Auth::user()->username,'operation'=>'Report 2','op_time'=>date('Y-m-d G:i:s')));
            }
            if(Input::has('export')){
                $export = Input::get('export');
                $contractor = DB::table('crpcontractorfinal')->where('Id',$contractorId)->get(array('NameOfFirm','CDBNo'));
                if($export == 'excel'){
                    Excel::create("Contractor's Key Personnel", function($excel) use ($reportData, $contractor) {
                        $excel->sheet('Sheet 1', function($sheet) use ($reportData, $contractor) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.contractorhumanresource')
                                ->with('contractor',$contractor)
                                ->with('reportData',$reportData);
                        });
                    })->export('xlsx');
                }
            }
        }
        return View::make('report.contractorhumanresource')
                ->with('contractors',$contractors)
                ->with('reportData',$reportData);
    }
}