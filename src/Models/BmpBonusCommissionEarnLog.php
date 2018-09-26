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

class BmpBonusCommissionEarnLog extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'bmp_bonus_commission_earn_log'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $bmpBonusCommissionEarnLog = array();
        $bmpBonusCommissionEarnLog['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $bmpBonusCommissionEarnLog['user_name'] = isset($params['user_name']) ? filter_var($params['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpBonusCommissionEarnLog['reason_id'] = isset($params['reason_id']) ? filter_var($params['reason_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '0';
        $bmpBonusCommissionEarnLog['reason_description'] = isset($params['reason_description']) ? filter_var($params['reason_description'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpBonusCommissionEarnLog['is_added_by_cron'] = isset($params['is_added_by_cron']) ? filter_var($params['is_added_by_cron'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '0';
        $bmpBonusCommissionEarnLog['amount'] = isset($params['amount']) ? filter_var($params['amount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $bmpBonusCommissionEarnLog['added_in'] = isset($params['added_in']) ? filter_var($params['added_in'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';

        $bmpBonusCommissionEarnLog['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $bmpBonusCommissionEarnLog['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $bmpBonusCommissionEarnLog;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'user_name',
            'reason_id',
            'reason_description',
            'is_added_by_cron',
            'amount',
            'added_in',
            'created_at',
            'updated_at'
        ];
    }

    public function getAllCommissionBonus($user_name = '') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            if ($user_name == '') {
                $stmt = $this->pdo->prepare("SELECT id,user_name,reason_description,amount,added_in,(CASE WHEN reason_id = '1' THEN 'Direct Commission' WHEN reason_id = '2' THEN 'Indirect Binary Commision' WHEN reason_id = '3' THEN 'Matching Bonus' WHEN reason_id = '4' THEN 'Residual Bonus' WHEN reason_id = '5' THEN 'Mining Earning' ELSE '' END) AS reason_id_view,reason_id,(CASE WHEN is_added_by_cron = 1 THEN 'Yes' WHEN is_added_by_cron = 0 THEN 'No' ELSE '' END) AS is_added_by_cron_view,is_added_by_cron,created_at FROM bmp_bonus_commission_earn_log order by id desc ");
                $stmt->execute();
            } else {

                $stmt = $this->pdo->prepare("SELECT id,user_name,reason_description,amount,added_in,(CASE WHEN reason_id = '1' THEN 'Direct Commission' WHEN reason_id = '2' THEN 'Indirect Binary Commision' WHEN reason_id = '3' THEN 'Matching Bonus' WHEN reason_id = '4' THEN 'Residual Bonus' WHEN reason_id = '5' THEN 'Mining Earning' ELSE '' END) AS reason_id_view,reason_id,(CASE WHEN is_added_by_cron = 1 THEN 'Yes' WHEN is_added_by_cron = 0 THEN 'No' ELSE '' END) AS is_added_by_cron_view,is_added_by_cron,created_at FROM bmp_bonus_commission_earn_log where user_name = ? order by id desc ");
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
