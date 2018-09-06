<?php

namespace Api\Controllers;

//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;
use Api\Models\BmpWallet;
use Api\Models\Tree;
use PDO;

class TreeController extends ApiController {

    /**
     * 
     */
    public function joinTree() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;
            //array of required fields
            $requiredData = array('parent_user', 'side', 'user_name', 'platform', 'transaction_type',);
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            $transactionTypeKey = array_keys($transactionType);
            if (isset($requestedParams["transaction_type"]) && !in_array($requestedParams["transaction_type"], $transactionTypeKey)) {
                throw new Exception("Please enter valid transaction type.");
            }

            if (empty($requestedParams["parent_user"]) || empty($requestedParams["side"]) || empty($requestedParams["user_name"])) {
                throw new Exception("Please enter valid team details.");
            }

            $tree = new Tree($this->pdo);
            $result = $tree->addChildNode($requestedParams);
            if ($result) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Tree data added succesfully.');
            } else {
                $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'There is problem to create user.');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

}
