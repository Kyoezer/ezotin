<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/2/2016
 * Time: 12:10 PM
 */
class ReportArchitectRegistrationDetail extends ReportController
{
    public function getIndex(){
        $parameters = array();
        $parametersForPrint = array();
        $hasParams = false;

        $arNo = Input::get('ARNo');
        $architectName = Input::get('ArchitectName');

        $query="select T1.ApplicationDate,coalesce(T1.ARNo,T3.ARNo) as ARNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crparchitectappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpArchitectId = T1.Id) end as Type,(select A.Name from crparchitectfinal T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id or T2.Id = T3.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, T1.PaymentApprovedDate from crparchitect T1 left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crparchitect T3 on T3.Id = T1.CrpArchitectId where 1";

        if((bool)$arNo){
            $hasParams = true;
            $parametersForPrint['AR No'] = $arNo;
            $query.=" and (T1.ARNo = ? or T3.ARNo = ?)";
            array_push($parameters,$arNo);
            array_push($parameters,$arNo);
        }

        if((bool)$architectName){
            $parametersForPrint['Name'] = $architectName;
            $hasParams = true;
            $query.=" and (T1.Name like '%$architectName%' or T3.Name like '%$architectName%')";
        }
        if($hasParams){
            $reportData = DB::select("$query order by T1.CreatedOn",$parameters);
        }else{
            $reportData = array();
        }


        return View::make('report.architectregistrationdetail')
                    ->with('reportData',$reportData)
                    ->with('parametersForPrint',$parametersForPrint);
    }
}