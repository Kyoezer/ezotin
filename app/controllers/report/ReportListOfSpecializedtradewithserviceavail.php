<?php

class ReportListOfSpecializedtradewithserviceavail extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $specializedtradeListAll=SpecializedTradeFinalModel::SpecializedTradeHardListAll()->get(array('Id'));
       
    
        $surveyLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');

        $query = "SELECT c.SPNo, c.Name, c.ApplicationDate, c.RegistrationApprovedDate, a.Name as Status, d.Name as ServiceType FROM crpspecializedtrade c LEFT JOIN crpspecializedtradeappliedservice b ON b.CrpSpecializedTradeId = c.CrpSpecializedTradeId LEFT JOIN cmnlistitem a ON a.Id = c.CmnApplicationRegistrationStatusId LEFT JOIN crpservice d ON d.Id = b.CmnServiceTypeId WHERE CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ";

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
        $specializedtradeLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Specialized Trade with service avail', function($excel) use ($specializedtradeLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($specializedtradeLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listOfSpecializedtradewithserviceavail')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('specializedtradeLists',$specializedtradeLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listOfSpecializedtradewithserviceavail')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('specializedtradeListAll',$specializedtradeListAll)
          
            ->with('specializedtradeLists',$specializedtradeLists);
         
    }
}