<?php

namespace ESCloud\SDK;

use ESCloud\SDK\HttpClient\ClientInterface;
use ESCloud\SDK\Service\AIFaceService;
use ESCloud\SDK\Service\AIService;
use ESCloud\SDK\Service\DrpService;
use ESCloud\SDK\Service\ESopService;
use ESCloud\SDK\Service\InspectionService;
use ESCloud\SDK\Service\MobileService;
use ESCloud\SDK\Service\MpService;
use ESCloud\SDK\Service\NotificationService;
use ESCloud\SDK\Service\PlatformNewsService;
use ESCloud\SDK\Service\PlayService;
use ESCloud\SDK\Service\PushService;
use ESCloud\SDK\Service\ResourceService;
use ESCloud\SDK\Service\ScrmService;
use ESCloud\SDK\Service\SearchService;
use ESCloud\SDK\Service\SmsService;
use ESCloud\SDK\Service\WeChatService;
use ESCloud\SDK\Service\XAPIService;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class ESCloudSDK
{
    protected $options;

    protected $services = array();

    protected $auth;

    protected $logger;

    protected $httpClient;

    /**
     * ESCloudSDK constructor.
     *
     * @param array $options
     * @param LoggerInterface|null $logger
     * @param ClientInterface|null $httpClient
     * @throws InvalidArgumentException
     */
    public function __construct(array $options, LoggerInterface $logger = null, ClientInterface $httpClient = null)
    {
        if (empty($options['access_key'])) {
            throw new InvalidArgumentException('`access_key` param is missing.');
        }
        if (empty($options['secret_key'])) {
            throw new InvalidArgumentException('`secret_key` param is missing.');
        }

        $this->options = $options;
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    /**
     * 获取云资源播放服务
     *
     * @return ResourceService
     */
    public function getResourceService()
    {
        return $this->getService('Resource', true);
    }

    /**
     * 获取短信服务
     *
     * @return SmsService
     */
    public function getSmsService()
    {
        return $this->getService('Sms');
    }

    /**
     * 获取云资源播放服务
     *
     * @return PlayService
     */
    public function getPlayService()
    {
        return $this->getService('Play');
    }

    /**
     * 获取XAPI服务
     *
     * @return XAPIService
     */
    public function getXAPIService()
    {
        return $this->getService('XAPI');
    }

    /**
     * 获取分销服务
     *
     * @return DrpService
     */
    public function getDrpService()
    {
        return $this->getService('Drp');
    }

    /**
     * @return MpService
     */
    public function getMpService()
    {
        return $this->getService('Mp');
    }

    /**
     * @return ESopService
     */
    public function getESopService()
    {
        return $this->getService('ESop');
    }

    /**
     * @return AIService
     */
    public function getAIService()
    {
        return $this->getService('AI', true);
    }

    /**
     * @return AIFaceService
     */
    public function getAIFaceService()
    {
        return $this->getService('AIFace');
    }

    /**
     * @return PushService
     */
    public function getPushService()
    {
        return $this->getService('Push');
    }

    /**
     * @return NotificationService
     */
    public function getNotificationService()
    {
        return $this->getService('Notification');
    }

    /**
     * @return WeChatService
     */
    public function getWeChatService()
    {
        return $this->getService('WeChat');
    }

    /**
     * @return InspectionService
     */
    public function getInspectionService()
    {
        return $this->getService('Inspection', true);
    }

    /**
     * @return MobileService
     */
    public function getMobileService()
    {
        return $this->getService('Mobile');
    }

    /**
     * @return ScrmService
     */
    public function getScrmService()
    {
        return $this->getService('Scrm');
    }

    /**
     * @return SearchService
     */
    public function getSearchService()
    {
        return $this->getService('Search', true);
    }

    /**
     * @return PlatformNewsService
     */
    public function getPlatformNewsService()
    {
        return $this->getService('PlatformNews');
    }

    /**
     * 根据服务名获得服务实例
     *
     * @param string $name 服务名
     *
     * @return mixed 服务实例
     */
    protected function getService($name, $useJwt = false)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        $lowerName = strtolower($name);
        $options = empty($this->options['service'][$lowerName]) ? array() : $this->options['service'][$lowerName];

        $class = __NAMESPACE__ . '\\Service\\' . $name . 'Service';
        $auth = new Auth($this->options['access_key'], $this->options['secret_key'], $useJwt);
        $this->services[$name] = new $class($auth, $options, $this->logger, $this->httpClient);

        return $this->services[$name];
    }
}
