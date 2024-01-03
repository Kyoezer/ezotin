<?php

class ReportListOfExpiredEngineer extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $sectors = CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
        $trades = CmnListItemModel::trade()->get(array('Id','Name'));
        $query = "select * from expiredengineer Where 1";

        $parameters = array();
        $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'';
        $country = Input::has('Country')?Input::get('Country'):'';
        $dzongkhag = Input::has('Dzongkhag')?Input::get('Dzongkhag'):'';
        $sector = Input::has('SectorType')?Input::get('SectorType'):'';
        $trade = Input::has('Trade')?Input::get('Trade'):'';
        $limit = Input::has('Limit')?Input::get('Limit'):'';
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $status = Input::has('Status')?Input::get('Status'):'';
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
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
        if((bool)$trade){
            $query.=" and Trade = ?";
            array_push($parameters,$trade);
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
         $query.=" order by CDBNo";

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $engineersList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Expired Engineers', function($excel) use ($engineersList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($engineersList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.expiredengineer')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('cdbNo',$cdbNo)
                            ->with('sector',$sector)
                            ->with('trade',$trade)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('status',$status)
                            ->with('engineersList',$engineersList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.expiredengineer')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('engineersList',$engineersList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('trades',$trades)
                        ->with('sectors',$sectors);
    }

    public function listofconsultantengineer(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $consultants = ConsultantFinalModel::ConsultantHardListAll()->get(array('Id','NameOfFirm'));
        
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $sectors = CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
        $trades = CmnListItemModel::trade()->get(array('Id','Name'));
        $query = "select b.NameOfFirm,b.CDBNo,a.Name,a.CIDNo,if(a.Sex='M','Male','Female')gender,c.NameEn
        from crpconsultanthumanresourcefinal a 
        left join crpconsultantfinal b on a.CrpConsultantFinalId=b.Id 
        left join cmndzongkhag c on b.CmnDzongkhagId=c.Id
        where a.CmnDesignationId=(select Id from cmnlistitem where Name = 'Engineer')";

        $parameters = array();
        $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'';
        $dzongkhag = Input::has('Dzongkhag')?Input::get('Dzongkhag'):'';
        $consultant = Input::has('consultant')?Input::get('consultant'):'';
        
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
        }
        if((bool)$dzongkhag){
            $query.=" and c.NameEn = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$consultant){
            $query.=" and b.NameOfFirm = ?";
            array_push($parameters,$consultant);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $engineersList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Engineers', function($excel) use ($engineersList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($engineersList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofconsultantengineers')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('cdbNo',$cdbNo)
                            ->with('engineersList',$engineersList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofconsultantengineers')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('engineersList',$engineersList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('consultants',$consultants)
                        ->with('statuses',$statuses)
                        ->with('trades',$trades)
                        ->with('sectors',$sectors);
    }
}