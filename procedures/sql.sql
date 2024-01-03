BEGIN
    DECLARE VId CHAR(36);
	  DECLARE VCdbNo INT;
	  DECLARE VRecordId VARCHAR(25);
	  DECLARE VCheck INT DEFAULT FALSE;
	  DECLARE end_of_table INT DEFAULT FALSE;
	  DECLARE child_table_done INT DEFAULT FALSE;
	  DECLARE bidCursor CURSOR FOR SELECT T1.Id, T3.migratedrecordid FROM cdb_local.`crpbiddingformdetail` T1 join cdb_local.crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId join crpbiddingform T3 on T3.Id = T1.CrpBiddingFormId and T1.CmnWorkExecutionStatusId = '1ec69344-a256-11e4-b4d2-080027dcfac6' where T2.CDBNo not in (select A.CDBNo from cdb_local.trackrecord A where A.Recordid = T3.migratedrecordid);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET end_of_table = TRUE;

    OPEN bidCursor;
    read_loop: LOOP
    FETCH bidCursor into VId, VRecordId;
    IF end_of_table THEN
      set end_of_table = false;
      LEAVE read_loop;
    END IF;

		SET BCdbNo = SELECT CDBNo from cdb_local.trackrecord where Recordid = VRecordId;

    END LOOP read_loop;
    CLOSE bidCursor;

END



SELECT T1.CDBNo, T1.Id from cdb_local.crpcontractorfinal T1 where (select count(*) from cdb_local.crpcontractorequipmentfinal A where A.CrpContractorFinalId = T1.Id) <> (select count(*) from cdbolddata.appequipment B join cdbolddata.application C on C.ai_key = B.ae_ai_key where C.ai_CDBRegNum = T1.CDBNo);