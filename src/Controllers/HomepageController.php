<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Api\Services\McryptCipher;
use stdClass;
//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;

class HomepageController extends ApiController {

    public function showTest() {
        $object = new stdClass();
        // allow a maximum of 100 requests for the IP in 5 minutes
        //$accessToken = $this->getOauthAccessToken();

        /* $c = new McryptCipher(getenv('ENCRYPTION_KEY'));
          $encrypted = $c->encryptDecrypt('7u8i9o0p','e');

          $decrypted = $c->encryptDecrypt($encrypted,'d');
          echo $encrypted.'---'.$decrypted; exit; */
        try {
            $requestedParams = $this->request->getParameters();
            $requiredData = array('password', 'platform');
            $this->validation($requestedParams, $requiredData);
            $c = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $encrypted = $c->encryptDecrypt($requestedParams['password'], 'e');

            $decrypted = $c->encryptDecrypt($encrypted, 'd');
            echo $encrypted . '---' . $decrypted;
            exit;
            $this->limitApiRequestsInMinutes(2, 1);
            $response = $this->validateOauthRequest();
            $oauthUser = $this->getOauthUser();
            $this->response->setContent(json_encode(array('success' => true, 'message' => 'You accessed my APIs!', 'token' => $oauthUser['user_id'])));

            $response = $this->getResponse('Success', parent::INVALID_PARAM_RESPONSE_CODE, $object, 'You accessed my APIs');

// send response in json format
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }
         return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function show() {
        $content = "<h1>Welcome " . getenv('API_NAME') . " API</h1>";

        try {
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $this->response->setContent(json_encode($content));
    }

}
