<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/2/2016
 * Time: 12:10 PM
 */
class ReportContractorRegistrationDetail extends ReportController
{
    public function getIndex(){
        $parameters = array();
        $parameters2 = array();
        $parametersForPrint = array();
        $hasParams = false;
        $reportDataContractor = array();
        $reportDataConsultant = array();
        $reportDataArchitect = array();
        $reportDataEngineer = array();
        $reportDataSP = array();
        $statuses = CmnListItemModel::registrationStatus()->whereIn('cmnlistitem.ReferenceNo',array('12003','12001','12004'))->get(array('Id','Name'));
        $services = DB::table('crpservice')->orderBy('Name')->get(array('Name'));
        $cdbNo = Input::get('CDBNo');
        $name = Input::get('Name');
        $user = Input::get('SysUserId');
        $thirtyDaysEarlier = date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("31 days"));

        $fromDate = Input::has('FromDate')?Input::get('FromDate'):date_format($thirtyDaysEarlier,'Y-m-d');
        $toDate = Input::get('ToDate');
        $status = Input::get('Status');
        $applicantType = Input::get('ApplicantType');
        $serviceType = Input::get('ServiceType');
        $referenceNo = Input::get('ReferenceNo');
        $users = DB::select("select distinct T1.Id, T1.FullName from sysuser T1 join crpcontractor T2 on T1.Id = T2.SysVerifierUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpcontractor T2 on T1.Id = T2.SysApproverUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpcontractor T2 on T1.Id = T2.SysPaymentApproverUserId UNION
                            select distinct T1.Id, T1.FullName from sysuser T1 join crpconsultant T2 on T1.Id = T2.SysVerifierUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpconsultant T2 on T1.Id = T2.SysApproverUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpconsultant T2 on T1.Id = T2.SysPaymentApproverUserId UNION
                            select distinct T1.Id, T1.FullName from sysuser T1 join crparchitect T2 on T1.Id = T2.SysVerifierUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crparchitect T2 on T1.Id = T2.SysApproverUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crparchitect T2 on T1.Id = T2.SysPaymentApproverUserId UNION
                            select distinct T1.Id, T1.FullName from sysuser T1 join crpengineer T2 on T1.Id = T2.SysVerifierUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpengineer T2 on T1.Id = T2.SysApproverUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpengineer T2 on T1.Id = T2.SysPaymentApproverUserId UNION
                            select distinct T1.Id, T1.FullName from sysuser T1 join crpspecializedtrade T2 on T1.Id = T2.SysVerifierUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpspecializedtrade T2 on T1.Id = T2.SysApproverUserId
                            union select distinct T1.Id, T1.FullName from sysuser T1 join crpspecializedtrade T2 on T1.Id = T2.SysPaymentApproverUserId");


        if((bool)$applicantType){

            if((int)$applicantType == 1){
                $parametersForPrint['Applicant Type'] = 'Contractor';
                $query="select 'Contractor' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpcontractorappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpContractorId = T1.Id) end as Type,(select A.Name from crpcontractor T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.RegistrationVerifiedDate) as PaymentApprovedDate from crpcontractor T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejecterUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpcontractor T3 on T3.Id = T1.CrpContractorId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            }elseif((int)$applicantType == 2){
                $parametersForPrint['Applicant Type'] = 'Consultant';
                $query2="select 'Consultant' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpconsultantappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpConsultantId = T1.Id) end as Type,(select A.Name from crpconsultant T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crpconsultant T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpconsultant T3 on T3.Id = T1.CrpConsultantId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            }elseif((int)$applicantType == 3){
                $parametersForPrint['Applicant Type'] = 'Architect';
                $query3="select 'Architect' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.ARNo,T3.ARNo) as CDBNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crparchitectappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpArchitectId = T1.Id) end as Type,(select A.Name from crparchitect T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crparchitect T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crparchitect T3 on T3.Id = T1.CrpArchitectId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            }elseif((int)$applicantType == 4){
                $parametersForPrint['Applicant Type'] = 'Specialized Trade';
                $query4="select 'Specialized Trade' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.SPNo,T3.SPNo) as CDBNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpspecializedtradeappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpSpecializedTradeId = T1.Id) end as Type,(select A.Name from crpspecializedtrade T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crpspecializedtrade T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpspecializedtrade T3 on T3.Id = T1.CrpSpecializedTradeId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            }else{
                $parametersForPrint['Applicant Type'] = 'Engineer';
                $query5="select 'Engineer' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpengineerappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpEngineerId = T1.Id) end as Type,(select A.Name from crpengineer T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crpengineer T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpengineer T3 on T3.Id = T1.CrpEngineerId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            }
        }else{
            $query="select 'Contractor' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpcontractorappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpContractorId = T1.Id) end as Type,(select A.Name from crpcontractor T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.RegistrationVerifiedDate) as PaymentApprovedDate from crpcontractor T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejecterUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpcontractor T3 on T3.Id = T1.CrpContractorId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            $query2="select 'Consultant' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpconsultantappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpConsultantId = T1.Id) end as Type,(select A.Name from crpconsultant T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crpconsultant T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpconsultant T3 on T3.Id = T1.CrpConsultantId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            $query3="select 'Architect' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.ARNo,T3.ARNo) as CDBNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crparchitectappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpArchitectId = T1.Id) end as Type,(select A.Name from crparchitect T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crparchitect T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crparchitect T3 on T3.Id = T1.CrpArchitectId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            $query4="select 'Specialized Trade' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.SPNo,T3.SPNo) as CDBNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpspecializedtradeappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpSpecializedTradeId = T1.Id) end as Type,(select A.Name from crpspecializedtrade T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crpspecializedtrade T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpspecializedtrade T3 on T3.Id = T1.CrpSpecializedTradeId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
            $query5="select 'Engineer' as ApplicantType,(select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = T1.Id order by X.ActionDate) as HistoryDetails,T1.ReferenceNo, T1.RemarksByRejector, T1.ApplicationDate,T1.MobileNo,G.FullName as PickedByUser, coalesce(T1.CDBNo,T3.CDBNo) as CDBNo,T1.Name as NameOfFirm,case when T3.Id is null then 'Registration' else (select group_concat(B.Name SEPARATOR ', ') from crpengineerappliedservice C join crpservice B on C.CmnServiceTypeId = B.Id where C.CrpEngineerId = T1.Id) end as Type,(select A.Name from crpengineer T2 left join cmnlistitem A on A.Id = T2.CmnApplicationRegistrationStatusId where T2.Id = T1.Id) as Status,B.FullName as Verifier,T1.VerifiedDate as RegistrationVerifiedDate, C.FullName as Approver, T1.RegistrationApprovedDate, D.FullName as Rejector, T1.RejectedDate, E.FullName as PaymentApprover, coalesce(T1.PaymentApprovedDate,T1.VerifiedDate) as PaymentApprovedDate from crpengineer T1 left join sysuser G on G.Id = T1.SysLockedByUserId left join sysuser B on B.Id = T1.SysVerifierUserId left join sysuser C on C.Id = T1.SysApproverUserId left join sysuser D on D.Id = T1.SysRejectorUserId left join sysuser E on E.Id = T1.SysPaymentApproverUserId left join crpengineer T3 on T3.Id = T1.CrpEngineerId where T1.ApplicationDate >= '2016-06-01' and coalesce(T1.RegistrationStatus,0) = 1";
        }




        if((bool)$cdbNo){
            $hasParams = true;
            $parametersForPrint['CDB No'] = $cdbNo;
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    $query.=" and (T1.CDBNo = '$cdbNo' or T3.CDBNo = '$cdbNo')";
                }elseif((int)$applicantType == 2){
                    $query2.=" and (T1.CDBNo = '$cdbNo' or T3.CDBNo = '$cdbNo')";
                }elseif((int)$applicantType == 3){
                    $query3.=" and (T1.ARNo = '$cdbNo' or T3.ARNo = '$cdbNo')";
                }elseif((int)$applicantType == 4){
                    $query4.=" and (T1.SPNo = '$cdbNo' or T3.SPNo = '$cdbNo')";
                }else{
                    $query5.=" and (T1.CDBNo = '$cdbNo' or T3.CDBNo = '$cdbNo')";
                }
            }else{
                $query.=" and (T1.CDBNo = '$cdbNo' or T3.CDBNo = '$cdbNo')";
                $query2.=" and (T1.CDBNo = '$cdbNo' or T3.CDBNo = '$cdbNo')";
                $query3.=" and (T1.ARNo = '$cdbNo' or T3.ARNo = '$cdbNo')";
                $query4.=" and (T1.SPNo = '$cdbNo' or T3.SPNo = '$cdbNo')";
                $query5.=" and (T1.CDBNo = '$cdbNo' or T3.CDBNo = '$cdbNo')";
            }
        }

        if((bool)$referenceNo){
            $hasParams = true;
            $parametersForPrint['Application No'] = $referenceNo;
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    $query.=" and T1.ReferenceNo = '$referenceNo'";
                }elseif((int)$applicantType == 2){
                    $query2.=" and T1.ReferenceNo = '$referenceNo'";
                }elseif((int)$applicantType == 3){
                    $query3.=" and T1.ReferenceNo = '$referenceNo'";
                }elseif((int)$applicantType == 4){
                    $query4.=" and T1.ReferenceNo = '$referenceNo'";
                }else{
                    $query5.=" and T1.ReferenceNo = '$referenceNo'";
                }
            }else{
                $query.=" and T1.ReferenceNo = '$referenceNo'";
                $query2.=" and T1.ReferenceNo = '$referenceNo'";
                $query3.=" and T1.ReferenceNo = '$referenceNo'";
                $query4.=" and T1.ReferenceNo = '$referenceNo'";
                $query5.=" and T1.ReferenceNo = '$referenceNo'";
            }
        }
        if((bool)$name){
            $parametersForPrint['Name'] = $name;
            $hasParams = true;
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    $query.=" and (T1.NameOfFirm like '%$name%' or T3.NameOfFirm like '%$name%')";
                }elseif((int)$applicantType == 2){
                    $query2.=" and (T1.NameOfFirm like '%$name%' or T3.NameOfFirm like '%$name%')";
                }elseif((int)$applicantType == 3){
                    $query3.=" and (T1.Name like '%$name%' or T3.Name like '%$name%')";
                }elseif((int)$applicantType == 4){
                    $query4.=" and (T1.Name like '%$name%' or T3.Name like '%$name%')";
                }else{
                    $query5.=" and (T1.Name like '%$name%' or T3.Name like '%$name%')";
                }
            }else{
                $query.=" and (T1.NameOfFirm like '%$name%' or T3.NameOfFirm like '%$name%')";
                $query2.=" and (T1.NameOfFirm like '%$name%' or T3.NameOfFirm like '%$name%')";
                $query3.=" and (T1.Name like '%$name%' or T3.Name like '%$name%')";
                $query4.=" and (T1.Name like '%$name%' or T3.Name like '%$name%')";
                $query5.=" and (T1.Name like '%$name%' or T3.Name like '%$name%')";
            }

        }
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $hasParams = true;
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    $query.=" and (T1.ApplicationDate >= '$fromDate' or T1.RegistrationVerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                }elseif((int)$applicantType == 2){
                    $query2.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                }elseif((int)$applicantType == 3){
                    $query3.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                }elseif((int)$applicantType == 4){
                    $query4.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                }else{
                    $query5.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                }
            }else{
                $query.=" and (T1.ApplicationDate >= '$fromDate' or T1.RegistrationVerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                $query2.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                $query3.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                $query4.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
                $query5.=" and (T1.ApplicationDate >= '$fromDate' or T1.VerifiedDate >= '$fromDate' or T1.RegistrationApprovedDate >= '$fromDate' or T1.RejectedDate >= '$fromDate' or T1.PaymentApprovedDate >= '$fromDate')";
            }

        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $hasParams = true;
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    $query.=" and (T1.ApplicationDate <='$toDate' or T1.RegistrationVerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                }elseif((int)$applicantType == 2){
                    $query2.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                }elseif((int)$applicantType == 3){
                    $query3.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                }elseif((int)$applicantType == 4){
                    $query4.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                }else{
                    $query5.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                }
            }else{
                $query.=" and (T1.ApplicationDate <='$toDate' or T1.RegistrationVerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                $query2.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                $query3.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                $query4.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
                $query5.=" and (T1.ApplicationDate <='$toDate' or T1.VerifiedDate <= '$toDate' or T1.RegistrationApprovedDate <= '$toDate' or T1.RejectedDate <= '$toDate' or T1.PaymentApprovedDate <= '$toDate')";
            }

        }
        if((bool)$status){
            $hasParams = true;
            if($status != "All"){
                $parametersForPrint['Status'] = DB::table('cmnlistitem')->where('CmnListId',CONST_CMN_REFERENCE_REGISTRATIONSTATUS)->where('Id',$status)->pluck('Name');
                if((bool)$applicantType){
                    if((int)$applicantType == 1){
                        $query.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    }elseif((int)$applicantType == 2){
                        $query2.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    }elseif((int)$applicantType == 3){
                        $query3.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    }elseif((int)$applicantType == 4){
                        $query4.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    }else{
                        $query5.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    }
                }else{
                    $query.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    $query2.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    $query3.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    $query4.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                    $query5.=" and T1.CmnApplicationRegistrationStatusId = '$status'";
                }
            }

        }
        if((bool)$user){
            $hasParams = true;
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    $query.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejecterUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                }elseif((int)$applicantType == 2){
                    $query2.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                }elseif((int)$applicantType == 3){
                    $query3.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                }elseif((int)$applicantType == 4){
                    $query4.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                }else{
                    $query5.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                }
            }else{
                $query.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejecterUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                $query2.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                $query3.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                $query4.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
                $query5.=" and (T1.SysVerifierUserId = '$user' or T1.SysApproverUserId = '$user' or T1.SysRejectorUserId = '$user' or T1.SysPaymentApproverUserId = '$user')";
            }
        }
        if((bool)$serviceType){
            $parametersForPrint['Service Type'] = $serviceType;
            $hasParams = true;
        }
        if($hasParams){
            if((bool)$applicantType){
                if((int)$applicantType == 1){
                    //$query = "$query and (T1.RegistrationVerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
                    if((bool)$serviceType){
                        $query.=" having Type like '%$serviceType%'";
                    }
                    $query.="  order by ApplicationDate desc";
                    $reportDataContractor = DB::select($query);
                }elseif((int)$applicantType == 2){
                    //$query2="$query2 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
                    if((bool)$serviceType){
                        $query2.=" having Type like '%$serviceType%'";
                    }
                    $query2.="  order by ApplicationDate desc";
                    $reportDataConsultant = DB::select($query2);
                }elseif((int)$applicantType == 3){
                    //$query3="$query3 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
                    if((bool)$serviceType){
                        $query3.=" having Type like '%$serviceType%'";
                    }
                    $query3.="  order by ApplicationDate desc";
                    $reportDataArchitect = DB::select($query3);
                }elseif((int)$applicantType == 4){
                    //$query4="$query4 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
                    if((bool)$serviceType){
                        $query4.=" having Type like '%$serviceType%'";
                    }
                    $query4.="  order by ApplicationDate desc";
                    $reportDataSP = DB::select($query4);
                }else{
                    //$query5="$query5 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
                    if((bool)$serviceType){
                        $query5.=" having Type like '%$serviceType%'";
                    }
                    $query5.="  order by ApplicationDate desc";
                    $reportDataEngineer = DB::select($query5);
                }
            }else{
//                $query = "$query and (T1.RegistrationVerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
//                $query2 = "$query2 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
//                $query3 = "$query3 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
//                $query4 = "$query4 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";
//                $query5 = "$query5 and (T1.VerifiedDate is not null or T1.RegistrationApprovedDate is not null or T1.RejectedDate is not null or T1.PaymentApprovedDate is not null)";

                if((bool)$serviceType){
                    $query.=" having Type like '%$serviceType%'";
                }
                $query.="  order by ApplicationDate desc";
                if((bool)$serviceType){
                    $query2.=" having Type like '%$serviceType%'";
                }
                $query2.="  order by ApplicationDate desc";
                if((bool)$serviceType){
                    $query3.=" having Type like '%$serviceType%'";
                }
                $query3.="  order by ApplicationDate desc";
                if((bool)$serviceType){
                    $query4.=" having Type like '%$serviceType%'";
                }
                $query4.="  order by ApplicationDate desc";
                if((bool)$serviceType){
                    $query5.=" having Type like '%$serviceType%'";
                }
                $query5.="  order by ApplicationDate desc";
                $reportDataContractor = DB::select($query);
                $reportDataConsultant = DB::select($query2);
                $reportDataArchitect = DB::select($query3);
                $reportDataSP = DB::select($query4);
                $reportDataEngineer = DB::select($query5);
            }
        }

        if(Input::get('export') == 'excel'){
            Excel::create('Ezotin Application Details', function($excel) use ($reportDataContractor,$reportDataConsultant,$reportDataArchitect,$reportDataSP,$reportDataEngineer,$parametersForPrint) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportDataContractor,$reportDataConsultant,$reportDataArchitect,$reportDataSP,$reportDataEngineer,$parametersForPrint) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->setPaperSize(1);
                    $sheet->loadView('reportexcel.ezotinapplicationdetails')
                        ->with('reportDataContractor',$reportDataContractor)
                        ->with('reportDataConsultant',$reportDataConsultant)
                        ->with('reportDataArchitect',$reportDataArchitect)
                        ->with('reportDataSP',$reportDataSP)
                        ->with('reportDataEngineer',$reportDataEngineer)
                        ->with('parametersForPrint',$parametersForPrint);

                });

            })->export('xlsx');
        }


        return View::make('report.contractorregistrationdetail')
                    ->with('fromDate',$fromDate)
                    ->with('statuses',$statuses)
                    ->with('services',$services)
                    ->with('users',$users)
                    ->with('reportDataContractor',$reportDataContractor)
                    ->with('reportDataConsultant',$reportDataConsultant)
                    ->with('reportDataArchitect',$reportDataArchitect)
                    ->with('reportDataSP',$reportDataSP)
                    ->with('reportDataEngineer',$reportDataEngineer)
                    ->with('parametersForPrint',$parametersForPrint);
    }
}