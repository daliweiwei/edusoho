<?php

namespace Tests\Unit\Activity\Dao;

use Tests\Unit\Base\BaseDaoTestCase;

class ActivityDaoTest extends BaseDaoTestCase
{
    public function testSearch()
    {
        $activity1 = $this->mockActivity(['title' => 'activity 1']);
        $activity2 = $this->mockActivity(['title' => 'activity 2', 'mediaType' => 'video']);

        $testConditons = [
            [
                'condition' => ['fromCourseId' => 1],
                'expectedResults' => [$activity1, $activity2],
                'expectedCount' => 2,
            ],
            [
                'condition' => ['mediaType' => 'text'],
                'expectedResults' => [$activity1],
                'expectedCount' => 1,
            ],
            [
                'condition' => ['mediaType' => 'text', 'fromCourseId' => 1],
                'expectedResults' => [$activity1],
                'expectedCount' => 1,
            ],
        ];

        $this->searchTestUtil($this->getActivityDao(), $testConditons, $this->getCompareKeys());
    }

    public function testFindByCourseId()
    {
        $activity = $this->mockActivity(['title' => 'activity 1']);
        $activities = $this->getActivityDao()->findByCourseId(1);
        $this->assertEquals(count($activities), 1);
        $this->assertArrayEquals($activity, $activities[0], $this->getCompareKeys());
    }

    public function testFindByIds()
    {
        $activity1 = $this->mockActivity(['title' => 'activity 1']);
        $activity2 = $this->mockActivity(['title' => 'activity 2']);
        $activity3 = $this->mockActivity(['title' => 'activity 3']);

        $activities = $this->getActivityDao()->findByIds([$activity1['id'], $activity2['id'], $activity3['id']]);
        $this->assertEquals(count($activities), 3);

        $this->assertArrayEquals($activity1, $activities[0], $this->getCompareKeys());
        $this->assertArrayEquals($activity2, $activities[1], $this->getCompareKeys());
        $this->assertArrayEquals($activity3, $activities[2], $this->getCompareKeys());
    }

    public function testGetByCopyIdAndCourseSetId()
    {
        $activity1 = $this->mockActivity(['title' => 'activity 1', 'copyId' => 1]);
        $activity = $this->getActivityDao()->getByCopyIdAndCourseSetId($activity1['copyId'], $activity1['fromCourseSetId']);

        $this->assertArrayEquals($activity1, $activity, $this->getCompareKeys());
    }

    public function testFindSelfVideoActivityByCourseIds()
    {
        $activity1 = $this->getActivityDao()->findSelfVideoActivityByCourseIds([]);

        $this->assertEquals($activity1, []);

        $fields = [
            'id' => 1,
            'mediaSource' => 'self',
        ];
        $video = $this->getVideoActivityDao()->create($fields);
        $activity1 = $this->mockActivity(['title' => 'activity1', 'mediaType' => 'video', 'mediaId' => 1]);
        $activity2 = $this->mockActivity(['title' => 'activity2', 'mediaId' => 1]);
        $activity3 = $this->mockActivity(['title' => 'activity3', 'mediaType' => 'video']);
        $activity4 = $this->mockActivity([
            'title' => 'activity4',
            'mediaType' => 'video',
            'mediaId' => 1,
            'fromCourseId' => 2,
        ]);
        $activities = $this->getActivityDao()->findSelfVideoActivityByCourseIds([1, 2]);

        $this->assertEquals(count($activities), 2);
        $this->assertEquals($activities[0]['fileId'], 0);
        $this->assertArrayEquals($activity1, $activities[0], $this->getCompareKeys());
        $this->assertArrayEquals($activity4, $activities[1], $this->getCompareKeys());
    }

    public function testFindOverlapTimeActivitiesByCourseId()
    {
        $activity1 = $this->mockActivity(['title' => 'activity1', 'mediaType' => 'live', 'startTime' => 20, 'endTime' => 30]);
        $activity2 = $this->mockActivity(['title' => 'activity2', 'startTime' => 30, 'endTime' => 40]);
        $activity3 = $this->mockActivity(['title' => 'activity3', 'mediaType' => 'live', 'startTime' => 25, 'endTime' => 35]);
        $activities = $this->getActivityDao()->findOverlapTimeActivitiesByCourseId(1, 30, 20, 2);

        $this->assertEquals(count($activities), 2);
        $this->assertArrayEquals($activity1, $activities[0], $this->getCompareKeys());
        $this->assertArrayEquals($activity3, $activities[1], $this->getCompareKeys());
    }

    public function testFindActivitiesByCourseIdsAndTypes()
    {
        $this->mockActivity(['title' => 'activity1', 'fromCourseId' => 1, 'mediaType' => 'homework']);
        $this->mockActivity(['title' => 'activity2', 'fromCourseId' => 1, 'mediaType' => 'testpaper']);
        $this->mockActivity(['title' => 'activity3', 'fromCourseId' => 2, 'mediaType' => 'homework']);

        $result = $this->getActivityDao()->findActivitiesByCourseIdsAndTypes([1], ['homework', 'testpaper']);
        $this->assertEquals(2, count($result));
    }

    protected function getDefaultMockFields()
    {
        return [
            'title' => 'activity',
            'mediaId' => 0,
            'mediaType' => 'text',
            'content' => '124',
            'fromCourseId' => 1,
            'fromCourseSetId' => 1,
            'fromUserId' => 1,
            'startTime' => time() - 1000,
            'endTime' => time(),
        ];
    }

    protected function getCompareKeys()
    {
        $default = $this->getDefaultMockFields();

        return array_keys($default);
    }

    protected function mockActivity($fields)
    {
        $fields = array_merge($this->getDefaultMockFields(), $fields);

        return $this->getActivityDao()->create($fields);
    }

    protected function getActivityDao()
    {
        return $this->createDao('Activity:ActivityDao');
    }

    protected function getVideoActivityDao()
    {
        return $this->createDao('Activity:VideoActivityDao');
    }
}
