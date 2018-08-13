<?php

/**
 * To Manupulate Invoice table
 *
 * 
 * 
 */

namespace Api\Models;

use Api\Models\ApiModel;
use PDO;
use Redis;
use Exception;
use stdClass;

class Invoice extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'invoice'; //Initialize table name for model

    /**
     * Accept bulk data and insert into table
     * @param type $invoice
     * @return Boolean
     */

    public function insert($invoice, $isIdRequired = 1) {
        try {
            //prepare statement for inserting the records start
            $this->pdo->beginTransaction();
            $strFields = implode(',', $this->fields);
            //$intColumnCount = 0;
            $intColumnCount = count($this->fields);
            if ($isIdRequired == 0) {
                $strFields = substr($strFields, 3);
                $intColumnCount = $intColumnCount - 1;
            }
            $values = "(" . implode(',', array_fill(0, $intColumnCount, '?')) . ")";
            $queryPart = array_fill(0, count($invoice), $values);

            $insertQuery = "INSERT INTO " . $this->tableName . " (" . $strFields . ") VALUES ";
            $insertQuery .= implode(',', $queryPart);

            $insertInvoice = $this->pdo->prepare($insertQuery); // Actual prepare statement
            //prepare statement for inserting the records end
            $i = 1;
            foreach ($invoice as $invoices) {
                $customer = $this->sanitizeAllData($invoices); // Get all data sanitized
//                print_r($customer);die;
                foreach ($this->fields as $field) {
                    if ($field == "id") {
                        if ($isIdRequired != 0) {
                            $insertInvoice->bindValue($i++, $invoices[$field]);
                        }
                    } else {
                        $insertInvoice->bindValue($i++, $invoices[$field]);
                    }
                }
            }

            $insertInvoice->execute(); //execute the prepare statement
            $lastId = [];
            $lastId['invoice_id'] = $this->pdo->lastInsertId();
            $this->pdo->commit();
            $result = $lastId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $result = 0;
        }
        return $result; //return the response
    }

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */
    private function sanitizeAllData($params = []) {
        $invoice = array();
        $invoice['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $invoice['Paydate'] = isset($params['Paydate']) ? filter_var($params['Paydate'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Paydate'] = isset($params['Paydate']) ? filter_var($params['Paydate'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Invoiceid'] = isset($params['Invoiceid']) ? filter_var($params['Invoiceid'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Purpose'] = isset($params['Purpose']) ? filter_var($params['Purpose'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Btcaddress'] = isset($params['Btcaddress']) ? filter_var($params['Btcaddress'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Amount'] = isset($params['Amount']) ? filter_var($params['Amount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Btcamount'] = isset($params['Btcamount']) ? filter_var($params['Btcamount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Status'] = isset($params['Status']) ? filter_var($params['Status'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $invoice['Username'] = isset($params['Username']) ? filter_var($params['Username'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';

        $invoice['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $invoice['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : NULL;

        return $invoice;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'Paydate',
            'Invoiceid',
            'Purpose',
            'Btcaddress',
            'Amount',
            'Btcamount',
            'Status',
            'Username',
            'api_response',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * check invoice is presented or not
     * return success response or error response in json 
     * return id in data params
     */
    public function isInvoicePresent($username, $purpose) {
        try {
           // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
                $stmt = $this->pdo->prepare("SELECT * FROM `invoice` WHERE Purpose=:Purpose AND Username=:Username AND Status='Unpaid' order by id desc limit 1");
                $stmt->execute(['Purpose' => $purpose,'Username'=>$username]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    return $result;
                } else {
                    return 0;
                }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * return customer details
     * return success response or error response in json 
     */
    public function getCustomerDetails($userNumber) {
        try {
            $stmt = $this->pdo->prepare("CALL getCustomerDetails(?)");
            $stmt->execute(array($userNumber));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $objectCustomer = new stdClass();
            foreach ($result as $row) {
                $objectCustomer = $row;
            }
            if ($result) {
                return $objectCustomer;
            } else {
                throw new Exception("Invalid User");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Generate bar code
     * @param array $requestedParams
     * @throws Exception  
     * @return json
     */
    public function generateBarCode($params = []) {
        try {
            // set Barcode39 object 
            $bc = new \Barcode39($params['barcode_url']);
            // set text size 
            $bc->barcode_text_size = 50;
            // set barcode bar thickness (thick bars) 
            $bc->barcode_bar_thick = 1;
            // set barcode bar thickness (thin bars) 
            $bc->barcode_bar_thin = 2;

            $bc->barcode_height = 120;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . "/barcode/" . $params['customer_id'] . "_" . $params['barcode_url'] . ".jpeg";
            //echo $filePath;die;
            // save barcode GIF file 
            $bc->draw($filePath);
            $bucket = getenv("BUCKET_NAME");
            $keyname = getenv("KEY");
            // Instantiate the client.
            $s3 = S3Client::factory(array(
                    'version' => getenv("VERSION"),
                    'region' => getenv("REGION"),
                    'credentials' => array(
                        'key' => $keyname,
                        'secret' => getenv("SECRET"),
                    )
            ));
            //print_R($s3);die;
            try {
                // Upload data.
                $result = $s3->putObject(array(
                    'Bucket' => $bucket,
                    'Key' => "barcode/" . $params['merchant_id'] . "/" . $params['customer_id'] . "/" . $params['barcode_url'] . ".jpeg",
                    'SourceFile' => $filePath,
                    'ACL' => 'public-read'
                ));
                // Print the URL to the object.
                //echo $result['ObjectURL'] . "\n";die;
            } catch (S3Exception $e) {
                throw new Exception($e->getMessage());
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Temporary function to delete customer and related merchant data
    public function testDeleteCustomerDetails($params = []) {
        try {
            $stmt = $this->pdo->prepare("CALL testDeleteCustomerDetails(?)");
            $stmt->execute(array($params['customer_id']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result['rowCount'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get pin code details
     * @param array $params
     * @throws Exception  
     * @return array of data
     */
    public function getPinCodeDetails($params = []) {
        try {
            $stmt = $this->pdo->prepare("CALL searchPincode(?, ?)");
            $stmt->execute(array($params['pin_code'], $params['isMasterPincodeRequired']));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * send feedback to inloyal/user
     * @param array $params
     * @throws Exception  
     * @return array
     */
    public function sendFeedback($params = []) {
        try {
            $stmt = $this->pdo->prepare("CALL getCustomerDetailsForFeedback(?, ?)");
            $stmt->execute(array($params['customer_id'], $params['merchant_id']));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            unset($stmt);
            if ($result) {
                $email = new Email($this->pdo, $this->redis);
                /* if (getenv("MAILGUN_TEST_MODE")) {
                  $emailVars['to'] = 'prashantb.iprogrammer@gmail.com';
                  } else {
                  $emailVars['to'] = $result[0]['feedback_email_id'];// this is copied to next line below
                  } */

                $emailVars['to'] = $result[0]['feedback_email_id'];

                $emailVars['fromName'] = $result[0]['first_name'] . ' ' . $result[0]['last_name'];
                $emailVars['fromEmail'] = $result[0]['email_address'];
                $emailVars['subject'] = $params['subject'];
                $message = '<b><u>Feedback from ' . $emailVars['fromName'] . ':</u></b><br/><br/><i>' . $params['feedback_message'] . '</i><br/><br/>'
                    . '<b><u>Customer Details:</u></b><br/><br/>'
                    . 'Name: ' . $result[0]['first_name'] . ' ' . $result[0]['last_name'] . '<br/>'
                    . 'Mobile: ' . $result[0]['mobile_number'] . '<br/>'
                    . 'Email: ' . $result[0]['email_address'] . '<br/><br/>'
                    . '<b>Device Info:</b><br/>' . $params['app_info'] . '<br/><br/>'
                    . 'The above feedback has been sent by the customer through the mobile cards wallet, <b>inloyal</b>.';
                $emailVars['message'] = $message;
                $emailVars['inloyalBaseLogo'] = getenv("INLOYAL_BASE_LOGO");
                $emailVars['iosLogo'] = getenv("EMAIL_IOS_LOGO");
                $emailVars['androidLogo'] = getenv("EMAIL_ANDROID_LOGO");
                $sendEmail = $email->sendEmail('IN_FEEDBACK', $emailVars);
            } else {
                return [];
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * check email verified or not
     * return success response or error response in json 
     * return id in data params
     */
    public function checkEmailVerified($customerId) {
        try {
            $stmt = $this->pdo->prepare("CALL checkEmailVerified(?)");
            $stmt->execute(array($customerId));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * return merchant details to send it in email
     * return 
     */
    public function getMerchantInfoForEmail($merchantId) {
        try {
            $stmt = $this->pdo->prepare("CALL getMerchantInfoForEmail(?)");
            $stmt->execute(array($merchantId));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * return merchant details to send it in email
     * return 
     */
    public function getCustomerNotifications($params) {
        try {
            if ($params['customer_id'] != '' && $params['merchant_id'] != '' && $params['loyalty_id'] != '' && $params['last_id'] != '') {
                $stmt = $this->pdo->prepare("CALL getCustomerNotifications(?,?,?,?)");
                $stmt->execute(array($params['customer_id'], $params['merchant_id'], $params['loyalty_id'], $params['last_id']));
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } else {
                throw new Exception('Please provide all the details.');
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*
     * Get merchant wise customer details
     */

    public function getCustomerDetailsMerchantWise($customerId, $merchantId) {
        try {
            $stmt = $this->pdo->prepare("CALL getCustomerDetailsForFeedback(?,?)");
            $stmt->execute(array($customerId, $merchantId));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                throw new Exception("Invalid User");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Insert customers into customer master from CSV with details firstname, lastname, mobile and email
     */
    public function insertCustomerFromCsv($mobile, $firstName, $lastName, $email, $emailVerifyKey) {
        try {
            //prepare statement for inserting the records start
            $this->pdo->beginTransaction();
            $createdDate = date('Y-m-d');
            $insertQuery = 'INSERT INTO customers (first_name, last_name, email_address, mobile_number, email_verified,	email_verify_key, mobile_verified, customer_city_id, pin_code, registered_from, registered_location, is_app_installed, status, created_by, updated_by, created_at) 
            VALUES ("' . $firstName . '", "' . $lastName . '", "' . $email . '", "' . $mobile . '", "0", "' . $emailVerifyKey . '", "1", 2707, 46269, "3", 0, "0", "1", "1", "1", "' . $createdDate . '");';
            $insertCustomer = $this->pdo->prepare($insertQuery); // Actual prepare statement
            //prepare statement for inserting the records end            
            $insertCustomer->execute(); //execute the prepare statement            
            $lastId = [];
            $lastId['customer_id'] = $this->pdo->lastInsertId();
            $result = $lastId;
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $result = 0;
        }
        return $result; //return the response
    }

}
