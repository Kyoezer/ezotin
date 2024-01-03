/*------------------------------------DELETE CMNLISTITEMS TABLE-------------------------------------------------------------------*/
DELETE FROM cdb_local.cmnlistitem WHERE CmnListId='ff4e55ee-a254-11e4-b4d2-080027dcfac6';/*----DELETE QUALIFICATION--*/
DELETE FROM cdb_local.cmnlistitem WHERE CmnListId='599fbfdc-a250-11e4-b4d2-080027dcfac6' AND Id!='679b760f-d9ff-11e4-b628-080027dcfac6';/*----DELETE DESIGNATION--*/
DELETE FROM cdb_local.cmnlistitem WHERE CmnListId='bf4b32e8-a256-11e4-b4d2-080027dcfac6';/*----DELETE TRADE--*/
/*------------------------------------INSERT CMNLISTITEMS TABLE-------------------------------------------------------------------*/
/*----INSERT QUALIFICATION--*/
INSERT INTO cdb_local.cmnlistitem (Id,CmnListId,Name,CreatedBy)
SELECT DISTINCT UUID(),'ff4e55ee-a254-11e4-b4d2-080027dcfac6',T1.Qualification,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.employeedetails T1;
/*----INSERT DESIGNATION--*/
INSERT INTO cdb_local.cmnlistitem (Id,CmnListId,Name,CreatedBy)
SELECT DISTINCT UUID(),'599fbfdc-a250-11e4-b4d2-080027dcfac6',T1.Designation,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.employeedetails T1;

INSERT INTO cdb_local.cmnlistitem (Id,CmnListId,Name,CreatedBy)
SELECT DISTINCT UUID(),'599fbfdc-a250-11e4-b4d2-080027dcfac6',T1.HRname,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblhrtire T1 
WHERE T1.HRName not in (select Name from cdb_local.cmnlistitem where CmnListId='599fbfdc-a250-11e4-b4d2-080027dcfac6');
/*----INSERT Trade--*/
INSERT INTO cdb_local.cmnlistitem (Id,CmnListId,Name,CreatedBy)
SELECT DISTINCT UUID(),'bf4b32e8-a256-11e4-b4d2-080027dcfac6',T1.Trade,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.employeedetails T1;
/*------------------------------------DELETE EQUIPMENT TABLE------------------------------------------------------------------------*/
DELETE FROM cdb_local.cmnequipment;
DELETE FROM cdbolddata.equipmentlistgeneral WHERE eg_key in (select eg_key from (select eg_key from cdbolddata.equipmentlistgeneral group by eg_name having count(eg_name)>1) X);
/*------------------------------------INSERT EQUIPMENT TABLE------------------------------------------------------------------------*/
INSERT INTO cdbolddata.equipmentlistgeneral (eg_name)
SELECT Equipmentname FROM tblequiptire WHERE Equipmentname not in (SELECT eg_name from cdbolddata.equipmentlistgeneral);
INSERT INTO cdb_local.cmnequipment (Id,Code,Name,IsRegistered,CreatedBy)
SELECT UUID(),@equipmentCode:=@equipmentCode+1,T2.eg_name,0,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.equipmentlistgeneral T2,(SELECT @equipmentCode:= 0) as equipmentCode;

INSERT INTO cdb_local.cmnequipment (Id,Name,IsRegistered,CreatedBy)
SELECT DISTINCT UUID(),T1.ae_Description,case T1.ae_regNumber when null then 0 else 1 end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.appequipment T1
WHERE T1.ae_Description NOT IN (SELECT Name FROM cdb_local.cmnequipment);
UPDATE cdbolddata.appequipment T1 SET ae_eg_key=(select eg_key from cdbolddata.equipmentlistgeneral where eg_name=T1.ae_Description);
/*------------------------------------UPDATE COUNTRY AND DZONGKHAG CONTRATCOR TABLE-------------------------------------------------------------------*/
UPDATE cdbolddata.contractorconsultant SET ci_RegCountry='Bhutan' WHERE trim(ci_RegCountry)='';
UPDATE cdbolddata.contractorconsultant SET ci_RegCity='Samdrup Jongkhar' WHERE ci_RegCity='Samdrup Jonkhar';
UPDATE cdbolddata.contractorconsultant SET ci_RegCity='Trashiyangtse' WHERE ci_RegCity='Trashi Yangtse';
UPDATE cdbolddata.contractorconsultant SET ci_RegCity='Wangdue Phodrang' WHERE ci_RegCity='Wangdue';

UPDATE cdbolddata.contractorconsultant SET Ci_CorCity=NULL WHERE trim(Ci_CorCity)='';
UPDATE cdbolddata.contractorconsultant SET Ci_CorCity='Samdrup Jongkhar' WHERE Ci_CorCity='Samdrup Jonkhar';
UPDATE cdbolddata.contractorconsultant SET Ci_CorCity='Trashiyangtse' WHERE Ci_CorCity='Trashi Yangtse';
UPDATE cdbolddata.contractorconsultant SET Ci_CorCity='Wangdue Phodrang' WHERE Ci_CorCity='Wangdue';

UPDATE cdbolddata.dzongkhag SET Dz_Name='Samdrup Jongkhar' WHERE Dz_Name='Samdrup Jonkhar';
UPDATE cdbolddata.dzongkhag SET Dz_Name='Trashiyangtse' WHERE Dz_Name='Trashi Yangtse';
UPDATE cdbolddata.dzongkhag SET Dz_Name='Wangdue Phodrang' WHERE Dz_Name='Wangdue';
UPDATE cdbolddata.dzongkhag SET Dz_Name='Lhuentse' WHERE Dz_Name='Lhuntse';

UPDATE cdbolddata.tblworkidt0 SET Dzongkhag='Samdrup Jongkhar' WHERE Dzongkhag='Samdrup Jonkhar';
UPDATE cdbolddata.tblworkidt0 SET Dzongkhag='Trashiyangtse' WHERE Dzongkhag='Trashi Yangtse';
UPDATE cdbolddata.tblworkidt0 SET Dzongkhag='Wangdue Phodrang' WHERE Dzongkhag='Wangdue';
UPDATE cdbolddata.tblworkidt0 SET Dzongkhag='Lhuentse' WHERE Dzongkhag='Lhuntse';

UPDATE cdbolddata.biddingform SET Dzongkhag='Samdrup Jongkhar' WHERE Dzongkhag='Samdrup Jonkhar';
UPDATE cdbolddata.biddingform SET Dzongkhag='Trashiyangtse' WHERE Dzongkhag='Trashi Yangtse';
UPDATE cdbolddata.biddingform SET Dzongkhag='Wangdue Phodrang' WHERE Dzongkhag='Wangdue';
UPDATE cdbolddata.biddingform SET Dzongkhag='Lhuentse' WHERE Dzongkhag='Lhuntse';
UPDATE cdbolddata.biddingform SET Dzongkhag='Thimphu' WHERE Dzongkhag in ('Nepal','India');

UPDATE cdbolddata.pbiddingform SET Dzongkhag='Samdrup Jongkhar' WHERE Dzongkhag='Samdrup Jonkhar';
UPDATE cdbolddata.pbiddingform SET Dzongkhag='Trashiyangtse' WHERE Dzongkhag='Trashi Yangtse';
UPDATE cdbolddata.pbiddingform SET Dzongkhag='Trashiyangtse' WHERE Dzongkhag='Trashi Tangtse';
UPDATE cdbolddata.pbiddingform SET Dzongkhag='Wangdue Phodrang' WHERE Dzongkhag='Wangdue';
UPDATE cdbolddata.pbiddingform SET Dzongkhag='Lhuentse' WHERE Dzongkhag='Lhuntse';
UPDATE cdbolddata.pbiddingform SET Dzongkhag='Thimphu' WHERE Dzongkhag in ('Nepal','India');
/*------------------------------------DELETE Duplicate contractor (DUPLICATE CDB NO)-------------------------------------------------------------------*/
DELETE FROM cdbolddata.contractorconsultant where ci_key in (6594,7238);
/*------------------------------------BHUTANESE CONTRACTORS-------------------------------------------------------------------*/
UPDATE cdbolddata.`contractorconsultant` Set Status = 'Approved' WHERE Status is NULL;
UPDATE cdbolddata.`contractorconsultant` Set Status = 'Deregistered' WHERE Status = 'De-registered';
UPDATE cdbolddata.`contractorconsultant` Set Status = 'Revoked' WHERE Status = 'Revoke';

-- INSERT INTO cdb_local.`crpcontractor`
-- (`Id`, `ReferenceNo`, `ApplicationDate`, `CDBNo`, `CmnOwnershipTypeId`, `NameOfFirm`,`RegisteredAddress`,`CmnRegisteredDzongkhagId`, `Village`, `Gewog`, `Address`, `CmnCountryId`, `CmnDzongkhagId`, `Email`, `TelephoneNo`, `MobileNo`, `FaxNo`, `RegistrationStatus`, `CmnApplicationRegistrationStatusId`, `RegistrationApprovedDate`, `RegistrationExpiryDate`, `RegistrationPaymentApprovedDate`)
-- SELECT UUID(),@referenceNo:=@referenceNo+1,InitialDT,ci_CDBRegNum,'1e243ef0-c652-11e4-b574-080027dcfac6',ci_firm,concat(ci_RegAdd1,' ',coalesce(ci_RegAdd2,'')),(select Id from cdb_local.cmndzongkhag where NameEn=T1.ci_RegCity),'','',concat(ci_CorAdd1,' ',coalesce(ci_CorAdd2,'')),coalesce((select Id from cdb_local.cmncountry where Name=coalesce(T1.ci_RegCountry,'Bhutan')),'8f897032-c6e6-11e4-b574-080027dcfac6'),(select Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Ci_CorCity,T1.ci_RegCity)),ci_Email,ci_TelNo,'',ci_FaxNo,1,'463c2d4c-adbd-11e4-99d7-080027dcfac6',ci_CDBRegDate,ci_CDBExpiryDate,ci_CDBExpiryDate
-- FROM cdbolddata.contractorconsultant T1,(SELECT @referenceNo:= 0) as referenceNo
-- WHERE T1.ci_Contractor=1;

INSERT INTO cdb_local.`crpcontractor`
(`Id`, `ReferenceNo`, `ApplicationDate`, `CDBNo`, `CmnOwnershipTypeId`, `NameOfFirm`,`RegisteredAddress`,`CmnRegisteredDzongkhagId`, `Village`, `Gewog`, `Address`, `CmnCountryId`, `CmnDzongkhagId`, `Email`, `TelephoneNo`, `MobileNo`, `FaxNo`, `RegistrationStatus`, `CmnApplicationRegistrationStatusId`, `RegistrationApprovedDate`, `RegistrationExpiryDate`, `RegistrationPaymentApprovedDate`)
SELECT UUID(),@referenceNo:=@referenceNo+1,InitialDT,ci_CDBRegNum,'1e243ef0-c652-11e4-b574-080027dcfac6',ci_firm,concat(ci_RegAdd1,' ',coalesce(ci_RegAdd2,'')),(select Id from cdb_local.cmndzongkhag where NameEn=T1.ci_RegCity),'','',concat(ci_CorAdd1,' ',coalesce(ci_CorAdd2,'')),coalesce((select Id from cdb_local.cmncountry where Name=coalesce(T1.ci_RegCountry,'Bhutan')),'8f897032-c6e6-11e4-b574-080027dcfac6'),(select Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Ci_CorCity,T1.ci_RegCity)),ci_Email,ci_TelNo,'',ci_FaxNo,1,(select Id from cdb_local.cmnlistitem where Name = T1.Status and CmnListId = 'e70f06a6-adbc-11e4-99d7-080027dcfac6'),ci_CDBRegDate,ci_CDBExpiryDate,ci_CDBExpiryDate
FROM cdbolddata.contractorconsultant T1,(SELECT @referenceNo:= 0) as referenceNo
WHERE T1.ci_Contractor=1;

UPDATE crpcontractor SET CmnOwnershipTypeId = '28da31ab-c652-11e4-b574-080027dcfac6' WHERE NameOfFirm like 'Private Limited' or NameOfFirm like '%Pvt Ltd%' or NameOfFirm like '%Pvt. Ltd.%' and CmnCountryId = '8f897032-c6e6-11e4-b574-080027dcfac6';

/*---------------------------------------INSERT NON-BHUTANESE CONTRATORS------------------------------*/

INSERT INTO cdb_local.`crpcontractor`
(`Id`, `ReferenceNo`, `ApplicationDate`, `CDBNo`, `CmnOwnershipTypeId`, `NameOfFirm`,`RegisteredAddress`,`CmnRegisteredDzongkhagId`, `Village`, `Gewog`, `Address`, `CmnCountryId`, `CmnDzongkhagId`, `Email`, `TelephoneNo`, `MobileNo`, `FaxNo`, `RegistrationStatus`, `CmnApplicationRegistrationStatusId`, `RegistrationApprovedDate`, `RegistrationExpiryDate`, `RegistrationPaymentApprovedDate`)
SELECT UUID(),@referenceNo:=coalesce(@referenceNo,0)+1,InitialDT,ci_CDBRegNum,'1e243ef0-c652-11e4-b574-080027dcfac6',ci_firm,concat(ci_RegAdd1,' ',coalesce(ci_RegAdd2,'')),(select Id from cdb_local.cmndzongkhag where NameEn=T1.ci_RegCity),'','',concat(ci_CorAdd1,' ',coalesce(ci_CorAdd2,'')),(select Id from cdb_local.cmncountry where Name=coalesce(T1.ci_RegCountry,'Bhutan')),(select Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Ci_CorCity,T1.ci_RegCity)),ci_Email,ci_TelNo,'',ci_FaxNo,1,'463c2d4c-adbd-11e4-99d7-080027dcfac6',ci_CDBRegDate,ci_CDBExpiryDate,ci_CDBExpiryDate
FROM cdbolddata.contractorconsultant T1,(SELECT @referenceNo:= (select max(ReferenceNo) from cdb_local.crpcontractor)) as referenceNo
WHERE T1.ci_Contractor!=1;

