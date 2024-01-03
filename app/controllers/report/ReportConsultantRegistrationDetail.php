<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/2/2016
 * Time: 12:10 PM
 */
class ReportConsultantRegistrationDetail extends ReportController
{
    public function getIndex(){
        $parameters = array();
        $parametersForPrint = array();
        $hasParams = false;

        $cdbNo = Input::get('CDBNo');
        $consultantName = Input::get('ConsultantName');

        $query="select T1.ApplicationDate,coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpconsultantappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpConsultantId = T1.Id) end as Type,(select A.Name from crpconsultantfinal T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id or T2.Id = T3.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, T1.PaymentApprovedDate from crpconsultant T1 left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpconsultant T3 on T3.Id = T1.CrpConsultantId where 1";

        if((bool)$cdbNo){
            $hasParams = true;
            $parametersForPrint['CDB No'] = $cdbNo;
            $query.=" and (T1.CDBNo = ? or T3.CDBNo = ?)";
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
        }

        if((bool)$consultantName){
            $parametersForPrint['Firm Name'] = $consultantName;
            $hasParams = true;
            $query.=" and (T1.NameOfFirm like '%$consultantName%' or T3.NameOfFirm like '%$consultantName%')";
        }
        if($hasParams){
            $reportData = DB::select("$query order by T1.CreatedOn",$parameters);
        }else{
            $reportData = array();
        }


        return View::make('report.consultantregistrationdetail')
                    ->with('reportData',$reportData)
                    ->with('parametersForPrint',$parametersForPrint);
    }
}