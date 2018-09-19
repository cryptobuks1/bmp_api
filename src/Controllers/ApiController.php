<?php

namespace Api\Controllers;

use Http\Response;
use Http\Request;
use PDO;
use OAuth2\Server as OAuthServer;
use Api\Models\OauthMobileStorage;
use Api\Models\OauthUserStorage;
use Api\Services\Oauth2\Grant\MobileGrant;
use Api\Services\Oauth2\Grant\UserGrant;
use Api\Services\RateLimiter;
use Api\Services\Blockchain;
use Exception;

abstract class ApiController {

    /**
     * All the required libraries will be instantiated in these variables
     * @var object
     */
    protected $request;
    protected $response;
    protected $pdo;
    protected $redis;
    protected $oauthServer;
    protected $blockchain;

    /**
     * Constants for error codes
     * @var const
     */
    const SUCCESS_RESPONSE_CODE = 100;
    const AUTH_RESPONSE_CODE = 101;
    const INVALID_PARAM_RESPONSE_CODE = 102;
    const NO_DATA_FOUND = 103;
    const RECORD_ALREADY_EXISTS = 104;
    const UNAUTH_RESPONSE_CODE = 401;
    const TOO_MANY_REQUEST_RESPONSE_CODE = 529;
    const REFERRAL_COUPON_VERIFICATION_RESPONSE_SUCCESS_CODE = 301;
    const REFERRAL_COUPON_VERIFICATION_RESPONSE_FAILURE_CODE = 302;
    const INVENTORY_RESTRICTION_FAILURE_RESPONSE_CODE = 108;
    //SMS transaction type
    const SMS_MSG_TEMPLATES = [201 => "IN_LOGIN_OTP", 202 => "IN_MERCHANT_ADD", 203 => "IN_NUMBER_CHANGED_OTP", 204 => "LP_APP_CHANGE_NUMBER_OTP", 205 => "LP_KIOSK_CHANGE_NUMBER_OTP", 206 => "LP_KIOSK_REDEMPTION_OTP", 207 => "LP_KIOSK_PLACE_ORDER_OTP", 208 => "LP_REFUND_POINTS", 209 => "LP_REGISTRATION_OTP"];
    const PLATFORM = array("1" => "Android", "2" => "iOS", "3" => "Web");
    const POOLDATA = array("Starter" => array('price' => '300','tittle'=>'Pool 1','dbtable'=>'starterpack'),
        "Mini" => array('price' => '600','tittle'=>'Pool 2','dbtable'=>'minipack'),
        "Medium" => array('price' => '1200','tittle'=>'Pool 3','dbtable'=>'mediumpack'),
        "Grand" => array('price' => '2400','tittle'=>'Pool 4','dbtable'=>'grandpack'),
        "Ultimate" => array('price' => '4800','tittle'=>'Pool 5','dbtable'=>'ultimatepack')
    );
    # define platform for transaction type
    const TRANSACTION_TYPE = array("201" => "Customer Login", "202" => "Registration", "203" => "Pool Registration", "204" => "Email verification", "205" => "Wallet Creation", "206" => "Wallet Balance Fetch", "207" => "Wallet Send Money","208" => "Wallet Receive Money","301" => "Membership Invoice Creation", "302" => "Pool Purchase Invoice Creation","401"=>"Withdrawl Request");
    const GRANT_TYPE = array("client_credentials" => "client_credentials");

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Response $response, PDO $pdo, OAuthServer $oauthServer, Blockchain $blockchain) {
        $this->request = $request;
        $this->response = $response;
        $this->pdo = $pdo;
        $this->blockchain = new $blockchain(getenv('API_CODE'));
        $this->blockchain->setServiceUrl(getenv('SERVICE_URL'));
        $this->oauthServer = $oauthServer;
    }

    /**
     * Common function for response
     * @param string $status
     * @param int $statusCode
     * @param array $response
     * @param string $statusDescription
     * @return array
     */
    public function getResponse($status, $statusCode, $response, $statusDescription) {
        return[
            'status' => $status,
            'statusCode' => $statusCode,
            'response' => $response,
            'statusDescription' => $statusDescription
        ];
    }

    /**
     * Common function for validating Oauth Request for Geting Access Token
     * @return array
     */
    public function validateOauthRequest() {
        $this->oauthStorage = new \OAuth2\Storage\Pdo($this->pdo, ['user_table' => 'customers']);
        $this->oauthServer->addStorage($this->oauthStorage);
        // Handle a request to a resource and authenticate the access token
        if (!$this->oauthServer->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            $errorParams = $this->oauthServer->getResponse()->getParameters();
            $this->oauthServer->getResponse()->addParameters([
                'status' => isSet($errorParams['error']) ? $errorParams['error'] : 'Failure',
                'statusCode' => self::UNAUTH_RESPONSE_CODE,
                'response' => null,
                'statusDescription' => isSet($errorParams['error_description']) ? $errorParams['error_description'] : 'Something went Wrong.'
            ]);
            $this->oauthServer->getResponse()->send();
            die;
        } else {
            return true;
        }
    }

    /**
     * Common function for retrive Oauth Access Token
     * @return array
     */
    public function getOauthAccessToken() {
        try {
            $requestGlobal = \OAuth2\Request::createFromGlobals();
            //echo "<pre>";print_r($this->oauthServer);exit;
            // return $this->oauthServer;

            if (isset($requestGlobal->request['grant_type']) && $requestGlobal->request['grant_type'] == "client_credentials") {
                $this->oauthServer->setConfig('access_lifetime', 3600 * 24 * 30); // 30 day
            } else {
                $this->oauthServer->setConfig('access_lifetime', 86400); // 1 day
            }

            $this->oauthServer->setConfig('refresh_token_lifetime', 2419200); // 28 days

            $this->oauthStorage = new \OAuth2\Storage\Pdo($this->pdo, ['user_table' => 'Users']);
            $this->oauthServer->addStorage($this->oauthStorage);
            $oauthMobileStorage = new OauthMobileStorage($this->pdo, $this->redis);
            $this->oauthServer->addGrantType(new MobileGrant($oauthMobileStorage));

            $oauthUserStorage = new OauthUserStorage($this->pdo, $this->redis);
            $this->oauthServer->addGrantType(new UserGrant($oauthUserStorage));

            $this->oauthServer->addGrantType(new \OAuth2\GrantType\RefreshToken($this->oauthServer->getStorage('refresh_token'), ['always_issue_new_refresh_token' => true]));
            $this->oauthServer->addGrantType(new \OAuth2\GrantType\ClientCredentials($this->oauthStorage));

            return $accessToken = $this->oauthServer->grantAccessToken($requestGlobal);
        } catch (Exception $e) {
            throw new Exception("Please enter valid mobile_number." . $e->getMessage());
        }
    }

    /**
     * Common function for retrive Oauth Access Token specific User
     * @return array
     */
    public function getOauthUser() {
        $this->oauthStorage = new \OAuth2\Storage\Pdo($this->pdo, ['user_table' => 'customers']);
        $this->oauthServer->addStorage($this->oauthStorage);

        $token = $this->oauthServer->getAccessTokenData(\OAuth2\Request::createFromGlobals());
        // Handle a request to a resource and authenticate the access token
        if (empty($token)) {
            $errorParams = $this->oauthServer->getResponse()->getParameters();
            $this->oauthServer->getResponse()->addParameters([
                'status' => $errorParams['error'],
                'statusCode' => self::UNAUTH_RESPONSE_CODE,
                'response' => null,
                'statusDescription' => $errorParams['error_description']
            ]);
            $this->oauthServer->getResponse()->send();
            die;
        } else {
            return $token;
        }
    }

    /**
     * Common function for Rate limit access of API call
     * @return json format fot eception
     */
    public function limitApiRequestsInMinutes($noOfRequest, $minutes, $prefixScript = null) {
        try {
            if (!empty($prefixScript)) {
                $rateLimiter = new RateLimiter($this->redis, $_SERVER["REMOTE_ADDR"], $prefixScript);
            } else {
                $rateLimiter = new RateLimiter($this->redis, $_SERVER["REMOTE_ADDR"]);
            }

            // allow a maximum of 100 requests for the IP in 5 minutes
            $rateLimiter->limitRequestsInMinutes($noOfRequest, $minutes);
        } catch (Exception $e) {
            $response = new \OAuth2\Response();
            $response->addParameters([
                'status' => 'Too Many Requests',
                'statusCode' => self::TOO_MANY_REQUEST_RESPONSE_CODE,
                'response' => null,
                'statusDescription' => 'Too Many Requests'
            ]);
            $response->setStatusCode(self::TOO_MANY_REQUEST_RESPONSE_CODE, 'Too Many Requests');
            $response->send();
            die();
        }
    }

    /**
     * return validation error message or null
     * @throws Exception
     * @param array $requestedParams, $requiredData
     * @return json string
     */
    public function validation($requestedParams, $requiredData) {
        $missingInput = array(); //array to contain error
        $error = "";
        $intCount = 0;
        foreach ($requestedParams as $key => $value) {
            if (!in_array($key, $requiredData)) {
                $error .= $key . ", ";
            } else {
                $key = array_search($key, $requiredData);
                if ($key !== false)
                    unset($requiredData[$key]);
            }
            $intCount++;
        }
        if (count($requiredData) !== 0) {
            $missingInput[] = 'Parameter missing : ' . implode(',', $requiredData);
        }

        if (!empty($missingInput)) {
            throw new Exception(implode(", ", $missingInput));
        }
    }

}
