<?php

namespace AppBundle\Controller\AdminV2\System;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Component\OAuthClient\OAuthClientFactory;
use AppBundle\Controller\AdminV2\BaseController;
use Biz\System\Service\SettingService;
use Biz\User\Service\UserAuthSettingService;
use Biz\User\Service\UserFieldService;
use Biz\User\UserFieldException;
use Symfony\Component\HttpFoundation\Request;

class UserSettingController extends BaseController
{
    public function authAction(Request $request)
    {
        $auth = $this->getSettingService()->get('auth', []);
        $defaultSettings = $this->getSettingService()->get('default', []);
        $userDefaultSetting = $this->getSettingService()->get('user_default', []);
        $courseDefaultSetting = $this->getSettingService()->get('course_default', []);
        $path = $this->container->getParameter('kernel.root_dir').'/../web/assets/img/default/';
        $userDefaultSet = $this->getUserDefaultSet();
        $defaultSetting = array_merge($userDefaultSet, $userDefaultSetting);

        $registerEnabled = $auth && $auth['register_mode'] && 'closed' == $auth['register_mode'] ? 'closed' : 'opened';
        $default = [
            'register_enabled' => $registerEnabled,
            'register_mode' => 'mobile',
            'email_enabled' => 'closed',
            'setting_time' => -1,
            'email_activation_title' => '',
            'email_activation_body' => '',
            'welcome_enabled' => 'closed',
            'welcome_sender' => '',
            'welcome_methods' => [],
            'welcome_title' => '',
            'welcome_body' => '',
            'user_terms' => 'closed',
            'user_terms_body' => '',
            'privacy_policy' => 'closed',
            'privacy_policy_body' => '',
            'captcha_enabled' => 0,
            'register_protective' => 'middle',
            'password_level' => 'low',
            'nickname_enabled' => 0,
            'avatar_alert' => 'none',
        ];

        if (isset($auth['captcha_enabled']) && $auth['captcha_enabled']) {
            if (!isset($auth['register_protective'])) {
                $auth['register_protective'] = 'low';
            }
        }

        $auth = array_merge($default, $auth);

        //完成新人助手
        $this->doneNewcomerTask();

        if ('POST' == $request->getMethod()) {
            $defaultSetting = $request->request->all();

            if (!isset($defaultSetting['user_name'])) {
                $defaultSetting['user_name'] = '学员';
            }

            $userDefaultSetting = ArrayToolkit::parts($defaultSetting, [
                'defaultAvatar', 'user_name',
            ]);

            $default = $this->getSettingService()->get('default', []);
            $defaultSetting = array_merge($default, $defaultSettings, $courseDefaultSetting, $userDefaultSetting);

            $this->getSettingService()->set('user_default', $userDefaultSetting);
            $this->getSettingService()->set('default', $defaultSetting);

            if (isset($auth['setting_time']) && $auth['setting_time'] > 0) {
                $firstSettingTime = $auth['setting_time'];
                $authUpdate = $request->request->all();
                $authUpdate['setting_time'] = $firstSettingTime;
            } else {
                $authUpdate = $request->request->all();
                $authUpdate['setting_time'] = time();
            }

            if (empty($authUpdate['welcome_methods'])) {
                $authUpdate['welcome_methods'] = [];
            }

            if ('none' == $authUpdate['register_protective']) {
                $authUpdate['captcha_enabled'] = 0;
            } else {
                $authUpdate['captcha_enabled'] = 1;
            }

            $auth = array_merge($auth, $authUpdate);
            $this->getUserAuthSettingService()->update($auth);

            $this->setFlashMessage('success', 'site.save.success');
        }

        return $this->render('admin-v2/system/user-setting/auth.html.twig', [
            'auth' => $auth,
            'userFields' => $this->getUserFieldService()->getEnabledFieldsOrderBySeq(),
            'defaultSetting' => $defaultSetting,
            'hasOwnCopyright' => false,
        ]);
    }

    protected function doneNewcomerTask()
    {
        $biz = $this->getBiz();
        $authSettingTask = $biz['newcomer.auth_setting_task'];
        $isTaskDone = $authSettingTask->getStatus();

        if (!$isTaskDone) {
            $authSettingTask->doneTask('auth_setting_task');
        }
    }

    public function userAvatarAction(Request $request)
    {
        $defaultSetting = $this->getSettingService()->get('default', []);

        if ('POST' == $request->getMethod()) {
            $userDefaultSetting = $request->request->all();

            $userDefaultSetting = ArrayToolkit::parts($userDefaultSetting, [
                'defaultAvatar',
            ]);

            $defaultSetting = array_merge($defaultSetting, $userDefaultSetting);

            $this->getSettingService()->set('default', $defaultSetting);

            $this->setFlashMessage('success', 'site.save.success');
        }

        return $this->render('admin-v2/system/user-setting/user-avatar.html.twig', [
            'defaultSetting' => $defaultSetting,
        ]);
    }

