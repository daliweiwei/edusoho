<?php

namespace AppBundle\Common;

use Biz\Content\Service\BlockService;
use Biz\Content\Service\ContentService;
use Biz\Content\Service\FileService;
use Biz\Content\Service\NavigationService;
use Biz\Crontab\SystemCrontabInitializer;
use Biz\Org\Service\OrgService;
use Biz\Role\Service\RoleService;
use Biz\System\Service\SettingService;
use Biz\Taxonomy\Service\CategoryService;
use Biz\Taxonomy\Service\TagService;
use Biz\User\CurrentUser;
use Biz\User\Service\UserService;
use Codeages\Biz\Pay\Service\AccountService;
use CustomBundle\Biz\Common\CustomSystemInitializer;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Topxia\Service\Common\ServiceKernel;

class SystemInitializer
{
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function init()
    {
        $this->_initTag();
        $this->_initCategory();
        $this->_initFile();
        $this->_initPages();
        $this->_initNavigations();
        $this->_initBlocks();
        $this->_initJob();
        $this->_initQueueJob();
        $this->_initOrg();
        $this->_initRole();
        $this->_initUserBalance();

        $this->initSettings();
        $this->_initQuestionBankCategory();
        $this->_initSystemUsers();
        $this->_initCustom();
    }

    public function initSettings()
    {
        $this->_initThemesSetting();
        $this->_initWapSetting();
        $this->_initPostNumRulesSetting();
        $this->_initDeveloperSetting();
        $this->_initBackstageSetting();
        $this->_initAppDiscoverySetting();
        $this->_initArticleSetting();
        $this->_initCoinSetting();
        $this->_initDefaultSetting();
        $this->_initMagicSetting();
        $this->_initMailerSetting();
        $this->_initConsultSetting();
        $this->_initPaymentSetting();
        $this->_initRefundSetting();
        $this->_initSiteSetting();
        $this->_initStorageSetting();
        $this->_initCouponSetting();
        $this->_initCloudSmsSetting();
        $this->_initUgcContentAuditSetting();
    }

    public function _initCustom()
    {
        try {
            $biz = ServiceKernel::instance()->getBiz();
            $customSystemInitializer = new CustomSystemInitializer($biz, $this->output);
            $customSystemInitializer->init();
        } catch (\Exception $e) {
            $this->output->write('  定制初始化的数据异常'.$e->getMessage());
        }
    }

    public function initAdminUser($fields)
    {
        $this->output->write("  创建管理员帐号:{$fields['email']}, 密码：{$fields['password']}   ");
        $fields['emailVerified'] = 1;

        $user = $this->getUserService()->getUserByEmail($fields['email']);

        if (empty($user)) {
            $user = $this->getUserService()->register($fields);
        }

        $user['roles'] = ['ROLE_USER', 'ROLE_TEACHER', 'ROLE_SUPER_ADMIN'];
        $user['currentIp'] = '127.0.0.1';

        $currentUser = new CurrentUser();
        $currentUser->fromArray($user);
        ServiceKernel::instance()->setCurrentUser($currentUser);

        $this->getUserService()->changeUserRoles($user['id'], ['ROLE_USER', 'ROLE_TEACHER', 'ROLE_SUPER_ADMIN']);

        $this->output->writeln(' ...<info>成功</info>');

        return $this->getUserService()->getUser($user['id']);
    }

