<?php

namespace Api\Services\Oauth2\Grant;

use OAuth2\GrantType\GrantTypeInterface;
use Api\Services\Oauth2\Storage\UserGrantInterface;
use OAuth2\ResponseType\AccessTokenInterface;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;

class UserGrant implements GrantTypeInterface
{

    private $userInfo;
    protected $storage;

    /**
     * @param Api\Services\Oauth2\Storage\UserGrantInterface $storage REQUIRED Storage class for retrieving user credentials information
     */
    public function __construct(UserGrantInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getQuerystringIdentifier()
    {
        return 'user';
    }

    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        if (!$request->request("phone")) {
            $response->setError(400, 'invalid_request', 'Missing parameters: "phone" required');

            return null;
        }

        if (!$this->storage->checkUserCredentials($request->request("phone"))) {
            $response->setError(401, 'invalid_grant', 'Invalid username combination');

            return null;
        }

        $userInfo = $this->storage->getUserDetails($request->request("phone"));

        if (empty($userInfo)) {
            $response->setError(400, 'invalid_grant', 'Unable to retrieve user information');

            return null;
        }

        if (!isset($userInfo['user_id'])) {
            throw new \LogicException("you must set the user_id on the array returned by getUserDetails");
        }

        $this->userInfo = $userInfo;

        return true;
    }

    public function getClientId()
    {
        return null;
    }

    public function getUserId()
    {
        return $this->userInfo['user_id'];
    }

    public function getScope()
    {
        return isset($this->userInfo['scope']) ? $this->userInfo['scope'] : null;
    }

    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope)
    {
        return $accessToken->createAccessToken($client_id, $user_id, $scope);
    }
}
