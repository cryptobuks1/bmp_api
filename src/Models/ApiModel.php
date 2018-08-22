<?php


namespace Api\Models;

use PDO;
use Redis;

abstract class ApiModel {

    /**
     * Object of PDO
     * @var object 
     */
    protected $pdo;

    /**
     * Object of Redis
     * @var object 
     */
    protected $redis;

    /**
     * All column names of customers table as an array
     * @var array 
     */
    public $fields = [];

    const PDO_FETCH_ASSOC = PDO::FETCH_ASSOC;

    /**
     * Create a new Model instance.
     *
     * @return void
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->fields = $this->getAllTableFields(); //Get all table column names 
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract function getAllTableFields();
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
}
