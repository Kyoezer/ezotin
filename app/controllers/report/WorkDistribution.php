<?php

class WorkDistribution extends ReportController{
    public function getIndex(){
        $parameters = array();
        $dzongkhagsAgencies = DB::select("select Code,Id,Name from cmnprocuringagency");
        $agency = Input::get('Agency');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $type = Input::get('Type');
        $limit = Input::get('Limit');

        $query = "select T1.ProcuringAgency as Code, count(T1.WorkStartDate) as NoOfWorks,sum(coalesce(T1.FinalAmount,T1.EvaluatedAmount)) as ContractAmount from viewcontractorstrackrecords T1 where T1.ReferenceNo in (3001,3003)";
        if((bool)$agency){
            $query.=" and T1.ProcuringAgencyId = ?";
            array_push($parameters,$agency);
        }
        if((bool)$fromDate){
            $query.=" and T1.WorkStartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate) {
            $query .= " and T1.WorkCompletionDate  <= ?";
            array_push($parameters, $toDate);
        }
        if((bool)$type) {
            $query .= " and T1.Type = ?";
            array_push($parameters, (int)$type);
        }
        $query.=" group by ProcuringAgencyCode";
        $reportData = DB::select($query,$parameters);
        return View::make('report.workdistribution')
                    ->with('dzongkhagsAgencies',$dzongkhagsAgencies)
                    ->with('reportData',$reportData);
    }
}