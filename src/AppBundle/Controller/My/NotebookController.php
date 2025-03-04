<?php

namespace AppBundle\Controller\My;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\Paginator;
use AppBundle\Controller\BaseController;
use Biz\Course\Service\CourseNoteService;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\MemberService;
use Biz\Task\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;

class NotebookController extends BaseController
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $conditions = [
            'userId' => $user['id'],
            'noteNumGreaterThan' => 0,
        ];

        $paginator = new Paginator(
            $request,
            $this->getCourseMemberService()->countMembers($conditions),
            10
        );

        $courseMembers = $this->getCourseMemberService()->searchMembers($conditions, $orderBy = [], $paginator->getOffsetCount(), $paginator->getPerPageCount());

        $courses = $this->getCourseService()->findCoursesByIds(ArrayToolkit::column($courseMembers, 'courseId'));
        $courses = ArrayToolkit::index($courses, 'id');

        return $this->render('my/learning/notebook/index.html.twig', [
            'courseMembers' => $courseMembers,
            'paginator' => $paginator,
            'courses' => $courses,
        ]);
    }

    public function showAction(Request $request, $courseId)
    {
        $user = $this->getCurrentUser();

        $course = $this->getCourseService()->getCourse($courseId);

        $notes = $this->getCourseNoteService()->findCourseNotesByUserIdAndCourseId($user['id'], $course['id']);
        $taskIds = ArrayToolkit::column($notes, 'taskId');

        $tasks = $this->getTaskService()->findTasksByIds($taskIds);
        $tasks = ArrayToolkit::index($tasks, 'id');

        $notes = $this->sortNotesByTaskSeq($notes, $tasks);
        $title = $course['courseSetTitle'].'-'.(empty($course['title']) ? '默认计划' : $course['title']);

        return $this->render('my/learning/notebook/show.html.twig', [
            'course' => $course,
            'tasks' => $tasks,
            'notes' => $notes,
            'title' => $title,
        ]);
    }

    public function deleteAction($id)
    {
        $this->getCourseNoteService()->deleteNote($id);

        return $this->createJsonResponse(true);
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
     * @return CourseNoteService
     */
    protected function getCourseNoteService()
    {
        return $this->createService('Course:CourseNoteService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->createService('Task:TaskService');
    }

    /**
     * @param $notes
     * @param $tasks
     *
     * @return array
     */
    protected function sortNotesByTaskSeq($notes, $tasks)
    {
        foreach ($notes as $index => $note) {
            $notes[$index]['seq'] = empty($tasks[$note['taskId']]) ? 0 : $tasks[$note['taskId']]['seq'];
        }

        usort($notes, function ($note1, $note2) {
            if (0 == $note1['seq'] || 0 == $note2['seq']) {
                return true;
            }

            return $note1['seq'] > $note2['seq'];
        });

        return $notes;
    }
}
