<?php

class ReportWorkId extends ReportController{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name'));
        $parameters = array();
        $limit = Input::get('Limit');
        $procuringAgency = Input::get('Agency');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $tenderSource = Input::has('TenderSource')?Input::get('TenderSource'):0;

        $query = "select T1.NameOfWork, case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId, T1.ContractPeriod, T3.Code as Class, T4.Code as Category from etltender T1 left join (cmnprocuringagency T2 join cmnprocuringagency D on D.Id = T2.CmnProcuringAgencyId) on T1.CmnProcuringAgencyId = T2.Id join cmncontractorclassification T3 on T3.Id = T1.CmnContractorClassificationId join cmncontractorworkcategory T4 on T4.Id = T1.CmnContractorCategoryId where 1";
        if((bool)$procuringAgency){
            $query.=" and (T2.Name = ? or D.Name = ?)";
            array_push($parameters,$procuringAgency);
            array_push($parameters,$procuringAgency);
        }
        if((bool)$fromDate){
            $query.=" and DATE_FORMAT(T1.UploadedDate,'%Y-%m-%d') >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and DATE_FORMAT(T1.UploadedDate,'%Y-%m-%d') <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$tenderSource){
            $query.=" and T1.TenderSource = ?";
            array_push($parameters,$tenderSource);
        }
        $append = "";
        if((bool)$limit){
            if($limit != 'All'){
                $append.=" limit $limit";
            }
        }else{
            $append.=" limit 20";
        }
        $reportData = DB::select($query." order by T1.DateOfSaleOfTender DESC, T2.Name$append",$parameters);

        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Work Id (Report)', function($excel) use ($reportData, $procuringAgency,$fromDate,$toDate,$tenderSource) {

                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $procuringAgency,$fromDate,$toDate,$tenderSource) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.reportworkid')
                            ->with('procuringAgency',$procuringAgency)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('tenderSource',$tenderSource)
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.reportworkid')
            ->with('procuringAgencies',$procuringAgencies)
            ->with('reportData',$reportData);
    }
}