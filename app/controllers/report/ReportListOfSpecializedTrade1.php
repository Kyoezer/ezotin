<?php

class ReportListOfSpecializedTrade extends ReportController{
    public function getIndex(){
        $dzongkhags = DB::table('viewlistofspecializedtrade')->get(array(DB::raw('distinct(Dzongkhag)')));
        $statuses = DB::table('viewlistofspecializedtrade')->get(array(DB::raw('distinct(Status)')));
        $query = "select * from viewlistofspecializedtrade Where 1";
        $parameters = array();
        $spNo = Input::get('SPNo');
        $dzongkhag = Input::get('Dzongkhag');
        $limit = Input::get('Limit');
        $status = Input::get('Status');
        if((bool)$spNo){
            $query.=" and SPNo = ?";
            array_push($parameters,$spNo);
        }
        if((bool)$dzongkhag){
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$status){
            $query.=" and Status = ?";
            array_push($parameters,$status);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $specializedTradeList = DB::select($query.$limitOffsetAppend,$parameters);
//        echo "<pre>"; dd($specilizedTradeList);
//        if(Input::has('export')){
//            $export = Input::get('export');
//            if($export == 'excel'){
//                Excel::create('List of Contractors', function($excel) use ($contractorsList,$toDate,$fromDate,$cdbNo,$limit,$dzongkhag,$country,$category,$classification,$status) {
//                    $excel->sheet('Sheet 1', function($sheet) use ($contractorsList,$cdbNo,$toDate,$fromDate,$limit,$dzongkhag,$country,$category,$classification,$status) {
//                        $sheet->setOrientation('landscape');
//                        $sheet->setFitToPage(1);
//                        $sheet->setPaperSize(1);
//                        $sheet->loadView('reportexcel.listofcontractors')
//                            ->with('type',1)
//                            ->with('cdbNo',$cdbNo)
//                            ->with('limit',$limit)
//                            ->with('fromDate',$fromDate)
//                            ->with('toDate',$toDate)
//                            ->with('dzongkhag',$dzongkhag)
//                            ->with('country',$country)
//                            ->with('category',$category)
//                            ->with('classification',$classification)
//                            ->with('status',$status)
//                            ->with('contractorsList',$contractorsList);
//
//                    });
//
//                })->export('xlsx');
//            }
//        }
        return View::make('report.listofspecializedtrade')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('specializedTradeList',$specializedTradeList)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses);
    }
}