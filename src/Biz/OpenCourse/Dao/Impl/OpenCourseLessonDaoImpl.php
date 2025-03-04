<?php

namespace Biz\OpenCourse\Dao\Impl;

use Biz\OpenCourse\Dao\OpenCourseLessonDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class OpenCourseLessonDaoImpl extends GeneralDaoImpl implements OpenCourseLessonDao
{
    protected $table = 'open_course_lesson';

    public function declares()
    {
        return [
            'timestamps' => ['createdTime', 'updatedTime'],
            'serializes' => [],
            'orderbys' => ['createdTime', 'startTime', 'endTime', 'recommendedSeq', 'studentNum', 'hitNum', 'seq', 'updatedTime'],
            'conditions' => [
                'id = :lessonId',
                'id NOT IN (:lessonIdNotIn)',
                'courseId = :courseId',
                'updatedTime >= :updatedTime_GE',
                'status = :status',
                'type = :type',
                'free = :free',
                'userId = :userId',
                'mediaId = :mediaId',
                'number = :number',
                'startTime >= :startTimeGreaterThan',
                'endTime < :endTimeLessThan',
                'startTime <= :startTimeLessThan',
                'endTime > :endTimeGreaterThan',
                'title LIKE :titleLike',
                'createdTime >= :startTime',
                'createdTime <= :endTime',
                'copyId = :copyId',
                'courseId IN ( :courseIds )',
                'replayStatus IN (:replayStatusList)',
            ],
        ];
    }

    public function findByIds(array $ids)
    {
        return $this->findInField('id', $ids);
    }

    public function findByCourseId($courseId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE courseId = ? ORDER BY seq ASC";

        return $this->db()->fetchAll($sql, [$courseId]);
    }

    public function deleteByCourseId($id)
    {
        return $this->db()->delete($this->table, ['courseId' => $id]);
    }

    public function searchLessonsWithOrderBy($conditions, $start, $limit)
    {
        $builder = $this->createQueryBuilder($conditions)
            ->select('*, (CASE WHEN endTime < UNIX_TIMESTAMP(NOW()) THEN 1 WHEN startTime > UNIX_TIMESTAMP(NOW()) THEN 2 ELSE 3 END) AS weight,
        (
            CASE WHEN endTime < UNIX_TIMESTAMP(NOW()) THEN UNIX_TIMESTAMP(NOW()) - endTime ELSE startTime
            END) AS seq')
            ->addOrderBy('weight', 'DESC')
            ->addOrderBy('seq', 'ASC')
        ->setFirstResult($start)
        ->setMaxResults($limit);

        return $builder->execute()->fetchAll();
    }

    public function findTimeSlotOccupiedLessonsByCourseId($courseId, $startTime, $endTime, $excludeLessonId = 0)
    {
        $addtionalCondition = ';';

        $params = [$courseId, $startTime, $startTime, $startTime, $endTime];

        if (!empty($excludeLessonId)) {
            $addtionalCondition = 'and id != ? ;';
            $params[] = $excludeLessonId;
        }

        $sql = "SELECT * FROM {$this->table} WHERE courseId = ? AND ((startTime  < ? AND endTime > ?) OR  (startTime between ? AND ?)) ".$addtionalCondition;

        return $this->db()->fetchAll($sql, $params);
    }

    public function getLessonMaxSeqByCourseId($courseId)
    {
        $sql = "SELECT MAX(seq) FROM {$this->table()} WHERE  courseId = ?";

        return $this->db()->fetchColumn($sql, [$courseId]);
    }

    public function getLiveOpenLessonByMediaId($mediaId)
    {
        return $this->getByFields(['type' => 'liveOpen', 'mediaId' => $mediaId]);
    }

    protected function createQueryBuilder($conditions)
    {
        if (isset($conditions['title'])) {
            $conditions['titleLike'] = "%{$conditions['title']}%";
            unset($conditions['title']);
        }
        $builder = parent::createQueryBuilder($conditions);

        if (isset($conditions['notLearnedIds'])) {
            $builder->andWhere('id NOT IN ( :notLearnedIds)');
        }

        return $builder;
    }
}