UPDATE crpcontractor SET CmnOwnershipTypeId = '63459069-mp32-11e4-b574-080027dcfac6' WHERE CmnCountryId <> '8f897032-c6e6-11e4-b574-080027dcfac6' and (NameOfFirm like '%Private Limited%' or NameOfFirm like '%Pvt Ltd%' or NameOfFirm like '%Pvt. Ltd.%');
/*------------------------------------Add comments for Contractors-----------------------------------*/
INSERT INTO cdb_local.`crpcontractorcommentsadverserecord`(`Id`, `CrpContractorFinalId`, `Date`, `Remarks`, `Type`, `CreatedBy`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpcontractor WHERE CDBNo=T1.ci_CDBRegNum),coalesce(T1.DeRegDate,CURDATE()),T1.ci_CDBComment,1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.contractorconsultant T1 WHERE T1.ci_CDBComment IS NOT NULL AND T1.ci_Contractor=1;
/*------------------------------------Add adverse records for Contractors-----------------------------------*/
INSERT INTO cdb_local.`crpcontractorcommentsadverserecord`(`Id`, `CrpContractorFinalId`, `Date`, `Remarks`, `Type`, `CreatedBy`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpcontractor WHERE CDBNo=T1.ci_CDBRegNum),coalesce(T1.AdDate,CURDATE()),T1.AdRecord,2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.contractorconsultant T1 WHERE T1.AdRecord IS NOT NULL AND T1.ci_Contractor=1;
/*------------------------------------CONTRATCOR WORK CLASSIFICATION (W1) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpcontractorworkclassification`
(`Id`, `CrpContractorId`, `CmnProjectCategoryId`, `CmnAppliedClassificationId`, `CmnVerifiedClassificationId`, `CmnApprovedClassificationId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpcontractor WHERE CDBNo=T1.ci_CDBRegNum),'6cd737d4-a2b7-11e4-b4d2-080027dcfac6',T2.Id,T2.Id,T2.Id
FROM cdbolddata.contractorconsultant T1 JOIN cdb_local.cmncontractorclassification T2 ON T1.ci_W1=T2.Code
WHERE T1.ci_W1 IN ('L','M','S','R');
/*------------------------------------CONTRATCOR WORK CLASSIFICATION (W2) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpcontractorworkclassification`
(`Id`, `CrpContractorId`, `CmnProjectCategoryId`, `CmnAppliedClassificationId`, `CmnVerifiedClassificationId`, `CmnApprovedClassificationId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpcontractor WHERE CDBNo=T1.ci_CDBRegNum),'8176bd2d-a2b7-11e4-b4d2-080027dcfac6',T2.Id,T2.Id,T2.Id
FROM cdbolddata.contractorconsultant T1 JOIN cdb_local.cmncontractorclassification T2 ON T1.ci_W2=T2.Code
WHERE T1.ci_W2 IN ('L','M','S','R'); 
/*------------------------------------CONTRATCOR WORK CLASSIFICATION (W3) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpcontractorworkclassification`
(`Id`, `CrpContractorId`, `CmnProjectCategoryId`, `CmnAppliedClassificationId`, `CmnVerifiedClassificationId`, `CmnApprovedClassificationId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpcontractor WHERE CDBNo=T1.ci_CDBRegNum),'8afc0568-a2b7-11e4-b4d2-080027dcfac6',T2.Id,T2.Id,T2.Id
FROM cdbolddata.contractorconsultant T1 JOIN cdb_local.cmncontractorclassification T2 ON T1.ci_W3=T2.Code
WHERE T1.ci_W3 IN ('L','M','S','R');
/*------------------------------------CONTRATCOR WORK CLASSIFICATION (W4) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpcontractorworkclassification`
(`Id`, `CrpContractorId`, `CmnProjectCategoryId`, `CmnAppliedClassificationId`, `CmnVerifiedClassificationId`, `CmnApprovedClassificationId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpcontractor WHERE CDBNo=T1.ci_CDBRegNum),'9090a82a-a2b7-11e4-b4d2-080027dcfac6',T2.Id,T2.Id,T2.Id
FROM cdbolddata.contractorconsultant T1 JOIN cdb_local.cmncontractorclassification T2 ON T1.ci_W4=T2.Code
WHERE T1.ci_W4 IN ('L','M','S','R');
/*------------------------------------DELETE RECORD FROM CONTRACTORPARTNER WHICH DOESNOT HAVE A PARENT(CONTRACTORCONSULTANT)-------------------------------------------------------------------*/  
DELETE FROM cdbolddata.contractorpartner WHERE cp_ci_key not in (SELECT ci_key from cdbolddata.contractorconsultant);
DELETE FROM cdbolddata.employeedetails WHERE cdbNumber not in (SELECT ci_CDBRegNum from cdbolddata.contractorconsultant);
/*------------------------------------CONTRATCOR PARTNER DETAILS TABLE-------------------------------------------------------------------*/
INSERT INTO `cdb_local`.`cmnlistitem` (`Id`, `CmnListId`, `ReferenceNo`, `Code`, `Name`, `CreatedBy`, `EditedBy`, `CreatedOn`, `EditedOn`) VALUES ('50537046-uuid-11e5-8b00-9c2a70cc8e06', '599fbfdc-a250-11e4-b4d2-080027dcfac6', NULL, NULL, 'Owner/Partner', 'bf258f4b-c639-11e4-b574-080027dcfac6', NULL, '2016-03-09 16:04:29', NULL);

INSERT INTO cdb_local.`crpcontractorhumanresource`
(`Id`, `CrpContractorId`, `CmnSalutationId`, `CIDNo`, `Name`, `Sex`, `CmnCountryId`,`CmnDesignationId`, `IsPartnerOrOwner`, `Verified`, `Approved`, CreatedBy)
SELECT UUID(),(SELECT A.Id FROM cdb_local.crpcontractor A JOIN cdbolddata.contractorconsultant B ON A.CDBNo=B.ci_CDBRegNum WHERE B.ci_key=T1.cp_ci_key and B.ci_key<>'' and T1.cp_ci_key<>''),CASE WHEN T1.cp_sex='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.cp_ID_No,T1.cp_Name,CASE WHEN T1.cp_sex='Male' THEN 'M' ELSE 'F' END,(SELECT Id FROM cdb_local.cmncountry WHERE Nationality=coalesce(T1.cp_Nationality,'Bhutanese')),'50537046-uuid-11e5-8b00-9c2a70cc8e06',1,1,1, '894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.contractorpartner T1 JOIN cdbolddata.contractorconsultant T2 ON T2.ci_key=T1.cp_ci_key where (SELECT A.Id FROM cdb_local.crpcontractor A JOIN cdbolddata.contractorconsultant B ON A.CDBNo=B.ci_CDBRegNum WHERE B.ci_key=T1.cp_ci_key and B.ci_key<>'' and T1.cp_ci_key<>'') is not null;
/*------------------------------------UPDATE ShowInCertificate CONTRATCOR PARTNER DETAILS TABLE-------------------------------------------------------------------*/  
/*--UPDATE cdb_local.`crpcontractorhumanresource` SET ShowInCertificate=1 WHERE Id=(SELECT Id FROM cdb_local.`crpcontractorhumanresource` GROUP BY CrpContractorId);--*/
/*------------------------------------CONTRATCOR HUMAN RESOURCE TABLE-------------------------------------------------------------------*/  
INSERT INTO cdb_local.`crpcontractorhumanresource`
(`Id`, `CrpContractorId`, `CmnSalutationId`, `CIDNo`, `Name`, `Sex`, `CmnCountryId`,`CmnQualificationId`,`CmnTradeId`,`JoiningDate`,`CmnDesignationId`,`Verified`, `Approved`, CreatedBy)
SELECT UUID(),(SELECT A.Id FROM cdb_local.crpcontractor A join cdbolddata.employeedetails B on TRIM(A.CDBNo)= TRIM(B.cdbNumber) where B.Pkey = T1.Pkey),CASE WHEN T1.Sex='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.IDNo,T1.EName,CASE WHEN T1.Sex='Male' THEN 'M' ELSE 'F' END,coalesce((SELECT Id FROM cdb_local.cmncountry WHERE Nationality=coalesce(T1.Nationality,'Bhutanese')),'8f897032-c6e6-11e4-b574-080027dcfac6'),(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE T1.Qualification=X.Name and CmnListId='ff4e55ee-a254-11e4-b4d2-080027dcfac6'),(SELECT Y.Id FROM cdb_local.cmnlistitem Y WHERE T1.Trade=Y.Name and CmnListId='bf4b32e8-a256-11e4-b4d2-080027dcfac6'),T1.Joindt,(SELECT Z.Id FROM cdb_local.cmnlistitem Z WHERE T1.Designation=Z.Name and CmnListId='599fbfdc-a250-11e4-b4d2-080027dcfac6'),1,1, '894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.employeedetails T1; /*WHERE T1.IDNo NOT IN (SELECT CIDNo FROM cdb_local.`crpcontractorhumanresource`);*/
/*------------------------------------SET QUALIFICATION AND TRADE TO PARTNERS IN CONTRACTOR HUMAN RESOURCE TABLE-------------------------------------------------------------------*/  

--TO DELETE DUPLICATES--
DELETE FROM cdb_local.`crpcontractorhumanresource` WHERE Id in (SELECT A.Id from (SELECT X.Id from cdb_local.crpcontractorhumanresource X group by TRIM(X.CIDNo), X.CrpContractorId having count(TRIM(CIDNo))>1) A);

UPDATE cdb_local.`crpcontractorhumanresource` T1 SET T1.CmnQualificationId=(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE X.Name=(SELECT Qualification FROM cdbolddata.employeedetails WHERE IDNo=T1.CIDNo limit 1) and X.CmnListId='ff4e55ee-a254-11e4-b4d2-080027dcfac6'),T1.CmnTradeId=(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE X.Name=(SELECT Trade FROM cdbolddata.employeedetails WHERE IDNo=T1.CIDNo limit 1) and X.CmnListId='bf4b32e8-a256-11e4-b4d2-080027dcfac6') WHERE IsPartnerOrOwner=1;

UPDATE crpcontractorhumanresource SET CIDNo = TRIM(CIDNo);
//FOR ABOVE query
SELECT Pkey,count(Qualification) FROM cdbolddata.employeedetails join cdb_local.crpcontractorhumanresource T1 WHERE IDNo=T1.CIDNo and IsPartnerOrOwner=1 group by Pkey having count(Qualification) > 1;
//
/*------------------------------------CONTRATCOR EQUIPMENT TABLE-------------------------------------------------------------------*/ 
INSERT INTO cdb_local.`crpcontractorequipment`
(`Id`, `CrpContractorId`, `CmnEquipmentId`, `RegistrationNo`,`SerialNo`, `Quantity`, `Verified`, `Approved`, CreatedBy)
SELECT UUID(),(SELECT X.Id FROM cdb_local.crpcontractor X WHERE X.CDBNo= T3.ci_CDBRegNum LIMIT 1),(SELECT Id FROM cdb_local.cmnequipment WHERE Name=(SELECT A.eg_name FROM cdbolddata.equipmentlistgeneral A WHERE A.eg_key=T1.ae_eg_key)),T1.ae_regNumber,T1.ae_SerialNumber,T1.ae_Quantity,1,1,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.appequipment T1 JOIN cdbolddata.application T2 ON T1.ae_ai_key=T2.ai_key JOIN cdbolddata.contractorconsultant T3 ON T2.ai_CDBRegNum=T3.ci_CDBRegNum
WHERE T1.ae_eg_key IS NOT NULL;

INSERT INTO cdb_local.`crpcontractorequipment`
(`Id`, `CrpContractorId`, `CmnEquipmentId`, `RegistrationNo`,`SerialNo`, `Quantity`, `Verified`, `Approved`, CreatedBy)
SELECT UUID(),(SELECT X.Id FROM cdb_local.crpcontractor X WHERE X.CDBNo= T3.ci_CDBRegNum LIMIT 1),(SELECT Id FROM cdb_local.cmnequipment WHERE Name=T1.ae_Description),T1.ae_regNumber,T1.ae_SerialNumber,T1.ae_Quantity,1,1,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.appequipment T1 JOIN cdbolddata.application T2 ON T1.ae_ai_key=T2.ai_key JOIN cdbolddata.contractorconsultant T3 ON T2.ai_CDBRegNum=T3.ci_CDBRegNum
WHERE T1.ae_eg_key IS NULL and T1.ae_Description is not null and T1.ae_Description != '';

