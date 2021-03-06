<?php

namespace Api\Controllers;

use Http\Response;
use Http\Request;
use PDO;
use Api\Models\Users;
use Api\Models\Tree;
use Api\Models\Rank;
use Api\Models\Email;
use Exception;
use stdClass;
use Api\Services\EmailHelper;
use Api\Services\McryptCipher;
use Api\Controllers\WalletController;

class UserController extends ApiController {
    # define constant for platform

    public function loginCustomer() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;
            $grantType = parent::GRANT_TYPE;
            $requiredData = array('user_name', 'password', 'platform', 'transaction_type', 'grant_type');
            $this->validation($requestedParams, $requiredData);
            $userDetails = "";
            if (empty($requestedParams["user_name"]) || empty($requestedParams["password"])) {
                throw new Exception("Please enter valid user credentials.");
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

            $mcryptCipher = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $requestedParams["password"] = $mcryptCipher->encryptDecrypt($requestedParams["password"], 'e');
            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->checkLoginResponse($requestedParams);

            if ($useResponse) {
                $response = $usersObj->getUserDetailsByUserName($useResponse['Username']);
            } else {
                throw new Exception('Please enter valid username and password.');
            }
            //$accessToken = $this->getOauthAccessToken();
            //return  $this->response->setContent(json_encode($accessToken));
            //$response->auth = $accessToken;
            $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'You have login successfully.');
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function registerCustomer() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $transactionType = self::TRANSACTION_TYPE;
            $grantType = parent::GRANT_TYPE;

            $requiredData = array('name', 'email', 'gender', 'password', 'user_name', 'platform', 'transaction_type', 'country');

            $this->validation($requestedParams, $requiredData);
            $userDetails = "";

