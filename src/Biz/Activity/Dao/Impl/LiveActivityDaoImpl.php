<?php

namespace Biz\Activity\Dao\Impl;

use Biz\Activity\Dao\LiveActivityDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class LiveActivityDaoImpl extends GeneralDaoImpl implements LiveActivityDao
{
    protected $table = 'activity_live';

    public function declares()
    {
        return [
            'serializes' => ['replayTagIds' => 'delimiter', 'fileIds' => 'json', 'coursewareIds' => 'json', 'cloudStatisticData' => 'json'],
            'orderbys' => ['liveStartTime', 'liveEndTime', 'id'],
            'conditions' => [
                'id IN (:ids)',
                'liveId = :liveId',
                'liveProvider = :liveProvider',
                'replayStatus = :replayStatus',
                'replayPublic = :replayPublic',
                'replayTagIds like :replayTagIds',
                'anchorId = :anchorId',
                'progressStatus != :progressStatusNotEqual',
                'progressStatus = :progressStatus',
                'liveStartTime >= :liveStartTime_GT',
                'liveEndTime <= :liveEndTime_LT',
                /*S2B2C 增加syncId*/
                'syncId = :syncId',
                'syncId in (:syncIds)',
                'syncId > :syncIdGT',
                /*END*/
            ],
        ];
    }

    public function findByIds($Ids)
    {
        return $this->findInField('id', $Ids);
    }

    public function findByLiveIdAndReplayStatus($liveId)
    {
        return $this->findByFields(['liveId' => $liveId, 'replayStatus' => 'ungenerated']);
    }

    public function findLiveActivitiesByReplayStatus($replayStatus = 'generated')
    {
        return $this->findByFields(['replayStatus' => $replayStatus]);
    }

    public function findLiveActivitiesByIsPublic()
    {
        return $this->findByFields(['replayPublic' => 1, 'replayStatus' => 'generated']);
    }

    public function findLiveActivitiesByReplayTagId($tagId)
    {
        return $this->findByFields(['replayTagIds' => '%|'.$tagId.'|%', 'replayStatus' => 'generated']);
    }

    public function getByLiveId($liveId)
    {
        return $this->getByFields(['liveId' => $liveId]);
    }

    public function findByLiveIds($liveIds)
    {
        return $this->findInField('liveId', $liveIds);
    }

    public function getBySyncIdGTAndLiveId($liveId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE syncId > 0 and liveId = ?";

        return $this->db()->fetchAssoc($sql, [$liveId]);
    }

    public function getBySyncId($syncId)
    {
        return $this->getByFields(['syncId' => $syncId]);
    }
}
