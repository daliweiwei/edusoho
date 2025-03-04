<?php

namespace AppBundle\Controller\AdminV2\Developer;

use AppBundle\Common\JsonToolkit;
use AppBundle\Controller\AdminV2\BaseController;
use Biz\Activity\Service\ActivityService;
use Biz\CloudFile\Service\CloudFileService;
use Biz\CloudPlatform\AppException;
use Biz\CloudPlatform\Service\AppService;
use Biz\File\Service\UploadFileService;
use Biz\System\Service\SettingService;
use Biz\Task\Service\TaskService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class DeveloperSettingController extends BaseController
{
    public function swaggerApiAction()
    {
        return $this->render('admin-v2/developer/swagger-ui/index.html.twig', [
        ]);
    }

    public function indexAction(Request $request)
    {
        $developerSetting = $this->getSettingService()->get('developer', []);
        $storageSetting = $this->getSettingService()->get('storage', []);

        $default = [
            'debug' => '0',
            'without_network' => '0',
            'cloud_api_server' => empty($storageSetting['cloud_api_server']) ? '' : $storageSetting['cloud_api_server'],
            'cloud_file_server' => '',
            'cloud_api_tui_server' => empty($storageSetting['cloud_api_tui_server']) ? '' : $storageSetting['cloud_api_tui_server'],
            'cloud_api_event_server' => empty($storageSetting['cloud_api_event_server']) ? '' : $storageSetting['cloud_api_event_server'],
            'cloud_api_es_op_server' => empty($storageSetting['cloud_api_es_op_server']) ? '' : $storageSetting['cloud_api_es_op_server'],
            'cloud_api_notification_server' => empty($storageSetting['cloud_api_notification_server']) ? '' : $storageSetting['cloud_api_notification_server'],
            'cloud_api_im_server' => '',
            'cloud_api_wechat_server' => empty($storageSetting['cloud_api_wechat_server']) ? '' : $storageSetting['cloud_api_wechat_server'],
            'app_api_url' => '',
            'cloud_sdk_cdn' => '',
            'hls_encrypted' => '1',
            'mp_service_url' => 'http://mp-service.qiqiuyun.net',
            'platform_news_api_server' => empty($storageSetting['platform_news_api_server']) ? '' : $storageSetting['platform_news_api_server'],
            'es_live_api_server' => '',
        ];

        $developerSetting = array_merge($default, $developerSetting);

        if ('POST' == $request->getMethod()) {
            $developerSetting = $request->request->all();

            $this->openDevModeIfDebugEnable($developerSetting);

            $storageSetting['cloud_api_server'] = $developerSetting['cloud_api_server'];
            $storageSetting['cloud_api_tui_server'] = $developerSetting['cloud_api_tui_server'];
            $storageSetting['cloud_api_event_server'] = $developerSetting['cloud_api_event_server'];
            $storageSetting['cloud_api_im_server'] = $developerSetting['cloud_api_im_server'];
            $storageSetting['cloud_api_es_op_server'] = $developerSetting['cloud_api_es_op_server'];
            $storageSetting['cloud_api_notification_server'] = $developerSetting['cloud_api_notification_server'];
            $storageSetting['cloud_api_wechat_server'] = $developerSetting['cloud_api_wechat_server'];
            $storageSetting['platform_news_api_server'] = $developerSetting['platform_news_api_server'];
            $storageSetting['es_live_api_server'] = $developerSetting['es_live_api_server'];
            $storageSetting['question_parse_api_server'] = $developerSetting['question_parse_api_server'];
            $storageSetting['ai_api_server'] = $developerSetting['ai_api_server'];
            $this->getSettingService()->set('storage', $storageSetting);
            $this->getSettingService()->set('developer', $developerSetting);

            $this->getLogService()->info('system', 'update_settings', '更新开发者设置', $developerSetting);

            $this->dealServerConfigFile();

            $this->setFlashMessage('success', 'site.save.success');
        }

        return $this->render('admin-v2/developer/developer-setting/index.html.twig', [
            'developerSetting' => $developerSetting,
        ]);
    }

    protected function dealServerConfigFile()
    {
        $serverConfigFile = $this->getParameter('kernel.root_dir').'/data/api_server.json';
        $fileSystem = new Filesystem();
        $fileSystem->remove($serverConfigFile);
    }

    public function versionAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $data = $request->request->all();
            $app = $this->getAppservice()->getAppByCode($data['code']);

            if (empty($app)) {
                $this->createNewException(AppException::NOTFOUND_APP());
            }

            $this->getAppservice()->updateAppVersion($app['id'], $data['version']);

            return $this->redirect($this->generateUrl('admin_v2_app_upgrades'));
        }

        $appCount = $this->getAppservice()->findAppCount();
        $apps = $this->getAppservice()->findApps(0, $appCount);

        return $this->render('admin-v2/developer/developer-setting/version.html.twig', [
            'apps' => $apps,
        ]);
    }

    public function magicAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $setting = $request->request->get('setting', '{}');
            $setting = json_decode($setting, true);

            if (empty($setting)) {
                $setting = ['export_allow_count' => 100000, 'export_limit' => 10000, 'enable_org' => 0];
            }

            $this->getSettingService()->set('magic', $setting);
            $this->getLogService()->info('system', 'update_settings', '更新Magic设置', $setting);
            $this->setFlashMessage('success', 'site.save.success');
        }

        $setting = $this->getSettingService()->get('magic', []);
        $setting = JsonToolkit::prettyPrint(json_encode($setting));

        return $this->render('admin-v2/developer/developer-setting/magic.html.twig', [
            'setting' => $setting,
        ]);
    }

    private function openDevModeIfDebugEnable($developerSetting)
    {
        try {
            $fileSystem = new Filesystem();
            $devLockFile = $this->container->getParameter('kernel.root_dir').'/data/dev.lock';
            $ignoreDeleteDevLockFile = $this->container->getParameter('kernel.root_dir').'/data/ignoreDeleteDevLock';
            if ($developerSetting['debug']) {
                $fileSystem->touch($devLockFile);
            } else {
                if (!$fileSystem->exists($ignoreDeleteDevLockFile)) {
                    $fileSystem->remove($devLockFile);
                }
            }
        } catch (\Exception $e) {
            //可能线上环境的dev.lock被人加过，导致权限问题无法删除
            //所以，捕获异常，对于这种情况，不处理
        }
    }

    /**
     * @return ActivityService
     */
    protected function getActivityService()
    {
        return $this->createService('Activity:ActivityService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->createService('Task:TaskService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return AppService
     */
    protected function getAppService()
    {
        return $this->createService('CloudPlatform:AppService');
    }

    /**
     * @return CloudFileService
     */
    protected function getCloudFileService()
    {
        return $this->createService('CloudFile:CloudFileService');
    }

    /**
     * @return UploadFileService
     */
    protected function getUploadFileService()
    {
        return $this->createService('File:UploadFileService');
    }
}
