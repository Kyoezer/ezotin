<?php

class ReportListOfExpiredArchitect extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $sectors = CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
        $query = "select * from expiredarchitect Where 1";

        $parameters = array();
        $arNo = Input::get('ARNo');
        $country = Input::get('Country');
        $dzongkhag = Input::get('Dzongkhag');
        $sector = Input::get('SectorType');
        $limit = Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $status = Input::get('Status');
        if((bool)$arNo){
            $query.=" and ARNo like '%$arNo%'";
        }
        if((bool)$country){
            $query.=" and Country = ?";
            array_push($parameters,$country);
        }
        if((bool)$dzongkhag){
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$sector){
            $query.=" and Sector = ?";
            array_push($parameters,$sector);
        }
        if((bool)$fromDate){
            $query.=" and ApprovedDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and ApprovedDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$status){
            $query.=" and Status = ?";
            array_push($parameters,$status);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/


        if(Input::has('export')){
            $architectsList = DB::select($query." order by ARNo",$parameters);
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Expired Architects', function($excel) use ($architectsList, $country,$dzongkhag,$arNo,$sector,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($architectsList, $country,$dzongkhag,$arNo,$sector,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.expiredarchitect')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('arNo',$arNo)
                            ->with('sector',$sector)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('status',$status)
                            ->with('architectsList',$architectsList);

                    });

                })->export('xlsx');
            }
        }else{
            $architectsList = DB::select($query." order by ARNo".$limitOffsetAppend,$parameters);
        }
        return View::make('report.expiredarchitect')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('architectsList',$architectsList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('sectors',$sectors);
    }
}