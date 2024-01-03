<?php

class ProgressReport extends ReportController{
    public function getIndex(){
        $parameters = array();
        $query1 = "select count(distinct(T1.Id)) as L from crpcontractor T1 join (crpcontractorworkclassification T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 1) on T1.Id = T2.CrpContractorId where T1.CrpContractorId is null";
        $query2 = "select count(distinct(T1.Id)) as M from crpcontractor T1 join (crpcontractorworkclassification T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 2) on T1.Id = T2.CrpContractorId where T1.CrpContractorId is null";
        $query3 = "select count(distinct(T1.Id)) as S from crpcontractor T1 join (crpcontractorworkclassification T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 3) on T1.Id = T2.CrpContractorId where T1.CrpContractorId is null";
        $query4 = "select count(distinct(T1.Id)) as R from crpcontractor T1 join (crpcontractorworkclassification T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 4) on T1.Id = T2.CrpContractorId where T1.CrpContractorId is null";
        $newRegistrationLarge = DB::select($query1,$parameters);
        $newRegistrationMedium = DB::select($query2,$parameters);
        $newRegistrationSmall = DB::select($query3,$parameters);
        $newRegistrationRegistered = DB::select($query4,$parameters);

        $parameters2 = array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
        $query5 = "select count(distinct(T1.Id)) as L from crpcontractorfinal T1 join (crpcontractorworkclassificationfinal T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 1) on T1.Id = T2.CrpContractorFinalId where T1.CmnApplicationRegistrationStatusId = ?";
        $query6 = "select count(distinct(T1.Id)) as M from crpcontractorfinal T1 join (crpcontractorworkclassificationfinal T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 2) on T1.Id = T2.CrpContractorFinalId where T1.CmnApplicationRegistrationStatusId = ?";
        $query7 = "select count(distinct(T1.Id)) as S from crpcontractorfinal T1 join (crpcontractorworkclassificationfinal T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 3) on T1.Id = T2.CrpContractorFinalId where T1.CmnApplicationRegistrationStatusId = ?";
        $query8 = "select count(distinct(T1.Id)) as R from crpcontractorfinal T1 join (crpcontractorworkclassificationfinal T2 join cmncontractorworkcategory T3 on T2.CmnProjectCategoryId join cmncontractorclassification T4 on T2.CmnApprovedClassificationId = T4.Id and T4.ReferenceNo = 4) on T1.Id = T2.CrpContractorFinalId where T1.CmnApplicationRegistrationStatusId = ?";
        $deRegisteredLarge = DB::select($query5,$parameters2);
        $deRegisteredMedium = DB::select($query6,$parameters2);
        $deRegisteredSmall = DB::select($query7,$parameters2);
        $deRegisteredRegistered = DB::select($query8,$parameters2);
    }
}