<?php

class ReportListOfContractors  extends ReportController{
    public function getIndex(){
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $contractorClassifications = DB::table('cmncontractorclassification')->orderBy('Code')->get(array("Code","Name"));
        $query = "  SELECT `viewlistofcontractors`.`CDBNo` AS `CDBNo`, `viewlistofcontractors`.`NameOfFirm` AS `NameOfFirm`, `viewlistofcontractors`.`Address` AS `Address`, `viewlistofcontractors`.`ApprovedDate` AS `ApplicationDate`, `viewlistofcontractors`.`Status` AS `Status`, `viewlistofcontractors`.`ExpiryDate` AS `ExpiryDate`, `viewlistofcontractors`.`Country` AS `Country`, `viewlistofcontractors`.`Dzongkhag` AS `Dzongkhag`, `viewlistofcontractors`.`TelephoneNo` AS `TelephoneNo`, `viewlistofcontractors`.`MobileNo` AS `MobileNo`, `viewlistofcontractors`.`Classification1` AS `Classification1`, `viewlistofcontractors`.`Classification2` AS `Classification2`, `viewlistofcontractors`.`Classification3` AS `Classification3`, `viewlistofcontractors`.`Classification4` AS `Classification4`, GROUP_CONCAT(`crpcontractorhumanresourcefinal`.`Name` SEPARATOR ',') AS `ownerName`, IF(`crpcontractorhumanresourcefinal`.`Sex` = 'M','Male',IF(`crpcontractorhumanresourcefinal`.`Sex` = 'F','Female','-')) AS `gender` FROM((`viewlistofcontractors` LEFT JOIN `crpcontractorfinal` ON (`viewlistofcontractors`.`CDBNo` = `crpcontractorfinal`.`CDBNo`)) LEFT JOIN `crpcontractorhumanresourcefinal` ON (`crpcontractorfinal`.`Id` = `crpcontractorhumanresourcefinal`.`CrpContractorFinalId`)) WHERE `viewlistofcontractors`.`Status` = 'Approved' AND `crpcontractorhumanresourcefinal`.`IsPartnerOrOwner` = '1' AND 1 GROUP BY `viewlistofcontractors`.`CDBNo`$$ ";

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
            $query.=" and ApplicationDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and ApplicationDate <= ?";
            array_push($parameters,$toDate);
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
                Excel::create('List of Contractors', function($excel) use ($contractorList, $country,$dzongkhag,$cdbNo,$limit,$fromDate,$toDate,$start) {

                    $excel->sheet('Sheet 1', function($sheet) use ($contractorList, $country,$dzongkhag,$cdbNo,$limit,$fromDate,$toDate,$start) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofcontractors')
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
        return View::make('report.listofcontractors')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorList',$contractorList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('contractorClassifications',$contractorClassifications);
                    
    }


public function getCategoryWise(){
        $parameters = array();
        $parametersForPrint = array();
        $reportData = array();
        $hasParams = false;
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $category = Input::get('ContractorCategoryId');
        $classification = Input::get('ContractorClassificationId');
        $statusId = Input::get('StatusId');
        $countryId = Input::get('CountryId');
        $dzongkhagId = Input::get('DzongkhagId');

        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Code'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));

        $query = "select count(distinct T1.Id) as Count from crpcontractorfinal T1 left join crpcontractorworkclassificationfinal T2 on T1.Id = T2.CrpContractorFinalId where 1";

        if((bool)$fromDate){
            $hasParams = true;
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and ApprovedDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
        }
        if((bool)$toDate){
            $hasParams = true;
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and ApprovedDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
        }
        if((bool)$category){
            $hasParams = true;
            $parametersForPrint['Category'] = ContractorWorkCategoryModel::contractorProjectCategory()->where('Id',$category)->pluck('Code');
            $query.=" and T2.CmnProjectCategoryId = ?";
            array_push($parameters,$category);
        }
        if((bool)$classification){
            $hasParams = true;
            $parametersForPrint['Classification'] = ContractorClassificationModel::classification()->where('Id',$classification)->pluck('Code');
            $query.=" and T2.CmnApprovedClassificationId = ?";
            array_push($parameters,$classification);
        }
        if((bool)$statusId){
            $hasParams = true;
            $parametersForPrint['Status'] = DB::table('cmnlistitem')->where('Id',$statusId)->pluck('Name');
            $query.=" and T1.CmnApplicationRegistrationStatusId = ?";
            array_push($parameters,$statusId);
        }
        if((bool)$countryId){
            $hasParams = true;
            $parametersForPrint['Country'] = DB::table('cmncountry')->where('Id',$countryId)->pluck('Name');
            $query.=" and T1.CmnCountryId = ?";
            array_push($parameters,$countryId);
        }
        if((bool)$dzongkhagId){
            $hasParams = true;
            $parametersForPrint['Dzongkhag'] = DB::table('cmndzongkhag')->where('Id',$dzongkhagId)->pluck("NameEn");
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if($hasParams){
            $reportData = DB::select("$query", $parameters);
        }
        return View::make('report.contractorcategoryreport')
                ->with('statuses',$statuses)
                ->with('countries',$countries)
                ->with('dzongkhags',$dzongkhags)
                ->with('contractorClassifications',$classifications)
                ->with('contractorCategories',$categories)
                ->with('parametersForPrint',$parametersForPrint)
                ->with('reportData',$reportData);
    }

}