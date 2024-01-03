<?php

class ReportEvaluation extends ReportController{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name'));
        $classes = ContractorClassificationModel::classification()->get(array('Name','Code'));
        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Name','Code'));
        $statuses = CmnListItemModel::WorkExecutionStatus()->get(array('Id','Name'));
        $parameters = array();
        $procuringAgency = Input::get('Agency');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $class = Input::get('Class');
        $category = Input::get('Category');
        $status = Input::get('Status');

        $query = "select concat(T2.Code, '/',year(T1.DateOfSaleOfTender),'/',T1.WorkId) as WorkId,T1.CommencementDateFinal, T1.NameOfWork,case when D.Name is not null then concat(D.Name,' - ',T2.Name) else T2.Name end as Agency, group_concat(X.CDBNo SEPARATOR ', ') as CDBNo, T3.Code as Class, T4.Code as Category, W.AwardedAmount, W.ActualStartDate, W.ActualEndDate, Y.Name as Status from etltender T1 join cmnlistitem Y on Y.Id = T1.CmnWorkExecutionStatusId left join (cmnprocuringagency T2 join cmnprocuringagency D on D.Id = T2.CmnProcuringAgencyId) on T2.Id = T1.CmnProcuringAgencyId join cmncontractorclassification T3 on T3.Id = T1.CmnContractorClassificationId join cmncontractorworkcategory T4 on T4.Id = T1.CmnContractorCategoryId left join (etltenderbiddercontractor W left join etltenderbiddercontractordetail E on E.EtlTenderBidderContractorId = W.Id join crpcontractorfinal X on X.Id = E.CrpContractorFinalId) on W.EtlTenderId = T1.Id and W.ActualStartDate is not null where coalesce(Y.ReferenceNo,0) in (3001,3003, 3002,12001,3005) and T1.TenderSource = 1";
        if((bool)$procuringAgency){
            $query.=" and (T2.Name = ? or D.Name = ?)";
            array_push($parameters,$procuringAgency);
            array_push($parameters,$procuringAgency);
        }
        if((bool)$class){
            $query.=" and T3.Name = ?";
            array_push($parameters,$class);
        }
        if((bool)$category){
            $query.=" and T4.Name = ?";
            array_push($parameters,$category);
        }
        if((bool)$status){
            $query.=" and Y.Id = ?";
            array_push($parameters,$status);
        }
        if((bool)$fromDate){
            $query.=" and coalesce(W.ActualStartDate,T1.DateOfSaleOfTender) >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and coalesce(W.ActualStartDate,T1.DateOfSaleOfTender) <= ?";
            array_push($parameters,$toDate);
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query." group by T1.Id order by T1.DateOfSaleOfTender DESC, T2.Name",$parameters,20,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query." group by T1.Id order by T1.DateOfSaleOfTender DESC, T2.Name$limitOffsetAppend",$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Evaluation (Report)', function($excel) use ($reportData, $procuringAgency,$fromDate,$toDate,$class,$category) {

                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $procuringAgency,$fromDate,$toDate,$class,$category) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.reportevaluation')
                            ->with('procuringAgency',$procuringAgency)
                            ->with('class',$class)
                            ->with('category',$category)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.reportevaluation')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('classes',$classes)
            ->with('categories',$categories)
            ->with('statuses',$statuses)
            ->with('reportData',$reportData); 
    }
}