/*----------------------------------------------------------INSRERT DATA INTO CONTRACTOR FINAL TABLE----------------------------------*/
-- INSERT INTO cdb_local.crpcontractorfinal(Id,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,RegisteredAddress,CmnRegisteredDzongkhagId,Gewog,Village,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
-- SELECT Id,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,RegisteredAddress,CmnRegisteredDzongkhagId,Gewog,Village,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,'463c2d4c-adbd-11e4-99d7-080027dcfac6',RegistrationApprovedDate,RegistrationExpiryDate,'bf258f4b-c639-11e4-b574-080027dcfac6'
-- FROM cdb_local.crpcontractor;

INSERT INTO cdb_local.crpcontractorfinal(Id,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,RegisteredAddress,CmnRegisteredDzongkhagId,Gewog,Village,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
SELECT Id,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,RegisteredAddress,CmnRegisteredDzongkhagId,Gewog,Village,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdb_local.crpcontractor;

UPDATE cdb_local.`crpcontractor` T1 SET T1.TradeLicenseNo = (select A.ci_MTILicenceNum from cdbolddata.contractorconsultant A where A.ci_Contractor = 1 and A.ci_CDBRegNum = T1.CDBNo limit 1);
UPDATE crpcontractorfinal T1 SET T1.TradeLicenseNo = (select A.TradeLicenseNo from crpcontractor A where A.CDBNo = T1.CDBNo);

INSERT INTO cdb_local.crpcontractorworkclassificationfinal(Id,CrpContractorFinalId,CmnProjectCategoryId,CmnAppliedClassificationId,CmnVerifiedClassificationId,CmnApprovedClassificationId,CreatedBy)
SELECT UUID(),CrpContractorId,CmnProjectCategoryId,CmnAppliedClassificationId,CmnVerifiedClassificationId,CmnApprovedClassificationId,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdb_local.crpcontractorworkclassification;

INSERT INTO cdb_local.crpcontractorequipmentfinal(Id,CrpContractorFinalId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,CreatedBy)
SELECT Id,CrpContractorId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdb_local.crpcontractorequipment;

INSERT INTO cdb_local.crpcontractorhumanresourcefinal(Id,CrpContractorFinalId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,CreatedBy)
SELECT Id,CrpContractorId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdb_local.crpcontractorhumanresource;

UPDATE cdb_local.crpcontractorhumanresourcefinal T1 SET T1.JoiningDate = (select T1.InitialDT from cdbolddata.contractorconsultant T1 join (cdb_local.crpcontractorhumanresource T2 join cdb_local.crpcontractorfinal T3 on T2.CrpContractorId = T3.Id) on T3.CDBNo = T1.ci_CDBRegNum limit 1) where T1.IsPartnerOrOwner = 1;
/*------------------------------END of Contractor's Data-------------------------------------------*/

/*--------------------------------------------------------START FOR CONSULTANTS----------------------------------*/
UPDATE cdbolddata.tblconsultantregistration SET ci_RegCountry='Bhutan' WHERE trim(ci_RegCountry)='';
UPDATE cdbolddata.tblconsultantregistration SET ci_RegCity='Samdrup Jongkhar' WHERE ci_RegCity='Samdrup Jonkhar';
UPDATE cdbolddata.tblconsultantregistration SET ci_RegCity='Trashiyangtse' WHERE ci_RegCity='Trashi Yangtse';
UPDATE cdbolddata.tblconsultantregistration SET ci_RegCity='Wangdue Phodrang' WHERE ci_RegCity='Wangdue';

UPDATE cdbolddata.tblconsultantregistration SET Ci_CorCity=NULL WHERE trim(Ci_CorCity)='';
UPDATE cdbolddata.tblconsultantregistration SET Ci_CorCity='Samdrup Jongkhar' WHERE Ci_CorCity='Samdrup Jonkhar';
UPDATE cdbolddata.tblconsultantregistration SET Ci_CorCity='Trashiyangtse' WHERE Ci_CorCity='Trashi Yangtse';
UPDATE cdbolddata.tblconsultantregistration SET Ci_CorCity='Wangdue Phodrang' WHERE Ci_CorCity='Wangdue';
/*------------------------------CONSULTANT TABLE---------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultant`
(`Id`, `ReferenceNo`, `ApplicationDate`, `CDBNo`, `CmnOwnershipTypeId`, `NameOfFirm`,`RegisteredAddress`,`CmnRegisteredDzongkhagId`, `Village`, `Gewog`, `Address`, `CmnCountryId`, `CmnDzongkhagId`, `Email`, `TelephoneNo`, `MobileNo`, `FaxNo`, `RegistrationStatus`, `CmnApplicationRegistrationStatusId`, `RegistrationApprovedDate`, `RegistrationExpiryDate`, `RegistrationPaymentApprovedDate`)
SELECT UUID(),@referenceNo:=@referenceNo+1,InitialDT,ci_CDBRegNum,'1e243ef0-c652-11e4-b574-080027dcfac6',ci_firm,concat(ci_RegAdd1,' ',coalesce(ci_RegAdd2,'')),(select Id from cdb_local.cmndzongkhag where NameEn=T1.ci_RegCity),'','',concat(ci_CorAdd1,' ',coalesce(ci_CorAdd2,'')),(select Id from cdb_local.cmncountry where Name=coalesce(T1.ci_RegCountry,'Bhutan')),(select Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Ci_CorCity,T1.ci_RegCity)),ci_Email,ci_TelNo,'',ci_FaxNo,1,'463c2d4c-adbd-11e4-99d7-080027dcfac6',ci_CDBRegDate,ci_CDBExpiryDate,ci_CDBExpiryDate
FROM cdbolddata.tblconsultantregistration T1,(SELECT @referenceNo:= 0) as referenceNo;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ARCHITECTURAL SERVICE (A1)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'e6372584-bc15-11e4-81ac-080027dcfac6','2dc059a3-bc17-11e4-81ac-080027dcfac6','2dc059a3-bc17-11e4-81ac-080027dcfac6','2dc059a3-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_A1=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ARCHITECTURAL SERVICE (A2)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'e6372584-bc15-11e4-81ac-080027dcfac6','378c8114-bc17-11e4-81ac-080027dcfac6','378c8114-bc17-11e4-81ac-080027dcfac6','378c8114-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_A2=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ARCHITECTURAL SERVICE (A3)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'e6372584-bc15-11e4-81ac-080027dcfac6','42914a22-bc17-11e4-81ac-080027dcfac6','42914a22-bc17-11e4-81ac-080027dcfac6','42914a22-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_A3=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C1)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','51f58a70-bc17-11e4-81ac-080027dcfac6','51f58a70-bc17-11e4-81ac-080027dcfac6','51f58a70-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C1=1;

//102,120,149,164 (ci_CDBRegNum)   --DO LIMTI 1--
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C2)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','5b147a4d-bc17-11e4-81ac-080027dcfac6','5b147a4d-bc17-11e4-81ac-080027dcfac6','5b147a4d-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C2=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C3)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','6516bfdd-bc17-11e4-81ac-080027dcfac6','6516bfdd-bc17-11e4-81ac-080027dcfac6','6516bfdd-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C3=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C4)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','7b84fd72-bc17-11e4-81ac-080027dcfac6','7b84fd72-bc17-11e4-81ac-080027dcfac6','7b84fd72-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C4=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C5)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','a8ee79e6-bc17-11e4-81ac-080027dcfac6','a8ee79e6-bc17-11e4-81ac-080027dcfac6','a8ee79e6-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C5=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C6)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','be34bd47-bc17-11e4-81ac-080027dcfac6','be34bd47-bc17-11e4-81ac-080027dcfac6','be34bd47-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C6=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (CIVIL SERVICE (C7)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'f39b9245-bc15-11e4-81ac-080027dcfac6','cc3bfc36-bc17-11e4-81ac-080027dcfac6','cc3bfc36-bc17-11e4-81ac-080027dcfac6','cc3bfc36-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_C7=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E1)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','ded7b309-bc17-11e4-81ac-080027dcfac6','ded7b309-bc17-11e4-81ac-080027dcfac6','ded7b309-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E1=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E2)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','ef1e617f-bc17-11e4-81ac-080027dcfac6','ef1e617f-bc17-11e4-81ac-080027dcfac6','ef1e617f-bc17-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E2=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E3)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','1a4e9b6f-bc18-11e4-81ac-080027dcfac6','1a4e9b6f-bc18-11e4-81ac-080027dcfac6','1a4e9b6f-bc18-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E3=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E4)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','271c4483-bc18-11e4-81ac-080027dcfac6','271c4483-bc18-11e4-81ac-080027dcfac6','271c4483-bc18-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E4=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E5)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','30a3dd3c-bc18-11e4-81ac-080027dcfac6','30a3dd3c-bc18-11e4-81ac-080027dcfac6','30a3dd3c-bc18-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E5=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E6)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','3ceb09ba-bc18-11e4-81ac-080027dcfac6','3ceb09ba-bc18-11e4-81ac-080027dcfac6','3ceb09ba-bc18-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E6=1;
/*------------------------------------CONSULTANT WORK CLASSIFICATION (ELECTRICAL SERVICE (E7)) TABLE-------------------------------------------------------------------*/
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','4461b1b0-bc18-11e4-81ac-080027dcfac6','4461b1b0-bc18-11e4-81ac-080027dcfac6','4461b1b0-bc18-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E7=1;


/////________////////
INSERT INTO cdb_local.`crpconsultantworkclassification`
(`Id`, `CrpConsultantId`, `CmnServiceCategoryId`, `CmnAppliedServiceId`, `CmnVerifiedServiceId`, `CmnApprovedServiceId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.ci_CDBRegNum),'fb2aa1a7-bc15-11e4-81ac-080027dcfac6','4461b1b0-bc18-11e4-81ac-080027dcfac6','4461b1b0-bc18-11e4-81ac-080027dcfac6','4461b1b0-bc18-11e4-81ac-080027dcfac6'
FROM cdbolddata.tblconsultantregistration T1 JOIN cdbolddata.tblconsultantapplication T2 on T1.ci_key=T2.ai_ci_key
WHERE T2.ai_E7=1;

/*------------------------------------CONSULTANT PARTNER DETAILS TABLE-------------------------------------------------------------------*/  
DELETE FROM cdbolddata.tblconsultantpartner WHERE cp_ci_key not in (select ci_key from cdbolddata.tblconsultantregistration);
INSERT INTO cdb_local.`crpconsultanthumanresource`
(`Id`, `CrpConsultantId`, `CmnSalutationId`, `CIDNo`, `Name`, `Sex`, `CmnCountryId`,`CmnDesignationId`, `IsPartnerOrOwner`, `Verified`, `Approved`,CreatedBy)
SELECT UUID(),(SELECT A.Id FROM cdb_local.crpconsultant A JOIN cdbolddata.tblconsultantregistration B ON A.CDBNo=B.ci_CDBRegNum WHERE B.ci_key=T1.cp_ci_key and B.ci_CDBRegNum!=''),CASE WHEN T1.cp_sex='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.cp_ID_No,T1.cp_Name,CASE WHEN T1.cp_sex='Male' THEN 'M' ELSE 'F' END,coalesce((SELECT Id FROM cdb_local.cmncountry WHERE Nationality=coalesce(T1.cp_Nationality,'Bhutanese')),'8f897032-c6e6-11e4-b574-080027dcfac6'),'679b760f-d9ff-11e4-b628-080027dcfac6',1,1,1,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.tblconsultantpartner T1 JOIN cdbolddata.tblconsultantregistration T2 ON T2.ci_key=T1.cp_ci_key;
/*------------------------------------UPDATE ShowInCertificate CONTRATCOR PARTNER DETAILS TABLE-------------------------------------------------------------------*/  
/*--UPDATE cdb_local.`crpconsultanthumanresource` SET ShowInCertificate=1 WHERE Id=(SELECT Id FROM cdb_local.`crpconsultanthumanresource` GROUP BY CrpConsultantId);--*/
-- /*------------------------------------CONTRATCOR HUMAN RESOURCE TABLE-------------------------------------------------------------------*/
-- INSERT INTO cdb_local.`crpconsultanthumanresource`
-- (`Id`, `CrpConsultantId`, `CmnSalutationId`, `CIDNo`, `Name`, `Sex`, `CmnCountryId`,`CmnQualificationId`,`CmnTradeId`,`CmnDesignationId`,`Verified`, `Approved`, CreatedBy)
-- SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.cdbNumber limit 1),CASE WHEN T1.Sex='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.IDNo,T1.EName,CASE WHEN T1.Sex='Male' THEN 'M' ELSE 'F' END,(SELECT Id FROM cdb_local.cmncountry WHERE Nationality=coalesce(T1.Nationality,'Bhutanese')),(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE T1.Qualification=X.Name and X.CmnListId = 'ff4e55ee-a254-11e4-b4d2-080027dcfac6'),(SELECT Y.Id FROM cdb_local.cmnlistitem Y WHERE T1.Trade=Y.Name and Y.CmnListId = 'bf4b32e8-a256-11e4-b4d2-080027dcfac6'),(SELECT Z.Id FROM cdb_local.cmnlistitem Z WHERE T1.Designation=Z.Name and Z.CmnListId = '599fbfdc-a250-11e4-b4d2-080027dcfac6'),1,1,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
-- FROM cdbolddata.tblconsultantemployee T1 JOIN cdbolddata.tblconsultantregistration T2 ON T2.ci_CDBRegNum=T1.cdbNumber
-- WHERE T1.IDNo NOT IN (SELECT CIDNo FROM cdb_local.`crpconsultanthumanresource`);

INSERT INTO cdb_local.`crpconsultanthumanresource`
(`Id`, `CrpConsultantId`, `CmnSalutationId`, `CIDNo`, `Name`, `Sex`, `CmnCountryId`,`CmnQualificationId`,`CmnTradeId`,`CmnDesignationId`,`Verified`, `Approved`, CreatedBy)
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.cdbNumber limit 1),CASE WHEN T1.Sex='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.IDNo,T1.EName,CASE WHEN T1.Sex='Male' THEN 'M' ELSE 'F' END,(SELECT Id FROM cdb_local.cmncountry WHERE Nationality=coalesce(T1.Nationality,'Bhutanese')),(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE T1.Qualification=X.Name and X.CmnListId = 'ff4e55ee-a254-11e4-b4d2-080027dcfac6'),(SELECT Y.Id FROM cdb_local.cmnlistitem Y WHERE T1.Trade=Y.Name and Y.CmnListId = 'bf4b32e8-a256-11e4-b4d2-080027dcfac6'),(SELECT Z.Id FROM cdb_local.cmnlistitem Z WHERE T1.Designation=Z.Name and Z.CmnListId = '599fbfdc-a250-11e4-b4d2-080027dcfac6'),1,1,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.tblconsultantemployee T1 JOIN cdbolddata.tblconsultantregistration T2 ON T2.ci_CDBRegNum=T1.cdbNumber;

DELETE FROM cdb_local.`crpconsultanthumanresource` WHERE Id in (SELECT A.Id from (SELECT X.Id from cdb_local.crpconsultanthumanresource X where X.IsPartnerOrOwner <> 1 group by TRIM(X.CIDNo), X.CrpConsultantId having count(TRIM(CIDNo))>1) A);

DELETE FROM cdb_local.`crpcontractorhumanresource` WHERE Id in (SELECT A.Id from (SELECT X.Id from cdb_local.crpcontractorhumanresource X group by TRIM(X.CIDNo), X.CrpContractorId having count(TRIM(CIDNo))>1) A);
--RUN MANY TIMES --

//MODIFIED WORKING
INSERT INTO cdb_local.`crpconsultanthumanresource`
(`Id`, `CrpConsultantId`, `CmnSalutationId`, `CIDNo`, `Name`, `Sex`, `CmnCountryId`,`CmnQualificationId`,`CmnTradeId`,`CmnDesignationId`,`Verified`, `Approved`) 
SELECT UUID(),(SELECT Id FROM cdb_local.crpconsultant WHERE CDBNo=T1.cdbNumber and CDBNo not in (102,120,149,164)),CASE WHEN T1.Sex='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.IDNo,T1.EName,CASE WHEN T1.Sex='Male' THEN 'M' ELSE 'F' END,(SELECT Id FROM cdb_local.cmncountry WHERE Nationality=coalesce(T1.Nationality,'Bhutanese')),(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE T1.Qualification=X.Name),(SELECT Y.Id FROM cdb_local.cmnlistitem Y WHERE T1.Trade=Y.Name and Y.CmnListId = 'bf4b32e8-a256-11e4-b4d2-080027dcfac6'),(SELECT Z.Id FROM cdb_local.cmnlistitem Z WHERE T1.Designation=Z.Name and Z.CmnListId = '599fbfdc-a250-11e4-b4d2-080027dcfac6'),1,1
FROM cdbolddata.tblconsultantemployee T1 JOIN cdbolddata.tblconsultantregistration T2 ON T2.ci_CDBRegNum=T1.cdbNumber  and T1.cdbNumber not in (102,120,149,164)
WHERE T1.IDNo NOT IN (SELECT CIDNo FROM cdb_local.`crpconsultanthumanresource`);
/*------------------------------------SET QUALIFICATION AND TRADE TO PARTNERS IN CONTRACTOR HUMAN RESOURCE TABLE-------------------------------------------------------------------*/  
UPDATE cdb_local.`crpconsultanthumanresource` T1 SET CmnQualificationId=(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE X.Name=(SELECT Qualification FROM cdbolddata.tblconsultantemployee WHERE IDNo=T1.CIDNo) and X.CmnListId = 'ff4e55ee-a254-11e4-b4d2-080027dcfac6'),CmnTradeId=(SELECT X.Id FROM cdb_local.cmnlistitem X WHERE X.Name=(SELECT Trade FROM cdbolddata.tblconsultantemployee WHERE IDNo=T1.CIDNo) and X.CmnListId = 'bf4b32e8-a256-11e4-b4d2-080027dcfac6') WHERE IsPartnerOrOwner=1;

UPDATE crpconsultanthumanresource SET CIDNo = TRIM(CIDNo);
/*------------------------------------INSERT EQUIPMENTS FOR CONSULTANTS NOT IN MASTRE-------------------------*/
INSERT INTO cdb_local.cmnequipment (Id,Name,IsRegistered,CreatedBy)
SELECT DISTINCT UUID(),T1.ae_Description,0,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblconsultantappequipment T1
WHERE T1.ae_Description NOT IN (SELECT Name FROM cdb_local.cmnequipment);
/*------------------------------------CONSULTANT EQUIPMENT TABLE-------------------------------------------------------------------*/ 
INSERT INTO cdb_local.`crpconsultantequipment`
(`Id`, `CrpConsultantId`, `CmnEquipmentId`, `RegistrationNo`,`SerialNo`, `Quantity`, `Verified`, `Approved`, CreatedBy)
SELECT UUID(),(SELECT X.Id FROM cdb_local.crpconsultant X WHERE X.CDBNo=T3.ci_CDBRegNum LIMIT 1),(SELECT Id FROM cdb_local.cmnequipment WHERE Name=T1.ae_Description),T1.ae_regNumber,T1.ae_SerialNumber,T1.ae_Quantity,1,1,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM cdbolddata.tblconsultantappequipment T1 JOIN cdbolddata.tblconsultantapplication T2 ON T1.ae_ai_key=T2.ai_key JOIN cdbolddata.tblconsultantregistration T3 ON T2.ai_CDBRegNum=T3.ci_CDBRegNum WHERE (SELECT Id FROM cdb_local.cmnequipment WHERE Name=T1.ae_Description) IS NOT NULL;
/*-------------------------------------------------------------END of Consultant's Data-------------------------------------------*/

/*-------------------------------------------------Architect Table--------------------------------------------------------------*/
INSERT INTO cdb_local.`crparchitect`
(`Id`, `ReferenceNo`, `ApplicationDate`, `ARNo`, `CmnServiceSectorTypeId`, `CIDNo`,`CmnSalutationId`,`Name`, `CmnCountryId`, `CmnDzongkhagId`, `MobileNo`,`CmnQualificationId`,`RegistrationStatus`,`CmnApplicationRegistrationStatusId`, `RegistrationApprovedDate`, `RegistrationExpiryDate`)
SELECT UUID(),@referenceNo:=@referenceNo+1,InitialRegDate,CDBNumber,'dacae294-bea7-11e4-9757-080027dcfac6',CID,'872100b3-a5f0-11e4-8ab5-080027dcfac6',AName,'8f897032-c6e6-11e4-b574-080027dcfac6','a1a67f99-c6e6-11e4-b574-080027dcfac6',PhoneNo,'504ba628-e5de-11e5-8b00-9c2a70cc8e06',1,'463c2d4c-adbd-11e4-99d7-080027dcfac6',RegDate,ExpiryDate
FROM cdbolddata.tblarchitectreg T1,(SELECT @referenceNo:= 0) as referenceNo;
/*-------------------------------------------------Specialized Trades Table--------------------------------------------------------------*/
INSERT INTO cdb_local.`crpspecializedtrade`
(`Id`, `ReferenceNo`, `ApplicationDate`, `SPNo`, `CIDNo`,`CmnSalutationId`,`Name`,`CmnDzongkhagId`, `MobileNo`,RegistrationStatus,`CmnApplicationRegistrationStatusId`, `RegistrationApprovedDate`, `RegistrationExpiryDate`)
SELECT UUID(),@referenceNo:=@referenceNo+1,T1.CDBRegDate,T1.CDBNo,T1.CIDNo,CASE WHEN T1.Gender='Male' THEN '872100b3-a5f0-11e4-8ab5-080027dcfac6' ELSE '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' END,T1.ContactPerson,(select Id from cdb_local.cmndzongkhag where NameEn=(select X.Dz_Name from cdbolddata.dzongkhag X join cdbolddata.specilizedtrader0 Y on X.Dz_key=Y.Dzongkhag where Y.RecordID = T1.RecordID LIMIT 1)),T1.PhoneNo,1,'463c2d4c-adbd-11e4-99d7-080027dcfac6',T1.CDBRegDate,T2.ExpiryDate
FROM cdbolddata.specilizedtrader0 T1 JOIN cdbolddata.specilizedtrader1 T2 on T1.RecordID=T2.PRecordID,(SELECT @referenceNo:= 0) as referenceNo group by T2.PRecordID;

UPDATE cdb_local.crpspecializedtrade A SET A.CmnDzongkhagId = (select T4.Id from cdb_local.crpspecializedtradefinal T1 join cdbolddata.specilizedtrader0 T2 on T1.SPNo = T2.CDBNo join cdb_local.cmndzongkhag T4 on CAST(T4.Code as SIGNED) = T2.Dzongkhag where T1.SPNo = A.SPNo)
/*-------------------------------------------------Specialized Trades Work CLassification (Masonry)--------------------------------------------------------------*/
INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
(`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT SUBSTRING_INDEX(X.CDBNo,'SP-',-1) FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID)),'12efa085-c74f-11e4-bf37-080027dcfac6','12efa085-c74f-11e4-bf37-080027dcfac6','12efa085-c74f-11e4-bf37-080027dcfac6'
FROM cdbolddata.specilizedtrader1 T1
WHERE T1.Masonry=1;


///
INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
(`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT X.CDBNo FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID LIMIT 1)),'12efa085-c74f-11e4-bf37-080027dcfac6','12efa085-c74f-11e4-bf37-080027dcfac6','12efa085-c74f-11e4-bf37-080027dcfac6'
FROM cdbolddata.specilizedtrader1 T1
WHERE T1.Masonry=1;
/*-------------------------------------------------Specialized Trades Work CLassification (Plumbing)--------------------------------------------------------------*/
-- INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
-- (`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
-- SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT SUBSTRING_INDEX(X.CDBNo,'SP-',-1) FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID)),'19775594-c74f-11e4-bf37-080027dcfac6','19775594-c74f-11e4-bf37-080027dcfac6','19775594-c74f-11e4-bf37-080027dcfac6'
-- FROM cdbolddata.specilizedtrader1 T1
-- WHERE T1.Plumbing=1;

