<?php

namespace ApiBundle\Api\Resource\OpenCourse;

use ApiBundle\Api\Annotation\ApiConf;
use ApiBundle\Api\ApiRequest;
use ApiBundle\Api\Resource\AbstractResource;
use AppBundle\Common\DeviceToolkit;
use Biz\Live\Service\LiveService;
use Biz\OpenCourse\OpenCourseException;
use Biz\OpenCourse\Service\LiveCourseService;
use Biz\OpenCourse\Service\OpenCourseService;

class OpenCourseLiveTicket extends AbstractResource
{
    /**
     * @ApiConf(isRequiredAuth=false)
     */
    public function add(ApiRequest $request, $lessonId)
    {
        $lesson = $this->getOpenCourseService()->getLesson($lessonId);
        if (empty($lesson)) {
            throw OpenCourseException::NOTFOUND_LESSON();
        }

        if ('liveOpen' != $lesson['type']) {
            throw OpenCourseException::LESSON_TYPE_INVALID();
        }

        $course = $this->getOpenCourseService()->getCourse($lesson['courseId']);

        $params = [];

        $user = $this->getCurrentUser();
        if ($user->isLogin()) {
            $member = $this->getOpenCourseService()->getCourseMember($course['id'], $user['id']);
            if (empty($member)) {
                $this->getOpenCourseService()->createMember([
                    'courseId' => $lesson['courseId'],
                    'ip' => $request->getHttpRequest()->getClientIp(),
                    'lastEnterTime' => time(),
                ]);
            }
        }

        $params['role'] = $this->getLiveCourseService()->checkCourseUserRole($course, $lesson);
        $params['id'] = $user->isLogin() ? $user['id'] : (int) ($this->getMillisecond()) * 1000 + rand(0, 999);
        $params['displayName'] = $user->isLogin() ? $user['nickname'] : '游客'.$this->getRandomString(8);
        $params['nickname'] = $user->isLogin() ? $user['nickname'].'_'.$user['id'] : '游客'.$this->getRandomString(8);
        $params['isLogin'] = $user->isLogin();
        $params['startTime'] = $lesson['startTime'];
        $params['endTime'] = $lesson['endTime'];

        $params['device'] = $request->request->get('device', DeviceToolkit::isMobileClient() ? 'mobile' : 'desktop');

        return $this->getLiveService()->createLiveTicket($lesson['mediaId'], $params);
    }

    /**
     * @ApiConf(isRequiredAuth=false)
     */
    public function get(ApiRequest $request, $lessonId, $liveTicket)
    {
        $lesson = $this->getOpenCourseService()->getLesson($lessonId);
        if (empty($lesson)) {
            throw OpenCourseException::NOTFOUND_LESSON();
        }

        if ('liveOpen' != $lesson['type']) {
            throw OpenCourseException::LESSON_TYPE_INVALID();
        }

        $liveTicket = $this->getLiveService()->getLiveTicket($lesson['mediaId'], $liveTicket);
        if (empty($liveTicket)) {
            throw OpenCourseException::NOTFOUND_TICKET();
        }

        return $liveTicket;
    }

    protected function getRandomString($length, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $s = '';
        $cLength = strlen($chars);

        while (strlen($s) < $length) {
            $s .= $chars[mt_rand(0, $cLength - 1)];
        }

        return $s;
    }

    protected function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());

        return (float) sprintf('%.0f', ((float) ($t1) + (float) ($t2)) * 1000);
    }

    /**
     * @return LiveService
     */
    protected function getLiveService()
    {
        return $this->service('Live:LiveService');
    }

    /**
     * @return OpenCourseService
     */
    protected function getOpenCourseService()
    {
        return $this->service('OpenCourse:OpenCourseService');
    }

    /**
     * @return LiveCourseService
     */
    protected function getLiveCourseService()
    {
        return $this->getBiz()->service('OpenCourse:LiveCourseService');
    }
}
