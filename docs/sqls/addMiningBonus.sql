DELIMITER $$
DROP PROCEDURE IF EXISTS addMiningBonus$$
 CREATE PROCEDURE addMiningBonus()
addMiningBonus: BEGIN
        DECLARE success INT(11) DEFAULT 0;
        DECLARE approve_bill, custBillCount, customerId,lastInsertedId INT(11) DEFAULT  0;
        DECLARE responseMessage VARCHAR(250) DEFAULT '';

        
            SET success = 0;
            SET lastInsertedId = 0;
                innerBlock:BEGIN   
                DECLARE targetUserCursor CURSOR FOR         

                    SELECT
                        ppm.id,
                        pm.merchant_id,
                        trp.nextLocationVal,
                       	trp.nextlenQtyVal,
                        pm.product_category,
                        pm.product_sub_category,
                        (ppm.selling_price_points_value * ptsRatio),
                        (ppm.special_price_points_value * ptsRatio),
                        ppm.special_price_start_date,
                        ppm.special_price_end_date

                    FROM
                    	`tmp_redeeming_product` AS trp 
                    JOIN
                        `products_merchant` AS ppm ON ppm.id = trp.nextProductVal
                    JOIN     
                        `products_master` AS pm ON pm.id=ppm.product_id
                    
                    WHERE
                        FIND_IN_SET(ppm.id,targetId)
                    GROUP BY trp.nextProductVal,trp.nextLocationVal;
                    -- AND
                       -- pm.merchant_id=merchantId;
            
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
                OPEN targetProductsCursor;
                targetProduct: LOOP
                    FETCH targetProductsCursor INTO selectedProductId,selectedProductMerchantId,salectedLocationId,quantityPassed,selectedProductCategoryId,selectedProductSubCategoryId,selectedProductSellingPricePoints,selectedProductSpecialPricePoints,selectedProductSpecialPriceStartDate,selectedProductSpecialPriceEndDate;

                    IF done = 1 THEN 
                        -- SELECT 'PROMOCODE IS NOT VALID FOR THIS MERCHANT/PRODUCTS.' AS response,'0' AS isPromocodeValid;
                        LEAVE targetProduct;
                        -- LEAVE verifyPromocode;
                    END IF;
                END LOOP targetProduct;
                CLOSE targetProductsCursor;
                END innerBlock;
                
 /*               START TRANSACTION;
                IF NOT EXISTS (SELECT * FROM `users` WHERE  Username=user_name AND Password=password order by id desc limit 1) THEN

                INSERT INTO users (Fullname, Country, Email, Telephone, Gender, Username, Password, Sponsor, Token, Account, Status, Activation, treestatus,platform,is_wallet_user) VALUES(name,country,email,telephone,gender,user_name,password,sponsor_account,token,account,status,activation,'notree',platform,isWalletUser);
                SET lastInsertedId = LAST_INSERT_ID(); 
                SET customerId = LAST_INSERT_ID();
                INSERT INTO accountbalance (Balance, Username) VALUES('0',user_name);
                INSERT INTO binaryincome(userid, day_bal, current_bal, total_bal) VALUES(user_name,'0','0','0');
                INSERT INTO hubcoin (Balance, Username) VALUES('0',user_name);
                INSERT INTO team (Balance, Username) VALUES('0',user_name);
                INSERT INTO teamvolume (Balance, Username) VALUES('0',user_name);
                INSERT INTO rank (Rank, Rankid, Username, Sponsor) VALUES('Miner','1',user_name,sponsor_account);
                INSERT INTO mining (Balance, Username) VALUES('0',user_name);
                INSERT INTO commission (Balance, Username) VALUES('0',user_name);
                INSERT INTO starterpack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '547.50', '0', 'Not-Purchased');
                INSERT INTO minipack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '1095', '0', 'Not-Purchased');
                INSERT INTO mediumpack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '2190', '0', 'Not-Purchased');
                INSERT INTO grandpack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable,Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '4380', '0', 'Not-Purchased');
                INSERT INTO ultimatepack (PurchaseDate, MiningDate, Username, Status, CompletionDate, TotalMinable, Withdrawal, Comment) VALUES('0', '0', user_name, 'Inactive', '0', '8760', '0', 'Not-Purchased');
                INSERT INTO register (EntryDate, Amount, Username) VALUES('0', '0', user_name);


                
                IF lastInsertedId = 0 THEN
                        SET responseMessage = 'Problem to add customer.';
                        SET success = 0;
                        ROLLBACK;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE insertCustomer;		
                ELSE    
                        SET responseMessage = 'Customer added succesfully.';
                        SET success = 1;
                    	COMMIT;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE insertCustomer;

                END IF;
            ELSE 
                SET responseMessage = 'Customer already exist.';
                SET success = 0;
            END IF;
        SELECT success AS response, lastInsertedId,responseMessage; 
        
        COMMIT;  */ 
        END$$
DELIMITER ;