<?php

namespace Codeages\Biz\ItemBank\Answer\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\ItemBank\Answer\Dao\AnswerReportDao;
use Codeages\Biz\ItemBank\Answer\Exception\AnswerException;
use Codeages\Biz\ItemBank\Answer\Exception\AnswerReportException;
use Codeages\Biz\ItemBank\Answer\Service\AnswerQuestionReportService;
use Codeages\Biz\ItemBank\Answer\Service\AnswerRandomSeqService;
use Codeages\Biz\ItemBank\Answer\Service\AnswerReportService;
use Codeages\Biz\ItemBank\Assessment\Exception\AssessmentException;
use Codeages\Biz\ItemBank\BaseService;
use Codeages\Biz\ItemBank\ErrorCode;
use Codeages\Biz\ItemBank\Item\Service\AttachmentService;

class AnswerReportServiceImpl extends BaseService implements AnswerReportService
{
    public function create(array $answerReport)
    {
        $answerReport = $this->getValidator()->validate($answerReport, [
            'user_id' => ['required', 'integer', ['min', 0]],
            'assessment_id' => ['required', 'integer', ['min', 0]],
            'answer_record_id' => ['required', 'integer', ['min', 0]],
            'score' => ['numeric', ['min', 0]],
            'total_score' => ['numeric', ['min', 0]],
            'right_rate' => ['numeric', ['min', 0]],
            'right_question_count' => ['integer'],
            'objective_score' => ['numeric', ['min', 0]],
            'subjective_score' => ['numeric', ['min', 0]],
            'grade' => [],
            'review_time' => ['integer'],
            'comment' => [],
        ]);

        if (empty($this->getAssessmentService()->getAssessment($answerReport['assessment_id']))) {
            throw new AssessmentException('Assessment not found.', ErrorCode::ASSESSMENT_NOTFOUND);
        }

        $answerRecord = $this->getAnswerRecordService()->get($answerReport['answer_record_id']);
        if (empty($answerRecord)) {
            throw new AnswerException('Answer record not found.', ErrorCode::ANSWER_RECORD_NOTFOUND);
        }
        $answerReport['answer_scene_id'] = $answerRecord['answer_scene_id'];

        return $this->getAnswerReportDao()->create($answerReport);
    }

    public function update($id, array $answerReport)
    {
        $answerReport = $this->getValidator()->validate($answerReport, [
            'score' => ['numeric', ['min', 0]],
            'right_rate' => ['numeric', ['min', 0]],
            'review_user_id' => ['integer'],
            'review_time' => ['integer'],
            'right_question_count' => ['integer'],
            'objective_score' => ['numeric', ['min', 0]],
            'subjective_score' => ['numeric', ['min', 0]],
            'grade' => [],
            'comment' => [],
        ]);

        if (empty($this->getSimple($id))) {
            throw new AnswerReportException('Answer report not found.', ErrorCode::ANSWER_REPORT_NOTFOUND);
        }

        if (isset($answerReport['grade']) && empty($answerReport['grade'])) {
            unset($answerReport['grade']);
        }

        return $this->getAnswerReportDao()->update($id, $answerReport);
    }

