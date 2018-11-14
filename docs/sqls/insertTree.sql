DELIMITER $$
DROP PROCEDURE IF EXISTS insertTree$$
 CREATE PROCEDURE insertTree(IN parentUser VARCHAR(250), IN side VARCHAR(250),IN pUserName VARCHAR(250))
insertTree: BEGIN
        DECLARE success INT(11) DEFAULT 0;
        DECLARE customerId,lastInsertedId,parentRankId,capping,parentLeftCount,parentRightCount,parentLeftCredits,parentRightCredits  INT(11) DEFAULT  0;
        DECLARE responseMessage,parentUserSponsor,parantLeftNode,parantRightNode VARCHAR(250) DEFAULT '';
        DECLARE dayBal,currentBal,totalBal,inDirectBinaryCommisionAmount,matchingBonusAmount DECIMAL(14,4) DEFAULT 0.00;
        
            SET success = 0;
            SET lastInsertedId = 0;
            SET inDirectBinaryCommisionAmount = 200;
            SET matchingBonusAmount = 10;

                
                START TRANSACTION;

                INSERT INTO user(Username,under_userid,side) values(pUserName,parentUser,side);
                SET lastInsertedId = LAST_INSERT_ID(); 

                IF NOT EXISTS (SELECT * FROM tree WHERE userid = pUserName) THEN
                    INSERT INTO tree (userid) VALUES (pUserName);
                END IF;
                
                SELECT tree.left,tree.right,tree.leftcount,tree.rightcount,tree.leftcredits,tree.rightcredits INTO parantLeftNode,parantRightNode,parentLeftCount,parentRightCount,parentLeftCredits,parentRightCredits  FROM tree WHERE userid = parentUser;

                IF side = 'left' THEN 
                    UPDATE tree SET `left` = pUserName,tree.leftcount = (parentLeftCount + 1),tree.leftcredits = (parentLeftCredits + 10)  WHERE userid = parentUser;
                ELSE 
                    UPDATE tree SET `right` = pUserName ,tree.rightcount = (parentRightCount + 1),tree.rightcredits = (parentRightCredits + 10) WHERE userid = parentUser;
                END IF;
                
                SELECT Rankid INTO parentRankId FROM rank WHERE Username = pUserName;
                -- GET DATA FOR PER DAY BONUS RESTRICTION --
                IF (parentRankId = 1) THEN 
                    SET capping = 800;
                ELSEIF (parentRankId = 2) THEN
                    SET capping = 1000;
                ELSEIF (parentRankId = 3) THEN
                    SET capping = 1200;
                ELSEIF (parentRankId = 4) THEN
                    SET capping = 1400;
                ELSEIF (parentRankId = 5) THEN
                    SET capping = 1600;
                ELSEIF (parentRankId = 6) THEN
                    SET capping = 2000;
                ELSE
                    SET capping = 2000;
                END IF;
                
                SELECT day_bal,current_bal,total_bal INTO dayBal,currentBal,totalBal FROM binaryincome WHERE userid=pUserName;
                SELECT Sponsor INTO parentUserSponsor FROM users WHERE Username=pUserName;
                IF (currentBal < capping) THEN 
                    -- SELECT tree.left,tree.right INTO parantLeftNode,parantRightNode FROM tree WHERE userid = parentUser;
                    
                    -- CODE TO ADD INDIRECT BINARY COMMISSION --
                    IF((parantLeftNode <> '' AND parantLeftNode IS NOT NULL) AND (parantRightNode <> '' AND parantRightNode IS NOT NULL)) THEN
                        UPDATE binaryincome SET day_bal = (day_bal+inDirectBinaryCommisionAmount),current_bal = (current_bal+inDirectBinaryCommisionAmount),total_bal = (total_bal+inDirectBinaryCommisionAmount),updated_at=now() WHERE userid = parentUser;
                        UPDATE accountbalance SET Balance = (Balance+inDirectBinaryCommisionAmount),updated_at=now() WHERE Username=parentUser;
                        INSERT INTO bmp_bonus_commission_earn_log (user_name, reason_id, reason_description, is_added_by_cron, amount, added_in) 
                                                         VALUES (parentUser, '2', CONCAT('Indirect Binary commision of user ',pUserName), '0', inDirectBinaryCommisionAmount, 'binaryincome');
                        -- CODE TO ADD MATCHING BONUS --
                        IF (parentUserSponsor <> '' AND parentUserSponsor IS NOT NULL) THEN 
                            UPDATE team SET Balance = (Balance+matchingBonusAmount),updated_at=now() WHERE Username=parentUserSponsor;
                            UPDATE accountbalance SET Balance = (Balance+matchingBonusAmount),updated_at=now() WHERE Username=parentUserSponsor;
                            INSERT INTO bmp_bonus_commission_earn_log (user_name, reason_id, reason_description, is_added_by_cron, amount, added_in) 
                                                          VALUES (parentUserSponsor, '3', CONCAT('Matching bonus of parent user ',parentUser,' on joining of ',pUserName), '0', matchingBonusAmount, 'team');
                        
                        END IF;
                        
                    END IF;
                
                END IF;

                
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