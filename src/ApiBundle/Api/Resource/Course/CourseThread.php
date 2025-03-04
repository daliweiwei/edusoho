<?php

namespace ApiBundle\Api\Resource\Course;

use ApiBundle\Api\ApiRequest;
use ApiBundle\Api\Resource\AbstractResource;
use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\ContentToolkit;
use Biz\Common\CommonException;
use Biz\Course\CourseException;
use Biz\File\UploadFileException;

class CourseThread extends AbstractResource
{
    public function get(ApiRequest $request, $courseId, $threadId)
    {
        list($course, $memeber) = $this->getCourseService()->tryTakeCourse($courseId);
        if ('0' == $course['canLearn']) {
            throw CourseException::CLOSED_COURSE();
        }
        $thread = $this->getCourseThreadService()->getThreadByThreadId($threadId);
        $this->extractImgs($thread);
        $this->handleAttachments($thread);

        if (!empty($thread['videoId'])) {
            $file = $this->getUploadFileService()->getFile($thread['videoId']);
            $thread['askVideoLength'] = $file['length'];
        }
        $this->getOCUtil()->single($thread, ['userId']);
        $this->getOCUtil()->single($thread, ['courseId'], 'course');
        $this->getCourseThreadService()->hitThread($courseId, $threadId);

        return $thread;
    }

    public function search(ApiRequest $request, $courseId)
    {
        $this->getCourseService()->tryTakeCourse($courseId);
        list($offset, $limit) = $this->getOffsetAndLimit($request);

        $type = $request->query->get('type', 'question');
        $keyword = $request->query->get('keyword');
        $taskId = $request->query->get('taskId', 0);
        $conditions = [
            'courseId' => $courseId,
            'title' => $keyword,
            'type' => $type,
        ];

        $courseSetting = $this->getSettingService()->get('course', []);
        $conditions['types'] = [];
        if (!isset($courseSetting['show_question']) || '1' === $courseSetting['show_question']) {
            $conditions['types'][] = 'question';
        }
        if (!isset($courseSetting['show_discussion']) || '1' === $courseSetting['show_discussion']) {
            $conditions['types'][] = 'discussion';
        }
        if (empty($conditions['types'])) {
            return $this->makePagingObject([], 0, $offset, $limit);
        }

        if ($taskId) {
            $videoAskTime = $request->query->get('videoAskTime', 0);
            $task = $this->getTaskService()->getTask($taskId);
            $activity = $this->getActivityService()->getActivity($task['activityId'], true);
            if (!isset($activity['ext']['file'])) {
                throw UploadFileException::NOTFOUND_FILE();
            }
            $videoId = $activity['ext']['file']['id'];
            $conditions['videoId'] = isset($videoId) ? $videoId : 0;
            $conditions['videoAskTime_GE'] = ($videoAskTime - 60) > 0 ? $videoAskTime - 60 : 0;
            $conditions['videoAskTime_LE'] = $videoAskTime + 60;
        }

        if (!empty($keyword)) {
            $this->createSearchKeyword($keyword, $type);
        }

        $total = $this->getCourseThreadService()->countThreads($conditions);
        $threads = $this->getCourseThreadService()->searchThreads(
            $conditions,
            'created',
            $offset,
            $limit
        );
        foreach ($threads as &$thread) {
            $this->extractImgs($thread);
            $this->handleAttachments($thread);
        }
        $this->getOCUtil()->multiple($threads, ['userId']);

        return $this->makePagingObject(array_values($threads), $total, $offset, $limit);
    }

    public function add(ApiRequest $request, $courseId)
    {
        $this->getCourseService()->tryTakeCourse($courseId);
        $fields = $request->request->all();
        $fields['courseId'] = $courseId;
        $fields['source'] = 'app';
        if (!ArrayToolkit::requireds($fields, ['content', 'courseId', 'type'])) {
            throw CommonException::ERROR_PARAMETER_MISSING();
        }

        if (isset($fields['taskId'])) {
            $task = $this->getTaskService()->getTask($fields['taskId']);
            $activity = $this->getActivityService()->getActivity($task['activityId'], true);
            $fields['videoId'] = ('video' == $activity['mediaType']) ? $activity['ext']['file']['id'] : 0;
        }

        $fields['title'] = substr($fields['title'], 0, 100);
        if (empty($fields['title'])) {
            $fields['questionType'] = $this->getQuestionType($fields['fileIds']);
        }

        if (isset($fields['imgs'])) {
            $fields['content'] = ContentToolkit::appendImgs($fields['content'], $fields['imgs']);
        }

        $thread = $this->getCourseThreadService()->createThread($fields);

        if (isset($fields['fileIds'])) {
            $this->getUploadFileService()->createUseFiles($fields['fileIds'], $thread['id'], 'course.thread', 'attachment');
        }

        return $thread;
    }

