<?php

namespace Api\Models;

use Api\Models\ApiModel;
use Api\Services\Oauth2\Storage\UserGrantInterface;
use PDO;

class OauthUserStorage extends ApiModel implements UserGrantInterface
{

    /**
     * table name used by model
     * @var string
     */
    public $tableName = 'customers'; //Initialize table name for model

    public function checkUserCredentials($mobileNumber)
    {
        if ($user = $this->getUser($mobileNumber)) {
            return true;
        }
        return false;
    }

    public function getUserDetails($username)
    {
        return $this->getUser($username);
    }

    public function getUser($mobile)
    {
        try {
            $response = $this->redis->get('oauth_user_' . $mobile);
            if (!$response) {
                //$stmt = $this->pdo->prepare($sql = sprintf('SELECT * from %s where mobile_number=:mobile AND status=:status', $this->tableName));
                //$stmt->execute(array('mobile' => $mobile,'status' => 1));
                $stmt = $this->pdo->prepare($sql = sprintf('SELECT * from %s where mobile_number=:mobile', $this->tableName));
                $stmt->execute(array('mobile' => $mobile));
                if (!$userInfo = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    return false;
                }
                $response = array_merge(array(
                    'user_id' => $mobile,
                    ), $userInfo);

                $this->redis->setex('oauth_user_' . $mobile, 3600, json_encode($response));
            } else {
                $response = json_decode($response, true);
            }
            return $response;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields()
    {
        return[
            'id',
            'old_customer_ids',
            'first_name',
            'last_name',
            'email_address',
            'mobile_number',
            'facebook_social_id',
            'twitter_social_id',
            'google_plus_social_id',
            'email_verified',
            'email_verify_key',
            'mobile_verified',
            'date_of_birth',
            'gender',
            'marital_status',
            'anniversary_date',
            'spouse_dob',
            'customer_city_id',
            'pin_code',
            'registered_from',
            'registered_location',
            'status',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at'
        ];
    }
}
