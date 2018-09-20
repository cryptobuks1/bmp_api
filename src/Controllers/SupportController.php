<?php

namespace Api\Controllers;

//use Api\Models\Customer;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;
use Api\Models\BmpWallet;
use Api\Models\Users;
use Api\Models\Support;
use PDO;

class SupportController extends ApiController {

    public function getAllSupportTicketByUserName() {
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
                $support = new Support($this->pdo);
                if ((isset($useResponse['is_admin_user'])) && $useResponse['is_admin_user'] == 1) {
                    $supportDBResponse = $support->getAllSupportTicketByUserName('');
                } else {
                    $supportDBResponse = $support->getAllSupportTicketByUserName($requestedParams['user_name']);
                }
                $response['support_data'] = $supportDBResponse;
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

    public function addSupportRequest() {
        $object = new stdClass();
        try {
            $this->validateOauthRequest();
            $requestedParams = $this->request->getParameters();
            $this->response->setContent(json_encode($requestedParams));

            $platform = parent::PLATFORM;
            $transactionType = parent::TRANSACTION_TYPE;


            //array of required fields
            $requiredData = array('user_name', 'ticket_id', 'issue','category');
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
            if (empty($requestedParams["user_name"]) || empty($requestedParams["ticket_id"]) || empty($requestedParams["issue"])|| empty($requestedParams["category"])) {
                throw new Exception("Please enter valid withdrawl parameters.");
            }
            $requestedParams['status'] = '1';
            $support = new Support($this->pdo);
            $usersObj = new Users($this->pdo);
            $useResponse = $usersObj->getUserDetailsByUserName($requestedParams["user_name"]);
            if ($useResponse) {
                $addSupportTicket = $support->insert(array($requestedParams));
                if ($addSupportTicket) {
                    $usersObj = new Users($this->pdo);
                    $requestedParams['from_email_address'] = $useResponse['Email'];
                    $requestedParams['from_email_name'] = $useResponse['Fullname'];
                    $requestedParams['to_email_address'] = 'support@bitminepool.com';
                    $requestedParams['to_email_name'] = 'Support';
                    $requestedParams['subject'] = 'Support ticket with ID '.$requestedParams['ticket_id'];
                    $message = '';
                    $message .= '<table>';
                    $message .= '<tr>';
                    $message .= '<td>User Name</td><td>Ticket ID</td><td>Description</td>';
                    $message .= '<td>'.$requestedParams["user_name"].'</td><td>'.$requestedParams["ticket_id"].'</td><td>'.$requestedParams["issue"].'</td>';
                    $message .= '</tr>';
                    
                    $message .= '</table>';
                    
                    $requestedParams['message'] = $message;

                    $sendEmailresult = $this->sendEmail($requestedParams);
                    $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $requestedParams, 'Support ticket submitted successfully.');
                } else {
                    $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $result, 'There is problem to submit ticket.');
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

}
