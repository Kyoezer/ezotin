<?php

class BidRatio extends ReportController{
    public function getIndex(){
        $category = Input::get('Category');
        $classification = Input::get('Classification');
        $year = Input::get('Year');
        $parameters = array();
        $query = "select count(T1.Id) as NoOfWorksAwarded from etltender T1 where coalesce(T1.CmnWorkExecutionStatusId,0) in (?,?)";
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        if((bool)$year){
            $query.=" and year(T1.DateOfSaleOfTender) = ?";
            array_push($parameters,$year);
        }
        return View::make('report.bidratio');
    }
}