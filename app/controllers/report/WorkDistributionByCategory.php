<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 4/20/2015
 * Time: 12:42 PM
 */

class WorkDistributionByCategory extends ReportController{
    public function getIndex(){
        $parameters = array();
        $dzongkhagsAgencies = DB::select('select Id, NameEn as Code from cmndzongkhag');
//        $query = "select `t1`.`Code` AS `Code`,count(`t2`.`Id`) AS `NoOfWorks`,coalesce(sum(coalesce(`t2`.`ContractPriceFinal`,x.AwardedAmount)),0) AS `ContractAmount` from (`cmncontractorworkcategory` `t1` left join ((`etltender` `t2` join cmndzongkhag S on S.Id = t2.CmnDzongkhagId join `cmnlistitem` `a` on((`t2`.`CmnWorkExecutionStatusId` = `a`.`Id`))) join `etltenderbiddercontractor` `x` on((`x`.`EtlTenderId` = `t2`.`Id`) and x.ActualStartDate is not null)) on(((`t2`.`CmnContractorCategoryId` = `t1`.`Id`) and (`a`.`ReferenceNo` in (3001,3003))))) where t2.CmnWorkExecutionStatusId is not null";
        $query = "select T1.ProjectCategory as Code, count(T1.WorkStartDate) as NoOfWorks, sum(coalesce(T1.FinalAmount,T1.EvaluatedAmount)) as ContractAmount from viewcontractorstrackrecords T1 where T1.ReferenceNo in (3001,3003)";
        $dzongkhag = Input::get('Dzongkhag');
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):false;
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $type = Input::get('Type');
        if((bool)$dzongkhag){
            $query.=" and T1.Dzongkhag = ?";
            array_push($parameters,$dzongkhag);
        }
        if((bool)$fromDate){
            $query.=" and T1.WorkStartDate >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.WorkCompletionDate <= ?";
            array_push($parameters,$toDate);
        }
        if((bool)$type){
            $query.=" and T1.Type = ?";
            array_push($parameters,(int)$type);
        }
        $reportData = DB::select("$query group By T1.ProjectCategory",$parameters);
        return View::make('report.workdistributionbycategory')
            ->with('dzongkhagsAgencies',$dzongkhagsAgencies)
            ->with('reportData',$reportData);
    }
}