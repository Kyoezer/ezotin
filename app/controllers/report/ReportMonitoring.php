<?php

class ReportMonitoring extends ReportController{
    public function getListOfSuspendedFirms(){
        $parameters = array();
        $contractorId = Input::get('ContractorId');
        $dzongkhagId = Input::get('DzongkhagId');
        $classId = Input::get('ClassId');
        $type = Input::get('Type');
        $action = Input::get('Action');

        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):false;

        $classes = ContractorClassificationModel::classification()->get(array('Code','Priority'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));

        $query = "select T1.MonitoringDate,T2.InitialDate,T1.ActionTaken,coalesce(T1.ActionRemarks,T1.Remarks) as Remarks,T1.ActionDate,T1.FromDate,T1.ToDate,T2.CDBNo,T2.NameOfFirm, T3.NameEn as Dzongkhag,case when T4.MaxClassificationPriority = 1000 then 'L' else case when T4.MaxClassificationPriority = '999' then 'M' else case when T4.MaxClassificationPriority = '998' then 'S' else 'R' end end end as Class from crpmonitoringoffice T1 join (crpcontractorfinal T2 join cmndzongkhag T3 on T3.Id = T2.CmnDzongkhagId join viewcontractormaxclassification T4 on T4.CrpContractorFinalId = T2.Id) on T2.Id = T1.CrpContractorFinalId where T1.ActionTaken in (2,1)";

        if((bool)$contractorId){
            $query.=" and T1.CrpContractorFinalId = ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$action){
            $query.=" and T1.ActionTaken = ?";
            array_push($parameters,$action);
        }
        if((bool)$dzongkhagId){
            $query.=" and T2.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$classId){
            $query.=" and T4.MaxClassificationPriority = ?";
            array_push($parameters,$classId);
        }
        if((bool)$fromDate){
            $query.=" and T2.InitialDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T2.InitialDate <= ?";
            array_push($parameters,$toDate);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query." order by T2.CDBNo".$limitOffsetAppend,$parameters);
//        if(Input::has('export')){
//            $export = Input::get('export');
//            if($export == 'excel'){
//                Excel::create('Category Wise Report', function($excel) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//
//                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//                        $sheet->setOrientation('landscape');
//                        $sheet->setFitToPage(1);
//                        $sheet->loadView('reportexcel.categorywisereport')
//                            ->with('dzongkhag',$dzongkhag)
//                            ->with('class',$class)
//                            ->with('category',$category)
//                            ->with('limit',$limit)
//                            ->with('fromDate',$fromDate)
//                            ->with('toDate',$toDate)
//                            ->with('agency',$agency)
//                            ->with('reportData',$reportData);
//
//                    });
//
//                })->export('xlsx');
//            }
//        }
        return View::make('report.monitoringsuspendedfirms')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('dzongkhags',$dzongkhags)
            ->with('classes',$classes)
            ->with('reportData',$reportData);
    }
    public function getListOfPassedFirms(){
        $parameters = array();
        $contractorId = Input::get('ContractorId');
        $dzongkhagId = Input::get('DzongkhagId');
        $classId = Input::get('ClassId');
        $type = Input::get('Type');
        $status = Input::get('Status');

        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):false;

        $classes = ContractorClassificationModel::classification()->get(array('Code','Priority'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));

        $query = "select T1.MonitoringDate,T2.InitialDate,T2.CDBNo,T2.NameOfFirm,T1.MonitoringStatus, T3.NameEn as Dzongkhag,case when T4.MaxClassificationPriority = 1000 then 'L' else case when T4.MaxClassificationPriority = '999' then 'M' else case when T4.MaxClassificationPriority = '998' then 'S' else 'R' end end end as Class from crpmonitoringoffice T1 join (crpcontractorfinal T2 join cmndzongkhag T3 on T3.Id = T2.CmnDzongkhagId join viewcontractormaxclassification T4 on T4.CrpContractorFinalId = T2.Id) on T2.Id = T1.CrpContractorFinalId where 1";

        if((bool)$contractorId){
            $query.=" and T1.CrpContractorFinalId = ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$dzongkhagId){
            $query.=" and T2.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$classId){
            $query.=" and T4.MaxClassificationPriority = ?";
            array_push($parameters,$classId);
        }
        if((bool)$status){
            if((int)$status == 2){
                $query.=" and T1.MonitoringStatus = ?";
                array_push($parameters,0);
            }else{
                $query.=" and T1.MonitoringStatus = ?";
                array_push($parameters,1);
            }
        }else{
            $query.=" and T1.MonitoringStatus = ?";
            array_push($parameters,0);
        }
        if((bool)$fromDate){
            $query.=" and T2.InitialDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T2.InitialDate <= ?";
            array_push($parameters,$toDate);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query." order by T2.CDBNo".$limitOffsetAppend,$parameters);
