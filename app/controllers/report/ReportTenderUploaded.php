<?php

class ReportTenderUploaded extends ReportController{
    public function getIndex(){
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name'));
        $parameters = array();
        $limit = Input::get('Limit');
        $procuringAgency = Input::get('Agency');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $tenderSource = Input::has('TenderSource')?Input::get('TenderSource'):0;

        $query = "select T1.NameOfWork,T1.UploadedDate,case when T3.Name is not null then concat(T3.Name,' - ',T2.Name) else T2.Name end as Agency, date(T1.LastDateAndTimeOfSubmission) as LastDateOfSubmission from etltender T1 left join (cmnprocuringagency T2 left join cmnprocuringagency T3 on T3.Id = T2.CmnProcuringAgencyId) on T2.Id = T1.CmnProcuringAgencyId where 1";
        if((bool)$procuringAgency){
            $query.=" and (T2.Name = ? or T3.Name = ?)";
            array_push($parameters,$procuringAgency);
            array_push($parameters,$procuringAgency);
        }
        if((bool)$fromDate){
            $query.=" and DATE_FORMAT(T1.UploadedDate,'%Y-%m-%d') >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and DATE_FORMAT(T1.UploadedDate,'%Y-%m-%d') <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$tenderSource){
            $query.=" and T1.TenderSource = ?";
            array_push($parameters,$tenderSource);
        }
        $query.=" order by UploadedDate DESC, T2.Name";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Tender Uploaded', function($excel) use ($reportData, $procuringAgency,$fromDate,$toDate,$tenderSource) {

                    $excel->sheet('Sheet 1', function($sheet) use ($reportData, $procuringAgency,$fromDate,$toDate,$tenderSource) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.tenderuploaded')
                            ->with('procuringAgency',$procuringAgency)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('tenderSource',$tenderSource)
                            ->with('reportData',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.tenderuploaded')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('reportData',$reportData);
    }

    public function workcompletedsameyear(){
        
        $query = "select count(*) totalWork,date_format(a.CompletionDateFinal,'%Y')CompletionDateFinal,date_format(b.ActualStartDate,'%Y')ActualStartDate from etltender a 
                    left join etltenderbiddercontractor b on a.Id=b.EtlTenderId
                    where a.CmnWorkExecutionStatusId='a13c5d39-b5a8-11e4-81ac-080027dcfac6' 
                    and date_format(a.CompletionDateFinal,'%Y')=date_format(b.ActualStartDate,'%Y') 
                    group by date_format(a.CompletionDateFinal,'%Y') order by a.CompletionDateFinal desc";
        /*PAGINATION*/
        $parameters = array(); 
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $workList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.workcompletedsameyear')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('workList',$workList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.workcompletedsameyear')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('workList',$workList);
    }



    public function workawardedabovebelowestimate(){
        
        $query = "SELECT a.`NameOfWork`,a.`ReferenceNo`,d.NameOfFirm,d.CDBNo,a.`ProjectEstimateCost`,b.AwardedAmount 
        ,if(a.ProjectEstimateCost<b.AwardedAmount,'Above Estimated','Below Estimated') estimatedStatus
        FROM `etltender`  a
        left join etltenderbiddercontractor b on a.id=b.EtlTenderId and b.AwardedAmount is not null
        left join etltenderbiddercontractordetail c on b.Id=c.EtlTenderBidderContractorId
        left join crpcontractorfinal d on c.CrpContractorFinalId=d.Id 
        where a.ProjectEstimateCost <> b.AwardedAmount ";
        /*PAGINATION*/
        $parameters = array(); 
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $workList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of work awarded above below estimate cost', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.workawardedabovebelowestimate')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('workList',$workList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.workawardedabovebelowestimate')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('workList',$workList);
    }

}