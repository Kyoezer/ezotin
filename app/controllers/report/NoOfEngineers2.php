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


        $electricalDegreeContractor = DB::select("SELECT COUNT(T1.Id) AS ElectricalDegree FROM crpcontractorhumanresourcefinal T1 JOIN crpcontractorfinal A ON A.Id = T1.CrpContractorFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de031db0-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ");
        $electricalDegreeConsultant = DB::select("SELECT COUNT(T1.Id) AS ElectricalDegree FROM crpconsultanthumanresourcefinal T1 JOIN crpconsultantfinal A ON A.Id = T1.CrpConsultantFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de031db0-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ");
        $electricalDegree = $electricalDegreeContractor[0]->ElectricalDegree + $electricalDegreeConsultant[0]->ElectricalDegree;

        $electricalDegreeBhutaneseContractor = DB::select("SELECT COUNT(T1.Id) AS ElectricalDegree FROM crpcontractorhumanresourcefinal T1 JOIN crpcontractorfinal A ON A.Id = T1.CrpContractorFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de031db0-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' AND T1.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' ");
        $electricalDegreeBhutaneseConsultant = DB::select("SELECT COUNT(T1.Id) AS ElectricalDegree FROM crpconsultanthumanresourcefinal T1 JOIN crpconsultantfinal A ON A.Id = T1.CrpConsultantFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de031db0-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' AND T1.CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6' ");
        $electricalDegreeBhutanese = $electricalDegreeBhutaneseContractor[0]->ElectricalDegree + $electricalDegreeBhutaneseConsultant[0]->ElectricalDegree;
        $electricalDegreeNonBhutanese = (int)$electricalDegree - (int)$electricalDegreeBhutanese;


        $electricalDiplomaContractor = DB::select("Select COUNT(T1.Id) AS ElectricalDiploma FROM crpcontractorhumanresourcefinal T1 JOIN crpcontractorfinal A ON A.Id = T1.CrpContractorFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de03261f-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ");
        $electricalDiplomaConsultant = DB::select("Select COUNT(T1.Id) AS ElectricalDiploma FROM crpconsultanthumanresourcefinal T1 JOIN crpconsultantfinal A ON A.Id = T1.CrpConsultantFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de03261f-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ");
        $electricalDiploma = $electricalDiplomaContractor[0]->ElectricalDiploma + $electricalDiplomaConsultant[0]->ElectricalDiploma;

        $electricalDiplomaBhutaneseContractor = DB::select("SELECT COUNT(T1.Id) AS ElectricalDiploma FROM crpcontractorhumanresourcefinal T1 JOIN crpcontractorfinal A ON A.Id = T1.CrpContractorFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de03261f-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ");
        $electricalDiplomaBhutaneseConsultant = DB::select("SELECT COUNT(T1.Id) AS ElectricalDiploma FROM crpconsultanthumanresourcefinal T1 JOIN crpconsultantfinal A ON A.Id = T1.CrpConsultantFinalId JOIN cmnlistitem T2 ON T1.CmnDesignationId = T2.Id AND T2.Id = '030af1d9-24af-11e6-967f-9c2a70cc8e06' JOIN cmnlistitem T3 ON T3.Id = T1.CmnQualificationId AND T3.Id = 'de03261f-24ae-11e6-967f-9c2a70cc8e06' AND A.CmnApplicationRegistrationStatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6' ");
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