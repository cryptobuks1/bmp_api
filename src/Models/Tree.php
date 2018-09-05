<?php

/**
 * To Manupulate Users table
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

class Tree extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'tree'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $tree = array();
        $tree['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $tree['userid'] = isset($params['userid']) ? filter_var($params['userid'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['left'] = isset($params['left']) ? filter_var($params['left'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['right'] = isset($params['right']) ? filter_var($params['right'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['leftcount'] = isset($params['leftcount']) ? filter_var($params['leftcount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['rightcount'] = isset($params['rightcount']) ? filter_var($params['rightcount'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['leftcredits'] = isset($params['leftcredits']) ? filter_var($params['leftcredits'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['rightcredits'] = isset($params['rightcredits']) ? filter_var($params['rightcredits'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $tree['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $tree['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $tree;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'userid',
            'left',
            'right',
            'leftcount',
            'rightcount',
            'leftcredits',
            'rightcredits',
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

    public function addChildNode($params) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("CALL insertTree(?,?,?)");
            $stmt->execute([
                $params['parent_user'],
                $params['side'],
                $params['user_name']
            ]);

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
