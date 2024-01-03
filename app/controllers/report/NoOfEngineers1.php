<?php

class NoOfEngineers extends ReportController{
    public function getIndex(){
        $civilDegreeContractor = DB::select("select count(T1.Id) as CivilDegree from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $civilDegreeConsultant = DB::select("select count(T1.Id) as CivilDegree from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $civilDegree = $civilDegreeConsultant[0]->CivilDegree + $civilDegreeContractor[0]->CivilDegree;

        $civilDegreeBhutaneseContractor = DB::select("select count(T1.Id) as CivilDegree from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'  and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $civilDegreeBhutaneseConsultant = DB::select("select count(T1.Id) as CivilDegree from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $civilDegreeBhutanese = $civilDegreeBhutaneseConsultant[0]->CivilDegree + $civilDegreeBhutaneseContractor[0]->CivilDegree;
        $civilDegreeNonBhutanese = (int)$civilDegree - (int)$civilDegreeBhutanese;

        $civilDiplomaContractor = DB::select("select count(T1.Id) as CivilDiploma from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $civilDiplomaConsultant = DB::select("select count(T1.Id) as CivilDiploma from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $civilDiploma = $civilDiplomaConsultant[0]->CivilDiploma + $civilDiplomaContractor[0]->CivilDiploma;

        $civilDiplomaBhutaneseContractor = DB::select("select count(T1.Id) as CivilDiploma from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $civilDiplomaBhutaneseConsultant = DB::select("select count(T1.Id) as CivilDiploma from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4003 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $civilDiplomaBhutanese = $civilDiplomaBhutaneseContractor[0]->CivilDiploma + $civilDiplomaBhutaneseConsultant[0]->CivilDiploma;
        $civilDiplomaNonBhutanese = (int)$civilDiploma - (int)$civilDiplomaBhutanese;


        $electricalDegreeContractor = DB::select("select count(T1.Id) as ElectricalDegree from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $electricalDegreeConsultant = DB::select("select count(T1.Id) as ElectricalDegree from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $electricalDegree = $electricalDegreeContractor[0]->ElectricalDegree + $electricalDegreeConsultant[0]->ElectricalDegree;

        $electricalDegreeBhutaneseContractor = DB::select("select count(T1.Id) as ElectricalDegree from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $electricalDegreeBhutaneseConsultant = DB::select("select count(T1.Id) as ElectricalDegree from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2001 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $electricalDegreeBhutanese = $electricalDegreeBhutaneseContractor[0]->ElectricalDegree + $electricalDegreeBhutaneseConsultant[0]->ElectricalDegree;
        $electricalDegreeNonBhutanese = (int)$electricalDegree - (int)$electricalDegreeBhutanese;


        $electricalDiplomaContractor = DB::select("select count(T1.Id) as ElectricalDiploma from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $electricalDiplomaConsultant = DB::select("select count(T1.Id) as ElectricalDiploma from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
        $electricalDiploma = $electricalDiplomaContractor[0]->ElectricalDiploma + $electricalDiplomaConsultant[0]->ElectricalDiploma;

        $electricalDiplomaBhutaneseContractor = DB::select("select count(T1.Id) as ElectricalDiploma from crpcontractorhumanresourcefinal T1 join crpcontractorfinal A on A.Id = T1.CrpContractorFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $electricalDiplomaBhutaneseConsultant = DB::select("select count(T1.Id) as ElectricalDiploma from crpconsultanthumanresourcefinal T1 join crpconsultantfinal A on A.Id = T1.CrpConsultantFinalId join cmnlistitem T2 on T1.CmnTradeId = T2.Id and T2.ReferenceNo = 4002 join cmnlistitem T3 on T3.Id = T1.CmnQualificationId and T3.ReferenceNo = 2002 and A.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' and T1.CmnCountryId = ?",array(CONST_COUNTRY_BHUTAN));
        $electricalDiplomaBhutanese = $electricalDiplomaBhutaneseContractor[0]->ElectricalDiploma + $electricalDiplomaBhutaneseConsultant[0]->ElectricalDiploma;
        $electricalDiplomaNonBhutanese = (int)$electricalDiploma - (int)$electricalDiplomaBhutanese;
        return View::make('report.noofengineers')
                ->with('civilDegree',$civilDegree)
                ->with('civilDegreeBhutanese',$civilDegreeBhutanese)
                ->with('civilDegreeNonBhutanese',$civilDegreeNonBhutanese)
                ->with('civilDiploma',$civilDiploma)
                ->with('civilDiplomaBhutanese',$civilDiplomaBhutanese)
                ->with('civilDiplomaNonBhutanese',$civilDiplomaNonBhutanese)
                ->with('electricalDegree',$electricalDegree)
                ->with('electricalDegreeBhutanese',$electricalDegreeBhutanese)
                ->with('electricalDegreeNonBhutanese',$electricalDegreeNonBhutanese)
                ->with('electricalDiploma',$electricalDiploma)
                ->with('electricalDiplomaBhutanese',$electricalDiplomaBhutanese)
                ->with('electricalDiplomaNonBhutanese',$electricalDiplomaNonBhutanese);
    }
}