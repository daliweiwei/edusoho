<?php

namespace Biz\Activity\Service\Impl;

use AppBundle\Common\ArrayToolkit;
use Biz\Activity\Constant\ActivityMediaType;
use Biz\Activity\Dao\ActivityDao;
use Biz\Activity\Listener\ActivityLearnLogListener;
use Biz\Activity\Service\ActivityLearnLogService;
use Biz\Activity\Service\ActivityService;
use Biz\Activity\Service\ExerciseActivityService;
use Biz\Activity\Service\HomeworkActivityService;
use Biz\Activity\Service\LiveActivityService;
use Biz\Activity\Service\TestpaperActivityService;
use Biz\Activity\Type\Testpaper;
use Biz\BaseService;
use Biz\Common\CommonException;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\MaterialService;
use Biz\Course\Service\MemberService;
use Biz\File\Service\UploadFileService;
use Biz\System\Constant\LogAction;
use Biz\System\Constant\LogModule;
use Biz\System\Service\LogService;
use Biz\System\Service\SettingService;
use Biz\User\Service\UserService;
use Biz\Util\EdusohoLiveClient;
use Codeages\Biz\Framework\Event\Event;
use Codeages\Biz\ItemBank\Answer\Service\AnswerRecordService;

class ActivityServiceImpl extends BaseService implements ActivityService
{
    const LIVE_STARTTIME_DIFF_SECONDS = 7200;

    public function getActivity($id, $fetchMedia = false)
    {
        $activity = $this->getActivityDao()->get($id);

        if ($fetchMedia) {
            $activity = $this->fetchMedia($activity);
        }

        return $activity;
    }

    public function getActivityFinishCondition($activity)
    {
        if (!ArrayToolkit::requireds($activity, ['mediaType', 'finishType', 'finishData'])) {
            throw $this->createInvalidArgumentException('params missed');
        }

        $type = $activity['mediaType'];
        $finishType = $activity['finishType'];
        $finishData = $activity['finishData'];

        if ('testpapaer' == $type && 'score' == $finishType) {
            if (!isset($activity['ext'])) {
                $activity = $this->fetchMedia($activity);
            }
            $finishData = $activity['ext']['finishCondition']['finishScore'];
        }

        $text = "mobile.task.finish_tips.{$type}.{$finishType}";

        try {
            $text = $this->trans($text, ['%finishData%' => $finishData]);
        } catch (\Exception $e) {
            // 如果新增类型，而翻译文件未配置，会报错
            $text = '';
        }

        return [
            'type' => $finishType,
            'data' => $finishData,
            'text' => $text,
        ];
    }

    public function findActivities($ids, $fetchMedia = false, $showCloud = 1)
    {
        $activities = $this->getActivityDao()->findByIds($ids);

        return $this->prepareActivities($fetchMedia, $activities, $showCloud);
    }

    public function findActivitiesByCourseIdAndType($courseId, $type, $fetchMedia = false)
    {
        $activities = $this->getActivityDao()->findActivitiesByCourseIdAndType($courseId, $type);

        return $this->prepareActivities($fetchMedia, $activities);
    }

    public function findActivitiesByCourseIdsAndType($courseIds, $type, $fetchMedia = false)
    {
        $activities = $this->getActivityDao()->findActivitiesByCourseIdsAndType($courseIds, $type);

        return $this->prepareActivities($fetchMedia, $activities);
    }

    public function findActivitiesByCourseIdsAndTypes($courseIds, $types, $fetchMedia = false)
    {
        $activities = $this->getActivityDao()->findActivitiesByCourseIdsAndTypes($courseIds, $types);

        return $this->prepareActivities($fetchMedia, $activities);
    }

    public function findActivitiesByCourseSetIdAndType($courseSetId, $type, $fetchMedia = false)
    {
        $activities = $this->getActivityDao()->findActivitiesByCourseSetIdAndType($courseSetId, $type);

        return $this->prepareActivities($fetchMedia, $activities);
    }

