<?php

class ReportCertifiedBuilderHumanResource extends ReportController{
    public function getIndex(){
        $contractors = CertifiedbuilderFinalModel::certifiedBuilderHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $contractorId = Input::get('CertifiedBuilderId');
        $cdbNo = Input::get('CDBNo');
        $reportData = array();
        if((bool)$contractorId || (bool)$cdbNo){
            $reportData = DB::table("crpcertifiedbuilderhumanresourcefinal as T1")
                                ->join('crpcertifiedbuilderfinal as A','A.Id','=','T1.CrpCertifiedBuilderFinalId')
                                ->join('cmnlistitem as T2','T1.CmnDesignationId','=','T2.Id')
                                ->leftJoin('cmnlistitem as T3','T1.CmnQualificationId','=','T3.Id')
                                ->leftJoin('cmnlistitem as T4','T1.CmnTradeId','=','T4.Id')
                                ->join('cmncountry as T5','T5.Id','=','T1.CmnCountryId')
                                ->where('T1.CrpCertifiedBuilderFinalId',$contractorId)
                                ->orWhere('A.CDBNo',$cdbNo)
                                ->orderBy(DB::raw('coalesce(T1.IsPartnerOrOwner,0)'), "DESC")
                                ->get(array('T1.Name','T2.Name as Designation','T1.CIDNo','T3.Name as Qualification','T4.Name as Trade','T5.Name as Country'));
            if($cdbNo){
                DB::table('tblworkidtrack')->insert(array('workid'=>$cdbNo,'username'=>Auth::user()->username,'operation'=>'Report 2','op_time'=>date('Y-m-d G:i:s')));
            }
            if(Input::has('export')){
                $export = Input::get('export');
                $contractor = DB::table('crpcertifiedbuilderfinal')->where('Id',$contractorId)->get(array('NameOfFirm','CDBNo'));
                if($export == 'excel'){
                    Excel::create("certifiedbuilder's Key Personnel", function($excel) use ($reportData, $contractor) {
                        $excel->sheet('Sheet 1', function($sheet) use ($reportData, $contractor) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.certifiedbuilderhumanresource')
                                ->with('contractor',$contractor)
                                ->with('reportData',$reportData);
                        });
                    })->export('xlsx');
                }
            }
        }
        return View::make('report.certifiedbuilderhumanresource')
                ->with('contractors',$contractors)
                ->with('reportData',$reportData);
    }
}