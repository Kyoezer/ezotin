BEGIN
	/*Procedure By Kinley Nidup; WEb Name:ZeroCool; Facbook Link: https://www.facebook.com/kgyel*/
	/*Procedure Name:ProCrpEngineerNewRegistrationFinalData Procedure Parameters: PSysUserId,PCrpEngineerId,PSysCreatedByUserId,PCmnApplicationRegistrationStatusId*/
	INSERT INTO crpengineerfinal (Id,SysUserId,ReferenceNo,ApplicationDate,CDBNo,CmnServiceSectorTypeId,CmnTradeId,CIDNo,CmnSalutationId,Name,CmnCountryId,CmnDzongkhagId,Gewog,Village,Email,MobileNo,EmployerName,EmployerAddress,CmnQualificationId,GraduationYear,NameOfUniversity,CmnUniversityCountryId,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
	SELECT Id,PSysUserId,ReferenceNo,ApplicationDate,CDBNo,CmnServiceSectorTypeId,CmnTradeId,CIDNo,CmnSalutationId,Name,CmnCountryId,CmnDzongkhagId,Gewog,Village,Email,MobileNo,EmployerName,EmployerAddress,CmnQualificationId,GraduationYear,NameOfUniversity,CmnUniversityCountryId,PCmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,PSysCreatedByUserId
	FROM crpengineer WHERE Id=PCrpEngineerId;

	INSERT INTO crpengineerattachmentfinal(Id,CrpEngineerFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpEngineerId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crpengineerattachment WHERE CrpEngineerId=PCrpEngineerId;
END


