<?php

namespace Api\Controllers;

//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;
use Api\Models\BmpWallet;
use Api\Models\Users;
use PDO;

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
            //array of required fields
            $requiredData = array('password', 'user_name', 'email_address');
            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;

            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            $transactionTypeKey = array_keys($transactionType);
            if (isset($requestedParams["transaction_type"]) && !in_array($requestedParams["transaction_type"], $transactionTypeKey)) {
                throw new Exception("Please enter valid transaction type.");
            }

            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            if (empty($requestedParams["user_name"]) || empty($requestedParams["password"])) {
                throw new Exception("Please enter all essential information.");
            }

            $bmpWalletObj = new BmpWallet($this->pdo);
            $bmpWalletResponse = $bmpWalletObj->checkForWalletexist($requestedParams);
            if ($bmpWalletResponse) {
                $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $bmpWalletResponse, 'Wallet is alredy exist.');
            } else {
                $requestedParams['label'] = 'Main address of wallet of ' . $requestedParams["user_name"];
                //$result = $this->blockchain->Create->create($requestedParams['password'], $requestedParams['email_address'], $requestedParams['label']);
                $result = '{"guid":"7e40a36a-d61a-4636-aa0e-a4ed3b06d237","address":"18SPT5NUNzkvibfw9J1ANkaF1y5NRFm1KS","label":null,"link":"Main address of wallet oftest8@gmail.com"}';
                $result = json_decode($result);
                if ($result->guid) {
                    $requestedParams['guid'] = $result->guid;
                    $requestedParams['address'] = $result->address;
                    $requestedParams['status'] = 1;
                    $bmpWallet = $bmpWalletObj->insert(array($requestedParams));
                    if ($bmpWallet) {
                        $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'Wallet created successfully.');
                    } else {
                        $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to create user.');
                    }
                } else {
                    $response = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $result, 'There is problem to create wallet.');
                }
            }
        } catch (Exception $e) {
            $response = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($response)); // send response in json format*/
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
            if (!$result->tx_hash) {
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
            if (!$result->tx_hash) {
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
            if (!$result->address) {
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
            if (!$result->archived) {
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
            if (!$result->archived) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No record found');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getAllWalletDetailByUserName() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('user_name', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $platform = parent::PLATFORM;
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            if (empty($requestedParams["user_name"])) {
                throw new Exception("Please enter valid user credentials.");
            }
            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
            $response['user_data'] = $useResponse;
            $walletData = [];
            if ($useResponse) {
                if ($useResponse['guid']) {
                    $this->blockchain->Wallet->credentials($useResponse['guid'], $useResponse['password']);
                    $walletData['balance'] = $this->blockchain->Wallet->getBalance();
                    $walletData['addresses'] = $this->blockchain->Wallet->getAddresses();
                } else {
                    $walletData['balance'] = 0;
                    $walletData['addresses'] = [];
                }
                $response['wallet_data'] = $walletData;
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Success');
                //$response = $useResponse;
            } else {
                throw new Exception('Please enter valid username and password.');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/ 
    }

}
