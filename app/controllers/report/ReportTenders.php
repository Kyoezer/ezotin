<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 9/13/2016
 * Time: 1:50 PM
 */
class ReportTenders extends ReportController
{
    public function getDownloadedFromWeb(){
        $parameters = array();
        $limit = Input::has('Limit')?Input::get('Limit'):50;
        $workId = trim(Input::get("WorkId"));
        $agency = Input::get('Agency');
        $dzongkhag = Input::get("Dzongkhag");
        $classification = Input::get("Classification");
        $category = Input::get("Category");
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');


        $dzongkhags = DB::table('cmndzongkhag')->get(array('Id','NameEn as Dzongkhag'));
        $categories = DB::table('cmncontractorworkcategory')->get(array('Id','Code as Category'));
        $classes = DB::table('cmncontractorclassification')->get(array('Id','Code as Classification'));
        $agencies = DB::table('cmnprocuringagency')->get(array('Id','Name as ProcuringAgency'));

        DB::statement("SET SESSION group_concat_max_len = 1000000;");
        $query = "select T2.UploadedDate,T1.TenderId,count(T1.Id) as Total,group_concat(concat(coalesce(T1.PhoneNo,'--'),'/',coalesce(T1.Email,'--')) SEPARATOR ', ') as Details, case when T2.migratedworkid is null then concat(T3.Code,'/',year(T2.UploadedDate),'/',T2.WorkId) else T2.migratedworkid end as WorkId, T3.Name as Agency, T2.NameOfWork, T4.NameEn as Dzongkhag, T5.Code as Category, T6.Code as Class, T2.ContractPeriod, T2.ProjectEstimateCost, T2.TentativeStartDate, T2.TentativeEndDate from webtenderdownload T1 join (etltender T2 join cmnprocuringagency T3 on T2.CmnProcuringAgencyId = T3.Id join cmndzongkhag T4 on T4.Id = T2.CmnDzongkhagId left join cmncontractorworkcategory T5 on T5.Id = T2.CmnContractorCategoryId left join cmncontractorclassification T6 on T6.Id = T2.CmnContractorClassificationId) on T2.Id = T1.TenderId where (T1.Email is not null or T1.PhoneNo is not null)";
        if((bool)$workId){
            $query.=" and case when T2.migratedworkid is null then concat(T3.Code,'/',year(T2.UploadedDate),'/',T2.WorkId) else TRIM(T2.migratedworkid) end like '%$workId%'";
        }
        if((bool)$agency){
            $query.=" and T3.Id = ?";
            array_push($parameters,$agency);
        }
        if((bool)$dzongkhag){
            $query.=" and T4.Id = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$classification){
            $query.=" and T6.Id = ?";
            array_push($parameters,$classification);
        }
        if((bool)$category){
            $query.=" and T5.Id = ?";
            array_push($parameters,$category);
        }
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query.=" and DATE_FORMAT(T2.UploadedDate,'%Y-%m-%d') >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query.=" and DATE_FORMAT(T2.UploadedDate,'%Y-%m-%d') <= ?";
            array_push($parameters,$toDate);
        }
        $query.=" group by T1.TenderId order by T2.UploadedDate desc";

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select("$query$limitOffsetAppend",$parameters);
        return View::make('report.tendersdownloadedfromweb')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('dzongkhags',$dzongkhags)
                    ->with('categories',$categories)
                    ->with('classifications',$classes)
                    ->with('agencies',$agencies)
                    ->with('reportData',$reportData);
    }
    public function getDownloadDetails($id){
        $reportCount = DB::table("webtenderdownload")
                        ->whereRaw("(Email is not null or PhoneNo is not null)")
                        ->where('TenderId',$id)
                        ->count();
        $reportHalved = (int)($reportCount/2);
        $remainingReports = (int)$reportCount - (int)$reportHalved;
        $reportData1 = DB::select("select coalesce(T1.PhoneNo,'--') as PhoneNo,coalesce(T1.Email,'--') as Email from webtenderdownload T1 join (etltender T2 join cmnprocuringagency T3 on T2.CmnProcuringAgencyId = T3.Id join cmndzongkhag T4 on T4.Id = T2.CmnDzongkhagId left join cmncontractorworkcategory T5 on T5.Id = T2.CmnContractorCategoryId left join cmncontractorclassification T6 on T6.Id = T2.CmnContractorClassificationId) on T2.Id = T1.TenderId where (T1.Email is not null or T1.PhoneNo is not null) and T1.TenderId = ? limit 0, $reportHalved",array($id));
        $reportData2 = DB::select("select coalesce(T1.PhoneNo,'--') as PhoneNo,coalesce(T1.Email,'--') as Email from webtenderdownload T1 join (etltender T2 join cmnprocuringagency T3 on T2.CmnProcuringAgencyId = T3.Id join cmndzongkhag T4 on T4.Id = T2.CmnDzongkhagId left join cmncontractorworkcategory T5 on T5.Id = T2.CmnContractorCategoryId left join cmncontractorclassification T6 on T6.Id = T2.CmnContractorClassificationId) on T2.Id = T1.TenderId where (T1.Email is not null or T1.PhoneNo is not null) and T1.TenderId = ? limit $reportHalved, $remainingReports",array($id));
        return View::make("report.tenderdownloaddetails")
                    ->with('reportData1',$reportData1)
                    ->with('reportData2',$reportData2);
    }
}