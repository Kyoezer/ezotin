<?php

class SurveyorReport extends ReportController{
    public function getIndex(){

        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $sectors = CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
        $trades = CmnListItemModel::trade()->get(array('Id','Name'));
        // $query = "select * from crparchitectfinal a
        //         left join sysuser b on a.SysUserId=b.Id
        //         where a.ARNo like 'CDB/BS/%'";
        
        $query = "select ARNo,Sector,  Status, CIDNo,ArchitectName, ApprovedDate,ExpiryDate,
         Dzongkhag, Country, Gewog,Village,Email, MobileNo from viewlistofarchitects Where ARNo like 'CDB/BS/%' ";

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
        if(Request::segment(2) == 'listofsurveyorexpiring'){
            $type = 3;
            if((bool)$fromDate){
                $query.=" and ExpiryDate >= ?";
                array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
            }
            if((bool)$toDate){
                $query.=" and ExpiryDate <= ?";
                array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
            }
        }else{
            $type = 1;
            if((bool)$fromDate){
                $query.=" and ApprovedDate >= ?";
                array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
            }
            if((bool)$toDate){
                $query.=" and ApprovedDate <= ?";
                array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
            }
        }
        if((bool)$cdbNo){
            $query.=" and ARNo like '%$cdbNo%'";
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

        $surveyorList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listOfSurveyor')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('ARNo',$cdbNo)
                            ->with('sector',$sector)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('status',$status)
                            ->with('surveyorList',$surveyorList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listOfSurveyor')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('surveyorList',$surveyorList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('type',$type)
                        ->with('trades',$trades)
                        ->with('sectors',$sectors);
    }
 
}