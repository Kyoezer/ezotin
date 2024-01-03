BEGIN
	DECLARE VRegistrationStatus CHAR(36) DEFAULT (SELECT Id FROM cmnlistitem WHERE ReferenceNo=12003 LIMIT 1);
	DECLARE VRenewalServiceTypeId CHAR(36) DEFAULT (SELECT Id FROM crpservice WHERE ReferenceNo=2 LIMIT 1);
	DECLARE VHasRenewal INT DEFAULT (SELECT COUNT(Id) FROM crpcontractorappliedservice WHERE CrpContractorId=PCrpContractorId AND CmnServiceTypeId=VRenewalServiceTypeId);
	IF VHasRenewal=1 THEN
		UPDATE crpcontractorfinal T1 INNER JOIN crpcontractor T2 ON T1.Id = T2.CrpContractorId 
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
		T1.RegistrationExpiryDate=T2.RegistrationExpiryDate,
		T1.CreatedBy=PSysCreatedByUserId
		WHERE T1.Id=PCrpFinalContractorId;
	ELSE
		UPDATE crpcontractorfinal T1 INNER JOIN crpcontractor T2 ON T1.Id = T2.CrpContractorId 
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
		T1.CreatedBy=PSysCreatedByUserId
		WHERE T1.Id=PCrpFinalContractorId;
	END IF;

	INSERT INTO crpcontractorattachmentfinal(Id,CrpContractorFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpContractorId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpcontractorattachment T1 WHERE T1.CrpContractorId=PCrpContractorId;

	DELETE FROM crpcontractorworkclassificationfinal WHERE CrpContractorFinalId=PCrpFinalContractorId;
	INSERT INTO crpcontractorworkclassificationfinal(Id,CrpContractorFinalId,CmnProjectCategoryId,CmnAppliedClassificationId,CmnVerifiedClassificationId,CmnApprovedClassificationId,CreatedBy)
	SELECT UUID(),PCrpFinalContractorId,CmnProjectCategoryId,CmnAppliedClassificationId,CmnVerifiedClassificationId,CmnApprovedClassificationId,PSysCreatedByUserId
	FROM crpcontractorworkclassification 
	WHERE CrpContractorId=PCrpContractorId;


	INSERT INTO crpcontractorhumanresourcefinal(Id,CrpContractorFinalId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,CreatedBy)
	SELECT UUID(),PCrpFinalContractorId,CmnSalutationId,CIDNo,Name,Sex,CmnCountryId,CmnQualificationId,CmnTradeId,CmnDesignationId,ShowInCertificate,IsPartnerOrOwner,PSysCreatedByUserId
	FROM crpcontractorhumanresource 
	WHERE CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorhumanresourceattachmentfinal(Id,CrpContractorHumanResourceFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpContractorHumanResourceId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpcontractorhumanresource T1 JOIN crpcontractorhumanresourceattachment T2 on T1.Id=T2.CrpContractorHumanResourceId 
	WHERE T1.CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorequipmentfinal(Id,CrpContractorFinalId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,CreatedBy)
	SELECT UUID(),PCrpFinalContractorId,CmnEquipmentId,RegistrationNo,SerialNo,ModelNo,Quantity,PSysCreatedByUserId
	FROM crpcontractorequipment WHERE CrpContractorId=PCrpContractorId;

	INSERT INTO crpcontractorequipmentattachmentfinal(Id,CrpContractorEquipmentFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpContractorEquipmentId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpcontractorequipment T1 JOIN crpcontractorequipmentattachment T2 ON T1.Id=T2.CrpContractorEquipmentId 
	WHERE T1.CrpContractorId=PCrpContractorId;
END