//
INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
(`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT X.CDBNo FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID LIMIT 1)),'19775594-c74f-11e4-bf37-080027dcfac6','19775594-c74f-11e4-bf37-080027dcfac6','19775594-c74f-11e4-bf37-080027dcfac6'
FROM cdbolddata.specilizedtrader1 T1
WHERE T1.Plumbing=1;
/*-------------------------------------------------Specialized Trades Work CLassification (Electrical House Wiring)--------------------------------------------------------------*/
INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
(`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT X.CDBNo FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID LIMIT 1)),'254a886f-c74f-11e4-bf37-080027dcfac6','254a886f-c74f-11e4-bf37-080027dcfac6','254a886f-c74f-11e4-bf37-080027dcfac6'
FROM cdbolddata.specilizedtrader1 T1
WHERE T1.Electrical=1;

//ADD LIMIT ONE LIKE ABOVE
/*-------------------------------------------------Specialized Trades Work CLassification (Construction Carpentry)--------------------------------------------------------------*/
INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
(`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT X.CDBNo FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID LIMIT 1)),'32c91243-c74f-11e4-bf37-080027dcfac6','32c91243-c74f-11e4-bf37-080027dcfac6','32c91243-c74f-11e4-bf37-080027dcfac6'
FROM cdbolddata.specilizedtrader1 T1
WHERE T1.Carpentry=1;

-- //
-- INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
-- (`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
-- SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT SUBSTRING_INDEX(X.CDBNo,'SP-',-1) FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID lIMIT 1)),'32c91243-c74f-11e4-bf37-080027dcfac6','32c91243-c74f-11e4-bf37-080027dcfac6','32c91243-c74f-11e4-bf37-080027dcfac6'
-- FROM cdbolddata.specilizedtrader1 T1
-- WHERE T1.Carpentry=1;
/*-------------------------------------------------Specialized Trades Work CLassification (Welding and Fabrication)--------------------------------------------------------------*/
INSERT INTO cdb_local.`crpspecializedtradeworkclassification`
(`Id`, `CrpSpecializedTradeId`, `CmnAppliedCategoryId`, `CmnVerifiedCategoryId`, `CmnApprovedCategoryId`)
SELECT UUID(),(SELECT Id FROM cdb_local.crpspecializedtrade WHERE SPNo=(SELECT X.CDBNo FROM cdbolddata.specilizedtrader0 X JOIN cdbolddata.specilizedtrader1 Y on X.RecordID=Y.PRecordID LIMIT 1)),'3c0f937c-c74f-11e4-bf37-080027dcfac6','3c0f937c-c74f-11e4-bf37-080027dcfac6','3c0f937c-c74f-11e4-bf37-080027dcfac6'
FROM cdbolddata.specilizedtrader1 T1
WHERE T1.WeldingFabrication=1;

