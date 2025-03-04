<?php

namespace Biz\Course\Service\Impl;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\SimpleValidator;
use Biz\BaseService;
use Biz\Common\CommonException;
use Biz\Course\Dao\ReportDao;
use Biz\Course\Service\CourseNoteService;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\MemberService;
use Biz\Course\Service\ReportService;
use Biz\Course\Service\ThreadService;
use Biz\Task\Service\TaskResultService;
use Biz\Task\Service\TaskService;
use Biz\Task\Service\TryViewLogService;
use Biz\User\Service\UserService;

class ReportServiceImpl extends BaseService implements ReportService
{
    private static $mockStartTime;

    public function summary($courseId)
    {
        $course = $this->getCourseService()->getCourse($courseId);
        $defaultSummary = [
            'studentNum' => 0,
            'studentNumToday' => 0,
            'finishedNum' => 0,
            'finishedNumToday' => 0,
            'tryViewNum' => 0,
            'tryViewNumToday' => 0,
            'noteNum' => 0,
            'noteNumToday' => 0,
            'askNum' => 0,
            'askNumToday' => 0,
            'discussionNum' => 0,
            'discussionNumToday' => 0,
        ];

        $summary = [];

        $startTime = strtotime(date('Y-m-d'));

        //仅供单元测试
        if (!empty(self::$mockStartTime)) {
            $startTime = self::$mockStartTime;
        }

        $summary['studentNum'] = $this->getCourseMemberService()->countMembers(['courseId' => $courseId, 'role' => 'student']);
        $summary['studentNumToday'] = $this->getCourseMemberService()->countMembers(['courseId' => $courseId, 'role' => 'student', 'startTimeGreaterThan' => $startTime]);
        $summary['finishedNum'] = $this->getCourseMemberService()->countMembers([
            'role' => 'student',
            'learnedCompulsoryTaskNumGreaterThan' => $course['compulsoryTaskNum'],
            'courseId' => $courseId,
        ]);
        $summary['finishedNumToday'] = $this->getCourseMemberService()->countMembers([
            'role' => 'student',
            'learnedCompulsoryTaskNumGreaterThan' => $course['compulsoryTaskNum'],
            'courseId' => $courseId,
            'lastLearnTimeGreaterThan' => $startTime,
        ]);
        $summary['tryViewNum'] = $this->getTaskTryViewService()->countTryViewLogs(['courseId' => $courseId]);
        $summary['tryViewNumToday'] = $this->getTaskTryViewService()->countTryViewLogs(['courseId' => $courseId, 'createdTime_GE' => $startTime]);
        $summary['noteNum'] = $this->getCourseNoteService()->countCourseNotes(['courseId' => $courseId]);
        $summary['noteNumToday'] = $this->getCourseNoteService()->countCourseNotes(['courseId' => $courseId, 'startTimeGreaterThan' => $startTime]);
        $summary['askNum'] = $this->getThreadService()->countThreads(['courseId' => $courseId, 'type' => 'question']);
        $summary['askNumToday'] = $this->getThreadService()->countThreads(['courseId' => $courseId, 'type' => 'question', 'startCreatedTime' => $startTime]);
        $summary['discussionNum'] = $this->getThreadService()->countThreads(['courseId' => $courseId, 'type' => 'discussion']);
        $summary['discussionNumToday'] = $this->getThreadService()->countThreads(['courseId' => $courseId, 'type' => 'discussion', 'startCreatedTime' => $startTime]);

        $summary = array_merge($defaultSummary, $summary);

        return $summary;
    }

    public function getCompletionRateTrend($courseId, $startDate, $endDate)
    {
        $course = $this->getCourseService()->getCourse($courseId);

        $historyData = $this->getReportDao()->findCompleteCourseCountGroupByDate($courseId, 0, strtotime('-1 day', strtotime($startDate)));

        $userPickData = $this->getReportDao()->findCompleteCourseCountGroupByDate($courseId, strtotime($startDate), strtotime('+1 day', strtotime($endDate) - 1));

        $total = 0;
        foreach ($historyData as $singleData) {
            $total += $singleData['count'];
        }

        $end = new \DateTime($endDate);
        $end->modify('+1 day');
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            $end
        );

        $userPickData = ArrayToolkit::index($userPickData, 'date');

        $result = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            if (isset($userPickData[$dateStr])) {
                $total += $userPickData[$dateStr]['count'];
            }

