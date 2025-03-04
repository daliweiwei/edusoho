<?php

namespace Biz\User\Event;

use Biz\System\Service\SettingService;
use Biz\WeChat\Service\WeChatService;
use Codeages\Biz\Framework\Event\Event;
use Codeages\PluginBundle\Event\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEventSubscriber extends EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'user.registered' => 'onUserRegistered',
            'user.follow' => 'onUserFollowed',
            'user.unfollow' => 'onUserUnfollowed',
            'user.bind' => 'onUserBind',
            'user.unbind' => 'onUserUnbind',
            'user.change_password' => 'onUserChangePassword',
            'user.lock' => 'onUserLock',
        ];
    }

    public function onUserBind(Event $event)
    {
        $user = $event->getSubject();
        $type = $event->getArgument('bindType');
        $bind = $event->getArgument('bind');
        $token = $event->getArgument('token');
        if (in_array($type, ['weixinmob'])) {
            if (empty($token['openid'])) {
                return;
            }

            try {
                $this->getWeChatService()->freshOfficialWeChatUserWhenLogin($user, $bind, $token);
            } catch (\Exception $e) {
                $this->getLogger()->error($e);
            }
        }
    }

    public function onUserUnbind(Event $event)
    {
        $type = $event->getArgument('bindType');
        $bind = $event->getArgument('bind');
        if (in_array($type, ['weixinmob', 'weixinweb', 'weixin'])) {
            $weChatUsers = $this->getWeChatService()->findWeChatUsersByUserId($bind['toId']);
            foreach ($weChatUsers as $weChatUser) {
                if ($weChatUser['unionId'] == $bind['fromId']) {
                    $this->getWeChatService()->updateWeChatUser($weChatUser['id'], [
                        'userId' => 0,
                    ]);
                }
            }
        }
    }

    public function onUserRegistered(Event $event)
    {
        $user = $event->getSubject();
        $this->sendRegisterMessage($user);
    }

    public function onUserFollowed(Event $event)
    {
        $friend = $event->getSubject();
        $user = $this->getUserService()->getUser($friend['fromId']);

        $message = [
            'userId' => $user['id'],
            'userName' => $user['nickname'],
            'opration' => 'follow',
        ];
        $this->getNotificationService()->notify($friend['toId'], 'user-follow', $message);
    }

    public function onUserUnfollowed(Event $event)
    {
        $friend = $event->getSubject();
        $user = $this->getUserService()->getUser($friend['fromId']);

        $message = [
            'userId' => $user['id'],
            'userName' => $user['nickname'],
            'opration' => 'unfollow',
        ];
        $this->getNotificationService()->notify($friend['toId'], 'user-follow', $message);
    }

    public function onUserChangePassword(Event $event)
    {
        $user = $event->getSubject();
        $tokens = $this->getTokenService()->findTokensByUserIdAndType($user['id'], 'mobile_login');
        if (!empty($tokens)) {
            foreach ($tokens as $token) {
                $this->getTokenService()->destoryToken($token['token']);
            }
        }
        if (1 == $user['passwordInit']) {
            $this->getUserService()->updatePasswordChanged($user['id'], 1);
        }
    }

    public function onUserLock(Event $event)
    {
        $user = $event->getSubject();
        $fields = [
            "loginSessionId" => ""
        ];
        
        $this->getUserService()->updateUser($user['id'], $fields);
        $this->getSessionService()->deleteSessionBySessId($user['loginSessionId']);
    }

    private function sendRegisterMessage($user)
    {
        $auth = $this->getSettingService()->get('auth', []);

        if (empty($auth['welcome_enabled'])
            || 'opened' != $auth['welcome_enabled']
            || empty($auth['welcome_sender'])) {
            return;
        }

        $senderUser = $this->getUserService()->getUserByNickname($auth['welcome_sender']);

        if (empty($senderUser)) {
            return;
        }

        $welcomeBody = $this->getWelcomeBody($user);

        if (empty($welcomeBody)) {
            return;
        }

        // TODO

        //if (strlen($welcomeBody) >= 1000) {

//    $welcomeBody = $this->getWebExtension()->plainTextFilter($welcomeBody, 1000);

        //}
        if ($senderUser['id'] != $user['id']) {
            $this->getMessageService()->sendMessage($senderUser['id'], $user['id'], $welcomeBody);
            $conversation = $this->getMessageService()->getConversationByFromIdAndToId($user['id'], $senderUser['id']);
            $this->getMessageService()->deleteConversation($conversation['id']);
        }
    }

    protected function getWelcomeBody($user)
    {
        $site = $this->getSettingService()->get('site', []);
        $valuesToBeReplace = ['{{nickname}}', '{{sitename}}', '{{siteurl}}'];
        $valuesToReplace = [$user['nickname'], $site['name'], $site['url']];

        $auth = $this->getSettingService()->get('auth', []);
        $welcomeBody = '';
        if (!empty($auth) && isset($auth['welcome_body'])) {
            $welcomeBody = $auth['welcome_body'];
        }

        $welcomeBody = str_replace($valuesToBeReplace, $valuesToReplace, $welcomeBody);

        return $welcomeBody;
    }

    /**
     * @return \Biz\User\Service\TokenService
     */
    protected function getTokenService()
    {
        return $this->getBiz()->service('User:TokenService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->getBiz()->service('System:SettingService');
    }

    protected function getMessageService()
    {
        return $this->getBiz()->service('User:MessageService');
    }

    private function getUserService()
    {
        return $this->getBiz()->service('User:UserService');
    }

    protected function getNotificationService()
    {
        return $this->getBiz()->service('User:NotificationService');
    }

    protected function getSessionService()
    {
        return $this->getBiz()->service("Session:SessionService");
    }

    /**
     * @return Log
     */
    protected function getLogger()
    {
        $biz = $this->getBiz();

        return $biz['logger'];
    }

    /**
     * @return WeChatService
     */
    protected function getWeChatService()
    {
        return $this->getBiz()->service('WeChat:WeChatService');
    }
}