/*------------------------------------------------Goverment Engineer Data-------------------------------------------------------------------------*/
INSERT INTO cdb_local.crpgovermentengineer (`Id`,`Name`,`CIDNo`,`PositionTitle`,`Agency`,`Qualification`,`Trade`)
SELECT UUID(),engName,Citizenship_ID,Position_Title,Agnecy,Degree,Subject
FROM cdbolddata.govtengineers;
/*----------------------------------------------Ministry table--------------------------------------------------------*/
INSERT INTO cdb_local.`cmnlistitem` (Id,CmnListId,Name,CreatedBy) 
SELECT UUID(),'ffc90e78-a256-11e4-b4d2-080027dcfac6',Name,'bf258f4b-c639-11e4-b574-080027dcfac6' 
FROM cdbolddata.tblgengeneralm WHERE ParentCode='012';
/*----------------------------------------------Proicuring Agency table--------------------------------------------------------*/
INSERT INTO cdb_local.`cmnprocuringagency` (Id,Code,Name,CmnMinistryId,CreatedBy)
SELECT UUID(),T1.AgencyCode,T1.NameofAgency,(select Id from cdb_local.cmnlistitem where CmnListId='ffc90e78-a256-11e4-b4d2-080027dcfac6' and Name=T1.Ministry),'bf258f4b-c639-11e4-b574-080027dcfac6' 
FROM cdbolddata.procuringagency T1;


-- FOR BELOW QUERY, REMOVE LHUNTSE FROM cmndzongkhag AS IT REPEATS--
ALTER TABLE  cdb_local.`etltender` ADD  `migratedworkid` VARCHAR(25) NOT NULL AFTER `EditedOn`;
INSERT INTO cdb_local.`etltender` (`migratedworkid`,`Id`, `ReferenceNo`, `NameOfWork`, `WorkId`, `DescriptionOfWork`, `CmnDzongkhagId`, `CmnProcuringAgencyId`, `CmnContractorCategoryId`, `CmnContractorClassificationId`, `ContractPeriod`, `DateOfSaleOfTender`, `DateOfClosingSaleOfTender`, `LastDateAndTimeOfSubmission`, `TenderOpeningDateAndTime`, `CostOfTender`, `EMD`, `ProjectEstimateCost`, `ShowCostInWebsite`, `TentativeStartDate`, `TentativeEndDate`, `ContactPerson`, `PublishInWebsite`, `TenderSource`, `ContractPriceInitial`, `ContractPriceFinal`, `CommencementDateOfficial`, `CommencementDateFinal`, `CompletionDateOfficial`, `CompletionDateFinal`, `CmnWorkExecutionStatusId`, `OntimeCompletionScore`, `QualityOfExecutionScore`, `CreatedBy`)
SELECT T1.WorkId,UUID(),T1.RefNo,T1.Nworks,SUBSTRING_INDEX(T1.WorkId,'/',-1),T1.CDescription,(select Id from cdb_local.cmndzongkhag where NameEn=T1.Dzongkhag),(SELECT Id from cdb_local.cmnprocuringagency where Code=T1.PAgency),(select Id from cdb_local.cmncontractorworkcategory where Code=T1.WClassification),(select Id from cdb_local.cmncontractorclassification where Code=T1.CClassification),T1.Duration,T1.StSaleDt,T1.EndSaleDt,T1.LastDt,T1.BOpenDt,T1.CostOfTender,T1.Emd,T1.AEstimate,0,T1.TStartDt,T1.TCompleDt,T1.CPerson,case when T1.Status='Y' then 1 else 0 end,1,T2.BidAmount,T2.EvalAmount,T2.Startdt,T2.Startdt,T2.CompleDt,T2.CompleDt,case when T2.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T2.Status in ('Completed','Completec') then 'a13c5d39-b5a8-11e4-81ac-080027dcfac6' when T2.Status in ('Terminated','Contract Terminated') then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' end,T2.EvaluationOntime,T2.EvaluationQuality,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblworkidt0 T1 left join cdbolddata.trackrecord T2 on T1.WorkId=T2.WorkId and T2.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed');
/*--------------------------------------------------Delete duplicate tenders inserted in above query--------------------------------------------------------*/
DELETE FROM cdb_local.`etltender` WHERE Id in (SELECT A.Id from (SELECT X.Id from cdb_local.etltender X join cdb_local.cmnprocuringagency Y on X.CmnProcuringAgencyId=Y.Id group by X.WorkId,year(X.DateOfSaleOfTender),Y.Code having count(WorkId)>1) A);

//RUN ABOVE QUERY TEN OR MORE TIMES

UPDATE `etltender` SET `OntimeCompletionScore` = NULL where `OntimeCompletionScore` = 0;
UPDATE `etltender` SET `QualityOfExecutionScore` = NULL where `QualityOfExecutionScore` = 0;


/*--------------------------------------------------eltcriteriahumanresource--------------------------------------------------------*/
INSERT INTO cdb_local.`etlcriteriahumanresource`(`Id`, `EtlTenderId`, `EtlTierId`, `CmnDesignationId`, `Qualification`, `Points`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltender X where X.migratedworkid=T1.WorkId),(SELECT Id from cdb_local.etltier where Name=T1.Tier),(SELECT Id from cdb_local.cmnlistitem where Name=T1.HRname and CmnListId='599fbfdc-a250-11e4-b4d2-080027dcfac6'),T1.Qualification,T1.HRPoint,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblhrtire T1 WHERE T1.WorkId!='';
/*--------------------------------------------------eltcriteriaequipment--------------------------------------------------------*/
INSERT INTO cdb_local.`etlcriteriaequipment`(`Id`, `EtlTenderId`, `EtlTierId`, `CmnEquipmentId`, `Quantity`, `Points`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltender X where X.migratedworkid=T1.WorkId),(SELECT Id from cdb_local.etltier where Name=T1.Tier),(SELECT Id from cdb_local.cmnequipment where Name=T1.Equipmentname),T1.NoOfEquipment,T1.EquipmentPoint,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblequiptire T1 WHERE T1.WorkId!='';
/*--------------------------------------------------etltenderevaluationcommittee--------------------------------------------------------*/
INSERT INTO cdb_local.`etltendercommittee`(`Id`, `Type`, `EtlTenderId`, `Name`, `Designation`, `CreatedBy`)
SELECT UUID(),1,(SELECT X.Id from cdb_local.etltender X where X.migratedworkid=T1.WorkId),T1.name,T1.designation,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluationcommittee T1 where T1.WorkId!=''; 
/*--------------------------------------------------etlbiddercontractor--------------------------------------------------------*/
/*---------------Add a recoId primary key column from old data ---------------------------*/
ALTER TABLE  cdb_local.`etltenderbiddercontractor` ADD  `RecordId` INT NOT NULL AFTER `EditedOn`;
INSERT INTO cdb_local.`etltenderbiddercontractor`(`RecordId`,`Id`, `EtlTenderId`, `JointVenture`, `FinancialBidQuoted`, `CmnOwnershipTypeId`, `EmploymentOfVTI`, `CommitmentOfInternship`, `ActualStartDate`, `ActualEndDate`, `AwardedAmount`, `Remarks`, `CreatedBy`)
SELECT T1.RecordId,UUID(),(SELECT X.Id from cdb_local.etltender X where X.migratedworkid=T1.WorkId),case when T1.JV='yes' then 1 else 0 end,T1.FinancialBid,T1.OsStatus,T1.OsEmployment,T1.OsCommitment,case when T1.Result='Awarded' then T1.StartDt else NULL end,case when T1.Result='Awarded' then T1.EndDt else NULL end,T1.AwardedAmount,T1.Remark,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 WHERE T1.WorkId!='';

UPDATE `etltenderbiddercontractor` SET AwardedAmount = NULL WHERE AwardedAmount = 0;
UPDATE `etltenderbiddercontractor` T1 SET T1.WorkId = (SELECT migratedworkid from etltender where Id = T1.EtlTenderId);
MIgrated Track record table from old db to new db (JUST WHOLE TABLE)
UPDATE `etltenderbiddercontractor` T1 SET T1.ActualStartDate = (SELECT Startdt from trackrecord where WorkId = T1.WorkId and Status in ('Completed','Awarded') limit 1),  T1.ActualEndDate = (SELECT CompleDt from trackrecord where WorkId = T1.WorkId and Status in ('Completed','Awarded') limit 1) where T1.AwardedAmount is not null and T1.ActualStartDate is null;
//RUN ABOVE QUERY MANY TIMES
/*--------------------------------------------------etlbiddercontractordetail--------------------------------------------------------*/
-- INSERT INTO cdb_local.`etltenderbiddercontractordetail`(`Id`, `EtlTenderBidderContractorId`, `Sequence`, `CrpContractorFinalId`, `Stake`, `CreatedBy`)
-- SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),1,(SELECT Id FROM cdb_local.crpcontractorfinal where CDBNo=SUBSTRING_INDEX(T1.CDBNo,'NB',-1 )),case when T1.CdbPercent=0.00 or T1.CdbPercent is null then 100 else T1.CdbPercent end,'bf258f4b-c639-11e4-b574-080027dcfac6'
-- FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on SUBSTRING_INDEX(T1.CDBNo,'NB',-1)=T2.CDBNo WHERE T1.WorkId!='';

INSERT INTO cdb_local.`etltenderbiddercontractordetail`(`Id`, `EtlTenderBidderContractorId`, `Sequence`, `CrpContractorFinalId`, `Stake`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),1,(SELECT Id FROM cdb_local.crpcontractorfinal where CDBNo=T1.CDBNo),case when T1.CdbPercent=0.00 or T1.CdbPercent is null then 100 else T1.CdbPercent end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';

/*----------------------------------------------etltenderbidderdetail for 2nd join venture partner---------------------------*/
INSERT INTO cdb_local.`etltenderbiddercontractordetail`(`Id`, `EtlTenderBidderContractorId`, `Sequence`, `CrpContractorFinalId`, `Stake`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),2,(SELECT Id FROM cdb_local.crpcontractorfinal where CDBNo=T1.CDBNo1),case when T1.Cdb1Percent=0.00 or T1.Cdb1Percent is null then 100 else T1.Cdb1Percent end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo1=T2.CDBNo WHERE T1.WorkId!='' and coalesce(T1.CDBNo1,0)>0;
/*----------------------------------------------etltenderbidderdetail for 3rd join venture partner---------------------------*/
INSERT INTO cdb_local.`etltenderbiddercontractordetail`(`Id`, `EtlTenderBidderContractorId`, `Sequence`, `CrpContractorFinalId`, `Stake`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),3,(SELECT Id FROM cdb_local.crpcontractorfinal where CDBNo=T1.CDBNo2),case when T1.Cdb2Percent=0.00 or T1.Cdb2Percent is null then 100 else T1.Cdb2Percent end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo2=T2.CDBNo WHERE T1.WorkId!='' and coalesce(T1.CDBNo2,0)>0;
/*-----------------------------------------------------evaluation data for humanresiurce-----------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorhumanresource`(`Id`, `EtlTenderBidderContractorId`, `CIDNo`, `EtlTierId`, `CmnDesignationId`,`Qualification`,`Points`, `CreatedBy`)
SELECT UUID(),(SELECT Y.Id from cdb_local.etltender X join cdb_local.etltenderbiddercontractor Y on X.Id=Y.EtlTenderId join cdb_local.etltenderbiddercontractordetail Z on Y.Id=Z.EtlTenderBidderContractorId join cdb_local.crpcontractorfinal A on A.Id=Z.CrpContractorFinalId where X.migratedworkid=T1.WorkId and T1.CDBNo=A.CDBNo limit 1),T1.Cid,(SELECT Id from cdb_local.etltier where Name=T1.Tier),(SELECT Id from cdb_local.cmnlistitem where Name=T1.HRName and CmnListId='599fbfdc-a250-11e4-b4d2-080027dcfac6'),T1.Qualification,T1.Point,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluationhr T1 WHERE T1.WorkId!='' and T1.WorkId in (select migratedworkid from cdb_local.etltender) and (SELECT Y.Id from cdb_local.etltender X join cdb_local.etltenderbiddercontractor Y on X.Id=Y.EtlTenderId join cdb_local.etltenderbiddercontractordetail Z on Y.Id=Z.EtlTenderBidderContractorId join cdb_local.crpcontractorfinal A on A.Id=Z.CrpContractorFinalId where X.migratedworkid=T1.WorkId and T1.CDBNo=A.CDBNo limit 1) is not null;

UPDATE `etlcontractorhumanresource` Set CIDNo = TRIM(CIDNo) WHERE 1
/*-----------------------------------------------------evaluation data for equipment-----------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorequipment`(`Id`, `EtlTenderBidderContractorId`, `RegistrationNo`, `EtlTierId`, `CmnEquipmentId`,`OwnedOrHired`,`Points`, `CreatedBy`)
SELECT UUID(),(SELECT Y.Id from cdb_local.etltender X join cdb_local.etltenderbiddercontractor Y on X.Id=Y.EtlTenderId join cdb_local.etltenderbiddercontractordetail Z on Y.Id=Z.EtlTenderBidderContractorId join cdb_local.crpcontractorfinal A on A.Id=Z.CrpContractorFinalId where X.migratedworkid=T1.WorkId and T1.CDBNo=A.CDBNo limit 1),T1.RegNo,(SELECT Id from cdb_local.etltier where Name=T1.Tier),(SELECT Id from cdb_local.cmnequipment where Name=T1.EquipmentName),case when T1.HireOwn='Owned' then 1 else 2 end,T1.Point,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluationequipment T1 WHERE T1.WorkId!='' and T1.WorkId in (select migratedworkid from cdb_local.etltender) and (SELECT Y.Id from cdb_local.etltender X join cdb_local.etltenderbiddercontractor Y on X.Id=Y.EtlTenderId join cdb_local.etltenderbiddercontractordetail Z on Y.Id=Z.EtlTenderBidderContractorId join cdb_local.crpcontractorfinal A on A.Id=Z.CrpContractorFinalId where X.migratedworkid=T1.WorkId and T1.CDBNo=A.CDBNo limit 1) is not null;
/*------------------------------------------------------etlcontractorcapacity BOBL--------------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'28254fad-a2bf-11e4-b4d2-080027dcfac6',coalesce(T1.credit1,0.00),1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
				/*-------------------for second partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'28254fad-a2bf-11e4-b4d2-080027dcfac6',coalesce(T1.credit1jv,0.00),2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>1);
/*-------------------for third partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'28254fad-a2bf-11e4-b4d2-080027dcfac6',coalesce(T1.credit1jv2,0.00),3,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>2);
/*------------------------------------------------------etlcontractorcapacity BNBL--------------------------------------------------------------------------------*/

