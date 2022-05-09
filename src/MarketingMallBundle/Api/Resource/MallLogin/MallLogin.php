<?php

namespace MarketingMallBundle\Api\Resource\MallLogin;

use ApiBundle\Api\ApiRequest;
use Firebase\JWT\JWT;
use MarketingMallBundle\Api\Resource\BaseResource;
use MarketingMallBundle\Client\MarketingMallApi;
use Topxia\Service\Common\ServiceKernel;

class MallLogin extends BaseResource
{
    private $storages = [];

    public function add(ApiRequest $request)
    {
        $this->storages = $this->getSettingService()->get('storage', []);
        $mallSettings = $this->getSettingService()->get('marketing_mall', []);
        if (empty($mallSettings)) {
            $mallSettings = $this->initSchool();
        }

        $authorization = JWT::encode(['exp' => time() + 1000 * 3600 * 24, 'userInfo' => $this->getUserInfo(), 'access_key' => $mallSettings['access_key'], 'header' => 'MARKETING_MALL'], $mallSettings['secret_key']);

        return [
                'token' => $authorization,
                'url' => $this->getSchema().$_SERVER['HTTP_HOST'],
                'code' => $mallSettings['access_key'],
            ];
    }

    private function getUserInfo()
    {
        $user = $this->getUserService()->getUserAndProfile($this->getCurrentUser()->getId());

        return [
            'nickname' => $user['nickname'],
            'truename' => $user['truename'],
            'avatar' => $user['mediumAvatar'],
            'mobile' => $user['verifiedMobile'],
            'email' => $user['email'],
        ];
    }

    protected function initSchool()
    {
        $client = new MarketingMallApi($this->storages);
        $authorization = JWT::encode(['exp' => time() + 1000 * 3600 * 24, 'userInfo' => $this->getUserInfo(), 'access_key' => $this->storages['cloud_access_key'], 'header' => 'MARKETING_MALL'], $this->storages['cloud_secret_key']);
        $result = $client->init([
            'token' => $authorization,
            'url' => $this->getSchema() . $_SERVER['HTTP_HOST'],
            'code' => $this->storages['cloud_access_key'],
        ]);
        $setting = [
            'accessKey' => $result['accessKey'],
            'secretKey' => $result['secretKey'],
        ];
        $this->getSettingService()->set('marketing_mall', $setting);

        return $setting;
    }

    protected function getSchema()
    {
        $https = empty($_SERVER['HTTPS']) ? '' : $_SERVER['HTTPS'];
        if ('off' !== strtolower($https)) {
            return 'https://';
        }

        return 'http://';
    }

    protected function getUserService()
    {
        return ServiceKernel::instance()->createService('User:UserService');
    }

    private function getSettingService()
    {
        return ServiceKernel::instance()->createService('System:SettingService');
    }
}
