<?php

namespace AppBundle\Controller\Classroom;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\ClassroomToolkit;
use AppBundle\Common\ExtensionManager;
use AppBundle\Common\Paginator;
use AppBundle\Controller\BaseController;
use Biz\Certificate\Service\CertificateService;
use Biz\Certificate\Service\RecordService;
use Biz\Classroom\ClassroomException;
use Biz\Classroom\Service\ClassroomService;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\MemberService;
use Biz\Goods\Service\GoodsService;
use Biz\Order\OrderException;
use Biz\Order\Service\OrderService;
use Biz\Product\Service\ProductService;
use Biz\Sign\Service\SignService;
use Biz\System\Service\SettingService;
use Biz\Taxonomy\Service\CategoryService;
use Biz\Taxonomy\Service\TagService;
use Biz\Thread\Service\ThreadService;
use Biz\User\Service\AuthService;
use Biz\User\Service\StatusService;
use Biz\User\Service\TokenService;
use Biz\User\Service\UserFieldService;
use Biz\User\UserException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use VipPlugin\Biz\Marketing\Service\VipRightService;
use VipPlugin\Biz\Marketing\VipRightSupplier\ClassroomVipRightSupplier;
use VipPlugin\Biz\Vip\Service\LevelService;
use VipPlugin\Biz\Vip\Service\VipService;

class ClassroomController extends BaseController
{
    public function dashboardAction($nav, $classroom, $member)
    {
        $canManageClassroom = $this->getClassroomService()->canManageClassroom($classroom['id']);

        $threadSetting = $this->getSettingService()->get('ugc_thread', []);
        $typeExcludes = [];
        if (empty($threadSetting['enable_thread'])) {
            $typeExcludes = array_merge($typeExcludes, ['question', 'discussion']);
        }
        if (empty($threadSetting['enable_classroom_question'])) {
            $typeExcludes = array_merge($typeExcludes, ['question']);
        }
        if (empty($threadSetting['enable_classroom_thread'])) {
            $typeExcludes = array_merge($typeExcludes, ['discussion']);
        }
        $classroom['threadNum'] = $this->getThreadService()->searchThreadCount([
            'targetType' => 'classroom',
            'targetId' => $classroom['id'],
            'typeExcludes' => $typeExcludes,
            'excludeAuditStatus' => 'illegal',
        ]);

        return $this->render('classroom/dashboard-nav.html.twig', [
            'canManageClassroom' => $canManageClassroom,
            'classroom' => $classroom,
            'nav' => $nav,
            'member' => $member,
        ]);
    }

    public function keywordsAction($classroom)
    {
        $category = $this->getCategoryService()->getCategory($classroom['categoryId']);
        $parentCategory = [];

        if (!empty($category) && 0 != $category['parentId']) {
            $parentCategory = $this->getCategoryService()->getCategory($category['parentId']);
        }

        return $this->render('classroom/keywords.html.twig', [
            'category' => $category,
            'parentCategory' => $parentCategory,
            'classroom' => $classroom,
        ]);
    }

