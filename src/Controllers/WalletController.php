<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;

class WalletController extends ApiController {

    /**
     * 
     */
    public function getWalletBalance() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result['balance'] = $this->blockchain->Wallet->getBalance();
            $result['identifier'] = $this->blockchain->Wallet->getIdentifier();
            if (!$result['balance']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function createWallet() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_pass');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $result = $this->blockchain->Create->create($requestedParams['wallet_pass'], $requestedParams['wallet_email'], $requestedParams['wallet_label']);
            if (!$result['guid']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function sendPayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass', 'to_address', 'amount');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->send($requestedParams['to_address'], $requestedParams['amount'], $requestedParams['from_address'], $requestedParams['fee']);
            if (!$result['tx_hash']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    /**
     * Sample multiple recipient address   
     * {
      "1JzSZFs2DQke2B3S4pBxaNaMzzVZaG4Cqh": 100000000,
      "12Cf6nCcRtKERh9cQm3Z29c9MWvQuFSxvT": 1500000000,
      "1dice6YgEVBf88erBFra9BHf6ZMoyvG88": 200000000
      }
     * */
    public function sendMultiplePayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass', 'to_address', 'amount');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->send($requestedParams['to_address'], $requestedParams['amount'], $requestedParams['from_address'], $requestedParams['fee']);
            if (!$result['tx_hash']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getAllWalletAddress() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result['address'] = $this->blockchain->Wallet->getAddresses();
            $result['identifier'] = $this->blockchain->Wallet->getIdentifier();
            if (!$result['address']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getBalanceByWalletAddress() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass', 'address');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->getAddressBalance($requestedParams['address']);
            if (!$result) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function generateAddressForWallet() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass', 'label');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->getNewAddress($requestedParams['label']);
            if (!$result) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function archiveWalletAddress() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass', 'address');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->archiveAddress($requestedParams['address']);
            if (!$result['archived']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function unarchiveWalletAddress() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_pass', 'address');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->unarchiveAddress($requestedParams['address']);
            if (!$result['archived']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

}
