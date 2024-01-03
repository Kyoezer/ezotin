BEGIN
DECLARE vtable_name VARCHAR(255);  
DECLARE col_name VARCHAR(255);  
DECLARE end_of_tables INT DEFAULT 0;    
DECLARE end_of_cols INT DEFAULT 0;    
DECLARE trigger_name VARCHAR(1500);
DECLARE dbase VARCHAR(500) DEFAULT 'cdb_local';

DECLARE VForeignCount INT DEFAULT 0;
DECLARE VForeignKeyTableName VARCHAR(256) DEFAULT NULL;
DECLARE VForeignOldName VARCHAR(500) DEFAULT NULL; 
DECLARE VForeignNewName VARCHAR(500) DEFAULT NULL; 
        
DECLARE cur CURSOR FOR SELECT t.table_name FROM information_schema.tables t WHERE t.table_schema = dbase AND TABLE_TYPE='BASE TABLE';
DECLARE CONTINUE HANDLER FOR NOT FOUND SET end_of_tables = 1;

SET @s = '';

OPEN cur;
tables_loop: LOOP
	FETCH cur INTO vtable_name;
	IF end_of_tables = 1 THEN
        	LEAVE tables_loop;
	END IF;        
	IF vtable_name != 'sysaudittrail' AND vtable_name != 'cmnlist' AND vtable_name != 'cmnsearch' AND vtable_name != 'cmnsearchresult' AND vtable_name != 'crpservicefeestructure' AND vtable_name != 'etlbidevalutionparameters' AND vtable_name != 'etltier' AND vtable_name != 'sysaudittrailetoolcinet' AND vtable_name != 'sysdeletedrecord' AND vtable_name != 'sysloginfailurelog' AND vtable_name != 'sysmenu' AND vtable_name != 'sysmenugroup' AND vtable_name != 'sysuserlog' THEN
        BLOCK: BEGIN
    		DECLARE curc CURSOR FOR SELECT c.column_name FROM information_schema.columns c WHERE c.table_name= vtable_name and c.table_schema = dbase;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET end_of_cols = 1;
            OPEN curc;
            set @updateStatement = '';
            set @deleteStatement = '';
            cols_loop: LOOP
        		FETCH curc INTO col_name;
                IF end_of_cols = 1 THEN
                	set end_of_cols =0;
                        LEAVE cols_loop;
                END IF;
                IF col_name!='Id' AND col_name!='CreatedBy' AND col_name!='EditedBy' AND col_name!='created_at' AND col_name!='updated_at' THEN
                    SET VForeignCount=(SELECT count(COLUMN_NAME) FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA=dbase AND TABLE_NAME = vtable_name AND COLUMN_NAME = col_name AND REFERENCED_TABLE_NAME IS NOT NULL);
                    IF VForeignCount>0 THEN
                        /*--------The Name Column selected below should be changed to the foreign key column name-----------------*/
                        SET VForeignKeyTableName=(SELECT REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA=dbase AND TABLE_NAME = vtable_name AND COLUMN_NAME = col_name LIMIT 1);
                        SET @updateStatement = CONCAT(@updateStatement,'\nif OLD.',col_name, ' != NEW.', col_name ,' then \nSET VForeignOldName=(SELECT Name FROM ',VForeignKeyTableName,' WHERE Id=OLD.',col_name,');\nSET VForeignNewName=(SELECT Name FROM ',VForeignKeyTableName,' WHERE Id=NEW.',col_name,'); \ninsert into sysaudittrail (TableName,ColumnName,OldValueId,OldValue,NewValueId,NewValue,RowId,Action,ActionDate,SysUserId,DbUser) values (''',vtable_name,''',''',col_name,''',OLD.',col_name,',VForeignOldName,NEW.',col_name,',VForeignNewName,NEW.Id,''U'',NOW(),NEW.EditedBy,USER()); \nend if;' );
                    ELSE
                        SET @updateStatement = CONCAT(@updateStatement,'\ninsert into sysaudittrail (TableName,ColumnName,OldValue,RowId,Action,ActionDate,SysUserId,DbUser) values (''',vtable_name,''',''',col_name,''',OLD.',col_name,',OLD.Id,''D'',NOW(),VActionUserId,USER());' );
                    END IF;
                    SET VForeignCount=0;
        		END IF;
    		END LOOP cols_loop;
    		close curc;
        END BLOCK;
        SET trigger_name = CONCAT('trig_',vtable_name,'_audit_ad');
		SET @s = CONCAT(@s, '\nDROP TRIGGER IF EXISTS ', trigger_name ,';\nDELIMITER //\n\nCREATE TRIGGER ', trigger_name , ' AFTER DELETE ON ', vtable_name, ' FOR EACH ROW \nBEGIN ' );
        SET @s = CONCAT(@s, '\nDECLARE VActionUserId CHAR(36);\nSET VActionUserId=coalesce((SELECT SysUserId FROM sysdeletedrecord WHERE Id=OLD.Id LIMIT 1),NULL);' );
        SET @s = CONCAT(@s, @updateStatement,'\nEND//\nDELIMITER ;');
	END IF;
END LOOP;
CLOSE cur;
SET @filePath = CONCAT("'H:/AfterDeleteTriggers_" ,DATE_FORMAT(NOW(),'%d%b%Y%H%i%s'), ".sql'");
SET @fileout = CONCAT('select @s INTO OUTFILE ', @filePath, "FIELDS TERMINATED BY '' ESCAPED BY ''" );
prepare stmt from @fileout;
execute stmt;
select CONCAT('Create Trigger file for database (', dbase ,') has been created at ', @filePath);

END