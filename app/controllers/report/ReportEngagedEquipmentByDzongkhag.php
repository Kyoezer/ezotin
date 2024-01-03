<?php

class ReportEngagedEquipmentByDzongkhag extends ReportController{
    public function getIndex(){
        $date = Input::has('Date')?date_format(date_create(Input::get('Date')),'Y-m-d'):date('Y-m-d');
        $equipmentList = DB::table('cmnequipment')->where('IsRegistered',1)->get(array('Id','Name'));
        $dzongkhags = DB::table('cmndzongkhag')->orderBy("NameEn")->get(array('Id','NameEn'));
        $condition=" 1=1";
        $condition2=" 2=2";
        if((bool)$date){
            $condition=" T3.ActualStartDate <= '$date'";
        }
        foreach($equipmentList as $equipment):
            foreach($dzongkhags as $dzongkhag):
                $detail = DB::table('etlcontractorequipment as T1')
                                ->join('etltenderbiddercontractor as T3','T3.Id','=','T1.EtlTenderBidderContractorId')
                                ->join('etltender as T2','T2.Id','=','T3.EtlTenderId')
                                ->where('T1.CmnEquipmentId',$equipment->Id)
                                ->whereNotNull('T3.ActualStartDate')
                                ->where("T2.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                                ->where('T2.CmnDzongkhagId',$dzongkhag->Id)
                                ->whereRaw($condition)
                                ->whereRaw($condition2)
                                ->select(DB::raw("distinct T1.Id"))
                                ->count();

                $equipmentDetail[$equipment->Id][$dzongkhag->Id] =$detail;
            endforeach;
        endforeach;
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Engaged Equipments by Dzongkhag', function ($excel) use ($equipmentDetail,$dzongkhags, $equipmentList,$fromDate, $toDate) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($equipmentDetail,$dzongkhags,$equipmentList, $fromDate, $toDate) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.engagedequipmentbydzongkhag')
                            ->with('dzongkhags',$dzongkhags)
                            ->with('equipmentDetail', $equipmentDetail)
                            ->with('equipmentList', $equipmentList)
                            ->with('date', $date);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.engagedequipmentbydzongkhag')
            ->with('dzongkhags',$dzongkhags)
            ->with('equipmentDetail',$equipmentDetail)
            ->with('equipmentList',$equipmentList);
    }
}