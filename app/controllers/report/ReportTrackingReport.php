<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/2/2016
 * Time: 9:04 PM
 */
class ReportTrackingReport extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $operation = Input::get("operation");
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $username = Input::get('username');
        $workid = Input::get('workid');
        $clear = Input::get('clear');
        if((bool)$clear){
            Session::forget("reporttracking_operation");
            Session::forget("reporttracking_fromdate");
            Session::forget("reporttracking_todate");
            Session::forget("reporttracking_username");
            Session::forget("reporttracking_workid");
        }
        if($operation=='' && $operation != NULL){
            Session::forget("reporttracking_operation");
        }if($fromDate=='' && $fromDate != NULL){
            Session::forget("reporttracking_fromdate");
        }if($toDate=='' && $toDate != NULL){
            Session::forget("reporttracking_todate");
        }if($username=='' && $username != NULL){
            Session::forget("reporttracking_username");
        }if($workid=='' && $workid != NULL){
            dd('here');
            Session::forget("reporttracking_workid");
        }
        if((bool)$operation){
            Session::put("reporttracking_operation",$operation);
            $parametersForPrint["Operation"] = $operation;
        }else{
            if(Session::has("reporttracking_operation")){
                $operation = Session::get("reporttracking_operation");
                $parametersForPrint["Operation"] = $operation;
            }else{
                $operation = "x";
            }
        }
        if((bool)$workid){
            Session::put("reporttracking_workid",$workid);
            $parametersForPrint["Detail"] = $workid;
        }else{
            if(Session::has("reporttracking_workid")){
                $workid = Session::get("reporttracking_workid");
                $parametersForPrint["Detail"] = $workid;
            }else{
                $workid = "x";
            }
        }
        if((bool)$username){
            Session::put("reporttracking_username",$username);
            $parametersForPrint["User"] = $username;
        }else{
            if(Session::has("reporttracking_username")){
                $username = Session::get("reporttracking_username");
                $parametersForPrint["User"] = $username;
            }else{
                $username = "x";
            }
        }
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            Session::put("reporttracking_fromdate",$fromDate);
            $parametersForPrint["From Date"] = $fromDate;
        }else{
            if(Session::has("reporttracking_fromdate")){
                $fromDate = Session::get("reporttracking_fromdate");
                $parametersForPrint["From Date"] = $fromDate;
            }else{
                $fromDate = "x";
            }
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            Session::put("reporttracking_todate",$toDate);
            $parametersForPrint["To Date"] = $toDate;
        }else{
            if(Session::has("reporttracking_todate")){
                $toDate = Session::get("reporttracking_todate");
                $parametersForPrint["To Date"] = $toDate;
            }else{
                $toDate = "x";
            }
        }
        $operations = DB::table("tblworkidtrack")->select("operation")->orderBy("operation")->distinct()->get();
        $users = DB::table("tblworkidtrack")->select("username")->orderBy("username")->distinct()->get();
        $reportData = DB::table("tblworkidtrack")
                        ->orderBy("op_time","desc")
                        ->select("workid","username","operation","op_time")
                        ->whereRaw("case '$operation' when 'x' then 1=1 else operation='$operation' end")
                        ->whereRaw("case '$username' when 'x' then 1=1 else username like '$username' end")
                        ->whereRaw("case '$workid' when 'x' then 1=1 else workid like '%$workid%' end")
                        ->whereRaw("case '$fromDate' when 'x' then 1=1 else CAST(op_time as DATE)>='$fromDate' end")
                        ->whereRaw("case '$toDate' when 'x' then 1=1 else CAST(op_time as DATE)<='$toDate' end")
                        ->paginate(Input::has('RecordsPerPage')?Input::get('RecordsPerPage'):200);
        return View::make('report.reporttracking')
                    ->with("parametersForPrint",$parametersForPrint)
                    ->with("users",$users)
                    ->with("operations",$operations)
                    ->with('reportData',$reportData);
    }
}