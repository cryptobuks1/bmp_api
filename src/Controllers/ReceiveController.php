<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;

use Api\Models\Invoice;
//use Api\Services\Oauth2\Oauth;
use stdClass;
use Exception;

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
            $this->response->setContent(json_encode($requestedParams));
            //array of required fields
            $requiredData = array('user_name', 'invoice_id','invoice_type');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
            $invoice = new Invoice();
            $isInvoiceExist = $invoice->isInvoicePresent($requestedParams['user_name'], $requestedParams['invoice_type']);
            exit;
            //$this->blockchain->Wallet->credentials($requestedParams['wallet_guid'], $requestedParams['wallet_pass']);
            
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
   
}
