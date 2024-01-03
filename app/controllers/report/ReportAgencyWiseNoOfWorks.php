<?php

class ReportAgencyWiseNoOfWorks extends ReportController{
    public function getIndex(){
        $order = Input::has('Order')?Input::get('Order'):'ASC';
        $limit = Input::get('Limit');
        $query = "select T1.Name as Agency,count(T2.Id) as NoOfWorks from cmnprocuringagency T1 join etltender T2 on T2.CmnProcuringAgencyId = T1.Id and T2.TenderSource = 1 join cmnlistitem T3 on T2.CmnWorkExecutionStatusId = T3.Id and T3.ReferenceNo in (3001,3003) group by T1.Id order by NoOfWorks";
        $parameters = array();
        if((bool)$order){
            $query.=" $order, Agency";
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Agency Wise Number of Works', function ($excel) use ($reportData,$order, $limit) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData,$order, $limit) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.agencywisenoofworks')
                            ->with('order', $order)
                            ->with('limit',$limit)
                            ->with('reportData', $reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.agencywisenoofworks')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('reportData',$reportData);
    }
}