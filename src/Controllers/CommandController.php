<?php

namespace Api\Controllers;

//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;
use Api\Models\Command;
use PDO;

class CommandController extends ApiController {

    public function addMiningBonus() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            $platform = parent::PLATFORM;
            //array of required fields
            $requiredData = array('platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }
            $requestedParams["user_name"] = (isset($requestedParams["user_name"]) && !empty($requestedParams["user_name"])) ? $requestedParams["user_name"] : '';
            $commandObj = new Command($this->pdo);
            $result = $commandObj->addMiningBonus($requestedParams["user_name"]);
            if ($result) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success.');
            } else {
                $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'No data found to update bonus data.');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

}
