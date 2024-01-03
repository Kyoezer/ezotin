<?php

class ReportContractorWorkInHand extends ReportController{
    public function getIndex(){
        $contractors = ContractorFinalModel::contractorHardListAll()->get(array('NameOfFirm','CDBNo'));
        $cdbNo = Input::get('CDBNo');
        $singleContractor = array();
        $workDetails = array();
        if((bool)$cdbNo){
            $this->auditTrailEtoolCinet(NULL,NULL,"Checked Work In Hand for CDB No $cdbNo");
            $curYear = date('Y');
            $tenYearsAgo = (int)$curYear - 10;
            DB::table('tblworkidtrack')->insert(array('workid'=>$cdbNo,'username'=>Auth::user()->username,'operation'=>'Report 1','op_time'=>date('Y-m-d G:i:s')));
            $singleContractor = DB::table('viewlistofcontractors')->where('CDBNo',$cdbNo)->get(array('NameOfFirm','CDBNo','Classification1','Classification2','Classification3','Classification4'));
            $workDetails = DB::table('viewcontractorstrackrecords')
                                ->where('CDBNo',$cdbNo)
                                ->where('WorkCompletionDate','>=','2010-01-01')
//                                ->where('WorkCompletionDate','>=',"$tenYearsAgo-01-01")
                                ->orderBy(DB::raw("case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end"))
                                ->orderBy('ProcuringAgency')
                                ->get(array('WorkId','CDBNo',DB::raw("case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year"),
                                'ProcuringAgency as Agency','NameOfWork','ProjectCategory as Category','BidAmount as AwardedAmount','FinalAmount','Dzongkhag','ReferenceNo','WorkStatus as Status',DB::raw("(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS"),'Remarks'));
            if(Input::has('export')){
                $export = Input::get('export');
                if($export == 'excel'){
                    Excel::create("Contractor's work in hand", function($excel) use ($workDetails, $singleContractor) {
                        $excel->sheet('Sheet 1', function($sheet) use ($workDetails, $singleContractor) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.contractorworkinhand')
                                ->with('singleContractor',$singleContractor)
                                ->with('workDetails',$workDetails);
                        });

                    })->export('xlsx');
                }
            }
        }
        return View::make('report.contractorworkinhand')
                ->with('contractors',$contractors)
                ->with('singleContractor',$singleContractor)
                ->with('workDetails',$workDetails);
    }
}