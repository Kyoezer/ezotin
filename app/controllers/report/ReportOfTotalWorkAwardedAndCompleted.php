<?php

class ReportOfTotalWorkAwardedAndCompleted extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id'));
       
    
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $wk = Input::get('WorkStatus');
        $query = "SELECT * from ViewTotalWorkAwardedAndCompleted where 1";

        $parameters = array();
       
        if((bool)$fromDate){
            $query.=" and WorkStartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and WorkStartDate <= ?";
            array_push($parameters,$toDate);
        }

        if((bool)$wk){
            $query.=" and WorkStatus like '%$wk%'";
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
                Excel::create('List of work awarded and completed', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofworkawardedandcompleted')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofworkawardedandcompleted')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
          
            ->with('contractorLists',$contractorLists);
         
    }
}