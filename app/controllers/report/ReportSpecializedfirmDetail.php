<?php

class ReportSpecializedfirmDetail extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
     
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        
        $limit=Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $cdbNo = Input::get('SPNo');
        $refNo = Input::get('ReferenceNo');
        $query = "SELECT ReferenceNo, NameOfFirm, SPNo,RemarksByRejector, ApplicationDate, VerifiedBy, AprroverBy, RejectedBy, PaymentApprover, ServiceType, CurrentStatus, RemarksByVerifier, RemarksByApprover, RemarksByPaymentApprover, ( CASE WHEN CurrentStatus = 'Approved' OR CurrentStatus = 'Rejected' THEN CurrentlyPickedBy = NULL ELSE CurrentlyPickedBy END) AS `CurrentlyPickedBy`, RejectedDate, PaymentApprovedDate, RegistrationPaymentApprovedDate, RegistrationApprovedDate, RegistrationVerifiedDate FROM SpecializedfirmApplicationDetail where 1";
 $status = Input::get('CurrentStatus');
        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and SPNo like '%$cdbNo%'";
        }
        $parameters = array();
        if((bool)$refNo){
            $query.=" and ReferenceNo like '%$refNo%'";
        }
        if((bool)$fromDate){
            $query.=" and ApplicationDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and ApplicationDate <= ?";
            array_push($parameters,$toDate);
        }
   if((bool)$status){
            $query.=" and CurrentStatus= ?";
            array_push($parameters,$status);
        }
    else{
            $query.=" order by ApplicationDate DESC";
           
        }
  
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $specializedfirmLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Specialized Firm Application Details', function($excel) use ($specializedfirmLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($specializedfirmLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.specializedfirmdetail')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('specializedfirmLists',$specializedfirmLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.specializedfirmdetail')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
           ->with('statuses',$statuses)
            ->with('specializedfirmLists',$specializedfirmLists);
         
    }
}