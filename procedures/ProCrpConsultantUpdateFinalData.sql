BEGIN
	DECLARE VRegistrationStatus CHAR(36) DEFAULT (SELECT Id FROM cmnlistitem WHERE ReferenceNo=12003);
	DECLARE VRenewalServiceTypeId CHAR(36) DEFAULT (SELECT Id FROM crpservice WHERE ReferenceNo=2 LIMIT 1);
	DECLARE VHasRenewal INT DEFAULT (SELECT COUNT(Id) FROM crpconsultantappliedservice WHERE CrpConsultantId=PCrpConsultantId AND CmnServiceTypeId=VRenewalServiceTypeId);
	IF VHasRenewal=1 THEN
		UPDATE crpconsultantfinal T1 INNER JOIN crpconsultant T2 ON T1.Id = T2.CrpConsultantId 
		SET
		T1.ApplicationDate=T2.ApplicationDate,
		T1.CmnOwnershipTypeId=T2.CmnOwnershipTypeId,
		T1.NameOfFirm=T2.NameOfFirm,
		T1.CmnRegisteredDzongkhagId=T2.CmnRegisteredDzongkhagId,
		T1.Gewog=T2.Gewog,
		T1.Village=T2.Village,
		T1.Address=T2.Address,
		T1.CmnCountryId=T2.CmnCountryId,
		T1.CmnDzongkhagId=T2.CmnDzongkhagId,
		T1.Email=T2.Email,
		T1.TelephoneNo=T2.TelephoneNo,
		T1.MobileNo=T2.MobileNo,
		T1.FaxNo=T2.FaxNo,
		T1.CmnApplicationRegistrationStatusId=VRegistrationStatus,
		T1.RegistrationApprovedDate=T1.RegistrationExpiryDate,
		T1.RegistrationExpiryDate=T2.RegistrationExpiryDate
		WHERE T1.Id=PCrpFinalConsultantId;

	ELSE

		UPDATE crpconsultantfinal T1 INNER JOIN crpconsultant T2 ON T1.Id = T2.CrpConsultantId 
		SET 
		T1.ApplicationDate=T2.ApplicationDate,
		T1.CmnOwnershipTypeId=T2.CmnOwnershipTypeId,
		T1.NameOfFirm=T2.NameOfFirm,
		T1.CmnRegisteredDzongkhagId=T2.CmnRegisteredDzongkhagId,
		T1.Gewog=T2.Gewog,
		T1.Village=T2.Village,
		T1.Address=T2.Address,
		T1.CmnCountryId=T2.CmnCountryId,
		T1.CmnDzongkhagId=T2.CmnDzongkhagId,
		T1.Email=T2.Email,
		T1.TelephoneNo=T2.TelephoneNo,
		T1.MobileNo=T2.MobileNo,
		T1.FaxNo=T2.FaxNo
		WHERE T1.Id=PCrpFinalConsultantId;


	END IF;

	INSERT INTO crpconsultantattachmentfinal(Id,CrpConsultantFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),PCrpFinalConsultantId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpconsultantattachment T1 WHERE T1.CrpConsultantId=PCrpConsultantId;

	DELETE FROM crpconsultantworkclassificationfinal WHERE CrpConsultantFinalId=PCrpFinalConsultantId;
	INSERT INTO crpconsultantworkclassificationfinal(Id,CrpConsultantFinalId,CmnServiceCategoryId,CmnAppliedServiceId,CmnVerifiedServiceId,CmnApprovedServiceId,CreatedBy)
	SELECT UUID(),PCrpFinalConsultantId,CmnServiceCategoryId,CmnAppliedServiceId,CmnVerifiedServiceId,CmnApprovedServiceId,PSysCreatedByUserId
	FROM crpconsultantworkclassification WHERE CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultantequipmentfinal(Id,CrpConsultantFinalId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,CreatedBy)
	SELECT UUID(),PCrpFinalConsultantId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,PSysCreatedByUserId
	FROM crpconsultantequipment WHERE CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultantequipmentattachmentfinal(Id,CrpConsultantEquipmentFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpConsultantEquipmentId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpconsultantequipment T1 JOIN crpconsultantequipmentattachment T2 ON T1.Id=T2.CrpConsultantEquipmentId 
	WHERE T1.CrpConsultantId=PCrpConsultantId;
	
	INSERT INTO crpconsultanthumanresourcefinal(Id,CrpConsultantFinalId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,CreatedBy)
	SELECT UUID(),PCrpFinalConsultantId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,PSysCreatedByUserId
	FROM crpconsultanthumanresource WHERE CrpConsultantId=PCrpConsultantId;

	INSERT INTO crpconsultanthumanresourceattachmentfinal(Id,CrpConsultantHumanResourceFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpConsultantHumanResourceId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpconsultanthumanresource T1 JOIN crpconsultanthumanresourceattachment T2 on T1.Id=T2.CrpConsultantHumanResourceId 
	WHERE T1.CrpConsultantId=PCrpConsultantId;
	
END