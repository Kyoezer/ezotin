<?php

class ReportSurveyorRegistrationDetail extends ReportController
{
    public function getIndex(){
        $parameters = array();
        $parametersForPrint = array();
        $hasParams = false;

        $arNo = Input::get('ARNo');
        $surveyName = Input::get('SurveyName');

        $query="select T1.ApplicationDate,coalesce(T1.ARNo,T3.ARNo) as ARNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpsurveyappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpSurveyId = T1.Id) end as Type,(select A.Name from crpsurveyfinal T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id or T2.Id = T3.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, T1.PaymentApprovedDate from crpsurvey T1 left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpsurvey T3 on T3.Id = T1.CrpSurveyId where 1";

        if((bool)$arNo){
            $hasParams = true;
            $parametersForPrint['AR No'] = $arNo;
            $query.=" and (T1.ARNo = ? or T3.ARNo = ?)";
            array_push($parameters,$arNo);
            array_push($parameters,$arNo);
        }

        if((bool)$surveyName){
            $parametersForPrint['Name'] = $surveyName;
            $hasParams = true;
            $query.=" and (T1.Name like '%$surveyName%' or T3.Name like '%$surveyName%')";
        }
        if($hasParams){
            $reportData = DB::select("$query order by T1.CreatedOn",$parameters);
        }else{
            $reportData = array();
        }


        return View::make('report.surveyorregistrationdetail')
                    ->with('reportData',$reportData)
                    ->with('parametersForPrint',$parametersForPrint);
    }
}