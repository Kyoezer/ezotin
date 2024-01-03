<?php

class ReportListOfRevokedSpecializedfirm extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $specializedfirmListAll=SpecializedfirmFinalModel::SpecializedtradeHardListAll()->get(array('Id','NameOfFirm'));
        $specializedfirmCategoryId = SpecializedTradeCategoryModel::Category()->orderBy('Code')->get(array("Id","Code","Name"));
 
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
        $parameters=array();
        $cdbNo=Input::get('CdbRegistrationNo');
        $specializedtradeId=Input::get('SpecializedtradeId');
        $dzongkhags = Input::get('CmnDzongkhagId');
        $SpecializedfirmCategories = Input::get('CmnApprovedCategoryId');
        
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "select SPNo, NameOfFirm, Address,Status, ExpiryDate, Dzongkhag, Country, TelephoneNo, MobileNo, Category from listofspecializedfirm Where StatusId = 'f89a6f55-b1dd-11e4-89f3-080027dcfac6'  or StatusId = 'f89a6f55-b1dd-aac4-89f3-080027dcfac6'";
      

        if((bool)$cdbNo){
            $parametersForPrint['SP No.'] = $cdbNo;
            $query.=" and SPNo=?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$specializedtradeId){
            $parametersForPrint['Specializedtrade'] = $specializedtradeId;
            $specializedtradeId="%$specializedtradeId%";
            $query.=" and NameOfFirm like ?";
            array_push($parameters,$specializedtradeId);
        }
        if((bool)$dzongkhags){
            $parametersForPrint['Dzongkhag'] = $dzongkhags;
            $query.=" and Dzongkhag=?";
            array_push($parameters,$dzongkhags);
        }
        
        if((bool)$SpecializedfirmCategories){
            $parametersForPrint['Category'] = DB::table('cmnspecializedtradecategory')->where('Id',$SpecializedfirmCategories)->pluck('Code');
            $query.=" and (CategoryId1 = ? or CategoryId2 = ? or CategoryId3 = ? or CategoryId4 = ? or CategoryId5 = ? or CategoryId6 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$SpecializedfirmCategories);
            }
        }
        if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and ApprovedDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and ApprovedDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }

        $query.=" order by SPNo,NameOfFirm";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $specializedfirmLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Revoked/Suspended/Debarred Specializedfirm', function($excel) use ($specializedfirmLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($specializedfirmLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofspecializedfirmrevoked')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('specializedfirmLists',$specializedfirmLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofspecializedfirmrevoked')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('specializedfirmListAll',$specializedfirmListAll)
            ->with('specializedtradeId',$specializedtradeId)
            ->with('specializedfirmLists',$specializedfirmLists)
            ->with('dzongkhagId',$dzongkhagId)
            ->with('specializedfirmCategoryId',$specializedfirmCategoryId);
       
    }
}