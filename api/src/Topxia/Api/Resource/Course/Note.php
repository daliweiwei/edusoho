<?php

namespace Topxia\Api\Resource\Course;

use Biz\Course\Service\CourseNoteService;
use Biz\Course\Service\CourseService;
use Biz\Task\Service\TaskService;
use Biz\User\UserException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Topxia\Api\Resource\BaseResource;

class Note extends BaseResource
{
    public function get(Application $app, Request $request, $courseId, $noteId)
    {
        //$courseId暂时用不到
        $user = $this->getCurrentUser();
        $note = $this->getCourseNoteService()->getNote($noteId);
        if (empty($note)) {
            return $this->error('500', "ID为{$noteId}的笔记不存在");
        }

        if ($note['userId'] != $user['id'] && empty($note['status'])) {
            throw UserException::PERMISSION_DENIED();
        }

        return $this->filter($note);
    }

    public function post(Application $app, Request $request)
    {
        $requiredFields = ['taskId', 'content'];
        $fields = $this->checkRequiredFields($requiredFields, $request->request->all());

        $task = $this->getTaskService()->getTask($fields['taskId']);
        if (empty($task)) {
            return $this->error('403', "ID为{$fields['taskId']}的任务不存在");
        }

        $note = [
            'courseId' => $task['courseId'],
            'taskId' => $task['id'],
            'status' => !empty($fields['status']) ? 1 : 0,
            'content' => $fields['content'],
        ];
        $note = $this->getCourseNoteService()->saveNote($note);

        return $this->filter($note);
    }

    public function filter($res)
    {
        $res['createdTime'] = date('c', $res['createdTime']);
        $res['updatedTime'] = date('c', $res['updatedTime']);

        return $res;
    }

    /**
     * @return CourseNoteService
     */
    protected function getCourseNoteService()
    {
        return $this->getServiceKernel()->createService('Course:CourseNoteService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->getServiceKernel()->createService('Course:CourseService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->getServiceKernel()->createService('Task:TaskService');
    }
}