            if (empty($requestedParams["user_name"]) || empty($requestedParams["password"]) || empty($requestedParams["email"]) || empty($requestedParams["name"])) {
                throw new Exception("Please enter all valid user details.");
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
            $grantTypeKey = array_keys($grantType);
            if (isset($requestedParams["grant_type"]) && !in_array($requestedParams["grant_type"], $grantTypeKey)) {
                throw new Exception("Please enter valid grant type.");
            }
            $requestedParams["token"] = mt_rand(0, 1000000);
            $requestedParams["account"] = $this->random_code(20);
            $requestedParams["status"] = 'Open';
            $requestedParams["activation"] = '0';
            $usersObj = new Users($this->pdo);
            $mcryptCipher = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $requestedParams["password"] = $mcryptCipher->encryptDecrypt($requestedParams["password"], 'e');
            $useResponse = $usersObj->checkLoginResponse($requestedParams);
            if ($useResponse) {
                $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $useResponse, 'User is alredy exist.');
            } else {

                $newUser = $usersObj->createUser($requestedParams);
                if ($newUser) {
                    if ($newUser['response'] == 1) {
                        $sendVerifyCode = $this->sendVerficationEmail($requestedParams);
                        $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'User created successfully.');
                    } else {
                        $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $newUser, 'There is problem to create user.');
                    }
                } else {
                    $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'There is problem to create user.');
                }
            }

            //$accessToken = $this->getOauthAccessToken();
            //return  $this->response->setContent(json_encode($accessToken));
            //$response->auth = $accessToken;
            //$response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Please login to proceed.');
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function updateCustomer() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $transactionType = self::TRANSACTION_TYPE;
            $grantType = parent::GRANT_TYPE;

            $requiredData = array('name', 'gender', 'platform', 'id');

            $this->validation($requestedParams, $requiredData);
            $userDetails = "";

            if (empty($requestedParams["id"]) || empty($requestedParams["name"]) || empty($requestedParams["gender"])) {
                throw new Exception("Please enter all valid user details.");
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
            $grantTypeKey = array_keys($grantType);
            if (isset($requestedParams["grant_type"]) && !in_array($requestedParams["grant_type"], $grantTypeKey)) {
                throw new Exception("Please enter valid grant type.");
            }

            $usersObj = new Users($this->pdo);

            $newUser = $usersObj->updateUser($requestedParams);
            if ($newUser) {
                $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'User updated successfully.');
            } else {
                $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], 'There is problem to update user.');
            }

            //$accessToken = $this->getOauthAccessToken();
            //return  $this->response->setContent(json_encode($accessToken));
            //$response->auth = $accessToken;
            //$response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Please login to proceed.');
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function sendVerficationEmail($params) {
        try {
            //PHPMailer Object
            $mail = new EmailHelper;
            $email = new Email($this->pdo);

            $message = '';
            $message .= '<table style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: #000000; line-height: 22px; width: 600px;" cellspacing="0" cellpadding="0" align="center">';
            $message .= "<tr><td>Verification Code</td><td>" . $params['token'] . "</td></tr>";

            $message .= "</table>";

            //echo $message;
            $emailContent = $email->getEmailContent('EMAIL_VERIFICATION', ['verificationDetails' => $message,
                'name' => $params['name'],
                'logo' => getenv('BASE_URL') . '/images/logo_transparent_small.png',
            ]);

            $result = $mail->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_EMAIL_NAME'), $params['email'], $params['name'], 'BitMine Pool Email Verification Code', $emailContent);

            if ($result) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function sendForgetPasswordEmail($params) {
        try {
            //PHPMailer Object
            $mail = new EmailHelper;
            $email = new Email($this->pdo);

            $message = '';
            $message .= '<table style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: #000000; line-height: 22px; width: 600px;" cellspacing="0" cellpadding="0" align="center">';
            $message .= "<tr><td>User Name</td><td>" . $params['Username'] . "</td></tr>";
            $message .= "<tr><td>Password</td><td>" . $params['Password'] . "</td></tr>";

            $message .= "</table>";

            //echo $message;
            $emailContent = $email->getEmailContent('FORGET_PASSWORD', ['passwordDetails' => $message,
                'name' => $params['Fullname'],
                'logo' => getenv('BASE_URL') . '/images/logo_transparent_small.png',
            ]);


            $result = $mail->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_EMAIL_NAME'), $params['Email'], $params['Fullname'], 'Bit Mine Pool Forget Password', $emailContent);
            if ($result) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function sendTestEmail() {
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            return $this->response->setContent(json_encode($requestedParams));
            $platform = parent::PLATFORM;
            $requiredData = array('email_address');

            $this->validation($requestedParams, $requiredData);
            //PHPMailer Object
            $mail = new EmailHelper;

            $result = $mail->sendEmail(getenv('REGISTER_FROM_EMAIL'), getenv('REGISTER_FROM_EMAIL_NAME'), $requestedParams['email_address'], 'Test email', 'Bit Mine Pool Email Verification Code', 'This is test message.');
            if ($result) {
                $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Email sent successfully.');
            } else {
                $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to send email.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }
        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function random_code($limit) {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public function verifyEmail() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;

            $requiredData = array('user_name', 'token');
            $this->validation($requestedParams, $requiredData);
            $userDetails = "";
            if (empty($requestedParams["user_name"]) || empty($requestedParams["token"])) {
                throw new Exception("Please enter valid verification details.");
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

            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams['user_name'], $requestedParams['token']);
            if ($useResponse) {
                $response = $useResponse;
                $requestedParams['token'] = mt_rand(0, 1000000);
                $requestedParams['activation'] = 1;
                $updateActivationCode = $usersObj->activateUserCode($requestedParams);
            } else {
                throw new Exception('Please enter valid verification code.');
            }

            $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'Please log in to proceed.');
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function sendForgetPassword() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;

            $requiredData = array('user_name', 'platform');
            $this->validation($requestedParams, $requiredData);
            $userDetails = "";
            if (empty($requestedParams["user_name"])) {
                throw new Exception("Please enter valid verification details.");
            }
            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }


            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
            $mcryptCipher = new McryptCipher(getenv('ENCRYPTION_KEY'));
            $useResponse['Password'] = $mcryptCipher->encryptDecrypt($useResponse['Password'], 'd');
            if ($useResponse) {
                $sendForgetPassword = $this->sendForgetPasswordEmail($useResponse);
                if ($sendForgetPassword) {
                    $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $useResponse, 'The password has been sent to your register email address.');
                } else {
                    $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $sendForgetPassword, 'Please try after some time.');
                }
            } else {
                throw new Exception('Please enter valid user name.');
            }

            //$response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'User Details');
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function sendEmailVerificationCode() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;

            $requiredData = array('user_name', 'platform');
            $this->validation($requestedParams, $requiredData);
            $userDetails = "";
            if (empty($requestedParams["user_name"])) {
                throw new Exception("Please enter valid verification details.");
            }
            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }
            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);

            if ($useResponse) {
                $useResponse['token'] = $useResponse['Token'];
                $useResponse['name'] = $useResponse['Fullname'];
                $useResponse['email'] = $useResponse['Email'];
                $sendVerificationCode = $this->sendVerficationEmail($useResponse);
                if ($sendVerificationCode) {
                    $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $useResponse, 'The code has been sent to your register email address.');
                } else {
                    $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $sendVerificationCode, 'Please try after some time.');
                }
            } else {
                throw new Exception('Please enter valid user name.');
            }

            //$response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $response, 'User Details');
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    public function getAllUserDataByUserName() {

        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $platform = parent::PLATFORM;
            $requiredData = array('user_name', 'platform');
            $this->validation($requestedParams, $requiredData);
            $userDetails = "";
            if (empty($requestedParams["user_name"])) {
                throw new Exception("Please enter valid user credentials.");
            }
            //Get constant
            $platformKey = array_keys($platform);

            if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                throw new Exception("Please enter valid platform.");
            }
            $usersObj = new Users($this->pdo);
            $treeObj = new Tree($this->pdo);
            $rankObj = new Rank($this->pdo);
            $useResponse['user_data'] = $usersObj->getUserDetailsByUserName($requestedParams['user_name']);
            $useResponse['tree_data'] = $treeObj->getTreeDataByUserName($requestedParams['user_name']);
            $useResponse['rank_data'] = $rankObj->getSimpleRankDataByUserName($requestedParams['user_name']);
            $useResponse['child_node_data'] = $usersObj->getChildNodeDataByUserName($requestedParams['login_user_name']);
            $useResponse['dashboard_data'] = $usersObj->getDashboardDataByUserName($requestedParams['user_name']);
            if ($useResponse['user_data']) {
                $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $useResponse, 'User details.');
            } else {
                throw new Exception('Please enter valid username.');
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

}
