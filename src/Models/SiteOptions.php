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

class SiteOptions extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'site_options'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $site_options = array();
        $site_options['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $site_options['option_name'] = isset($params['option_name']) ? filter_var($params['option_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $site_options['option_value'] = isset($params['option_value']) ? filter_var($params['option_value'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $site_options['option_description'] = isset($params['option_description']) ? filter_var($params['option_description'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $site_options['status'] = isset($params['status']) ? filter_var($params['status'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '1';
        $site_options['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $site_options['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $site_options;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'option_name',
            'option_value',
            'option_description',
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
    public function getOptionValueByName($optionName) {
        try {
            $stmt = $this->pdo->prepare("SELECT option_name,option_value,option_description FROM site_options where option_name = :optionName order by id desc limit 1");
            $stmt->execute(['optionName' => $optionName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return '';
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateOptionByName($param) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("UPDATE site_options SET option_value = :option_value,option_description = :option_description,updated_at=now() WHERE option_name=:option_name");
            $result = $stmt->execute(['option_value'=>$param['option_value'],'option_name'=>$param['option_name'],'option_description'=>$param['option_description']]);
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
