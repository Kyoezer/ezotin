<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 3/3/2016
 * Time: 2:56 PM
 */
class ReportContractorHistory extends ReportController
{
    public function getIndex(){
        $reportData = array();
        $parameters = array();
        $parametersForPrint = array();
        $cdbNo = Input::get('CDBNo');

        $query = "SELECT T1.ai_CDBRegNum as CDBNo, T2.NameOfFirm, T1.ai_rg_type as Action, T1.ai_w1_Approved as W1, T1.ai_w2_Approved as W2, T1.ai_w3_Approved as W3, T1.ai_w4_Approved as W4, T1.ai_appDate as ApplicationDate from applicationhistory T1 join crpcontractorfinal T2 on T2.CDBNo = T1.ai_CDBRegNum where 1";

        if((bool)$cdbNo){
            $query.=" and T1.ai_CDBRegNum = ?";
            $parametersForPrint['CDB No.'] = $cdbNo;
            array_push($parameters,$cdbNo);
            $reportData = DB::select("$query order by T1.ai_CDBRegNum,T1.ai_appDate desc",$parameters);
        }
        if(Request::segment(1) =='contractor'){
            $master = 'master';
        }else{
            $master = 'reportsmaster';
        }

        return View::make('report.contractorhistory')
                ->with('master',$master)
                ->with('parametersForPrint',$parametersForPrint)
                ->with('reportData',$reportData);
    }
}