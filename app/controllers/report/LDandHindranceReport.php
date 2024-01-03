<?php
class LDandHindranceReport extends ReportController{
    public function getLD(){
        $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'--';
        $workId = Input::has('WorkId')?Input::get('WorkId'):'--';
        $ldWorks = DB::table('etltender as T1')
                        ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                        ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                        ->join('cmncontractorclassification as T4','T1.CmnContractorClassificationId','=','T4.Id')
                        ->join('cmnlistitem as T5','T5.Id','=','T1.CmnWorkExecutionStatusId')
                        ->leftJoin('cmndzongkhag as T6','T6.Id','=','T1.CmnDzongkhagId')
                        ->join('etltenderbiddercontractor as T7','T7.EtlTenderId','=','T1.Id')
                        ->join('etltenderbiddercontractordetail as T8','T8.EtlTenderBidderContractorId','=','T7.Id')
                        ->join('crpcontractorfinal as T9','T9.Id','=','T8.CrpContractorFinalId')
                        ->whereRaw('(T7.AwardedAmount is not null and T7.ActualStartDate is not null)')
                        ->whereRaw("(case when '$workId' = '--' then 1 else case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) = '$workId' else T1.migratedworkid = '$workId' end end)")
                        ->whereRaw("coalesce(T1.LDImposed,0)=1")
                        ->groupBy('T1.Id')
                        ->havingRaw("(case when '$cdbNo' = '--' then 1 else Contractors like '%$cdbNo%' end)")
                        ->select(DB::raw("distinct T1.Id"),'T1.ContractPriceFinal','T7.AwardedAmount','T1.CommencementDateFinal','CompletionDateFinal','T7.ActualStartDate','T7.ActualEndDate','T1.CmnWorkExecutionStatusId',DB::raw("GROUP_CONCAT(CONCAT(T9.NameOfFirm,' (',T9.CDBNo,')') Separator ', ') as Contractors"),'T1.Remarks','T6.NameEn as Dzongkhag','LDNoOfDays','LDAmount','T1.NameOfWork',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"))
                        ->paginate(15);

        if(Input::get('export') == 'excel'){
            $ldWorks = DB::table('etltender as T1')
                ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                ->join('cmncontractorclassification as T4','T1.CmnContractorClassificationId','=','T4.Id')
                ->join('cmnlistitem as T5','T5.Id','=','T1.CmnWorkExecutionStatusId')
                ->leftJoin('cmndzongkhag as T6','T6.Id','=','T1.CmnDzongkhagId')
                ->join('etltenderbiddercontractor as T7','T7.EtlTenderId','=','T1.Id')
                ->join('etltenderbiddercontractordetail as T8','T8.EtlTenderBidderContractorId','=','T7.Id')
                ->join('crpcontractorfinal as T9','T9.Id','=','T8.CrpContractorFinalId')
                ->whereRaw('(T7.AwardedAmount is not null and T7.ActualStartDate is not null)')
                ->whereRaw("(case when '$workId' = '--' then 1 else case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) = '$workId' else T1.migratedworkid = '$workId' end end)")
                ->whereRaw("coalesce(T1.LDImposed,0)=1")
                ->groupBy('T1.Id')
                ->havingRaw("(case when '$cdbNo' = '--' then 1 else Contractors like '%$cdbNo%' end)")
                ->select(DB::raw("distinct T1.Id"),'T1.ContractPriceFinal','T7.AwardedAmount','T1.CommencementDateFinal','CompletionDateFinal','T7.ActualStartDate','T7.ActualEndDate','T1.CmnWorkExecutionStatusId',DB::raw("GROUP_CONCAT(CONCAT(T9.NameOfFirm,' (',T9.CDBNo,')') Separator ', ') as Contractors"),'T1.Remarks','T6.NameEn as Dzongkhag','LDNoOfDays','LDAmount','T1.NameOfWork',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"))
                ->get();
            Excel::create('LD Report', function ($excel) use ($ldWorks,$cdbNo,$workId) {
                $excel->sheet('Sheet 1', function ($sheet) use ($ldWorks,$cdbNo,$workId) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->loadView('reportexcel.ldreport')
                        ->with('cdbNo', $cdbNo)
                        ->with('workId', $workId)
                        ->with('ldWorks', $ldWorks);
                });
            })->export('xlsx');
        }else{
            $ldWorks = DB::table('etltender as T1')
                ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                ->join('cmncontractorclassification as T4','T1.CmnContractorClassificationId','=','T4.Id')
                ->join('cmnlistitem as T5','T5.Id','=','T1.CmnWorkExecutionStatusId')
                ->leftJoin('cmndzongkhag as T6','T6.Id','=','T1.CmnDzongkhagId')
                ->join('etltenderbiddercontractor as T7','T7.EtlTenderId','=','T1.Id')
                ->join('etltenderbiddercontractordetail as T8','T8.EtlTenderBidderContractorId','=','T7.Id')
                ->join('crpcontractorfinal as T9','T9.Id','=','T8.CrpContractorFinalId')
                ->whereRaw('(T7.AwardedAmount is not null and T7.ActualStartDate is not null)')
                ->whereRaw("(case when '$workId' = '--' then 1 else case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) = '$workId' else T1.migratedworkid = '$workId' end end)")
                ->whereRaw("coalesce(T1.LDImposed,0)=1")
                ->groupBy('T1.Id')
                ->havingRaw("(case when '$cdbNo' = '--' then 1 else Contractors like '%$cdbNo%' end)")
                ->select(DB::raw("distinct T1.Id"),'T1.ContractPriceFinal','T7.AwardedAmount','T1.CommencementDateFinal','CompletionDateFinal','T7.ActualStartDate','T7.ActualEndDate','T1.CmnWorkExecutionStatusId',DB::raw("GROUP_CONCAT(CONCAT(T9.NameOfFirm,' (',T9.CDBNo,')') Separator ', ') as Contractors"),'T1.Remarks','T6.NameEn as Dzongkhag','LDNoOfDays','LDAmount','T1.NameOfWork',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"))
                ->paginate(15);
        }
        return View::make('report.ldreport')
                    ->with('parametersForPrint',array())
                    ->with('ldWorks',$ldWorks);
    }
    public function getHindrance(){
        $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'--';
        $workId = Input::has('WorkId')?Input::get('WorkId'):'--';

        if(Input::get('export') == 'excel'){
            $hindranceWorks = DB::table('etltender as T1')
                ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                ->join('cmncontractorclassification as T4','T1.CmnContractorClassificationId','=','T4.Id')
                ->join('cmnlistitem as T5','T5.Id','=','T1.CmnWorkExecutionStatusId')
                ->leftJoin('cmndzongkhag as T6','T6.Id','=','T1.CmnDzongkhagId')
                ->join('etltenderbiddercontractor as T7','T7.EtlTenderId','=','T1.Id')
                ->join('etltenderbiddercontractordetail as T8','T8.EtlTenderBidderContractorId','=','T7.Id')
                ->join('crpcontractorfinal as T9','T9.Id','=','T8.CrpContractorFinalId')
                ->whereRaw('(T7.AwardedAmount is not null and T7.ActualStartDate is not null)')
                ->whereRaw("(case when '$workId' = '--' then 1 else case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) = '$workId' else T1.migratedworkid = '$workId' end end)")
                ->whereRaw("coalesce(T1.Hindrance,0)=1")
                ->groupBy('T1.Id')
                ->havingRaw("(case when '$cdbNo' = '--' then 1 else Contractors like '%$cdbNo%' end)")
                ->select(DB::raw("distinct T1.Id"),'T1.ContractPriceFinal','T7.AwardedAmount','T1.CommencementDateFinal','CompletionDateFinal','T7.ActualStartDate','T7.ActualEndDate','T1.CmnWorkExecutionStatusId',DB::raw("GROUP_CONCAT(CONCAT(T9.NameOfFirm,' (',T9.CDBNo,')') Separator ', ') as Contractors"),'T1.Remarks','T6.NameEn as Dzongkhag','HindranceNoOfDays','T1.NameOfWork',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"))
                ->get();
            Excel::create('Hindrance Report', function ($excel) use ($hindranceWorks,$cdbNo,$workId) {
                $excel->sheet('Sheet 1', function ($sheet) use ($hindranceWorks,$cdbNo,$workId) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->loadView('reportexcel.hindrancereport')
                        ->with('cdbNo', $cdbNo)
                        ->with('workId', $workId)
                        ->with('hindranceWorks', $hindranceWorks);
                });
            })->export('xlsx');
        }else{
            $hindranceWorks = DB::table('etltender as T1')
                ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                ->join('cmncontractorclassification as T4','T1.CmnContractorClassificationId','=','T4.Id')
                ->join('cmnlistitem as T5','T5.Id','=','T1.CmnWorkExecutionStatusId')
                ->leftJoin('cmndzongkhag as T6','T6.Id','=','T1.CmnDzongkhagId')
                ->join('etltenderbiddercontractor as T7','T7.EtlTenderId','=','T1.Id')
                ->join('etltenderbiddercontractordetail as T8','T8.EtlTenderBidderContractorId','=','T7.Id')
                ->join('crpcontractorfinal as T9','T9.Id','=','T8.CrpContractorFinalId')
                ->whereRaw('(T7.AwardedAmount is not null and T7.ActualStartDate is not null)')
                ->whereRaw("(case when '$workId' = '--' then 1 else case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) = '$workId' else T1.migratedworkid = '$workId' end end)")
                ->whereRaw("coalesce(T1.Hindrance,0)=1")
                ->groupBy('T1.Id')
                ->havingRaw("(case when '$cdbNo' = '--' then 1 else Contractors like '%$cdbNo%' end)")
                ->select(DB::raw("distinct T1.Id"),'T1.ContractPriceFinal','T7.AwardedAmount','T1.CommencementDateFinal','CompletionDateFinal','T7.ActualStartDate','T7.ActualEndDate','T1.CmnWorkExecutionStatusId',DB::raw("GROUP_CONCAT(CONCAT(T9.NameOfFirm,' (',T9.CDBNo,')') Separator ', ') as Contractors"),'T1.Remarks','T6.NameEn as Dzongkhag','HindranceNoOfDays','T1.NameOfWork',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"))
                ->paginate(15);
        }
        return View::make('report.hindrancereport')
            ->with('parametersForPrint',array())
            ->with('hindranceWorks',$hindranceWorks);
    }
}