    public function findActivitiesByCourseSetIdsAndType($courseSetIds, $type, $fetchMedia = false)
    {
        $activities = $this->getActivityDao()->findActivitiesByCourseSetIdsAndType($courseSetIds, $type);

        return $this->prepareActivities($fetchMedia, $activities);
    }

    public function findActivitiesByCourseSetIdsAndTypes($courseSetIds, $types, $fetchMedia = false)
    {
        $activities = $this->getActivityDao()->findActivitiesByCourseSetIdsAndTypes($courseSetIds, $types);

        return $this->prepareActivities($fetchMedia, $activities);
    }

    public function search($conditions, $orderBy, $start, $limit, $columns = [])
    {
        return $this->getActivityDao()->search($conditions, $orderBy, $start, $limit, $columns);
    }

    public function count($conditions)
    {
        return $this->getActivityDao()->count($conditions);
    }

    public function trigger($id, $eventName, $data = [])
    {
        $activity = $this->getActivity($id);

        if (empty($activity)) {
            return false;
        }

        if ('start' == $eventName) {
            $this->dispatchEvent("activity.{$eventName}", new Event($activity, $data));
        }

        if (isset($data['events']) && array_key_exists('finish', $data['events'])) {
            $data = ['taskId' => empty($data['taskId']) ? 0 : $data['taskId']];
            $eventName = 'finish';
        }
        $this->triggerActivityLearnLogListener($activity, $eventName, $data);

        if (empty($data['events'])) {
            $events = [];
        } else {
            $events = $data['events'];
            unset($data['events']);
        }
        foreach ($events as $key => $event) {
            $data = array_merge($event, $data);
            $this->triggerActivityLearnLogListener($activity, $key, $data);
            $this->triggerExtendListener($activity, $key, $data);
        }
        if ('doing' == $eventName || 'finish' == $eventName) {
            $this->dispatchEvent("activity.{$eventName}", new Event($activity, $data));
        }

        return true;
    }

    protected function triggerActivityLearnLogListener($activity, $eventName, $data)
    {
        $logListener = new ActivityLearnLogListener($this->biz);
        $logData = $this->extractLogData($eventName, $data);
        $logListener->handle($activity, $logData);
    }

    protected function triggerExtendListener($activity, $eventName, $data)
    {
        $activityListener = $this->getActivityConfig($activity['mediaType'])->getListener($eventName);
        if (null !== $activityListener) {
            $activityListener->handle($activity, $data);
        }
    }

    public function preCreateCheck($activityType, $fields)
    {
        $activity = $this->getActivityConfig($activityType);
        $activity->preCreateCheck($fields);
    }

    public function preUpdateCheck($activityId, $fields)
    {
        $activity = $this->getActivity($activityId);

        $activityInstance = $this->getActivityConfig($activity['mediaType']);
        $activityInstance->preUpdateCheck($activity, $fields);
    }

    public function createActivity($fields)
    {
        if ($this->invalidActivity($fields)) {
            $this->createNewException(CommonException::ERROR_PARAMETER());
        }

        $this->getCourseService()->tryManageCourse($fields['fromCourseId']);
        $activityConfig = $this->getActivityConfig($fields['mediaType']);
        if (empty($fields['mediaId'])) {
            $media = $activityConfig->create($fields);
        }
        if (!empty($media)) {
            $fields['mediaId'] = $media['id'];
        }
        // 使用content来存储media内容
        if (!empty($fields['media']) && empty($fields['content'])) {
            $fields['content'] = json_encode($fields['media']);
        }
        $materials = $this->getMaterialsFromActivity($fields);
        $fields['fromUserId'] = $this->getCurrentUser()->getId();
        $fields = $this->filterFields($fields);
        $activity = $this->getActivityDao()->create($fields);

        if (!empty($materials)) {
            $this->createActivityMaterials($activity, $materials);
        }
        $listener = $activityConfig->getListener('activity.created');
        if (!empty($listener)) {
            $listener->handle($activity, []);
        }

        return $activity;
    }

