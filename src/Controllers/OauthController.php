<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Exception;

class OauthController extends ApiController
{
    public function getAccessToken()
    {
        try {

            $requestedParams = $this->request->getParameters();

            if (!isset($requestedParams['grant_type'])) {
                throw new Exception("Please enter valid require parameters.");
            }
            $accessToken = $this->getOauthAccessToken();
            $this->response->setContent(json_encode($accessToken));
        } catch (Exception $e) {
            throw new Exception("API call: getAccessToken :" . $e->getMessage());
        }
    }
}
