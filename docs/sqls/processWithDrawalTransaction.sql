DELIMITER $$
DROP PROCEDURE IF EXISTS processWithDrawalTransaction$$
 CREATE PROCEDURE processWithDrawalTransaction(IN pUserName VARCHAR(250), IN pStatus VARCHAR(250), IN pTransactionID INT(11))
processWithDrawalTransaction: BEGIN
        DECLARE success INT(11) DEFAULT 0;
        DECLARE approve_bill, custBillCount, customerId,lastInsertedId INT(11) DEFAULT  0;
        DECLARE responseMessage,selectedUserName,selectedAddress,selectedAccountBalance VARCHAR(250) DEFAULT '';
        DECLARE selectedAmount DECIMAL(14,4) DEFAULT 0.0000;

        
            SET success = 0;
            SET lastInsertedId = 0;
            
            
            START TRANSACTION;
            IF EXISTS (SELECT * FROM bmp_wallet_withdrawl_transactions WHERE status = 1 AND id = pTransactionID order by id desc limit 1) THEN
                -- CODE TO APPROVE THE WITHDRAWAL TRANSACTION -- 
                IF pStatus = '2' THEN 
                    SELECT user_name,amount,to_address INTO selectedUserName,selectedAmount,selectedAddress FROM bmp_wallet_withdrawl_transactions WHERE status = 1 AND id = pTransactionID;

                    SELECT Balance INTO selectedAccountBalance FROM accountbalance WHERE Username = selectedUserName;
                    IF ( selectedAmount <= selectedAccountBalance ) THEN 

                        UPDATE accountbalance SET Balance = (Balance - selectedAccountBalance),updated_at=now() WHERE Username=selectedUserName; 
                        UPDATE bmp_wallet_withdrawl_transactions SET status = '2',updated_at=now() WHERE user_name=selectedUserName AND status = 1 AND id = pTransactionID; 
                        IF ROW_COUNT() > 0 THEN
                            SET lastInsertedId = pTransactionID;               
                        END IF;

                        IF lastInsertedId = 0 THEN
                            SET responseMessage = 'Problem to update transaction.';
                            SET success = 0;
                            ROLLBACK;
                            SELECT success AS response,lastInsertedId,responseMessage; 
                            LEAVE processWithDrawalTransaction;		
                        ELSE    
                            SET responseMessage = 'Transaction updated succesfully.';
                            SET success = 1;
                            COMMIT;
                            SELECT success AS response,lastInsertedId,responseMessage; 
                            LEAVE processWithDrawalTransaction;

                        END IF;
                    ELSE 
                        SET responseMessage = 'User has insufficient balance to process this request.';
                        SET success = 0;
                    END IF;
                    -- CODE TO REJECT THE WITHDRAWAL TRANSACTION -- 
                ELSEIF pStatus = '3' THEN 
                    UPDATE bmp_wallet_withdrawl_transactions SET status = '3',updated_at=now() WHERE status = 1 AND id = pTransactionID; 
                    IF ROW_COUNT() > 0 THEN
                        SET lastInsertedId = pTransactionID;               
                    END IF;

                    IF lastInsertedId = 0 THEN
                        SET responseMessage = 'Problem to update transaction.';
                        SET success = 0;
                        ROLLBACK;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE processWithDrawalTransaction;		
                    ELSE    
                        SET responseMessage = 'Transaction updated succesfully.';
                        SET success = 1;
                        COMMIT;
                        SELECT success AS response,lastInsertedId,responseMessage; 
                        LEAVE processWithDrawalTransaction;
                    END IF;
                END IF;
               
            ELSE 
                SET responseMessage = 'Transaction is not exist.';
                SET success = 0;
            END IF;
            
        SELECT success AS response, lastInsertedId,responseMessage; 
        
        COMMIT;   
        END$$
DELIMITER ;