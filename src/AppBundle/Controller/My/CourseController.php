<?php

namespace AppBundle\Controller\My;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\Paginator;
use AppBundle\Controller\Course\CourseBaseController;
use Biz\Assistant\Service\AssistantStudentService;
use Biz\Classroom\Service\ClassroomService;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\CourseSetService;
use Biz\Course\Service\LearningDataAnalysisService;
use Biz\Course\Service\MemberService;
use Biz\Course\Util\CourseTitleUtils;
use Biz\Favorite\Service\FavoriteService;
use Biz\Task\Service\TaskResultService;
use Biz\Task\Service\TaskService;
use Biz\Taxonomy\Service\CategoryService;
use Symfony\Component\HttpFoundation\Request;
use VipPlugin\Biz\Marketing\Service\VipRightService;
use VipPlugin\Biz\Vip\Service\VipService;

class CourseController extends CourseBaseController
{
    public function indexAction()
    {
        if ($this->getCurrentUser()->isTeacher()) {
            return $this->redirect($this->generateUrl('my_teaching_course_sets'));
        } else {
            return $this->redirect($this->generateUrl('my_courses_learning'));
        }
    }

    public function learningAction(Request $request)
    {
        $currentUser = $this->getUser();

        $members = $this->getCourseMemberService()->searchMembers(['userId' => $currentUser['id'], 'role' => 'student'], ['createdTime' => 'desc'], 0, PHP_INT_MAX);
        $members = ArrayToolkit::index($members, 'courseId');

        $courseIds = ArrayToolkit::column($members, 'courseId');
        $courses = $this->getCourseService()->findCoursesByIds($courseIds);
        $courses = ArrayToolkit::sortPerArrayValue($courses, 'createdTime', false);
        $courses = ArrayToolkit::group($courses, 'courseSetId');
        list($learnedCourseSetIds, $learningCourseSetIds) = $this->differentiateCourseSetIds($courses, $members);
        foreach ($members as &$member) {
            $member['lastLearnTime'] = !empty($member['lastLearnTime']) ? $member['lastLearnTime'] : $member['createdTime'];
        }
        array_multisort(ArrayToolkit::column($members, 'lastLearnTime'), SORT_DESC, $members);
        $members = ArrayToolkit::index($members, 'courseId');
        $conditions = [
            'types' => [CourseSetService::NORMAL_TYPE, CourseSetService::LIVE_TYPE],
            'ids' => $learningCourseSetIds,
        ];

        $paginator = new Paginator(
            $request,
            $this->getCourseSetService()->countCourseSets($conditions),
            12
        );
        $conditions['ids'] = array_splice($conditions['ids'], $paginator->getOffsetCount(), $paginator->getOffsetCount() + $paginator->getPerPageCount());
        $courseSets = $this->getCourseSetService()->searchCourseSets(
            $conditions,
            [],
            0,
            PHP_INT_MAX
        );

        $courseSets = ArrayToolkit::index($courseSets, 'id');
        $courseSets = $this->sortCourseSets($courseSets, $members);

        $courseSets = $this->calculateCourseSetprogress($courseSets, $courses);
        $courseSets = $this->getClassrooms($courseSets);

        $learningCourses = $this->getCourseService()->findUserLearningCourses($currentUser['id'], 0, PHP_INT_MAX);

        return $this->render(
            'my/learning/course/learning.html.twig',
            [
                'courses' => $courses,
                'paginator' => $paginator,
                'courseSets' => $courseSets,
                'members' => $members,
                'learningCourses' => $learningCourses,
            ]
        );
    }

