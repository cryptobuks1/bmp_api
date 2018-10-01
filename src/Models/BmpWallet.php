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

class BmpWallet extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'bmp_wallet'; //Initialize table name for model



    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */
    public function sanitizeAllData($params = []) {
        $bmpWallet = array();
        $bmpWallet['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $bmpWallet['user_name'] = isset($params['user_name']) ? filter_var($params['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWallet['email_address'] = isset($params['email_address']) ? filter_var($params['email_address'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWallet['password'] =  isset($params['password']) ? filter_var($params['password'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWallet['label'] = isset($params['label']) ? filter_var($params['label'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWallet['guid'] = isset($params['guid']) ? filter_var($params['guid'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWallet['address'] = isset($params['address']) ? filter_var($params['address'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpWallet['status'] = isset($params['status']) ? (int) filter_var($params['status'], FILTER_SANITIZE_NUMBER_INT) : 1;
        $bmpWallet['is_register_for_bmp'] = isset($params['is_register_for_bmp']) ? (int) filter_var($params['is_register_for_bmp'], FILTER_SANITIZE_NUMBER_INT) : 2;
        
        $bmpWallet['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $bmpWallet['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
       
        return $bmpWallet;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'user_name',
            'email_address',
            'password',
            'label',
            'address',
            'guid',
            'status',
            'is_register_for_bmp',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * check invoice is presented or not
     * return success response or error response in json 
     * return id in data params
     */
        public function checkForWalletexist($params) {
        try {
           // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
                $stmt = $this->pdo->prepare("SELECT * FROM `bmp_wallet` WHERE user_name=:user_name AND email_address=:user_name  order by id desc limit 1");
                $stmt->execute(['user_name' => $params['user_name'],'email_address'=>$params['email_address']]);
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
