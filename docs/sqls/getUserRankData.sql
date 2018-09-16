DELIMITER $$
DROP PROCEDURE IF EXISTS getUserRankData$$
CREATE PROCEDURE getUserRankData(IN pUserName VARCHAR(250))
getUserRankData: BEGIN
        DECLARE success,done,selectedRankId,purchasedRegistrationMembership,purchasedAnyOfPool INT(11) DEFAULT 0;
        DECLARE selectedAccountBalance,selectedMiningBalance,selectedTeamBalance,selectedCommissionBalance,selectedTeamVolumeBalance DECIMAL(14,4) DEFAULT 0.00;
        DECLARE responseMessage,selectedRank VARCHAR(250) DEFAULT '';
        
            SET success = 0;

            IF  EXISTS (SELECT * FROM `users` WHERE  Username=pUserName order by id desc limit 1) THEN
                SELECT Balance INTO selectedAccountBalance FROM accountbalance WHERE Username = pUserName;
                SELECT Balance INTO selectedMiningBalance FROM mining WHERE Username = pUserName;
                SELECT Balance INTO selectedTeamBalance FROM team WHERE Username = pUserName;
                SELECT Balance INTO selectedCommissionBalance FROM commission WHERE Username = pUserName;
                SELECT Balance INTO selectedTeamVolumeBalance FROM teamvolume WHERE Username = pUserName;
                SELECT Rank,Rankid INTO selectedRank,selectedRankId FROM rank WHERE Username = pUserName;
                
                IF EXISTS(SELECT * FROM invoice where Purpose = 'Registration' AND Status = 'Paid' AND Username = pUserName order by id desc limit 1) THEN
                    SET purchasedRegistrationMembership = 1;
                END IF;
                IF EXISTS(SELECT * FROM invoice where Purpose <> 'Registration' AND Status = 'Paid' AND Username = pUserName order by id desc limit 1) THEN
                    SET purchasedAnyOfPool = 1;
                END IF;
                

                SET responseMessage = 'Success';
                SET success = 1;
                SELECT success AS response, responseMessage,selectedAccountBalance,selectedMiningBalance,selectedTeamBalance,selectedCommissionBalance,selectedTeamVolumeBalance,selectedRank,selectedRankId,purchasedRegistrationMembership; 
            ELSE 
                SET responseMessage = 'Customer is not exist.';
                SET success = 0;
                SELECT success AS response, responseMessage; 
            END IF;
            
        
        END$$
DELIMITER ;