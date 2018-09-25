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
    public $tableName = 'rank'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $rank = array();
        $rank['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $rank['Rank'] = isset($params['Rank']) ? filter_var($params['Rank'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $rank['Rankid'] = isset($params['Rankid']) ? filter_var($params['Rankid'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $rank['Username'] = isset($params['Username']) ? filter_var($params['Username'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $rank['Sponsor'] = isset($params['Sponsor']) ? filter_var($params['Sponsor'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';

        $rank['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $rank['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $rank;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'Rank',
            'Rankid',
            'Username',
            'Sponsor',
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

    public function getRankDataByUserName($user_name) {
        try {
            //$stmt = $this->pdo->prepare("SELECT * FROM rank where Username =? order by id desc limit 1");
            $stmt = $this->pdo->prepare("CALL getUserRankData(?)");
            $stmt->execute([
                $user_name
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

    public function getSimpleRankDataByUserName($user_name) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM rank where Username =? order by id desc limit 1");
            $stmt->execute([
                $user_name
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
