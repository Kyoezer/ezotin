<?php

class EvaluationTrack  extends ReportController{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name'));

      
       
        $parameters = array();
      
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
      
        $status = Input::get('Status');

        $query = " Select * from etlevaluation where 1 ";
     
      
    
        if((bool)$status){
            $query.=" and Operation = ?";
            array_push($parameters,$status);
        }
        if((bool)$fromDate){
            $query.=" and OperationTime >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and OperationTime <= ?";
            array_push($parameters,$toDate);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query."  order by OperationTime DESC",$parameters,20,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query."  order by OperationTime DESC$limitOffsetAppend",$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Evaluation Track', function($excel) use ($reportData, $procuringAgency,$fromDate,$toDate) {

                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $procuringAgency,$fromDate,$toDate) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.evaluationtrack')
                            ->with('procuringAgency',$procuringAgency)
                          
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.evaluationtrack')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
          
            
      
            ->with('reportData',$reportData); 
    }
}