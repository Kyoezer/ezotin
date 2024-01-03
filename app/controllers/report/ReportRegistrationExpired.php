<?php

class ReportRegistrationExpired extends ReportController{
    public function getIndex(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $query = "select CDBNo, ExpiryDate, NameOfFirm,Address,Dzongkhag,TelephoneNo,Classification1,Classification2,Classification3,Classification4,Country,MobileNo from viewlistofcontractors Where 1";

        $parameters = array();
        $dzongkhag = Input::get('Dzongkhag');
        $limit = Input::get('Limit');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        if((bool)$dzongkhag){
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$fromDate){
            $query.=" and ExpiryDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$limit){
            if($limit != 'All'){
                $query.=" limit $limit";
            }
        }else{
            $query.=" limit 20";
        }
        $contractorsList = DB::select($query.' order by ExpiryDate ASC',$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Contractors whose Registration has expired', function($excel) use ($contractorsList,$dzongkhag,$fromDate,$limit) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorsList,$dzongkhag,$fromDate,$limit) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.registrationexpiredcontractors')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('contractorsList',$contractorsList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.registrationexpiredcontractors')
                        ->with('contractorsList',$contractorsList)
                        ->with('dzongkhags',$dzongkhags);
    }
}