    public function headerAction($previewAs, $classroomId)
    {
        $classroom = $this->getClassroomService()->getClassroom($classroomId);

        $user = $this->getCurrentUser();

        $courses = $this->getClassroomService()->findActiveCoursesByClassroomId($classroomId);

        $coursesNum = count($courses);

        $checkMemberLevelResult = $classroomMemberLevel = null;

        if (empty($user['id'])) {
            $checkMemberLevelResult = 'not_login';
        }

        if ($this->isPluginInstalled('Vip') && $this->setting('vip.enabled')) {
            $vipRight = $this->getVipRightService()->getVipRightBySupplierCodeAndUniqueCode(ClassroomVipRightSupplier::CODE, $classroom['id']);
            $classroomMemberLevel = empty($vipRight) ? null : $this->getLevelService()->getLevel($vipRight['vipLevelId']);

            if ($user['id'] && $classroomMemberLevel) {
                $checkMemberLevelResult = $this->getVipService()->checkUserVipRight($user['id'], ClassroomVipRightSupplier::CODE, $classroom['id']);
            }
        }

        if ($previewAs) {
            if (!$this->getClassroomService()->canManageClassroom($classroomId)) {
                $previewAs = '';
            }
        }

        $lessonNum = 0;
        $coinPrice = 0;
        $price = 0;

        $cashRate = $this->getCashRate();

        foreach ($courses as $key => $course) {
            $lessonNum += $course['taskNum'];

            $coinPrice += $course['price'] * $cashRate;
            $price += $course['price'];
        }

        $canFreeJoin = $this->canFreeJoin($classroom, $courses, $user);
        $breadcrumbs = $this->getCategoryService()->findCategoryBreadcrumbs($classroom['categoryId']);

        $member = $user['id'] ? $this->getClassroomService()->getClassroomMember($classroom['id'], $user['id']) : null;
        $member = $this->previewAsMember($previewAs, $member, $classroom);

        if ($member) {
            $vipSetting = $this->getSettingService()->get('vip', []);
            $vipDeadline = false;
            if ($this->isPluginInstalled('Vip') && !empty($vipSetting['enabled']) && 'vip_join' == $member['joinedChannel'] && in_array('student', $member['role'])) {
                $vipMember = $this->getVipService()->getMemberByUserId($member['userId']);
                $vipRight = $this->getVipRightService()->getVipRightBySupplierCodeAndUniqueCode('classroom', $classroomId);
                if (!empty($vipMember) && !empty($vipRight)) {
                    $vipDeadline = true;
                    $member['deadline'] = ($vipMember['deadline'] < $member['deadline']) || empty($member['deadline']) ? $vipMember['deadline'] : $member['deadline'];
                }
            }

            return $this->render('classroom/classroom-join-header.html.twig', [
                'classroom' => $classroom,
                'courses' => $courses,
                'lessonNum' => $lessonNum,
                'coinPrice' => $coinPrice,
                'price' => $price,
                'member' => $member,
                'checkMemberLevelResult' => $checkMemberLevelResult,
                'classroomMemberLevel' => $classroomMemberLevel,
                'coursesNum' => $coursesNum,
                'canFreeJoin' => $canFreeJoin,
                'breadcrumbs' => $breadcrumbs,
                'vipDeadline' => $vipDeadline,
            ]);
        }

        return $this->render('classroom/classroom-header.html.twig', [
            'classroom' => $classroom,
            'courses' => $courses,
            'checkMemberLevelResult' => $checkMemberLevelResult,
            'classroomMemberLevel' => $classroomMemberLevel,
            'coursesNum' => $coursesNum,
            'member' => $member,
            'canFreeJoin' => $canFreeJoin,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /*
     * 如果用户已购买了此班级，或者用户是该班级的教师，则显示班级的Dashboard界面。
     * 如果用户未购买该班级，那么显示课程的营销界面。
     */
    public function showAction(Request $request, $id)
    {
        $classroom = $this->getClassroomService()->getClassroom($id);
        $previewAs = '';

        if (empty($classroom)) {
            $this->createNewException(ClassroomException::NOTFOUND_CLASSROOM());
        }

        $user = $this->getCurrentUser();

        $member = $user['id'] ? $this->getClassroomService()->getClassroomMember($classroom['id'], $user['id']) : null;

        if ($request->query->get('previewAs')) {
            if ($this->getClassroomService()->canManageClassroom($id)) {
                $previewAs = $request->query->get('previewAs');
            }
        }

        $member = $this->previewAsMember($previewAs, $member, $classroom);

        if ($member && '0' == $member['locked']) {
            return $this->redirect($this->generateUrl('classroom_courses', [
                'classroomId' => $id,
            ]));
        }

        $product = $this->getProductService()->getProductByTargetIdAndType($classroom['id'], 'classroom');
        $goods = $this->getGoodsService()->getGoodsByProductId($product['id']);

        return $this->redirect($this->generateUrl('goods_show', ['id' => $goods['id']]));
    }

    private function previewAsMember($previewAs, $member, $classroom)
    {
        $user = $this->getCurrentUser();

        if (in_array($previewAs, ['guest', 'auditor', 'member'])) {
            if ('guest' == $previewAs) {
                return;
            }

            $deadline = ClassroomToolkit::buildMemberDeadline([
                'expiryMode' => $classroom['expiryMode'],
                'expiryValue' => $classroom['expiryValue'],
            ]);

            $member = [
                'id' => 0,
                'classroomId' => $classroom['id'],
                'userId' => $user['id'],
                'orderId' => 0,
                'levelId' => 0,
                'noteNum' => 0,
                'threadNum' => 0,
                'remark' => '',
                'role' => ['auditor'],
                'locked' => 0,
                'createdTime' => 0,
                'deadline' => $deadline,
            ];

            if ('member' == $previewAs) {
                $member['role'] = ['member'];
            }
        }

        return $member;
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *                                                            商品介绍跳转到商品页
     */
    public function introductionAction($id)
    {
        $product = $this->getProductService()->getProductByTargetIdAndType($id, 'classroom');
        $goods = $this->getGoodsService()->getGoodsByProductId($product['id']);

        return $this->redirect($this->generateUrl('goods_show', ['id' => $goods['id']]));
    }

    public function teachersBlockAction($classroom)
    {
        $classroomTeacherIds = $this->getClassroomService()->findTeachers($classroom['id']);
        $users = $this->getUserService()->findUsersByIds($classroomTeacherIds);
        $headTeacher = $this->getUserService()->getUser($classroom['headTeacherId']);
        $headTeacherprofiles = $this->getUserService()->getUserProfile($classroom['headTeacherId']);
        $profiles = $this->getUserService()->findUserProfilesByIds($classroomTeacherIds);
        $currentUser = $this->getCurrentUser();

        $isFollowed = false;

        if ($headTeacher && $currentUser->isLogin()) {
            $isFollowed = $this->getUserService()->isFollowed($currentUser['id'], $headTeacher['id']);
        }

        if ($headTeacher && !(in_array($headTeacher, $users))) {
            $teachersCount = 1 + count($users);
        } else {
            $teachersCount = count($users);
        }

        return $this->render('classroom/teachers-block.html.twig', [
            'classroom' => $classroom,
            'users' => $users,
            'profiles' => $profiles,
            'headTeacher' => $headTeacher,
            'headTeacherprofiles' => $headTeacherprofiles,
            'teachersCount' => $teachersCount,
            'isFollowed' => $isFollowed,
        ]);
    }

    public function roleAction($previewAs, $classroomId)
    {
        $classroom = $this->getClassroomService()->getClassroom($classroomId);

        $user = $this->getCurrentUser();

        $courses = $this->getClassroomService()->findActiveCoursesByClassroomId($classroomId);

        $checkMemberLevelResult = $classroomMemberLevel = null;

        if ($this->setting('vip.enabled') && $user['id']) {
            $vipRight = $this->getVipRightService()->getVipRightBySupplierCodeAndUniqueCode(ClassroomVipRightSupplier::CODE, $classroom['id']);
            $classroomMemberLevel = !empty($vipRight) ? $this->getLevelService()->getLevel($vipRight['vipLevelId']) : null;

            if ($classroomMemberLevel) {
                $checkMemberLevelResult = $this->getVipService()->checkUserVipRight($user['id'], ClassroomVipRightSupplier::CODE, $classroom['id']);
            }
        }

        $member = $user['id'] ? $this->getClassroomService()->getClassroomMember($classroom['id'], $user['id']) : null;

        if ($previewAs) {
            if (!$this->getClassroomService()->canManageClassroom($classroomId)) {
                $previewAs = '';
            }
        }

        $member = $this->previewAsMember($previewAs, $member, $classroom);

        $coinPrice = 0;
        $price = 0;

        $cashRate = $this->getCashRate();

        foreach ($courses as $key => $course) {
            $coinPrice += $course['price'] * $cashRate;
            $price += $course['price'];
        }

        if ($member && '0' == $member['locked']) {
            return $this->render('classroom/role.html.twig', [
                'classroom' => $classroom,
                'courses' => $courses,
                'coinPrice' => $coinPrice,
                'price' => $price,
                'member' => $member,
                'checkMemberLevelResult' => $checkMemberLevelResult,
                'classroomMemberLevel' => $classroomMemberLevel,
            ]);
        }

        return new Response();
    }

    public function deadlineReachAction(Request $request, $classroomId)
    {
        $user = $this->getCurrentUser();

        if (!$user->isLogin()) {
            $this->createNewException(UserException::UN_LOGIN());
        }

        $this->getClassroomService()->removeStudent($classroomId, $user['id']);

        return $this->redirect($this->generateUrl('classroom_introductions', ['id' => $classroomId]));
    }

    public function latestMembersBlockAction($classroom, $count = 20)
    {
        $students = $this->getClassroomService()->findClassroomStudents($classroom['id'], 0, $count);
        $users = $this->getUserService()->findUsersByIds(ArrayToolkit::column($students, 'userId'));

        return $this->render('classroom/latest-members-block.html.twig', [
            'students' => $students,
            'users' => $users,
        ]);
    }

    public function classroomStatusBlockAction($classroom, $count = 10)
    {
        $learns = $this->getStatusService()->searchStatuses(
            ['onlyClassroomId' => $classroom['id']],
            ['createdTime' => 'DESC'],
            0,
            $count
        );

        if ($learns) {
            $ownerIds = ArrayToolkit::column($learns, 'userId');
            $owners = $this->getUserService()->findUsersByIds($ownerIds);

            $manager = ExtensionManager::instance();

            foreach ($learns as $key => $learn) {
                $learns[$key]['user'] = $owners[$learn['userId']];
                $learns[$key]['message'] = $manager->renderStatus($learn, 'simple');
                if ('closed' == $classroom['status']) {
                    $learns[$key]['message'] = str_replace('link-dark', 'link-dark js-handleLearnContentOnMessage', $learns[$key]['message']);
                }
                unset($learn);
            }
        }

        return $this->render('status/status-block.html.twig', [
            'learns' => $learns,
        ]);
    }

    public function signPageAction($classroomId)
    {
        $user = $this->getCurrentUser();

        $classroom = $this->getClassroomService()->getClassroom($classroomId);

        $isSignedToday = $this->getSignService()->isSignedToday($user['id'], 'classroom_sign', $classroom['id']);

        $week = ['日', '一', '二', '三', '四', '五', '六'];

        $userSignStatistics = $this->getSignService()->getSignUserStatistics($user['id'], 'classroom_sign', $classroom['id']);

        $day = date('d', time());

        $signDay = $this->getSignService()->findSignRecordsByPeriod($user['id'], 'classroom_sign', $classroom['id'], date('Y-m', time()), date('Y-m-d', time() + 3600));
        $notSign = $day - count($signDay);

        return $this->render('classroom/sign.html.twig', [
            'classroom' => $classroom,
            'isSignedToday' => $isSignedToday,
            'userSignStatistics' => $userSignStatistics,
            'notSign' => $notSign,
            'week' => $week[date('w', time())], ]);
    }

    public function signAction($classroomId)
    {
        $user = $this->getCurrentUser();
        $userSignStatistics = [];

        $this->checkClassroomStatus($classroomId);

        $member = $this->getClassroomService()->getClassroomMember($classroomId, $user['id']);

        if ($this->getClassroomService()->canTakeClassroom($classroomId) || (isset($member) && array_intersect(['auditor'], $member['role']))) {
            $this->getSignService()->userSign($user['id'], 'classroom_sign', $classroomId);

            $userSignStatistics = $this->getSignService()->getSignUserStatistics($user['id'], 'classroom_sign', $classroomId);
        }

        return $this->createJsonResponse($userSignStatistics);
    }

    public function getSignedRecordsByPeriodAction(Request $request, $classroomId)
    {
        $user = $this->getCurrentUser();
        $userId = $user['id'];

        $startDay = $request->query->get('startDay');
        $endDay = $request->query->get('endDay');

        $userSigns = $this->getSignService()->findSignRecordsByPeriod($userId, 'classroom_sign', $classroomId, $startDay, $endDay);
        $result = [];
        $result['records'] = [];

        if ($userSigns) {
            foreach ($userSigns as $userSign) {
                $result['records'][] = [
                    'day' => date('d', $userSign['createdTime']),
                    'time' => date('H-i', $userSign['createdTime']),
                    'rank' => $userSign['_rank'], ];
            }
        }

        $userSignStatistics = $this->getSignService()->getSignUserStatistics($userId, 'classroom_sign', $classroomId);
        $classSignStatistics = $this->getSignService()->getSignTargetStatistics('classroom_sign', $classroomId, date('Ymd', time()));

        $result['todayRank'] = $this->getSignService()->getTodayRank($userId, 'classroom_sign', $classroomId);
        $result['signedNum'] = $classSignStatistics['signedNum'];
        $result['keepDays'] = $userSignStatistics['keepDays'];

        return $this->createJsonResponse($result);
    }

    public function becomeStudentAction($id)
    {
        if (!$this->setting('vip.enabled')) {
            $this->createNewException(ClassroomException::FORBIDDEN_BECOME_STUDENT());
        }

        $user = $this->getCurrentUser();

        if (!$user->isLogin()) {
            $this->createNewException(UserException::UN_LOGIN());
        }

        if ($this->getClassroomService()->isClassroomOverDue($id)) {
            $this->createNewException(ClassroomException::EXPIRED_CLASSROOM());
        }

        $this->getClassroomService()->becomeStudent($id, $user['id'], ['becomeUseMember' => true]);

        return $this->redirect($this->generateUrl('classroom_show', ['id' => $id]));
    }

    public function exitAction(request $request, $id)
    {
        $user = $this->getCurrentUser();

        $member = $this->getClassroomService()->getClassroomMember($id, $user['id']);

        if (empty($member)) {
            $this->createNewException(ClassroomException::NOTFOUND_MEMBER());
        }

        if (!$this->getClassroomService()->canTakeClassroom($id, true)) {
            $this->createNewException(ClassroomException::FORBIDDEN_TAKE_CLASSROOM());
        }

        $reason = $request->request->get('reason');
        $this->getClassroomService()->removeStudent(
            $id,
            $user['id'],
            ['reason' => $reason['note'], 'reason_type' => 'exit']
        );

        return $this->redirect($this->generateUrl('classroom_show', ['id' => $id]));
    }

    public function exitForNoReasonAction(request $request, $id)
    {
        $user = $this->getCurrentUser();

        $member = $this->getClassroomService()->getClassroomMember($id, $user['id']);

        if (empty($member)) {
            $this->createNewException(ClassroomException::NOTFOUND_MEMBER());
        }

        if (!$this->getClassroomService()->canTakeClassroom($id, true)) {
            $this->createNewException(ClassroomException::FORBIDDEN_TAKE_CLASSROOM());
        }

        $this->getClassroomService()->removeStudent(
            $id,
            $user['id']);

        return $this->redirect($this->generateUrl('classroom_show', ['id' => $id]));
    }

    public function becomeAuditorAction($id)
    {
        $user = $this->getCurrentUser();

        if (!$user->isLogin()) {
            return $this->createMessageResponse('info', '你好像忘了登录哦？', null, 3000, $this->generateUrl('login'));
        }

        $classroom = $this->getClassroomService()->getClassroom($id);

        if (empty($classroom)) {
            $this->createNewException(ClassroomException::NOTFOUND_CLASSROOM());
        }

        if (!$classroom['buyable']) {
            return $this->createMessageResponse('info', "非常抱歉，该{$classroom['title']}不允许加入，如有需要请联系客服", '', 3, $this->generateUrl('homepage'));
        }

        if ($this->getClassroomService()->canTakeClassroom($id)) {
            $member = $this->getClassroomService()->getClassroomMember($id, $user['id']);

            if ($member) {
                goto response;
            }
        }

        if ($this->getClassroomService()->isClassroomAuditor($id, $user['id'])) {
            goto response;
        }

        $this->getClassroomService()->becomeAuditor($id, $user['id']);

        response:
        return $this->redirect($this->generateUrl('classroom_show', ['id' => $id]));
    }

    public function canviewAction($classroomId)
    {
        $user = $this->getCurrentUser();

        if (!$user->isLogin()) {
            $this->createNewException(UserException::UN_LOGIN());
        }

        $result = $this->getClassroomService()->canLookClassroom($classroomId);

        return $this->createJsonResponse($result);
    }

    public function classroomBlockAction($courseId)
    {
        $classroomIds = $this->getClassroomService()->findClassroomIdsByCourseId($courseId);

        $classroom = empty($classroomIds) || 0 == count($classroomIds) ? null : $this->getClassroomService()->getClassroom($classroomIds[0]);

        return $this->render('classroom/classroom-block.html.twig', [
            'classroom' => $classroom,
        ]);
    }

    public function qrcodeAction(Request $request, $id)
    {
        $user = $this->getCurrentUser();

        $token = $this->getTokenService()->makeToken('qrcode', [
            'userId' => $user['id'],
            'data' => [
                'url' => $this->generateUrl('classroom_show', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
            'times' => 1,
            'duration' => 3600,
        ]);
        $url = $this->generateUrl('common_parse_qrcode', ['token' => $token['token']], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = [
            'img' => $this->generateUrl('common_qrcode', ['text' => $url], UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        return $this->createJsonResponse($response);
    }

    private function canFreeJoin($classroom, $courses, $user)
    {
        $classroomSetting = $this->getSettingService()->get('classroom');

        if (empty($classroomSetting['discount_buy'])) {
            return false;
        }

        list($courses) = $this->getClassroomService()->findUserPaidCoursesInClassroom($user['id'], $classroom['id']);

        $priceType = 'RMB';

        $coinSetting = $this->getSettingService()->get('coin');
        $coinEnable = isset($coinSetting['coin_enabled']) && 1 == $coinSetting['coin_enabled'];

        if ($coinEnable && !empty($coinSetting) && array_key_exists('price_type', $coinSetting)) {
            $priceType = $coinSetting['price_type'];
        }

        $totalPrice = $classroom['price'];

        if ('Coin' == $priceType) {
            $totalPrice = $totalPrice * $coinSetting['cash_rate'];
        }

        //        $classroomSetting = $this->getSettingService()->get("classroom");

        if ($this->getCoursesTotalPrice($courses, $priceType) >= (float) $totalPrice) {
            return true;
        }

        return false;
    }

    private function getCoursesTotalPrice($courses, $priceType)
    {
        $coursesTotalPrice = 0;

        $cashRate = $this->getCashRate();

        foreach ($courses as $key => $course) {
            if ('RMB' == $priceType) {
                $coursesTotalPrice += $course['originPrice'];
            } elseif ('Coin' == $priceType) {
                $coursesTotalPrice += $course['originPrice'] * $cashRate;
            }
        }

        return $coursesTotalPrice;
    }

    private function checkClassroomStatus($classroomId)
    {
        $classroom = $this->getClassroomService()->getClassroom($classroomId);

        if (!$classroom) {
            $this->createNewException(ClassroomException::NOTFOUND_CLASSROOM());
        }

        if ('closed' == $classroom['status']) {
            $this->createNewException(ClassroomException::CLOSED_CLASSROOM());
        }

        if ('published' != $classroom['status']) {
            $this->createNewException(ClassroomException::UNPUBLISHED_CLASSROOM());
        }
    }

    public function classroomThreadsAction(Request $request, $type)
    {
        $user = $this->getCurrentUser();

        if (!$user->isTeacher()) {
            return $this->createMessageResponse('error', '您不是老师，不能查看此页面！');
        }

        $teacherClassrooms = $this->getClassroomService()->searchMembers(['role' => 'teacher', 'userId' => $user['id']], ['createdTime' => 'desc'], 0, PHP_INT_MAX);
        $headTeacherClassrooms = $this->getClassroomService()->searchMembers(['role' => 'headTeacher', 'userId' => $user['id']], ['createdTime' => 'desc'], 0, PHP_INT_MAX);

        $classrooms = array_merge($teacherClassrooms, $headTeacherClassrooms);

        $classroomIds = ArrayToolkit::column($classrooms, 'classroomId');

        $classrooms = $this->getClassroomService()->findClassroomsByIds($classroomIds);

        if (empty($classrooms)) {
            return $this->render('classroom/my-teaching-threads.html.twig', [
                'type' => $type,
                'threadType' => 'classroom',
                'threads' => [],
            ]);
        }

        $conditions = [
            'targetIds' => $classroomIds,
            'targetType' => 'classroom',
            'type' => $type,
        ];

        $paginator = new Paginator(
            $request,
            $this->getThreadService()->searchThreadCount($conditions),
            20
        );
        $threads = $this->getThreadService()->searchThreads(
            $conditions,
            'createdNotStick',
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $users = $this->getUserService()->findUsersByIds(ArrayToolkit::column($threads, 'lastPostUserId'));

        return $this->render('classroom/my-teaching-threads.html.twig', [
            'paginator' => $paginator,
            'threads' => $threads,
            'users' => $users,
            'classrooms' => $classrooms,
            'type' => $type,
            'threadType' => 'classroom',
        ]);
    }

    protected function getCashRate()
    {
        $coinSetting = $this->getSettingService()->get('coin');
        $coinEnable = isset($coinSetting['coin_enabled']) && 1 == $coinSetting['coin_enabled'];
        $cashRate = $coinEnable && isset($coinSetting['cash_rate']) ? $coinSetting['cash_rate'] : 1;

        return $cashRate;
    }

    public function orderInfoAction($sn)
    {
        $order = $this->getOrderService()->getOrderBySn($sn);

        if (empty($order)) {
            $this->createNewException(OrderException::NOTFOUND_ORDER());
        }

        $classroom = $this->getClassroomService()->getClassroom($order['targetId']);

        if (empty($classroom)) {
            $this->createNewException(ClassroomException::NOTFOUND_CLASSROOM());
        }

        return $this->render('classroom/classroom-order.html.twig', ['order' => $order, 'classroom' => $classroom]);
    }

    public function certificatesAction(Request $request, $classroomId)
    {
        $classroom = $this->getClassroomService()->getClassroom($classroomId);
        if (empty($classroom)) {
            $this->createNewException(ClassroomException::NOTFOUND_CLASSROOM());
        }

        $certificates = $this->getCertificateService()->search(
            ['targetType' => 'classroom', 'status' => 'published', 'targetId' => $classroom['id']],
            ['createdTime' => 'DESC'],
            0,
            PHP_INT_MAX
        );

        $user = $this->getCurrentUser();
        $obtainedCertificates = $this->getCertificateRecordService()->search(
            ['targetType' => 'classroom', 'statuses' => ['valid', 'expired'], 'userId' => $user['id']],
            [],
            0,
            PHP_INT_MAX
        );

        return $this->render('classroom/certificates/list.html.twig', [
            'certificates' => $certificates,
            'obtained' => ArrayToolkit::index($obtainedCertificates, 'certificateId'),
            'classroom' => $classroom,
            'member' => $user['id'] ? $this->getClassroomService()->getClassroomMember($classroom['id'], $user['id']) : null,
        ]);
    }

    public function memberAccessAction(Request $request, $classroomId, $memberId)
    {
        $user = $this->getCurrentUser();
        $memberAccessCode = $this->getVipService()->checkUserVipRight($user['id'], 'classroom', $classroomId);
        $vipRight = $this->getVipRightService()->getVipRightBySupplierCodeAndUniqueCode('classroom', $classroomId);
        $vipRightLevel = $this->getLevelService()->getLevel($vipRight['vipLevelId']);

        return $this->render('classroom/member-access-modal.html.twig',
            [
                'code' => $memberAccessCode,
                'userLevel' => $vipRightLevel,
                'vipRightLevel' => empty($vipRight) ? [] : $this->getLevelService()->getLevel($vipRight['vipLevelId']),
                'classroom' => $this->getClassroomService()->getClassroom($classroomId),
            ]);
    }

    /**
     * @return VipRightService
     */
    protected function getVipRightService()
    {
        return $this->createService('VipPlugin:Marketing:VipRightService');
    }

    /**
     * @return RecordService
     */
    protected function getCertificateRecordService()
    {
        return $this->createService('Certificate:RecordService');
    }

    /**
     * @return CertificateService
     */
    protected function getCertificateService()
    {
        return $this->createService('Certificate:CertificateService');
    }

    /**
     * @return ThreadService
     */
    protected function getThreadService()
    {
        return $this->createService('Thread:ThreadService');
    }

    /**
     * @return ClassroomService
     */
    private function getClassroomService()
    {
        return $this->createService('Classroom:ClassroomService');
    }

    /**
     * @return SignService
     */
    private function getSignService()
    {
        return $this->createService('Sign:SignService');
    }

    /**
     * @return CourseService
     */
    private function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return LevelService
     */
    protected function getLevelService()
    {
        return $this->createService('VipPlugin:Vip:LevelService');
    }

    /**
     * @return VipService
     */
    protected function getVipService()
    {
        return $this->createService('VipPlugin:Vip:VipService');
    }

    /**
     * @return StatusService
     */
    protected function getStatusService()
    {
        return $this->createService('User:StatusService');
    }

    /**
     * @return CategoryService
     */
    private function getCategoryService()
    {
        return $this->createService('Taxonomy:CategoryService');
    }

    /**
     * @return TokenService
     */
    protected function getTokenService()
    {
        return $this->createService('User:TokenService');
    }

    /**
     * @return OrderService
     */
    protected function getOrderService()
    {
        return $this->createService('Order:OrderService');
    }

    /**
     * @return UserFieldService
     */
    protected function getUserFieldService()
    {
        return $this->createService('User:UserFieldService');
    }

    /**
     * @return TagService
     */
    protected function getTagService()
    {
        return $this->createService('Taxonomy:TagService');
    }

    /**
     * @return AuthService
     */
    protected function getAuthService()
    {
        return $this->createService('User:AuthService');
    }

    /**
     * @return MemberService
     */
    protected function getCourseMemberService()
    {
        return $this->createService('Course:MemberService');
    }

    /**
     * @return ProductService
     */
    protected function getProductService()
    {
        return $this->createService('Product:ProductService');
    }

    /**
     * @return GoodsService
     */
    protected function getGoodsService()
    {
        return $this->createService('Goods:GoodsService');
    }
}
