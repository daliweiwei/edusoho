<?php

namespace ApiBundle\Api\Resource\App;

use ApiBundle\Api\Annotation\ApiConf;
use ApiBundle\Api\ApiRequest;
use ApiBundle\Api\Resource\AbstractResource;
use Biz\Course\Service\CourseSetService;
use Biz\DiscoveryColumn\Service\DiscoveryColumnService;

class AppChannel extends AbstractResource
{
    const DEFAULT_DISPLAY_COUNT = 6;

    /**
     * @ApiConf(isRequiredAuth=false)
     */
    public function search(ApiRequest $request)
    {
        $channel = $this->getDiscoveryColumnService()->getDisplayData();

        if (!$channel) {
            return $this->getDefaultChannel();
        }

        return $channel;
    }

    private function getDefaultChannel()
    {
        $conditions = [
            'status' => 'published',
            'parentId' => 0,
            'type' => 'normal',
        ];
        $latestCourseSets = $courseSets = $this->getCourseSetService()->searchCourseSets(
            $conditions,
            ['createdTime' => 'DESC'],
            0,
            self::DEFAULT_DISPLAY_COUNT
        );

        $defaultChannel = [
            [
                'title' => '最新课程',
                'type' => 'course',
                'categoryId' => 0,
                'orderType' => 'new',
                'showCount' => self::DEFAULT_DISPLAY_COUNT,
                'data' => $latestCourseSets,
                'actualCount' => self::DEFAULT_DISPLAY_COUNT,
            ],
        ];

        return $defaultChannel;
    }

    /**
     * @return CourseSetService
     */
    private function getCourseSetService()
    {
        return $this->service('Course:CourseSetService');
    }

    /**
     * @return DiscoveryColumnService
     */
    private function getDiscoveryColumnService()
    {
        return $this->service('DiscoveryColumn:DiscoveryColumnService');
    }
}
