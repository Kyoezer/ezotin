<?php

class ReportEngagedEquipments extends ReportController{
    public function getIndex(){
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $query = "select distinct T5.Id,T1.Name as Equipment, T5.RegistrationNo, T5.Quantity from cmnequipment T1 join (etltender T2 join cmnlistitem T3 on T2.CmnWorkExecutionStatusId = T3.Id and T3.ReferenceNo = 3001 join etltenderbiddercontractor T4 on T4.EtlTenderId = T2.Id and T4.ActualStartDate is not null join etlcontractorequipment T5 on T5.EtlTenderBidderContractorId = T4.Id) on T1.Id = T5.CmnEquipmentId and T2.TenderSource = 1 join sysuser X on X.CmnProcuringAgencyId = T2.CmnProcuringAgencyId where 1";
        $parameters = array();
        if((bool)$fromDate){
            $query.=" and T4.ActualStartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T4.ActualStartDate <= ?";
            array_push($parameters,$toDate);
        }
        $query.=" order by Equipment";
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
                Excel::create('Engaged Equipments', function ($excel) use ($reportData, $fromDate, $toDate) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData, $fromDate, $toDate) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofengagedequipments')
                            ->with('fromDate', $fromDate)
                            ->with('reportData', $reportData)
                            ->with('toDate', $toDate);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofengagedequipments')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('reportData',$reportData);
    }
}