    public function loginConnectAction(Request $request)
    {
        $clients = OAuthClientFactory::clients();
        $default = $this->getDefaultLoginConnect($clients);
        $loginConnect = $this->getSettingService()->get('login_bind', []);
        $loginConnect = array_merge($default, $loginConnect);

        if ($request->isMethod('POST')) {
            $loginConnect = ArrayToolkit::trim($request->request->all());
            $formerLoginConnect = $this->getSettingService()->get('login_bind');
            $loginConnect = array_merge($formerLoginConnect, $loginConnect);
            $loginConnect = $this->decideEnabledLoginConnect($loginConnect);

            $this->getSettingService()->set('login_bind', $loginConnect);
        }

        return $this->render('admin-v2/system/user-setting/login-connect.html.twig', [
            'loginConnect' => $loginConnect,
            'clients' => $clients,
        ]);
    }

    public function userFieldsAction(Request $request)
    {
        $textCount = $this->getUserFieldService()->countFields(['fieldName' => 'textField']);
        $intCount = $this->getUserFieldService()->countFields(['fieldName' => 'intField']);
        $floatCount = $this->getUserFieldService()->countFields(['fieldName' => 'floatField']);
        $dateCount = $this->getUserFieldService()->countFields(['fieldName' => 'dateField']);
        $varcharCount = $this->getUserFieldService()->countFields(['fieldName' => 'varcharField']);
        $selectCount = $this->getUserFieldService()->countFields(['fieldName' => 'selectField']);

        $fields = $this->getUserFieldService()->getFieldsOrderBySeq();

        for ($i = 0; $i < count($fields); ++$i) {
            if (strstr($fields[$i]['fieldName'], 'textField')) {
                $fields[$i]['fieldName'] = '多行文本';
            }

            if (strstr($fields[$i]['fieldName'], 'varcharField')) {
                $fields[$i]['fieldName'] = '文本';
            }

            if (strstr($fields[$i]['fieldName'], 'intField')) {
                $fields[$i]['fieldName'] = '整数';
            }

            if (strstr($fields[$i]['fieldName'], 'floatField')) {
                $fields[$i]['fieldName'] = '小数';
            }

            if (strstr($fields[$i]['fieldName'], 'dateField')) {
                $fields[$i]['fieldName'] = '日期';
            }

            if (strstr($fields[$i]['fieldName'], 'selectField')) {
                $fields[$i]['fieldName'] = '下拉列表';
            }
        }

        $courseSetting = $this->getSettingService()->get('course', []);
        $auth = $this->getSettingService()->get('auth', []);

        $commomFields = $this->get('codeages_plugin.dict_twig_extension')->getDict('userInfoFields');
        $commomFieldsKeys = array_keys($commomFields);

        if (isset($auth['registerFieldNameArray'])) {
            $auth['registerFieldNameArray'] = array_unique(array_merge($auth['registerFieldNameArray'], $commomFieldsKeys));
        }

        if (isset($courseSetting['userinfoFieldNameArray'])) {
            $courseSetting['userinfoFieldNameArray'] = array_unique(array_merge($courseSetting['userinfoFieldNameArray'], $commomFieldsKeys));
        }

        $userPartner = $this->getSettingService()->get('user_partner', []);
        $userFields = $this->getUserFieldService()->getEnabledFieldsOrderBySeq();
        $userFields = ArrayToolkit::index($userFields, 'fieldName');

        if ('POST' == $request->getMethod()) {
            $courseSetting['buy_fill_userinfo'] = $request->request->get('buy_fill_userinfo');
            $courseSetting['userinfoFields'] = $request->request->get('userinfoFields');
            $courseSetting['userinfoFieldNameArray'] = $request->request->get('userinfoFieldNameArray');

            $this->getSettingService()->set('course', $courseSetting);

            $userPartner['avatar_alert'] = $request->request->get('avatar_alert');
            $userPartner['nickname_enabled'] = $request->request->get('nickname_enabled');
            $userPartner['open_student_info'] = $request->request->get('open_student_info');
            $this->getSettingService()->set('user_partner', $userPartner);

            $auth['fill_userinfo_after_login'] = $request->request->get('fill_userinfo_after_login');
            $auth['registerSort'] = $request->request->get('registerSort');
            $auth['registerFieldNameArray'] = $request->request->get('registerFieldNameArray');

            $cloudSmsSettings = $this->getSettingService()->get('cloud_sms', ['sms_enabled' => 0]);
            $mobileSmsValidate = $request->request->get('mobileSmsValidate', 0);
            $auth['mobileSmsValidate'] = $cloudSmsSettings['sms_enabled'] && $mobileSmsValidate ? $mobileSmsValidate : 0;

            $this->getSettingService()->set('auth', $auth);

            $this->setFlashMessage('success', 'site.save.success');
        }

        return $this->render('admin-v2/system/user-setting/user-fields.html.twig', [
            'textCount' => $textCount,
            'intCount' => $intCount,
            'floatCount' => $floatCount,
            'dateCount' => $dateCount,
            'varcharCount' => $varcharCount,
            'selectCount' => $selectCount,
            'fields' => $fields,
            'courseSetting' => $courseSetting,
            'authSetting' => $auth,
            'userFields' => $userFields,
        ]);
    }