    public function learnedAction(Request $request)
    {
        $currentUser = $this->getUser();
        $members = $this->getCourseMemberService()->searchMembers(['userId' => $currentUser['id'], 'role' => 'student'], ['createdTime' => 'desc'], 0, PHP_INT_MAX);
        $members = ArrayToolkit::index($members, 'courseId');

        $courseIds = ArrayToolkit::column($members, 'courseId');
        $courses = $this->getCourseService()->findCoursesByIds($courseIds);

        $courses = ArrayToolkit::group($courses, 'courseSetId');

        list($learnedCourseSetIds, $learningCourseSetIds) = $this->differentiateCourseSetIds($courses, $members);

        $conditions = [
            'ids' => $learnedCourseSetIds,
        ];
        $paginator = new Paginator(
            $request,
            $this->getCourseSetService()->countCourseSets($conditions),
            12
        );

        $courseSets = $this->getCourseSetService()->searchCourseSets(
            $conditions,
            [],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $courseSets = $this->calculateCourseSetprogress($courseSets, $courses);
        $courseSets = $this->getClassrooms($courseSets);

        return $this->render(
            'my/learning/course/learned.html.twig',
            [
                'courses' => $courses,
                'courseSets' => $courseSets,
                'paginator' => $paginator,
                'members' => $members,
            ]
        );
    }

    public function headerForMemberAction($course, $member)
    {
        if ('classroom' === $member['joinedType'] && !empty($member['classroomId'])) {
            $classroomMember = $this->getClassroomService()->getClassroomMember($member['classroomId'], $member['userId']);
            $member['locked'] = $classroomMember['locked'];
        }
        $courseSet = $this->getCourseSetService()->getCourseSet($course['courseSetId']);
        $courses = $this->getCourseService()->findPublishedCoursesByCourseSetId($course['courseSetId']);
        $breadcrumbs = $this->getCategoryService()->findCategoryBreadcrumbs($courseSet['categoryId']);
        if (empty($member['previewAs'])) {
            $learnProgress = $this->getLearningDataAnalysisService()->getUserLearningSchedule($course['id'], $member['userId']);
        } else {
            $learnProgress = [
                'taskCount' => 0,
                'progress' => 0,
                'taskResultCount' => 0,
                'toLearnTasks' => 0,
                'taskPerDay' => 0,
                'planStudyTaskCount' => 0,
                'planProgressProgress' => 0,
            ];
        }
        $isUserFavorite = false;
        $user = $this->getUser();
        if ($user->isLogin()) {
            $isUserFavorite = !empty($this->getFavoriteService()->getUserFavorite($user['id'], 'course', $course['courseSetId']));
        }
        $vipSetting = $this->getSettingService()->get('vip', []);
        $vipDeadline = false;
        if ($this->isPluginInstalled('Vip') && !empty($vipSetting['enabled']) && 'student' === $member['role'] && 'vip_join' == $member['joinedChannel']) {
            $vipMember = $this->getVipService()->getMemberByUserId($member['userId']);
            $courseVipRight = $this->getVipRightService()->getVipRightBySupplierCodeAndUniqueCode('course', $course['id']);
            $classroom = $this->getClassroomService()->getClassroomByCourseId($course['id']);
            if (!empty($classroom)) {
                $classroomVipRight = $this->getVipRightService()->getVipRightBySupplierCodeAndUniqueCode('classroom', $classroom['id']);
            }
            if (!empty($vipMember) && (!empty($courseVipRight) || !empty($classroomVipRight))) {
                $vipDeadline = true;
                $member['deadline'] = ($vipMember['deadline'] < $member['deadline']) || empty($member['deadline']) ? $vipMember['deadline'] : $member['deadline'];
            }
        }

        return $this->render(
            'course/header/header-for-member.html.twig',
            [
                'courseSet' => $courseSet,
                'courses' => $courses,
                'course' => $course,
                'member' => $member,
                'taskCount' => $learnProgress['taskCount'],
                'progress' => $learnProgress['progress'],
                'taskResultCount' => $learnProgress['taskResultCount'],
                'toLearnTasks' => $learnProgress['toLearnTasks'],
                'taskPerDay' => $learnProgress['taskPerDay'],
                'planStudyTaskCount' => $learnProgress['planStudyTaskCount'],
                'planProgressProgress' => $learnProgress['planProgressProgress'],
                'isUserFavorite' => $isUserFavorite,
                'marketingPage' => 0,
                'breadcrumbs' => $breadcrumbs,
                'vipDeadline' => $vipDeadline,
            ]
        );
    }

    public function showAction(Request $request, $id, $tab = 'tasks')
    {
        $course = $this->getCourseService()->getCourse($id);

        $user = $this->getCurrentUser();
        if (!$user->isLogin()) {
            return $this->redirect($this->generateUrl('course_show', ['id' => $id, 'tab' => $tab]));
        }
        $member = $this->getCourseMember($request, $course);
        $classroom = [];
        if ($course['parentId'] > 0) {
            $classroom = $this->getClassroomService()->getClassroomByCourseId($course['id']);
            // 访问班级课程时确保将用户添加到课程member中
            if (!empty($classroom) && empty($member)) {
                $this->joinCourseMemberByClassroomId($course['id'], $classroom['id']);
            }
        }
        // 非班级课程，点击介绍跳转到概览商品页
        if (empty($member) || (0 === (int) $course['parentId'] && 'summary' === $tab)) {
            return $this->redirect($this->generateUrl('course_show', ['id' => $id, 'tab' => $tab]));
        }
        $tags = $this->findCourseSetTagsByCourseSetId($course['courseSetId']);
        $hasMulCoursePlans = $this->getCourseService()->hasMulCourses($course['courseSetId']);
        if ($hasMulCoursePlans) {
            $course['title'] = CourseTitleUtils::getDisplayedTitle($course);
        } else {
            if (!empty($course['courseSetTitle'])) {
                $course['title'] = $course['courseSetTitle'];
            }
        }
        $assistant = [];
        $assistantStudent = $this->getAssistantStudentService()->getByStudentIdAndCourseId($member['userId'], $id);
        if (!empty($assistantStudent)) {
            $assistant = $this->getUserService()->getUser($assistantStudent['assistantId']);
        }

        return $this->render(
            'course/course-show.html.twig',
            [
                'tab' => $tab,
                'tags' => $tags,
                'member' => $member,
                'isCourseTeacher' => in_array($member['role'], ['teacher', 'assistant']),
                'course' => $course,
                'classroom' => $classroom,
                'hasAssistant' => !empty($assistant['weChatQrCode']),
            ]
        );
    }

    public function tasksAction($course, $member = [])
    {
        $course['taskDisplay'] = 1;
        list($courseItems, $nextOffsetSeq) = $this->getCourseService()->findCourseItemsByPaging($course['id'], ['limit' => 10000]);

        return $this->render(
            'course/tabs/tasks.html.twig',
            [
                'course' => $course,
                'courseItems' => $courseItems,
                'nextOffsetSeq' => $nextOffsetSeq,
                'member' => $member,
                'optionalTaskCount' => $this->getTaskService()->countTasks(
                    [
                        'courseId' => $course['id'],
                        'status' => 'published',
                        'isOptional' => 1,
                    ]
                ),
            ]
        );
    }

    public function learningLivesCalendarAction(Request $request)
    {
        return $this->render(
            'my/learning/lives-calendar.html.twig'
        );
    }

    /**
     * 当用户是班级学员却不在课程学员中时，将学员添加到课程学员中.
     *
     * @param $courseId
     * @param $classroomId
     */
    protected function joinCourseMemberByClassroomId($courseId, $classroomId)
    {
        $classroom = $this->getClassroomService()->getClassroom($classroomId);
        $user = $this->getCurrentUser();

        $classroomMember = $this->getClassroomService()->getClassroomMember($classroom['id'], $user['id']);

        if (empty($classroomMember) || !in_array('student', $classroomMember['role'])) {
            return;
        }

        $info = [
            'joinedChannel' => $classroomMember['joinedChannel'],
            'deadline' => $classroomMember['deadline'],
        ];

        $this->getMemberService()->createMemberByClassroomJoined($courseId, $user['id'], $classroom['id'], $info);
    }

    protected function sortCourseSets($courseSets, $members)
    {
        $sort = [];
        foreach ($members as $member) {
            if (empty($courseSets[$member['courseSetId']])) {
                continue;
            }

            if (!empty($sort[$member['courseSetId']])) {
                continue;
            }

            $sort[$member['courseSetId']] = $courseSets[$member['courseSetId']];
        }

        return $sort;
    }

    protected function calculateCourseSetprogress($courseSets, $courses)
    {
        if (empty($courseSets)) {
            return [];
        }

        $user = $this->getCurrentUser();

        foreach ($courseSets as $courseSetId => $courseSet) {
            $currentCourses = $courses[$courseSet['id']];
            $courseIds = ArrayToolkit::column($currentCourses, 'id');

            $learnProgress = $this->getLearningDataAnalysisService()->getUserLearningCompulsoryProgressByCourseIds($courseIds, $user['id']);

            $courseSets[$courseSetId]['percent'] = $learnProgress['percent'];
        }

        return $courseSets;
    }

    protected function getClassrooms($courseSets)
    {
        if (empty($courseSets)) {
            return [];
        }

        $courseSetIds = ArrayToolkit::column($courseSets, 'id');
        $classroomCourses = $this->getClassroomService()->findClassroomsByCourseSetIds($courseSetIds);
        $classroomCourses = ArrayToolkit::index($classroomCourses, 'courseSetId');
        $classroomIds = ArrayToolkit::column($classroomCourses, 'classroomId');

        $classrooms = $this->getClassroomService()->findClassroomsByIds($classroomIds);

        foreach ($courseSets as $courseSetId => $courseSet) {
            if (0 == $courseSet['parentId'] || empty($classroomCourses[$courseSet['id']])) {
                continue;
            }

            $classroomCourse = $classroomCourses[$courseSet['id']];
            $classroom = $classrooms[$classroomCourse['classroomId']];
            $courseSets[$courseSetId]['classroom'] = [
                'id' => $classroom['id'],
                'title' => $classroom['title'],
            ];
        }

        return $courseSets;
    }

    protected function differentiateCourseSetIds($groupCourses, $members)
    {
        if (empty($groupCourses)) {
            return [[-1], [-1]];
        }
        $members = ArrayToolkit::index($members, 'courseId');
        $learnedCourseSetIds = [];
        $learningCourseSetIds = [];
        foreach ($groupCourses as $courseSetId => $courses) {
            $isLearned = 1;
            array_map(function ($course) use ($members, &$isLearned) {
                $member = $members[$course['id']];
                if ($member['learnedCompulsoryTaskNum'] < $course['compulsoryTaskNum'] or 0 == $course['compulsoryTaskNum']) {
                    $isLearned = 0;
                }
            }, $courses);

            if ($isLearned) {
                array_push($learnedCourseSetIds, $courseSetId);
            } else {
                array_push($learningCourseSetIds, $courseSetId);
            }
        }

        return [$learnedCourseSetIds ?: [-1], $learningCourseSetIds ?: [-1]];
    }

    /**
     * @return TaskResultService
     */
    protected function getTaskResultService()
    {
        return $this->createService('Task:TaskResultService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->createService('Task:TaskService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    /**
     * @return ClassroomService
     */
    protected function getClassroomService()
    {
        return $this->createService('Classroom:ClassroomService');
    }

    /**
     * @return CategoryService
     */
    private function getCategoryService()
    {
        return $this->createService('Taxonomy:CategoryService');
    }

    /**
     * @return LearningDataAnalysisService
     */
    private function getLearningDataAnalysisService()
    {
        return $this->createService('Course:LearningDataAnalysisService');
    }

    /**
     * @return MemberService
     */
    protected function getCourseMemberService()
    {
        return $this->createService('Course:MemberService');
    }

    /**
     * @return AssistantStudentService
     */
    protected function getAssistantStudentService()
    {
        return $this->createService('Assistant:AssistantStudentService');
    }

    /**
     * @return FavoriteService
     */
    protected function getFavoriteService()
    {
        return $this->createService('Favorite:FavoriteService');
    }

    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return VipRightService
     */
    protected function getVipRightService()
    {
        return $this->createService('VipPlugin:Marketing:VipRightService');
    }

    /**
     * @return VipService
     */
    protected function getVipService()
    {
        return $this->createService('VipPlugin:Vip:VipService');
    }

    protected function getCourseThreadService()
    {
        return $this->createService('Course:ThreadService');
    }
}
