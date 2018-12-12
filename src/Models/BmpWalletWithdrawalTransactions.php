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

class BmpWalletWithdrawalTransactions extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'bmp_wallet_withdrawl_transactions'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $bmpWalletWithdrawlTransactions = array();
        $bmpWalletWithdrawlTransactions['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $bmpWalletWithdrawlTransactions['user_name'] = isset($params['user_name']) ? filter_var($params['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletWithdrawlTransactions['amount'] = isset($params['amount']) ? filter_var($params['amount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletWithdrawlTransactions['to_address'] = isset($params['to_address']) ? filter_var($params['to_address'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletWithdrawlTransactions['response'] = isset($params['response']) ? filter_var($params['response'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWalletWithdrawlTransactions['status'] = isset($params['status']) ? (int) filter_var($params['status'], FILTER_SANITIZE_NUMBER_INT) : 1;

        $bmpWalletWithdrawlTransactions['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $bmpWalletWithdrawlTransactions['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $bmpWalletWithdrawlTransactions;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'user_name',
            'amount',
            'to_address',
            'response',
            'status',
            'created_at',
            'updated_at'
        ];
    }

    public function getAllWalletWithdrawalDBTransactions($user_name = '') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            if ($user_name == '') {
                $stmt = $this->pdo->prepare("SELECT id,user_name,response,(CASE WHEN status = 1 THEN 'Pending' WHEN status = 2 THEN 'Processed' WHEN status = 3 THEN 'Rejected' ELSE '' END) AS status_view,amount,status,to_address,created_at FROM bmp_wallet_withdrawl_transactions order by id desc ");
                $stmt->execute();
            } else {

                $stmt = $this->pdo->prepare("SELECT id,user_name,response,(CASE WHEN status = 1 THEN 'Pending' WHEN status = 2 THEN 'Processed' WHEN status = 3 THEN 'Rejected' ELSE '' END) AS status_view,amount,status,to_address,created_at FROM bmp_wallet_withdrawl_transactions where user_name = ? order by id desc ");
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

    public function getWithdrawalTransactionByID($transaction_id) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("SELECT id,user_name,response,(CASE WHEN status = 1 THEN 'Pending' WHEN status = 2 THEN 'Processed' WHEN status = 3 THEN 'Rejected' ELSE '' END) AS status_view,amount,status,to_address,created_at FROM bmp_wallet_withdrawl_transactions where id = ? order by id desc ");
            $stmt->execute([$transaction_id]);

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

    public function updateWithdrawalTransaction($params) {
        try {
            $stmt = $this->pdo->prepare("CALL processWithDrawalTransaction(?,?,?)");
            $stmt->execute([$params['user_name'], $params['status'], $params['transaction_id']]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}
