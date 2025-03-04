<?php

namespace Biz\User\Service\Impl;

use AppBundle\Common\TimeMachine;
use Biz\BaseService;
use Biz\User\Dao\TokenDao;
use Biz\User\Service\TokenService;
use Ramsey\Uuid\Uuid;

class TokenServiceImpl extends BaseService implements TokenService
{
    public function makeToken($type, array $args = [])
    {
        if ('mobile_login' == $type) {
            $args['duration'] = TimeMachine::ONE_MONTH * 6;
        }
        $token = [];
        $token['type'] = $type;
        $token['token'] = $this->_makeTokenValue();
        $token['data'] = !isset($args['data']) ? '' : $args['data'];
        $token['times'] = empty($args['times']) ? 0 : (int) $args['times'];
        $token['remainedTimes'] = $token['times'];
        $token['userId'] = empty($args['userId']) ? 0 : $args['userId'];
        $token['expiredTime'] = empty($args['duration']) ? 0 : time() + $args['duration'];

        return $this->getTokenDao()->create($token);
    }

    public function makeTokens($type, array $argsGroup = [])
    {
        $tokens = [];
        foreach ($argsGroup as $args) {
            $tokens[] = [
                'type' => $type,
                'token' => $this->_makeTokenValue(),
                'data' => !isset($args['data']) ? '' : $args['data'],
                'times' => empty($args['times']) ? 0 : (int) $args['times'],
                'remainedTimes' => empty($args['times']) ? 0 : (int) $args['times'],
                'userId' => empty($args['userId']) ? 0 : $args['userId'],
                'expiredTime' => empty($args['duration']) ? 0 : time() + $args['duration'],
            ];
        }
        $this->getTokenDao()->batchCreate($tokens);

        return $this->getTokenDao()->findByTokens(array_column($tokens, 'token'));
    }

    //length 被多处调用，length参数可认为是无效,插件等外部调用清除后，去掉length参数
    public function makeFakeTokenString($length = 32)
    {
        return $this->_makeTokenValue();
    }

    public function verifyToken($type, $value, array $data = [])
    {
        if (empty($value)) {
            return false;
        }
        $token = $this->getTokenDao()->getByToken($value);

        if (empty($token)) {
            return false;
        }

        if ($token['type'] != $type) {
            return false;
        }

        if (($token['expiredTime'] > 0) && ($token['expiredTime'] < time())) {
            return false;
        }

        if ($token['remainedTimes'] > 1) {
            $this->getTokenDao()->wave([$token['id']], ['remainedTimes' => -1]);
        }

        if (!empty($data)) {
            $token = $this->getTokenDao()->update($token['id'], ['data' => $data]);
        }

        $this->_gcToken($token);

        return $token;
    }

    public function destoryToken($token)
    {
        $token = $this->getTokenDao()->getByToken($token);

        if (empty($token)) {
            return;
        }

        $this->getTokenDao()->delete($token['id']);
    }

    public function findTokensByUserIdAndType($userId, $type)
    {
        return $this->getTokenDao()->findByUserIdAndType($userId, $type);
    }

    public function destroyTokensByUserId($userId)
    {
        return $this->getTokenDao()->destroyTokensByUserId($userId);
    }

    public function getTokenByType($type)
    {
        return $this->getTokenDao()->getByType($type);
    }

    public function deleteTokenByTypeAndUserId($type, $userId)
    {
        return $this->getTokenDao()->deleteByTypeAndUserId($type, $userId);
    }

    public function deleteExpiredTokens($limit)
    {
        $this->getTokenDao()->deleteTopsByExpiredTime(time(), $limit);
    }

    protected function _gcToken($token)
    {
        if (($token['times'] > 0) && ($token['remainedTimes'] <= 1)) {
            $this->getTokenDao()->delete($token['id']);

            return;
        }

        if (($token['expiredTime'] > 0) && ($token['expiredTime'] < time())) {
            $this->getTokenDao()->delete($token['id']);

            return;
        }

        return;
    }

    //去掉了length参数，token标准化，不允许自定义长度
    protected function _makeTokenValue()
    {
        $uuid = Uuid::uuid1();

        return $uuid->getHex();
    }

    /**
     * @return TokenDao
     */
    protected function getTokenDao()
    {
        return $this->createDao('User:TokenDao');
    }
}
