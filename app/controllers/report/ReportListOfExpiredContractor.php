<?php

class ReportListOfExpiredContractor  extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $contractorClassifications = DB::table('cmncontractorclassification')->orderBy('Code')->get(array("Code","Name"));
        $query = " SELECT CDBNo, NameOfFirm, Address, Status, ExpiryDate, ApplicationDate,Country, Dzongkhag, TelephoneNo, MobileNo, Classification1, Classification2, Classification3, Classification4, ownerName, gender FROM expiredcontractor where 1";

        $parameters = array();
        $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'';
        $country = Input::has('Country')?Input::get('Country'):'';
        $dzongkhag = Input::has('Dzongkhag')?Input::get('Dzongkhag'):'';
        $classification = Input::get('Classification');
        $limit = Input::has('Limit')?Input::get('Limit'):'';
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
     
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
  
        if((bool)$classification){
            $query.=" and (Classification1 LIKE '%$classification%' or Classification2 LIKE '%$classification%' or Classification3 LIKE '%$classification%' or Classification4 LIKE '%$classification%')";
        }
        if((bool)$fromDate){
            $query.=" and ExpiryDate>= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and ExpiryDate<= ?";
            array_push($parameters,$toDate);
        }
        else{
            $query.=" order by ExpiryDate ASC";
            
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Expired Contractors', function($excel) use ($contractorList, $country,$dzongkhag,$cdbNo,$limit,$fromDate,$toDate,$start) {

                    $excel->sheet('Sheet 1', function($sheet) use ($contractorList, $country,$dzongkhag,$cdbNo,$limit,$fromDate,$toDate,$start) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.expiredcontractor')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('cdbNo',$cdbNo)
                        ->with('start',$start)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                           
                            ->with('contractorList',$contractorList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.expiredcontractor')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorList',$contractorList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('contractorClassifications',$contractorClassifications);
                    
    }

}