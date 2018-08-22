<?php
namespace Api\Controllers;

use Http\Response;
use Http\Request;
use PDO;
use Api\Models\Users;
use Exception;
use stdClass;

class UserController extends ApiController
{
    # define constant for platform

    const PLATFORM = array("1" => "Android", "2" => "iOS", "3" => "Web");
    # define platform for transaction type
    const TRANSACTION_TYPE = array("201" => "Customer Login", "202" => "Registration", "203" => "Pool Registration", "204" => "Redemption verification");

    //Generate user details based on mobile_number and facebookId
    /*
     * @input params : mobile_number, facebookId
     * @output : return success or error response
     */
    public function getDetails()
    {
        try {
            //if ($this->validateOauthRequest()) {
                $requestedParams = $this->request->getParameters();
                //array of required fields
                $requiredData = array('platform');
                //Validate input parameters
                $this->validation($requestedParams, $requiredData);
                if (!isset($requestedParams['mobile_number']) && !isset($requestedParams['facebookId'])) {
                    throw new Exception("Please enter valid mobile_number or facebookId.");
                }
                if (isset($requestedParams["mobile_number"])) {
                    //Validate Mobile number
                    $this->inputNumber($requestedParams["mobile_number"], 10, "Mobile", 1);
                }
                $response = $this->getDetailsResponse($requestedParams);
            //}
        } catch (Exception $e) {
            $response = json_encode($this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], $e->getMessage()));
        }
        $this->response->setContent($response); // send response in json format
    }

    //Generate user details response based on mobile_number and facebookId
    /*
     * @input params : mobile_number, facebookId
     * @output : return success or error response
     */
    public function getDetailsResponse($requestedParams)
    {
        try {
            $columnName = "mobile_number";
            $isMerchantLogin = 0;
            $merchantId = 0;
            if (isset($requestedParams['mobile_number'])) {
                $userNumber = $requestedParams['mobile_number'];
            }
            if (isset($requestedParams['facebookId'])) {
                $columnName = "facebook_social_id";
                $userNumber = $requestedParams['facebookId'];
            }
            if (isset($requestedParams['isMerchantLogin']) && $requestedParams['isMerchantLogin'] == 1) {
                $isMerchantLogin = 1;    
            }
            if (isset($requestedParams['merchantId']) && $requestedParams['merchantId'] > 0) {
                $merchantId = $requestedParams['merchantId'];    
            }
            
            // Check user is registered or not
            $customer = new Customer($this->pdo, $this->redis);
            $customerId = $customer->isUserRegistered($userNumber, $columnName, isset($requestedParams["mobile_number"]) ? $requestedParams["mobile_number"] : '',$isMerchantLogin,$merchantId);
            $customerDetails = [];
            $addedMerchantDetails = [];
            $availableMerchantDetails = [];
            $isUserRegister = "0";
            $isUserRegisteredForSmatloyal = "0";
            
            if ($customerId) {
                $customer = new Customer($this->pdo, $this->redis);
                $customerDetails = $customer->getCustomerDetails($customerId);
                $merchant = new Merchant($this->pdo, $this->redis);
                $addedMerchantDetails = $merchant->getMerchantDetails($customerId);
                $merchantIds = "";
                foreach ($addedMerchantDetails as $addedMerchantDetail) {
                    $merchantIds .= $addedMerchantDetail['id'] . ", ";
                }
                $merchantIds = trim($merchantIds, ", ");
                $availableMerchantDetails = $merchant->getAvailableMerchantDetails($merchantIds, $customerDetails['current_tier_id']);
                $isUserRegister = "1";
            } else if($isMerchantLogin && $customerId == 0){ //Only for web app merchant login
                
                //Check wheather user is registered for smatloyal
                $customerId = $customer->isUserRegistered($userNumber, $columnName, isset($requestedParams["mobile_number"]) ? $requestedParams["mobile_number"] : '',0,0);
                if ($customerId){
                    $customer = new Customer($this->pdo, $this->redis);
                    $customerDetails = $customer->getCustomerDetails($customerId);
                    $merchant = new Merchant($this->pdo, $this->redis);
                    $addedMerchantDetails = $merchant->getMerchantDetails($customerId);
                    $merchantIds = "";
                    foreach ($addedMerchantDetails as $addedMerchantDetail) {
                        //If user is already registered to this loyalty program then dont allow 
                        if($addedMerchantDetail['id'] === $merchantId){
                            throw new Exception('Customer has already registered to this loyalty program with another number. Please try again with different mobile number.');
                        }
                    }
                    
                    $isUserRegisteredForSmatloyal = "1";
                }
            }
            
            $responseDetails = ["isUserRegister" => $isUserRegister,"isUserRegisteredForSmatloyal" => $isUserRegisteredForSmatloyal, "versionCode" => '1.0', "merchant_list" => [ "added" => $addedMerchantDetails, "available" => $availableMerchantDetails]];
            $response = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $responseDetails, 'User Details');
        } catch (Exception $e) {
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, [], $e->getMessage());
        }

        return json_encode($response);
    }

    //check OTP while login and get response of user like customer , merchant
    /*
     * @input params : mobile_number, otp, id
     * @output : return success or error response
     */
    public function loginCustomer()
    {

        try {
            $onlyFacebookLogin = false;
            $requestedParams = $this->request->getParameters();
            $platform = self::PLATFORM;
            $transactionType = self::TRANSACTION_TYPE;
            $userDetails = "";
            if (!isset($requestedParams["mobile_number"]) && !isset($requestedParams["facebookId"])) {
                //array of required fields
                $requiredData = array('mobile_number');
                //Validate input parameters
                $this->validation($requestedParams, $requiredData);
            } elseif (isset($requestedParams["mobile_number"]) && !isset($requestedParams["facebookId"])) {
                //array of required fields
                $requiredData = array('mobile_number', 'otp', 'platform', 'transactionType');
                //Validate input parameters
                $this->validation($requestedParams, $requiredData);
                //Get constant
                $platformKey = array_keys($platform);
                if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                    throw new Exception("Please enter valid platform.");
                }

                $transactionTypeKey = array_keys($transactionType);
                if (isset($requestedParams["transactionType"]) && !in_array($requestedParams["transactionType"], $transactionTypeKey)) {
                    throw new Exception("Please enter valid transaction type.");
                }

                if (isset($requestedParams["mobile_number"])) {
                    //Validate Mobile number
                    $this->inputNumber($requestedParams["mobile_number"], 9, "Mobile", 1);
                }

                $otpDetails = new SmsController($this->request, $this->response, $this->pdo, $this->redis, $this->oauthServer);
                $otpResponse = $otpDetails->checkOtpResponse($requestedParams);
                $decodedResponse = json_decode($otpResponse);
                if ($decodedResponse->status == "Success") {
                    $userDetails = $this->getDetailsResponse($requestedParams);
                } else {
                    throw new Exception($decodedResponse->statusDescription);
                }
            } elseif (!isset($requestedParams["mobile_number"]) && isset($requestedParams["facebookId"])) {

                //array of required fields
                $requiredData = array('platform', 'facebookId');
                //Validate input parameters
                $this->validation($requestedParams, $requiredData);
                //Validate platform
                $platformKey = array_keys($platform);
                if (isset($requestedParams["platform"]) && !in_array($requestedParams["platform"], $platformKey)) {
                    throw new Exception("Please enter valid platform.");
                }
                $userDetails = $this->getDetailsResponse($requestedParams);
                $onlyFacebookLogin = true;
            }

            $decodedResponse = json_decode($userDetails);
            if ($decodedResponse->status == "Success") {
                $response = $decodedResponse;

                if ($onlyFacebookLogin) {
                    $customerData = json_decode(json_encode($decodedResponse), True);
                    if (isset($customerData['response']['merchant_list']['added']['0']['customerDetails']['mobile_number'])) {
                        $_POST['phone'] = $customerData['response']['merchant_list']['added']['0']['customerDetails']['mobile_number'];
                    }
                }

                $accessToken = $this->getOauthAccessToken();
                $response->auth = $accessToken;
            } else {
                throw new Exception($decodedResponse->statusDescription);
            }
        } catch (Exception $e) {
            $object = new stdClass();
            $response = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, $object, $e->getMessage());
        }

        return $this->response->setContent(json_encode($response)); // send response in json format
    }

    /**
     * Check the list of fields which are marked as required and throws wxception if missing
     * @param array $requestedParams
     * @param array $requiredData
     * @throws Exception
     */
    public function validation($requestedParams, $requiredData)
    {
        $missingInput = array(); //array to contain error
        $error = "";
        foreach ($requestedParams as $key => $value) {
            if (!in_array($key, $requiredData)) {
                $error .= $key . ", ";
            } else {
                $key = array_search($key, $requiredData);
                if ($key !== false)
                    unset($requiredData[$key]);
            }
        }
        if ($error != "") {
            //$missingInput[] = "Invalid " . trim($error, ", ");
        }

        if (count($requiredData) !== 0) {
            $missingInput[] = 'Parameter missing : ' . implode(',', $requiredData);
        }

        if (!empty($missingInput)) {
            throw new Exception(implode(", ", $missingInput));
        }
    }

    /**
     * validate mobile number
     * @params number , digit, column name , required
     * @return true or false
     * @throws Exception
     * * */
    public function inputNumber($number, $digit, $varName, $required = 1)
    {
        if (empty($number)) {
            if ($required == 1) {
                throw new Exception("$varName is required.");
                return false;
            }
        } else {
            if (!preg_match("/^[0-9]*$/", $number) || strlen($number) != $digit) {
                throw new Exception("Invalid $varName number");
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Accepts and sends the customer device token data to Api\Models\Admin model
     * @throws Exception
     * @param array $admins (all fields of admins table)
     * @return json string
     */
    public function createPOSUser()
    {
        $admin = new Admin($this->pdo, $this->redis);
        $admins = $this->request->getParameter('posUsers'); //get the posted data
        try {
            $result = $admin->insert($admins); // send data to model and get response
            //echo $result; die;
            if (!$result) {
                $error = "Something went wrong, Please try after sometime";
                throw new Exception($error); // if error then throw exception
            } else {
                $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, $result, 'Success');
            }
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, '', $e->getMessage());
        }

        $this->response->setContent(json_encode($content)); // send response in json format
    }

    /**
     * send feedback to smatloyal/user based on merchant id
     * @throws Exception
     * @param $requestedParams
     * @return json
     */
    public function submitFeedback()
    {
        try {
            $requestedParams = $this->request->getParameters();
            //array of required fields
            $requiredData = array('platform', 'merchant_id', 'customer_id', 'subject', 'feedback_message', 'app_info','reason');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $customer = new Customer($this->pdo, $this->redis);
            $customer->sendFeedback($requestedParams);
            $content = $this->getResponse('Success', parent::SUCCESS_RESPONSE_CODE, "", 'Feedback submitted successfully.');
        } catch (Exception $e) {
            $content = $this->getResponse('Failure', parent::INVALID_PARAM_RESPONSE_CODE, '', $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format
    }
}
