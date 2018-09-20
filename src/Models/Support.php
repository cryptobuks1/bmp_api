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

class Support extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'support'; //Initialize table name for model



    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */
    public function sanitizeAllData($params = []) {
        $support = array();
        $support['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $support['ticket_id'] = isset($params['ticket_id']) ? filter_var($params['ticket_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $support['date'] = isset($params['date']) ? date('Y-m-d', strtotime(filter_var($params['date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d');
        $support['user_name'] = isset($params['user_name']) ? filter_var($params['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $support['issue'] = isset($params['issue']) ? filter_var($params['issue'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $support['status'] = isset($params['status']) ? filter_var($params['status'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '1';
        $support['category'] = isset($params['category']) ? filter_var($params['category'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '4';
        $support['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $support['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $support;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'ticket_id',
            'date',
            'user_name',
            'issue',
            'status',
            'category',
            'created_at',
            'updated_at'
        ];
    }

    public function getAllSupportTicketByUserName($user_name = '') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            if ($user_name == '') {
                $stmt = $this->pdo->prepare("SELECT id,ticket_id,date,user_name,issue,(CASE WHEN status = 1 THEN 'Pending' WHEN status = 2 THEN 'Processed' WHEN status = 3 THEN 'Rejected' ELSE '' END) AS status_view,status,(CASE WHEN category = 1 THEN 'Registration' WHEN category = 2 THEN 'Account Activation' WHEN category = 3 THEN 'Payment' WHEN category = 4 THEN 'Others' ELSE '' END) AS category_view,category,created_at FROM support order by id desc ");
                $stmt->execute();
            } else {

                $stmt = $this->pdo->prepare("SELECT id,ticket_id,date,user_name,issue,(CASE WHEN status = 1 THEN 'Pending' WHEN status = 2 THEN 'Processed' WHEN status = 3 THEN 'Rejected' ELSE '' END) AS status_view,status,(CASE WHEN category = 1 THEN 'Registration' WHEN category = 2 THEN 'Account Activation' WHEN category = 3 THEN 'Payment' WHEN category = 4 THEN 'Others' ELSE '' END) AS category_view,category,created_at FROM support where user_name = ? order by id desc ");
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
