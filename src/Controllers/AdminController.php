<?php

namespace Api\Controllers;

//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;
use Api\Models\Users;
use Api\Models\Email;
use Api\Models\BmpWallet;
use Api\Models\Invoice;
use Api\Models\BmpWalletSentReceiveTransactions;
use Api\Models\SiteOptions;
use Api\Models\BmpWalletWithdrawalTransactions;
use Api\Models\BmpBonusCommissionEarnLog;
use Api\Models\Tree;
use PDO;

class AdminController extends ApiController {

    public function getAllWalletDBTransactionDetails() {
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
                $walletDBResponse = $bmpWalletSentReceiveTransactions->getAllWalletDBTransactions();

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

    public function getAllAccountDBTransactionDetails() {
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
            $startDate = '';
            $endDate = '';
            if (!empty($requestedParams["start_date"])) {
                $startDate = date('Y-m-d 00:00:00', strtotime($requestedParams["start_date"]));
            } else {
                $startDate = date('Y-m-d 00:00:00', strtotime("-1 Months"));
            }
            if (!empty($requestedParams["end_date"])) {
                $endDate = date('Y-m-d 00:00:00', strtotime($requestedParams["end_date"]));
            } else {
                $endDate = date('Y-m-d H:i:s');
            }

            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
            //$useResponse = $usersObj->getAccountDetailsByUserName($requestedParams["user_name"],$startDate,$endDate);
            if ((isset($useResponse['is_admin_user'])) && $useResponse['is_admin_user'] == 1) {
                $statementDBResponse = $usersObj->getAccountDetailsByUserName('', $startDate, $endDate);
            } else {
                $statementDBResponse = $usersObj->getAccountDetailsByUserName($requestedParams["user_name"], $startDate, $endDate);
            }

            //$response['user_data'] = $useResponse;

            $walletData = [];
            if ($useResponse) {
                $response['data'] = $statementDBResponse;
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

    public function getAllInvoiceDBTransactionDetails() {
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
                $invoice = new Invoice($this->pdo);
                $invoiceDBResponse = $invoice->getAllInvoiceDBTransactions();

                $response['invoice_data'] = $invoiceDBResponse;
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

    public function getAllCommissionBonusDBDetails() {
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
                $bmpBonusCommissionEarnLog = new BmpBonusCommissionEarnLog($this->pdo);

                if ((isset($useResponse['is_admin_user'])) && $useResponse['is_admin_user'] == 1) {
                    $bonusCommisionDBResponse = $bmpBonusCommissionEarnLog->getAllCommissionBonus();
                } else {
                    $bonusCommisionDBResponse = $bmpBonusCommissionEarnLog->getAllCommissionBonus($requestedParams['user_name']);
                }
                $response['bonus_data'] = $bonusCommisionDBResponse;
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

    public function processWithdrawal() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;


            //array of required fields
            $requiredData = array('user_name', 'transaction_id', 'status');
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
            if (empty($requestedParams["user_name"]) || empty($requestedParams["transaction_id"]) || empty($requestedParams["status"])) {
                throw new Exception("Please enter valid transaction parameters.");
            }
            $bmpWalletWithdrawalTransactions = new BmpWalletWithdrawalTransactions($this->pdo);
            $usersObj = new Users($this->pdo);
            $email = new Email($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
           
            if ($useResponse && $useResponse['is_admin_user'] == 1) {
                $UpdateWithdrawalTransaction = $bmpWalletWithdrawalTransactions->updateWithdrawalTransaction($requestedParams);
                 $transactionDetail = $bmpWalletWithdrawalTransactions->getWithdrawalTransactionByID($requestedParams["transaction_id"]);
            
                 $transactionUserResponse = $usersObj->getUserDetailsByUserName($transactionDetail["user_name"]);

                 if ($UpdateWithdrawalTransaction) {
                    
                    $message = '';
                    $message .= '<table style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: #000000; line-height: 22px; width: 600px;" cellspacing="0" cellpadding="0" align="center">';
                    $message .= "<tr><td>Send To Address</td><td>" . $transactionDetail['to_address'] . "</td></tr>";
                    $message .= "<tr><td>Status</td><td>" . $transactionDetail['status_view'] . "</td></tr>";
                    $message .= "<tr><td>Amount</td><td>" . $transactionDetail['amount'] . "(In BTC)</td></tr>";
                    
                    $message .= "</table>";

                    //echo $message;
                    $emailContent = $email->getEmailContent('WITHDRAWAL_PROCESSED', ['requestDetails' => $message,
                        'name' => $transactionUserResponse['Fullname'],
                        'logo' => getenv('BASE_URL') . '/images/logo.png',
                    ]);


                    $emailSent = $this->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_EMAIL_NAME'), $transactionUserResponse['Email'], $transactionUserResponse['Fullname'], "Withdrawal process request with ID " . $transactionDetail['id'], $emailContent);

                    $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'Transaction processed successfully.');
                } else {
                    $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to process transaction.');
                }
            } else {
                throw new Exception('Please enter valid username.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function getSiteOption() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;


            //array of required fields
            $requiredData = array('user_name', 'option_name', 'platform');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            if (empty($requestedParams["user_name"]) || empty($requestedParams["option_name"])) {
                throw new Exception("Please enter valid option parameters.");
            }

            $siteOptions = new SiteOptions($this->pdo);
            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
            if ($useResponse) {
                $siteOptionData = $siteOptions->getOptionValueByName($requestedParams["option_name"]);
                if ($siteOptionData) {
                    $result['site_option'] = $siteOptionData;
                    $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'configuration option found successfully.');
                } else {
                    $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'There is problem to fetch configuration option.');
                }
            } else {
                throw new Exception('Please enter valid username.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function updateSiteOption() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;


            //array of required fields
            $requiredData = array('user_name', 'option_name', 'option_value');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }


            if (empty($requestedParams["user_name"]) || empty($requestedParams["option_name"]) || empty($requestedParams["option_value"])) {
                throw new Exception("Please enter valid option parameters.");
            }

            $requestedParams['status'] = '1';
            $siteOptions = new SiteOptions($this->pdo);
            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
            if ($useResponse) {
                $updateOptionValue = $siteOptions->updateOptionByName($requestedParams);
                if ($updateOptionValue) {
                    $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'Configuration option updated successfully.');
                } else {
                    $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'There is problem to update option.');
                }
            } else {
                throw new Exception('Please enter valid username.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

    public function sendApiEmail() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;

            //array of required fields
            $requiredData = array('to_email_address', 'to_email_name', 'subject', 'message');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);

            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }

            if (empty($requestedParams["from_email_name"])) {
                $requestedParams["from_email_name"] = getenv('REGISTER_FROM_EMAIL_NAME');
            }
            if (empty($requestedParams["from_email_address"])) {
                $requestedParams["from_email_address"] = getenv('REGISTER_FROM_EMAIL');
            }

            if (empty($requestedParams["to_email_address"]) || empty($requestedParams["to_email_name"]) || empty($requestedParams["subject"]) || empty($requestedParams["message"])) {
                throw new Exception("Please enter valid option parameters.");
            }
            $result = $this->sendEmail($requestedParams["from_email_address"], $requestedParams["from_email_name"], $requestedParams["to_email_address"], $requestedParams["to_email_name"], $requestedParams["subject"], $requestedParams["message"]);

            if ($result) {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'Email sent successfully.');
            } else {
                $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'There is problem to sent email.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

}