    public function updateActivity($id, $fields)
    {
        $savedActivity = $this->getActivity($id);

        $this->getCourseService()->tryManageCourse($savedActivity['fromCourseId']);

        $realActivity = $this->getActivityConfig($savedActivity['mediaType']);

        $materials = $this->getMaterialsFromActivity($fields);
        if (!empty($materials)) {
            $this->syncActivityMaterials($savedActivity, $materials);
        }

        if (!empty($savedActivity['mediaId'])) {
            $media = $realActivity->update($savedActivity['mediaId'], $fields, $savedActivity);

            if (!empty($media)) {
                $fields['mediaId'] = $media['id'];
            }
        }
        if (!empty($fields['media']) && empty($fields['content'])) {
            $fields['content'] = $fields['media'];
        }

        $fields = $this->filterFields($fields);

        return $this->getActivityDao()->update($id, $fields);
    }

    public function deleteActivity($id)
    {
        $activity = $this->getActivity($id);
        $course = $this->getCourseService()->getCourse($activity['fromCourseId']);
        if ($course) {
            $this->getCourseService()->tryManageCourse($course['id']);
        }

        try {
            $this->beginTransaction();

            $this->getMaterialService()->deleteMaterialsByLessonId($activity['id']);

            $this->getActivityConfig($activity['mediaType'])->delete($activity['mediaId']);
            $this->getActivityLearnLogService()->deleteLearnLogsByActivityId($id);
            $result = $this->getActivityDao()->delete($id);
            if (empty($activity['copyId'])) {
                $this->getLogService()->info(LogModule::COURSE, LogAction::DELETE_ACTIVITY, '删除教学活动成功', ['id' => $id]);
            }
            $this->commit();

            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            $this->getLogService()->error(LogModule::COURSE, LogAction::DELETE_ACTIVITY, '删除教学活动失败', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function isFinished($id, $userId = 0)
    {
        $activity = $this->getActivity($id);
        $activityConfig = $this->getActivityConfig($activity['mediaType']);

        return $activityConfig->isFinished($id, $userId);
    }

    public function getByMediaIdAndMediaTypeAndCopyId($mediaId, $mediaType, $copyId)
    {
        return $this->getActivityDao()->getByMediaIdAndMediaTypeAndCopyId($mediaId, $mediaType, $copyId);
    }

    public function getByMediaIdAndMediaType($mediaId, $mediaType)
    {
        return $this->getActivityDao()->getByMediaIdAndMediaType($mediaId, $mediaType);
    }

    public function getByMediaIdAndMediaTypeAndCourseId($mediaId, $mediaType, $courseId)
    {
        return $this->getActivityDao()->getByMediaIdAndMediaTypeAndCourseId($mediaId, $mediaType, $courseId);
    }

    /**
     * @param $conditions
     *
     * @return array
     *               处理 直播回放的activityId 管理员和讲师
     */
    public function findManageReplayActivityIds($conditions)
    {
        $activityIds = $this->processManageReplayConditionsActivityIds();
        $activityIds = $this->processFindManageReplayConditions_withCourseTab($activityIds, $conditions);
        $activityIds = $this->processFindManageReplayConditions_withLiveActivityTab($activityIds, $conditions);

        if (!empty($conditions['keyword']) && 'activityTitle' == $conditions['keywordType']) {
            $activityConditions['title'] = $conditions['keyword'];
        }
        if (empty($activityConditions)) {
            $ids = array_values(array_unique($activityIds));

            return empty($ids) ? [-1] : $ids;
        }
        $activityConditions['mediaType'] = 'live';
        $activities = $this->search($activityConditions, [], 0, PHP_INT_MAX, ['id']);
        $searchActivityIds = ArrayToolkit::column($activities, 'id');
        $ids = array_values(array_unique(array_intersect($activityIds, $searchActivityIds)));

        return empty($ids) ? [-1] : $ids;
    }

    protected function processManageReplayConditionsActivityIds()
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser->isAdmin()) {
            $activities = $this->search(['mediaType' => 'live'], [], 0, PHP_INT_MAX, ['id']);
            $activityIds = ArrayToolkit::column($activities, 'id');
        } else {
            $activityIds = $this->findLiveActivityIdsWithoutAdmin();
        }
        $publishActivities = $this->getLiveActivityService()->search(['replayPublic' => 1], [], 0, $this->getLiveActivityService()->count(['replayPublic' => 1]), ['id']);

        $liveActivityIds = empty($publishActivities) ? [-1] : ArrayToolkit::column($publishActivities, 'id');
        $activities = $this->search(['mediaIds' => $liveActivityIds, 'mediaType' => 'live'], [], 0, PHP_INT_MAX, ['id']);
        $ids = ArrayToolkit::column($activities, 'id');

        return array_values(array_unique(array_merge($activityIds, $ids)));
    }

    protected function processFindManageReplayConditions_withLiveActivityTab($activityIds, $conditions)
    {
        if (!empty($conditions['startTime'])) {
            $startTime = strtotime(date('Y-m-d', $conditions['startTime']));
            $liveConditions['liveStartTime_GT'] = $startTime;
        }

        if (!empty($conditions['endTime'])) {
            $endTime = strtotime(date('Y-m-d', $conditions['endTime']).' 23:59:59');
            $liveConditions['liveStartTime_LT'] = $endTime;
        }

        if (!empty($conditions['replayTagId'])) {
            $liveConditions['replayTagIds'] = "%|{$conditions['replayTagId']}|%";
        }
        if (!empty($conditions['replayPublic'])) {
            $liveConditions['replayPublic'] = $conditions['replayPublic'];
        }
        if (!empty($conditions['keyword']) && 'anchor' == $conditions['keywordType']) {
            $users = $this->getUserService()->findUserLikeNickname($conditions['keyword']);
            $liveConditions['anchorIds'] = empty($users) ? [-1] : ArrayToolkit::column($users, 'id');
        }

        if (!empty($conditions['anchorId'])) {
            $liveConditions['anchorIds'] = empty($liveConditions['anchorIds']) ? [$conditions['anchorId']] : array_merge($liveConditions['anchorIds'], [$conditions['anchorId']]);
        }

        if (empty($liveConditions)) {
            return $activityIds;
        }
        $liveActivities = $this->getLiveActivityService()->search($liveConditions, [], 0, PHP_INT_MAX, ['id']);
        $liveActivityIds = empty($liveActivities) ? [-1] : ArrayToolkit::column($liveActivities, 'id');
        $anchorActivities = $this->search(['mediaIds' => $liveActivityIds, 'mediaType' => 'live'], [], 0, PHP_INT_MAX, ['id']);
        $activityTagIds = ArrayToolkit::column($anchorActivities, 'id');

        return array_intersect($activityIds, $activityTagIds);
    }

    protected function processFindManageReplayConditions_withCourseTab($activityIds, $conditions)
    {
        $courseIds = [];
        if (!empty($conditions['categoryId'])) {
            $courses = $this->getCourseService()->searchCourses(['categoryId' => $conditions['categoryId']], [], 0, PHP_INT_MAX, ['id']);
            $courseIds = empty($courses) ? [-1] : ArrayToolkit::column($courses, 'id');
        }
        if (!empty($conditions['keyword']) && 'courseTitle' == $conditions['keywordType']) {
            $courses = $this->getCourseService()->searchCourses(['titleLike' => $conditions['keyword'], 'ids' => $courseIds], [], 0, PHP_INT_MAX, ['id']);
            $likeCourses = $this->getCourseService()->searchCourses(['courseSetTitleLike' => $conditions['keyword'], 'ids' => $courseIds], [], 0, PHP_INT_MAX, ['id']);
            $courses = array_merge($courses, $likeCourses);
            $courseIds = empty($courses) ? [-1] : ArrayToolkit::column($courses, 'id');
        }
        if (!empty($conditions['courseId'])) {
            $courseIds = empty($courseIds) ? [$conditions['courseId']] : array_intersect($courseIds, [$conditions['courseId']]);
        }
        if (empty($courseIds)) {
            return $activityIds;
        }
        $activityCategory = $this->search(['ids' => $activityIds, 'courseIds' => $courseIds, 'mediaType' => 'live'], [], 0, PHP_INT_MAX, ['id']);
        $activityCategoryIds = ArrayToolkit::column($activityCategory, 'id');

        return array_intersect($activityIds, $activityCategoryIds);
    }

    protected function findLiveActivityIdsWithoutAdmin()
    {
        $courses = $this->getCourseService()->searchCourses(['teacherIds' => "%|{$this->getCurrentUser()->getId()}|%"], [], 0, PHP_INT_MAX, ['id']);
        $courseIds = empty($courses) ? [-1] : ArrayToolkit::column($courses, 'id');
        $teacherActivities = $this->search(['courseIds' => $courseIds, 'mediaType' => 'live'], [], 0, PHP_INT_MAX, ['id']);
        $liveActivities = $this->getLiveActivityService()->search(['anchorId' => $this->getCurrentUser()->getId(), 'replayStatusNotEqual' => 'ungenerated'], [], 0, PHP_INT_MAX, ['id']);
        $liveActivityIds = empty($liveActivities) ? [-1] : ArrayToolkit::column($liveActivities, 'id');
        $anchorActivities = $this->search(['mediaIds' => $liveActivityIds, 'mediaType' => 'live'], [], 0, PHP_INT_MAX, ['id']);
        $activities = array_merge($teacherActivities, $anchorActivities);
        $activityIds = ArrayToolkit::column($activities, 'id');

        return array_values(array_unique($activityIds));
    }

    private function createActivityMaterials($activity, $materials)
    {
        if (empty($materials)) {
            return;
        }
        foreach ($materials as $material) {
            $this->getMaterialService()->uploadMaterial($this->buildMaterial($material, $activity));
        }
    }

    private function syncActivityMaterials($activity, $materials)
    {
        if (empty($materials)) {
            return;
        }
        $exists = $this->getMaterialService()->searchMaterials(
            [
                'lessonId' => $activity['id'],
                'type' => 'course',
            ],
            ['createdTime' => 'DESC'],
            0,
            PHP_INT_MAX
        );
        $currents = [];
        foreach ($materials as $material) {
            $currents[] = $this->buildMaterial($material, $activity);
        }

        $dropMaterials = $this->diffMaterials($exists, $currents);
        $addMaterials = $this->diffMaterials($currents, $exists);
        $updateMaterials = $this->dirtyMaterials($exists, $currents);
        foreach ($dropMaterials as $material) {
            $this->getMaterialService()->deleteMaterial($activity['fromCourseSetId'], $material['id']);
        }
        foreach ($addMaterials as $material) {
            $this->getMaterialService()->uploadMaterial($material);
        }
        foreach ($updateMaterials as $material) {
            $this->getMaterialService()->updateMaterial($material['id'], $material, $material);
        }
    }

    protected function buildMaterial($material, $activity)
    {
        return [
            'fileId' => intval($material['fileId']),
            'courseId' => $activity['fromCourseId'],
            'courseSetId' => $activity['fromCourseSetId'],
            'lessonId' => $activity['id'],
            'title' => $material['title'],
            'description' => empty($material['summary']) ? '' : $material['summary'],
            'userId' => $this->getCurrentUser()->getId(),
            'type' => 'course',
            'source' => 'download' == $activity['mediaType'] ? 'coursematerial' : 'courseactivity',
            'link' => empty($material['link']) ? '' : $material['link'],
            'copyId' => 0,
        ];
    }

    protected function diffMaterials($arr1, $arr2)
    {
        $diffs = [];
        if (empty($arr2)) {
            return $arr1;
        }
        foreach ($arr1 as $value1) {
            $contained = false;
            foreach ($arr2 as $value2) {
                if (0 == $value1['fileId']) {
                    $contained = $value1['link'] == $value2['link'];
                } else {
                    $contained = $value1['fileId'] == $value2['fileId'];
                }
                if ($contained) {
                    break;
                }
            }
            if (!$contained) {
                $diffs[] = $value1;
            }
        }

        return $diffs;
    }

    protected function dirtyMaterials($exists, $currents)
    {
        $diffs = [];
        if (empty($arr2)) {
            return $diffs;
        }
        foreach ($exists as $exist) {
            foreach ($currents as $current) {
                //如果fileId存在则匹配fileId，否则匹配link
                if ((0 != $exist['fileId'] && $exist['fileId'] == $current['fileId'])
                    || (0 == $exist['fileId'] && $exist['link'] == $current['link'])
                ) {
                    $current['id'] = $exist['id'];
                    if (empty($current['description'])) {
                        $current['description'] = $exist['description'];
                    }
                    $diffs[] = $current;
                    break;
                }
            }
        }

        return $diffs;
    }

    protected function filterFields($fields)
    {
        $fields = ArrayToolkit::parts(
            $fields,
            [
                'title',
                'remark',
                'mediaId',
                'mediaType',
                'mediaId',
                'content',
                'length',
                'fromCourseId',
                'fromCourseSetId',
                'fromUserId',
                'startTime',
                'endTime',
                'finishType',
                'finishData',
                'validPeriodMode',
            ]
        );

        if (!empty($fields['startTime']) && !empty($fields['length']) && 'testpaper' != $fields['mediaType']) {
            $fields['endTime'] = $fields['startTime'] + $fields['length'] * 60;
        }

        if ('testpaper' == $fields['mediaType'] && isset($fields['validPeriodMode'])) {
            if (Testpaper::VALID_PERIOD_MODE_ONLY_START == $fields['validPeriodMode']) {
                $fields['endTime'] = 0;
            } elseif (Testpaper::VALID_PERIOD_MODE_NO_LIMIT == $fields['validPeriodMode']) {
                $fields['startTime'] = 0;
                $fields['endTime'] = 0;
            }
            unset($fields['validPeriodMode']);
        }

        if (empty($fields['mediaType'])) {
            unset($fields['mediaType']);
        }

        return $fields;
    }

    protected function invalidActivity($activity)
    {
        if (!ArrayToolkit::requireds(
            $activity,
            [
                'title',
                'mediaType',
                'fromCourseId',
                'fromCourseSetId',
            ]
        )
        ) {
            return true;
        }
        $activity = $this->getActivityConfig($activity['mediaType']);
        if (!is_object($activity)) {
            return true;
        }

        return false;
    }

    /**
     * @param  $fields
     *
     * @return array 多维数组
     */
    public function getMaterialsFromActivity($fields)
    {
        if (!empty($fields['materials'])) {
            return json_decode($fields['materials'], true);
        }

        if (!empty($fields['media'])) {
            $media = json_decode($fields['media'], true);
            if (!empty($media['id'])) {
                $media['fileId'] = $media['id'];
                $media['title'] = $media['name'];

                return [$media];
            }
        }

        return [];
    }

    /**
     * @param  $activity
     *
     * @return mixed
     */
    public function fetchMedia($activity)
    {
        if (!empty($activity['mediaId'])) {
            $activityConfig = $this->getActivityConfig($activity['mediaType']);
            $media = $activityConfig->get($activity['mediaId']);
            $activity['ext'] = $media;
            $activity['customComments'] = $media['customComments'] ?? [];

            return $activity;
        }

        return $activity;
    }

    public function fetchMedias($mediaType, $activities, $showCloud = 1)
    {
        $activityConfig = $this->getActivityConfig($mediaType);

        $mediaIds = ArrayToolkit::column($activities, 'mediaId');
        $medias = $activityConfig->find($mediaIds, $showCloud);

        $medias = ArrayToolkit::index($medias, 'id');

        array_walk(
            $activities,
            function (&$activity) use ($medias) {
                //part of the activity have no extension table
                $activity['ext'] = empty($medias[$activity['mediaId']]) ? [] : $medias[$activity['mediaId']];
            }
        );

        return $activities;
    }

    public function findActivitySupportVideoTryLook($courseIds)
    {
        $activities = $this->getActivityDao()->findSelfVideoActivityByCourseIds($courseIds);
        $cloudFiles = $this->findCloudFilesByMediaIds($activities);
        $activities = array_filter($activities, function ($activity) use ($cloudFiles) {
            return !empty($cloudFiles[$activity['fileId']]);
        });

        return $activities;
    }

    public function isLiveFinished($activityId)
    {
        $activity = $this->getActivity($activityId, true);

        if (empty($activity) || empty($activity['ext'])) {
            return true;
        }

        return EdusohoLiveClient::LIVE_STATUS_CLOSED == $activity['ext']['progressStatus'];
    }

    public function checkLiveStatus($courseId, $activityId)
    {
        $activity = $this->getActivity($activityId, true);
        if (empty($activity)) {
            return ['result' => false, 'message' => 'message_response.live_task_not_exist.message'];
        }

        if ($activity['fromCourseId'] != $courseId) {
            return ['result' => false, 'message' => 'message_response.illegal_params.message'];
        }

        if (empty($activity['ext']['liveId'])) {
            return ['result' => false, 'message' => 'message_response.live_class_not_exist.message'];
        }

        $setting = $this->getSettingService()->get('magic', []);
        $setTime = empty($setting['live_entry_time']) ? self::LIVE_STARTTIME_DIFF_SECONDS : $setting['live_entry_time'];
        $setTime = $this->isTeacher($courseId) ? $setTime : self::LIVE_STARTTIME_DIFF_SECONDS;
        if ($activity['startTime'] - time() > $setTime) {
            return ['result' => false, 'message' => 'message_response.live_not_start.message'];
        }

        if (EdusohoLiveClient::LIVE_STATUS_CLOSED == $activity['ext']['progressStatus']) {
            return ['result' => false, 'message' => 'message_response.live_over.message'];
        }

        return ['result' => true, 'message' => ''];
    }

    protected function isTeacher($courseId)
    {
        $user = $this->getCurrentUser();
        if ($this->getMemberService()->isCourseTeacher($courseId, $user['id'])) {
            return $user->isTeacher();
        }

        return false;
    }

    public function findActivitiesByMediaIdsAndMediaType($mediaIds, $mediaType)
    {
        if (empty($mediaIds)) {
            return [];
        }

        return $this->getActivityDao()->findActivitiesByMediaIdsAndMediaType($mediaIds, $mediaType);
    }

    public function getActivityConfig($type)
    {
        return $this->biz["activity_type.{$type}"];
    }

    public function getActivityByAnswerSceneId($answerSceneId)
    {
        $mediaTypes = [
            ActivityMediaType::HOMEWORK,
            ActivityMediaType::TESTPAPER,
            ActivityMediaType::EXERCISE,
        ];
        foreach ($mediaTypes as $mediaType) {
            $activity = $this->getActivityByAnswerSceneIdAndMediaType($answerSceneId, $mediaType);
            if (!empty($activity)) {
                return $activity;
            }
        }

        return [];
    }

    public function getActivityByAnswerSceneIdAndMediaType($answerSceneId, $mediaType)
    {
        if (ActivityMediaType::HOMEWORK == $mediaType) {
            $mediaActivity = $this->getHomeworkActivityService()->getByAnswerSceneId($answerSceneId);
        }
        if (ActivityMediaType::TESTPAPER == $mediaType) {
            $mediaActivity = $this->getTestpaperActivityService()->getActivityByAnswerSceneId($answerSceneId);
        }
        if (ActivityMediaType::EXERCISE == $mediaType) {
            $mediaActivity = $this->getExerciseActivityService()->getByAnswerSceneId($answerSceneId);
        }

        return empty($mediaActivity) ? [] : $this->getByMediaIdAndMediaType($mediaActivity['id'], $mediaType);
    }

    public function orderAssessmentSubmitNumber($userIds, $answerSceneId)
    {
        $records = $this->getAnswerRecordService()->search(['user_ids' => $userIds, 'answer_scene_id' => $answerSceneId], ['end_time' => 'ASC'], 0, PHP_INT_MAX);
        $records = ArrayToolkit::group($records, 'user_id');
        $orderedRecords = [];
        foreach ($records as $record) {
            $index = 1;
            foreach ($record as $userRecord) {
                $orderedRecords[$userRecord['id']]['answer_record_id'] = $userRecord['id'];
                $orderedRecords[$userRecord['id']]['submit_num'] = $index;
                ++$index;
            }
        }

        return $orderedRecords;
    }

    /**
     * @return AnswerRecordService
     */
    protected function getAnswerRecordService()
    {
        return $this->createService('ItemBank:Answer:AnswerRecordService');
    }

    /**
     * @return MaterialService
     */
    protected function getMaterialService()
    {
        return $this->createService('Course:MaterialService');
    }

    /**
     * @return ActivityDao
     */
    protected function getActivityDao()
    {
        return $this->createDao('Activity:ActivityDao');
    }

    /**
     * @return ActivityLearnLogService
     */
    protected function getActivityLearnLogService()
    {
        return $this->createService('Activity:ActivityLearnLogService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    /**
     * @return UploadFileService
     */
    protected function getUploadFileService()
    {
        return $this->createService('File:UploadFileService');
    }

    /**
     * @param $eventName
     * @param $data
     *
     * @return mixed
     */
    protected function extractLogData($eventName, $data)
    {
        unset($data['task']);
        $logData = $data;
        $logData['event'] = $eventName;

        return $logData;
    }

    /**
     * @param $fetchMedia
     * @param $activities
     * @param $showCloud
     *
     * @return mixed
     */
    protected function prepareActivities($fetchMedia, $activities, $showCloud = 1)
    {
        if (empty($activities)) {
            return $activities;
        }
        $activityGroups = ArrayToolkit::group($activities, 'mediaType');
        if ($fetchMedia) {
            foreach ($activityGroups as $mediaType => $activityGroup) {
                $activityGroups[$mediaType] = $this->fetchMedias($mediaType, $activityGroup, $showCloud);
            }
        }

        $fullActivities = [];
        foreach ($activityGroups as $activityGroup) {
            $fullActivities = array_merge($fullActivities, array_values($activityGroup));
        }

        $activityIds = ArrayToolkit::column($activities, 'id');

        foreach ($fullActivities as $activity) {
            $key = array_search($activity['id'], $activityIds);
            $sortedActivities[$key] = $activity;
        }
        ksort($sortedActivities);

        return $sortedActivities;
    }

    /**
     * @param $activities
     *
     * @return array
     */
    protected function findCloudFilesByMediaIds($activities)
    {
        $fileIds = ArrayToolkit::column($activities, 'fileId');
        $files = $this->getUploadFileService()->findFilesByIds($fileIds);
        $cloudFiles = array_filter($files, function ($file) {
            return 'cloud' === $file['storage'];
        });

        return ArrayToolkit::index($cloudFiles, 'id');
    }

    /**
     * @return TestpaperActivityService
     */
    protected function getTestpaperActivityService()
    {
        return $this->createService('Activity:TestpaperActivityService');
    }

    /**
     * @return HomeworkActivityService
     */
    protected function getHomeworkActivityService()
    {
        return $this->createService('Activity:HomeworkActivityService');
    }

    /**
     * @return ExerciseActivityService
     */
    protected function getExerciseActivityService()
    {
        return $this->createService('Activity:ExerciseActivityService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return MemberService
     */
    protected function getMemberService()
    {
        return $this->createService('Course:MemberService');
    }

    /**
     * @return LiveActivityService
     */
    protected function getLiveActivityService()
    {
        return $this->createService('Activity:LiveActivityService');
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->createService('User:UserService');
    }

    /**
     * @return LogService
     */
    protected function getLogService()
    {
        return $this->createService('System:LogService');
    }
}