    public function editUserFieldsAction(Request $request, $id)
    {
        $field = $this->getUserFieldService()->getField($id);

        if (empty($field)) {
            $this->createNewException(UserFieldException::NOTFOUND_USERFIELD());
        }

        if (strstr($field['fieldName'], 'textField')) {
            $field['fieldName'] = '多行文本';
        }

        if (strstr($field['fieldName'], 'varcharField')) {
            $field['fieldName'] = '文本';
        }

        if (strstr($field['fieldName'], 'intField')) {
            $field['fieldName'] = '整数';
        }

        if (strstr($field['fieldName'], 'floatField')) {
            $field['fieldName'] = '小数';
        }

        if (strstr($field['fieldName'], 'dateField')) {
            $field['fieldName'] = '日期';
        }

        if (strstr($field['fieldName'], 'selectField')) {
            $field['fieldName'] = '下拉列表';
        }

        if ('POST' == $request->getMethod()) {
            $fields = $request->request->all();

            if (isset($fields['enabled'])) {
                $fields['enabled'] = 1;
            } else {
                $fields['enabled'] = 0;
            }
            if (!empty($field['detail'])) {
                $detail = trim($fields['detail']);
                $fields['detail'] = array_filter(explode("\r\n", $detail));
            }
            $field = $this->getUserFieldService()->updateField($id, $fields);
            $this->changeUserInfoFields($field, $type = 'update');

            return $this->redirect($this->generateUrl('admin_v2_setting_user_fields'));
        }

        if ('下拉列表' == $field['fieldName']) {
            $field['detail'] = implode("\r\n", $field['detail']);
        }

        return $this->render('admin-v2/system/user-setting/user-fields.modal.edit.html.twig', [
            'field' => $field,
        ]);
    }

    public function deleteUserFieldsAction(Request $request, $id)
    {
        $field = $this->getUserFieldService()->getField($id);

        if (empty($field)) {
            $this->createNewException(UserFieldException::NOTFOUND_USERFIELD());
        }

        if ('POST' == $request->getMethod()) {
            $this->changeUserInfoFields($field, $type = 'delete');

            $this->getUserFieldService()->dropField($id);

            return $this->redirect($this->generateUrl('admin_v2_setting_user_fields'));
        }

        return $this->render('admin-v2/system/user-setting/user-fields.modal.delete.html.twig', [
            'field' => $field,
        ]);
    }

    public function addUserFieldsAction(Request $request)
    {
        $field = $request->request->all();

        if (isset($field['field_title'])
            && in_array($field['field_title'], ['真实姓名', '手机号码', 'QQ', '所在公司', '身份证号码', '性别', '职业', '微博', '微信'])) {
            $this->createNewException(UserFieldException::DUPLICATE_TITLE());
        }
        if (!empty($field['detail'])) {
            $detail = trim($field['detail']);
            $field['detail'] = array_filter(explode("\r\n", $detail));
        }

        $field = $this->getUserFieldService()->addUserField($field);

        $this->changeUserInfoFields($field, $type = 'update');

        if (false == $field) {
            $this->setFlashMessage('danger', 'admin.setting.user.custom_fileds.empty');
        }

        return $this->redirect($this->generateUrl('admin_v2_setting_user_fields'));
    }

    protected function updateWeixinMpFile($val)
    {
        $dir = realpath(__DIR__.'/../../../../web/');
        array_map('unlink', glob($dir.'/MP_verify_*.txt'));
        if (!empty($val)) {
            file_put_contents($dir.'/MP_verify_'.$val.'.txt', $val);
        }
    }