//        if(Input::has('export')){
//            $export = Input::get('export');
//            if($export == 'excel'){
//                Excel::create('Category Wise Report', function($excel) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//
//                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//                        $sheet->setOrientation('landscape');
//                        $sheet->setFitToPage(1);
//                        $sheet->loadView('reportexcel.categorywisereport')
//                            ->with('dzongkhag',$dzongkhag)
//                            ->with('class',$class)
//                            ->with('category',$category)
//                            ->with('limit',$limit)
//                            ->with('fromDate',$fromDate)
//                            ->with('toDate',$toDate)
//                            ->with('agency',$agency)
//                            ->with('reportData',$reportData);
//
//                    });
//
//                })->export('xlsx');
//            }
//        }
        return View::make('report.monitoringpassedfirms')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('dzongkhags',$dzongkhags)
            ->with('classes',$classes)
            ->with('reportData',$reportData);
    }
    public function getListOfFirms(){
        $parameters = array();
        $contractorId = Input::get('ContractorId');
        $dzongkhagId = Input::get('DzongkhagId');
        $classId = Input::get('ClassId');
        $count = Input::get('Count');
        $type = Input::get('Type');
        $year = Input::get('year');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):false;

        $classes = ContractorClassificationModel::classification()->get(array('Code','Priority'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));

        $query = "select T1.InitialDate,(select GROUP_CONCAT(B.Year SEPARATOR ', ') from crpmonitoringoffice B where B.CrpContractorFinalId = T1.Id) as Years,(select count(*) from crpmonitoringoffice A where A.CrpContractorFinalId = T1.Id) as MonitoredCount,T1.CDBNo,T1.NameOfFirm,T2.NameEn as Dzongkhag, case when T3.MaxClassificationPriority = 1000 then 'L' else case when T3.MaxClassificationPriority = '999' then 'M' else case when T3.MaxClassificationPriority = '998' then 'S' else 'R' end end end as Class from crpcontractorfinal T1 join cmndzongkhag T2 on T2.Id = T1.CmnDzongkhagId join viewcontractormaxclassification T3 on T3.CrpContractorFinalId = T1.Id";
        if((bool)$contractorId){
            $query.=" and T1.Id = ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$dzongkhagId){
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$count){
            if((int)$count == 1){
                $query.=" and (select count(*) from crpmonitoringoffice A where A.CrpContractorFinalId = T1.Id) = 0";
            }else{
                $query.=" and (select count(*) from crpmonitoringoffice A where A.CrpContractorFinalId = T1.Id) > 0";
            }

        }
        if((bool)$classId){
            $query.=" and T3.MaxClassificationPriority = ?";
            array_push($parameters,$classId);
        }
        if((bool)$fromDate){
            $query.=" and T1.InitialDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.InitialDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$year){
            $query.=" and B.Year <= ?";
            array_push($parameters,$year);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query." order by MonitoredCount desc".$limitOffsetAppend,$parameters);
