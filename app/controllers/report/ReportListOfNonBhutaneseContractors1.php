<?php

class ReportListOfNonBhutaneseContractors extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->where('Name',DB::raw('<>'),'Bhutan')->get(array('Id','Name'));
        $dzongkhags= DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
       // $query = "select CDBNo, NameOfFirm, Address,Status, ExpiryDate, Country,'--' as Dzongkhag, TelephoneNo, MobileNo, Classification1, Classification2, Classification3, Classification4 from viewlistofcontractors Where Country <> 'Bhutan'";
        $query = " SELECT CDBNo, NameOfFirm, Address, Status, ExpiryDate, Country, Dzongkhag, TelephoneNo, MobileNo, Classification1, Classification2, Classification3, Classification4, ownerName, gender FROM ViewNonBhutaneseContractor where 1";
        $parameters = array();
        $cdbNo = Input::get('CDBNo');
        $countryId = Input::get('CountryId');
        $dzongkhagId = Input::get('DzongkhagId');
        $categoryId = Input::get('ContractorCategoryId');
        $classificationId = Input::get('ContractorClassificationId');
        $limit = Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $statusId = Input::get('StatusId');
        if((bool)$cdbNo){
            $query.=" and CDBNo = '$cdbNo'";
        }
        if((bool)$countryId){
            $query.=" and CmnCountryId = ?";
            array_push($parameters,$countryId);
        }
        if((bool)$categoryId){
            $query.=" and (CategoryId1 = ? or CategoryId2 = ? or CategoryId3 = ? or CategoryId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$categoryId);
            }
        }
        if((bool)$classificationId){
            $query.=" and (ClassId1 = ? or ClassId2 = ? or ClassId3 = ? or ClassId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$classificationId);
            }
        }
        if((bool)$fromDate){
            $query.=" and InitialDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and InitialDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$statusId){
            $query.=" and CmnApplicationRegistrationStatusId = ?";
            array_push($parameters,$statusId);
        }

  else{
                $query.=" order by CDBNo";
               
            }


        $pageNo = Input::has('page')?Input::get('page'):1;

        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        $contractorsList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = '';
            $country = '';
            $category = '';
            $classification = '';
            $status = '';
            if((bool)$dzongkhagId){
                $dzongkhag = DB::table('cmndzongkhag')->where('Id',$dzongkhagId)->pluck('NameEn');
            }
            if((bool)$countryId){
                $country = DB::table('cmncountry')->where('Id',$countryId)->pluck('Name');
            }
            if((bool)$categoryId){
                $category = DB::table('cmncontractorworkcategory')->where('Id',$categoryId)->pluck('Code');
            }
            if((bool)$classificationId){
                $classification = DB::table('cmncontractorclassification')->where('Id',$classificationId)->pluck('Code');
            }
            if((bool)$statusId){
                $status = DB::table('cmnlistitem')->where('Id',$statusId)->pluck('Name');
            }
            if($export == 'excel'){
                Excel::create('List of Non Bhutanese Contractors', function($excel) use ($contractorsList,$toDate,$fromDate,$cdbNo,$limit,$dzongkhag,$country,$category,$classification,$status) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorsList,$cdbNo,$toDate,$fromDate,$limit,$dzongkhag,$country,$category,$classification,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofcontractors')
                            ->with('type',2)
                            ->with('cdbNo',$cdbNo)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('category',$category)
                            ->with('classification',$classification)
                            ->with('status',$status)
                            ->with('contractorsList',$contractorsList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofcontractors')
                        ->with('type',2)
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorsList',$contractorsList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('contractorCategories',$contractorCategories)
                        ->with('contractorClassifications',$contractorClassifications);
    }
}