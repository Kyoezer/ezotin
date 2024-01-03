<?php

class ReportConsultantDetail extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
     
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        
        $limit=Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $cdbNo = Input::get('CDBNo');
        $refNo = Input::get('ReferenceNo');
        $query = "SELECT ReferenceNo, NameOfFirm, CDBNo,RemarksByRejector, ApplicationDate, VerifiedBy, AprroverBy, RejectedBy, PaymentApprover, ServiceType, CurrentStatus, RemarksByVerifier, RemarksByApprover, RemarksByPaymentApprover, ( CASE WHEN CurrentStatus = 'Approved' OR CurrentStatus = 'Rejected' THEN CurrentlyPickedBy = NULL ELSE CurrentlyPickedBy END) AS `CurrentlyPickedBy`, RejectedDate, PaymentApprovedDate, RegistrationPaymentApprovedDate, RegistrationApprovedDate, RegistrationVerifiedDate FROM consultantapplicationdetail where 1";
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
            $query.=" and ApplicationDate >= ?";
            array_push($parameters,$fromDate);
        }
   if((bool)$status){
            $query.=" and CurrentStatus= ?";
            array_push($parameters,$status);
        }
        if((bool)$toDate){
            $query.=" and ApplicationDate <= ?";
            array_push($parameters,$toDate);
        }
        else
        {
            $query.=" order by ApplicationDate DESC";
           
        }
  
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $consultantLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Consultant Application Details', function($excel) use ($consultantLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($consultantLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.consultantdetail')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('consultantLists',$consultantLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.consultantdetail')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
 ->with('statuses',$statuses)
            ->with('consultantLists',$consultantLists);
         
    }
}