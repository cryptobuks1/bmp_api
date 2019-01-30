DELIMITER $$ 
DROP PROCEDURE IF EXISTS getUserAccountStatementData$$
CREATE PROCEDURE getUserAccountStatementData(IN pUserName VARCHAR(250), IN startDate DATE,IN endDate DATE)
BEGIN
    DECLARE done,lifetime_stock_issued_quantity,lifetime_stock_used_quantity INTEGER(50) DEFAULT 0;
    DECLARE lifetime_stock_issued_value,lifetime_stock_used_value DECIMAL(14,2) DEFAULT 0;
    DECLARE selectedDate DATE DEFAULT NULL;
    
    IF startDate IS NULL AND endDate IS NULL THEN
        SET endDate = CURDATE();
        SET startDate = DATE_SUB(endDate, INTERVAL 30 DAY);
    END IF;
    innerBlock:BEGIN   
        DECLARE dateCursor CURSOR FOR 
        SELECT * FROM 
            (SELECT adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date FROM
            (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
            (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
            (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
            (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
            (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
        WHERE selected_date BETWEEN DATE_FORMAT(startDate,'%Y-%m-%d') AND DATE_FORMAT(endDate,'%Y-%m-%d');

        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

        CREATE TEMPORARY TABLE IF NOT EXISTS temp_account_statement(
                 temp_id INT unsigned PRIMARY KEY  NOT NULL AUTO_INCREMENT,            
                 user_name VARCHAR(500),
                 transaction_date DATE,
                 transaction_narration VARCHAR(1000),
                 transaction_ref_no VARCHAR(500),
                 withdrawal DECIMAL(14,2),
                 deposit DECIMAL(14,2),
                 created_date DATE          
        );  

        OPEN dateCursor;
            affiliate: LOOP
                FETCH dateCursor INTO selectedDate;
                IF done = 1 THEN 
                    LEAVE affiliate;
                END IF;
				IF pUserName <> NULL OR pUserName <> '' THEN 
					INSERT INTO temp_account_statement (
                    user_name,transaction_date,transaction_narration,transaction_ref_no,withdrawal,deposit,created_date)
               
                    SELECT user_name,DATE_FORMAT(created_at,'%Y-%m-%d'),reason_description,(CASE WHEN reason_id='1' THEN 'Direct Commision' WHEN reason_id='2' THEN 'Indirect Commision' WHEN reason_id='3' THEN 'Matching Bonus' WHEN reason_id='4' THEN 'Residual Bonus' WHEN reason_id='5' THEN 'Mining Earning' ELSE '' END),0,amount,CURDATE() FROM `bmp_bonus_commission_earn_log` WHERE DATE_FORMAT(created_at,'%Y-%m-%d') = selectedDate AND user_name = pUserName
                    UNION
                    SELECT user_name,DATE_FORMAT(created_at,'%Y-%m-%d'),CONCAT('Transferred to ',' ',to_address),id,amount,0,CURDATE() FROM `bmp_wallet_withdrawl_transactions` 
                    WHERE DATE_FORMAT(created_at,'%Y-%m-%d') = selectedDate AND user_name = pUserName;
				ELSE 
                INSERT INTO temp_account_statement (
                    user_name,transaction_date,transaction_narration,transaction_ref_no,withdrawal,deposit,created_date)
               
                    SELECT user_name,DATE_FORMAT(created_at,'%Y-%m-%d'),reason_description,(CASE WHEN reason_id='1' THEN 'Direct Commision' WHEN reason_id='2' THEN 'Indirect Commision' WHEN reason_id='3' THEN 'Matching Bonus' WHEN reason_id='4' THEN 'Residual Bonus' WHEN reason_id='5' THEN 'Mining Earning' ELSE '' END),0,amount,CURDATE() FROM `bmp_bonus_commission_earn_log` WHERE DATE_FORMAT(created_at,'%Y-%m-%d') = selectedDate
                    UNION
                    SELECT user_name,DATE_FORMAT(created_at,'%Y-%m-%d'),CONCAT('Transferred to ',' ',to_address),id,amount,0,CURDATE() FROM `bmp_wallet_withdrawl_transactions`
                    WHERE DATE_FORMAT(created_at,'%Y-%m-%d') = selectedDate;
				END IF;	
				
            END LOOP affiliate;
        CLOSE dateCursor;
    END innerBlock;
SELECT * FROM temp_account_statement order by transaction_date desc;

DROP TEMPORARY TABLE temp_account_statement;
END$$
DELIMITER ;