    private function _initMailerSetting()
    {
        $this->output->write('  初始化邮件服务器设置');

        $default = [
            'enabled' => 0,
            'host' => 'smtp.exmail.qq.com',
            'port' => '25',
            'username' => 'user@example.com',
            'password' => '',
            'from' => 'user@example.com',
            'name' => '',
        ];
        $this->getSettingService()->set('mailer', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initMagicSetting()
    {
        $this->output->write('  初始化magic设置');
        $default = [
            'export_allow_count' => 100000,
            'export_limit' => 10000,
            'enable_org' => 0,
        ];

        $this->getSettingService()->set('magic', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initConsultSetting()
    {
        $this->output->write('  初始化客服设置');

        $default = [
            'enabled' => 0,
            'worktime' => '9:00 - 17:00',
            'qq' => [
                ['name' => '', 'number' => ''],
            ],
            'qqgroup' => [
                ['name' => '', 'number' => ''],
            ],
            'phone' => [
                ['name' => '', 'number' => ''],
            ],
            'webchatURI' => '',
            'email' => '',
            'color' => 'default',
        ];

        $this->getSettingService()->set('contact', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initRefundSetting()
    {
        $this->output->write('  初始化退款设置');

        $setting = [
            'maxRefundDays' => 10,
            'applyNotification' => '您好，您退款的{{item}}，管理员已收到您的退款申请，请耐心等待退款审核结果。',
            'successNotification' => '您好，您申请退款的{{item}} 审核通过，将为您退款{{amount}}元。',
            'failedNotification' => '您好，您申请退款的{{item}} 审核未通过，请与管理员再协商解决纠纷。',
        ];
        $this->getSettingService()->set('refund', $setting);
        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initSiteSetting()
    {
        $this->output->write('  初始化站点设置');

        $default = [
            'name' => 'EDUSOHO测试站',
            'slogan' => '强大的在线教育解决方案',
            'url' => 'http://demo.edusoho.com',
            'logo' => '',
            'seo_keywords' => 'edusoho, 在线教育软件, 在线在线教育解决方案',
            'seo_description' => 'edusoho是强大的在线教育开源软件',
            'master_email' => 'test@edusoho.com',
            'icp' => ' 浙ICP备13006852号-1',
            'analytics' => '',
            'status' => 'open',
            'closed_note' => '',
        ];

        $this->getSettingService()->set('site', $default);
        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initPaymentSetting()
    {
        $this->output->write('  初始化支付设置');

        $default = [
            'enabled' => 0,
            'bank_gateway' => 'none',
            'alipay_enabled' => 0,
            'alipay_key' => '',
            'alipay_accessKey' => '',
            'alipay_secretKey' => '',
        ];

        $this->getSettingService()->set('payment', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initDefaultSetting()
    {
        $this->output->write('  初始化章节的默认设置');
        $settingService = $this->getSettingService();

        $defaultSetting = [
            'user_name' => '学员',
            'chapter_name' => '章',
            'part_name' => '节',
        ];

        $default = $settingService->get('default', []);
        $defaultSetting = array_merge($default, $defaultSetting);

        $settingService->set('default', $defaultSetting);
    }

    private function _initStorageSetting()
    {
        $this->output->write('  初始化云服务器设置');

        $default = [
            'upload_mode' => 'local',
            'cloud_api_server' => 'http://api.edusoho.net',
            'cloud_access_key' => '',
            'cloud_secret_key' => '',
            'video_h5_enable' => 1,

            'enable_playback_rates' => 0,
        ];

        $this->getSettingService()->set('storage', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initCouponSetting()
    {
        $this->output->write('  初始化优惠码设置');

        $default = [
            'enabled' => 1,
        ];

        $this->getSettingService()->set('coupon', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initCloudSmsSetting()
    {
        $this->output->write('  初始化短信设置');

        $default = [
            'system_remind' => 'on',
        ];

        $this->getSettingService()->set('cloud_sms', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    private function _initUgcContentAuditSetting()
    {
        $this->output->write('  初始化放刷弹窗设置');

        $default = [
            'enable_anti_brush_captcha' => '1',
        ];

        $this->getSettingService()->set('ugc_content_audit', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    public function initRegisterSetting($user)
    {
        $this->output->write('  初始化注册设置');

        $emailBody = <<<'EOD'
Hi, {{nickname}}

欢迎加入{{sitename}}!

请点击下面的链接完成注册：

{{verifyurl}}

如果以上链接无法点击，请将上面的地址复制到你的浏览器(如IE)的地址栏中打开，该链接地址24小时内打开有效。

感谢对{{sitename}}的支持！

{{sitename}} {{siteurl}}

(这是一封自动产生的email，请勿回复。)
EOD;

        $default = [
            'register_mode' => 'email',
            'email_activation_title' => '请激活您的{{sitename}}帐号',
            'email_activation_body' => trim($emailBody),
            'welcome_enabled' => 'opened',
            'welcome_sender' => $user['nickname'],
            'welcome_methods' => [],
            'welcome_title' => '欢迎加入{{sitename}}',
            'welcome_body' => '您好{{nickname}}，我是{{sitename}}的管理员，欢迎加入{{sitename}}，祝您学习愉快。如有问题，随时与我联系。',
        ];

        $this->getSettingService()->set('auth', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initTag()
    {
        $this->output->write('  初始化标签');
        $defaultTag = $this->getTagService()->getTagByName('默认标签');

        if (!$defaultTag) {
            $this->getTagService()->addTag(['name' => '默认标签']);
        }

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initCategory()
    {
        $this->output->write('  初始化分类分组');

        $courseGroup = $this->getCategoryService()->getGroupByCode('course');

        if (empty($courseGroup)) {
            $courseGroup = $this->getCategoryService()->addGroup([
                'name' => '课程分类',
                'code' => 'course',
                'depth' => 3,
            ]);
        }

        $courseCategory = $this->getCategoryService()->getCategoryByCode('default');

        if (empty($courseCategory)) {
            $this->getCategoryService()->createCategory([
                'name' => '默认分类',
                'code' => 'default',
                'weight' => 100,
                'groupId' => $courseGroup['id'],
                'parentId' => 0,
            ]);
        }

        $classroomGroup = $this->getCategoryService()->getGroupByCode('classroom');

        if (!$classroomGroup) {
            $classroomGroup = $this->getCategoryService()->addGroup([
                'name' => '班级分类',
                'code' => 'classroom',
                'depth' => 3,
            ]);
        }

        $classroomCategory = $this->getCategoryService()->getCategoryByCode('classroomdefault');

        if (!$classroomCategory) {
            $this->getCategoryService()->createCategory([
                'name' => '默认分类',
                'code' => 'classroomdefault',
                'weight' => 100,
                'groupId' => $classroomGroup['id'],
                'parentId' => 0,
            ]);
        }

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initFile()
    {
        $this->output->write('  初始化文件分组');

        $groups = $this->getFileService()->getAllFileGroups();

        foreach ($groups as $group) {
            $this->getFileService()->deleteFileGroup($group['id']);
        }

        $this->getFileService()->addFileGroup([
            'name' => '默认文件组',
            'code' => 'default',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '缩略图',
            'code' => 'thumb',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '课程',
            'code' => 'course',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '用户',
            'code' => 'user',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '课程私有文件',
            'code' => 'course_private',
            'public' => 0,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '资讯',
            'code' => 'article',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '临时目录',
            'code' => 'tmp',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '全局设置文件',
            'code' => 'system',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '小组',
            'code' => 'group',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '编辑区',
            'code' => 'block',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '班级',
            'code' => 'classroom',
            'public' => 1,
        ]);

        $this->getFileService()->addFileGroup([
            'name' => '题目',
            'code' => 'question',
            'public' => 1,
        ]);

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initPages()
    {
        $this->getContentService()->createContent([
            'title' => '关于我们',
            'type' => 'page',
            'alias' => 'aboutus',
            'body' => '',
            'template' => 'default',
            'status' => 'published',
        ]);

        $this->getContentService()->createContent([
            'title' => '常见问题',
            'type' => 'page',
            'alias' => 'questions',
            'body' => '',
            'template' => 'default',
            'status' => 'published',
        ]);
    }

    protected function _initCoinSetting()
    {
        $this->output->write('  初始化虚拟币');

        $default = [
            'coin_enabled' => 0,
            'cash_model' => 'none',
            'cash_rate' => 1,
            'coin_name' => '虚拟币',
            'coin_content' => '',
            'coin_picture' => '',
            'coin_picture_50_50' => '',
            'coin_picture_30_30' => '',
            'coin_picture_20_20' => '',
            'coin_picture_10_10' => '',
            'charge_coin_enabled' => '',
        ];

        $this->getSettingService()->set('coin', $default);

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initNavigations()
    {
        $this->output->write('  初始化导航');

        $this->getNavigationService()->createNavigation([
            'name' => '师资力量',
            'url' => 'teacher',
            'sequence' => 1,
            'isNewWin' => 0,
            'isOpen' => 1,
            'type' => 'top',
        ]);

        $this->getNavigationService()->createNavigation([
            'name' => '常见问题',
            'url' => 'page/questions',
            'sequence' => 2,
            'isNewWin' => 0,
            'isOpen' => 1,
            'type' => 'top',
        ]);

        $this->getNavigationService()->createNavigation([
            'name' => '关于我们',
            'url' => 'page/aboutus',
            'sequence' => 2,
            'isNewWin' => 0,
            'isOpen' => 1,
            'type' => 'top',
        ]);

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initThemesSetting()
    {
        $this->getSettingService()->set('theme', [
            'uri' => 'jianmo',
        ]);
    }

    protected function _initWapSetting()
    {
        $this->getSettingService()->set('wap', [
            'version' => '2',
            'template' => 'sail',
        ]);
    }

    protected function _initPostNumRulesSetting()
    {
        $setting = [
            'rules' => [
                'thread' => [
                    'fiveMuniteRule' => [
                        'interval' => 300,
                        'postNum' => 100,
                    ],
                ],
                'threadLoginedUser' => [
                    'fiveMuniteRule' => [
                        'interval' => 300,
                        'postNum' => 50,
                    ],
                ],
            ],
        ];
        $this->getSettingService()->set('post_num_rules', $setting);
    }

    protected function _initDeveloperSetting()
    {
        $this->getSettingService()->get('developer', []);
        $developer['cloud_api_failover'] = 1;
        $this->getSettingService()->set('developer', $developer);
    }

    protected function _initBackstageSetting()
    {
        $this->getSettingService()->set('backstage', [
            'is_v2' => 1,
            'allow_show_switch_btn' => 0,
        ]);
    }

    protected function _initAppDiscoverySetting()
    {
        $this->getSettingService()->set('app_discovery', ['version' => 1]);
    }

    protected function _initArticleSetting()
    {
        $this->getSettingService()->set('article', [
            'name' => '资讯频道', 'pageNums' => 20,
        ]);
    }

    protected function _initBlocks()
    {
        $themeDir = ServiceKernel::instance()->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'../web/themes';
        $this->output->write('  初始化编辑区');

        $metaFiles = [
            'system' => "{$themeDir}/block.json",
            'default' => "{$themeDir}/default/block.json",
            'autumn' => "{$themeDir}/autumn/block.json",
            'jianmo' => "{$themeDir}/jianmo/block.json",
        ];

        foreach ($metaFiles as $category => $file) {
            $metas = file_get_contents($file);
            $metas = json_decode($metas, true);

            foreach ($metas as $code => $meta) {
                $data = [];

                foreach ($meta['items'] as $key => $item) {
                    $data[$key] = $item['default'];
                }

                $filename = __DIR__.'/blocks/'.'block-'.md5($code).'.html';

                if (file_exists($filename)) {
                    $content = file_get_contents($filename);
                    $content = preg_replace_callback('/(<img[^>]+>)/i', function ($matches) {
                        preg_match_all('/<\s*img[^>]*src\s*=\s*["\']?([^"\']*)/is', $matches[0], $srcs);
                        preg_match_all('/<\s*img[^>]*alt\s*=\s*["\']?([^"\']*)/is', $matches[0], $alts);
                        $URI = preg_replace('/'.INSTALL_URI.'.*/i', '', $_SERVER['REQUEST_URI']);
                        $src = preg_replace('/\b\?[\d]+.[\d]+.[\d]+/i', '', $srcs[1][0]);
                        $src = $URI.trim($src);

                        $img = "<img src='{$src}'";

                        if (isset($alts[1][0])) {
                            $alt = $alts[1][0];
                            $img .= " alt='{$alt}'>";
                        } else {
                            $img .= '>';
                        }

                        return $img;
                    }, $content);
                } else {
                    $content = '';
                }
                $blockTemplate = $this->getBlockService()->getBlockTemplateByCode($code);
                if (empty($blockTemplate)) {
                    $blockTemplate = $this->getBlockService()->createBlockTemplate([
                        'title' => $meta['title'],
                        'mode' => 'template',
                        'templateName' => $meta['templateName'],
                        'content' => $content,
                        'code' => $code,
                        'meta' => $meta,
                        'data' => $data,
                        'category' => $category,
                    ]);
                } else {
                    $blockTemplate = $this->getBlockService()->updateBlockTemplate($blockTemplate['id'], [
                        'mode' => 'template',
                        'category' => empty($meta['category']) ? 'system' : $meta['category'],
                        'meta' => $meta,
                        'data' => $data,
                        'content' => $content,
                        'templateName' => $meta['templateName'],
                        'title' => $meta['title'],
                    ]);
                }

                $block = $this->getBlockService()->getBlockByCode($code);
                if (empty($block)) {
                    $this->getBlockService()->createBlock([
                        'blockTemplateId' => $blockTemplate['id'],
                        'code' => $code,
                        'content' => $content,
                        'data' => $data,
                    ]);
                } else {
                    $this->getBlockService()->updateBlockTemplate($block['blockTemplateId'], [
                        'content' => $content,
                        'data' => $data,
                    ]);
                }
            }
        }

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initJob()
    {
        $this->output->write('  初始化CrontabJob');

        SystemCrontabInitializer::init();

        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initQueueJob()
    {
        $this->output->write('  DataBase消息队列初始化');
        try {
            SystemQueueCrontabinitializer::init();
            $this->output->writeln(' ...<info>成功</info>');
        } catch (\Exception $e) {
            $this->output->writeln(' ...<info>失败</info>'.$e->getMessage());
        }
    }

    protected function _initSystemUsers()
    {
        $this->getUserService()->initSystemUsers();
    }

    protected function _initOrg()
    {
        $org = $this->getOrgService()->getOrgByCode('FullSite');

        if (!empty($org)) {
            return;
        }

        $org = [
            'name' => '全站',
            'code' => 'FullSite',
        ];

        $this->getOrgService()->createOrg($org);
    }

    public function initFolders()
    {
        $folders = [
            ServiceKernel::instance()->getParameter('kernel.root_dir').'/data/udisk',
            ServiceKernel::instance()->getParameter('kernel.root_dir').'/data/private_files',
            ServiceKernel::instance()->getParameter('kernel.root_dir').'/../web/files',
        ];

        $filesystem = new Filesystem();

        foreach ($folders as $folder) {
            if (!$filesystem->exists($folder)) {
                $filesystem->mkdir($folder);
            }
        }
    }

    protected function _initRole()
    {
        $this->output->write('  初始化角色');
        $this->getRoleService()->refreshRoles();
        $this->output->writeln(' ...<info>成功</info>');
    }

    protected function _initQuestionBankCategory()
    {
        $this->output->write('  初始化题库默认分类');
        $this->getQuestionBankCategoryService()->createCategory(['name' => '默认分类', 'parentId' => 0]);
        $this->output->writeln(' ...<info>成功</info>');
    }

    public function initLockFile()
    {
        $this->output->write('  初始化install.lock');
        touch(ServiceKernel::instance()->getParameter('kernel.root_dir').'/data/install.lock');
        touch(ServiceKernel::instance()->getParameter('kernel.root_dir').'/config/routing_plugins.yml');

        $this->output->writeln(' ...<info>成功</info>');
    }

    /**
     * 创建系统用户
     */
    private function _initUserBalance()
    {
        $this->getAccountService()->createUserBalance(['user_id' => 0]);
    }

    /**
     * @return TagService
     */
    protected function getTagService()
    {
        return ServiceKernel::instance()->getBiz()->service('Taxonomy:TagService');
    }

    /**
     * @return AccountService
     */
    protected function getAccountService()
    {
        return ServiceKernel::instance()->getBiz()->service('Pay:AccountService');
    }

    /**
     * @return CategoryService
     */
    protected function getCategoryService()
    {
        return ServiceKernel::instance()->getBiz()->service('Taxonomy:CategoryService');
    }

    /**
     * @return UserService
     */
    private function getUserService()
    {
        return ServiceKernel::instance()->getBiz()->service('User:UserService');
    }

    /**
     * @return SettingService
     */
    private function getSettingService()
    {
        return ServiceKernel::instance()->getBiz()->service('System:SettingService');
    }

    /**
     * @return FileService
     */
    private function getFileService()
    {
        return ServiceKernel::instance()->getBiz()->service('Content:FileService');
    }

    /**
     * @return ContentService
     */
    protected function getContentService()
    {
        return ServiceKernel::instance()->getBiz()->service('Content:ContentService');
    }

    /**
     * @return BlockService
     */
    protected function getBlockService()
    {
        return ServiceKernel::instance()->getBiz()->service('Content:BlockService');
    }

    /**
     * @return NavigationService
     */
    protected function getNavigationService()
    {
        return ServiceKernel::instance()->getBiz()->service('Content:NavigationService');
    }

    /**
     * @return OrgService
     */
    protected function getOrgService()
    {
        return ServiceKernel::instance()->getBiz()->service('Org:OrgService');
    }

    /**
     * @return RoleService
     */
    protected function getRoleService()
    {
        return ServiceKernel::instance()->getBiz()->service('Role:RoleService');
    }

    /**
     * @return \Biz\QuestionBank\Service\CategoryService
     */
    protected function getQuestionBankCategoryService()
    {
        return ServiceKernel::instance()->getBiz()->service('QuestionBank:CategoryService');
    }
}