    protected function getUserDefaultSet()
    {
        $default = [
            'defaultAvatar' => 0,
            'defaultAvatarFileName' => 'avatar',
            'articleShareContent' => '我正在看{{articletitle}}，关注{{sitename}}，分享知识，成就未来。',
            'courseShareContent' => '我正在学习{{course}}，收获巨大哦，一起来学习吧！',
            'groupShareContent' => '我在{{groupname}}小组,发表了{{threadname}},很不错哦,一起来看看吧!',
            'classroomShareContent' => '我正在学习{{classroom}}，收获巨大哦，一起来学习吧！',
            'user_name' => '学员',
        ];

        return $default;
    }

    private function changeUserInfoFields($fieldInfo, $type = 'update')
    {
        $auth = $this->getSettingService()->get('auth', []);
        $courseSetting = $this->getSettingService()->get('course', []);

        if (isset($auth['registerFieldNameArray'])) {
            if ('delete' == $type || ('update' == $type && !$fieldInfo['enabled'])) {
                foreach ($auth['registerFieldNameArray'] as $key => $value) {
                    if ($value == $fieldInfo['fieldName']) {
                        unset($auth['registerFieldNameArray'][$key]);
                    }
                }
            } elseif ('update' == $type && $fieldInfo['enabled']) {
                $auth['registerFieldNameArray'][] = $fieldInfo['fieldName'];
                $auth['registerFieldNameArray'] = array_unique($auth['registerFieldNameArray']);
            }
        }

        if (isset($courseSetting['userinfoFieldNameArray'])) {
            if ('delete' == $type || ('update' == $type && !$fieldInfo['enabled'])) {
                foreach ($courseSetting['userinfoFieldNameArray'] as $key => $value) {
                    if ($value == $fieldInfo['fieldName']) {
                        unset($courseSetting['userinfoFieldNameArray'][$key]);
                    }
                }
            } elseif ('update' == $type && $fieldInfo['enabled']) {
                $courseSetting['userinfoFieldNameArray'][] = $fieldInfo['fieldName'];
                $courseSetting['userinfoFieldNameArray'] = array_unique($courseSetting['userinfoFieldNameArray']);
            }
        }

        $this->getSettingService()->set('auth', $auth);
        $this->getSettingService()->set('course', $courseSetting);

        return true;
    }

    private function getDefaultLoginConnect($clients)
    {
        $default = [
            'mobile_bind_mode' => 'constraint',
            'login_limit' => 0,
            'client_login_limit' => 0,
            'enabled' => 0,
            'verify_code' => '',
            'captcha_enabled' => 0,
            'temporary_lock_enabled' => 0,
            'temporary_lock_allowed_times' => 5,
            'ip_temporary_lock_allowed_times' => 20,
            'temporary_lock_minutes' => 20,
            'login_captcha_enable' => 0,
        ];

        foreach ($clients as $type => $client) {
            $default["{$type}_enabled"] = 0;
            $default["{$type}_key"] = '';
            $default["{$type}_secret"] = '';
            $default["{$type}_set_fill_account"] = 0;
            if ('weixinmob' == $type) {
                $default['weixinmob_mp_secret'] = '';
            }
        }

        return $default;
    }

    private function decideEnabledLoginConnect($loginConnect)
    {
        if (0 == $loginConnect['enabled']) {
            $loginConnect['weibo_enabled'] = 0;
            $loginConnect['qq_enabled'] = 0;
            $loginConnect['renren_enabled'] = 0;
            $loginConnect['weixinweb_enabled'] = 0;
            $loginConnect['weixinmob_enabled'] = 0;
        }
        //新增第三方登录方式，加入下列列表计算，以便判断是否关闭第三方登录功能
        $loginConnects = ArrayToolkit::parts($loginConnect, ['weibo_enabled', 'qq_enabled', 'renren_enabled', 'weixinweb_enabled', 'weixinmob_enabled']);
        $sum = 0;
        foreach ($loginConnects as $value) {
            $sum += $value;
        }

        if ($sum < 1) {
            if (1 == $loginConnect['enabled']) {
                $this->setFlashMessage('danger', 'site.third_party.login.way.no_choose');
            }
            if (0 == $loginConnect['enabled']) {
                $this->setFlashMessage('success', 'site.save.success');
            }
            $loginConnect['enabled'] = 0;
        } else {
            $loginConnect['enabled'] = 1;
            $this->setFlashMessage('success', 'site.save.success');
        }

        return $loginConnect;
    }

    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    protected function getAppService()
    {
        return $this->createService('CloudPlatform:AppService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return UserFieldService
     */
    protected function getUserFieldService()
    {
        return $this->createService('User:UserFieldService');
    }

    protected function getAuthService()
    {
        return $this->createService('User:AuthService');
    }

    /**
     * @return UserAuthSettingService
     */
    protected function getUserAuthSettingService()
    {
        return $this->createService('User:UserAuthSettingService');
    }
}
