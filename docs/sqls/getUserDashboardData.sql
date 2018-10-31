DELIMITER $$
DROP PROCEDURE IF EXISTS getUserDashboardData$$
CREATE PROCEDURE getUserDashboardData(IN pUserName VARCHAR(250))
getUserDashboardData: BEGIN
        DECLARE success,done,selectedRankId,purchasedRegistrationMembership,purchasedAnyOfPool,dealerTotalEnrollment,dealerSixMinersEnrollment,dealerSixMinersWithTwoSubMinersEnrollment,superDealerTotalEnrollment,superDealerThreeDealersEnrollment INT(11) DEFAULT 0;
        DECLARE selectedAccountBalance,selectedMiningBalance,selectedTeamBalance,selectedCommissionBalance,selectedBinaryIncomeBalance,selectedTeamVolumeBalance DECIMAL(14,4) DEFAULT 0.00;
        DECLARE responseMessage,selectedRank VARCHAR(250) DEFAULT '';
        DECLARE starterWithdrawal,miniWithdrawal,mediumWithdrawal,grandWithdrawal,ultimateWithdrawal  DECIMAL(14,4) DEFAULT 0.00;
        DECLARE starterDailyMine,miniDailyMine,mediumDailyMine,grandDailyMine,ultimateDailyMine  DECIMAL(14,4) DEFAULT 0.00;
        DECLARE selectedStarterMiningDate,selectedMiniMiningDate,selectedMediumMiningDate,selectedGrandMiningDate,selectedUltimateMiningDate VARCHAR(250) DEFAULT '';
        
        -- DECLARE isMinerRankAchieved,isDealerRankAchieved,isSuperDealerRankAchieved,isExecutiveRankAchieved,isCrownRankAchieved,isGlobalCrownRankAchieved INT(11) DEFAULT 0;
        
            SET success = 0;

            IF  EXISTS (SELECT * FROM users WHERE  Username=pUserName order by id desc limit 1) THEN
                SELECT Balance INTO selectedAccountBalance FROM accountbalance WHERE Username = pUserName;
                SELECT Balance INTO selectedMiningBalance FROM mining WHERE Username = pUserName;
                SELECT Balance INTO selectedTeamBalance FROM team WHERE Username = pUserName;
                SELECT Balance INTO selectedCommissionBalance FROM commission WHERE Username = pUserName;
                SELECT total_bal INTO selectedBinaryIncomeBalance FROM binaryincome WHERE userid = pUserName;
                SELECT Balance INTO selectedTeamVolumeBalance FROM teamvolume WHERE Username = pUserName;
                SELECT Rank,Rankid INTO selectedRank,selectedRankId FROM rank WHERE Username = pUserName;

                SELECT MiningDate INTO selectedStarterMiningDate FROM starterpack WHERE Username=pUserName AND Status='Active' AND MiningDate <> '0' order by id desc limit 1;
                IF (selectedStarterMiningDate <> '0') THEN 
                    SELECT COALESCE(SUM(Usd),0) INTO starterDailyMine FROM dailymine WHERE Pack='Starter' AND Username = pUserName AND Date Between selectedStarterMiningDate AND ( CURRENT_DATE - INTERVAL 0 DAY );
                END IF;
                
                SELECT MiningDate INTO selectedMiniMiningDate FROM minipack WHERE Username=pUserName AND Status='Active' AND MiningDate <> '0' order by id desc limit 1;
                IF (selectedMiniMiningDate <> '0') THEN 
                    SELECT COALESCE(SUM(Usd),0) INTO miniDailyMine FROM dailymine WHERE Pack='Mini' AND Username = pUserName AND Date Between selectedMiniMiningDate AND ( CURRENT_DATE - INTERVAL 0 DAY );
                END IF;

                SELECT MiningDate INTO selectedMediumMiningDate FROM mediumpack WHERE Username=pUserName AND Status='Active' AND MiningDate <> '0' order by id desc limit 1;
                IF (selectedMediumMiningDate <> '0') THEN 
                    SELECT COALESCE(SUM(Usd),0) INTO mediumDailyMine FROM dailymine WHERE Pack='Medium' AND Username = pUserName AND Date Between selectedMediumMiningDate AND ( CURRENT_DATE - INTERVAL 0 DAY );
                END IF;

                SELECT MiningDate INTO selectedGrandMiningDate FROM grandpack WHERE Username=pUserName AND Status='Active' AND MiningDate <> '0' order by id desc limit 1;
                IF (selectedGrandMiningDate <> '0') THEN 
                    SELECT COALESCE(SUM(Usd),0) INTO grandDailyMine FROM dailymine WHERE Pack='Grand' AND Username = pUserName AND Date Between selectedGrandMiningDate AND ( CURRENT_DATE - INTERVAL 0 DAY );
                END IF;

                SELECT MiningDate INTO selectedUltimateMiningDate FROM ultimatepack WHERE Username=pUserName AND Status='Active' AND MiningDate <> '0' order by id desc limit 1;
                IF (selectedUltimateMiningDate <> '0') THEN 
                    SELECT COALESCE(SUM(Usd),0) INTO ultimateDailyMine FROM dailymine WHERE Pack='Ultimate' AND Username = pUserName AND Date Between selectedUltimateMiningDate AND ( CURRENT_DATE - INTERVAL 0 DAY );
                END IF;

                SET responseMessage = 'Success';
                SET success = 1;
                SELECT success AS response,responseMessage,
                                    selectedAccountBalance,selectedMiningBalance,selectedTeamBalance,selectedCommissionBalance,selectedBinaryIncomeBalance,selectedTeamVolumeBalance,selectedRank,selectedRankId,
                                    starterDailyMine,miniDailyMine,mediumDailyMine,grandDailyMine,ultimateDailyMine
                                    ; 
            ELSE 
                SET responseMessage = 'Customer is not exist.';
                SET success = 0;
                SELECT success AS response, responseMessage; 
            END IF;
            
        
        END$$
DELIMITER ;