            $result[] = [
                'date' => $dateStr,
                'finishedNum' => $total,
                'finishedRate' => $this->getPercent($total, $course['studentNum']),
            ];
        }

        return $result;
    }

    public function getStudentTrend($courseId, $timeRange)
    {
        $studentIncreaseData = $this->getCourseMemberService()->findDailyIncreaseNumByCourseIdAndRoleAndTimeRange($courseId, 'student', $timeRange);
        $tryViewIncreaseData = $this->getTaskTryViewService()->searchLogCountsByCourseIdAndTimeRange($courseId, $timeRange);
        $end = new \DateTime($timeRange['endDate']);
        $end->modify('+1 day');
        $period = new \DatePeriod(
            new \DateTime($timeRange['startDate']),
            new \DateInterval('P1D'),
            $end
        );

        $studentIncreaseData = ArrayToolkit::index($studentIncreaseData, 'date');
        $tryViewIncreaseData = ArrayToolkit::index($tryViewIncreaseData, 'date');

        $result = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $studentIncreaseNum = isset($studentIncreaseData[$dateStr]) ? $studentIncreaseData[$dateStr]['count'] : 0;
            $tryViewIncreaseNum = isset($tryViewIncreaseData[$dateStr]) ? $tryViewIncreaseData[$dateStr]['count'] : 0;
            $result[] = [
                'date' => $dateStr,
                'studentIncrease' => $studentIncreaseNum,
                'tryViewIncrease' => $tryViewIncreaseNum,
            ];
        }

        return $result;
    }

    public function getStudentDetail($courseId, $userIds, $taskLimit = 20)
    {
        $users = $this->getUserService()->searchUsers(['userIds' => $userIds], [], 0, count($userIds));
        $users = ArrayToolkit::index($users, 'id');
        $userProfiles = $this->getUserService()->findUserProfilesByIds($userIds);
        $userProfiles = ArrayToolkit::index($userProfiles, 'id');
        $courseTasks = $this->getTaskService()->searchTasks(
            [
                'courseId' => $courseId,
                'isOptional' => 0,
                'status' => 'published',
            ],
            ['seq' => 'ASC'],
            0,
            $taskLimit
        );
        $taskIds = ArrayToolkit::column($courseTasks, 'id');

        $taskResults = $this->getTaskResultService()->searchTaskResults(
            [
                'courseId' => $courseId,
                'userIds' => $userIds,
                'courseTaskIds' => $taskIds,
            ],
            [],
            0,
            PHP_INT_MAX
        );

        $taskResults = ArrayToolkit::groupIndex($taskResults, 'userId', 'courseTaskId');

        return [$users, $courseTasks, $taskResults, $userProfiles];
    }

    public function buildStudentDetailOrderBy($conditions)
    {
        $orderBy = ['createdTime' => 'DESC'];
        if (!empty($conditions['orderBy'])) {
            switch ($conditions['orderBy']) {
                case 'createdTimeDesc':
                    $orderBy = ['createdTime' => 'DESC'];
                    break;
                case 'createdTimeAsc':
                    $orderBy = ['createdTime' => 'ASC'];
                    break;
                case 'learnedCompulsoryTaskNumDesc':
                    $orderBy = ['learnedCompulsoryTaskNum' => 'DESC'];
                    break;
                case 'learnedCompulsoryTaskNumAsc':
                    $orderBy = ['learnedCompulsoryTaskNum' => 'ASC'];
                    break;
            }
        }

        return $orderBy;
    }

    public function buildStudentDetailConditions($conditions, $courseId)
    {
        $course = $this->getCourseService()->getCourse($courseId);
        $memberConditions = [
            'courseId' => $course['id'],
            'role' => 'student',
        ];

        if (!empty($conditions['range'])) {
            switch ($conditions['range']) {
                case 'unLearnedSevenDays':
                    $endTime = strtotime(date('Y-m-d', strtotime('-7 days')));
                    $memberConditions['lastLearnTimeLessThen'] = $endTime;
                    $memberConditions['learnedCompulsoryTaskNumLT'] = $course['compulsoryTaskNum'];
                    break;
                case 'unFinished':
                    $memberConditions['learnedCompulsoryTaskNumLT'] = $course['compulsoryTaskNum'];
                    break;
            }
        }

        if (!empty($conditions['nameOrMobile'])) {
            $mobile = SimpleValidator::mobile($conditions['nameOrMobile']);
            if ($mobile) {
                $user = $this->getUserService()->getUserByVerifiedMobile($conditions['nameOrMobile']);
                $users = empty($user) ? [] : [$user];
            } else {
                $users = $this->getUserService()->searchUsers(
                    ['nickname' => $conditions['nameOrMobile']],
                    [],
                    0,
                    PHP_INT_MAX
                );
            }

            if (empty($users)) {
                $memberConditions['userId'] = 0;
            } else {
                $userIds = ArrayToolkit::column($users, 'id');
                $memberConditions['userIds'] = $userIds;
            }
        }

        return $memberConditions;
    }

    public function searchUserIdsByCourseIdAndFilterAndSortAndKeyword($courseId, $filter, $sort, $start, $limit)
    {
        $conditions = $this->prepareCourseIdAndFilter($courseId, $filter);
        $orderBy = $this->prepareSort($sort);
        $userIds = $this->getCourseMemberService()->searchMemberIds($conditions, $orderBy, $start, $limit);

        return $userIds;
    }

    public function prepareCourseIdAndFilter($courseId, $filter)
    {
        switch ($filter) {
            case 'all':
                $conditions = [
                    'courseId' => $courseId,
                    'role' => 'student',
                ];
                break;
            case 'unLearnedSevenDays':
                $endTime = strtotime(date('Y-m-d', strtotime('-7 days')));
                $conditions = [
                    'courseId' => $courseId,
                    'role' => 'student',
                    'lastLearnTimeLessThen' => $endTime,
                    'isLearned' => 0,
                ];
                break;
            case 'unFinished':
                $conditions = [
                    'courseId' => $courseId,
                    'role' => 'student',
                    'isLearned' => 0,
                ];
                break;
            default:
                $this->createNewException(CommonException::ERROR_PARAMETER());
        }

        return $conditions;
    }

    public function prepareSort($sort)
    {
        switch ($sort) {
            case 'createdTimeDesc':
                $orderBy = ['createdTime' => 'DESC'];
                break;
            case 'createdTimeAsc':
                $orderBy = ['createdTime' => 'ASC'];
                break;
            case 'CompletionRateDesc':
                $orderBy = ['learnedCompulsoryTaskNum' => 'DESC'];
                break;
            case 'CompletionRateDAsc':
                $orderBy = ['learnedCompulsoryTaskNum' => 'ASC'];
                break;
            default:
                $this->createNewException(CommonException::ERROR_PARAMETER());
        }

        return $orderBy;
    }

    public function getCourseTaskLearnData($tasks, $courseId)
    {
        if (empty($tasks)) {
            return [];
        }

        $course = $this->getCourseService()->getCourse($courseId);
        $studentNum = $course['studentNum'];
        foreach ($tasks as &$task) {
            if ('published' !== $task['status']) {
                $task['finishedNum'] = $task['learnNum'] = $task['notStartedNum'] = $task['rate'] = 0;

                continue;
            }

            $task['finishedNum'] = $this->getTaskResultService()->countUsersByTaskIdAndLearnStatus($task['id'], 'finish');
            $task['learnNum'] = $this->getTaskResultService()->countUsersByTaskIdAndLearnStatus($task['id'], 'start');
            $task['notStartedNum'] = $studentNum - $task['finishedNum'] - $task['learnNum'];
            $task['rate'] = $this->getPercent($task['finishedNum'], $studentNum);
        }

        return $tasks;
    }

    private function getPercent($count, $total)
    {
        $percent = 0 == $total ? 0 : round($count * 100 / $total, 3);

        return $percent > 100 ? 100 : $percent;
    }

    //仅供单元测试
    public function mockStartTime($mockStartTime)
    {
        self::$mockStartTime = $mockStartTime;
    }

    /**
     * @return CourseNoteService
     */
    protected function getCourseNoteService()
    {
        return $this->createService('Course:CourseNoteService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    /**
     * @return MemberService
     */
    protected function getCourseMemberService()
    {
        return $this->createService('Course:MemberService');
    }

    /**
     * @return ThreadService
     */
    protected function getThreadService()
    {
        return $this->createService('Course:ThreadService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->createService('Task:TaskService');
    }

    /**
     * @return TaskResultService
     */
    protected function getTaskResultService()
    {
        return $this->createService('Task:TaskResultService');
    }

    /**
     * @return TryViewLogService
     */
    protected function getTaskTryViewService()
    {
        return $this->createService('Task:TryViewLogService');
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->createService('User:UserService');
    }

    /**
     * @return ReportDao
     */
    protected function getReportDao()
    {
        return $this->createDao('Course:ReportDao');
    }
}
