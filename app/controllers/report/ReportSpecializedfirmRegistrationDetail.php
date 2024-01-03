<?php


class ReportSpecializedfirmRegistrationDetail extends ReportController
{
    public function getIndex(){
        $parameters = array();
        $parametersForPrint = array();
        $hasParams = false;

        $cdbNo = Input::get('SPNo');
        $specializedfirmName = Input::get('SpecializedfirmName');

        $query="select T1.ApplicationDate,coalesce(T1.SPNo,T3.SPNo) as SPNo,T1.NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpspecializedfirmappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpSpecializedTradeId = T1.Id) end as Type,(select A.Name from crpspecializedfirmfinal T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id or T2.Id = T3.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, T1.PaymentApprovedDate from crpspecializedfirm T1 left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpspecializedfirm T3 on T3.Id = T1.CrpSpecializedTradeId where 1";

        if((bool)$cdbNo){
            $hasParams = true;
            $parametersForPrint['SP No'] = $cdbNo;
            $query.=" and (T1.SPNo = ? or T3.SPNo = ?)";
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
        }

        if((bool)$specializedfirmName){
            $parametersForPrint['Firm Name'] = $specializedfirmName;
            $hasParams = true;
            $query.=" and (T1.NameOfFirm like '%$specializedfirmName%' or T3.NameOfFirm like '%$specializedfirmName%')";
        }
        if($hasParams){
            $reportData = DB::select("$query order by T1.CreatedOn",$parameters);
        }else{
            $reportData = array();
        }


        return View::make('report.specializedfirmregistrationdetail')
                    ->with('reportData',$reportData)
                    ->with('parametersForPrint',$parametersForPrint);
    }
}