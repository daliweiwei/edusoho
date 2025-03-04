<?php

namespace AppBundle\Component\OAuthClient;

use AppBundle\Common\Exception\UnexpectedValueException;
use Symfony\Component\HttpFoundation\Request;

class WeiboOAuthClient extends AbstractOAuthClient
{
    public function getAuthorizeUrl($callbackUrl, $credential)
    {
        $params = [];
        $params['client_id'] = $this->config['key'];
        $params['response_type'] = 'code';
        $params['redirect_uri'] = $callbackUrl;

        return 'https://api.weibo.com/oauth2/authorize?'.http_build_query($params);
    }

    public function getAccessToken($code, $callbackUrl)
    {
        $params = [];
        $params['client_id'] = $this->config['key'];
        $params['client_secret'] = $this->config['secret'];
        $params['authorization_code'] = 'code';
        $params['redirect_uri'] = $callbackUrl;
        $params['code'] = $code;

        $data = $this->postRequest('https://api.weibo.com/oauth2/access_token?'.http_build_query($params), []);

        $rawToken = json_decode($data, true);

        if (array_key_exists('error', $rawToken)) {
            return [
                'token' => null,
                'userId' => null,
                'expiredTime' => null,
            ];
        }

        $token = [
            'token' => $rawToken['access_token'],
            'userId' => $rawToken['uid'],
            'expiredTime' => $rawToken['expires_in'],
        ];

        return $token;
    }

    public function getUserInfo($token)
    {
        $params = [];

        $params['access_token'] = $token['token'];
        $params['uid'] = $token['userId'];

        $data = $this->getRequest('https://api.weibo.com/2/users/show.json', $params);
        $userInfo = json_decode($data, true);

        $this->checkError($userInfo);

        return $this->convertUserInfo($userInfo);
    }

    protected function convertUserInfo($rawUserInfo)
    {
        $info = [];
        $info['id'] = $rawUserInfo['idstr'];
        $info['name'] = $rawUserInfo['screen_name'];
        $info['location'] = $rawUserInfo['location'];
        $info['avatar'] = $rawUserInfo['avatar_hd'];

        return $info;
    }

    private function checkError($userInfo)
    {
        if (!array_key_exists('error_code', $userInfo)) {
            return;
        }
        if ('21321' == $userInfo['error_code']) {
            throw new UnexpectedValueException('unaudited');
        }
        if ('10006' == $userInfo['error_code']) {
            throw new UnexpectedValueException('unAuthorize');
        }
        throw new UnexpectedValueException($userInfo['error']);
    }

    public function verifyCredential(Request $request, $sessionCredential)
    {
        // TODO: Implement verifyCredential() method.
        return true;
    }
}
