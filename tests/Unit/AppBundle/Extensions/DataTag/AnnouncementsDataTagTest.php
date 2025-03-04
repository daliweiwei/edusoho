<?php

namespace Tests\Unit\AppBundle\Extensions\DataTag;

use AppBundle\Extensions\DataTag\AnnouncementsDataTag;
use Biz\Announcement\Service\AnnouncementService;
use Biz\BaseTestCase;

class AnnouncementsDataTagTest extends BaseTestCase
{
    public function testGetData()
    {
        $this->getAnnouncementService()->createAnnouncement([
            'content' => 'Announcement1',
            'url' => 'http://',
            'userId' => '1',
            'startTime' => time() - 3600,
            'endTime' => time() + 3600 * 2,
            'targetType' => 'course',
            'targetId' => '1',
        ]);

        $this->getAnnouncementService()->createAnnouncement([
            'content' => 'Announcement2',
            'url' => 'http://',
            'userId' => '1',
            'startTime' => time() - 3600,
            'endTime' => time() + 3600 * 2,
            'targetType' => 'course',
            'targetId' => '1',
        ]);

        $this->getAnnouncementService()->createAnnouncement([
            'content' => 'Announcement3',
            'url' => 'http://',
            'userId' => '1',
            'startTime' => time() - 3600,
            'endTime' => time() + 3600 * 2,
            'targetType' => 'classroom',
            'targetId' => '1',
        ]);

        $this->getAnnouncementService()->createAnnouncement([
            'content' => 'Announcement4',
            'url' => 'http://',
            'userId' => '1',
            'startTime' => time() - 3600,
            'endTime' => time() + 3600 * 2,
            'targetType' => 'classroom',
            'targetId' => '1',
        ]);

        $this->getAnnouncementService()->createAnnouncement([
            'content' => 'Announcement5',
            'url' => 'http://',
            'userId' => '1',
            'startTime' => time() - 3600,
            'endTime' => time() + 3600 * 2,
            'targetType' => 'global',
            'targetId' => '1',
        ]);

        $dataTag = new AnnouncementsDataTag();
        $announcement = $dataTag->getData(['count' => '5', 'targetType' => 'course', 'targetId' => 1]);
        $this->assertEquals(2, count($announcement));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage count参数缺失
     */
    public function testEmptyCount()
    {
        $dataTag = new AnnouncementsDataTag();
        $announcement = $dataTag->getData(['targetType' => 'course', 'targetId' => 1]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage count参数超出最大取值范围
     */
    public function testCountGT100()
    {
        $dataTag = new AnnouncementsDataTag();
        $announcement = $dataTag->getData(['count' => 101, 'targetType' => 'course', 'targetId' => 1]);
    }

    /**
     * @return AnnouncementService
     */
    private function getAnnouncementService()
    {
        return $this->createService('Announcement:AnnouncementService');
    }
}
