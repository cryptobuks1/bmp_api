DELIMITER $$
DROP PROCEDURE IF EXISTS getUserRankData$$
CREATE PROCEDURE getUserRankData(IN pUserName VARCHAR(250))
getUserRankData: BEGIN
        DECLARE success,done,selectedRankId,purchasedRegistrationMembership,purchasedAnyOfPool,dealerTotalEnrollment,dealerSixMinersEnrollment,dealerSixMinersWithTwoSubMinersEnrollment,superDealerTotalEnrollment,superDealerThreeDealersEnrollment INT(11) DEFAULT 0;
        DECLARE executiveDealerTotalEnrollment,executiveDealerTwoSuperDealersEnrollment,crownDealerTotalEnrollment,crownDealerThreeExecutiveDealersEnrollment,globalCrownDealerTotalEnrollment,globalCrownDealerThreeCrownDealersEnrollment INT(11) DEFAULT 0;
        DECLARE selectedAccountBalance,selectedMiningBalance,selectedTeamBalance,selectedCommissionBalance,selectedTeamVolumeBalance DECIMAL(14,4) DEFAULT 0.00;
        DECLARE responseMessage,selectedRank VARCHAR(250) DEFAULT '';
        DECLARE isMinerRankAchieved,isDealerRankAchieved,isSuperDealerRankAchieved,isExecutiveRankAchieved,isCrownRankAchieved,isGlobalCrownRankAchieved INT(11) DEFAULT 0;
        
            SET success = 0;

            IF  EXISTS (SELECT * FROM users WHERE  Username=pUserName order by id desc limit 1) THEN
                SELECT Balance INTO selectedAccountBalance FROM accountbalance WHERE Username = pUserName;
                SELECT Balance INTO selectedMiningBalance FROM mining WHERE Username = pUserName;
                SELECT Balance INTO selectedTeamBalance FROM team WHERE Username = pUserName;
                SELECT Balance INTO selectedCommissionBalance FROM commission WHERE Username = pUserName;
                SELECT Balance INTO selectedTeamVolumeBalance FROM teamvolume WHERE Username = pUserName;
                SELECT Rank,Rankid INTO selectedRank,selectedRankId FROM rank WHERE Username = pUserName;
                
                -- CONDITIONS FOR MINER RANK START --
                IF EXISTS(SELECT * FROM invoice where Purpose = 'Registration' AND Status = 'Paid' AND Username = pUserName order by id desc limit 1) THEN
                    SET purchasedRegistrationMembership = 1;
                END IF;
                IF EXISTS(SELECT * FROM invoice where Purpose <> 'Registration' AND Status = 'Paid' AND Username = pUserName order by id desc limit 1) THEN
                    SET purchasedAnyOfPool = 1;
                END IF;
                IF(purchasedRegistrationMembership = 1 AND purchasedAnyOfPool = 1) THEN 
                    SET isMinerRankAchieved = 1;
                    UPDATE rank SET Rank='Miner', Rankid='1' WHERE Username = pUserName;
                END IF;
                -- CONDITIONS FOR MINER RANK END --
                
                -- CONDITIONS FOR DEALER RANK START --
                    SELECT (balance >= 11400) INTO dealerTotalEnrollment FROM teamvolume WHERE Username = pUserName;
                    SELECT (count(*) >= 6 ) INTO dealerSixMinersEnrollment FROM invoice AS i JOIN users AS u ON u.Username=i.Username AND u.Sponsor = pUserName AND i.status='Paid';
                    SELECT ( (count(*) >= 6 ) AND (t.left IS NOT NULL AND t.right IS NOT NULL) ) INTO dealerSixMinersWithTwoSubMinersEnrollment FROM invoice AS i JOIN users AS u ON u.Username=i.Username AND u.Sponsor = pUserName AND i.status='Paid' JOIN tree AS t ON  t.userid=i.Username;
                
                IF(dealerTotalEnrollment = 1 AND dealerSixMinersEnrollment = 1 AND dealerSixMinersWithTwoSubMinersEnrollment = 1 ) THEN 
                    SET isDealerRankAchieved = 1;
                    UPDATE rank SET Rank='Dealer', Rankid='2' WHERE Username = pUserName;
                END IF;
                -- CONDITIONS FOR DEALER RANK END --

                -- CONDITIONS FOR SUPER DEALER RANK START --
                    SELECT (balance >= 50000) INTO superDealerTotalEnrollment FROM teamvolume WHERE Username = pUserName;
                    SELECT (count(*) >= 3 ) INTO superDealerThreeDealersEnrollment FROM users AS u JOIN rank AS r on r.Username=u.Username WHERE u.Sponsor = pUserName AND  r.Rankid >= 2;

                IF(superDealerTotalEnrollment = 1 AND superDealerThreeDealersEnrollment = 1 ) THEN 
                    SET isSuperDealerRankAchieved = 1;
                    UPDATE rank SET Rank='Super Dealer', Rankid='3' WHERE Username = pUserName;
                END IF;
                -- CONDITIONS FOR SUPER DEALER RANK END --
                
               -- CONDITIONS FOR EXECUTIVE DEALER RANK START --
                    SELECT (balance >= 220000) INTO executiveDealerTotalEnrollment FROM teamvolume WHERE Username = pUserName;
                    SELECT (count(*) >= 2 ) INTO executiveDealerTwoSuperDealersEnrollment FROM users AS u JOIN rank AS r on r.Username=u.Username WHERE u.Sponsor = pUserName AND  r.Rankid >= 3;

                IF(executiveDealerTotalEnrollment = 1 AND executiveDealerTwoSuperDealersEnrollment = 1 ) THEN 
                    SET isExecutiveRankAchieved = 1;
                    UPDATE rank SET Rank='Executive Dealer', Rankid='4' WHERE Username = pUserName;
                END IF;
                -- CONDITIONS FOR EXECUTIVE DEALER RANK END -- 

                -- CONDITIONS FOR CROWN DEALER RANK START --
                    SELECT (balance >= 2000000) INTO crownDealerTotalEnrollment FROM teamvolume WHERE Username = pUserName;
                    SELECT (count(*) >= 3 ) INTO crownDealerThreeExecutiveDealersEnrollment FROM users AS u JOIN rank AS r on r.Username=u.Username WHERE u.Sponsor = pUserName AND  r.Rankid >= 4;

                IF(crownDealerTotalEnrollment = 1 AND executiveDealerThreeExecutiveDealersEnrollment = 1 ) THEN 
                    SET isCrownRankAchieved = 1;
                    UPDATE rank SET Rank='Crown Dealer', Rankid='5' WHERE Username = pUserName;
                END IF;
                -- CONDITIONS FOR CROWN DEALER RANK END -- 
                
                -- CONDITIONS FOR GLOBAL CROWN DEALER RANK START --
                    SELECT (balance >= 10000000) INTO globalCrownDealerTotalEnrollment FROM teamvolume WHERE Username = pUserName;
                    SELECT (count(*) >= 3 ) INTO globalCrownDealerThreeCrownDealersEnrollment FROM users AS u JOIN rank AS r on r.Username=u.Username WHERE u.Sponsor = pUserName AND  r.Rankid >= 5;

                IF(globalCrownDealerTotalEnrollment = 1 AND globalCrownDealerThreeCrownDealersEnrollment = 1 ) THEN 
                    SET isGlobalCrownRankAchieved = 1;
                    UPDATE rank SET Rank='Global Crown Dealer', Rankid='6' WHERE Username = pUserName;
                END IF;
                -- CONDITIONS FOR GLOBAL CROWN DEALER RANK END -- 


                SET responseMessage = 'Success';
                SET success = 1;
                SELECT success AS response,responseMessage,
                                    isMinerRankAchieved,isDealerRankAchieved,isSuperDealerRankAchieved,isExecutiveRankAchieved,isCrownRankAchieved,isGlobalCrownRankAchieved,selectedAccountBalance,selectedMiningBalance,selectedTeamBalance,selectedCommissionBalance,selectedTeamVolumeBalance,selectedRank,selectedRankId,
                                    purchasedRegistrationMembership,purchasedAnyOfPool,
                                    dealerTotalEnrollment,dealerSixMinersEnrollment,dealerSixMinersWithTwoSubMinersEnrollment,
                                    superDealerTotalEnrollment,superDealerThreeDealersEnrollment,
                                    executiveDealerTotalEnrollment,executiveDealerTwoSuperDealersEnrollment,
                                    crownDealerTotalEnrollment,crownDealerThreeExecutiveDealersEnrollment,
                                    globalCrownDealerTotalEnrollment,globalCrownDealerThreeCrownDealersEnrollment
                                    ; 
            ELSE 
                SET responseMessage = 'Customer is not exist.';
                SET success = 0;
                SELECT success AS response, responseMessage; 
            END IF;
            
        
        END$$
DELIMITER ;