<?php

class ContractorsByDzongkhag extends ReportController{
    public function getIndex(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $parameters = array();
        $reportQuery = "select NameEn,W1L,W1M,W1S,W1Total,W2R,W3L,W3M,W3S,W3Total,W4L,W4M,W4S,W4Total from viewcontractorsbydzongkhag where 1";

//        $reportQuery = "select distinct T1.Dzongkhag as NameEn,
//(select count(*) from viewlistofcontractors B where B.ClassId1 = 'e19afe94-c3ea-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W1L,
//(select count(*) from viewlistofcontractors B where B.ClassId1 = '003f9a02-c3eb-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W1M,
//(select count(*) from viewlistofcontractors B where B.ClassId1 = 'ef832830-c3ea-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W1S,
//(select count(*) from viewlistofcontractors B where B.ClassId2 = '0c14ebea-c3eb-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W2R,
//(select count(*) from viewlistofcontractors B where B.ClassId3 = 'e19afe94-c3ea-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W3L,
//(select count(*) from viewlistofcontractors B where B.ClassId3 = '003f9a02-c3eb-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W3M,
//(select count(*) from viewlistofcontractors B where B.ClassId3 = 'ef832830-c3ea-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W3S,
//(select count(*) from viewlistofcontractors B where B.ClassId4 = 'e19afe94-c3ea-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W4L,
//(select count(*) from viewlistofcontractors B where B.ClassId4 = '003f9a02-c3eb-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W4M,
//(select count(*) from viewlistofcontractors B where B.ClassId4 = 'ef832830-c3ea-11e4-af9f-080027dcfac6' and B.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.Dzongkhag = B.Dzongkhag and B.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6') as W4S
//from viewlistofcontractors T1 where T1.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' and T1.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' and T1.Dzongkhag is not null";
//        array_push($parameters,CONST_COUNTRY_BHUTAN);
        $dzongkhagId = Input::get('DzongkhagId');
        if((bool)$dzongkhagId){
            $reportQuery.=" and CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }

        $reportData = DB::select($reportQuery,$parameters);

        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = '';
            if((bool)$dzongkhagId){
                $dzongkhag = DB::table('cmndzongkhag')->where('Id',$dzongkhagId)->pluck('NameEn');
            }
            if($export == 'excel'){
                Excel::create('Contractors By Dzongkhag', function($excel) use ($reportData, $dzongkhags,$dzongkhag) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $dzongkhags,$dzongkhag) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.contractorsbydzongkhag')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('reportData',$reportData)
                            ->with('dzongkhags',$dzongkhags);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.contractorsbydzongkhag')
                ->with('reportData',$reportData)
                ->with('dzongkhags',$dzongkhags);
    }
    public function exportToExcel(){
        Excel::create('Contractors By Dzongkhag', function($excel) {

            $excel->sheet('Sheet 1', function($sheet) {

                $sheet->loadView('folder.view');

            });

        });
    }

    public function getSummary(){
        $reportData = DB::select("SELECT T1.Dzongkhag, (select count(Dzongkhag) from viewcontractormaxclassification A where A.Dzongkhag = T1.Dzongkhag and MaxClassificationPriority = 1000 and A.CmnCountryId = ? and A.StatusReference <> 12005) as Large,(select count(Dzongkhag) from viewcontractormaxclassification A where A.Dzongkhag = T1.Dzongkhag and MaxClassificationPriority = 999 and A.CmnCountryId = ? and A.StatusReference <> 12005) as Medium,(select count(Dzongkhag) from viewcontractormaxclassification A where A.Dzongkhag = T1.Dzongkhag and MaxClassificationPriority = 998 and A.CmnCountryId = ? and A.StatusReference <> 12005) as Small,(select count(Dzongkhag) from viewcontractormaxclassification A where A.Dzongkhag = T1.Dzongkhag and MaxClassificationPriority = 997 and A.CmnCountryId = ? and A.StatusReference <> 12005) as Registered FROM `viewcontractormaxclassification` T1 where T1.Dzongkhag is not null and T1.CmnCountryId = ? group by T1.Dzongkhag",array(CONST_COUNTRY_BHUTAN,CONST_COUNTRY_BHUTAN,CONST_COUNTRY_BHUTAN,CONST_COUNTRY_BHUTAN,CONST_COUNTRY_BHUTAN));
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Contractors By Dzongkhag (Summary)', function($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.contractorsbydzongkhagsummary')
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.contractorsbydzongkhagsummary')
                ->with('reportData',$reportData);
    }
}