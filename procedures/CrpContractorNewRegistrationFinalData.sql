BEGIN
	/*Procedure By Kinley Nidup; WEb Name:ZeroCool; Facbook Link: https://www.facebook.com/kgyel*/
	/*Procedure Name:ProCrpContractorNewRegistrationFinalData Procedure Parameters: PSysUserId,PCrpContractorId,PSysCreatedByUserId,PCmnApplicationRegistrationStatusId*/
	
	INSERT INTO crpcontractorfinal(Id,SysUserId,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,RegisteredAddress,CmnRegisteredDzongkhagId,Gewog,Village,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
	SELECT Id,PSysUserId,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,RegisteredAddress,CmnRegisteredDzongkhagId,Gewog,Village,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,PCmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,PSysCreatedByUserId
	FROM crpcontractor WHERE Id=PCrpContractorId;

	INSERT INTO crpcontractorattachmentfinal(Id,CrpContractorFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpContractorId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpcontractorattachment T1 WHERE T1.CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorworkclassificationfinal(Id,CrpContractorFinalId,CmnProjectCategoryId,CmnAppliedClassificationId,CmnVerifiedClassificationId,CmnApprovedClassificationId,CreatedBy)
	SELECT UUID(),CrpContractorId,CmnProjectCategoryId,CmnAppliedClassificationId,CmnVerifiedClassificationId,CmnApprovedClassificationId,PSysCreatedByUserId
	FROM crpcontractorworkclassification WHERE CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorequipmentfinal(Id,CrpContractorFinalId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,CreatedBy)
	SELECT Id,CrpContractorId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,PSysCreatedByUserId
	FROM crpcontractorequipment WHERE CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorequipmentattachmentfinal(Id,CrpContractorEquipmentFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpContractorEquipmentId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpcontractorequipment T1 JOIN crpcontractorequipmentattachment T2 ON T1.Id=T2.CrpContractorEquipmentId WHERE T1.CrpContractorId=PCrpContractorId;
	
	INSERT INTO crpcontractorhumanresourcefinal(Id,CrpContractorFinalId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,CreatedBy)
	SELECT Id,CrpContractorId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,PSysCreatedByUserId
	FROM crpcontractorhumanresource WHERE CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorhumanresourceattachmentfinal(Id,CrpContractorHumanResourceFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpContractorHumanResourceId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpcontractorhumanresource T1 JOIN crpcontractorhumanresourceattachment T2 on T1.Id=T2.CrpContractorHumanResourceId WHERE T1.CrpContractorId=PCrpContractorId;

END