//        if(Input::has('export')){
//            $export = Input::get('export');
//            if($export == 'excel'){
//                Excel::create('Category Wise Report', function($excel) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//
//                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//                        $sheet->setOrientation('landscape');
//                        $sheet->setFitToPage(1);
//                        $sheet->loadView('reportexcel.categorywisereport')
//                            ->with('dzongkhag',$dzongkhag)
//                            ->with('class',$class)
//                            ->with('category',$category)
//                            ->with('limit',$limit)
//                            ->with('fromDate',$fromDate)
//                            ->with('toDate',$toDate)
//                            ->with('agency',$agency)
//                            ->with('reportData',$reportData);
//
//                    });
//
//                })->export('xlsx');
//            }
//        }
        return View::make('report.monitoringlistoffirms')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('dzongkhags',$dzongkhags)
            ->with('classes',$classes)
            ->with('reportData',$reportData);
    }
    public function getListOfSites(){
        $parameters = array();
        $contractorId = Input::get('ContractorId');
        $dzongkhagId = Input::get('DzongkhagId');
        $classId = Input::get('ClassId');
        $status = Input::get('Status');
        $type = Input::get('Type');

        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):false;

        $classes = ContractorClassificationModel::classification()->get(array('Code','Priority'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));

        $query = "select T1.MonitoringDate,T2.InitialDate,T1.WorkId,T1.Type,T1.MonitoringStatus,T2.CDBNo,T2.NameOfFirm, T3.NameEn as Dzongkhag,case when T4.MaxClassificationPriority = 1000 then 'L' else case when T4.MaxClassificationPriority = '999' then 'M' else case when T4.MaxClassificationPriority = '998' then 'S' else 'R' end end end as Class from crpmonitoringsite T1 join (crpcontractorfinal T2 join cmndzongkhag T3 on T3.Id = T2.CmnDzongkhagId join viewcontractormaxclassification T4 on T4.CrpContractorFinalId = T2.Id) on T2.Id = T1.CrpContractorFinalId where 1";
        if((bool)$contractorId){
            $query.=" and T1.Id = ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$dzongkhagId){
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$classId){
            $query.=" and T3.MaxClassificationPriority = ?";
            array_push($parameters,$classId);
        }
        if((bool)$fromDate){
            $query.=" and T1.MonitoringDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.MonitoringDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$status){
            if((int)$status == 2){
                $query.=" and T1.MonitoringStatus = ?";
                array_push($parameters,0);
            }else{
                $query.=" and T1.MonitoringStatus = ?";
                array_push($parameters,1);
            }
        }else{
            $query.=" and T1.MonitoringStatus = ?";
            array_push($parameters,0);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query." order by T2.CDBNo".$limitOffsetAppend,$parameters);
        foreach($reportData as $data):
            $type = $data->Type;
            $workId = $data->WorkId;
            if(($type == 1) || ($type == 2)){
                $table = "crpbiddingform";
            }else{
                $table = "etltender";
            }
            $nameOfWork = DB::table($table)->where('Id',$workId)->pluck('NameOfWork');
            $data->NameOfWork = $nameOfWork;
        endforeach;
//        if(Input::has('export')){
//            $export = Input::get('export');
//            if($export == 'excel'){
//                Excel::create('Category Wise Report', function($excel) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//
//                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $limit,$dzongkhag,$class,$category,$fromDate,$toDate,$agency) {
//                        $sheet->setOrientation('landscape');
//                        $sheet->setFitToPage(1);
//                        $sheet->loadView('reportexcel.categorywisereport')
//                            ->with('dzongkhag',$dzongkhag)
//                            ->with('class',$class)
//                            ->with('category',$category)
//                            ->with('limit',$limit)
//                            ->with('fromDate',$fromDate)
//                            ->with('toDate',$toDate)
//                            ->with('agency',$agency)
//                            ->with('reportData',$reportData);
//
//                    });
//
//                })->export('xlsx');
//            }
//        }
        return View::make('report.monitoringlistofsites')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('dzongkhags',$dzongkhags)
            ->with('classes',$classes)
            ->with('reportData',$reportData);
    }
}