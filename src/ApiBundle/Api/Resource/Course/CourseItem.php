<?php

namespace ApiBundle\Api\Resource\Course;

use ApiBundle\Api\Annotation\ApiConf;
use ApiBundle\Api\ApiRequest;
use ApiBundle\Api\Resource\AbstractResource;
use Biz\Activity\Service\HomeworkActivityService;
use Biz\Activity\Service\TestpaperActivityService;
use Biz\Course\CourseException;
use Codeages\Biz\ItemBank\Answer\Service\AnswerSceneService;

class CourseItem extends AbstractResource
{
    /**
     * @ApiConf(isRequiredAuth=false)
     */
    public function search(ApiRequest $request, $courseId)
    {
        $course = $this->getCourseService()->getCourse($courseId);

        if (!$course) {
            throw CourseException::NOTFOUND_COURSE();
        }

        $items = $this->convertToLeadingItems(
            $this->getCourseService()->findCourseItems($courseId),
            $course,
            $request->getHttpRequest()->isSecure(),
            $request->query->get('fetchSubtitlesUrls', 0),
            $request->query->get('onlyPublished', 0),
            $request->query->get('showOptionalNum', 1)
        );

        foreach ($items as &$item) {
            if (!empty($item['tasks'])) {
                foreach ($item['tasks'] as &$task) {
                    if ('homework' == $task['type'] && !empty($task['activity'])) {
                        $homeworkActivity = $this->getHomeworkActivityService()->get($task['activity']['mediaId']);
                        $task['activity']['mediaId'] = $homeworkActivity['assessmentId'];
                    }
                    if ('testpaper' == $task['type'] && !empty($task['activity']['ext'])) {
                        $testpaperActivity = $this->getTestpaperActivityService()->getActivity($task['activity']['mediaId']);
                        $scene = $this->getAnswerSceneService()->get($testpaperActivity['answerSceneId']);
                        if (!empty($scene)) {
                            $task['activity']['ext']['doTimes'] = $scene['do_times'];
                            $task['activity']['ext']['redoInterval'] = $scene['redo_interval'];
                            $task['activity']['ext']['limitedTime'] = $scene['limited_time'];
                        }
                    }
                }
            }
        }

        $request->query->has('format') ? $format = $request->query->get('format') : $format = 0;

        if ($format) {
            $filter = new CourseItemWithLessonFilter();
            $filter->filters($items);
            $items = $this->convertToTree($items);
        }

        return $items;
    }

    protected function convertToLeadingItems($originItems, $course, $isSsl, $fetchSubtitlesUrls, $onlyPublishTask = false, $showOptionalNum = 1)
    {
        return $this->container->get('api.util.item_helper')->convertToLeadingItemsV1($originItems, $course, $isSsl, $fetchSubtitlesUrls, $onlyPublishTask, $showOptionalNum);
    }

    protected function convertToTree($items)
    {
        return $this->container->get('api.util.item_helper')->convertToTree($items);
    }

    protected function getCourseService()
    {
        return $this->service('Course:CourseService');
    }

    /**
     * @return HomeworkActivityService
     */
    protected function getHomeworkActivityService()
    {
        return $this->service('Activity:HomeworkActivityService');
    }

    /**
     * @return TestpaperActivityService
     */
    protected function getTestpaperActivityService()
    {
        return $this->service('Activity:TestpaperActivityService');
    }

    /**
     * @return AnswerSceneService
     */
    protected function getAnswerSceneService()
    {
        return $this->service('ItemBank:Answer:AnswerSceneService');
    }
}
