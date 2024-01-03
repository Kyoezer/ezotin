BEGIN
	/*Procedure By Kinley Nidup; WEb Name:ZeroCool; Facbook Link: https://www.facebook.com/kgyel*/
	/*Procedure Name:ProCrpArchitectNewRegistrationFinalData Procedure Parameters: PSysUserId,PCrpArchitectId,PSysCreatedByUserId,PCmnApplicationRegistrationStatusId*/
	INSERT INTO crparchitectfinal (Id,SysUserId,ReferenceNo,ApplicationDate,ARNo,CmnServiceSectorTypeId,CIDNo,CmnSalutationId,Name,CmnCountryId,CmnDzongkhagId,Gewog,Village,Email,MobileNo,EmployerName,EmployerAddress,CmnQualificationId,GraduationYear,NameOfUniversity,CmnUniversityCountryId,CmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,CreatedBy)
	SELECT Id,PSysUserId,ReferenceNo,ApplicationDate,ARNo,CmnServiceSectorTypeId,CIDNo,CmnSalutationId,Name,CmnCountryId,CmnDzongkhagId,Gewog,Village,Email,MobileNo,EmployerName,EmployerAddress,CmnQualificationId,GraduationYear,NameOfUniversity,CmnUniversityCountryId,PCmnApplicationRegistrationStatusId,RegistrationApprovedDate,RegistrationExpiryDate,PSysCreatedByUserId
	FROM crparchitect WHERE Id=PCrpArchitectId;

	INSERT INTO crparchitectattachmentfinal(Id,CrpArchitectFinalId,DocumentName,DocumentPath,FileType,CreatedBy)
	SELECT UUID(),CrpArchitectId,DocumentName,DocumentPath,FileType,PSysCreatedByUserId
	FROM crparchitectattachment WHERE CrpArchitectId=PCrpArchitectId;
END


