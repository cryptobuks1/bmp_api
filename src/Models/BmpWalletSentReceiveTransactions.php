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

class BmpWalletSentReceiveTransactions extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'bmp_wallet_sent_receive_transactions'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $bmpWalletSentReceiveTransactions = array();
        $bmpWalletSentReceiveTransactions['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $bmpWalletSentReceiveTransactions['user_name'] = isset($params['user_name']) ? filter_var($params['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletSentReceiveTransactions['invoice_id'] = isset($params['invoice_id']) ? filter_var($params['invoice_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletSentReceiveTransactions['sent_receive_flag'] = isset($params['sent_receive_flag']) ? (int) filter_var($params['sent_receive_flag'], FILTER_SANITIZE_NUMBER_INT) : 1;
        $bmpWalletSentReceiveTransactions['amount'] = isset($params['amount']) ? filter_var($params['amount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletSentReceiveTransactions['from_address'] = isset($params['from_address']) ? filter_var($params['from_address'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletSentReceiveTransactions['to_address'] = isset($params['to_address']) ? filter_var($params['to_address'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletSentReceiveTransactions['response'] = isset($params['response']) ? filter_var($params['response'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletSentReceiveTransactions['status'] = isset($params['status']) ? (int) filter_var($params['status'], FILTER_SANITIZE_NUMBER_INT) : 1;

        $bmpWalletSentReceiveTransactions['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $bmpWalletSentReceiveTransactions['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $bmpWalletSentReceiveTransactions;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'user_name',
            'invoice_id',
            'amount',
            'sent_receive_flag',
            'from_address',
            'to_address',
            'response',
            'status',
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
            $stmt->execute(['Purpose' => $purpose, 'Username' => $username]);
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

    public function getAllWalletDBTransactions($user_name = '', $sent_receive_flag = 0) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            if ($user_name == '') {
                if ($sent_receive_flag == 0) {
                    $stmt = $this->pdo->prepare("SELECT id,user_name,response,invoice_id,(CASE WHEN sent_receive_flag = 1 THEN 'Sent' ELSE 'Receive' END) AS sent_receive_flag,amount,from_address,to_address,(CASE WHEN status = 1 THEN 'Pending' ELSE 'Paid' END) AS status,created_at FROM bmp_wallet_sent_receive_transactions order by id desc ");
                    $stmt->execute();
                } else {
                    $stmt = $this->pdo->prepare("SELECT id,user_name,response,invoice_id,(CASE WHEN sent_receive_flag = 1 THEN 'Sent' ELSE 'Receive' END) AS sent_receive_flag,amount,from_address,to_address,(CASE WHEN status = 1 THEN 'Pending' ELSE 'Paid' END) AS status,created_at FROM bmp_wallet_sent_receive_transactions where sent_receive_flag = ? order by id desc ");
                    $stmt->execute([$sent_receive_flag]);
                }
            } else {
                if ($sent_receive_flag == 0) {
                    $stmt = $this->pdo->prepare("SELECT id,invoice_id,(CASE WHEN sent_receive_flag = 1 THEN 'Sent' ELSE 'Receive' END) AS sent_receive_flag,amount,from_address,to_address,(CASE WHEN status = 1 THEN 'Pending' ELSE 'Paid' END) AS status,created_at FROM bmp_wallet_sent_receive_transactions where user_name = ? order by id desc ");
                    $stmt->execute([$user_name]);
                } else {
                    $stmt = $this->pdo->prepare("SELECT id,invoice_id,(CASE WHEN sent_receive_flag = 1 THEN 'Sent' ELSE 'Receive' END) AS sent_receive_flag,amount,from_address,to_address,(CASE WHEN status = 1 THEN 'Pending' ELSE 'Paid' END) AS status,created_at FROM bmp_wallet_sent_receive_transactions where user_name = ? AND sent_receive_flag = ? order by id desc ");
                    $stmt->execute([$user_name, $sent_receive_flag]);
                }
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
