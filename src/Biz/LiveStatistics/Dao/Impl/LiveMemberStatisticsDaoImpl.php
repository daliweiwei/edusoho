<?php

namespace Biz\LiveStatistics\Dao\Impl;

use Biz\LiveStatistics\Dao\LiveMemberStatisticsDao;
use Codeages\Biz\Framework\Dao\AdvancedDaoImpl;
use Codeages\Biz\Framework\Dao\DynamicQueryBuilder;

class LiveMemberStatisticsDaoImpl extends AdvancedDaoImpl implements LiveMemberStatisticsDao
{
    protected $table = 'live_statistics_member_data';

    public function searchLiveMembersJoinCourseMember($conditions, $start, $limit)
    {
        $builder = $this->createUserQueryBuilder($conditions)
                   ->orderBy('live.requestTime', 'DESC')
                   ->addOrderBy('member.id', 'ASC')
                   ->setFirstResult((int) $start)
                   ->setMaxResults((int) $limit);

        return $builder->execute()->fetchAll();
    }

    public function sumWatchDurationByLiveId($liveId)
    {
        $sql = 'SELECT sum(`watchDuration`) FROM `live_statistics_member_data` WHERE  `liveId` = ? ';

        return $this->db()->fetchColumn($sql, [$liveId]);
    }

    protected function createUserQueryBuilder($conditions)
    {
        $conditions = array_filter(
            $conditions,
            function ($v) {
                if (0 === $v) {
                    return true;
                }

                if (empty($v)) {
                    return false;
                }

                return true;
            }
        );

        $builder = new DynamicQueryBuilder($this->db(), $conditions);
        $builder->select('live.*,member.userId,member.id,member.courseId')
            ->from('course_member', 'member')
            ->leftJoin('member', 'live_statistics_member_data', 'live', 'member.userId=live.userId')
            ->andWhere('member.userId IN ( :userIds )')
            ->andWhere('member.userId = :userId')
            ->andWhere('member.courseId = :courseId');

        return $builder;
    }

    public function declares()
    {
        return [
            'serializes' => [],
            'timestamps' => ['createdTime', 'updatedTime'],
            'orderbys' => ['createdTime'],
            'conditions' => [
                'liveId = :liveId',
                'courseId = :courseId',
                'liveId IN (:liveIds)',
                'userId IN (:userIds)',
                'requestTime <= :requestTime_LT',
                'requestTime >= :requestTime_GE',
            ],
        ];
    }
}
