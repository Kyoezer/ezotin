BEGIN

	DECLARE VRegistrationStatus CHAR(36) DEFAULT (SELECT Id FROM cmnlistitem WHERE ReferenceNo=12003);
	DECLARE VRenewalServiceTypeId CHAR(36) DEFAULT (SELECT Id FROM crpservice WHERE ReferenceNo=2 LIMIT 1);
	DECLARE VHasRenewal INT DEFAULT (SELECT COUNT(Id) FROM crpspecializedtradeappliedservice WHERE CrpSpecializedTradeId=PCrpFinalSpecializedTradeId AND CmnServiceTypeId=VRenewalServiceTypeId);
	IF VHasRenewal=1 THEN
		UPDATE crpspecializedtradefinal T1 INNER JOIN crpspecializedtrade T2 ON T1.Id = T2.CrpSpecializedTradeId 
		SET
		T1.ApplicationDate=T2.ApplicationDate,
		T1.CIDNo=T2.CIDNo,
		T1.CmnSalutationId=T2.CmnSalutationId,
		T1.Name=T2.Name,
		T1.CmnDzongkhagId=T2.CmnDzongkhagId,
		T1.Gewog=T2.Gewog,
		T1.Village=T2.Village,
		T1.Email=T2.Email,
		T1.MobileNo=T2.MobileNo,
		T1.TelephoneNo=T2.TelephoneNo,
		T1.EmployerName=T2.EmployerName,
		T1.EmployerAddress=T2.EmployerAddress,
		T1.CmnApplicationRegistrationStatusId=VRegistrationStatus,
		T1.RegistrationApprovedDate=T1.RegistrationExpiryDate,
		T1.RegistrationExpiryDate=T2.RegistrationExpiryDate
		WHERE T1.Id=PCrpFinalSpecializedTradeId;

	ELSE

		UPDATE crpspecializedtradefinal T1 INNER JOIN crpspecializedtrade T2 ON T1.Id = T2.CrpSpecializedTradeId 
		SET 
		T1.ApplicationDate=T2.ApplicationDate,
		T1.CIDNo=T2.CIDNo,
		T1.CmnSalutationId=T2.CmnSalutationId,
		T1.Name=T2.Name,
		T1.CmnDzongkhagId=T2.CmnDzongkhagId,
		T1.Gewog=T2.Gewog,
		T1.Village=T2.Village,
		T1.Email=T2.Email,
		T1.MobileNo=T2.MobileNo,
		T1.TelephoneNo=T2.TelephoneNo,
		T1.EmployerName=T2.EmployerName,
		T1.EmployerAddress=T2.EmployerAddress
		WHERE T1.Id=PCrpFinalSpecializedTradeId;


	END IF;
	DELETE FROM crpspecializedtradeworkclassificationfinal WHERE CrpSpecializedTradeFinalId=PCrpFinalSpecializedTradeId;
	INSERT INTO crpspecializedtradeworkclassificationfinal (Id,CrpSpecializedTradeFinalId,CmnAppliedCategoryId,CmnVerifiedCategoryId,CmnApprovedCategoryId,CreatedBy)
	SELECT UUID(),PCrpFinalSpecializedTradeId,CmnAppliedCategoryId,CmnVerifiedCategoryId,CmnApprovedCategoryId,PSysCreatedByUserId
	FROM crpspecializedtradeworkclassification WHERE CrpSpecializedTradeId=PCrpSpecializedTradeId;
	
	INSERT INTO crpspecializedtradeattachmentfinal(Id,CrpSpecializedTradeFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),PCrpFinalSpecializedTradeId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpspecializedtradeattachment WHERE CrpSpecializedTradeId=PCrpSpecializedTradeId;
END