/*------------------------------------------------------etlcontractorcapacity BNBL--------------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'327da998-a2bf-11e4-b4d2-080027dcfac6',coalesce(T1.credit2,0.00),1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
				/*-------------------for second partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'327da998-a2bf-11e4-b4d2-080027dcfac6',coalesce(T1.credit2jv,0.00),2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>1);
				/*-------------------for third partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'327da998-a2bf-11e4-b4d2-080027dcfac6',coalesce(T1.credit2jv2,0.00),3,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>2);

/*------------------------------------------------------etlcontractorcapacity Druk PNBL--------------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'d538ce19-a2c0-11e4-b4d2-080027dcfac6',coalesce(T1.credit3,0.00),1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
				/*-------------------for second partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'d538ce19-a2c0-11e4-b4d2-080027dcfac6',coalesce(T1.credit3jv,0.00),2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>1);
				/*-------------------for third partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'d538ce19-a2c0-11e4-b4d2-080027dcfac6',coalesce(T1.credit3jv2,0.00),3,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>2);

/*------------------------------------------------------etlcontractorcapacity TBank--------------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'350254c4-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit4,0.00),1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
				/*-------------------for second partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'350254c4-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit4jv,0.00),2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>1);
				/*-------------------for third partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'350254c4-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit4jv2,0.00),3,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>2);


/*------------------------------------------------------etlcontractorcapacity BDBL--------------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'10e9b61b-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit5,0.00),1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
				/*-------------------for second partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'10e9b61b-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit5jv,0.00),2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>1);
				/*-------------------for third partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'10e9b61b-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit5jv2,0.00),3,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>2);

/*------------------------------------------------------etlcontractorcapacity Others--------------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'18f896c7-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit6,0.00),1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
				/*-------------------for second partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'18f896c7-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit6jv,0.00),2,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>1);
				/*-------------------for third partner in join venture----------------------------------*/
INSERT INTO cdb_local.`etlcontractorcapacity`(`Id`, `EtlTenderBidderContractorId`, `CmnBankId`, `Amount`, `Sequence`, `CreatedBy`)
SELECT UUID(),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),'18f896c7-f4a4-11e4-8b03-080027dcfac6',coalesce(T1.credit6jv2,0.00),3,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='' and (SELECT count(X.Id) from cdb_local.etltenderbiddercontractordetail X join cdb_local.etltenderbiddercontractor Y on X.EtlTenderBidderContractorId=Y.Id join cdb_local.etltender A on Y.EtlTenderId=A.Id where A.migratedworkid=T1.WorkId>2);

/*------------------------------------------------------------etlevaluatiuonscore--------------------------------------------------------------------------*/
INSERT INTO cdb_local.`etlevaluationscore`(`Id`, `EtlTenderId`, `EtlTenderBidderContractorId`, `Score1`, `Score2`, `Score3`, `Score4`, `Score5`, `Score6`, `Score7`, `Score8`, `Score9`, `Score10`, `SmallAndRegisteredContractorPosition`)
SELECT UUID(),(SELECT X.EtlTenderId from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),(SELECT X.Id from cdb_local.etltenderbiddercontractor X WHERE X.RecordId=T1.RecordId),coalesce(T1.SWE,0.00),coalesce(T1.Equipment,0.00),coalesce(T1.HResource,0.00),coalesce(T1.APS,0.00),coalesce(T1.BidCapacity,0.00),coalesce(T1.CreditLine,0.00),coalesce(T1.OsStrength,0.00),coalesce(T1.OsEmployment,0.00),coalesce(T1.OsCommitment,0.00),coalesce(T1.TotalPoint,0.00),case when (T1.Classification='S' or T1.Classification='R') and T1.Result not in('Process','NotAwarded','NotQualified') then case when T1.Result in('Awarded','Terminated','Completed') then 1 else SUBSTRING_INDEX(T1.Result,'L',-1) end else NULL end
FROM cdbolddata.tblevaluation T1 join cdb_local.crpcontractorfinal T2 on T1.CDBNo=T2.CDBNo WHERE T1.WorkId!='';
/*--------------------------------------------------------------bidding form for crps interface (crpbiddingform)---------------------*/
/*Delete orphan records from biddingform1 comparing to biddingform and track record table*/
DELETE FROM cdbolddata.biddingform1 where Precordid not in (select Recordid from cdbolddata.biddingform);
/*---------------Add a migratedrecordid primary key column from old data ---------------------------*/
ALTER TABLE  cdb_local.`crpbiddingform` ADD  `migratedrecordid` VARCHAR(25) NOT NULL AFTER `EditedOn`;
ALTER TABLE  cdb_local.`crpbiddingform` ADD  `InsertCounter` BIGINT NOT NULL AFTER `migratedrecordid`;

-- INSERT INTO cdb_local.`crpbiddingform`(`migratedrecordid`,`InsertCounter`,`Id`, `ReferenceNo`, `Type`, `CmnProcuringAgencyId`, `NameOfWork`, `DescriptionOfWork`, `CmnContractorProjectCategoryId`, `CmnContractorClassificationId`,`ApprovedAgencyEstimate`, `NitInMediaDate`, `BidSaleClosedDate`, `CmnDzongkhagId`, `BidOpeningDate`, `AcceptanceDate`, `ContractSigningDate`, `WorkOrderNo`, `ContractPeriod`, `WorkStartDate`, `WorkCompletionDate`, `ContractPriceInitial`, `ContractPriceFinal`, `CommencementDateOffcial`, `CommencementDateFinal`, `CompletionDateOffcial`, `CompletionDateFinal`, `OntimeCompletionScore`, `QualityOfExecutionScore`, `CmnWorkExecutionStatusId`, `ByCDB`, `CreatedBy`)
-- SELECT T1.Recordid,@referenceNo:=@referenceNo+1,UUID(),T1.OrderNo,0,(SELECT Id from cdb_local.cmnprocuringagency where Code=T1.PAgency),T1.Nworks,T1.CDescription,(SELECT Id from cdb_local.cmncontractorworkcategory where Code=T1.WClassification),(SELECT Id from cdb_local.cmncontractorclassification where Code=T1.CClasification),T1.AEstimate,T1.KuenselDt,T1.BClosedDt,(SELECT Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Dzongkhag,'Thimphu')),T1.BOpenDt,T1.ACpDT,T1.CSignDt,T1.OrderNo,T1.Duration,T1.OrderDt,case when T3.CompleDt is not null then T3.CompleDt else T1.CompleDt end,T3.EvalAmount,T3.EvalAmount,T1.OrderDt,T1.OrderDt,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,T3.EvaluationOntime,T3.EvaluationQuality,case when T3.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T3.Status in ('Completed','Completec') then 'a13c5d39-b5a8-11e4-81ac-080027dcfac6' when T3.Status in ('Terminated','Contract Terminated') then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' end,1,'bf258f4b-c639-11e4-b574-080027dcfac6'
-- FROM cdbolddata.biddingform T1 JOIN cdbolddata.biddingform1 T2 on T1.Recordid=T2.Precordid JOIN cdbolddata.trackrecord T3 on T1.Recordid=T3.Recordid,(SELECT @referenceNo:= 0) as referenceNo where T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed') and CHAR_LENGTH(T1.Recordid) < 12;




-- INSERT INTO cdb_local.`crpbiddingform`(`migratedrecordid`,`InsertCounter`,`Id`, `ReferenceNo`, `Type`, `CmnProcuringAgencyId`, `NameOfWork`, `DescriptionOfWork`, `CmnContractorProjectCategoryId`, `CmnContractorClassificationId`,`ApprovedAgencyEstimate`, `NitInMediaDate`, `BidSaleClosedDate`, `CmnDzongkhagId`, `BidOpeningDate`, `AcceptanceDate`, `ContractSigningDate`, `WorkOrderNo`, `ContractPeriod`, `WorkStartDate`, `WorkCompletionDate`, `ContractPriceInitial`, `ContractPriceFinal`, `CommencementDateOffcial`, `CommencementDateFinal`, `CompletionDateOffcial`, `CompletionDateFinal`, `OntimeCompletionScore`, `QualityOfExecutionScore`, `CmnWorkExecutionStatusId`, `ByCDB`, `CreatedBy`)
-- SELECT T1.Recordid,@referenceNo:=@referenceNo+1,UUID(),T1.OrderNo,0,(SELECT Id from cdb_local.cmnprocuringagency where Code=T1.PAgency),T1.Nworks,T1.CDescription,(SELECT Id from cdb_local.cmncontractorworkcategory where Code=T1.WClassification),(SELECT Id from cdb_local.cmncontractorclassification where Code=T1.CClasification),T1.AEstimate,T1.KuenselDt,T1.BClosedDt,(SELECT Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Dzongkhag,'Thimphu')),T1.BOpenDt,T1.ACpDT,T1.CSignDt,T1.OrderNo,T1.Duration,T1.OrderDt,case when T3.CompleDt is not null then T3.CompleDt else T1.CompleDt end,T3.EvalAmount,T3.EvalAmount,T1.OrderDt,T1.OrderDt,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,T3.EvaluationOntime,T3.EvaluationQuality,case when T3.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T3.Status in ('Completed','Completec') then 'a13c5d39-b5a8-11e4-81ac-080027dcfac6' when T3.Status in ('Terminated','Contract Terminated') then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' end,1,'bf258f4b-c639-11e4-b574-080027dcfac6'
-- FROM cdbolddata.biddingform T1 JOIN cdbolddata.biddingform1 T2 on T1.Recordid=T2.Precordid LEFT JOIN cdbolddata.trackrecord T3 on T1.Recordid=T3.Recordid,(SELECT @referenceNo:= 0) as referenceNo where T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed');


INSERT INTO cdb_local.`crpbiddingform`(`migratedrecordid`,`InsertCounter`,`Id`, `ReferenceNo`, `Type`, `CmnProcuringAgencyId`, `NameOfWork`, `DescriptionOfWork`, `CmnContractorProjectCategoryId`, `CmnContractorClassificationId`,`ApprovedAgencyEstimate`, `NitInMediaDate`, `BidSaleClosedDate`, `CmnDzongkhagId`, `BidOpeningDate`, `AcceptanceDate`, `ContractSigningDate`, `WorkOrderNo`, `ContractPeriod`, `WorkStartDate`, `WorkCompletionDate`, `ContractPriceInitial`, `ContractPriceFinal`, `CommencementDateOffcial`, `CommencementDateFinal`, `CompletionDateOffcial`, `CompletionDateFinal`, `OntimeCompletionScore`, `QualityOfExecutionScore`, `CmnWorkExecutionStatusId`, `ByCDB`, `CreatedBy`)
SELECT T1.Recordid,@referenceNo:=@referenceNo+1,UUID(),T1.OrderNo,0,(SELECT Id from cdb_local.cmnprocuringagency where Code=T1.PAgency),T1.Nworks,T1.CDescription,(SELECT Id from cdb_local.cmncontractorworkcategory where Code=T1.WClassification),(SELECT Id from cdb_local.cmncontractorclassification where Code=T1.CClasification),T1.AEstimate,T1.KuenselDt,T1.BClosedDt,(SELECT Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Dzongkhag,'Thimphu')),T1.BOpenDt,T1.ACpDT,T1.CSignDt,T1.OrderNo,T1.Duration,T1.OrderDt,case when T3.CompleDt is not null then T3.CompleDt else T1.CompleDt end,T3.EvalAmount,T3.EvalAmount,T1.OrderDt,T1.OrderDt,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,T3.EvaluationOntime,T3.EvaluationQuality,case when T3.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T3.Status in ('Completed','Completec') then 'a13c5d39-b5a8-11e4-81ac-080027dcfac6' when T3.Status in ('Terminated','Contract Terminated') then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' end,1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.biddingform T1 JOIN cdbolddata.biddingform1 T2 on T1.Recordid=T2.Precordid LEFT JOIN cdbolddata.trackrecord T3 on T1.Recordid=T3.Recordid,(SELECT @referenceNo:= 0) as referenceNo where case when T3.Recordid is null then T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed') else 1 end;

