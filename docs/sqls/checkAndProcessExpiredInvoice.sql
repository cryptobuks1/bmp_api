DELIMITER $$ 
DROP PROCEDURE IF EXISTS checkAndProcessExpiredInvoice$$
CREATE PROCEDURE checkAndProcessExpiredInvoice(IN pUserName VARCHAR(255),IN pInvoiceId VARCHAR(255),IN isRenewal INT)
checkAndProcessExpiredInvoice: BEGIN
    DECLARE packageList,selectedPackPurposeCron,selectedPackUserNameCron VARCHAR(500) DEFAULT '';
    DECLARE done INT(11) DEFAULT 0;

	IF pUserName = '' THEN
		SET pUserName = NULL;
	END IF;
	
	IF pInvoiceId = '' THEN
		SET pInvoiceId = NULL;
	END IF;
	
    CREATE TEMPORARY TABLE IF NOT EXISTS temp_expire_package (
         temp_id INT unsigned PRIMARY KEY  NOT NULL AUTO_INCREMENT,            
         selectedPaydateDB VARCHAR(250),
         selectedInvoiceidDB VARCHAR(250),
         selectedPurposeDB VARCHAR(250),
         selectedBtcaddressDB VARCHAR(250),
         selectedAmountDB VARCHAR(250),
         selectedBtcamountDB VARCHAR(250),
         selectedStatusDB VARCHAR(250),
         selectedUsernameDB VARCHAR(250),
         created_date DATE            
    );
 
    IF isRenewal = 1 THEN 
        SELECT GROUP_CONCAT(i.id) INTO packageList  FROM invoice AS i 
        WHERE IF(pUserName IS NOT NULL, i.Username = pUserName, 1=1)
        AND IF(pInvoiceId IS NOT NULL, i.Invoiceid = pInvoiceId, 1=1)
        AND i.Status = 'Paid' AND i.Purpose <> 'Registration'
        AND DATEDIFF(DATE_ADD(DATE_FORMAT(i.created_at,'%y-%m-%d'), INTERVAL 364 DAY),CURDATE()) <= 3 ;

    ELSE 
        SELECT GROUP_CONCAT(i.id) INTO packageList  FROM invoice AS i 
        WHERE IF(pUserName IS NOT NULL, i.Username = pUserName, 1=1)
        AND IF(pInvoiceId IS NOT NULL, i.Invoiceid = pInvoiceId, 1=1)
        AND i.Status = 'Paid' AND i.Purpose <> 'Registration'    
        AND DATE_ADD(DATE_FORMAT(i.created_at,'%y-%m-%d'), INTERVAL 364 DAY) < CURDATE();
    END IF;

    INSERT INTO temp_expire_package (selectedPaydateDB,selectedInvoiceidDB,selectedPurposeDB,selectedBtcaddressDB,selectedAmountDB,selectedBtcamountDB,selectedStatusDB,selectedUsernameDB,created_date)
    SELECT i.Paydate,i.Invoiceid,i.Purpose,i.Btcaddress,i.Amount,i.Btcamount,i.Status,i.Username,CURDATE()
    FROM invoice AS i
    WHERE FIND_IN_SET(i.id, packageList)
    ORDER BY i.id ASC;
	
 innerBlock:BEGIN
 	
    DECLARE targetPackageCursor CURSOR FOR
        SELECT 
            selectedPurposeDB,selectedUsernameDB
        FROM 
            temp_expire_package;
            
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
        OPEN targetPackageCursor;
                targetPackage: LOOP
                 FETCH targetPackageCursor INTO selectedPackPurposeCron,selectedPackUserNameCron;
				IF done = 1 THEN 
                    LEAVE targetPackage;
                END IF;
				 
				IF selectedPackPurposeCron = 'Starter'  THEN 
					UPDATE  starterpack SET PurchaseDate = 0,MiningDate = 0,Status='Inactive',CompletionDate = 0,Comment='Not-Purchased' WHERE Username = selectedPackUserNameCron;
				ELSEIF selectedPackPurposeCron = 'Mini' THEN
					UPDATE  minipack SET PurchaseDate = 0,MiningDate = 0,Status='Inactive',CompletionDate = 0,Comment='Not-Purchased' WHERE Username = selectedPackUserNameCron;
				ELSEIF selectedPackPurposeCron = 'Medium' THEN
					UPDATE  mediumpack SET PurchaseDate = 0,MiningDate = 0,Status='Inactive',CompletionDate = 0,Comment='Not-Purchased' WHERE Username = selectedPackUserNameCron;
				ELSEIF selectedPackPurposeCron = 'Grand' THEN
					UPDATE  grandpack SET PurchaseDate = 0,MiningDate = 0,Status='Inactive',CompletionDate = 0,Comment='Not-Purchased' WHERE Username = selectedPackUserNameCron;
				ELSEIF selectedPackPurposeCron = 'Ultimate' THEN
					UPDATE  ultimatepack SET PurchaseDate = 0,MiningDate = 0,Status='Inactive',CompletionDate = 0,Comment='Not-Purchased' WHERE Username = selectedPackUserNameCron;
				END IF;
				

         END LOOP targetPackage;
        CLOSE targetPackageCursor;
        END innerBlock;
		
	UPDATE invoice SET Status = "Expired", updated_at = CURRENT_TIMESTAMP()
    WHERE FIND_IN_SET(id, packageList);
    -- Add logic here to update starter pack n other pack. 
    -- UPDATE customer_order SET order_status = 5, updated_by = 1, updated_at = CURRENT_TIMESTAMP()
    -- WHERE FIND_IN_SET(id, packageList);
	
    SELECT * FROM temp_expire_package;

    DROP TEMPORARY TABLE temp_expire_package;
END$$
DELIMITER ;