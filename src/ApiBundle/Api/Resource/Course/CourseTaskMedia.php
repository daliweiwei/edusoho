<?php

namespace ApiBundle\Api\Resource\Course;

use ApiBundle\Api\Annotation\Access;
use ApiBundle\Api\Annotation\ApiConf;
use ApiBundle\Api\ApiRequest;
use ApiBundle\Api\Resource\AbstractResource;
use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\FileToolkit;
use Biz\Activity\ActivityException;
use Biz\Activity\Service\ActivityService;
use Biz\Activity\Service\ExerciseActivityService;
use Biz\Classroom\ClassroomException;
use Biz\Common\CommonException;
use Biz\Course\MemberException;
use Biz\Course\Service\CourseService;
use Biz\Course\Service\LiveReplayService;
use Biz\Course\Service\MaterialService;
use Biz\Course\Service\MemberService;
use Biz\File\Service\UploadFileService;
use Biz\File\UploadFileException;
use Biz\Player\PlayerException;
use Biz\Player\Service\PlayerService;
use Biz\Task\Service\TaskService;
use Biz\Testpaper\ExerciseException;
use Biz\Testpaper\Wrapper\TestpaperWrapper;
use Biz\User\UserException;
use Codeages\Biz\ItemBank\Answer\Constant\AnswerRecordStatus;
use Codeages\Biz\ItemBank\Answer\Service\AnswerQuestionReportService;
use Codeages\Biz\ItemBank\Answer\Service\AnswerRecordService;
use Codeages\Biz\ItemBank\Answer\Service\AnswerReportService;
use Codeages\Biz\ItemBank\Answer\Service\AnswerSceneService;
use Codeages\Biz\ItemBank\Answer\Service\AnswerService;
use Codeages\Biz\ItemBank\Assessment\Service\AssessmentService;
use Codeages\Biz\ItemBank\ErrorCode;
use Codeages\Biz\ItemBank\Item\Exception\ItemException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CourseTaskMedia extends AbstractResource
{
    /**
     * @param $courseId
     * @param $taskId
     *
     * @return array
     * @Access(roles="")
     * @ApiConf(isRequiredAuth=false)
     */
    public function get(ApiRequest $request, $courseId, $taskId)
    {
        $ssl = $request->getHttpRequest()->isSecure() ? true : false;
        $preview = $request->query->get('preview');
        if ($preview) {
            $task = $this->getTaskService()->getTask($taskId);
            $course = $this->getCourseService()->getCourse($task['courseId']);
            $this->checkPreview($course, $task);
        } else {
            $task = $this->getTaskService()->tryTakeTask($taskId);
            list($course, $member) = $this->getCourseService()->tryTakeCourse($task['courseId']);
        }

        $activity = $this->getActivityService()->getActivity($task['activityId'], true);
        $method = 'get'.$activity['mediaType'];
        if (!method_exists($this, $method)) {
            throw CommonException::NOTFOUND_METHOD();
        }
        $media = $this->$method($course, $task, $activity, $request->getHttpRequest(), $ssl);

        list($watermarkSetting, $fingerPrintSetting) = $this->fingerPrintWatermark();

        return [
            'mediaType' => $activity['mediaType'],
            'media' => $media,
            'format' => $request->query->get('format', 'common'),
            'watermarkSetting' => $watermarkSetting,
            'fingerPrintSetting' => $fingerPrintSetting,
        ];
    }

    protected function fingerPrintWatermark()
    {
        $storageSetting = $this->getSettingService()->get('storage');
        $fingerPrintSetting = [
            'video_fingerprint' => '',
        ];
        $watermarkSetting = [
            'video_watermark' => '0',
        ];

        if (!empty($storageSetting)) {
            $fingerPrintSetting = ArrayToolkit::parts($storageSetting, [
                'video_fingerprint',
                'video_fingerprint_time',
            ]);
            $fingerPrintSetting['video_fingerprint'] = empty($fingerPrintSetting['video_fingerprint']) ? '' : $this->getWebExtension()->getFingerprint();

            $watermarkSetting = ArrayToolkit::parts($storageSetting, [
                'video_watermark',
                'video_watermark_image',
                'video_embed_watermark_image',
                'video_watermark_position',
            ]);
        }

        return [$watermarkSetting, $fingerPrintSetting];
    }

    protected function checkPreview($course, $task)
    {
        $user = $this->getCurrentUser();
        $taskCanTryLook = false;
        if ($course['tryLookable'] && 'video' == $task['type']) {
            $activity = $this->getActivityService()->getActivity($task['activityId'], true);
            if (!empty($activity['ext']) && !empty($activity['ext']['file']) && 'cloud' === $activity['ext']['file']['storage']) {
                $taskCanTryLook = true;
            }
        }

        if (empty($task['isFree']) && !$taskCanTryLook) {
            if (!$user->isLogin()) {
                throw UserException::UN_LOGIN();
            }

            // 班级课程必须加入才能预览资源
            if ($course['parentId'] > 0) {
                $classroom = $this->getClassroomService()->getClassroomByCourseId($course['id']);
                $classroomMember = $this->getClassroomService()->getClassroomMember($classroom['id'], $user['id']);
                if (empty($classroomMember)) {
                    throw ClassroomException::UN_JOIN();
                }
            }

            if (!$this->getCourseMemberService()->isCourseMember($course['id'], $user['id'])) {
                throw MemberException::FORBIDDEN_NOT_MEMBER();
            }
        }

        //在可预览情况下查看网站设置是否可匿名预览
        $allowAnonymousPreview = $this->getSettingService()->node('course.allowAnonymousPreview', 1);

        if (empty($allowAnonymousPreview) && !$user->isLogin()) {
            throw UserException::UN_LOGIN();
        }
    }

    protected function getDownload($course, $task, $activity, $request, $ssl = false)
    {
        $medias = [];
        $materials = $this->getMaterialService()->findMaterialsByLessonIdAndSource($activity['id'], 'coursematerial');

        if (empty($materials)) {
            return $medias;
        }

        foreach ($materials as $material) {
            if (0 == $material['fileId']) {
                $media = [
                    'type' => 'link',
                    'fileName' => '',
                    'ext' => 'link',
                    'url' => $material['link'],
                ];
            } else {
                $file = $this->getUploadFileService()->getFile($material['fileId']);
                $media = [
                    // TODO 待IOS开发完成，就抽象为 link 和 file，file不再区local和cloud
                    'type' => $file['storage'],
                    'fileName' => $material['title'],
                    // TODO 待IOS开发完成，如果不需要就去掉，彻底不要 $file
                    'ext' => $file['ext'],
                    'url' => '',
                ];
            }

            $media['courseId'] = $course['id'];
            $media['taskId'] = $task['id'];
            $media['materialId'] = $material['id'];
            $media['fileId'] = $material['fileId'];
            $media['fileType'] = FileToolkit::getDownloadTaskTypeByExtension(($media['ext']));
            $media['title'] = $material['title'];
            $media['description'] = $material['description'];
            $media['fileSize'] = $material['fileSize'];
            $medias[] = $media;
        }

        return $medias;
    }

    protected function getTestpaper($course, $task, $activity, $request, $ssl = false)
    {
        return $activity['ext'];
    }

    protected function getVideo($course, $task, $activity, $request, $ssl = false)
    {
        $config = $this->getActivityService()->getActivityConfig($activity['mediaType']);
        $video = $config->get($activity['mediaId']);
        $watchStatus = $config->getWatchStatus($activity);
        if ('error' === $watchStatus['status']) {
            throw ActivityException::WATCH_LIMIT();
        }

        $video = $config->prepareMediaUri($video);
        if ('self' != $video['mediaSource']) {
            return $video;
        }

        $file = $this->getUploadFileService()->getFullFile($video['mediaId']);
        if (empty($file)) {
            throw UploadFileException::NOTFOUND_FILE();
        }
        if (!in_array($file['type'], ['audio', 'video'])) {
            throw PlayerException::NOT_SUPPORT_TYPE();
        }

        $version = $request->query->get('version', 'qiqiuyun');
        if ('escloud' == $version) {
            $options['playAudio'] = $request->query->get('playAudio', 0);
            $options['watchLimitTime'] = $this->getVideoFreeWatchTime($course, $task);

            return $this->getVideoWithEsCloud($file, $course, $task, $options, $request);
        }

        $player = $this->getPlayerService()->getAudioAndVideoPlayerType($file);

        $agentInWhiteList = $this->getResourceFacadeService()->agentInWhiteList($request->headers->get('user-agent'));

        $isEncryptionPlus = false;
        $context = [];
        if ('video' == $file['type'] && 'cloud' == $file['storage']) {
            $videoPlayer = $this->getPlayerService()->getVideoFilePlayer($file, $agentInWhiteList, [], $ssl);
            $isEncryptionPlus = $videoPlayer['isEncryptionPlus'];
            $context = $videoPlayer['context'];
            if (!empty($videoPlayer['mp4Url'])) {
                $mp4Url = $videoPlayer['mp4Url'];
            }
        }

        $url = isset($mp4Url) ? $mp4Url : $this->getPlayUrl($file, $context, $ssl);

        $supportMobile = (int) $this->getSettingService()->node('storage.support_mobile', 0);
        $securityVideoPlayer = (int) $this->getSettingService()->node('magic.security_video_player', 0);

        return [
            'resId' => $file['globalId'],
            'url' => isset($url) ? $url : null,
            'player' => $player,
            'videoHeaderLength' => isset($context['videoHeaderLength']) ? $context['videoHeaderLength'] : 0,
            'timeLimit' => $this->getVideoFreeWatchTime($course, $task),
            'agentInWhiteList' => $agentInWhiteList,
            'isEncryptionPlus' => $isEncryptionPlus,
            'supportMobile' => $supportMobile,
            'securityVideoPlayer' => $securityVideoPlayer,
        ];
    }

    protected function getVideoFreeWatchTime($course, $task)
    {
        $user = $this->getCurrentUser();
        $isCourseMember = $this->getCourseMemberService()->isCourseMember($course['id'], $user['id']);
        if (empty($task['isFree']) && !empty($course['tryLookable']) && !$isCourseMember) {
            return $course['tryLookLength'] * 60;
        }

        return 0;
    }

    protected function getVideoWithEsCloud($file, $course, $task, $options = [], $request)
    {
        $playerContext = $this->getResourceFacadeService()->getPlayerContext($file, $request->headers->get('user-agent'), $options);
        $playerContext['timeLimit'] = $this->getVideoFreeWatchTime($course, $task);
        $playerContext['securityVideoPlayer'] = (int) $this->getSettingService()->node('magic.security_video_player', 0);

        return $playerContext;
    }

    protected function getHomework($course, $task, $activity, $request, $ssl = false)
    {
        $user = $this->getCurrentUser();
        $assessment = $this->getAssessmentService()->showAssessment($activity['ext']['assessmentId']);
        $answerScene = $this->getAnswerSceneService()->get($activity['ext']['answerSceneId']);
        $answerRecord = $this->getAnswerRecordService()->getLatestAnswerRecordByAnswerSceneIdAndUserId($answerScene['id'], $user['id']);
        $testpaperWrapper = new TestpaperWrapper();
        $activity['ext'] = $testpaperWrapper->wrapTestpaper($assessment, $answerScene);
        if (empty($answerRecord)) {
            $activity['ext']['latestHomeworkResult'] = null;
        } else {
            $answerReport = $this->getAnswerReportService()->getSimple($answerRecord['answer_report_id']);
            $activity['ext']['latestHomeworkResult'] = $testpaperWrapper->wrapTestpaperResult(
                $answerRecord,
                $assessment,
                $answerScene,
                $answerReport
            );
        }

        return $activity['ext'];
    }

    protected function getExercise($course, $task, $activity, $request, $ssl = false)
    {
        $user = $this->getCurrentUser();
        $answerScene = $this->getAnswerSceneService()->get($activity['ext']['answerSceneId']);
        $answerRecord = $this->getAnswerRecordService()->getLatestAnswerRecordByAnswerSceneIdAndUserId($answerScene['id'], $user['id']);
        $testpaperWrapper = new TestpaperWrapper();
        if (empty($answerRecord) || AnswerRecordStatus::FINISHED == $answerRecord['status']) {
            try {
                $assessment = $this->getExerciseActivityService()->createExerciseAssessment($activity);
                $assessment = $this->getAssessmentService()->showAssessment($assessment['id']);
            } catch (ItemException $e) {
                if (ErrorCode::ITEM_NOT_ENOUGH == $e->getCode()) {
                    if (empty($answerRecord)) {
                        throw ExerciseException::LACK_QUESTION();
                    }
                    $assessment = $this->getAssessmentService()->showAssessment($answerRecord['assessment_id']);
                }
            }
            $activity['ext'] = $testpaperWrapper->wrapTestpaper($assessment, $answerScene);
            $activity['ext']['latestExerciseResult'] = null;
        } else {
            $assessment = $this->getAssessmentService()->showAssessment($answerRecord['assessment_id']);
            $answerReport = $this->getAnswerReportService()->getSimple($answerRecord['answer_report_id']);
            $activity['ext'] = $testpaperWrapper->wrapTestpaper($assessment, $answerScene);
            $activity['ext']['latestExerciseResult'] = $testpaperWrapper->wrapTestpaperResult(
                $answerRecord,
                $assessment,
                $answerScene,
                $answerReport ?? []
            );
        }

        $activity['ext']['lastExerciseResult'] = empty($answerRecord) ? null : $testpaperWrapper->wrapTestpaperResult(
            $answerRecord,
            $assessment,
            $answerScene,
            $answerReport ?? []
        );

        $activity['ext']['itemCounts'] = $activity['ext']['metas']['counts'] ?: (object) [];

        return $activity['ext'];
    }

    protected function getAudio($course, $task, $activity, $request, $ssl = false)
    {
        $config = $this->getActivityService()->getActivityConfig($activity['mediaType']);
        $audio = $config->get($activity['mediaId']);
        $file = $this->getUploadFileService()->getFullFile($audio['mediaId']);
        if (empty($file)) {
            throw UploadFileException::NOTFOUND_FILE();
        }
        if (!in_array($file['type'], ['audio', 'video'])) {
            throw PlayerException::NOT_SUPPORT_TYPE();
        }

        if ('escloud' == $request->query->get('version', 'qiqiuyun')) {
            $options['playAudio'] = $request->query->get('playAudio', 0);

            return $this->getAudioWithEsCloud($file, $audio, $activity, $options);
        }

        $player = $this->getPlayerService()->getAudioAndVideoPlayerType($file);

        $agentInWhiteList = $this->getResourceFacadeService()->agentInWhiteList($request->headers->get('user-agent'));

        $url = $this->getPlayUrl($file, [], $ssl);

        return [
            'resId' => $file['globalId'],
            'url' => isset($url) ? $url : null,
            'player' => $player,
            'hasText' => $audio['hasText'] ? true : false,
            'text' => $audio['hasText'] ? $activity['content'] : '',
            'agentInWhiteList' => $agentInWhiteList,
            'isEncryptionPlus' => false,
        ];
    }

    protected function getAudioWithEsCloud($file, $audio, $activity, $options)
    {
        $context = $this->getResourceFacadeService()->getPlayerContext($file, '', $options);
        $context['hasText'] = $audio['hasText'] ? true : false;
        $context['text'] = $audio['hasText'] ? $activity['content'] : '';

        return $context;
    }

    protected function getDoc($course, $task, $activity, $request, $ssl = false)
    {
        $config = $this->getActivityService()->getActivityConfig($activity['mediaType']);
        $doc = $config->get($activity['mediaId']);

        if ('escloud' == $request->query->get('version', 'qiqiuyun')) {
            $file = $this->getUploadFileService()->getFullFile($doc['mediaId']);

            return $this->getResourceFacadeService()->getPlayerContext($file);
        }

        list($result, $error) = $this->getPlayerService()->getDocFilePlayer($doc, $ssl);
        if (!empty($error)) {
            throw new BadRequestHttpException($error['message']);
        }

        return $result;
    }

    protected function getPpt($course, $task, $activity, $request, $ssl = false)
    {
        $config = $this->getActivityService()->getActivityConfig('ppt');

        $ppt = $config->get($activity['mediaId']);
        $file = $this->getUploadFileService()->getFullFile($ppt['mediaId']);

        if ('escloud' == $request->query->get('version', 'qiqiuyun')) {
            return $this->getResourceFacadeService()->getPlayerContext($file);
        }

        list($result, $error) = $this->getPlayerService()->getPptFilePlayer($ppt, $ssl);
        if (!empty($error)) {
            throw new BadRequestHttpException($error['message']);
        }

        return $result;
    }

    protected function getLive($course, $task, $activity, $request, $ssl = false)
    {
        $config = $this->getActivityService()->getActivityConfig($activity['mediaType']);
        $live = $config->get($activity['mediaId']);
        if ($live['roomCreated']) {
            $playerContext = [];
            if (LiveReplayService::REPLAY_VIDEO_GENERATE_STATUS == $live['replayStatus']) {
                $file = $this->getUploadFileService()->getFullFile($live['mediaId']);
                $playerContext = $this->getResourceFacadeService()->getPlayerContext($file);
            }

            return array_merge([
                'entryUrl' => $this->generateUrl('task_live_entry', ['courseId' => $course['id'], 'activityId' => $activity['id']], UrlGeneratorInterface::ABSOLUTE_URL),
                'startTime' => date('c', $activity['startTime']),
                'endTime' => date('c', $activity['endTime']),
            ], $playerContext);
        }
    }

    protected function getText($course, $task, $activity, $request, $ssl = false)
    {
        return [
            'title' => $activity['title'],
            'content' => $activity['content'],
        ];
    }

    protected function getPlayUrl($file, $context, $ssl)
    {
        $result = $this->getPlayerService()->getVideoPlayUrl($file, $context, $ssl);
        if (isset($result['url'])) {
            return $result['url'];
        }

        return $this->generateUrl($result['route'], $result['params'], $result['referenceType']);
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->getBiz()->service('Course:CourseService');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->getBiz()->service('Task:TaskService');
    }

    /**
     * @return ActivityService
     */
    protected function getActivityService()
    {
        return $this->getBiz()->service('Activity:ActivityService');
    }

    /**
     * @return PlayerService
     */
    protected function getPlayerService()
    {
        return $this->getBiz()->service('Player:PlayerService');
    }

    /**
     * @return UploadFileService
     */
    protected function getUploadFileService()
    {
        return $this->getBiz()->service('File:UploadFileService');
    }

    /**
     * @return MemberService
     */
    protected function getCourseMemberService()
    {
        return $this->getBiz()->service('Course:MemberService');
    }

    /**
     * @return MaterialService
     */
    protected function getMaterialService()
    {
        return $this->getBiz()->service('Course:MaterialService');
    }

    /**
     * @return AssessmentService
     */
    protected function getAssessmentService()
    {
        return $this->service('ItemBank:Assessment:AssessmentService');
    }

    /**
     * @return AnswerRecordService
     */
    protected function getAnswerRecordService()
    {
        return $this->service('ItemBank:Answer:AnswerRecordService');
    }

    /**
     * @return AnswerReportService
     */
    protected function getAnswerReportService()
    {
        return $this->service('ItemBank:Answer:AnswerReportService');
    }

    /**
     * @return AnswerQuestionReportService
     */
    protected function getAnswerQuestionReportService()
    {
        return $this->service('ItemBank:Answer:AnswerQuestionReportService');
    }

    /**
     * @return AnswerSceneService
     */
    protected function getAnswerSceneService()
    {
        return $this->service('ItemBank:Answer:AnswerSceneService');
    }

    /**
     * @return AnswerService
     */
    protected function getAnswerService()
    {
        return $this->service('ItemBank:Answer:AnswerService');
    }

    protected function getResourceFacadeService()
    {
        return $this->getBiz()->service('CloudPlatform:ResourceFacadeService');
    }

    protected function getClassroomService()
    {
        return $this->getBiz()->service('Classroom:ClassroomService');
    }

    /**
     * @return ExerciseActivityService
     */
    protected function getExerciseActivityService()
    {
        return $this->service('Activity:ExerciseActivityService');
    }
}
