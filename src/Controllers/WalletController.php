<?php

namespace Api\Controllers;

//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;
use Api\Models\BmpWallet;
use Api\Models\Users;
use Api\Models\Email;
use Api\Models\BmpWalletSentReceiveTransactions;
use Api\Models\BmpWalletWithdrawalTransactions;
use PDO;
use Api\Services\McryptCipher;

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
            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;
            //array of required fields
            $requiredData = array('wallet_guid', 'wallet_password', 'platform', 'transaction_type');
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

            if (empty($requestedParams["wallet_guid"]) || empty($requestedParams["wallet_password"])) {
                throw new Exception("Please enter wallet credentials.");
            }

            $mcryptCipher = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $requestedParams['wallet_password'] = $mcryptCipher->encryptDecrypt($requestedParams['wallet_password'], 'd');

            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_password']);
            $result['balance'] = $this->blockchain->Wallet->getBalance();
            $result['balance_usd'] = $this->blockchain->Rates->fromBTC((double) $result['balance'], 'USD');
            $result['identifier'] = $this->blockchain->Wallet->getIdentifier();
            if (!$result['balance']) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Unable to fetch the wallet balance.');
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
            $requiredData = array('password', 'user_name', 'email_address', 'platform', 'transaction_type');
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
            $mcryptCipher = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $requestedParams["password"] = $mcryptCipher->encryptDecrypt($requestedParams["password"], 'e');

            $bmpWalletObj = new BmpWallet($this->pdo);
            $bmpWalletResponse = $bmpWalletObj->checkForWalletexist($requestedParams);
            if ($bmpWalletResponse) {
                $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $bmpWalletResponse, 'Wallet is alredy exist.');
            } else {
                $requestedParams['label'] = 'Main address of wallet of ' . $requestedParams["user_name"];

                $decodedPassword = $mcryptCipher->encryptDecrypt($requestedParams["password"], 'd');
                $result = $this->blockchain->Create->create($decodedPassword, $requestedParams['email_address'], $requestedParams['label']);
                //$result = '{"guid":"7e40a36a-d61a-4636-aa0e-a4ed3b06d237","address":"18SPT5NUNzkvibfw9J1ANkaF1y5NRFm1KS","label":null,"link":"Main address of wallet oftest8@gmail.com"}';
                //$result = json_decode($result);
                if ($result->guid) {
                    $requestedParams['guid'] = $result->guid;
                    $requestedParams['address'] = $result->address;
                    $requestedParams['status'] = 1;
                    $bmpWallet = $bmpWalletObj->insert(array($requestedParams));
                    if ($bmpWallet) {
                        $email = new Email($this->pdo);
                        $message = '';
                        $message .= '<table style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: #000000; line-height: 22px; width: 600px;" cellspacing="0" cellpadding="0" align="center">';
                        $message .= "<tr><td>User Name</td><td>" . $requestedParams["user_name"] . "</td></tr>";
                        $message .= "<tr><td>Wallet GUID</td><td>" . $requestedParams['guid'] . "</td></tr>";
                        $message .= "<tr><td>Wallet Address</td><td>" . $requestedParams['address'] . "</td></tr>";

                        $message .= "</table>";

                        //echo $message;
                        $emailContent = $email->getEmailContent('WALLET_CREATED', ['walletDetails' => $message,
                            'name' => $requestedParams["user_name"],
                            'logo' => getenv('BASE_URL') . '/images/logo.png',
                        ]);


                        $emailSent = $this->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_EMAIL_NAME'), $requestedParams['email_address'], $requestedParams["user_name"], "Your wallet/account with Bitminepool.com is created.", $emailContent);

                        // Email triggered after registartion
                        /*$userEmailMessage = "<table>";
                        $userEmailMessage .= "<tr><td>User Name</td><td>" . $requestedParams["user_name"] . "</td></tr>";
                        $userEmailMessage .= "<tr><td>Wallet GUID</td><td>" . $requestedParams['guid'] . "</td></tr>";

                        $userEmailMessage .= "<tr><td>Wallet Address</td><td>" . $requestedParams['address'] . "</td></tr>";

                        $userEmailMessage .= "</table>";
                        $sendUserEmail = $this->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_NAME'), $requestedParams['email_address'], $requestedParams["user_name"], "Welcome to BitMine Pool", $userEmailMessage);
                        $sendAdminEmail = $this->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_NAME'), getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_NAME'), "New user is registered.", $userEmailMessage);
                        */

                        $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'Wallet created successfully.');
                    } else {
                        $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to create user.');
                    }
                } else {
                    $response = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $result, 'There is problem to create wallet.');
                }
            }
        } catch (Exception $e) {
            $object = new stdClass();
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

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;


            //array of required fields
            $requiredData = array('user_name', 'wallet_guid', 'wallet_pass', 'from_address', 'to_address', 'amount');
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

            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result = $this->blockchain->Wallet->send($requestedParams['to_address'], $requestedParams['amount'], $requestedParams['from_address']);
            /* $result = new stdClass();
              $result->message = "Sent 0.1 BTC to 1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq";
              $result->tx_hash = "f322d01ad784e5deeb25464a5781c3b20971c1863679ca506e702e3e33c18e9c";
              $result->notice = "Some funds are pending confirmation and cannot be spent yet (Value 0.001 BTC)"; */
            if ($result->tx_hash) {

                $requestedParams['sent_receive_flag'] = 1;
                $requestedParams['invoice_id'] = 0;
                $requestedParams['status'] = 2;
                $requestedParams['response'] = $result;
                $bmpWalletSentReceiveTransactions = new BmpWalletSentReceiveTransactions($this->pdo);
                $bmpWalletTransaction = $bmpWalletSentReceiveTransactions->insert(array($requestedParams));
                if ($bmpWalletTransaction) {
                    $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'The payment sent successfully.');
                } else {
                    $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to sent payment.');
                }
            } else {
                $response = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $result, 'There is problem to sent payment.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
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
            $object = new stdClass();
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
            $requiredData = array('wallet_guid', 'wallet_pass', 'platform');
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
            $object = new stdClass();
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
            $object = new stdClass();
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
            $object = new stdClass();
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
            $object = new stdClass();
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
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getAllWalletDetailByUserName() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
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
            $mcryptCipher = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $useResponse['password'] = $mcryptCipher->encryptDecrypt($useResponse["password"], 'd');
            $response['user_data'] = $useResponse;
            $walletData = [];
            if ($useResponse) {
                if ($useResponse['guid']) {
                    $this->blockchain->Wallet->credentials($useResponse['guid'], $useResponse['password']);
                    $walletData['balance'] = $this->blockchain->Wallet->getBalance();
                    $walletData['balance_usd'] = $this->blockchain->Rates->fromBTC((double) $walletData['balance'], 'USD');
                    $walletData['addresses'] = $this->blockchain->Wallet->getAddresses();
                } else {
                    $walletData['balance'] = 0;
                    $walletData['balance_usd'] = 0;
                    $walletData['addresses'] = [];
                }
                $response['wallet_data'] = $walletData;
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Success');
                //$response = $useResponse;
            } else {
                throw new Exception('Please enter valid username and password.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/ 
    }

    public function getAllWalletDBTransactionDetailByUserName() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
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
            //$response['user_data'] = $useResponse;
            $walletData = [];
            if ($useResponse) {
                $bmpWalletSentReceiveTransactions = new BmpWalletSentReceiveTransactions($this->pdo);
                $walletDBResponse = $bmpWalletSentReceiveTransactions->getAllWalletDBTransactions($requestedParams['user_name']);

                $response['wallet_data'] = $walletDBResponse;
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Success');
                //$response = $useResponse;
            } else {
                throw new Exception('Please enter valid username.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/ 
    }

    public function getAllWithdrawalDBTransactionByUserName() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
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
            //$response['user_data'] = $useResponse;
            $walletData = [];
            if ($useResponse) {
                $bmpWalletWithdrawalTransactions = new BmpWalletWithdrawalTransactions($this->pdo);
                if ((isset($useResponse['is_admin_user'])) && $useResponse['is_admin_user'] == 1) {
                    $walletDBResponse = $bmpWalletWithdrawalTransactions->getAllWalletWithdrawalDBTransactions('');
                } else {
                    $walletDBResponse = $bmpWalletWithdrawalTransactions->getAllWalletWithdrawalDBTransactions($requestedParams['user_name']);
                }


                $response['withdrawl_data'] = $walletDBResponse;
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Success');
                //$response = $useResponse;
            } else {
                throw new Exception('Please enter valid username.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/ 
    }

}
