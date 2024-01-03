<?php

class Engineers extends ReportController{
    public function getIndex(){
        $dzongkhags = DB::table('viewlistofcontractors')->get(array(DB::raw('distinct(Dzongkhag)')));
        $limit = Input::get('Limit');
        $cdbNo = Input::get('CDBNo');
        $dzongkhag = Input::get('Dzongkhag');
        $query = "select distinct T1.Id, concat(T1.CDBNo, ', ', T1.NameOfFIrm,', ',coalesce(T2.Name,'')) as Contractor, T1.Classification1, T1.Classification2, T1.Classification3, T1.Classification4 from viewlistofcontractors T1 left join crpcontractorhumanresourcefinal T2 on T2.CrpContractorFinalId = T1.Id and T2.ShowInCertificate = 1 where T1.Status = 'Approved'";

        $parameters = array();

        if((bool)$dzongkhag){
            $query.=" and T1.Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$cdbNo){
            $query.=" and T1.CDBNo = ?";
            array_push($parameters,$cdbNo);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorsList = DB::select($query.$limitOffsetAppend,$parameters);
        foreach($contractorsList as $contractor){
            $civilDegree[$contractor->Id] = DB::select("select count(T1.Id) as CivilDegree from crpcontractorhumanresourcefinal T1 join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 where T1.CrpContractorFinalId = ?",array($contractor->Id));
            $civilDiploma[$contractor->Id] = DB::select("select count(T1.Id) as CivilDiploma from crpcontractorhumanresourcefinal T1 join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 where T1.CrpContractorFinalId = ?",array($contractor->Id));
            $electricalDegree[$contractor->Id] = DB::select("select count(T1.Id) as ElectricalDegree from crpcontractorhumanresourcefinal T1 join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 where T1.CrpContractorFinalId = ?",array($contractor->Id));
            $electricalDiploma[$contractor->Id] = DB::select("select count(T1.Id) as ElectricalDiploma from crpcontractorhumanresourcefinal T1 join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 where T1.CrpContractorFinalId = ?",array($contractor->Id));
        }
//        echo "<pre>";
//        dd($civilDegree["95a5670e-fd71-11e4-b6ac-c81f66edb959"]);
//        if(Input::has('export')){
//            $export = Input::get('export');
//            if($export == 'excel'){
//                Excel::create('List of Engineers', function($excel) use ($engineersList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
//
//                    $excel->sheet('Sheet 1', function($sheet) use ($engineersList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
//                        $sheet->setOrientation('landscape');
//                        $sheet->setFitToPage(1);
//                        $sheet->loadView('reportexcel.listofengineers')
//                            ->with('dzongkhag',$dzongkhag)
//                            ->with('country',$country)
//                            ->with('cdbNo',$cdbNo)
//                            ->with('sector',$sector)
//                            ->with('trade',$trade)
//                            ->with('limit',$limit)
//                            ->with('fromDate',$fromDate)
//                            ->with('toDate',$toDate)
//                            ->with('status',$status)
//                            ->with('engineersList',$engineersList);
//
//                    });
//
//                })->export('xlsx');
//            }
//        }
        return View::make('report.engineers')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('contractorsList',$contractorsList)
                        ->with('civilDegree',$civilDegree)
                        ->with('civilDiploma',$civilDiploma)
                        ->with('electricalDegree',$electricalDegree)
                        ->with('electricalDiploma',$electricalDiploma);
    }
}