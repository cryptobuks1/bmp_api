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
            //array of required fields
            $requiredData = array('Username', 'Purpose', 'Invoiceid', 'Paydate', 'Amount', 'Btcamount', 'Status');
            //Validate input parameters
            $this->validation($requestedParams, $requiredData);
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
                    $callbackUrl = getenv('CALLBACK_URL');
                    $callbackUrl .= "?invoice=" . $requestedParams['Invoiceid'] . "&secret=" . getenv('SECRET');
                    $response = $this->blockchain->ReceiveV2->generate(getenv('API_CODE'), getenv('X_PUB'), $callbackUrl, getenv('GAP_LIMIT'));

                    // Show receive address to user:
                    $jsonResponse = array();
                    $requestedParams['Btcaddress'] = $jsonResponse['btc_address'] = $response->getReceiveAddress();
                    $jsonResponse['index'] = $response->getIndex();
                    $jsonResponse['callback'] = $response->getCallback();
                    $requestedParams['api_response'] = json_encode($jsonResponse);
                    //  $requestedParams['Btcaddress'] = '18jDWHD6ono1FyGf4eDKF4reQu9ZAkMGCj';
                    //  $requestedParams['api_response'] = '{"btc_address":"18jDWHD6ono1FyGf4eDKF4reQu9ZAkMGCj","index":8,"callback":"https:\/\/bitminepool.com\/bitcoin_system\/production\/payment\/callback.php?invoice=1234&secret=10081988Bmp"}';
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
            $logs =  $this->blockchain->ReceiveV2->callbackLogs(getenv('API_CODE'), $callbackUrl);
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
            $content = $this->getResponse('Failure', parent::AUTH_RESPONSE_CODE, $object, $e->getMessage());
        }
        $this->response->setContent(json_encode($content)); // send response in json format*/
    }

}
