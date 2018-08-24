<?php

/**
 * To Manupulate User table
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

class Rank extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'minipack'; //Initialize table name for model



    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */
    private function sanitizeAllData($params = []) {
        $minipack = array();
        $minipack['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $minipack['PurchaseDate'] = isset($params['PurchaseDate']) ? filter_var($params['PurchaseDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['MiningDate'] = isset($params['MiningDate']) ? filter_var($params['MiningDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['Username'] = isset($params['Username']) ? filter_var($params['Username'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['Status'] = isset($params['Status']) ? filter_var($params['Status'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['CompletionDate'] = isset($params['CompletionDate']) ? filter_var($params['CompletionDate'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['TotalMinable'] = isset($params['TotalMinable']) ? filter_var($params['TotalMinable'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['Withdrawal'] = isset($params['Withdrawal']) ? filter_var($params['Withdrawal'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $minipack['Comment'] = isset($params['Comment']) ? filter_var($params['Comment'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        
        $minipack['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $minipack['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $minipack;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'PurchaseDate',
            'MiningDate',
            'Username',
            'Status',
            'CompletionDate',
            'TotalMinable',
            'Withdrawal',
            'Comment',
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

}
