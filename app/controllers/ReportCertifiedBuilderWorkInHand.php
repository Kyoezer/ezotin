<?php

class ReportCertifiedBuilderWorkInHand extends ReportController {
	public function getIndex(){
        $contractors = CertifiedbuilderFinalModel::certifiedBuilderHardListAll()->get(array('NameOfFirm','CDBNo'));
        $cdbNo = Input::get('CDBNo');
        $singleCertifiedbuilder = array();
        $workDetails = array();
        if((bool)$cdbNo){
            $this->auditTrailEtoolCinet(NULL,NULL,"Checked Work In Hand for CDB No $cdbNo");
            $curYear = date('Y');
            $tenYearsAgo = (int)$curYear - 10;
            DB::table('tblworkidtrack')->insert(array('workid'=>$cdbNo,'username'=>Auth::user()->username,'operation'=>'Report 1','op_time'=>date('Y-m-d G:i:s')));
            $singleCertifiedbuilder = DB::table('viewlistofcertifiedbuilder')->where('CDBNo',$cdbNo)->get(array('NameOfFirm','CDBNo'));
            $workDetails = DB::table('viewcertifiedbuildertrackrecords')
                                ->where('CDBNo',$cdbNo)
                                ->where('WorkCompletionDate','>=','2010-01-01')
                                ->where('WorkCompletionDate','>=',"$tenYearsAgo-01-01")
                                ->orderBy(DB::raw("case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end"))
                                ->orderBy('ProcuringAgency')
                                ->get(array('WorkId','CDBNo',DB::raw("case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year"),
                                'ProcuringAgency as Agency','NameOfWork','BidAmount as AwardedAmount','FinalAmount','Dzongkhag','ReferenceNo','WorkStatus as Status',DB::raw("(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS"),'Remarks'));
            if(Input::has('export')){
                $export = Input::get('export');
                if($export == 'excel'){
                    Excel::create("Contractor's work in hand", function($excel) use ($workDetails,$singleCertifiedbuilder) {
                        $excel->sheet('Sheet 1', function($sheet) use ($workDetails,$singleCertifiedbuilder) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.certifiedbuilderworkinhand')
                                ->with('singleCertifiedbuilder',$singleCertifiedbuilder)
                                ->with('workDetails',$workDetails);
                        });

                    })->export('xlsx');
                }
            }
        }
        return View::make('report.certifiedbuilderworkinhand')
                ->with('singleCertifiedbuilder',$singleCertifiedbuilder)
                ->with('contractors',$contractors)
                ->with('workDetails',$workDetails);
    }


}
