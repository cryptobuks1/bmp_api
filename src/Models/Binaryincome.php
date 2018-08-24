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

class Binaryincome extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'binaryincome'; //Initialize table name for model



    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */
    private function sanitizeAllData($params = []) {
        $binaryincome = array();
        $binaryincome['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $binaryincome['userid'] = isset($params['userid']) ? filter_var($params['userid'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $binaryincome['day_bal'] = isset($params['day_bal']) ? filter_var($params['day_bal'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $binaryincome['current_bal'] = isset($params['current_bal']) ? filter_var($params['current_bal'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $binaryincome['total_bal'] = isset($params['total_bal']) ? filter_var($params['total_bal'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $binaryincome['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $binaryincome['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $binaryincome;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'userid',
            'day_bal',
            'current_bal',
            'total_bal',
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
