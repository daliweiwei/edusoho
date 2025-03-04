<?php

namespace AppBundle\Controller\AdminV2\System;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Component\OAuthClient\OAuthClientFactory;
use AppBundle\Controller\AdminV2\BaseController;
use AppBundle\Util\UploadToken;
use Biz\CloudPlatform\CloudAPIFactory;
use Biz\Common\CommonException;
use Biz\Content\FileException;
use Biz\System\Service\LoginBindSettingService;
use Biz\System\Service\PaymentSettingService;
use Biz\System\Service\SettingService;
use Biz\System\Service\WechatSettingService;
use Biz\WeChat\Service\WeChatService;
use QiQiuYun\SDK\Constants\WeChatPlatformTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WeChatSettingController extends BaseController
{
    public function indexAction(Request $request)
    {
        $isCallback = $request->query->get('isCallback', false);
        $clients = OAuthClientFactory::clients();
        $loginDefault = $this->getDefaultLoginConnect($clients);
        $loginConnect = $this->getLoginBindSettingService()->get([]);
        $loginConnect = array_merge($loginDefault, $loginConnect);

        $paymentDefault = $this->getDefaultPaymentSetting();
        $payment = $this->getSettingService()->get('payment', []);
        $payment = array_merge($paymentDefault, $payment);

        $wechatDefault = $this->getDafaultWechatSetting();
        $wechatSetting = $this->getSettingService()->get('wechat', []);
        $wechatSetting = array_merge($wechatDefault, $wechatSetting);

        $wechatNotificationDefault = $this->getDefaultWechatNotificationSetting();
        $wechatNotificationSetting = $this->getSettingService()->get('wechat_notification', []);
        $wechatNotificationSetting = array_merge($wechatNotificationDefault, $wechatNotificationSetting);

        if ($isCallback) {
            $wechatAuth = $this->getAuthorizationInfo();
            if ($wechatAuth['isAuthorized']) {
                $wechatSetting['is_authorization'] = 1;
            }
            $this->getSettingService()->set('wechat', $wechatSetting);
            $this->getSettingService()->set('wechat_notification', $wechatNotificationSetting);
        }
        if ($request->isMethod('POST')) {
            $fields = $request->request->all();
            $loginConnect = array_merge($loginConnect, ArrayToolkit::trim($fields['loginConnect']));
            $payment = array_merge($payment, ArrayToolkit::trim($fields['payment']));
            $newWeChatSetting = ArrayToolkit::trim($fields['wechatSetting']);

            $loginConnect = $this->decideEnabledLoginConnect($loginConnect);

            if (empty($payment['wxpay_enabled']) && empty($payment['alipay_enabled']) && empty($payment['llpay_enabled'])) {
                $payment['enabled'] = 0;
            } else {
                $payment['enabled'] = 1;
            }
            if (!empty($payment['wxpay_cert_uploaded'])) {
                $payment['wxpay_cert_path'] = $payment['wxpay_cert_tmp_path'] ?? '';
                $payment['wxpay_cert_name'] = $payment['wxpay_cert_tmp_name'] ?? '';
                $payment['wxpay_cert_ext'] = $payment['wxpay_cert_tmp_ext'] ?? '';
            }
            if (!empty($payment['wxpay_key_uploaded'])) {
                $payment['wxpay_key_path'] = $payment['wxpay_key_tmp_path'] ?? '';
                $payment['wxpay_key_name'] = $payment['wxpay_key_tmp_name'] ?? '';
                $payment['wxpay_key_ext'] = $payment['wxpay_key_tmp_ext'] ?? '';
            }
            unset($payment['wxpay_cert_uploaded']);
            unset($payment['wxpay_key_uploaded']);

            if (empty($loginConnect['weixinweb_enabled']) || empty($loginConnect['weixinmob_enabled'])) {
                $newWeChatSetting['wechat_notification_enabled'] = 0;
            }

            $loginConnect['weixinmob_mp_secret'] = $payment['wxpay_mp_secret'];
            $payment['wxpay_appid'] = $loginConnect['weixinmob_key'];
            $payment['wxpay_secret'] = $loginConnect['weixinmob_secret'];

            $this->getPaymentSettingService()->set($payment);
            $this->getLoginBindSettingService()->set($loginConnect);
            $this->updateWeixinMpFile($payment['wxpay_mp_secret']);

            if (!$this->getWeChatService()->handleCloudNotification($wechatSetting, $newWeChatSetting, $loginConnect)) {
                $this->setFlashMessage('danger', 'wechat.notification.switch_status_error');

                return $this->render('admin-v2/system/wechat-authorization/wechat-setting.html.twig', [
                    'loginConnect' => $loginConnect,
                    'payment' => $payment,
                    'wechatSetting' => $wechatSetting,
                    'isCloudOpen' => $this->isCloudOpen(),
                ]);
            }
            $wechatSetting = array_merge($wechatSetting, $newWeChatSetting);

            $wechatAuth = $this->getAuthorizationInfo();
            if ($wechatAuth['isAuthorized']) {
                $wechatSetting['is_authorization'] = 1;
            }

            $this->getWechatSettingService()->set($wechatSetting);
            $this->getSettingService()->set('wechat_notification', $wechatNotificationSetting);
            $this->setFlashMessage('success', 'site.save.success');
        }

        return $this->render('admin-v2/system/wechat-authorization/wechat-setting.html.twig', [
            'loginConnect' => $loginConnect,
            'payment' => $payment,
            'wechatSetting' => $wechatSetting,
            'isCloudOpen' => $this->isCloudOpen(),
            'wechatAuth' => $this->getAuthorizationInfo(),
        ]);
    }

    /**
     * @param $platformType
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function preAuthUrlAction(Request $request, $platformType)
    {
        if ('official_account' == $platformType) {
            $url = $this->getWeChatService()->getPreAuthUrl(WeChatPlatformTypes::OFFICIAL_ACCOUNT, $this->generateUrl('admin_v2_setting_wechat_auth', ['isCallback' => true], UrlGeneratorInterface::ABSOLUTE_URL));
        }
        if ('mini_program' == $platformType) {
            $url = $this->getWeChatService()->getPreAuthUrl(WeChatPlatformTypes::MINI_PROGRAM, $this->generateUrl('admin_v2_setting_wechat_auth', [], UrlGeneratorInterface::ABSOLUTE_URL));
        }

        return $this->createJsonResponse([
            'url' => empty($url) ? '' : $url,
        ]);
    }

    public function uploadCertAction(Request $request)
    {
        $this->validateToken($request->request->get('token'));
        $file = $request->files->get('file');
        $paymentSetting = $this->getSettingService()->get('payment', []);
        $paymentSetting['wxpay_cert_tmp_name'] = rtrim($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
        $paymentSetting['wxpay_cert_tmp_ext'] = '.'.$file->getClientOriginalExtension();
        $directory = $this->getBiz()['topxia.upload.private_directory'] . '/system';
        $file = $file->move($directory, 'wxpay_cert.pem');
        $paymentSetting['wxpay_cert_tmp_path'] = $file->getRealpath();
        $this->getSettingService()->set('payment', $paymentSetting);

        return $this->createJsonResponse([
            'name' => $paymentSetting['wxpay_cert_tmp_name'],
            'ext' => $paymentSetting['wxpay_cert_tmp_ext'],
        ]);
    }

    public function uploadCertKeyAction(Request $request)
    {
        $this->validateToken($request->request->get('token'));
        $file = $request->files->get('file');
        $paymentSetting = $this->getSettingService()->get('payment', []);
        $paymentSetting['wxpay_key_tmp_name'] = rtrim($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
        $paymentSetting['wxpay_key_tmp_ext'] = '.'.$file->getClientOriginalExtension();
        $directory = $this->getBiz()['topxia.upload.private_directory'] . '/system';
        $file = $file->move($directory, 'wxpay_cert_key.pem');
        $paymentSetting['wxpay_key_tmp_path'] = $file->getRealpath();
        $this->getSettingService()->set('payment', $paymentSetting);

        return $this->createJsonResponse([
            'name' => $paymentSetting['wxpay_key_tmp_name'],
            'ext' => $paymentSetting['wxpay_key_tmp_ext'],
        ]);
    }

    private function validateToken($token)
    {
        $parser = new UploadToken();
        $token = $parser->parse($token);

        if (empty($token)) {
            $this->createNewException(CommonException::EXPIRED_UPLOAD_TOKEN());
        }
        if ('system' != $token['group']) {
            $this->createNewException(FileException::FILE_GROUP_INVALID());
        }
        if ('cert' != $token['type']) {
            $this->createNewException(FileException::FILE_TYPE_ERROR());
        }
    }

    protected function isCloudOpen()
    {
        try {
            $api = CloudAPIFactory::create('root');
            $info = $api->get('/me');
        } catch (\RuntimeException $e) {
            return false;
        }

        if (empty($info['accessCloud'])) {
            return false;
        }

        return true;
    }

    protected function getAuthorizationInfo()
    {
        $biz = $this->getBiz();
        try {
            $info = $biz['ESCloudSdk.wechat']->getAuthorizationInfo(WeChatPlatformTypes::OFFICIAL_ACCOUNT);
            if ($info['isAuthorized']) {
                $ids = ArrayToolkit::column($info['funcInfo'], 'funcscope_category');
                $ids = ArrayToolkit::column($ids, 'id');
                /**
                 * 2、用户管理权限  7、群发与通知权限
                 */
                $needIds = [2, 7];
                $diff = array_diff($needIds, $ids);
                if (empty($diff)) {
                    $info['wholeness'] = 1;
                }
            }
        } catch (\Exception $e) {
            $info = [
                'isAuthorized' => false,
            ];
        }

        return $info;
    }

    private function decideEnabledLoginConnect($loginConnect)
    {
        $loginConnects = ArrayToolkit::parts($loginConnect, ['weibo_enabled', 'qq_enabled', 'renren_enabled', 'weixinweb_enabled', 'weixinmob_enabled']);
        $sum = 0;
        foreach ($loginConnects as $value) {
            $sum += $value;
        }

        if ($sum < 1) {
            $loginConnect['enabled'] = 0;
        } else {
            $loginConnect['enabled'] = 1;
        }

        return $loginConnect;
    }

    private function getDefaultLoginConnect($clients)
    {
        $default = [
            'login_limit' => 0,
            'enabled' => 0,
            'verify_code' => '',
            'captcha_enabled' => 0,
            'temporary_lock_enabled' => 0,
            'temporary_lock_allowed_times' => 5,
            'ip_temporary_lock_allowed_times' => 20,
            'temporary_lock_minutes' => 20,
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

    private function getDefaultPaymentSetting()
    {
        return [
            'enabled' => 0,
            'disabled_message' => '由于网校未开通任一支付功能，当前商品不支持购买，请联系网校开通支付功能后再进行购买。',
            'bank_gateway' => 'none',
            'alipay_enabled' => 0,
            'alipay_key' => '',
            'alipay_secret' => '',
            'alipay_account' => '',
            'alipay_type' => 'direct',
            'tenpay_enabled' => 0,
            'tenpay_key' => '',
            'tenpay_secret' => '',
            'wxpay_enabled' => 0,
            'wxpay_appid' => '',
            'wxpay_account' => '',
            'wxpay_key' => '',
            'wxpay_secret' => '',
            'llpay_enabled' => 0,
            'llpay_key' => '',
            'llpay_accessKey' => '',
            'llpay_secretKey' => '',
            'wxpay_mp_secret' => $this->getWeixinMpFile(),
        ];
    }

    private function getDafaultWechatSetting()
    {
        return [
            'wechat_notification_enabled' => 0,
            'account_code' => '',
        ];
    }

    private function getDefaultWechatNotificationSetting()
    {
        return [
            'is_authorization' => 0,
            'notification_type' => 'serviceFollow',
            'notification_sms' => 0,
        ];
    }

    private function getWeixinMpFile()
    {
        $dir = $this->container->getParameter('kernel.root_dir') . '/../web';
        $mp_secret = array_map('file_get_contents', glob($dir . '/MP_verify_*.txt'));

        return implode($mp_secret);
    }

    protected function updateWeixinMpFile($val)
    {
        $dir = $this->container->getParameter('kernel.root_dir') . '/../web';
        array_map('unlink', glob($dir . '/MP_verify_*.txt'));
        if (!empty($val)) {
            file_put_contents($dir . '/MP_verify_' . $val . '.txt', $val);
        }
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return PaymentSettingService
     */
    protected function getPaymentSettingService()
    {
        return $this->createService('System:PaymentSettingService');
    }

    /**
     * @return LoginBindSettingService
     */
    protected function getLoginBindSettingService()
    {
        return $this->createService('System:LoginBindSettingService');
    }

    /**
     * @return WechatSettingService
     */
    protected function getWechatSettingService()
    {
        return $this->createService('System:WechatSettingService');
    }

    /**
     * @return WeChatService
     */
    protected function getWeChatService()
    {
        return $this->createService('WeChat:WeChatService');
    }
}
