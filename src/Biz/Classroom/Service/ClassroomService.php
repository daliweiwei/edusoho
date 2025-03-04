<?php

namespace Biz\Classroom\Service;

use Biz\System\Annotation\Log;

interface ClassroomService
{
    const COVER_SIZE_VERSION = '2'; //修改封面比例为16：9版本

    /**
     * @param $conditions
     * @param $orderBy
     * @param $start
     * @param $limit
     * @param $columns
     *
     * @return mixed
     */
    public function searchMembers($conditions, $orderBy, $start, $limit, array $columns = []);

    public function searchMembersByClassroomId($classroomId, $conditions, $start, $limit);

    public function countMembersByClassroomId($classroomId, $conditions);

    public function findClassroomsByIds(array $ids);

    public function findActiveCoursesByClassroomId($classroomId);

    public function findSortedCoursesByClassroomIdAndTitle(int $classroomId, string $title);

    // TODO refactor.
    public function findMembersByUserIdAndClassroomIds($userId, $classroomIds);

    public function getClassroom($id);

    public function hitClassroom($id);

    /**
     * @param $id
     * @param $fields
     *
     * @return mixed
     * @Log(module="classroom",action="update")
     */
    public function updateClassroom($id, $fields);

    public function updateClassroomInfo($id, $fields);

    public function batchUpdateOrg($classroomIds, $orgCode);

    public function waveClassroom($id, $field, $diff);

    public function findAssistants($classroomId);

    public function findTeachers($classroomId);

    public function canManageClassroom($id);

    public function tryManageClassroom($id, $actionPermission = null);

    public function canCreateThreadEvent($resource);

    public function addCoursesToClassroom($classroomId, $courseIds);

    /**
     * 是否可参与班级的活动，只有正式学员、教师、网站管理员才能参与班级的活动，旁听生不能参与活动.
     */
    public function canTakeClassroom($id, $includeAuditor = false);

    public function tryTakeClassroom($id, $includeAuditor = false);

    /**
     * 是否可处理班级事务（批改作业，试卷等）.
     */
    public function canHandleClassroom($id);

    public function tryHandleClassroom($id);

    /**
     * 是否可查看班级，　所有班级成员、网站管理员都可以查看.
     */
    public function canLookClassroom($id);

    public function tryLookClassroom($id);

    public function canJoinClassroom($id);

    public function canLearnClassroom($id);

    /**
     * @param $id
     *
     * @return mixed
     * @Log(module="classroom",action="delete")
     */
    public function deleteClassroom($id);

    public function searchClassrooms($conditions, $orderBy, $start, $limit, $columns = [], $withMarketingInfo = false);

    public function appendSpecsInfo($classrooms);

    public function appendSpecInfo($classroom);

    public function countClassrooms($condtions);

    /**
     * @param $classroom
     *
     * @return mixed
     * @Log(module="classroom",action="create")
     */
    public function addClassroom($classroom);

    public function findClassroomByTitle($title);

    public function findClassroomsByLikeTitle($title);

    /**
     * @param $id
     *
     * @return mixed
     * @Log(module="classroom",action="close",funcName="getClassroom")
     */
    public function closeClassroom($id);

    /**
     * @param $id
     *
     * @return mixed
     * @Log(module="classroom",action="close",funcName="getClassroom")
     */
    public function unpublishedClassroom($id);

    /**
     * @param $id
     *
     * @return mixed
     * @Log(module="classroom",action="publish",funcName="getClassroom")
     */
    public function publishClassroom($id);

    /**
     * 班级课程API.
     */
    public function updateClassroomCourses($classroomId, $activeCourseIds);

    public function findClassroomsByCoursesIds($courseIds);

    public function findClassroomsByCourseSetIds(array $courseSetIds);

    public function findClassroomCourseByCourseSetIds($courseSetIds);

    /**
     * @param  $courseId
     *
     * @return mixed
     */
    public function getClassroomByCourseId($courseId);

    public function getClassroomCourseByCourseSetId($courseSetId);

    // 内部方法
    public function updateClassroomTeachers($id);

    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     * @Log(module="classroom",action="update_picture",funcName="getClassroom",param="id")
     */
    public function changePicture($id, $data);

    public function isCourseInClassroom($courseId, $classroomId);

    public function deleteClassroomCourses($classroomId, array $courseIds, $real = true);

    public function isClassroomStudent($classroomId, $studentId);

    public function isClassroomAuditor($classroomId, $studentId);

    public function isClassroomAssistant($classroomId, $userId);

    public function isClassroomHeadTeacher($classroomId, $userId);

