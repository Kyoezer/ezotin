<?php

class ReportContractorRegistrationDetail extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
     
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $cdbNo = Input::get('CDBNo');
        $refNo = Input::get('ReferenceNo');


        $query = "SELECT ReferenceNo, NameOfFirm, CDBNo,RemarksByRejector, ApplicationDate, VerifiedBy, AprroverBy, RejectedBy, PaymentApprover, ServiceType, CurrentStatus, RemarksByVerifier, RemarksByApprover, RemarksByPaymentApprover,   ( CASE WHEN CurrentStatus = 'Approved' OR CurrentStatus = 'Rejected' THEN CurrentlyPickedBy = NULL ELSE CurrentlyPickedBy END) AS `CurrentlyPickedBy` , RejectedDate, PaymentApprovedDate, RegistrationPaymentApprovedDate, RegistrationApprovedDate, RegistrationVerifiedDate FROM ContractorApplicationDetail  WHERE 1";
         $status = Input::get('CurrentStatus');
        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
        }
       
        $parameters = array();
        if((bool)$refNo){
            $query.=" and ReferenceNo like '%$refNo%'";
        }
 
        if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and ApplicationDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and ApplicationDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
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
        $contractorLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Contractor Application Details', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.contractorregistrationdetail')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.contractorregistrationdetail')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
               ->with('statuses',$statuses)
            ->with('contractorLists',$contractorLists);
         
    }
}