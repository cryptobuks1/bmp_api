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

class PaymentCallbackLog extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'payment_callback_log'; //Initialize table name for model



    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */
    private function sanitizeAllData($params = []) {
        $paymentCallbackLog = array();
        $paymentCallbackLog['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $paymentCallbackLog['username'] = isset($params['username']) ? filter_var($params['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $paymentCallbackLog['invoice_id'] = isset($params['invoice_id']) ? filter_var($params['invoice_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $paymentCallbackLog['amount_btc'] = isset($params['amount_btc']) ? filter_var($params['amount_btc'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $paymentCallbackLog['current_amount_btc'] = isset($params['current_amount_btc']) ? filter_var($params['current_amount_btc'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $paymentCallbackLog['amount_usd'] = isset($params['amount_usd']) ? filter_var($params['amount_usd'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $paymentCallbackLog['response'] = isset($params['response']) ? filter_var($params['response'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $paymentCallbackLog['status'] = isset($params['status']) ? (int) filter_var($params['status'], FILTER_SANITIZE_NUMBER_INT) : 1;
        $paymentCallbackLog['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
       
        return $paymentCallbackLog;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'username',
            'invoice_id',
            'amount_btc',
            'current_amount_btc',
            'amount_usd',
            'response',
            'status',
            'created_at'
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
