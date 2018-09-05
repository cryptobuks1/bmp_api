DELIMITER $$
DROP PROCEDURE IF EXISTS insertTree$$
 CREATE PROCEDURE insertTree(IN parentUser VARCHAR(250), IN side VARCHAR(250),IN userName VARCHAR(250))
insertTree: BEGIN
        DECLARE success INT(11) DEFAULT 0;
        DECLARE customerId,lastInsertedId INT(11) DEFAULT  0;
        DECLARE responseMessage VARCHAR(250) DEFAULT '';

        
            SET success = 0;
            SET lastInsertedId = 0;
            
                
                START TRANSACTION;

                INSERT INTO user(Username,under_userid,side) values(userName,parentUser,side);
                SET lastInsertedId = LAST_INSERT_ID(); 
                INSERT INTO tree (userid) VALUES (userName);


                IF side = 'left' THEN 
                    UPDATE tree SET `left` = userName WHERE userid = parentUser;
                ELSE 
                    UPDATE tree SET `right` = userName WHERE userid = parentUser;
                END IF;
 
                UPDATE users set treestatus= 'tree' where Username = userName;
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