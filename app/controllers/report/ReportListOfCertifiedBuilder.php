<?php

class ReportListOfCertifiedBuilder extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $query = "select CDBNo, NameOfFirm, Address,Status,Name,Sex ,ExpiryDate,Email, Dzongkhag, Country, TelephoneNo, MobileNo from listofcertifiedbuilder Where 1";

        $parameters = array();
        $cdbNo = Input::get('CDBNo');
        $countryId = Input::has('CountryId')?Input::get('CountryId'):CONST_COUNTRY_BHUTAN;
        $dzongkhagId = Input::get('DzongkhagId');
        $limit = Input::has('Limit')?Input::get('Limit'):'All';
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $statusId = Input::has('Status')?Input::get('Status'):CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
        $country ="";
        $dzongkhag = "";
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
        }
        if((bool)$countryId){
            $country = DB::table('cmncountry')->where('Id',$countryId)->pluck('Name');
            $query.=" and CmnCountryId = ?";
            array_push($parameters,$countryId);
        }
        if((bool)$dzongkhagId){
            $dzongkhag = DB::table('cmndzongkhag')->where('Id',$dzongkhagId)->pluck('NameEn');
            $query.=" and CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        
        if(Request::segment(2) == 'listofcertifiedbuilderbynearingexpiry'){
            if((bool)$fromDate){
                $query.=" and ExpiryDate >= ?";
                array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
            }
            if((bool)$toDate){
                $query.=" and ExpiryDate <= ?";
                array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
            }
        }else{
            if((bool)$fromDate){
                $query.=" and ApprovedDate >= ?";
                array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
            }
            if((bool)$toDate){
                $query.=" and ApprovedDate <= ?";
                array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
            }
        }
        
        if(Request::segment(2) == 'listofcertifiedbuilderbynearingexpiry'){
            $query.=" and StatusId = ? order by CDBNo,case when DATEDIFF(ExpiryDate,NOW())<=30 and DATEDIFF(ExpiryDate,NOW()) > 0 then 1 else 2 end, ExpiryDate ASC";
            array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
            $type = 3;
        }else{
            $query.=" order by CDBNo";
            $type = 1;
        }
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $certifiedbuilderList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Certified Builder', function($excel) use ($certifiedbuilderList, $country,$dzongkhag,$cdbNo,$limit,$fromDate,$toDate,$statusId) {

                    $excel->sheet('Sheet 1', function($sheet) use ($certifiedbuilderList, $country,$dzongkhag,$cdbNo,$limit,$fromDate,$toDate,$statusId) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofcertifiedbuilder')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('cdbNo',$cdbNo)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('status',$statusId)
                            ->with('certifiedbuilderList',$certifiedbuilderList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofcertifiedbuilder')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('type',$type)
                        ->with('certifiedbuilderList',$certifiedbuilderList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses);
    }
}