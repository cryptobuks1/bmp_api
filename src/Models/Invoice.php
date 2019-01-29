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
                $invoices = $this->sanitizeAllData($invoices); // Get all data sanitized
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
    public function sanitizeAllData($params = []) {
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
        $invoice['api_response'] = isset($params['api_response']) ? filter_var($params['api_response'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';

        $invoice['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $invoice['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

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
    public function isInvoicePresent($username, $purpose, $status = 'Unpaid') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("SELECT * FROM `invoice` WHERE Purpose=:Purpose AND Username=:Username AND Status=:Status order by id desc limit 1");
            $stmt->execute(['Purpose' => $purpose, 'Username' => $username, 'Status' => $status]);

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
     * check invoice is presented or not
     * return success response or error response in json 
     * return id in data params
     */
    public function getInvoiceByID($invoiceId) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("SELECT * FROM `invoice` WHERE Invoiceid=:Invoiceid order by id desc limit 1");
            $stmt->execute(['Invoiceid' => $invoiceId]);
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

    public function getAllInvoiceDBTransactions($user_name = '') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            if ($user_name == '') {
                //$stmt = $this->pdo->prepare("SELECT * FROM `invoice` order by id desc ");
                $stmt = $this->pdo->prepare("SELECT i.id,i.Paydate,i.Invoiceid,i.Purpose,i.Btcaddress,i.Amount,i.Btcamount,(CASE pcl.status WHEN '1' THEN 'Unpaid' WHEN '2' THEN 'Paid' ELSE 'N/A' END ) AS Status,pcl.response,i.status,i.created_at,i.Username, IFNULL(DATE_FORMAT(DATE_ADD(i.created_at, INTERVAL 365 DAY),'%d-%m-%Y'),'N/A') as expiry_date,pcl.id as log_id FROM invoice AS i LEFT JOIN payment_callback_log AS pcl ON pcl.invoice_id=i.Invoiceid  having pcl.id = (select id from payment_callback_log where invoice_id = i.Invoiceid  order by id desc limit 1 ) order by log_id desc ");
                $stmt->execute();
            } else {
                $stmt = $this->pdo->prepare("SELECT i.id,i.Paydate,i.Invoiceid,i.Purpose,i.Btcaddress,i.Amount,i.Btcamount,(CASE pcl.status WHEN '1' THEN 'Unpaid' WHEN '2' THEN 'Paid' ELSE 'N/A' END ) AS Status,pcl.response,i.status,i.created_at,i.Username, IFNULL(DATE_FORMAT(DATE_ADD(i.created_at, INTERVAL 365 DAY),'%d-%m-%Y'),'N/A') as expiry_date,pcl.id as log_id FROM invoice AS i LEFT JOIN payment_callback_log AS pcl ON pcl.invoice_id=i.Invoiceid  having pcl.id = (select id from payment_callback_log where invoice_id = i.Invoiceid  order by id desc limit 1 ) AND i.Username=? order by log_id desc ");
             
                //$stmt = $this->pdo->prepare("SELECT * FROM `invoice` where Username = ? order by id desc ");
                $stmt->execute([$user_name]);
            }

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}
