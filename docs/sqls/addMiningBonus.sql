DELIMITER $$
DROP PROCEDURE IF EXISTS addMiningBonus$$
CREATE PROCEDURE addMiningBonus(IN pUserName VARCHAR(250))
addMiningBonus: BEGIN
        DECLARE success,done,doneInner,selectedUserId,lastInsertedId,isMonthlyApplicable,isDailyApplicable,selectedPoolId INT(11) DEFAULT 0;
        DECLARE responseMessage,selectedFullname,selectedUserName,selectedEmail,selectedStatus,selectedPassword,selectedSponsor,selectedPoolName,selectedPooltableName,tempStr,miningMonthDate,currentMonthDate VARCHAR(250) DEFAULT '';
        DECLARE selecetdCreatedAt DATE;
        DECLARE selectedStarterDailyBonus,selectedStarterMonthlyBonus,selectedMiniDailyBonus,selectedMiniMonthlyBonus,selectedMediumDailyBonus,selectedMediumMonthlyBonus DECIMAL(14,4) DEFAULT 0.00;
        DECLARE selectedSponsorResidualBonus DECIMAL(14,4) DEFAULT 0.00;
        DECLARE selectedGrandDailyBonus,selectedGrandMonthlyBonus,selectedUltimateDailyBonus,selectedUltimatMonthlyBonus,sumOfbenifits DECIMAL(14,4) DEFAULT 0.00;
        DECLARE selectedStarterPurchaseDate,selectedStarterMiningDate,selectedStarterCompletionDate,selectedStarterStatus VARCHAR(250) DEFAULT '';
        DECLARE selectedMiniPurchaseDate,selectedMiniMiningDate,selectedMiniCompletionDate,selectedMiniStatus VARCHAR(250) DEFAULT '';
        DECLARE selectedMediumPurchaseDate,selectedMediumMiningDate,selectedMediumCompletionDate,selectedMediumStatus VARCHAR(250) DEFAULT '';
        DECLARE selectedGrandPurchaseDate,selectedGrandMiningDate,selectedGrandCompletionDate,selectedGrandStatus VARCHAR(250) DEFAULT '';
        DECLARE selectedUltimatePurchaseDate,selectedUltimateMiningDate,selectedUltimateCompletionDate,selectedUltimateStatus VARCHAR(250) DEFAULT '';
        
        SET success = 0;
        SET lastInsertedId = 0;
        SET selectedStarterDailyBonus = 1.5;
        SET selectedStarterMonthlyBonus = 30;
        SET selectedMiniDailyBonus = 3;
        SET selectedMiniMonthlyBonus = 90;
        SET selectedMediumDailyBonus = 6;
        SET selectedMediumMonthlyBonus = 180;
        SET selectedGrandDailyBonus = 12;
        SET selectedGrandMonthlyBonus = 360;
        SET selectedUltimateDailyBonus = 24;
        SET selectedUltimatMonthlyBonus = 720;

            CREATE TEMPORARY TABLE IF NOT EXISTS tmp_target_user(
                    temp_id integer(10) PRIMARY KEY AUTO_INCREMENT,
                    user_name VARCHAR(250),
                    pool_name VARCHAR(250),
                    pool_table_name VARCHAR(250),
                    benifit_type TINYINT DEFAULT 0, -- 1:Daily 2:Monthly 
                    benifit_amount_usd DECIMAL(14,4),
                    created_date DATE
                );

            innerBlock:BEGIN   
            DECLARE targetUserCursor CURSOR FOR         

                    SELECT
                        u.id,
                        u.Fullname,
                        u.Username,
                        u.Email,
                        u.Status,
                        u.Password,
                        u.Sponsor,
                        u.created_at,
                        starter.PurchaseDate,starter.MiningDate,starter.CompletionDate,starter.Status,
                        mini.PurchaseDate,mini.MiningDate,mini.CompletionDate,mini.Status,
                        medium.PurchaseDate,medium.MiningDate,medium.CompletionDate,medium.Status,
                        grand.PurchaseDate,grand.MiningDate,grand.CompletionDate,grand.Status,
                        ultimate.PurchaseDate,ultimate.MiningDate,ultimate.CompletionDate,ultimate.Status
                    FROM
                        users AS u
                    LEFT JOIN starterpack AS starter
                        ON starter.Username=u.Username
                    LEFT JOIN minipack AS mini
                        ON mini.Username=u.Username
                    LEFT JOIN mediumpack AS medium
                        ON medium.Username=u.Username
                    LEFT JOIN grandpack AS grand
                        ON grand.Username=u.Username
                    LEFT JOIN ultimatepack AS ultimate
                        ON ultimate.Username=u.Username
                    WHERE 
                        u.Status = 'Close' 
                    AND 
                        u.Activation = '1'
                    AND 
                        if((pUserName <> ''),u.Username=pUserName, 1=1)
                    ORDER BY
                        u.id DESC;
            
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
                OPEN targetUserCursor;
                targetUser: LOOP
                    FETCH targetUserCursor INTO selectedUserId,selectedFullname,selectedUserName,selectedEmail,selectedStatus,selectedPassword,selectedSponsor,selecetdCreatedAt,
                                                selectedStarterPurchaseDate,selectedStarterMiningDate,selectedStarterCompletionDate,selectedStarterStatus,
                                                selectedMiniPurchaseDate,selectedMiniMiningDate,selectedMiniCompletionDate,selectedMiniStatus,
                                                selectedMediumPurchaseDate,selectedMediumMiningDate,selectedMediumCompletionDate,selectedMediumStatus,
                                                selectedGrandPurchaseDate,selectedGrandMiningDate,selectedGrandCompletionDate,selectedGrandStatus,
                                                selectedUltimatePurchaseDate,selectedUltimateMiningDate,selectedUltimateCompletionDate,selectedUltimateStatus
                                                    ;
                    
                        IF done = 1 THEN 
                            LEAVE targetUser;
                        END IF;
                        SET sumOfbenifits = 0;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR STARTER START 
                        -- ========================================================================================================================================================================================================
                                IF (date(selectedStarterCompletionDate) > CURRENT_DATE() AND selectedStarterCompletionDate <> 0 AND date(selectedStarterMiningDate) < CURRENT_DATE() AND selectedStarterMiningDate <> 0 AND selectedStarterStatus = 'Active') THEN
                                    -- QUERY TO INSERT DAILY BONUS --
                                    INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Starter','starterpack',1,selectedStarterDailyBonus,now());
                                    INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Starter', 0, selectedStarterDailyBonus, 'Paid', selectedUserName, '0');     
                                    SET sumOfbenifits = (sumOfbenifits + selectedStarterDailyBonus);
                                    IF DATE_FORMAT(selectedStarterMiningDate,'%d') = DATE_FORMAT(CURRENT_DATE(),'%d') THEN
                                        -- QUERY TO INSERT MONTHLY BONUS --
                                        INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Starter','starterpack',2,selectedStarterMonthlyBonus,now());
                                        INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Starter', 0, selectedStarterMonthlyBonus, 'Paid', selectedUserName, '1'); 
                                    SET sumOfbenifits = (sumOfbenifits + selectedStarterMonthlyBonus);
                                    END IF;  
                                END IF;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR STARTER END 
                        -- ========================================================================================================================================================================================================
                        
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR MINI START 
                        -- ========================================================================================================================================================================================================
                                IF (date(selectedMiniCompletionDate) > CURRENT_DATE() AND selectedMiniCompletionDate <> 0 AND date(selectedMiniMiningDate) < CURRENT_DATE() AND selectedMiniMiningDate <> 0 AND selectedMiniStatus = 'Active') THEN
                                    -- QUERY TO INSERT DAILY BONUS --
                                    INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Mini','minipack',1,selectedMiniDailyBonus,now());
                                    INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Mini', 0, selectedMiniDailyBonus, 'Paid', selectedUserName, '0');
                                    SET sumOfbenifits = (sumOfbenifits + selectedMiniDailyBonus);
                                    IF DATE_FORMAT(selectedMiniMiningDate,'%d') = DATE_FORMAT(CURRENT_DATE(),'%d') THEN
                                        -- QUERY TO INSERT MONTHLY BONUS --
                                        INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Mini','minipack',2,selectedMiniMonthlyBonus,now());
                                        INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Mini', 0, selectedMiniMonthlyBonus, 'Paid', selectedUserName, '1');
                                        SET sumOfbenifits = (sumOfbenifits + selectedMiniMonthlyBonus);
                                    END IF;  
                                END IF;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR MINI END 
                        -- ========================================================================================================================================================================================================

                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR MEDIUM START 
                        -- ========================================================================================================================================================================================================
                                IF (date(selectedMediumCompletionDate) > CURRENT_DATE() AND selectedMediumCompletionDate <> 0 AND date(selectedMediumMiningDate) < CURRENT_DATE() AND selectedMediumMiningDate <> 0 AND selectedMediumStatus = 'Active') THEN
                                    -- QUERY TO INSERT DAILY BONUS --
                                    INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Medium','mediumpack',1,selectedMediumDailyBonus,now());
                                    INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Medium', 0, selectedMediumDailyBonus, 'Paid', selectedUserName, '0');
                                    SET sumOfbenifits = (sumOfbenifits + selectedMediumDailyBonus);
                                    IF DATE_FORMAT(selectedMediumMiningDate,'%d') = DATE_FORMAT(CURRENT_DATE(),'%d') THEN
                                        -- QUERY TO INSERT MONTHLY BONUS --
                                        INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Medium','mediumpack',2,selectedMediumMonthlyBonus,now());
                                        INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Medium', 0, selectedMediumMonthlyBonus, 'Paid', selectedUserName, '1');
                                        SET sumOfbenifits = (sumOfbenifits + selectedMediumMonthlyBonus);
                                    END IF;  
                                END IF;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR MEDIUM END 
                        -- ========================================================================================================================================================================================================

                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR GRAND START 
                        -- ========================================================================================================================================================================================================
                                IF (date(selectedGrandCompletionDate) > CURRENT_DATE() AND selectedGrandCompletionDate <> 0 AND date(selectedGrandMiningDate) < CURRENT_DATE() AND selectedGrandMiningDate <> 0 AND selectedGrandStatus = 'Active') THEN
                                        -- QUERY TO INSERT DAILY BONUS --
                                        INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Grand','grandpack',1,selectedGrandDailyBonus,now());
                                        INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Grand', 0, selectedGrandDailyBonus, 'Paid', selectedUserName, '0');
                                        SET sumOfbenifits = (sumOfbenifits + selectedGrandDailyBonus);
                                        IF DATE_FORMAT(selectedGrandMiningDate,'%d') = DATE_FORMAT(CURRENT_DATE(),'%d') THEN
                                                -- QUERY TO INSERT MONTHLY BONUS --
                                            INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Grand','grandpack',2,selectedGrandMonthlyBonus,now());
                                            INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Grand', 0, selectedGrandMonthlyBonus, 'Paid', selectedUserName, '1');
                                            SET sumOfbenifits = (sumOfbenifits + selectedGrandMonthlyBonus);
                                        END IF;  
                                END IF;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR GRAND END 
                        -- ========================================================================================================================================================================================================

                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR ULTIMATE START 
                        -- ========================================================================================================================================================================================================
                            IF (date(selectedUltimateCompletionDate) > CURRENT_DATE() AND selectedUltimateCompletionDate <> 0 AND date(selectedUltimateMiningDate) < CURRENT_DATE() AND selectedUltimateMiningDate <> 0 AND selectedUltimateStatus = 'Active') THEN
                                -- QUERY TO INSERT DAILY BONUS --
                                INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Ultimate','ultimatepack',1,selectedUltimateDailyBonus,now());
                                INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Ultimate', 0, selectedUltimateDailyBonus, 'Paid', selectedUserName, '0');
                                SET sumOfbenifits = (sumOfbenifits + selectedUltimateDailyBonus);
                                IF DATE_FORMAT(selectedUltimateMiningDate,'%d') = DATE_FORMAT(CURRENT_DATE(),'%d') THEN
                                    -- QUERY TO INSERT MONTHLY BONUS --
                                    INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Ultimate','ultimatepack',2,selectedUltimateMonthlyBonus,now());
                                    INSERT INTO dailymine (`Date`, Pack, Btc, Usd, Status, Username, is_monthly_mining) VALUES (now(), 'Ultimate', 0, selectedUltimateMonthlyBonus, 'Paid', selectedUserName, '1');
                                    SET sumOfbenifits = (sumOfbenifits + selectedUltimateMonthlyBonus);
                                END IF;  
                            END IF;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR ULTIMATE END 
                        -- ========================================================================================================================================================================================================
                            
                        IF(sumOfbenifits > 0 ) THEN 
                            UPDATE mining SET Balance = (Balance+sumOfbenifits),updated_at=now() WHERE Username=selectedUserName;
                            UPDATE accountbalance SET Balance = (Balance+sumOfbenifits),updated_at=now() WHERE Username=selectedUserName;

                            INSERT INTO bmp_bonus_commission_earn_log (user_name, reason_id, reason_description, is_added_by_cron, amount, added_in) 
                                                              VALUES (selectedUserName, '5', CONCAT('Mining earning of user ',selectedUserName), '1', sumOfbenifits, 'mining');

                            -- TO PROCESS RESIDUAL BONUS --
                            SET selectedSponsorResidualBonus = 0; 
                            IF(selectedSponsor <> '' AND selectedSponsor IS NOT NULL) THEN
                                SET selectedSponsorResidualBonus = (sumOfbenifits * 0.005);
                                UPDATE team SET Balance = (Balance+selectedSponsorResidualBonus),updated_at=now() WHERE Username=selectedSponsor;
                                UPDATE accountbalance SET Balance = (Balance+selectedSponsorResidualBonus),updated_at=now() WHERE Username=selectedSponsor;

                                INSERT INTO bmp_bonus_commission_earn_log (user_name, reason_id, reason_description, is_added_by_cron, amount, added_in) 
                                                              VALUES (selectedSponsor, '4', CONCAT('Residual bonus of parent user ',selectedSponsor,' on joining of ',selectedUserName), '1', selectedSponsorResidualBonus, 'team');

                            END IF;
                        END IF;
                      END LOOP targetUser;
                CLOSE targetUserCursor;
                END innerBlock;
                
              --  INSERT INTO()
                SELECT * FROM tmp_target_user;
                DROP TEMPORARY TABLE tmp_target_user;
        END$$
DELIMITER ;