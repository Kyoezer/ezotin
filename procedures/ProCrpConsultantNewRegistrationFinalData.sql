BEGIN
	/*Procedure By Kinley Nidup; WEb Name:ZeroCool; Facbook Link: https://www.facebook.com/kgyel*/
	/*Procedure Name:ProCrpConsultantNewRegistrationFinalData Procedure Parameters: PSysUserId,PCrpConsultantId,PSysCreatedByUserId,PCmnApplicationRegistrationStatusId*/
	
	INSERT INTO crpconsultantfinal(Id,SysUserId,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,CmnRegisteredDzongkhagId,RegisteredAddress,Village,Gewog,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
	SELECT Id,PSysUserId,ReferenceNo,ApplicationDate,CDBNo,CmnOwnershipTypeId,NameOfFirm,CmnRegisteredDzongkhagId,RegisteredAddress,Village,Gewog,Address,CmnCountryId,CmnDzongkhagId,Email,TelephoneNo,MobileNo,FaxNo,PCmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,PSysCreatedByUserId
	FROM crpconsultant WHERE Id=PCrpConsultantId;

	INSERT INTO crpconsultantattachmentfinal(Id,CrpConsultantFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpConsultantId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpconsultantattachment T1 WHERE T1.CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultantworkclassificationfinal(Id,CrpConsultantFinalId,CmnServiceCategoryId,CmnAppliedServiceId,CmnVerifiedServiceId,CmnApprovedServiceId,CreatedBy)
	SELECT UUID(),CrpConsultantId,CmnServiceCategoryId,CmnAppliedServiceId,CmnVerifiedServiceId,CmnApprovedServiceId,PSysCreatedByUserId
	FROM crpconsultantworkclassification WHERE CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultantequipmentfinal(Id,CrpConsultantFinalId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,CreatedBy)
	SELECT Id,CrpConsultantId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,PSysCreatedByUserId
	FROM crpconsultantequipment WHERE CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultantequipmentattachmentfinal(Id,CrpConsultantEquipmentFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpConsultantEquipmentId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpconsultantequipment T1 JOIN crpconsultantequipmentattachment T2 ON T1.Id=T2.CrpConsultantEquipmentId WHERE T1.CrpConsultantId=PCrpConsultantId;
	
	INSERT INTO crpconsultanthumanresourcefinal(Id,CrpConsultantFinalId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,CreatedBy)
	SELECT Id,CrpConsultantId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,PSysCreatedByUserId
	FROM crpconsultanthumanresource WHERE CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultanthumanresourceattachmentfinal(Id,CrpConsultantHumanResourceFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpConsultantHumanResourceId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpconsultanthumanresource T1 JOIN crpconsultanthumanresourceattachment T2 on T1.Id=T2.CrpConsultantHumanResourceId WHERE T1.CrpConsultantId=PCrpConsultantId;

END