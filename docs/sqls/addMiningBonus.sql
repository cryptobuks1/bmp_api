DELIMITER $$
DROP PROCEDURE IF EXISTS addMiningBonus$$
CREATE PROCEDURE addMiningBonus(IN pUserName VARCHAR(250))
addMiningBonus: BEGIN
        DECLARE success,done,doneInner,selectedUserId,lastInsertedId,isMonthlyApplicable,isDailyApplicable,selectedPoolId INT(11) DEFAULT 0;
        DECLARE responseMessage,selectedFullname,selectedUserName,selectedEmail,selectedStatus,selectedPassword,selectedSponsor,selectedPoolName,selectedPooltableName,tempStr,miningMonthDate,currentMonthDate VARCHAR(250) DEFAULT '';
        DECLARE selecetdCreatedAt DATE;
        DECLARE selectedDailyBonus,selectedMonthlyBonus DECIMAL(14,4) DEFAULT 0.00;
        SET success = 0;
        SET lastInsertedId = 0;

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
                        id,
                        Fullname,
                        Username,
                        Email,
                        Status,
                        Password,
                        Sponsor,
                        created_at
                    FROM
                        users
                    WHERE 
                        Status = 'Close' 
                    AND 
                        Activation = '1'
                    AND 
                        if((pUserName <> ''),Username=pUserName, 1=1)
                    ORDER BY
                        id DESC;
            
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
                OPEN targetUserCursor;
                targetUser: LOOP
                    FETCH targetUserCursor INTO selectedUserId,selectedFullname,selectedUserName,selectedEmail,selectedStatus,selectedPassword,selectedSponsor,selecetdCreatedAt;
                    
                    IF done = 1 THEN 
                        LEAVE targetUser;
                    END IF;
                   /* innerPoolBlock:BEGIN   
                    DECLARE targetPoolCursor CURSOR FOR  
                        SELECT
                            id,
                            pool_name,
                            pool_table_name,
                            daily_bonus,
                            monthly_bonus
                          FROM
                            bmp_pool_benifits;

                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET doneInner = 1;
                        OPEN targetPoolCursor;
                        targetPool: LOOP
                            FETCH targetPoolCursor INTO selectedPoolId,selectedPoolName,selectedPooltableName,selectedDailyBonus,selectedMonthlyBonus;

                            IF doneInner = 1 THEN 
                                LEAVE targetPool;
                            END IF;
                            
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE START
                        -- ========================================================================================================================================================================================================
                            SET @tempStr = CONCAT("SELECT *,DATE_FORMAT(MiningDate,'%d') AS miningMonthDate,DATE_FORMAT(CURRENT_DATE(),'%d') AS currentMonthDate FROM " , selectedPooltableName ," WHERE  CompletionDate > CURRENT_DATE() AND CompletionDate <> 0 AND MiningDate < CURRENT_DATE() AND MiningDate <> 0 AND  Username = '", selectedUserName ,"' AND Status = 'Active'");
                            
                            PREPARE stmt FROM @tempStr;
                            EXECUTE stmt;
                            
                            SELECT miningMonthDate,currentMonthDate;
                            IF EXISTS (tempStr) THEN
                                -- QUERY TO INSERT DAILY BONUS --
                                INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,selectedPoolName,selectedPooltableName,1,selectedDailyBonus,now());
                                IF miningMonthDate = currentMonthDate THEN
                                     -- QUERY TO INSERT MONTHLY BONUS --
                                    INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,selectedPoolName,selectedPooltableName,2,selectedMonthlyBonus,now());
                                END IF;    
                            END IF;
                            DEALLOCATE PREPARE stmt;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE END
                        -- ========================================================================================================================================================================================================


                            -- SELECT selectedUserId,selectedFullname,selectedUserName,selectedEmail,selectedStatus,selectedPassword,selectedSponsor,selecetdCreatedAt;
                        
                        END LOOP targetPool;
                        CLOSE targetUserCursor;
                    END innerPoolBlock;
*/
                         -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE FOR STARTER START 
                        -- ========================================================================================================================================================================================================

                           IF EXISTS (SELECT * FROM starterpack WHERE  CompletionDate > CURRENT_DATE() AND CompletionDate <> 0 AND MiningDate < CURRENT_DATE() AND MiningDate <> 0 AND  Username = selectedUserName AND Status = 'Active' ) THEN
                                
                                SELECT DATE_FORMAT(MiningDate,'%d') AS miningMonthDate,DATE_FORMAT(CURRENT_DATE(),'%d') AS currentMonthDate  FROM starterpack WHERE  CompletionDate > CURRENT_DATE() AND CompletionDate <> 0 AND MiningDate < CURRENT_DATE() AND MiningDate <> 0 AND  Username = selectedUserName AND Status = 'Active';
                                SELECT daily_bonus AS selectedDailyBonus,monthly_bonus AS selectedMonthlyBonus FROM bmp_pool_benifits WHERE pool_table_name = 'starterpack';
                                SET selectedDailyBonus = selectedDailyBonus;
                                -- QUERY TO INSERT DAILY BONUS --
                                INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Starter','starterpack',1,selectedDailyBonus,now());
                                IF miningMonthDate = currentMonthDate THEN
                                     -- QUERY TO INSERT MONTHLY BONUS --
                                    INSERT INTO tmp_target_user(user_name,pool_name,pool_table_name,benifit_type,benifit_amount_usd, created_date) VALUES(selectedUserName,'Starter','starterpack',2,selectedMonthlyBonus,now());
                                END IF;    
                            END IF;
                        -- ========================================================================================================================================================================================================
                        -- CHECK THAT MONTHLY AND DAILY MINING BENIFIT IS APPLICABLE STARTER END
                        -- ========================================================================================================================================================================================================




                      END LOOP targetUser;
                CLOSE targetUserCursor;
                END innerBlock;
                SELECT * FROM tmp_target_user;
                DROP TEMPORARY TABLE tmp_target_user;
        END$$
DELIMITER ;