<?php

class ReportEngagedHrEtool extends ReportController{
    public function getIndex(){


        $cdbNo = Input::get('CDBNo');
       $cid = Input::get('cidNo');


      
        $query = "select DISTINCT cidNo, CDBNo, WorkId, procuringAgency, Designation, hrName from etlengagehumanresource where  cidNo is not null and  1";
        $parameters = array();
       
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
        }
       
     if((bool)$cid ){
            $query.=" and cidNo like '%$cid%'";
        }
      
    

        $query.=" order by CDBNo";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,16,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select("$query$limitOffsetAppend",$parameters);
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Engaged Hr', function ($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofengagedhretool')
                           
                            ->with('reportData', $reportData);
                           

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofengagedhretool')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
     
     
            ->with('reportData',$reportData);
    }
}

