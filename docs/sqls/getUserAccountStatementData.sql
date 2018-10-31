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

                INSERT INTO temp_account_statement (user_name,transaction_date) VALUES (pUserName,selectedDate);
                /*SELECT ppcode.affiliate_id,ppcode.merchant_id,
                (SELECT merchant_name FROM merchants WHERE id = ppcode.merchant_id) ,
                (SELECT location_name FROM merchant_locations WHERE id = ppcode.location_id),
                (SELECT tittle FROM affiliate_partners WHERE id = ppcode.affiliate_id), 
                (SELECT IF(status=1,'Active','Inactive') FROM affiliate_partners WHERE id = ppcode.affiliate_id), 
                
                ((SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND DATE_FORMAT(created_at,'%Y-%m-%d') <= selectedDate) -
                 (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 1 AND DATE_FORMAT(updated_at,'%Y-%m-%d') < selectedDate)) as opening, 
                ((SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND DATE_FORMAT(created_at,'%Y-%m-%d') <= selectedDate) -
                 (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 1 AND DATE_FORMAT(updated_at,'%Y-%m-%d') < selectedDate)) * 
                (SELECT package_price FROM affiliate_partners_packages WHERE id = ppcode.target_Ids), 

                (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND DATE_FORMAT(created_at,'%Y-%m-%d') = selectedDate) as added,
                (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND DATE_FORMAT(created_at,'%Y-%m-%d') = selectedDate) * 
                (SELECT package_price FROM affiliate_partners_packages WHERE id = ppcode.target_Ids),

                (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 1 AND DATE_FORMAT(updated_at,'%Y-%m-%d') = selectedDate) as used,
                (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 1 AND DATE_FORMAT(updated_at,'%Y-%m-%d') = selectedDate) *  
                (SELECT package_price FROM affiliate_partners_packages WHERE id = ppcode.target_Ids),

                ((SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND DATE_FORMAT(created_at,'%Y-%m-%d') <= selectedDate) -
                 (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 1 AND DATE_FORMAT(updated_at,'%Y-%m-%d') <= selectedDate)) as current_stk,
                ((SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND DATE_FORMAT(created_at,'%Y-%m-%d') <= selectedDate) -
                 (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 1 AND DATE_FORMAT(updated_at,'%Y-%m-%d') <= selectedDate)) *  
                (SELECT package_price FROM affiliate_partners_packages WHERE id = ppcode.target_Ids),

                (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 0 AND DATE_FORMAT(promocode_end_date,'%Y-%m-%d') > selectedDate) as expired,
                (SELECT COUNT(id) FROM package_promocodes WHERE affiliate_id = ppcode.affiliate_id AND promocode_used = 0 AND DATE_FORMAT(promocode_end_date,'%Y-%m-%d') > selectedDate) *  
                (SELECT package_price FROM affiliate_partners_packages WHERE id = ppcode.target_Ids),
                (SELECT package_price FROM affiliate_partners_packages WHERE id = ppcode.target_Ids),selectedDate
                FROM package_promocodes as ppcode               
                WHERE DATE_FORMAT(ppcode.created_at,'%Y-%m-%d') = selectedDate OR DATE_FORMAT(ppcode.updated_at,'%Y-%m-%d') = selectedDate
                ORDER BY ppcode.id DESC;*/
            END LOOP affiliate;
        CLOSE dateCursor;
    END innerBlock;
SELECT * FROM temp_account_statement;
/*SELECT COUNT(id) INTO lifetime_stock_issued_quantity FROM package_promocodes
WHERE affiliate_id != 0
AND IF(affiliateId != 0,affiliate_id = affiliateId,1=1)
AND IF(merchantId != 0,merchant_id = merchantId,1=1)
AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN startDate AND endDate;

SELECT COUNT(id) INTO lifetime_stock_used_quantity  FROM package_promocodes 
WHERE affiliate_id != 0
AND promocode_used = 1
AND IF(affiliateId != 0,affiliate_id = affiliateId,1=1)
AND IF(merchantId != 0,merchant_id = merchantId,1=1)
AND DATE_FORMAT(updated_at,'%Y-%m-%d') BETWEEN startDate AND endDate;

SELECT temp_affiliate_inventory.*,lifetime_stock_issued_quantity,lifetime_stock_used_quantity FROM temp_affiliate_inventory
WHERE affiliate_id != 0
AND IF(affiliateId != 0,affiliate_id = affiliateId,1=1)
AND IF(merchantId != 0,merchant_id = merchantId,1=1)
AND (stock_added_quantity > 0 OR stock_used_quantity > 0 OR expired_stock_quantity > 0) 
GROUP BY affiliate_id,created_date;*/
DROP TEMPORARY TABLE temp_account_statement;
END$$
DELIMITER ;