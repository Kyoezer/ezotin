BEGIN
	/*Procedure By Kinley Nidup; WEb Name:ZeroCool; Facbook Link: https://www.facebook.com/kgyel*/
	/*Procedure Name:ProCrpSpecializedTradeNewRegistrationFinalData Procedure Parameters: PSysUserId,PCrpSpecializedTradeId,PSysCreatedByUserId,PCmnApplicationRegistrationStatusId*/
	INSERT INTO crpspecializedtradefinal (Id,SysUserId,ReferenceNo,ApplicationDate,SPNo,CIDNo,CmnSalutationId,Name,CmnDzongkhagId,Gewog,Village,Email,MobileNo,TelephoneNo,EmployerName,EmployerAddress,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
	SELECT Id,PSysUserId,ReferenceNo,ApplicationDate,SPNo,CIDNo,CmnSalutationId,Name,CmnDzongkhagId,Gewog,Village,Email,MobileNo,TelephoneNo,EmployerName,EmployerAddress,PCmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,PSysCreatedByUserId
	FROM crpspecializedtrade WHERE Id=PCrpSpecializedTradeId;

	INSERT INTO crpspecializedtradeworkclassificationfinal (Id,CrpSpecializedTradeFinalId,CmnAppliedCategoryId,CmnVerifiedCategoryId,CmnApprovedCategoryId,CreatedBy)
	SELECT UUID(),CrpSpecializedTradeId,CmnAppliedCategoryId,CmnVerifiedCategoryId,CmnApprovedCategoryId,PSysCreatedByUserId
	FROM crpspecializedtradeworkclassification WHERE CrpSpecializedTradeId=PCrpSpecializedTradeId;
	
	INSERT INTO crpspecializedtradeattachmentfinal(Id,CrpSpecializedTradeFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpSpecializedTradeId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpspecializedtradeattachment WHERE CrpSpecializedTradeId=PCrpSpecializedTradeId;
END