    public function findTeacherCanManagerClassRoomCourseSet($classroomId);

    public function updateMembers($conditions, $updateFields);

    public function changeMembersDeadlineByClassroomId($classroomId, $day, $waveType);

    public function updateMember($id, $member);

    public function searchMemberCount($conditions);

    public function searchMemberCountGroupByFields($conditions, $groupBy, $start, $limit);

    public function getClassroomMember($classroomId, $userId);

    public function remarkStudent($classroomId, $userId, $remark);

    public function removeStudent($classroomId, $userId, $info = []);

    public function removeStudents($classroomId, $userIds, $info);

    public function becomeStudent($classroomId, $userId, $info = []);

    public function becomeStudentWithOrder($classroomId, $userId, $info = []);

    public function becomeAuditor($classroomId, $userId);

    public function becomeAssistant($classroomId, $userId);

    /**
     * @param $classroomId
     * @param $userId
     *
     * @return mixed
     * @Log(module="classroom",action="update_head_teacher",funcName="getClassroom",param="classroomId")
     */
    public function addHeadTeacher($classroomId, $userId);

    public function updateAssistants($classroomId, $userIds);

    public function isClassroomTeacher($classroomId, $userId);

    public function findClassroomIdsByCourseId($courseId);

    public function findClassroomIdsByParentCourseId($parentCourseId);

    public function findByClassroomId($classroomId);

    public function findClassroomsByCourseId($courseId);

    /**
     * @param  $classroomId
     * @param  $courseId
     *
     * @return mixed
     */
    public function getClassroomCourse($classroomId, $courseId);

    public function findCoursesByClassroomId($classroomId);

    public function findSortedCoursesByClassroomIdAndCourseSetTitle(int $classroomId, string $title);

    public function getClassroomStudentCount($classroomId);

    public function findClassroomStudents($classroomId, $start, $limit);

    public function findClassroomMembersByRole($classroomId, $role, $start, $limit);

    public function lockStudent($classroomId, $userId);

    public function unlockStudent($classroomId, $userId);

    /**
     * @param $id
     * @param $number
     *
     * @return mixed
     * @Log(module="classroom",action="recommend",funcName="getClassroom")
     */
    public function recommendClassroom($id, $number);

    /**
     * @param $id
     *
     * @return mixed
     * @Log(module="classroom",action="cancel_recommend",funcName="getClassroom")
     */
    public function cancelRecommendClassroom($id);

    public function tryAdminClassroom($classroomId);

    public function getClassroomMembersByCourseId($courseId, $userId);

    public function findUserJoinedClassroomIds($userId);

    public function updateLearndNumByClassroomIdAndUserId($classroomId, $userId);

    public function countCoursesByClassroomId($classroomId);

    public function countMobileFilledMembersByClassroomId($classroomId, $locked = 0);

    public function isClassroomOverDue($classroom);

    public function updateMemberDeadlineByMemberId($memberId, $deadline);

    public function updateMembersDeadlineByDay($classroomId, $userIds, $day, $waveType);

    public function updateMembersDeadlineByDate($classroomId, $userIds, $date);

    public function checkDeadlineForUpdateDeadline($classroomId, $userIds, $date);

    public function checkDayAndWaveTypeForUpdateDeadline($classroomId, $userIds, $day, $waveType);

    public function updateMembersDeadlineByClassroomId($classroomId, $deadline);

    public function findWillOverdueClassrooms();

    public function countCourseTasksByClassroomId($classroomId);

    public function findUserPaidCoursesInClassroom($userId, $classroomId);

    public function findMembersByMemberIds($ids);

    public function tryFreeJoin($classroomId);

    public function refreshClassroomHotSeq();

    public function appendHasCertificate(array $classrooms);

    public function hasCertificate($classroomId);

    public function searchMembersSignStatistics($classroomId, array $conditions, array $orderBy, $start, $limit);

    public function updateClassroomMembersFinishedStatusByLimit($classroomId, $start, $limit);

    public function updateClassroomMembersFinishedStatus($classroomId);

    public function updateClassroomMemberFinishedStatus($classroomId, $userId);

    public function searchClassroomsWithStatistics($conditions, $orderBy, $start, $limit, $columns = []);

    public function calClassroomsTaskNums(array $classrooms, $withMemberInfo = false);

    public function updateMemberFieldsByClassroomIdAndUserId($classroomId, $userId, array $fields);

    public function updateClassroomMembersNoteAndThreadNumsByLimit($classroomId, $start, $limit);

    public function updateClassroomMembersNoteAndThreadNums($classroomId);
}
