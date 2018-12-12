<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Api\Models\Invoice;
use Api\Models\Users;
//use Api\Services\Oauth2\Oauth;
use Api\Models\BmpWalletWithdrawalTransactions;
use stdClass;
use Exception;
use Api\Models\Email;

class ReceiveController extends ApiController {

    /**
     * 
     */
    public function generateAddressToRecivePayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {

            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;
            //array of required fields
            $requiredData = array('Username', 'Purpose', 'Invoiceid', 'Paydate', 'Amount', 'Btcamount', 'Status', 'platform', 'transaction_type');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            if (empty($requestedParams["Username"]) || empty($requestedParams["Purpose"]) || empty($requestedParams["Invoiceid"]) || empty($requestedParams["Amount"])) {
                throw new Exception("Please enter valid invoice details.");
            }
            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            $transactionTypeKey = array_keys($transactionType);
            if (isset($requestedParams["transaction_type"]) && !in_array($requestedParams["transaction_type"], $transactionTypeKey)) {
                throw new Exception("Please enter valid transaction type.");
            }

            $invoice = new Invoice($this->pdo);
            $isInvoiceExist = $invoice->isInvoicePresent($requestedParams['Username'], $requestedParams['Purpose']);
            //$this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            if ($isInvoiceExist) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $isInvoiceExist, 'Invoice already exist.');
            } else {
                try {

                    if (empty(getenv('CALLBACK_URL')) || empty(getenv('API_CODE')) || empty(getenv('X_PUB')) || empty(getenv('SECRET'))) {
                        throw new Exception("The environment parameters are missing.");
                    }
                    $invoiceId = trim($requestedParams['Invoiceid']);
                    $callbackUrl = trim(getenv('CALLBACK_URL')) . "?invoice=" . $invoiceId . "&secret=" . getenv('SECRET');
                    $callbackUrl = trim($callbackUrl);
                    // $response = $this->blockchain->ReceiveV2->generate(getenv('API_CODE'), getenv('X_PUB'), $callbackUrl, getenv('GAP_LIMIT'));
                    // Show receive address to user:
                    /* $jsonResponse = array();
                      $requestedParams['Btcaddress'] = $jsonResponse['btc_address'] = $response->getReceiveAddress();
                      $jsonResponse['index'] = $response->getIndex();
                      $jsonResponse['callback'] = $response->getCallback();
                      $requestedParams['api_response'] = json_encode($jsonResponse); */
                    $requestedParams['Btcaddress'] = '18jDWHD6ono1FyGf4eDKF4reQu9ZAkMGCj';
                    $requestedParams['api_response'] = '{"btc_address":"18jDWHD6ono1FyGf4eDKF4reQu9ZAkMGCj","index":8,"callback":"https:\/\/bitminepool.com\/bitcoin_system\/production\/payment\/callback.php?invoice=1234&secret=10081988Bmp"}';
                } catch (Exception $e) {
                    $requestedParams['Btcaddress'] = '';
                    $requestedParams['api_response'] = $e;
                }
                $invoices = [];
                foreach ($requestedParams as $key => $value) {
                    $invoices[0][$key] = $value;
                }

                $result = $invoice->insert($invoices);
                if ($result['invoice_id']) {
                    $requestedParams['invoice_id'] = $result['invoice_id'];
                    $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'New address is created.');
                } else {
                    $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, 'Something went Wrong.');
                }
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function checkForAvailableInvoiceToRecivePayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $platform = parent::PLATFORM;
            $requestedParams = $this->request->getParameters();
            //array of required fields
            $requiredData = array('Username', 'Purpose', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            if (empty($requestedParams["Username"]) || empty($requestedParams["Purpose"])) {
                throw new Exception("Please enter valid user credentials.");
            }
            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            $invoice = new Invoice($this->pdo);
            $isInvoiceExist = $invoice->isInvoicePresent($requestedParams['Username'], $requestedParams['Purpose']);
            //$this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            if ($isInvoiceExist) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $isInvoiceExist, 'Please proceed ahead for Invoice payment.');
            } else {
                $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, [], 'Please proceed ahead for Invoice generation.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function checkForPaidInvoiceToRecivePayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $platform = parent::PLATFORM;
            $requestedParams = $this->request->getParameters();
            //array of required fields
            $requiredData = array('Username', 'Purpose', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            if (empty($requestedParams["Username"]) || empty($requestedParams["Purpose"])) {
                throw new Exception("Please enter valid user credentials.");
            }
            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            $invoice = new Invoice($this->pdo);
            $isInvoiceExist = $invoice->isInvoicePresent($requestedParams['Username'], $requestedParams['Purpose'], 'Paid');
            //$this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            if ($isInvoiceExist) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $isInvoiceExist, 'You have already paid for ' . $requestedParams["Purpose"] . '.');
            } else {
                $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, [], 'Please proceed ahead for Invoice generation.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getCallbacklogsByInvoiceId() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            //array of required fields
            $requiredData = array('Invoiceid');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            if (empty(getenv('CALLBACK_URL')) || empty(getenv('API_CODE'))) {
                throw new Exception("The environment parameters are missing.");
            }
            $callbackUrl = getenv('CALLBACK_URL');
            $callbackUrl .= "?invoice=" . $requestedParams['Invoiceid'] . "&secret=" . getenv('SECRET');
            $logs = $this->blockchain->ReceiveV2->callbackLogs(getenv('API_CODE'), urlencode($callbackUrl));
            $result = [];
            foreach ($logs as $key => $log) {
                $result[$key]['callback'] = $log->getCallback();
                $result[$key]['callbackAt'] = $log->getCalledAt();
                $result[$key]['callbackResponseCode'] = $log->getResponseCode();
                $result[$key]['callbackResponse'] = $log->getResponse();
            }

            if (empty($result)) {
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
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            //$requiredData = array('wallet_guid', 'wallet_pass');
            //Validate input parameters
            //$this->validation($requestedParams, $requiredData);
            $this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            $result['address'] = $this->blockchain->Wallet->getAddresses();
            $result['identifier'] = $this->blockchain->Wallet->getIdentifier();
            if (!$result['address']) {
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

    public function getAddressGap() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            if (empty(getenv('X_PUB')) || empty(getenv('API_CODE'))) {
                throw new Exception("The environment parameters are missing.");
            }
            $result = $this->blockchain->ReceiveV2->checkAddressGap(getenv('API_CODE'), getenv('X_PUB'));

            if (empty($result)) {
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

    public function getPoolDataToRecivePayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $poolData = parent::POOLDATA;
            //array of required fields
            $requiredData = array('Purpose', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            //Get constant
            $platformKey = array_keys($platform);
            //Get constant


            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }
            $poolDataKey = array_keys($poolData);

            if (isset($requestedParams["Purpose"]) && !in_array($requestedParams["Purpose"], $poolDataKey)) {
                throw new Exception("Please enter valid pool name.");
            }
            $result = $poolData[$requestedParams["Purpose"]];
            if (empty($result)) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No data found for requested pool.Please contact support@bitminepool.com');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function withdrawalPayment() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;


            //array of required fields
            $requiredData = array('user_name', 'to_address', 'amount');
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
            if (empty($requestedParams["user_name"]) || empty($requestedParams["to_address"]) || empty($requestedParams["amount"])) {
                throw new Exception("Please enter valid withdrawl parameters.");
            }

            $bmpWalletWithdrawalTransactions = new BmpWalletWithdrawalTransactions($this->pdo);
            $bmpWithdrawlTransaction = $bmpWalletWithdrawalTransactions->insert(array($requestedParams));
            if ($bmpWithdrawlTransaction) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'The withdrawl request submitted successfully.');
            } else {
                $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to sent withdrawl request.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getInvoiceByID() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $poolData = parent::POOLDATA;
            //array of required fields
            $requiredData = array('invoiceId', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            //Get constant
            $platformKey = array_keys($platform);
            //Get constant


            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }
            if (empty($requestedParams["invoiceId"])) {
                throw new Exception("Please enter valid invoice id.");
            }
            $invoice = new Invoice($this->pdo);
            $result = $invoice->getInvoiceByID($requestedParams["invoiceId"]);
            if (empty($result)) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'No data found for requested invoice id.Please contact support@bitminepool.com');
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function sendInvoiceNotificationByID() {
        // $this->response->setContent(json_encode(array('getWalletBalance is called')));
        $object = new stdClass();
        try {
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $poolData = parent::POOLDATA;
            //array of required fields
            $requiredData = array('invoiceId', 'userName', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            //Get constant
            $platformKey = array_keys($platform);
            //Get constant


            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }
            if (empty($requestedParams["invoiceId"]) || empty($requestedParams["userName"])) {
                throw new Exception("Please enter all valid invoice & user details.");
            }
            $invoice = new Invoice($this->pdo);
            $result = $invoice->getInvoiceByID($requestedParams["invoiceId"]);

            $usersObj = new Users($this->pdo);
            $email = new Email($this->pdo);
            
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["userName"]);
            $purpose = $result['Purpose'];

            $message = '';
            $message .= "<table>";
            $message .= "<tr><td>Invoice ID</td><td>".$result['Invoiceid']."</td></tr>";
            $message .= "<tr><td>Purpose</td><td>".$poolData[$purpose]['tittle']."</td></tr>";
            $message .= "<tr><td>Amount</td><td>".$result['Btcamount']."(In BTC)</td></tr>";
            $message .= "<tr><td>Paydate</td><td>".$result['Paydate']."</td></tr>";
            $message .= "</table>";
            
            //echo $message;
            $emailContent = $email->getEmailContent('INVOICE_PAID',['invoiceDetails'=>$message,'name'=>$useResponse['Fullname']]);
            echo $emailContent;
            exit;
            $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $message, 'Email sent sucessfully.');
            /*$emailSent = $this->sendEmail(getenv('REGISTER_FROM_EMAIL'),getenv('REGISTER_FROM_EMAIL_NAME'),$useResponse['Email'],$useResponse['Fullname'],"Invoice for ".$result['Invoiceid'],$message);
            if (isset($emailSent)) {
                
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, [], 'Email sent sucessfully.');
            } else {
                $content = $this->getResponse('Failure', parent::SUCCESS_RESPONSE_CODE,$requestedParams, 'No data found for requested invoice id.Please contact support@bitminepool.com');
            }*/
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

}
