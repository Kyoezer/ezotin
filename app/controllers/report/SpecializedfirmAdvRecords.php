<?php

class SpecializedfirmAdvRecords extends ReportController{
    public function getIndex(){
   
        $limit = Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $parameters = array();
        $query = "select T2.SPNo, T2.NameOfFirm,T1.Date, group_concat(T1.Remarks SEPARATOR '<br/> ') as AdverseRecords from crpspecializedtradecommentsadverserecord T1 join crpspecializedfirmfinal T2 on T2.Id = T1.CrpSpecializedTradeFinalId where T1.Type = 2 and T1.Remarks <> 'nil'";

        if((bool)$fromDate){
            $query.=" and T1.Date >= ?";
            array_push($parameters,date_format(date_create($fromDate),'d-m-7'));
        }
        if((bool)$toDate){
            $query.=" and T1.Date <= ?";
            array_push($parameters,date_format(date_create($toDate),'d-m-7'));
        }
        $append = "";
        if((bool)$limit){
            if($limit != 'All'){
                $append.=" limit $limit";
            }
        }else{
            $append.=" limit 20";
        }

        $query.=" group by T2.SPNo";

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        return View::make('report.specializedfirmadvrecords')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('reportData',$reportData);
    }
}