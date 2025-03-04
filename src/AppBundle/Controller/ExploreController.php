<?php

namespace AppBundle\Controller;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\Paginator;
use Biz\Activity\Service\ActivityService;
use Biz\Classroom\Service\ClassroomService;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\CourseSetService;
use Biz\OpenCourse\Service\OpenCourseService;
use Biz\System\Service\SettingService;
use Biz\Task\Service\TaskService;
use Biz\Taxonomy\Service\CategoryService;
use Biz\Taxonomy\Service\TagService;
use Symfony\Component\HttpFoundation\Request;
use VipPlugin\Biz\Marketing\Service\VipRightService;

class ExploreController extends BaseController
{
    const EMPTY_COURSE_SET_IDS = 0;

    public function courseSetsAction(Request $request, $category)
    {
        $conditions = $request->query->all();

        list($conditions, $filter) = $this->getFilter($conditions, 'course');

        list($conditions, $tags) = $this->getConditionsByTags($conditions);
        $conditions = $this->getCourseConditionsByTags($conditions);

        list($conditions, $categoryArray, $categoryParent) = $this->mergeConditionsByCategory($conditions, $category);

        $conditions = $this->getConditionsByVip($conditions, $filter['currentLevelId'], 'course');

        unset($conditions['code']);

        if (isset($conditions['ids']) && empty($conditions['ids'])) {
            $conditions['ids'] = [-1];
        }
        list($conditions, $orderBy) = $this->getCourseSetSearchOrderBy($conditions);
        $conditions = $this->getCourseSetFilterType($conditions);

        $conditions['parentId'] = 0;
        $conditions['status'] = 'published';

        $paginator = new Paginator(
            $this->get('request'),
            $this->getCourseSetService()->countCourseSets($conditions),
            20
        );

        $courseSets = [];
        if ('recommendedSeq' !== $orderBy) {
            $courseSets = $this->getCourseSetService()->searchCourseSets(
                $conditions,
                $orderBy,
                $paginator->getOffsetCount(),
                $paginator->getPerPageCount()
            );
        }

        if ('recommendedSeq' === $orderBy) {
            $conditions['recommended'] = 1;
            $recommendCount = $this->getCourseSetService()->countCourseSets($conditions);
            $currentPage = $request->query->get('page') ? $request->query->get('page') : 1;
            $recommendPage = (int) ($recommendCount / 20);
            $recommendLeft = $recommendCount % 20;

            if ($currentPage <= $recommendPage) {
                $courseSets = $this->getCourseSetService()->searchCourseSets(
                    $conditions,
                    $orderBy,
                    ($currentPage - 1) * 20,
                    20
                );
            } elseif (($recommendPage + 1) == $currentPage) {
                $courseSets = $this->getCourseSetService()->searchCourseSets(
                    $conditions,
                    $orderBy,
                    ($currentPage - 1) * 20,
                    20
                );
                $conditions['recommended'] = 0;
                $coursesTemp = $this->getCourseSetService()->searchCourseSets(
                    $conditions,
                    ['createdTime' => 'DESC'],
                    0,
                    20 - $recommendLeft
                );
                $courseSets = array_merge($courseSets, $coursesTemp);
            } else {
                $conditions['recommended'] = 0;
                $courseSets = $this->getCourseSetService()->searchCourseSets(
                    $conditions,
                    ['createdTime' => 'DESC'],
                    (20 - $recommendLeft) + ($currentPage - $recommendPage - 2) * 20,
                    20
                );
            }
        }

        $courseSets = ArrayToolkit::index($courseSets, 'id');
        $courses = $this->getCourseService()->findCoursesByCourseSetIds(ArrayToolkit::column($courseSets, 'id'));
        $courses = $this->getCourseService()->fillCourseTryLookVideo($courses);

        $tryLookVideoCourses = array_filter($courses, function ($course) {
            return !empty($course['tryLookVideo']);
        });
        $courses = ArrayToolkit::index($courses, 'courseSetId');
        $tryLookVideoCourses = ArrayToolkit::index($tryLookVideoCourses, 'courseSetId');

        array_walk($courseSets, function (&$courseSet) use ($courses, $tryLookVideoCourses) {
            if (isset($tryLookVideoCourses[$courseSet['id']])) {
                $courseSet['course'] = $tryLookVideoCourses[$courseSet['id']];
            } else {
                $courseSet['course'] = $courses[$courseSet['id']];
            }
        });

        return $this->render(
            'course-set/explore.html.twig',
            [
                'courseSets' => $this->getWebExtension()->filterCourseSetsVipRight($courseSets),
                'category' => $category,
                'filter' => $filter,
                'paginator' => $paginator,
                'consultDisplay' => true,
                'categoryArray' => $categoryArray,
                'categoryParent' => $categoryParent,
                'levels' => $this->findEnabledVipLevels(),
                'tags' => $tags,
                'isWxClient' => $this->isWxClient(),
                'isMobileClient' => $this->isMobileClient(),
            ]
        );
    }

