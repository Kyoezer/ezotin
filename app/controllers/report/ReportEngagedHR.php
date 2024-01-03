<?php

class ReportEngagedHR extends ReportController{
//    public function getIndex(){
//        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
//        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
//        $query = "select T1.Name as Equipment, T5.RegistrationNo, T5.Quantity from cmnequipment T1 join (etltender T2 join cmnlistitem T3 on T2.CmnWorkExecutionStatusId = T3.Id and T3.ReferenceNo = 3001 join etltenderbiddercontractor T4 on T4.EtlTenderId = T2.Id and T4.ActualStartDate is not null join etlcontractorequipment T5 on T5.EtlTenderBidderContractorId = T4.Id) on T1.Id = T5.CmnEquipmentId where 1";
//        $parameters = array();
//        if((bool)$fromDate){
//            $query.=" and T4.ActualStartDate >= ?";
//            array_push($parameters,$fromDate);
//        }
//        if((bool)$toDate){
//            $query.=" and T4.ActualStartDate <= ?";
//            array_push($parameters,$toDate);
//        }
//        $reportData = DB::select("$query order by Equipment",$parameters);
//        return View::make('report.listofengagedequipments')
//            ->with('reportData',$reportData);
//    }
}