<?php

namespace Biz\Task\Service;

use Biz\System\Annotation\Log;

interface TaskService
{
    const LEARN_TIME_STEP = 60;

    const WATCH_TIME_STEP = 120;

    public function getTask($id);

    public function getCourseTask($courseId, $id);

    public function getCourseTaskByCourseIdAndCopyId($courseId, $copyId);

    public function preCreateTaskCheck($task);

    /**
     * @param $task
     *
     * @return mixed
     * @Log(module="course",action="add_task")
     */
    public function createTask($task);

    public function batchCreateTasks($tasks);

    public function preUpdateTaskCheck($taskId, $fields);

    /**
     * @param $id
     * @param $fields
     *
     * @return mixed
     * @Log(module="course",action="update_task",param="id")
     */
    public function updateTask($id, $fields);

    public function updateSeq($id, $fields);

    public function updateTasks($Ids, $fields);

    public function publishTask($id);

    public function unpublishTask($id);

    /**
     * @param $id
     *
     * @return mixed
     * @Log(module="course",action="delete_task")
     */
    public function deleteTask($id);

    public function deleteTasksByCategoryId($courseId, $categoryId);

    public function deleteTasks(array $ids);

    public function findTasksByCourseId($courseId);

    public function findTasksByCourseSetId($courseSetId);

    public function findTasksByCategoryIds($categoryIds);

    public function findTasksByCourseIds($courseIds);

    public function findTasksByCourseIdAndType($courseId, $type);

    public function countTasksByCourseId($courseId);

    public function findTasksByActivityIds($activityIds);

    public function searchTasks($conditions, $orderBy, $start, $limit, $columns = []);

    public function findTestpapers($tasks, $type);

    public function countTasks($conditions);

    /**
     * @return array
     */
    public function findTasksByIds(array $ids);

    public function findTasksFetchActivityByCourseId($courseId);

    public function findTasksFetchActivityAndResultByCourseId($courseId);

    public function wrapTaskResultToTasks($courseId, $tasks);

    /**
     * for question and testpaper ranges.
     *
     * @param [type] $userId
     * @param [type] $courseSetId
     *
     * @return array
     */
    public function findUserTeachCoursesTasksByCourseSetId($userId, $courseSetId);

    public function isPreTasksIsFinished($preTasks);

    public function startTask($taskId);

    public function doTask($taskId, $time = self::LEARN_TIME_STEP);

    public function watchTask($taskId, $watchTime = self::WATCH_TIME_STEP);

    public function finishTask($taskId);

    public function isFinished($taskId, $userId = 0);

    public function tryTakeTask($taskId);

    public function trigger($id, $eventName, $data = []);

    /**
     * return next Task that can be learned of the  course plan, or return empty array().
     *
     * @param  $taskId
     *
     * @return mixed
     */
    public function getNextTask($taskId);

    /**
     *  return if the task can learn or not.
     *
     * @param  $taskId
     *
     * @return true|false
     */
    public function canLearnTask($taskId);

    // 任务是否可学

    /**
     * return if the task has been learned.
     *
     * @param  $taskId
     *
     * @return true|false
     */
    public function isTaskLearned($taskId);

    public function getMaxSeqByCourseId($courseId);

    public function getMaxNumberByCourseId($courseId);

    public function findTasksByChapterId($chapterId);

    public function findTasksFetchActivityByChapterId($chapterId);

    public function finishTaskResult($taskId, $userId = 0);

    public function findFreeTasksByCourseId($courseId);

    /**
     * 设置当前任务最大可同时进行的人数  如直播任务等.
     *
     * @param  $taskId
     * @param  $maxNum
     *
     * @return mixed
     */
    public function setTaskMaxOnlineNum($taskId, $maxNum);

    /**
     * 返回当前正在直播的直播任务
     *
     * @return array
     */
    public function findCurrentLiveTasks();

    /**
     * 返回当前将要直播的直播任务
     *
     * @return array
     */
    public function findFutureLiveTasks();

    /**
     * 返回过去直播过的课程ID.
     *
     * @return array<int>
     */
    public function findPastLivedCourseSetIds();

    /**
     * 自由式
     * 1.获取所有的在学中的任务结果，如果为空，则学员学员未开始学习或者已经学完，取第一个任务作为下一个学习任务，
     * 2.如果不为空，则按照任务序列返回第一个作为下一个学习任务
     * 任务式
     * 1.获取所有的学完的任务结果，如果为空，则学员学员未开始学习或者已经学完，取第前三个作为任务，
     * 2.如果不为空，则取关联的三个。
     *
     * 自由式和任务式的逻辑由任务策略完成
     *
     * @param  $courseId
     *
     * @return array tasks
     */
    public function findToLearnTasksByCourseId($courseId);

    /**
     * 侧边栏的任务中心不区分课程类型.
     *
     * @param $courseId
     *
     * @return mixed
     */
    public function findToLearnTasksByCourseIdForMission($courseId);

    public function getTimeSec($type);

    public function getTaskByCourseIdAndActivityId($courseId, $activityId);

    public function analysisTaskDataByTime($startTime, $endTime);

    /**
     * 获取用户最近进行的一个任务
     *
     * @param int $userId
     *
     * @return array
     */
    public function getUserRecentlyStartTask($userId);

    public function findPublishedLivingTasksByCourseSetId($courseSetId);

    public function findPublishedTasksByCourseSetId($courseSetId);

    public function getTodayLiveCourseNumber();

    public function countTasksByChpaterId($chapterId);

    public function updateTasksOptionalByLessonId($lessonId, $isOptional = 0);

    public function countLessonsWithMultipleTasks($courseId);

    public function getUserCurrentPublishedLiveTask($userId, $startTime, $endBeforeRange);

    public function canStartTask($taskId);

    public function searchTasksWithStatistics(array $conditions, $orderBy, $start, $limit);

    public function findTasksByCopyIdAndLockedCourseIds($copyId, $courseIds);

    public function syncClassroomCourseTasks($courseId, $real);

    public function isTaskLocked($taskId);
}
