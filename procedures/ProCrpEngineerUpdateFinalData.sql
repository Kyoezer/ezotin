BEGIN
	
	DECLARE VRegistrationStatus CHAR(36) DEFAULT (SELECT Id FROM cmnlistitem WHERE ReferenceNo=12003);
	DECLARE VRenewalServiceTypeId CHAR(36) DEFAULT (SELECT Id FROM crpservice WHERE ReferenceNo=2 LIMIT 1);
	DECLARE VHasRenewal INT DEFAULT (SELECT COUNT(Id) FROM crpcontractorappliedservice WHERE CrpContractorId=PCrpContractorId AND CmnServiceTypeId=VRenewalServiceTypeId);
	IF VHasRenewal=1 THEN
		UPDATE crpengineerfinal T1 INNER JOIN crpengineer T2 ON T1.Id = T2.CrpEngineerId 
		SET
		T1.ApplicationDate=T2.ApplicationDate,
		T1.CmnServiceSectorTypeId=T2.CmnServiceSectorTypeId,
		T1.CmnTradeId=T2.CmnTradeId,
		T1.CIDNo=T2.CIDNo,
		T1.CmnSalutationId=T2.CmnSalutationId,
		T1.Name=T2.Name,
		T1.CmnCountryId=T2.CmnCountryId,
		T1.CmnDzongkhagId=T2.CmnDzongkhagId,
		T1.Gewog=T2.Gewog,
		T1.Village=T2.Village,
		T1.Email=T2.Email,
		T1.MobileNo=T2.MobileNo,
		T1.EmployerName=T2.EmployerName,
		T1.EmployerAddress=T2.EmployerAddress,
		T1.CmnQualificationId=T2.CmnQualificationId,
		T1.GraduationYear=T2.GraduationYear,
		T1.NameOfUniversity=T2.NameOfUniversity,
		T1.CmnUniversityCountryId=T2.CmnUniversityCountryId,
		T1.CmnApplicationRegistrationStatusId=VRegistrationStatus,
		T1.RegistrationApprovedDate=T1.RegistrationExpiryDate,
		T1.RegistrationExpiryDate=T2.RegistrationExpiryDate
		WHERE T1.Id=PCrpFinalEngineerId;

	ELSE

		UPDATE crpengineerfinal T1 INNER JOIN crpengineer T2 ON T1.Id = T2.CrpEngineerId 
		SET 
		T1.ApplicationDate=T2.ApplicationDate,
		T1.CmnServiceSectorTypeId=T2.CmnServiceSectorTypeId,
		T1.CmnTradeId=T2.CmnTradeId,
		T1.CIDNo=T2.CIDNo,
		T1.CmnSalutationId=T2.CmnSalutationId,
		T1.Name=T2.Name,
		T1.CmnCountryId=T2.CmnCountryId,
		T1.CmnDzongkhagId=T2.CmnDzongkhagId,
		T1.Gewog=T2.Gewog,
		T1.Village=T2.Village,
		T1.Email=T2.Email,
		T1.MobileNo=T2.MobileNo,
		T1.EmployerName=T2.EmployerName,
		T1.EmployerAddress=T2.EmployerAddress,
		T1.CmnQualificationId=T2.CmnQualificationId,
		T1.GraduationYear=T2.GraduationYear,
		T1.NameOfUniversity=T2.NameOfUniversity,
		T1.CmnUniversityCountryId=T2.CmnUniversityCountryId
		WHERE T1.Id=PCrpFinalEngineerId;


	END IF;


	INSERT INTO crpengineerattachmentfinal(Id,CrpEngineerFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),PCrpFinalEngineerId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpengineerattachment WHERE CrpEngineerId=PCrpEngineerId;
END


