<?php

namespace Biz\Course\Service;

use Biz\System\Annotation\Log;

interface MemberService
{
    const MAX_TEACHER = 100;

    public function becomeStudentAndCreateOrder($userId, $courseId, $data);

    public function removeCourseStudent($courseId, $userId);

    public function removeCourseStudents($courseId, array $userIs);

    public function searchMembers($conditions, $orderBy, $start, $limit, $columns = []);

    /**
     * @before searchMemberCount
     *
     * @param  $conditions
     *
     * @return mixed
     */
    public function countMembers($conditions);

    public function stickMyCourseByCourseSetId($courseSetId);

    public function unStickMyCourseByCourseSetId($courseSetId);

    public function findWillOverdueCourses();

    public function getCourseMember($courseId, $userId);

    public function waveMember($id, $diffs);

    public function searchMemberIds($conditions, $sort, $start, $limit);

    public function findMemberUserIdsByCourseId($courseId);

    public function updateMember($id, $fields);

    public function updateMembers($conditions, $updateFields);

    public function isMemberNonExpired($course, $member);

    public function findCourseStudents($courseId, $start, $limit);

    public function findCourseTeachers($courseId);

    public function findMultiClassMemberByMultiClassIdAndRole($multiClassId, $role);

    public function findCourseSetTeachers($courseSetId);

    public function findCourseSetTeachersAndAssistant($courseSetId);

    public function findCourseStudentsByCourseIds($courseIds);

    public function findLatestStudentsByCourseSetId($courseSetId, $offset, $limit);

    public function getCourseStudentCount($courseId);

    public function getMultiClassMembers($courseId, $multiClassId, $role);

    public function isCourseTeacher($courseId, $userId);

    public function isCourseAssistant($courseId, $userId);

    public function isCourseStudent($courseId, $userId);

    public function isCourseMember($courseId, $userId);

    public function setDefaultTeacher($courseId);

    public function findLastLearnTimeRecordStudents($userIds);

    /**
     * @param $courseId
     * @param $teachers
     * @param $multiClassId
     *
     * @return mixed
     * @Log(module="course",action="update_teacher",serviceName="Course:CourseService",funcName="getCourse",param="courseId")
     */
    public function setCourseTeachers($courseId, $teachers, $multiClassId = 0);

    public function setCourseAssistants($courseId, $assistantIds, $multiClassId = 0);

    public function cancelTeacherInAllCourses($userId);

    public function remarkStudent($courseId, $userId, $remark);

    public function deleteMemberByCourseIdAndRole($courseId, $role);

    public function deleteMemberByCourseId($courseId);

    public function findMembersByUserIdAndJoinType($userId, $joinedType = 'course');

    public function quitCourseByDeadlineReach($userId, $courseId);

    /**
     * 成为学员，即加入课程的学习.
     */
    public function becomeStudent($courseId, $userId, $info = []);

    public function batchBecomeStudents($courseId, $memberIds);

    /**
     * 退学.
     */
    public function removeStudent($courseId, $userId, $info = []);

    /**
     * 封锁学员，封锁之后学员不能再查看该课程.
     */
    public function lockStudent($courseId, $userId);

    /**
     * 解封学员.
     */
    public function unlockStudent($courseId, $userId);

    public function createMemberByClassroomJoined($courseId, $userId, $classRoomId, array $info);

    public function batchCreateMembers($members);

    public function findCoursesByStudentIdAndCourseIds($userId, $courseIds);

    public function findCourseMembersByUserIdAndCourseIds($userId, $courseIds);

    public function findCourseMembersByUserIdAndClassroomId($userId, $classroomId);

    public function findCourseMembersByUserIdsAndClassroomId($userIds, $classroomId);

    public function findMembersByUserIdsAndRole($userIds, $role);

    public function becomeStudentByClassroomJoined($courseId, $userId);

    public function refreshMemberNoteNumber($courseId, $userId);

    /**
     * @param int $userId
     *
     * @return array[]
     */
    public function findTeacherMembersByUserId($userId);

    /**
     * @param  $userId
     * @param  $courseSetId
     *
     * @return array
     */
    public function findTeacherMembersByUserIdAndCourseSetId($userId, $courseSetId);

    /**
     * @param int $userId
     *
     * @return array[]
     */
    public function findStudentMemberByUserId($userId);

    public function countQuestionsByCourseIdAndUserId($courseId, $userId);

    public function countActivitiesByCourseIdAndUserId($courseId, $userId);

    public function countDiscussionsByCourseIdAndUserId($courseId, $userId);

    public function countPostsByCourseIdAndUserId($courseId, $userId);

    public function changeMembersDeadlineByCourseId($courseId, $day, $waveType);

    public function batchUpdateMemberDeadlinesByDay($courseId, $userIds, $day, $waveType = 'plus');

    public function checkDayAndWaveTypeForUpdateDeadline($courseId, $userIds, $day, $waveType = 'plus');

    public function batchUpdateMemberDeadlinesByDate($courseId, $userIds, $date);

    public function checkDeadlineForUpdateDeadline($date);

    public function updateMemberDeadlineByClassroomIdAndUserId($classroomId, $userId, $deadline);

    public function updateMembersDeadlineByClassroomId($classroomId, $deadline);

    public function findMembersByCourseIdAndRole($courseId, $role);

    public function findDailyIncreaseNumByCourseIdAndRoleAndTimeRange($courseId, $role, $timeRange = [], $format = '%Y-%m-%d');

    public function findMembersByIds($ids);

    public function countStudentMemberByCourseSetId($courseSetId);

    public function recountLearningDataByCourseId($courseId);

    public function refreshMemberFinishData($courseId, $userId);

    public function refreshCourseMembersFinishData($courseId);

    public function getUserLiveroomRoleByCourseIdAndUserId($courseId, $userId);

    public function releaseMultiClassMember($courseId, $multiClassId);

    public function findMultiClassMembersByMultiClassIdsAndRole($multiClassIds, $role);

    public function searchMultiClassIds($conditions, $sort, $start, $limit);

    public function deleteMemberByMultiClassIdAndRole($multiClassId, $role);

    public function findMembersByUserIdAndRoles($userId, $roles);

    public function getMemberByMultiClassIdAndUserId($multiClassId, $userId);

    public function findMultiClassIdsByUserId($userId);

    public function countGroupByCourseId($conditions);

    public function findGroupUserIdsByCourseIdAndRoles($courseId, $roles);
}
