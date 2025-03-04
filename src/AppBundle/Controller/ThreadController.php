<?php

namespace AppBundle\Controller;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\Paginator;
use Biz\Classroom\ClassroomException;
use Biz\Classroom\Service\ClassroomService;
use Biz\Course\CourseException;
use Biz\Course\Service\CourseService;
use Biz\File\Service\UploadFileService;
use Biz\ItemBankExercise\Service\ExerciseService;
use Biz\PostFilter\Service\TokenBucketService;
use Biz\System\Service\SettingService;
use Biz\Testpaper\ExerciseException;
use Biz\Thread\Service\ThreadService;
use Biz\Thread\ThreadException;
use Biz\User\Service\NotificationService;
use Symfony\Component\HttpFoundation\Request;

class ThreadController extends BaseController
{
    /**
     * [listAction description].
     *
     * @param Request $request [description]
     * @param [type]  $target  [description]
     * @param [type]  $filters [description]
     *
     * @return [type] [description]
     */
    public function listAction(Request $request, $target, $filters)
    {
        $conditions = $this->convertFiltersToConditions($target['id'], $filters);
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

        if (!empty($typeExcludes)) {
            $conditions['typeExcludes'] = $typeExcludes;
        }

        $conditions['excludeAuditStatus'] = 'illegal';

        $paginator = new Paginator(
            $request,
            $this->getThreadService()->searchThreadCount($conditions),
            20
        );

        $threads = $this->getThreadService()->searchThreads(
            $conditions,
            $filters['sort'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $userIds = array_merge(
            ArrayToolkit::column($threads, 'userId'),
            ArrayToolkit::column($threads, 'lastPostUserId')
        );
        $users = $this->getUserService()->findUsersByIds($userIds);

        return $this->render('thread/list.html.twig', [
            'target' => $target,
            'threads' => $threads,
            'users' => $users,
            'paginator' => $paginator,
            'filters' => $filters,
        ]);
    }

    public function showAction(Request $request, $target, $thread, $filter = [])
    {
        $conditions = [
            'threadId' => $thread['id'],
            'parentId' => 0,
            'excludeAuditStatus' => 'illegal',
        ];
        $paginator = new Paginator(
            $request,
            $this->getThreadService()->searchPostsCount(array_merge($conditions, $filter)),
            10
        );

        $posts = $this->getThreadService()->searchPosts(
            array_merge($conditions, $filter),
            ['createdTime' => 'ASC'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $conditions['ups_GT'] = 5;
        $goodPosts = $this->getThreadService()->searchPosts(
            $conditions,
            ['ups' => 'DESC'],
            0,
            3
        );

        $users = $this->getUserService()->findUsersByIds(ArrayToolkit::column(array_merge($goodPosts, $posts), 'userId'));

        $this->getThreadService()->hitThread($target['id'], $thread['id']);

        return $this->render('thread/show.html.twig', [
            'target' => $target,
            'thread' => $thread,
            'author' => $this->getUserService()->getUser($thread['userId']),
            'posts' => $posts,
            'goodPosts' => $goodPosts,
            'users' => $users,
            'paginator' => $paginator,
            'service' => $this->getThreadService(),
        ]);
    }

    public function subpostsAction(Request $request, $threadId, $postId, $less = false)
    {
        $post = $this->getThreadService()->getPost($postId);

        $conditions = ['parentId' => $postId, 'excludeAuditStatus' => 'illegal'];

        $paginator = new Paginator(
            $request,
            $this->getThreadService()->searchPostsCount($conditions),
            10
        );

        $paginator->setBaseUrl($this->generateUrl('thread_post_subposts', ['threadId' => $post['threadId'], 'postId' => $postId]));

        $posts = $this->getThreadService()->searchPosts(
            $conditions,
            ['createdTime' => 'ASC'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $users = $this->getUserService()->findUsersByIds(ArrayToolkit::column($posts, 'userId'));

        return $this->render('thread/subposts.html.twig', [
            'parentId' => $postId,
            'posts' => $posts,
            'users' => $users,
            'paginator' => $paginator,
            'less' => $less,
            'service' => $this->getThreadService(),
        ]);
    }

    public function createAction(Request $request, $target, $type = 'discussion', $thread = null)
    {
        if ('POST' === $request->getMethod()) {
            try {
                $data = $request->request->all();
                $data['targetType'] = $target['type'];
                $data['targetId'] = $target['id'];

                $thread = $this->getThreadService()->createThread($data);

                $attachment = $request->request->get('attachment');
                $this->getUploadFileService()->createUseFiles($attachment['fileIds'], $thread['id'], $attachment['targetType'], $attachment['type']);

                return $this->redirect($this->generateUrl("{$target['type']}_thread_show", [
                    "{$target['type']}Id" => $thread['targetId'],
                    'threadId' => $thread['id'],
                ]));
            } catch (\Exception $e) {
                return $this->createMessageResponse('error', $this->trans($e->getMessage()), '错误提示', 1, $request->getPathInfo());
            }
        }

        return $this->render('thread/create.html.twig', [
            'target' => $target,
            'thread' => $thread,
            'type' => $type,
        ]);
    }

    public function updateAction(Request $request, $target, $thread)
    {
        if ('POST' === $request->getMethod()) {
            try {
                $user = $this->getCurrentUser();
                $data = $request->request->all();

                if (isset($data['maxUsers']) && empty($data['maxUsers'])) {
                    $data['maxUsers'] = 0;
                }

                $thread = $this->getThreadService()->updateThread($thread['id'], $data);

                $attachment = $request->request->get('attachment');
                $this->getUploadFileService()->createUseFiles($attachment['fileIds'], $thread['id'], $attachment['targetType'], $attachment['type']);

                $message = [
                    'title' => $thread['title'],
                    'targetType' => $target['type'],
                    'targetId' => $target['id'],
                    'type' => 'type-modify',
                    'userId' => $user['id'],
                    'userName' => $user['nickname'], ];

                if ($thread['userId'] != $user['id']) {
                    $this->getNotifiactionService()->notify($thread['userId'], 'group-thread', $message);
                }

                return $this->redirect($this->generateUrl("{$target['type']}_thread_show", [
                    "{$target['type']}Id" => $target['id'],
                    'threadId' => $thread['id'],
                ]));
            } catch (\Exception $e) {
                return $this->createMessageResponse('error', $this->trans($e->getMessage()), '错误提示', 1, $request->getPathInfo());
            }
        }

        return $this->render('thread/create.html.twig', [
            'target' => $target,
            'thread' => $thread,
        ]);
    }

    public function deleteAction(Request $request, $threadId)
    {
        $thread = $this->getThreadService()->getThread($threadId);
        $this->getThreadService()->deleteThread($threadId);

        $user = $this->getCurrentUser();

        if ($thread['userId'] != $user['id']) {
            $message = [
                'title' => $thread['title'],
                'type' => 'delete',
                'userId' => $user['id'],
                'userName' => $user['nickname'], ];

            $this->getNotifiactionService()->notify($thread['userId'], 'group-thread', $message);
        }

        return $this->createJsonResponse(true);
    }

    public function setStickyAction(Request $request, $threadId)
    {
        $this->getThreadService()->setThreadSticky($threadId);

        return $this->createJsonResponse(true);
    }

    public function cancelStickyAction(Request $request, $threadId)
    {
        $this->getThreadService()->cancelThreadSticky($threadId);

        return $this->createJsonResponse(true);
    }

    public function setNiceAction(Request $request, $threadId)
    {
        $this->getThreadService()->setThreadNice($threadId);

        return $this->createJsonResponse(true);
    }

    public function cancelNiceAction(Request $request, $threadId)
    {
        $this->getThreadService()->cancelThreadNice($threadId);

        return $this->createJsonResponse(true);
    }

    public function postAction(Request $request, $threadId, $target = [])
    {
        $user = $this->getCurrentUser();
        $thread = $this->getThreadService()->getThread($threadId);

        if ('POST' === $request->getMethod()) {
            $fields = $request->request->all();
            if (!empty($fields['type'] && !empty($fields['id']))) {
                $target = ['type' => $fields['type'], 'id' => $fields['id']];
                unset($fields['type']);
                unset($fields['id']);
            }
            if (!empty($target['type'] && !empty($target['id']))) {
                $this->checkStatus($target);
            }
            $fields['threadId'] = $threadId;
            unset($fields['attachment']);
            $post = $this->getThreadService()->createPost($fields);

            $attachment = $request->request->get('attachment');
            $this->getUploadFileService()->createUseFiles($attachment['fileIds'], $post['id'], $attachment['targetType'], $attachment['type']);

            return $this->render('thread/part/post-item.html.twig', [
                'target' => $target,
                'post' => $post,
                'author' => $user,
                'service' => $this->getThreadService(),
            ]);
        }

        return $this->render('thread/post.html.twig', [
            'target' => $target,
            'thread' => $thread,
            'service' => $this->getThreadService(),
        ]);
    }

    public function postSaveAction(Request $request, $targetType, $targetId)
    {
        $user = $this->getCurrentUser();

        if (!$user->isLogin()) {
            $this->createAccessDeniedException('用户没有登录,不能评论!');
        }

        if ('POST' === $request->getMethod()) {
            $fields = $request->request->all();
            if (!$this->checkDragCaptchaToken($request, $fields['_dragCaptchaToken'])) {
                return $this->createJsonResponse(['error' => ['code' => 403, 'message' => $this->trans('exception.form..drag.expire')]], 403);
            }
            unset($fields['_dragCaptchaToken']);

            $post['content'] = $this->autoParagraph($fields['content']);
            $post['targetType'] = $targetType;
            $post['targetId'] = $targetId;

            $post = $this->getThreadService()->createPost($post);

            if ('openCourse' === $targetType) {
                $postReplyUrl = $this->container->get('router')->generate('open_course_post_reply', ['id' => $targetId, 'postId' => $post['id'], 'targetType' => 'openCourse']);
            } else {
                $postReplyUrl = $this->container->get('router')->generate('thread_post_reply', ['threadId' => $post['threadId'], 'postId' => $post['id']]);
            }

            return $this->render('thread/part/post-item.html.twig', [
                'post' => $post,
                'author' => $user,
                'service' => $this->getThreadService(),
                'postReplyUrl' => $postReplyUrl,
            ]);
        }
    }

    protected function checkStatus($target)
    {
        if (!empty($target['type']) && 'course' == $target['type']) {
            $course = $this->getCourseService()->getCourse($target['id']);
            if ('0' == $course['canLearn']) {
                throw CourseException::CLOSED_COURSE();
            }
        }
        if (!empty($target['type']) && 'exercise' == $target['type']) {
            $exercise = $this->getExerciseService()->get($target['id']);
            if ('closed' == $exercise['status']) {
                throw ExerciseException::CLOSED_EXERCISE();
            }
        }

        if (!empty($target['type']) && 'classroom' == $target['type']) {
            $classroom = $this->getClassroomService()->getClassroom($target['id']);
            if ('closed' == $classroom['status']) {
                throw ClassroomException::CLOSED_CLASSROOM();
            }
        }
    }

    public function postReplyAction(Request $request, $threadId, $postId, $targetType = 'classroom', $targetId = '')
    {
        $fields = $request->request->all();
        $target = [];
        if (!empty($targetId)) {
            $target = ['type' => 'classroom', 'id' => $targetId];
        }

        if (!empty($fields['type'] && !empty($fields['id']))) {
            $target = ['type' => $fields['type'], 'id' => $fields['id']];
            unset($fields['type']);
            unset($fields['id']);
        }
        if (!empty($target)) {
            $this->checkStatus($target);
        }
        $fields['content'] = $this->autoParagraph($fields['content']);
        $fields['threadId'] = $threadId;
        $fields['parentId'] = $postId;
        $fields['targetType'] = $targetType;

        $post = $this->getThreadService()->createPost($fields);

        return $this->render('thread/subpost-item.html.twig', [
            'post' => $post,
            'author' => $this->getCurrentUser(),
            'service' => $this->getThreadService(),
        ]);
    }

    public function postDeleteAction(Request $request, $threadId, $postId)
    {
        $this->getThreadService()->deletePost($postId);

        return $this->createJsonResponse(true);
    }

    public function postUpAction(Request $request, $threadId, $postId)
    {
        $result = $this->getThreadService()->voteUpPost($postId);

        return $this->createJsonResponse($result);
    }

    public function jumpAction(Request $request, $threadId)
    {
        $thread = $this->getThreadService()->getThread($threadId);

        if (empty($thread)) {
            $this->createNewException(ThreadException::NOTFOUND_THREAD());
        }

        return $this->redirect($this->generateUrl("{$thread['targetType']}_thread_show", [
            "{$thread['targetType']}Id" => $thread['targetId'],
            'threadId' => $thread['id'],
        ]));
    }

    public function postJumpAction(Request $request, $threadId, $postId)
    {
        $thread = $this->getThreadService()->getThread($threadId);

        if (empty($thread)) {
            $this->createNewException(ThreadException::NOTFOUND_THREAD());
        }

        $post = $this->getThreadService()->getPost($postId);

        if ($post && $post['parentId']) {
            $post = $this->getThreadService()->getPost($post['parentId']);
        }

        if (empty($post)) {
            return $this->redirect($this->generateUrl("{$thread['targetType']}_thread_show", [
                "{$thread['targetType']}Id" => $thread['targetId'],
                'threadId' => $thread['id'],
            ]));
        }

        $conditions = [
            'threadId' => $post['threadId'],
            'parentId' => 0,
            'lessThanId' => $post['id'],
        ];
        $count = $this->getThreadService()->searchPostsCount($conditions);

        $page = ceil($count / 20);

        return $this->redirect($this->generateUrl("{$thread['targetType']}_thread_show", [
            "{$thread['targetType']}Id" => $thread['targetId'],
            'threadId' => $thread['id'],
            'page' => $page,
        ])."#post-{$post['id']}");
    }

    public function userOtherThreadsBlockAction(Request $request, $thread, $userId)
    {
        $conditions = [
            'targetType' => $thread['targetType'],
            'targetId' => $thread['targetId'],
            'userId' => $userId,
        ];
        $threads = $this->getThreadService()->searchThreads($conditions, ['createdTime' => 'DESC'], 0, 11);

        return $this->render('thread/user-threads-block.html.twig', [
            'currentThread' => $thread,
            'threads' => $threads,
        ]);
    }

    public function zeroPostThreadsBlockAction(Request $request, $thread)
    {
        $conditions = [
            'targetType' => $thread['targetType'],
            'targetId' => $thread['targetId'],
            'postNum' => 0,
        ];
        $threads = $this->getThreadService()->searchThreads(
            $conditions,
            ['createdTime' => 'desc'],
            0,
            11
        );

        return $this->render('thread/zero-post-threads-block.html.twig', [
            'currentThread' => $thread,
            'threads' => $threads,
        ]);
    }

    protected function convertFiltersToConditions($id, $filters)
    {
        $conditions = ['targetId' => $id];

        switch ($filters['type']) {
            case 'question':
                $conditions['type'] = 'question';
                break;
            case 'nice':
                $conditions['nice'] = 1;
                break;
            default:
                break;
        }

        return $conditions;
    }

    /**
     * This function is from Cakephp TextHelper Class.
     */
    protected function autoParagraph($text)
    {
        if ('' !== trim($text)) {
            $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
            $text = preg_replace("/\n\n+/", "\n\n", str_replace(["\r\n", "\r"], "\n", $text));
            $texts = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);
            $text = '';

            foreach ($texts as $txt) {
                $text .= '<p>'.nl2br(trim($txt, "\n"))."</p>\n";
            }

            $text = preg_replace('|<p>\s*</p>|', '', $text);
        }

        return $text;
    }

    /**
     * @return ThreadService
     */
    protected function getThreadService()
    {
        return $this->getBiz()->service('Thread:ThreadService');
    }

    /**
     * @return TokenBucketService
     */
    protected function getTokenBucketService()
    {
        return $this->getBiz()->service('PostFilter:TokenBucketService');
    }

    /**
     * @return NotificationService
     */
    protected function getNotifiactionService()
    {
        return $this->getBiz()->service('User:NotificationService');
    }

    /**
     * @return UploadFileService
     */
    protected function getUploadFileService()
    {
        return $this->getBiz()->service('File:UploadFileService');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->getBiz()->service('System:SettingService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->getBiz()->service('Course:CourseService');
    }

    /**
     * @return ExerciseService
     */
    protected function getExerciseService()
    {
        return $this->getBiz()->service('ItemBankExercise:ExerciseService');
    }

    /**
     * @return ClassroomService
     */
    protected function getClassroomService()
    {
        return $this->getBiz()->service('Classroom:ClassroomService');
    }
}
