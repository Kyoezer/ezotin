<?php

class ReportNoOfEmployees extends ReportController{
    public function getIndex(){
        $gender = Input::get('Gender');
        $dzongkhag = Input::get('Dzongkhag');
        $country = Input::get('Country');
        $limit = Input::get('Limit');
        $type = Input::has('Type')?Input::get('Type'):1;
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $countries = CountryModel::country()->get(array('Name'));

        $query = "select T1.Name, T2.NameOfFirm as FirmName,'Contractor' as Type, T1.JoiningDate, A.NameEn as Dzongkhag, T1.CIDNo, T1.Sex, T3.Name as Country, T4.Name as Qualification, T5.Name as Designation, T6.Name as Trade from crpcontractorhumanresourcefinal T1 join crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId left join cmndzongkhag A on A.Id = T2.CmnDzongkhagId left join cmncountry T3 on T3.Id = T1.CmnCountryId left join cmnlistitem T4 on T4.Id = T1.CmnQualificationId left join cmnlistitem T5 on T5.Id = T1.CmnDesignationId left join cmnlistitem T6 on T6.Id = T1.CmnTradeId where 1";
        $query2 = "select T1.Name, T2.NameOfFirm as FirmName,'Consultant' as Type, T1.JoiningDate, A.NameEn as Dzongkhag, T1.CIDNo, T1.Sex, T3.Name as Country, T4.Name as Qualification, T5.Name as Designation, T6.Name as Trade from crpconsultanthumanresourcefinal T1 join crpconsultantfinal T2 on T2.Id = T1.CrpConsultantFinalId left join cmndzongkhag A on A.Id = T2.CmnDzongkhagId left join cmncountry T3 on T3.Id = T1.CmnCountryId left join cmnlistitem T4 on T4.Id = T1.CmnQualificationId left join cmnlistitem T5 on T5.Id = T1.CmnDesignationId left join cmnlistitem T6 on T6.Id = T1.CmnTradeId where 1";

        $parameters = array();

        if((bool)$gender){
            $query.=" and T1.Sex = ?";
            $query2.=" and T1.Sex = ?";
            array_push($parameters,$gender);
        }else{
            $gender = '';
        }
        if((bool)$dzongkhag){
            $query.=" and A.NameEn = ?";
            $query2.=" and A.NameEn = ?";
            array_push($parameters,$dzongkhag);
        }else{
            $dzongkhag = '';
        }
        if((bool)$country){
            $query.=" and T3.Name = ?";
            $query2.=" and T3.Name = ?";
            array_push($parameters,$country);
        }else{
            $country = '';
        }
        if((bool)$fromDate){
            $query.=" and T1.JoiningDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }else{
            $fromDate = '';
        }
        if((bool)$toDate){
            $query.=" and T1.JoiningDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }else{
            $toDate = '';
        }
        $append = '';
        if((bool)$limit){
            if($limit != 'All'){
                $append.=" limit $limit";
            }
        }else{
            $append.=" limit 20";
        }
        $reportData = DB::select($query." order by T2.NameOfFirm, T1.Name $append",$parameters);
        $reportData2 = DB::select($query2." order by T2.NameOfFirm, T1.Name $append",$parameters);
        if($type == 1){
            $type = "All";
            $reportDataFinal = array_merge($reportData,$reportData2);
        }
        if($type == 2){
            $type = "Contractor";
            $reportDataFinal = $reportData;
        }
        if($type == 3){
            $type = "Consultant";
            $reportDataFinal = $reportData2;
        }
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('No of Employees', function ($excel) use ($reportDataFinal, $dzongkhag, $gender, $type,$limit) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportDataFinal, $dzongkhag, $gender, $type,$limit) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.reportnoofemployees')
                            ->with('dzongkhag', $dzongkhag)
                            ->with('gender', $gender)
                            ->with('type', $type)
                            ->with('limit', $limit)
                            ->with('reportData', $reportDataFinal);
                    });
                })->export('xlsx');
            }
        }
        return View::make('report.reportnoofemployees')
            ->with('dzongkhags',$dzongkhags)
            ->with('countries',$countries)
            ->with('reportData',$reportDataFinal);
    }
}