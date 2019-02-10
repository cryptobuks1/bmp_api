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

class Command extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = ''; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        return [];
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return [];
    }

    /**
     * check invoice is presented or not
     * return success response or error response in json 
     * return id in data params
     */
    public function addMiningBonus($user_name = '') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("CALL addMiningBonus(?)");
            $stmt->execute([
                $user_name
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
	
	   /**
     * check expired invoice is presented or not
     * return success response or error response in json 
     * return id in data params
     */
    public function checkAndProcessExpiredInvoice($user_name = '',$invoice_id = '',$is_renewal = 0) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("CALL checkAndProcessExpiredInvoice(?,?,?)");
            $stmt->execute([
                $user_name,
				$invoice_id,
				$is_renewal
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
