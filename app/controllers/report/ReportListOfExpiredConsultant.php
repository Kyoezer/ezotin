<?php

class ReportListOfExpiredConsultant extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $consultantClassifications = DB::table('cmnconsultantservice')->orderBy('Code')->get(array("Code","Name"));
        $query = "select * from expiredconsultant Where 1";

        $parameters = array();
        $cdbNo = Input::get('CDBNo');
        $countryId = Input::has('CountryId')?Input::get('CountryId'):CONST_COUNTRY_BHUTAN;
        $dzongkhagId = Input::get('DzongkhagId');
        $classification = Input::get('Classification');
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
        if((bool)$classification){
            $query.=" and (CategoryA LIKE '%$classification%' or CategoryE LIKE '%$classification%' or CategoryC LIKE '%$classification%' or CategoryS LIKE '%$classification%')";
        }
        if(Request::segment(2) == 'listofconsultantsnearingexpiry'){
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
        if((bool)$statusId){
            $query.=" and StatusId = ?";
            array_push($parameters,$statusId);
        }
        if(Request::segment(2) == 'listofconsultantsnearingexpiry'){
            $query.=" and StatusId = ? order by CDBNo,case when DATEDIFF(ExpiryDate,NOW())<=30 and DATEDIFF(ExpiryDate,NOW()) > 0 then 1 else 2 end, ExpiryDate ASC";
            array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
            $type = 3;

        }else{
            $query.=" order by CDBNo ASC";
            $type = 1;

        }
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $consultantsList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Expired Consultants', function($excel) use ($consultantsList, $country,$dzongkhag,$cdbNo,$classification,$limit,$fromDate,$toDate,$statusId) {

                    $excel->sheet('Sheet 1', function($sheet) use ($consultantsList, $country,$dzongkhag,$cdbNo,$classification,$limit,$fromDate,$toDate,$statusId) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.expiredconsultant')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('cdbNo',$cdbNo)
                            ->with('classification',$classification)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('status',$statusId)
                            ->with('consultantsList',$consultantsList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.expiredconsultant')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('type',$type)
                        ->with('consultantsList',$consultantsList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('consultantClassifications',$consultantClassifications);
    }
}