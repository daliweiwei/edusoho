<?php

namespace AppBundle\Command;

use AppBundle\Common\BlockToolkit;
use AppBundle\Common\SystemInitializer;
use Biz\Crontab\SystemCrontabInitializer;
use Biz\User\CurrentUser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Topxia\Service\Common\ServiceKernel;

class InitWebsiteCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('util:init-website')
            ->addArgument('accessKey', InputArgument::REQUIRED, 'accessKey')
            ->addArgument('secretKey', InputArgument::REQUIRED, 'secretKey')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('siteName', InputArgument::REQUIRED, 'siteName')
            ->setDescription('用于初始化edusoho');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //获取数据库连接配置
        $this->getDbParamters();

        $this->logger('开始建校', $output);
        $result = $this->initDb();
        if (true === $result) {
            $this->logger('数据库创建成功', $output);
        } else {
            $this->logger($result, $output);

            return;
        }

        $accessKey = $input->getArgument('accessKey');
        $secretKey = $input->getArgument('secretKey');
        $siteName = $input->getArgument('siteName');

        $this->initServiceKernel();

        $initializer = new SystemInitializer($output);
        if (!$this->hasUsedFiles()) {
            $initializer->init();
            $this->initTopBanner();
            $setting = [
                'auth' => ['register_mode' => 'email'],
                'storage' => ['upload_mode' => 'cloud',
                                   'cloud_access_key' => $accessKey,
                                   'cloud_secret_key' => $secretKey,
                                   'cloud_key_applied' => 1,
                ],
                'site' => ['name' => $siteName],
            ];
        } else {
            //生成演示数据
            $result = $this->initUsedData();
            $this->logger($result, $output);
            $initializer->initSettings();
            //将演示数据中的用户角色降为教师
            $this->updateUsedUserData();
            $setting = [
                'storage' => ['upload_mode' => 'cloud',
                                   'cloud_access_key' => $accessKey,
                                   'cloud_secret_key' => $secretKey,
                                   'cloud_key_applied' => 1,
                ],
                'site' => ['name' => $siteName],
            ];
        }

        $this->initSetting($setting);
        $this->logger('网校设置授权成功', $output);

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $user = ['username' => $username, 'password' => $password, 'email' => $email];
        $user = $this->initUser($user);
        $this->logger('网校设置用户成功', $output);

        $initializer->initRegisterSetting($user);
        $initializer->initFolders();
        $initializer->initLockFile();
        SystemCrontabInitializer::init();

        $this->logger('网校创建成功', $output);
    }

    protected function getDbParamters()
    {
        $parameters = file_get_contents(__DIR__.'/../../../app/config/parameters.yml');
        $parameters = \Symfony\Component\Yaml\Yaml::parse($parameters);
        $parameters = $parameters['parameters'];

        $this->db_host = $parameters['database_host'];
        $this->db_port = $parameters['database_port'];
        $this->db_user = $parameters['database_user'];
        $this->db_password = $parameters['database_password'];
        $this->db_name = $parameters['database_name'];
    }

    private function logger($message, $output)
    {
        $time = date('Y-m-d H:i:s');
        $log = "{$time}, {$message}";
        $loggerFile = $this->getContainer()->getParameter('kernel.root_dir').'/logs/edusoho-init.log';
        file_put_contents($loggerFile, $log.PHP_EOL, FILE_APPEND);
        $output->writeln($log);
    }

    private function initUser($user)
    {
        $registerUser = [
            'nickname' => $user['username'],
            'email' => $user['email'],
            'password' => $user['password'],
        ];
        $registerUser = $this->getAuthService()->register($registerUser);

        return $this->getUserService()->changeUserRoles($registerUser['id'], [
            'ROLE_USER',
            'ROLE_TEACHER',
            'ROLE_SUPER_ADMIN',
        ]);
    }

    private function initSetting($data)
    {
        foreach ($data as $key => $value) {
            $originValue = $this->getSettingService()->get($key, []);
            $value = array_merge($originValue, $value);
            $this->getSettingService()->set($key, $value);
        }
    }

    protected function initServiceKernel()
    {
        $serviceKernel = ServiceKernel::create('dev', true);
        $currentUser = new CurrentUser();
        $currentUser->fromArray([
            'id' => 0,
            'nickname' => '游客',
            'currentIp' => '127.0.0.1',
            'roles' => ['ROLE_SUPER_ADMIN'],
            'orgId' => 1,
        ]);
        $serviceKernel->setCurrentUser($currentUser);
    }

    protected function initDb()
    {
        try {
            $pdo = $this->getDb();
            $checkTableResult = $pdo->query('show tables;');
            foreach ($checkTableResult as $i) {
                return '创建数据库表结构失败，数据库内已存在表结构，请删除数据库后重试';
            }
            $sqlFile = $this->getContainer()->getParameter('kernel.root_dir').'/../web/install/edusoho.sql';
            $sql = file_get_contents($sqlFile);
            $result = $pdo->exec($sql);
            $pdo = null;
            if (false === $result) {
                return '创建数据库表结构失败，请删除数据库后重试';
            }

            return true;
        } catch (\PDOException $e) {
            return '数据库连接错误';
        }
    }

    protected function initTopBanner()
    {
        $code = 'jianmo:home_top_banner';
        $blockTemplate = $this->getBlockService()->getBlockTemplateByCode($code);
        $html = BlockToolkit::render($blockTemplate, $this->getContainer());
        $fields = [
            'data' => $blockTemplate['data'],
            'content' => $html,
            'userId' => 1,
            'blockTemplateId' => $blockTemplate['id'],
            'code' => $code,
            'mode' => $blockTemplate['mode'],
        ];
        $this->getBlockService()->createBlock($fields);
    }

    protected function hasUsedFiles()
    {
        $sqlFile = $this->getContainer()->getParameter('kernel.root_dir').'/../web/install/edusoho_init_*.sql';
        $files = glob($sqlFile);

        if ($files) {
            return true;
        }

        return false;
    }

    protected function initUsedData()
    {
        $sqlFile = $this->getContainer()->getParameter('kernel.root_dir').'/../web/install/edusoho_init_*.sql';
        $files = glob($sqlFile);
        if ($files) {
            $db = $this->getDb();

            foreach ($files as $file) {
                $sql = file_get_contents($file);
                $result = $db->exec($sql);
            }

            return '创建演示数据成功';
        } else {
            return '无演示数据脚本文件';
        }
    }

    protected function updateUsedUserData()
    {
        $this->getUserService()->changeUserRoles(1, [
            'ROLE_USER',
            'ROLE_TEACHER',
        ]);
    }

    protected function getDb()
    {
        $pdo = new \PDO("mysql:host={$this->db_host};port={$this->db_port}", "{$this->db_user}", "{$this->db_password}");
        $pdo->exec('SET NAMES utf8');
        $pdo->exec("USE `{$this->db_name}`;");

        return $pdo;
    }

    protected function getSettingService()
    {
        return $this->getServiceKernel()->createService('System:SettingService');
    }

    protected function getAuthService()
    {
        return $this->getServiceKernel()->createService('User:AuthService');
    }

    protected function getUserService()
    {
        return $this->getServiceKernel()->createService('User:UserService');
    }

    protected function getBlockService()
    {
        return $this->getServiceKernel()->createService('Content:BlockService');
    }
}
