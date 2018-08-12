<?php
namespace Api\Services\Oauth2\Grant;

use OAuth2\GrantType\GrantTypeInterface;
use Api\Services\Oauth2\Storage\MobileGrantInterface;
use OAuth2\ResponseType\AccessTokenInterface;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;

class MobileGrant implements GrantTypeInterface
{

    private $userInfo;
    protected $storage;

    /**
     * @param Api\Services\Oauth2\Storage\MobileGrantInterface $storage REQUIRED Storage class for retrieving user credentials information
     */
    public function __construct(MobileGrantInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getQuerystringIdentifier()
    {
        return 'mobile';
    }

    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        if (!$request->request("phone") || !$request->request("otp")) {
            $response->setError(400, 'invalid_request', 'Missing parameters: "phone" and "otp" required');

            return null;
        }

        if (!$this->storage->checkMobileCredentials($request->request("phone"), $request->request("otp"))) {
            $response->setError(401, 'invalid_grant', 'Invalid username and password combination');

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
