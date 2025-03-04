<?php

namespace Biz\System\Service\Impl;

use Biz\BaseService;
use Biz\System\Service\LoginBindSettingService;

class LoginBindSettingServiceImpl extends BaseService implements LoginBindSettingService
{
    const BASE_NAME = 'login_bind';

    public function get($default = [])
    {
        return $this->getSettingService()->get(self::BASE_NAME, $default);
    }

    public function set($value)
    {
        $this->getSettingService()->set(self::BASE_NAME, $value);
        $this->dispatchEvent('setting.login_bind.set', $this->get(self::BASE_NAME));
    }

    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }
}
