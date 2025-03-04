<?php

namespace Biz\Course\Event;

use AppBundle\Common\ArrayToolkit;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\CourseSetService;
use Biz\Course\Service\MemberService;
use Biz\System\Service\LogService;
use Biz\Task\Dao\TaskDao;
use Biz\Task\Service\TaskResultService;
use Biz\Task\Service\TaskService;
use Codeages\Biz\Framework\Event\Event;
use Codeages\PluginBundle\Event\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StatisticsSubscriber extends EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'course.task.create' => 'onTaskCreate',
            'course.task.update' => 'onTaskUpdate',
            'course.task.delete' => 'onTaskDelete',
            'course.task.create.sync' => 'onTaskCreateSync',
            'course.task.update.sync' => 'onTaskUpdateSync',

            'course.lesson.publish' => ['onPublishLessonNumberChange', -100],
            'course.lesson.unpublish' => ['onPublishLessonNumberChange', -100],
            'course.lesson.batch_publish' => ['onPublishLessonNumberChangeForBatch', -100],
            'course.lesson.batch_unpublish' => ['onPublishLessonNumberChangeForBatch', -100],
            'course.lesson.create' => ['onLessonNumberChange', -100],
            'course.lesson.delete' => ['onLessonNumberChange', -100],
            'course.lesson.setOptional' => ['onLessonOptionalChange', -100],

            'course.thread.create' => 'onCourseThreadChange',
            'course.thread.delete' => 'onCourseThreadChange',

            'course.review.add' => 'onReviewNumberChange',
            'course.review.update' => 'onReviewNumberChange',
            'course.review.delete' => 'onReviewNumberChange',

            'course.marketing.update' => 'onCourseMarketingChange',
            'course.publish' => 'onCourseStatusChange',
            'course.close' => 'onCourseStatusChange',
            'course.delete' => ['onCourseDelete', -200],
            'course.task.updateOptional' => 'onCourseTaskUpdateOptional',

            'review.create' => 'onReviewChange',
            'review.update' => 'onReviewChange',
            'review.delete' => 'onReviewChange',
        ];
    }

    public function onTaskCreateSync(Event $event)
    {
        $task = $event->getSubject();

        $copiedTasks = $this->getCopiedTasks($task);
        foreach ($copiedTasks as $copiedTask) {
            $this->getCourseService()->updateCourseStatistics($copiedTask['courseId'], ['lessonNum', 'taskNum', 'compulsoryTaskNum', 'electiveTaskNum']);
        }
    }

    public function onTaskUpdateSync(Event $event)
    {
        $task = $event->getSubject();

        $copiedTasks = $this->getCopiedTasks($task);
        foreach ($copiedTasks as $copiedTask) {
            $this->getCourseService()->updateCourseStatistics($copiedTask['courseId'], ['lessonNum', 'taskNum', 'compulsoryTaskNum', 'electiveTaskNum']);
        }
    }

    public function onCourseMarketingChange(Event $event)
    {
        $subject = $event->getSubject();
        $course = $subject['newCourse'];
        $this->getCourseSetService()->updateCourseSetMinAndMaxPublishedCoursePrice($course['courseSetId']);
        $this->updateCopiedCourseSetPrice($course['courseSetId']);
    }

    public function onCourseStatusChange(Event $event)
    {
        $course = $event->getSubject();
        $this->getCourseSetService()->updateCourseSetMinAndMaxPublishedCoursePrice($course['courseSetId']);
    }

    public function onTaskCreate(Event $event)
    {
        $this->onTaskNumberChange($event, ['taskNum', 'lessonNum', 'compulsoryTaskNum', 'electiveTaskNum']);
    }

    public function onTaskUpdate(Event $event)
    {
        $newTask = $event->getSubject();
        $oldTask = $event->getArguments();
        $isOptionalChange = isset($oldTask['isOptional']) && $newTask['isOptional'] != $oldTask['isOptional'];
        if ($isOptionalChange) {
            $this->onTaskNumberChange($event, ['taskNum', 'lessonNum', 'compulsoryTaskNum', 'electiveTaskNum']);
        }
    }

    public function onTaskDelete(Event $event)
    {
        $task = $event->getSubject();
        $this->getTaskResultService()->deleteTaskResultsByTaskId($task['id']);
        $this->onTaskNumberChange($event, ['taskNum', 'lessonNum', 'compulsoryTaskNum', 'electiveTaskNum']);
    }

    public function onCourseTaskUpdateOptional(Event $event)
    {
        $this->onTaskNumberChange($event, ['taskNum', 'lessonNum', 'compulsoryTaskNum', 'electiveTaskNum']);
    }

    public function onPublishLessonNumberChange(Event $event)
    {
        $lesson = $event->getSubject();
        $this->getCourseService()->updateCourseStatistics($lesson['courseId'], [
            'compulsoryTaskNum', 'publishLessonNum', 'electiveTaskNum',
        ]);
    }

    public function onPublishLessonNumberChangeForBatch(Event $event)
    {
        $this->getCourseService()->updateCourseStatistics($event->getArgument('courseId'), [
            'compulsoryTaskNum', 'publishLessonNum', 'electiveTaskNum',
        ]);
    }

    public function onLessonNumberChange(Event $event)
    {
        $lesson = $event->getSubject();
        $this->getCourseService()->updateCourseStatistics($lesson['courseId'], [
            'lessonNum', 'publishLessonNum',
        ]);
    }

    public function onCourseThreadChange(Event $event)
    {
        $thread = $event->getSubject();
        $this->getCourseService()->updateCourseStatistics($thread['courseId'], [
            $thread['type'].'Num',
        ]);
    }

    public function onReviewNumberChange(Event $event)
    {
        $review = $event->getSubject();

        $this->getCourseService()->updateCourseStatistics($review['courseId'], [
            'ratingNum',
        ]);
    }

    public function onCourseDelete(Event $event)
    {
        $course = $event->getSubject();

        $this->getCourseSetService()->updateCourseSetStatistics($course['courseSetId'], ['ratingNum', 'noteNum', 'studentNum', 'materialNum']);
    }

    public function onLessonOptionalChange(Event $event)
    {
        $lesson = $event->getSubject();

        $this->getCourseService()->updateCourseStatistics($lesson['courseId'], ['compulsoryTaskNum', 'electiveTaskNum']);
    }

    public function onReviewChange(Event $event)
    {
        $review = $event->getSubject();

        if ('course' == $review['targetType']) {
            $this->getCourseService()->updateCourseStatistics($review['targetId'], ['ratingNum']);
        }
    }

    protected function onTaskNumberChange(Event $event, $fields)
    {
        $task = $event->getSubject();
        $this->getCourseService()->updateCourseStatistics($task['courseId'], $fields);
    }

    protected function updateCopiedCourseSetPrice($courseSetId)
    {
        $copiedCourseSets = $this->getCourseSetService()->findCourseSetsByParentIdAndLocked($courseSetId, 1);
        $copiedCourseSetIds = ArrayToolkit::column($copiedCourseSets, 'id');

        foreach ($copiedCourseSetIds as $copiedCourseSetId) {
            $this->getCourseSetService()->updateCourseSetMinAndMaxPublishedCoursePrice($copiedCourseSetId);
        }
    }

    private function getCopiedTasks($task)
    {
        $courses = $this->getCourseService()->findCoursesByParentIdAndLocked($task['courseId'], 1);

        return $this->getTaskDao()->findByCopyIdAndLockedCourseIds($task['id'], ArrayToolkit::column($courses, 'id'));
    }

    /**
     * @return CourseSetService
     */
    protected function getCourseSetService()
    {
        return $this->getBiz()->service('Course:CourseSetService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->getBiz()->service('Course:CourseService');
    }

    /**
     * @return LogService
     */
    protected function getLogService()
    {
        return $this->getBiz()->service('System:LogService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->getBiz()->service('Task:TaskService');
    }

    /**
     * @return TaskDao
     */
    protected function getTaskDao()
    {
        return $this->getBiz()->dao('Task:TaskDao');
    }

    /**
     * @return TaskResultService
     */
    protected function getTaskResultService()
    {
        return $this->getBiz()->service('Task:TaskResultService');
    }

    /**
     * @return MemberService
     */
    protected function getMemberService()
    {
        return $this->getBiz()->service('Course:MemberService');
    }
}