    protected function getQuestionType($fileIds)
    {
        $files = $this->getUploadFileService()->findFilesByIds($fileIds, false);
        $types = ArrayToolkit::column($files, 'type');

        switch ($types) {
            case in_array('video', $types):
                return 'video';
            case in_array('image', $types):
                return 'image';
            case in_array('audio', $types):
                return 'audio';
            default:
                return 'content';
        }
    }

    protected function createSearchKeyword($keyword, $type)
    {
        $existKeyword = $this->getSearchKeywordService()->getSearchKeywordByNameAndType($keyword, $type);
        if ($existKeyword) {
            $this->getSearchKeywordService()->addSearchKeywordTimes($existKeyword['id']);
            $result = $this->getSearchKeywordService()->getSearchKeyword($existKeyword['id']);
        } else {
            $result = $this->getSearchKeywordService()->createSearchKeyword(['name' => $keyword, 'type' => $type]);
        }

        return $result;
    }

    protected function extractImgs(&$thread)
    {
        $thread['imgs'] = [];
        if (empty($thread['content'])) {
            return;
        }

        $thread['imgs'] = ContentToolkit::extractImgs($thread['content']);
        $thread['content'] = ContentToolkit::filterImgs($thread['content']);
    }

    protected function handleAttachments(&$thread)
    {
        $attachments = ArrayToolkit::group($this->getUploadFileService()->findUseFilesByTargetTypeAndTargetIdAndType('course.thread', $thread['id'], 'attachment'), 'targetId');
        if (isset($attachments[$thread['id']])) {
            $thread['attachments'] = [];
            foreach ($attachments[$thread['id']] as $attachment) {
                $file = isset($attachment['file']) ? $attachment['file'] : [];
                if (in_array($file['type'], ['video', 'audio'])) {
                    $thread['attachments'][$file['type']] = [
                        'id' => $file['id'],
                        'length' => $file['length'],
                    ];
                    if ('video' === $file['type']) {
                        $thread['attachments'][$file['type']]['thumbnail'] = isset($file['thumbnail']) ? $file['thumbnail'] : '';
                    }
                } else {
                    $thread['attachments']['pictures'][] = [
                        'id' => $file['id'],
                        'thumbnail' => isset($file['thumbnail']) ? $file['thumbnail'] : '',
                    ];
                }
            }
        }
    }

    /**
     * @return \Biz\Course\Service\Impl\MemberServiceImpl
     */
    protected function getMemberService()
    {
        return $this->service('Course:MemberService');
    }

    /**
     * @return \Biz\Task\Service\Impl\TaskServiceImpl
     */
    protected function getTaskService()
    {
        return $this->service('Task:TaskService');
    }

    /**
     * @return \Biz\Activity\Service\Impl\ActivityServiceImpl
     */
    protected function getActivityService()
    {
        return $this->service('Activity:ActivityService');
    }

    /**
     * @return \Biz\File\Service\Impl\UploadFileServiceImpl
     */
    protected function getUploadFileService()
    {
        return $this->service('File:UploadFileService');
    }

    /**
     * @return \Biz\SearchKeyword\Service\Impl\SearchKeywordServiceImpl
     */
    protected function getSearchKeywordService()
    {
        return $this->service('SearchKeyword:SearchKeywordService');
    }

    /**
     * @return \Biz\Course\Service\Impl\CourseServiceImpl
     */
    protected function getCourseService()
    {
        return $this->service('Course:CourseService');
    }

    /**
     * @return \Biz\Course\Service\Impl\MemberServiceImpl
     */
    protected function getCourseMemberService()
    {
        return $this->service('Course:MemberService');
    }

    /**
     * @return \Biz\Course\Service\Impl\ThreadServiceImpl
     */
    protected function getCourseThreadService()
    {
        return $this->service('Course:ThreadService');
    }

    /**
     * @return \Biz\User\Service\Impl\UserServiceImpl
     */
    protected function getUserService()
    {
        return $this->service('User:UserService');
    }
}
