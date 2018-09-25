DELIMITER $$
DROP PROCEDURE IF EXISTS insertTree$$
 CREATE PROCEDURE insertTree(IN parentUser VARCHAR(250), IN side VARCHAR(250),IN pUserName VARCHAR(250))
insertTree: BEGIN
        DECLARE success INT(11) DEFAULT 0;
        DECLARE customerId,lastInsertedId,parentRankId,capping INT(11) DEFAULT  0;
        DECLARE responseMessage VARCHAR(250) DEFAULT '';
        DECLARE dayBal,currentBal,totalBal DECIMAL(14,4) DEFAULT 0.00;
        
            SET success = 0;
            SET lastInsertedId = 0;
            
                
                START TRANSACTION;

                INSERT INTO user(Username,under_userid,side) values(pUserName,parentUser,side);
                SET lastInsertedId = LAST_INSERT_ID(); 
                INSERT INTO tree (userid) VALUES (pUserName) WHERE NOT EXISTS (
                                SELECT * FROM tree WHERE userid=pUserName
                            );
                

                IF side = 'left' THEN 
                    UPDATE tree SET `left` = pUserName WHERE userid = parentUser;
                ELSE 
                    UPDATE tree SET `right` = pUserName WHERE userid = parentUser;
                END IF;
                
                SELECT Rankid INTO parentRankId FROM rank WHERE Username = pUserName;
                IF (parentRankId == 1) THEN 
                    SET capping = 800;
                ELSE IF (parentRankId == 2) THEN
                    SET capping = 1000;
                ELSE IF (parentRankId == 3) THEN
                    SET capping = 1200;
                ELSE IF (parentRankId == 4) THEN
                    SET capping = 1400;
                ELSE IF (parentRankId == 5) THEN
                    SET capping = 1600;
                ELSE IF (parentRankId == 6) THEN
                    SET capping = 2000;
                ELSE
                    SET capping = 2000;
                END IF;
                
                SELECT day_bal INTO dayBal,current_bal INTO currentBal,total_bal INTO totalBal FROM binaryincome WHERE userid=pUserName;

                UPDATE users set treestatus= 'tree' where Username = pUserName;
                IF lastInsertedId = 0 THEN
                        SET responseMessage = 'Problem to add tree data.';
                        SET success = 0;
                        ROLLBACK;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE insertTree;		
                ELSE    
                        SET responseMessage = 'Tree data added succesfully.';
                        SET success = 1;
                    	COMMIT;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE insertTree;

                END IF;

        SELECT success AS response, lastInsertedId,responseMessage; 
        
        COMMIT;   
        END$$
DELIMITER ;