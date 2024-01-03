BEGIN
    DECLARE VParentId CHAR(36);
    DECLARE VWorkId VARCHAR(500);
    DECLARE RecordId VARCHAR(500);
	  DECLARE VCdbNo INT;
	  DECLARE VOldCdbNo INT;
	  DECLARE VOldCdbNo1 INT;
	  DECLARE VOldCdbNo2 INT;
	  DECLARE VId CHAR(36);
	  DECLARE VCheck INT DEFAULT FALSE;
	  DECLARE end_of_table INT DEFAULT FALSE;
	  DECLARE child_table_done INT DEFAULT FALSE;
	  DECLARE etlCursor CURSOR FOR SELECT Id, WorkId, RecordId from cdb_test.etltenderbiddercontractor;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET end_of_table = TRUE;

    OPEN etlCursor;
    read_loop: LOOP
    FETCH etlCursor into VParentId, VWorkId, RecordId;
    IF end_of_table THEN
      set end_of_table = false;
      LEAVE read_loop;
    END IF;

    BLOCK2: BEGIN
    DECLARE childTableCursor CURSOR FOR SELECT T2.CDBNo, T1.Id FROM cdb_test.etltenderbiddercontractordetail T1 join cdb_test.crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId WHERE T1.EtlTenderBidderContractorId = VParentId;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET child_table_done = TRUE;
    OPEN childTableCursor;
    child_table_loop: LOOP
    FETCH childTableCursor INTO VCdbNo, VId;
        IF child_table_done THEN
        set child_table_done = false;
        CLOSE childTableCursor;
        LEAVE child_table_loop;
        END IF;

        SET VOldCdbNo = (SELECT CDBNo from cdbolddata.tblevaluation where WorkId = VWorkId and Result in ('Awarded','Completed','Terminated'));
        SET VOldCdbNo1 = (SELECT CDBNo1 from cdbolddata.tblevaluation where WorkId = VWorkId and Result in ('Awarded','Completed','Terminated'));
        SET VOldCdbNo2 = (SELECT CDBNo2 from cdbolddata.tblevaluation where WorkId = VWorkId and Result in ('Awarded','Completed','Terminated'));

        IF VCdbNo = VOldCdbNo THEN
          SET VCheck = TRUE;
        END IF;

        IF VCdbNo = VOldCdbNo1 THEN
          SET VCheck = TRUE;
        END IF;

        IF VCdbNo = VOldCdbNo2 THEN
          SET VCheck = TRUE;
        END IF;

        IF VCheck = FALSE THEN
          UPDATE cdb_test.etltenderbiddercontractor SET ActualStartDate = NULL WHERE Id = VParentId;
          UPDATE cdb_test.etltenderbiddercontractor SET ActualEndDate = NULL WHERE Id = VParentId;
          UPDATE cdb_test.etltenderbiddercontractor SET AwardedAmount = NULL WHERE Id = VParentId;
        END IF;

        SET VCheck = FALSE;
    END LOOP child_table_loop;
    END BLOCK2;

    END LOOP read_loop;
    CLOSE etlCursor;

END