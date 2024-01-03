<?php

class SpecializedfirmComments extends ReportController{
    public function getIndex(){
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $limit = Input::get('Limit');
        $parameters = array();
        $query = "select T2.SPNo, T2.NameOfFirm, T1.Date, group_concat(T1.Remarks SEPARATOR '<br/> ') as Comments from crpspecializedtradecommentsadverserecord T1 join crpspecializedfirmfinal T2 on T2.Id = T1.CrpSpecializedTradeFinalId where T1.Type = 1";

        if((bool)$fromDate){
            $query.=" and T1.Date >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $query.=" and T1.Date <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }
        $append = "";
        if((bool)$limit){
            if($limit != 'All'){
                $append.=" limit $limit";
            }
        }else{
            $append.=" limit 20";
        }
        $query.=" group by T2.Id order by T2.SPNo ";

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        return View::make('report.specializedfirmcomments')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('reportData',$reportData);
    }
}