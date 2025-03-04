<?php

namespace Codeages\Biz\ItemBank\Answer\Dao\Impl;

use Codeages\Biz\ItemBank\Answer\Dao\AnswerRecordDao;
use Codeages\Biz\Framework\Dao\AdvancedDaoImpl;

class AnswerRecordDaoImpl extends AdvancedDaoImpl implements AnswerRecordDao
{
    protected $table = 'biz_answer_record';

    public function getLatestAnswerRecordByAnswerSceneIdAndUserId($answerSceneId, $userId)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE answer_scene_id = ? AND user_id = ? ORDER BY id DESC LIMIT 1';

        return $this->db()->fetchAssoc($sql, [$answerSceneId, $userId]);
    }

    public function getNextReviewingAnswerRecordByAnswerSceneId($answerSceneId)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE answer_scene_id = ? AND status = "reviewing" ORDER BY begin_time ASC LIMIT 1';

        return $this->db()->fetchAssoc($sql, [$answerSceneId]);
    }

    public function findByAnswerSceneId($answerSceneId)
    {
        return $this->findByFields(['answer_scene_id' => $answerSceneId]);
    }

    public function countGroupByAnswerSceneId($conditions)
    {
        $builder = $this->createQueryBuilder($conditions)
            ->select("count(*) as count, answer_scene_id")
            ->groupBy('answer_scene_id');

        return $builder->execute()->fetchAll();
    }

    public function deleteByAssessmentId($assessmentId)
    {
        $sql = "DELETE FROM {$this->table} WHERE assessment_id = ?";

        return $this->db()->executeUpdate($sql, [$assessmentId]);
    }

    public function findByIds($ids)
    {
        return $this->findInField('id', $ids);
    }

    public function declares()
    {
        return [
            'timestamps' => [
                'created_time',
                'updated_time',
            ],
            'orderbys' => [
                'created_time',
                'updated_time',
                'begin_time',
                'end_time',
            ],
            'serializes' => [],
            'conditions' => [
                'answer_scene_id = :answer_scene_id',
                'user_id = :user_id',
                'user_id in (:user_ids)',
                'id in (:ids)',
                'status = :status',
                'status <> :statusNeq',
                'begin_time > :beginTime_GT',
                'begin_time <= :beginTime_ELT',
                'answer_scene_id IN (:answer_scene_ids)',
                'assessment_id = :assessment_id',
                'end_time <= :endTime_LE',
            ],
        ];
    }
}