    public function openCourseAction(Request $request, $category)
    {
        $conditions = $request->query->all();

        list($conditions, $tags) = $this->getConditionsByTags($conditions);
        $conditions = $this->getCourseConditionsByTags($conditions, 'openCourse');

        list($conditions, $categoryArray, $categoryParent) = $this->mergeConditionsByCategory($conditions, $category);
        $conditions = $this->_filterOpenCourseConditions($conditions);
        $pageSize = 18;

        $paginator = new Paginator(
            $this->get('request'),
            $this->getOpenCourseService()->countCourses($conditions),
            $pageSize
        );

        $courses = $this->_getPageRecommendedCourses($request, $conditions, 'recommendedSeq', $pageSize);
        $teachers = $this->findCourseTeachers($courses);

        return $this->render('open-course/explore.html.twig', [
            'courses' => $courses,
            'paginator' => $paginator,
            'teachers' => $teachers,
            'category' => $category,
            'categoryArray' => $categoryArray,
            'categoryParent' => $categoryParent,
            'tags' => $tags,
        ]);
    }

    protected function mergeConditionsByCategory($conditions, $category)
    {
        $categoryArray = [];
        $subCategory = empty($conditions['subCategory']) ? null : $conditions['subCategory'];
        $thirdLevelCategory = empty($conditions['selectedthirdLevelCategory']) ? null : $conditions['selectedthirdLevelCategory'];

        if (!empty($subCategory) && empty($thirdLevelCategory)) {
            $conditions['code'] = $subCategory;
        } elseif (!empty($thirdLevelCategory)) {
            $conditions['code'] = $thirdLevelCategory;
        } else {
            $conditions['code'] = $category;
        }

        if (!empty($conditions['code'])) {
            $categoryArray = $this->getCategoryService()->getCategoryByCode($conditions['code']);
            $conditions['categoryId'] = $categoryArray['id'];
            unset($conditions['code']);
        }

        $categoryParent = [];
        if (!empty($categoryArray['parentId'])) {
            $categoryParent = $this->getCategoryService()->getCategory($categoryArray['parentId']);
        }

        return [$conditions, $categoryArray, $categoryParent];
    }

    protected function mergeConditionsByCourses($conditions, $courses)
    {
        $courseSetIds = ArrayToolkit::column($courses, 'courseSetId');

        if (empty($conditions['ids'])) {
            $conditions['ids'] = $courseSetIds;

            return $conditions;
        }

        // 当其他查询条件筛选出了课程ID集合后，会员课程的课程ID集合应该和其求并集
        $setIds = array_intersect($courseSetIds, $conditions['ids']);
        $conditions['ids'] = empty($setIds) ? self::EMPTY_COURSE_SET_IDS : $setIds;

        return $conditions;
    }

    protected function findEnabledVipLevels()
    {
        if (!$this->isPluginInstalled('Vip')) {
            return [];
        }

        $levels = $this->getLevelService()->searchLevels(['enabled' => 1], ['seq' => 'ASC'], 0, 100);

        return ArrayToolkit::index($levels, 'id');
    }

    protected function getConditionsByVip($conditions, $currentLevelId, $supplierCode)
    {
        if (!$this->isPluginInstalled('Vip') || 'all' == $currentLevelId) {
            return $conditions;
        }

        $levels = $this->getLevelService()->findPrevEnabledLevels($currentLevelId);
        $vipLevelIds = ArrayToolkit::column($levels, 'id');
        $vipLevelIds = array_merge([$currentLevelId], $vipLevelIds);
        if (empty($vipLevelIds)) {
            return $conditions;
        }

        $vipRights = $this->getVipRightService()->findVipRightsBySupplierCodeAndVipLevelIds($supplierCode, $vipLevelIds);
        if ('course' == $supplierCode) {
            $courses = $this->getCourseService()->findPublicCoursesByIds(ArrayToolkit::column($vipRights, 'uniqueCode'));
            $conditions = !empty($courses) ? $this->mergeConditionsByCourses($conditions, $courses) : array_merge($conditions, ['ids' => [-1]]);
        } else {
            $classroomIds = $this->getClassroomService()->findClassroomsByIds(ArrayToolkit::column($vipRights, 'uniqueCode'));
            $conditions['classroomIds'] = empty($classroomIds) ? [-1] : ArrayToolkit::column($classroomIds, 'id');
        }

        return $conditions;
    }