INSERT INTO cdb_local.`crpbiddingform`(`migratedrecordid`,`InsertCounter`,`Id`, `ReferenceNo`, `Type`, `CmnProcuringAgencyId`, `NameOfWork`, `DescriptionOfWork`, `CmnContractorProjectCategoryId`, `CmnContractorClassificationId`,`ApprovedAgencyEstimate`, `NitInMediaDate`, `BidSaleClosedDate`, `CmnDzongkhagId`, `BidOpeningDate`, `AcceptanceDate`, `ContractSigningDate`, `WorkOrderNo`, `ContractPeriod`, `WorkStartDate`, `WorkCompletionDate`, `ContractPriceInitial`, `ContractPriceFinal`, `CommencementDateOffcial`, `CommencementDateFinal`, `CompletionDateOffcial`, `CompletionDateFinal`, `OntimeCompletionScore`, `QualityOfExecutionScore`, `CmnWorkExecutionStatusId`, `ByCDB`, `CreatedBy`)
SELECT T1.Recordid,@referenceNo:=@referenceNo+1,UUID(),T1.OrderNo,0,(SELECT Id from cdb_local.cmnprocuringagency where Code=T1.PAgency),T1.Nworks,T1.CDescription,(SELECT Id from cdb_local.cmncontractorworkcategory where Code=T1.WClassification),(SELECT Id from cdb_local.cmncontractorclassification where Code=T1.CClassification),T1.AEstimate,T1.KuenselDt,T1.BClosedDt,(SELECT Id from cdb_local.cmndzongkhag where NameEn=coalesce(T1.Dzongkhag,'Thimphu')),T1.BOpenDt,T1.ACpDT,T1.CSignDt,T1.OrderNo,T1.Duration,T1.OrderDt,case when T3.CompleDt is not null then T3.CompleDt else T1.CompleDt end,T3.EvalAmount,T3.EvalAmount,T1.OrderDt,T1.OrderDt,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,case when T3.CompleDt is not NULL then T3.CompleDt else T1.CompleDt end,T3.EvaluationOntime,T3.EvaluationQuality,case when T3.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T3.Status in ('Completed','Completec') then 'a13c5d39-b5a8-11e4-81ac-080027dcfac6' when T3.Status in ('Terminated','Contract Terminated') then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' end,1,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.pbiddingform T1 JOIN cdbolddata.pbiddingform1 T2 on T1.Recordid=T2.Precordid LEFT JOIN cdbolddata.trackrecord T3 on T1.Recordid=T3.Recordid,(SELECT @referenceNo:= 0) as referenceNo where case when T3.Recordid is null then T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed') else 1 end and T1.Recordid not in (select migratedrecordid from cdb_local.crpbiddingform);
/*-------------------------------------------------------------DELETE duplicate record id from crpbiddingform------------------*/
DELETE bf1 FROM cdb_local.crpbiddingform bf1, cdb_local.crpbiddingform bf2 WHERE bf1.InsertCounter < bf2.InsertCounter AND bf1.migratedrecordid = bf2.migratedrecordid;
/*--------------------------------------------------------------bidding form add contractor for crps interface (crpbiddingform)---------------------*/
INSERT INTO cdb_local.`crpbiddingformdetail`(`Id`, `CrpBiddingFormId`, `CrpContractorFinalId`, `BidAmount`, `EvaluatedAmount`, `CmnWorkExecutionStatusId`, `CreatedBy`)
SELECT UUID(),(SELECT Id from cdb_local.crpbiddingform where migratedrecordid=T1.Precordid),(SELECT Id from cdb_local.crpcontractorfinal where CDBNo=T1.cdbNumber),T3.BidAmount,T3.EvalAmount,case when T1.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T1.Status='Contract Terminated' then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' else NULL end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.biddingform1 T1 join cdbolddata.biddingform T2 on T1.Precordid=T2.Recordid join cdbolddata.trackrecord T3 on T2.Recordid=T3.Recordid where T1.cdbNumber is not null and T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed');

INSERT INTO cdb_local.`crpbiddingformdetail`(`Id`, `CrpBiddingFormId`, `CrpContractorFinalId`, `BidAmount`, `EvaluatedAmount`, `CmnWorkExecutionStatusId`, `CreatedBy`)
SELECT UUID(),(SELECT Id from cdb_local.crpbiddingform where migratedrecordid=T1.Precordid),(SELECT Id from cdb_local.crpcontractorfinal where CDBNo=T1.cdbNumber),T3.BidAmount,T3.EvalAmount,case when T1.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T1.Status='Contract Terminated' then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' else NULL end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.pbiddingform1 T1 join cdbolddata.pbiddingform T2 on T1.Precordid=T2.Recordid join cdbolddata.trackrecord T3 on T2.Recordid=T3.Recordid where T1.cdbNumber is not null and T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed');

INSERT INTO cdb_local.`crpbiddingformdetail`(`Id`, `CrpBiddingFormId`, `CrpContractorFinalId`, `BidAmount`, `EvaluatedAmount`, `CmnWorkExecutionStatusId`, `CreatedBy`)
SELECT UUID(),(SELECT Id from cdb_local.crpbiddingform where migratedrecordid=T1.Precordid),(SELECT Id from cdb_local.crpcontractorfinal where CDBNo=T3.CDBNo),T3.BidAmount,T3.EvalAmount,case when T1.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T1.Status='Contract Terminated' then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' else NULL end,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM cdbolddata.biddingform1 T1 join cdbolddata.biddingform T2 on T1.Precordid=T2.Recordid join cdbolddata.trackrecord T3 on T2.Recordid=T3.Recordid where T1.cdbNumber is null and T3.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed');

DELETE FROM cdb_local.`crpbiddingformdetail` WHERE Id in (SELECT A.Id from (SELECT X.Id from cdb_local.crpbiddingformdetail X group by X.CrpBiddingFormId, X.CrpContractorFinalId having count(X.BidAmount)>1) A);
//RUN ABOVE QUERY MULTIPLE TIMES TILL QUERY AFFECTED ROWS BECOMES 0

///===///
UPDATE cdb_local.crpbiddingform SET ByCDB = 0 WHERE CHAR_LENGTH(migratedrecordid) >= 12;

UPDATE `crpbiddingform` SET `OntimeCompletionScore` = NULL where `OntimeCompletionScore` = 0;
UPDATE `crpbiddingform` SET `QualityOfExecutionScore` = NULL where `QualityOfExecutionScore` = 0;

ALTER TABLE  cdb_local.`etltender` DROP  `migratedworkid`;
ALTER TABLE  cdb_local.`etltenderbiddercontractor` DROP  `RecordId`;
ALTER TABLE  cdb_local.`crpbiddingform` DROP  `migratedrecordid`;
ALTER TABLE  cdb_local.`crpbiddingform` DROP  `InsertCounter`;

