<?php

class ContractorsAdvRecords extends ReportController{
    public function getIndex(){
        die('csacas');
        $limit = Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $parameters = array();
        $query = "select T2.CDBNo, T2.NameOfFirm,T1.Date, group_concat(T1.Remarks SEPARATOR '<br/> ') as AdverseRecords from crpcontractorcommentsadverserecord T1 join crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId where T1.Type = 2 and T1.Remarks <> 'nil'";

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

        $query.=" group by T2.Id";

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        return View::make('report.contractoradvrecords')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('reportData',$reportData);
    }
}