    public function classroomAction(Request $request, $category)
    {
        $conditions = $request->query->all();
        $conditions['status'] = 'published';

        list($conditions, $tags) = $this->getConditionsByTags($conditions);
        $conditions = $this->getClassroomConditionsByTags($conditions);

        list($conditions, $filter) = $this->getFilter($conditions, 'classroom');

        $conditions = $this->getConditionsByVip($conditions, $filter['currentLevelId'], 'classroom');

        list($conditions, $orderBy) = $this->getClassroomSearchOrderBy($conditions);
        list($conditions, $categoryArray, $categoryParent) = $this->mergeConditionsByCategory($conditions, $category);

        $paginator = new Paginator(
            $this->get('request'),
            $this->getClassroomService()->countClassrooms($conditions),
            9
        );

        $classrooms = $this->getClassroomService()->searchClassrooms(
            $conditions,
            $orderBy,
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        return $this->render(
            'classroom/explore.html.twig',
            [
                'paginator' => $paginator,
                'classrooms' => $this->getWebExtension()->filterClassroomsVipRight($classrooms),
                'category' => $category,
                'categoryArray' => $categoryArray,
                'categoryParent' => $categoryParent,
                'filter' => $filter,
                'levels' => $this->findEnabledVipLevels(),
                'tags' => $tags,
                'isWxClient' => $this->isWxClient(),
                'isMobileClient' => $this->isMobileClient(),
            ]
        );
    }

    protected function getFilter($conditions, $type)
    {
        $default = ['price' => 'all', 'currentLevelId' => 'all'];
        if ('course' == $type) {
            $default['type'] = 'all';
        }

        $filter = !isset($conditions['filter']) ? $default : $conditions['filter'];

        if (isset($filter['price']) && 'free' === $filter['price']) {
            $conditions['price'] = '0.00';
        }

        if (isset($filter['type']) && 'all' != $filter['type']) {
            $conditions['type'] = strip_tags($filter['type']);
        }

        unset($conditions['filter']);

        return [$conditions, $filter];
    }

    private function _filterOpenCourseConditions($conditions)
    {
        $conditions['status'] = 'published';
        $conditions['parentId'] = 0;

        if (isset($conditions['ids']) && empty($conditions['ids'])) {
            $conditions['ids'] = [-1];
        }

        if (!empty($conditions['fliter']['type']) && 'all' != $conditions['fliter']['type']) {
            $conditions['type'] = $conditions['fliter']['type'];
        }

        return $conditions;
    }

    private function _getPageRecommendedCourses(Request $request, $conditions, $orderBy, $pageSize)
    {
        $conditions['recommended'] = 1;

        $recommendCount = $this->getOpenCourseService()->countCourses($conditions);
        $currentPage = $request->query->get('page') ? $request->query->get('page') : 1;
        $recommendPage = intval($recommendCount / $pageSize);
        $recommendLeft = $recommendCount % $pageSize;

        $currentPageCourses = $this->getOpenCourseService()->searchCourses(
            $conditions,
            ['recommendedSeq' => 'ASC'],
            ($currentPage - 1) * $pageSize,
            $pageSize
        );

        if (0 == count($currentPageCourses)) {
            $start = ($pageSize - $recommendLeft) + ($currentPage - $recommendPage - 2) * $pageSize;
            $limit = $pageSize;
        } elseif (count($currentPageCourses) > 0 && count($currentPageCourses) <= $pageSize) {
            $start = 0;
            $limit = $pageSize - count($currentPageCourses);
        }

        $conditions['recommended'] = 0;

        $courses = $this->getOpenCourseService()->searchCourses(
            $conditions,
            ['createdTime' => 'DESC'],
            $start, $limit
        );

        return array_merge($currentPageCourses, $courses);
    }

    protected function findCourseTeachers($courses)
    {
        if (!$courses) {
            return [];
        }

        $userIds = [];
        foreach ($courses as $key => $course) {
            $userIds = array_merge($userIds, $course['teacherIds']);
        }

        return $this->getUserService()->findUsersByIds($userIds);
    }

    protected function getConditionsByTags($conditions)
    {
        $selectedTag = '';
        $selectedTagGroupId = '';
        $tags = [];

        if (empty($conditions['tag'])) {
            return [$conditions, $tags];
        }

        if (!empty($conditions['tag']['tags'])) {
            $tags = $conditions['tag']['tags'];
        }

        if (!empty($conditions['tag']['selectedTag'])) {
            $selectedTag = $conditions['tag']['selectedTag']['tag'];
            $selectedTagGroupId = $conditions['tag']['selectedTag']['group'];
        }

        if (isset($tags[$selectedTagGroupId]) && $tags[$selectedTagGroupId] == $selectedTag) {
            unset($tags[$selectedTagGroupId]);
        } else {
            $tags[$selectedTagGroupId] = $selectedTag;
        }

        $tags = array_filter($tags);
        if (empty($tags)) {
            return [$conditions, $tags];
        }

        $conditions['tagIds'] = array_values($tags);

        return [$conditions, $tags];
    }

    protected function getCourseConditionsByTags($conditions, $ownerType = 'course-set')
    {
        if ('course-set' === $ownerType) {
            $conditions = $this->getCourseService()->appendReservationConditions($conditions);
        }

        if (empty($conditions['tagIds'])) {
            return $conditions;
        }

        $tagOwnerIds = $this->getTagService()->findOwnerIdsByTagIdsAndOwnerType($conditions['tagIds'], $ownerType);
        $conditions['ids'] = empty($tagOwnerIds) ? [] : $tagOwnerIds;
        unset($conditions['tagIds']);

        return $conditions;
    }

    protected function getClassroomConditionsByTags($conditions)
    {
        if (empty($conditions['tagIds'])) {
            return $conditions;
        }

        $tagOwnerIds = $this->getTagService()->findOwnerIdsByTagIdsAndOwnerType($conditions['tagIds'], 'classroom');

        $conditions['classroomIds'] = empty($tagOwnerIds) ? [0] : $tagOwnerIds;
        unset($conditions['tagIds']);

        return $conditions;
    }

    protected function getCourseSetSearchOrderBy($conditions)
    {
        $setting = $this->getSettingService()->get('course', []);

        $orderBy = empty($setting['explore_default_orderBy']) ? 'latest' : $setting['explore_default_orderBy'];

        $orderBy = empty($conditions['orderBy']) ? $orderBy : $conditions['orderBy'];
        unset($conditions['orderBy']);

        return [$conditions, $orderBy];
    }

    protected function getClassroomSearchOrderBy($conditions)
    {
        $setting = $this->getSettingService()->get('classroom');
        $orderBy = empty($setting['explore_default_orderBy']) ? 'createdTime' : $setting['explore_default_orderBy'];

        $orderBy = empty($conditions['orderBy']) ? $orderBy : $conditions['orderBy'];
        unset($conditions['orderBy']);

        return [$conditions, $orderBy];
    }

    protected function getCourseSetFilterType($conditions)
    {
        if (!$this->isPluginInstalled('Reservation')) {
            $conditions['excludeTypes'] = ['reservation'];
        }

        return $conditions;
    }

    protected function getTokenService()
    {
        return $this->createService('User:TokenService');
    }

    protected function getUserService()
    {
        return $this->createService('User:UserService');
    }

    /**
     * @return CategoryService
     */
    protected function getCategoryService()
    {
        return $this->createService('Taxonomy:CategoryService');
    }

    /**
     * @return TagService
     */
    protected function getTagService()
    {
        return $this->createService('Taxonomy:TagService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    protected function getThreadService()
    {
        return $this->createService('Course:ThreadService');
    }

    protected function getUploadFileService()
    {
        return $this->createService('File:UploadFileService');
    }

    protected function getAppService()
    {
        return $this->createService('CloudPlatform:AppService');
    }

    protected function getDiscountService()
    {
        return $this->createService('Discount:DiscountService');
    }

    /**
     * @return ClassroomService
     */
    protected function getClassroomService()
    {
        return $this->createService('Classroom:ClassroomService');
    }

    protected function getVipService()
    {
        return $this->createService('VipPlugin:Vip:VipService');
    }

    protected function getLevelService()
    {
        return $this->createService('VipPlugin:Vip:LevelService');
    }

    /**
     * @return CourseSetService
     */
    protected function getCourseSetService()
    {
        return $this->createService('Course:CourseSetService');
    }

    protected function getOrderService()
    {
        return $this->createService('Order:OrderService');
    }

    /**
     * @return ActivityService
     */
    protected function getActivityService()
    {
        return $this->createService('Activity:ActivityService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->createService('Task:TaskService');
    }

    /**
     * @return OpenCourseService
     */
    protected function getOpenCourseService()
    {
        return $this->createService('OpenCourse:OpenCourseService');
    }

    /**
     * @return VipRightService
     */
    protected function getVipRightService()
    {
        return $this->createService('VipPlugin:Marketing:VipRightService');
    }
}
