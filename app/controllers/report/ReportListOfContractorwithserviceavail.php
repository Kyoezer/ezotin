<?php

class ReportListOfContractorwithserviceavail extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id'));
       
    
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');

        $query = "SELECT c.CDBNo, c.NameOfFirm, c.ApplicationDate, c.RegistrationApprovedDate, a.Name as Status, d.Name as ServiceType FROM crpcontractor c LEFT JOIN crpcontractorappliedservice b ON b.CrpContractorId = c.CrpContractorId LEFT JOIN cmnlistitem a ON a.Id = c.CmnApplicationRegistrationStatusId LEFT JOIN crpservice d ON d.Id = b.CmnServiceTypeId WHERE CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ";

        $parameters = array();
       
        if((bool)$fromDate){
            $query.=" and ApplicationDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and ApplicationDate <= ?";
            array_push($parameters,$toDate);
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
                Excel::create('List of Contractor with service avail', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listOfContractorwithserviceavail')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listOfContractorwithserviceavail')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
          
            ->with('contractorLists',$contractorLists);
         
    }
}