/*----------------------------------INSERT DATA INTO FINAL TABLE--------------------------------------*/
INSERT INTO crparchitectfinal (Id,ReferenceNo,ApplicationDate,ARNo,CmnServiceSectorTypeId,CIDNo,CmnSalutationId,Name,CmnCountryId,CmnDzongkhagId,Gewog,Village,Email,MobileNo,EmployerName,EmployerAddress,CmnQualificationId,GraduationYear,NameOfUniversity,CmnUniversityCountryId,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
SELECT Id,ReferenceNo,ApplicationDate,ARNo,CmnServiceSectorTypeId,CIDNo,CmnSalutationId,Name,CmnCountryId,CmnDzongkhagId,Gewog,Village,Email,MobileNo,EmployerName,EmployerAddress,CmnQualificationId,GraduationYear,NameOfUniversity,CmnUniversityCountryId,'463c2d4c-adbd-11e4-99d7-080027dcfac6',RegistrationApprovedDate,RegistrationExpiryDate,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM crparchitect;


INSERT INTO crpspecializedtradefinal (Id,ReferenceNo,ApplicationDate,SPNo,CIDNo,CmnSalutationId,Name,CmnDzongkhagId,Gewog,Village,Email,MobileNo,TelephoneNo,EmployerName,EmployerAddress,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
SELECT Id,ReferenceNo,ApplicationDate,SPNo,CIDNo,CmnSalutationId,Name,CmnDzongkhagId,Gewog,Village,Email,MobileNo,TelephoneNo,EmployerName,EmployerAddress,'463c2d4c-adbd-11e4-99d7-080027dcfac6',RegistrationApprovedDate,RegistrationExpiryDate,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM crpspecializedtrade;

UPDATE crpspecializedtradefinal A SET A.CmnDzongkhagId = (select CmnDzongkhagId from crpspecializedtrade where SPNo = A.SPNo);

INSERT INTO crpspecializedtradeworkclassificationfinal (Id,CrpSpecializedTradeFinalId,CmnAppliedCategoryId,CmnVerifiedCategoryId,CmnApprovedCategoryId,CreatedBy)
SELECT UUID(),CrpSpecializedTradeId,CmnAppliedCategoryId,CmnVerifiedCategoryId,CmnApprovedCategoryId,'bf258f4b-c639-11e4-b574-080027dcfac6'
FROM crpspecializedtradeworkclassification;

INSERT INTO crpconsultantfinal(Id,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,CmnRegisteredDzongkhagId,RegisteredAddress,Village,Gewog,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
SELECT Id,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,CmnRegisteredDzongkhagId,RegisteredAddress,Village,Gewog,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,'463c2d4c-adbd-11e4-99d7-080027dcfac6',RegistrationApprovedDate,RegistrationExpiryDate,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM crpconsultant;

INSERT INTO crpconsultantworkclassificationfinal(Id,CrpConsultantFinalId,CmnServiceCategoryId,CmnAppliedServiceId,CmnVerifiedServiceId,CmnApprovedServiceId,CreatedBy)
SELECT UUID(),CrpConsultantId,CmnServiceCategoryId,CmnAppliedServiceId,CmnVerifiedServiceId,CmnApprovedServiceId,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1' FROM crpconsultantworkclassification;

INSERT INTO crpconsultantequipmentfinal(Id,CrpConsultantFinalId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,CreatedBy)
SELECT Id,CrpConsultantId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM crpconsultantequipment;

INSERT INTO crpconsultanthumanresourcefinal(Id,CrpConsultantFinalId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,CreatedBy)
SELECT Id,CrpConsultantId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,'894eba10-885b-11e5-ab33-5cf9dd5fc4f1'
FROM crpconsultanthumanresource;

INSERT INTO cdb_local.etlevaluationtrack
SELECT UUID(),work_id,operation,performed_by,update_time FROM cdbolddata.tbltrackprocess;

INSERT INTO cdb_local.etltrackequipment (Id,RecordId, WorkId,CDBNo,Tier,Equipment,RegistrationNo,OwnedOrHired,Points,Operation,User,OperationTime)
SELECT UUID(),RecordId,WorkId,CDBNo,Tier,EquipmentName,RegNo, case T1.HireOwn when 'Hired' then 2 else 1 end, Point, Operation, PerformedBy, UpdateTime FROM cdbolddata.`tblequipevaltrack` T1;

INSERT INTO cdb_local.etlreplacereleasetrack
SELECT UUID( ) , user_name, workid, cdb_no, hr_eq_id_old, hr_eq_id_new, do_op, operation, ref_doc
FROM cdbolddata.tblhreqrepreltrack;

INSERT INTO cdb_local.etltrackhumanresource
SELECT UUID(),RecordId,WorkId,CDBNO,Tier,Cid,HRName,Qualification,Point,Operation,PerformedBy,UpdateTime FROM cdbolddata.tblhrevaltrack;


-- INSERT INTO crpcontractorhumanresource
-- SELECT UUID(), (select Id from cdb_local.crpcontractor T1 WHERE T1.CDBNo = B.ci_CDBRegNum) as X, case A.cp_sex when 'Male' then '872100b3-a5f0-11e4-8ab5-080027dcfac6' else '334c7ec1-a5f4-11e4-8ab5-080027dcfac6' end, A.cp_ID_No, A.cp_Name, case A.cp_sex when 'Male' then 'M' else 'F' end, (select Id from cdb_local.cmncountry T2 where T2.Nationality = coalesce(A.cp_Nationality,'Bhutanese')) as Country, NULL,NULL,'0cbd1ba3-82de-11e5-8b98-5cf9dd5fc4f1',1,1,1,1,/*B.InitialDT,*/'894eba10-885b-11e5-ab33-5cf9dd5fc4f1',NULL,NOW(),NULL from cdbolddata.contractorpartner A join cdbolddata.contractorconsultant B on A.cp_ci_key = B.ci_key where B.ci_Contractor = 1 group by A.cp_ID_no having count(distinct A.cp_ID_no) = 1;
--
-- INSERT INTO crpcontractorhumanresourcefinal
-- SELECT Id, CrpContractorId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,NULL,CreatedBy,EditedBy,CreatedOn,EditedOn from crpcontractorhumanresource where CreatedOn >= DATE_FORMAT(NOW(),'%Y-%m-%d');

-- UPDATE cdb_local.crpcontractorhumanresourcefinal T1 SET T1.JoiningDate = (select T1.InitialDT from cdbolddata.contractorconsultant T1 join (cdb_local.crpcontractorhumanresource T2 join cdb_local.crpcontractorfinal T3 on T2.CrpContractorId = T3.Id) on T3.CDBNo = T1.ci_CDBRegNum limit 1) where T1.CreatedOn >= DATE_FORMAT(NOW(),'%Y-%m-%d');



SELECT DATE_FORMAT(NOW(),'%Y-%m-%d');

UPDATE cdb_local.`etltender` T1 SET T1.CompletionDateFinal = (select EndDt from cdbolddata.tblevaluation where WorkId = T1.migratedworkid and StartDt <> '0000-00-00 00:00:00' limit 1) WHERE CompletionDateFinal = '0000-00-00' and T1.CmnworkExecutionStatusId in ('1ec69344-a256-11e4-b4d2-080027dcfac6','a13c5d39-b5a8-11e4-81ac-080027dcfac6');
UPDATE cdb_local.`etltender` T1 SET T1.CommencementDateFinal = (select StartDt from cdbolddata.tblevaluation where WorkId = T1.migratedworkid and StartDt <> '0000-00-00 00:00:00' limit 1) WHERE CommencementDateFinal = '0000-00-00' and T1.CmnworkExecutionStatusId in ('1ec69344-a256-11e4-b4d2-080027dcfac6','a13c5d39-b5a8-11e4-81ac-080027dcfac6');

UPDATE cdb_local.etltender T1 SET T1.CommencementDateFinal = (select TStartDt from cdbolddata.tblworkidt0 where WorkId = T1.migratedworkid) where (T1.CommencementDateFinal = '0000-00-00' or T1.CommencementDateFinal is null) and T1.CmnworkExecutionStatusId in ('1ec69344-a256-11e4-b4d2-080027dcfac6','a13c5d39-b5a8-11e4-81ac-080027dcfac6');
UPDATE cdb_local.etltender T1 SET T1.CompletionDateFinal = (select TCompleDt from cdbolddata.tblworkidt0 where WorkId = T1.migratedworkid) where (T1.CompletionDateFinal = '0000-00-00' or T1.CompletionDateFinal is null) and T1.CmnworkExecutionStatusId in ('1ec69344-a256-11e4-b4d2-080027dcfac6','a13c5d39-b5a8-11e4-81ac-080027dcfac6');

UPDATE sysuser T1 SET CmnProcuringAgencyId = (select Id from cmnprocuringagency where (Code =  (select A.PAgency from pa_users A where concat(A.login_name,'@cinet.bt') = T1.username)) or (Name = (select A.PAName from pa_users A where concat(A.login_name,'@cinet.bt') = T1.username))) where T1.username like '%@cinet.bt';
UPDATE sysuser T1 SET CmnProcuringAgencyId = (select Id from cmnprocuringagency where (Code =  (select A.PAgency from pa_users A where concat(A.login_name,'@etool.bt') = T1.username)) or (Name = (select A.PAName from pa_users A where concat(A.login_name,'@etool.bt') = T1.username))) where T1.username like '%@etool.bt';
UPDATE sysuser T1 SET CmnProcuringAgencyId = (select Id from cmnprocuringagency where (Name = (select A.PAName from users A where concat(A.login_name,'@cinet.bt') = T1.username))) where T1.username like '%@cinet.bt';

UPDATE `tblworkidtrack` SET operation = 'Report 8' WHERE operation = 'Report 7';



--  DELETE FROM cdb_local.`crpcontractorhumanresourcefinal` WHERE Id in (SELECT A.Id from (SELECT X.Id from cdb_local.crpcontractorhumanresourcefinal X group by X.CIDNo having count(CIDNo)>1) A);

select `t1`.`WorkOrderNo` AS `WorkOrderNo`,`t1`.`Remarks` AS `Remarks`,NULL AS `WorkId`,`t1`.`NameOfWork` AS `NameOfWork`,`t1`.`DescriptionOfWork` AS `DescriptionOfWork`,
`t1`.`ContractPeriod` AS `ContractPeriod`,`t1`.`WorkStartDate` AS `WorkStartDate`,`t1`.`WorkCompletionDate` AS `WorkCompletionDate`,(case `t8`.`ReferenceNo` when 3001 then
`t2`.`EvaluatedAmount` else `t1`.`ContractPriceFinal` end) AS `FinalAmount`,`t2`.`CrpContractorFinalId` AS `CrpContractorFinalId`,`t1`.`OntimeCompletionScore` AS
`OntimeCompletionScore`,`t1`.`QualityOfExecutionScore` AS `QualityOfExecutionScore`,`t2`.`BidAmount` AS `BidAmount`,`t2`.`EvaluatedAmount` AS `EvaluatedAmount`,
`t3`.`Name` AS `ProcuringAgency`,`t4`.`Code` AS `ProjectCategory`,`t5`.`Name` AS `classification`,`t6`.`NameEn` AS `Dzongkhag`,`t7`.`NameOfFirm` AS `Contractor`,
`t7`.`CDBNo` AS `CDBNo`,`t8`.`Name` AS `WorkStatus`,`t8`.`ReferenceNo` AS `ReferenceNo`,`t1`.`migratedrecordid` AS `RecordId` from (((((((`cdb_local`.`crpbiddingform` `t1`
join `cdb_local`.`crpbiddingformdetail` `t2` on(((`t1`.`Id` = `t2`.`CrpBiddingFormId`) and (`t2`.`CmnWorkExecutionStatusId` = '1ec69344-a256-11e4-b4d2-080027dcfac6'))))
join `cdb_local`.`cmnprocuringagency` `t3` on((`t1`.`CmnProcuringAgencyId` = `t3`.`Id`))) join `cdb_local`.`cmnlistitem` `t8` on((`t1`.`CmnWorkExecutionStatusId` = `t8`.`Id`)))
join `cdb_local`.`cmncontractorworkcategory` `t4` on((`t1`.`CmnContractorProjectCategoryId` = `t4`.`Id`))) join `cdb_local`.`cmncontractorclassification` `t5`
on((`t1`.`CmnContractorClassificationId` = `t5`.`Id`))) join `cdb_local`.`cmndzongkhag` `t6` on((`t1`.`CmnDzongkhagId` = `t6`.`Id`))) join `cdb_local`.`crpcontractorfinal` `t7`
on((`t2`.`CrpContractorFinalId` = `t7`.`Id`))) union all select `t1`.`ReferenceNo` AS `WorkOrderNo`,`t1`.`Remarks` AS `Remarks`,
concat(`t4`.`Code`,'/',year(`t1`.`DateOfSaleOfTender`),'/',`t1`.`WorkId`) AS `WOrkId`,`t1`.`NameOfWork` AS `NameOfWork`,`t1`.`DescriptionOfWork` AS `DescriptionOfWork`,
`t1`.`ContractPeriod` AS `ContractPeriod`,coalesce(`t1`.`CommencementDateFinal`,`t2`.`ActualStartDate`) AS `WorkStartDate`,coalesce(`t1`.`CompletionDateFinal`,`t2`.`ActualEndDate`)
AS `WorkCompletionDate`,(case `t9`.`ReferenceNo` when 3001 then `t2`.`AwardedAmount` else `t1`.`ContractPriceFinal` end) AS `FinalAmount`,`t3`.`CrpContractorFinalId` AS
`CrpContractorFinalId`,`t1`.`OntimeCompletionScore` AS `OntimeCompletionScore`,`t1`.`QualityOfExecutionScore` AS `QualityOfExecutionScore`,`t2`.`FinancialBidQuoted` AS
`FinancialBidQuoted`,`t2`.`AwardedAmount` AS `AwardedAmount`,`t4`.`Name` AS `ProcuringAgency`,`t5`.`Code` AS `ProjectCategory`,`t6`.`Name` AS `Classification`,`t7`.`NameEn`
AS `Dzongkhag`,`t8`.`NameOfFirm` AS `Contractor`,`t8`.`CDBNo` AS `CDBNo`,`t9`.`Name` AS `WorkStatus`,`t9`.`ReferenceNo` AS `ReferenceNo`,`t1`.`migratedworkid` AS `RecordId`
from ((((((((`cdb_local`.`etltender` `t1` join `cdb_local`.`etltenderbiddercontractor` `t2` on(((`t1`.`Id` = `t2`.`EtlTenderId`) and (`t2`.`ActualStartDate` is not null))))
join `cdb_local`.`etltenderbiddercontractordetail` `t3` on((`t2`.`Id` = `t3`.`EtlTenderBidderContractorId`))) join `cdb_local`.`cmnlistitem` `t9`
on((`t1`.`CmnWorkExecutionStatusId` = `t9`.`Id`))) join `cdb_local`.`cmnprocuringagency` `t4` on((`t1`.`CmnProcuringAgencyId` = `t4`.`Id`))) join
`cdb_local`.`cmncontractorworkcategory` `t5` on((`t1`.`CmnContractorCategoryId` = `t5`.`Id`))) join `cdb_local`.`cmncontractorclassification` `t6`
on((`t1`.`CmnContractorClassificationId` = `t6`.`Id`))) join `cdb_local`.`cmndzongkhag` `t7` on((`t1`.`CmnDzongkhagId` = `t7`.`Id`))) join `cdb_local`.`crpcontractorfinal` `t8`
 on((`t3`.`CrpContractorFinalId` = `t8`.`Id`))) where (`t1`.`CmnWorkExecutionStatusId` is not null);

 SELECT T1.Id FROM cdb_local.`crpbiddingformdetail` T1 join cdb_local.crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId join crpbiddingform T3 on T3.Id = T1.CrpBiddingFormId and T1.CmnWorkExecutionStatusId = '1ec69344-a256-11e4-b4d2-080027dcfac6' where T2.CDBNo not in (select A.CDBNo from cdb_local.trackrecord A where A.Recordid = T3.migratedrecordid)



 SELECT T1.CDBNo,
(select count(B.CIDNo) from cdb_local.crpcontractorhumanresourcefinal B where B.CrpContractorFinalId = T1.Id and IsPartnerOrOwner = 1) as PartnerCountInNewDB, (select count(C.pkey) from cdbolddata.contractorpartner C join cdbolddata.contractorconsultant D on C.cp_ci_key = D.ci_key where D.ci_CDBRegNum = T1.CDBNo) PartnerCountInOldDB,
(select count(B.CIDNo) from cdb_local.crpcontractorhumanresourcefinal B where B.CrpContractorFinalId = T1.Id and IsPartnerOrOwner = 0) as EmployeeCountInNewDB,
(select count(coalesce(E.IDNo,'xx')) from cdbolddata.employeedetails E where TRIM(E.cdbNumber) = T1.CDBNo) as EmployeeCountInOldDB
FROM cdb_local.crpcontractorfinal T1;

SELECT T1.* FROM `appequipment` T1 join application T2 on T1.ae_ai_key = T2.ai_key join contractorconsultant T3 on T3.ci_key = T2.ai_ci_key where T3.ci_Contractor = 1 and  coalesce(T1.ae_Description,'') <> '';



-- INSERT INTO cdb_local.`etltender` (`Id`, `ReferenceNo`, `NameOfWork`, `DescriptionOfWork`, `CmnProcuringAgencyId`, `CmnContractorCategoryId`, `CmnContractorClassificationId`, `ContractPeriod`, `DateOfSaleOfTender`, `DateOfClosingSaleOfTender`, `LastDateAndTimeOfSubmission`, `TenderOpeningDateAndTime`, `CostOfTender`, `EMD`, `ProjectEstimateCost`, `ShowCostInWebsite`, `TentativeStartDate`, `TentativeEndDate`, `ContactPerson`, `PublishInWebsite`, `TenderSource`, `ContractPriceInitial`, `ContractPriceFinal`, `CommencementDateOfficial`, `CommencementDateFinal`, `CompletionDateOfficial`, `CompletionDateFinal`, `CmnWorkExecutionStatusId`, `OntimeCompletionScore`, `QualityOfExecutionScore`, `CreatedBy`)
-- SELECT UUID(),T1.refNo,T1.name,T1.description,(SELECT Id from cdb_local.cmnprocuringagency where Code=T1.PAgency or Name = T1.PAgency),(select Id from cdb_local.cmncontractorworkcategory where Code=T1.Category),(select Id from cdb_local.cmncontractorclassification where Code=T1.class),T1.cperiod,T1.stSaleDt,T1.endSaleDt,T1.ls_date,T1.op_date,T1.costofTender,T1.emd,T1.Aestimate,0,T1.tstartdt,T1.tcompletedt,T1.contactperson,case when T1.status='Y' then 1 else 0 end,2,T2.BidAmount,T2.EvalAmount,T2.Startdt,T2.Startdt,T2.CompleDt,T2.CompleDt,case when T2.Status in ('Awarded','Working') then '1ec69344-a256-11e4-b4d2-080027dcfac6' when T2.Status in ('Completed','Completec') then 'a13c5d39-b5a8-11e4-81ac-080027dcfac6' when T2.Status in ('Terminated','Contract Terminated') then '9cc4dab5-b5a8-11e4-81ac-080027dcfac6' end,T2.EvaluationOntime,T2.EvaluationQuality,'bf258f4b-c639-11e4-b574-080027dcfac6'
-- FROM cdbolddata.tblworkidt0 T1 left join cdbolddata.trackrecord T2 on T1.WorkId=T2.WorkId and T2.Status in('Awarded','Terminated','Completec','Contract Terminated','Working','Completed');
