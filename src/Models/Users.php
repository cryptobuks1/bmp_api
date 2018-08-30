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

class Users extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'users'; //Initialize table name for model

    /**
     * Sanitize the values of $params array and return
     * @param type $params
     * @return array
     */

    public function sanitizeAllData($params = []) {
        $users = array();
        $users['id'] = isset($params['id']) ? (int) filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        $users['Fullname'] = isset($params['Fullname']) ? filter_var($params['Fullname'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Country'] = isset($params['Country']) ? filter_var($params['Country'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Email'] = isset($params['Email']) ? filter_var($params['Email'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Telephone'] = isset($params['Telephone']) ? filter_var($params['Telephone'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Gender'] = isset($params['Gender']) ? filter_var($params['Gender'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Username'] = isset($params['Username']) ? filter_var($params['Username'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Password'] = isset($params['Password']) ? filter_var($params['Password'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Status'] = isset($params['Status']) ? filter_var($params['Status'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Sponsor'] = isset($params['Sponsor']) ? filter_var($params['Sponsor'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Token'] = isset($params['Token']) ? filter_var($params['Token'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['Account'] = isset($params['Account']) ? filter_var($params['Account'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';
        $users['treestatus'] = isset($params['treestatus']) ? filter_var($params['treestatus'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : '';

        $users['created_at'] = isset($params['created_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['created_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');
        $users['updated_at'] = isset($params['updated_at']) ? date('Y-m-d H:i:s', strtotime(filter_var($params['updated_at'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES))) : date('Y-m-d H:i:s');

        return $users;
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id',
            'Fullname',
            'Country',
            'Email',
            'Telephone',
            'Gender',
            'Username',
            'Password',
            'Username',
            'Status',
            'Sponsor',
            'Token',
            'Account',
            'treestatus',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * check invoice is presented or not
     * return success response or error response in json 
     * return id in data params
     */
    public function checkLoginResponse($params) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");

            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE  Username=:user_name AND Password=:password order by id desc limit 1");
            $stmt->execute(['user_name' => $params['user_name'], 'password' => $params['password']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getUserDetailsByUserName($user_name, $token = 0) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            if ($token == 0) {
                $stmt = $this->pdo->prepare("SELECT u.*,bw.address,bw.password,bw.guid FROM `users` AS u LEFT JOIN bmp_wallet AS bw ON bw.user_name=u.Username WHERE  u.Username=:user_name AND bw.user_name =:user_name order by id desc limit 1");
                $stmt->execute(['user_name' => $user_name]);
            } else {
                $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE  Username=:user_name AND Token:token order by id desc limit 1");
                $stmt->execute(['user_name' => $user_name, 'token' => $token]);
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function createUser($params) {

        try {
            $sponsor_account = (isset($params['sponsor_account']))?$params['sponsor_account']:'';
            $sponsorResponse = $this->getSponsorNameByAccount($sponsor_account);
            $sponsor = $sponsorResponse['Username'];
            $stmt = $this->pdo->prepare("CALL insertCustomer(?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $params['name'],
                $params['country'],
                $params['email'],
                $params['telephone'],
                $params['gender'],
                $params['user_name'],
                $params['password'],
                $params['token'],
                $params['account'],
                $sponsor,
                $params['status'],
                $params['activation'],
                $params['platform']
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getSponsorNameByAccount($account = '24rgxpwex1b4ko88owko') {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("SELECT Username FROM users where Account =:account order by id desc limit 1");
            $stmt->execute(['account' => $account]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if ($result) {
                return $result;
            } else {
                return 'mshai';
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
    public function activateUserCode($paramas) {
        try {
            // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt = $this->pdo->prepare("UPDATE users set Activation=:activation,Token=:token WHERE Username=:user_name limit 1;");
           $result = $stmt->execute(['activation' => $paramas['activation'],'token' => $paramas['token'],'user_name' => $paramas['user_name']]);
            $stmt->closeCursor();
            if ($result) {
                return $result;
            } else {
                return 'mshai';
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
}