    public function get($id)
    {
        $simpleAnswerReport = $answerReport = $this->getSimple($id);
        if (empty($simpleAnswerReport)) {
            return [];
        }

        $answerQuestionReports = $this->wrapperAnswerQuestionReports(
            $simpleAnswerReport['answer_record_id'],
            $this->getAnswerQuestionReportService()->findByAnswerRecordId($simpleAnswerReport['answer_record_id'])
        );
        $attachments = $this->getAttachmentService()->findAttachmentsByTargetIdsAndTargetType(
            ArrayToolkit::column($answerQuestionReports, 'id'),
            AttachmentService::ANSWER_TYPE
        );
        $assessmentSections = ArrayToolkit::index($this->geAssessmentSectionService()->findSectionsByAssessmentId($simpleAnswerReport['assessment_id']), 'id');
        $sectionReports = ArrayToolkit::group($answerQuestionReports, 'section_id');
        $attachments = ArrayToolkit::group($attachments, 'target_id');
        foreach ($sectionReports as $sectionId => &$sectionReport) {
            $itemReports = ArrayToolkit::group($sectionReport, 'item_id');
            foreach ($itemReports as $itemId => &$itemReport) {
                $questionReports = [];
                foreach ($itemReport as $questionReport) {
                    $questionReport['attachments'] = empty($attachments[$questionReport['id']]) ? [] : $attachments[$questionReport['id']];
                    $questionReports[] = ArrayToolkit::parts($questionReport, ['id', 'question_id', 'total_score', 'score', 'comment', 'status', 'response', 'attachments', 'revise']);
                }

                $itemReportGroupByStatus = ArrayToolkit::group($questionReports, 'status');
                $itemReport = [
                    'item_id' => $itemId,
                    'total_score' => array_sum(ArrayToolkit::column($itemReport, 'total_score')),
                    'score' => array_sum(ArrayToolkit::column($itemReport, 'score')),
                    'question_count' => count($itemReport),
                    'right_question_num' => empty($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_RIGHT]) ? 0 : count($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_RIGHT]),
                    'wrong_question_num' => empty($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_WRONG]) ? 0 : count($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_WRONG]),
                    'reviewing_question_num' => empty($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_REVIEWING]) ? 0 : count($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_REVIEWING]),
                    'no_answer_question_num' => empty($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_NOANSWER]) ? 0 : count($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_NOANSWER]),
                    'part_right_question_num' => empty($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_PART_RIGHT]) ? 0 : count($itemReportGroupByStatus[AnswerQuestionReportService::STATUS_PART_RIGHT]),
                    'question_reports' => $questionReports,
                ];
            }

            $sectionReportGroupByStatus = ArrayToolkit::group($sectionReport, 'status');
            $sectionReport = [
                'section_id' => $sectionId,
                'section_name' => empty($assessmentSections[$sectionId]['name']) ? '' : $assessmentSections[$sectionId]['name'],
                'total_score' => array_sum(ArrayToolkit::column($sectionReport, 'total_score')),
                'score' => array_sum(ArrayToolkit::column($sectionReport, 'score')),
                'question_count' => count($sectionReport),
                'right_question_num' => empty($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_RIGHT]) ? 0 : count($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_RIGHT]),
                'wrong_question_num' => empty($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_WRONG]) ? 0 : count($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_WRONG]),
                'reviewing_question_num' => empty($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_REVIEWING]) ? 0 : count($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_REVIEWING]),
                'no_answer_question_num' => empty($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_NOANSWER]) ? 0 : count($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_NOANSWER]),
                'part_right_question_num' => empty($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_PART_RIGHT]) ? 0 : count($sectionReportGroupByStatus[AnswerQuestionReportService::STATUS_PART_RIGHT]),
                'item_reports' => array_values($itemReports),
            ];
        }

        $answerReport['section_reports'] = array_values($sectionReports);

        return $answerReport;
    }

    public function wrapperAnswerQuestionReports($answerRecordId, $answerQuestionReports)
    {
        $questionReports = [];
        $answerRecord = $this->getAnswerRecordService()->get($answerRecordId);
        $answerQuestionReports = ArrayToolkit::index($answerQuestionReports, 'question_id');
        $assessmentQuestions = $this->getAssessmentService()->findAssessmentQuestions($answerRecord['assessment_id']);
        $questions = $this->getItemService()->findQuestionsByQuestionIdsIncludeDeleted(
            ArrayToolkit::column($assessmentQuestions, 'question_id')
        );

        foreach ($assessmentQuestions as $questionId => $assessmentQuestion) {
            if (!empty($questions[$questionId]) && 'rich_text' == $questions[$questionId]['answer_mode'] && 'reviewing' == $answerRecord['status']) {
                $status = AnswerQuestionReportService::STATUS_REVIEWING;
            } else {
                $status = empty($answerQuestionReports[$questionId]) ? AnswerQuestionReportService::STATUS_NOANSWER : $answerQuestionReports[$questionId]['status'];
            }
            $questionReports[] = [
                'id' => empty($answerQuestionReports[$questionId]) ? '0' : $answerQuestionReports[$questionId]['id'],
                'identify' => empty($answerQuestionReports[$questionId]) ? '' : $answerQuestionReports[$questionId]['identify'],
                'answer_record_id' => empty($answerQuestionReports[$questionId]) ? '0' : $answerQuestionReports[$questionId]['answer_record_id'],
                'assessment_id' => $answerRecord['assessment_id'],
                'section_id' => $assessmentQuestion['section_id'],
                'item_id' => $assessmentQuestion['item_id'],
                'question_id' => $assessmentQuestion['question_id'],
                'seq' => $assessmentQuestion['seq'],
                'score' => empty($answerQuestionReports[$questionId]) ? '0' : $answerQuestionReports[$questionId]['score'],
                'total_score' => $assessmentQuestion['score'],
                'response' => empty($answerQuestionReports[$questionId]) ? [] : $answerQuestionReports[$questionId]['response'],
                'status' => $status,
                'comment' => empty($answerQuestionReports[$questionId]) ? '' : $answerQuestionReports[$questionId]['comment'],
                'created_time' => empty($answerQuestionReports[$questionId]) ? '0' : $answerQuestionReports[$questionId]['created_time'],
                'updated_time' => empty($answerQuestionReports[$questionId]) ? '0' : $answerQuestionReports[$questionId]['updated_time'],
                'revise' => empty($answerQuestionReports[$questionId]) ? [] : $answerQuestionReports[$questionId]['revise'],
            ];
        }
        $questionReports = $this->sortPerArrayValue($questionReports, 'seq');
        $questionReports = $this->getAnswerRandomSeqService()->shuffleQuestionReportsAndConvertOptionsIfNecessary($questionReports, $answerRecordId);

        return $questionReports;
    }

    protected function sortPerArrayValue($arr, $attrName, $ascending = true)
    {
        usort(
            $arr,
            function ($first, $next) use ($ascending, $attrName) {
                if ($ascending) {
                    return $first[$attrName] > $next[$attrName] ? 1 : -1;
                } else {
                    return $first[$attrName] < $next[$attrName] ? 1 : -1;
                }
            }
        );

        return $arr;
    }

    public function getSimple($id)
    {
        return $this->getAnswerReportDao()->get($id);
    }

    public function findByAnswerSceneId($answerSceneId)
    {
        return $this->getAnswerReportDao()->findByAnswerSceneId($answerSceneId);
    }

    public function search($conditions, $orderBys, $start, $limit, $columns = [])
    {
        return $this->getAnswerReportDao()->search($conditions, $orderBys, $start, $limit, $columns);
    }

    public function count($conditions)
    {
        return $this->getAnswerReportDao()->count($conditions);
    }

    public function findByIds(array $ids)
    {
        return $this->getAnswerReportDao()->findByIds($ids);
    }

    public function batchUpdate($ids, $updateColumnsList)
    {
        return $this->getAnswerReportDao()->batchUpdate($ids, $updateColumnsList);
    }

    public function batchCreateAnswerReports($answerReports)
    {
        return $this->getAnswerReportDao()->batchCreate($answerReports);
    }

    public function replaceAssessmentsWithSnapshotAssessments($assessmentSnapshots)
    {
        if (empty($assessmentSnapshots)) {
            return;
        }
        $updateAssessments = [];
        $updateSections = [];
        foreach ($assessmentSnapshots as $assessmentSnapshot) {
            $updateAssessments[$assessmentSnapshot['origin_assessment_id']] = [
                'assessment_id' => $assessmentSnapshot['snapshot_assessment_id'],
            ];
            foreach ($assessmentSnapshot['sections_snapshot'] as $originSectionId => $snapshotSectionId) {
                $updateSections[$originSectionId] = [
                    'section_id' => $snapshotSectionId,
                ];
            }
        }
        $this->getAnswerReportDao()->batchUpdate(array_keys($updateAssessments), $updateAssessments, 'assessment_id');
        $this->getAnswerQuestionReportService()->replaceAssessmentsAndSectionsWithSnapshotAssessmentsAndSections($updateAssessments, $updateSections);
    }

    /**
     * @return \Codeages\Biz\ItemBank\Assessment\Service\AssessmentSectionService
     */
    protected function geAssessmentSectionService()
    {
        return $this->biz->service('ItemBank:Assessment:AssessmentSectionService');
    }

    /**
     * @return \Codeages\Biz\ItemBank\Assessment\Service\AssessmentService
     */
    protected function getAssessmentService()
    {
        return $this->biz->service('ItemBank:Assessment:AssessmentService');
    }

    /**
     * @return \Codeages\Biz\ItemBank\Answer\Service\AnswerRecordService
     */
    protected function getAnswerRecordService()
    {
        return $this->biz->service('ItemBank:Answer:AnswerRecordService');
    }

    /**
     * @return \Codeages\Biz\ItemBank\Answer\Service\AnswerQuestionReportService
     */
    protected function getAnswerQuestionReportService()
    {
        return $this->biz->service('ItemBank:Answer:AnswerQuestionReportService');
    }

    /**
     * @return AnswerRandomSeqService
     */
    protected function getAnswerRandomSeqService()
    {
        return $this->biz->service('ItemBank:Answer:AnswerRandomSeqService');
    }

    /**
     * @return AnswerReportDao
     */
    protected function getAnswerReportDao()
    {
        return $this->biz->dao('ItemBank:Answer:AnswerReportDao');
    }

    /**
     * @return \Codeages\Biz\ItemBank\Item\Service\AttachmentService
     */
    protected function getAttachmentService()
    {
        return $this->biz->service('ItemBank:Item:AttachmentService');
    }

    /**
     * @return \Codeages\Biz\ItemBank\Item\Service\ItemService
     */
    protected function getItemService()
    {
        return $this->biz->service('ItemBank:Item:ItemService');
    }
}
