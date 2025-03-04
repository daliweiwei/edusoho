<?php

namespace Biz\Course\Dao\Impl;

use Biz\Course\Dao\CourseDao;
use Biz\Course\Dao\CourseMemberDao;
use Codeages\Biz\Framework\Dao\AdvancedDaoImpl;
use Codeages\Biz\Framework\Dao\DaoException;

class CourseMemberDaoImpl extends AdvancedDaoImpl implements CourseMemberDao
{
    protected $table = 'course_member';
    protected $alias = 'm';

    public function findByIds($ids)
    {
        return $this->findInField('id', $ids);
    }

    public function findByCourseId($courseId)
    {
        return $this->findByFields([
            'courseId' => $courseId,
        ]);
    }

    public function findUserIdsByCourseId($courseId)
    {
        $sql = "SELECT userId FROM {$this->table} WHERE courseId = ?";

        return $this->db()->fetchAll($sql, [$courseId]);
    }

    public function findByUserId($userId)
    {
        return $this->findByFields([
            'userId' => $userId,
        ]);
    }

    public function findByCourseIds($courseIds)
    {
        return $this->findInField('courseId', $courseIds);
    }

    public function findLastLearnTimeRecordStudents($userIds)
    {
        if (empty($userIds)) {
            return [];
        }
        $marks = str_repeat('?,', count($userIds) - 1).'?';

        $childSql = "SELECT userId, max(lastLearnTime) as maxLastLearnTime FROM course_member  WHERE role = ? AND userId IN ($marks) AND finishedTime = 0 group by userId";
        $sql = 'select cm.*,c.compulsoryTaskNum as compulsoryTaskNum from `course_member` as cm right join ('.$childSql.') as m on cm.userId = m.userId and cm.lastLearnTime = m.maxLastLearnTime JOIN course_v8 AS c ON cm.courseId = c.id where cm.learnedCompulsoryTaskNum < compulsoryTaskNum order by cm.userId DESC';

        return $this->db()->fetchAll($sql, array_merge(['student'], $userIds));
    }

    public function getByCourseIdAndUserId($courseId, $userId)
    {
        return $this->getByFields([
            'courseId' => $courseId,
            'userId' => $userId,
        ]);
    }

    public function findLearnedByCourseIdAndUserId($courseId, $userId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE courseId = ? AND userId = ? AND isLearned = 1";

        return $this->db()->fetchAll($sql, [$courseId, $userId]);
    }

    public function findByCourseIdAndRole($courseId, $role)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE courseId = ? AND role = ? ORDER BY seq, createdTime DESC";

        return $this->db()->fetchAll($sql, [$courseId, $role]);
    }

    public function findByCourseSetIdAndRole($courseSetId, $role)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE courseSetId = ? AND role = ? ORDER BY seq, createdTime DESC";

        return $this->db()->fetchAll($sql, [$courseSetId, $role]);
    }

    public function findByCourseSetIdAndRoles($courseSetId, $roles)
    {
        if (empty($roles)) {
            return [];
        }

        $marks = str_repeat('?,', count($roles) - 1).'?';
        $sql = "SELECT * FROM {$this->table()} WHERE courseSetid = ? AND role in ($marks);";

        return $this->db()->fetchAll($sql, array_merge([$courseSetId], $roles));
    }

    public function findByMultiClassIdsAndRole($multiClassIds, $role)
    {
        if (empty($multiClassIds)) {
            return [];
        }

        $marks = str_repeat('?,', count($multiClassIds) - 1).'?';

        $sql = "SELECT * FROM {$this->table()} WHERE role = ? AND multiClassId in ($marks)";

        return $this->db()->fetchAll($sql, array_merge([$role], $multiClassIds));
    }

    public function findByUserIdAndRoles($userId, $roles)
    {
        if (empty($roles)) {
            return [];
        }

        $marks = str_repeat('?,', count($roles) - 1).'?';
        $sql = "SELECT * FROM {$this->table()} WHERE userId = ? AND role in ($marks);";

        return $this->db()->fetchAll($sql, array_merge([$userId], $roles));
    }

    public function findUserIdsByCourseIdAndRoles($courseId, $roles)
    {
        if (empty($roles) || empty($courseId)) {
            return [];
        }

        $marks = str_repeat('?,', count($roles) - 1).'?';
        $sql = "SELECT userId, role FROM {$this->table()} WHERE courseId = ? AND role in ($marks);";

        return $this->db()->fetchAll($sql, array_merge([$courseId], $roles));
    }

    public function getByMultiClassIdAndUserId($multiClassId, $userId)
    {
        return $this->getByFields(['multiClassId' => $multiClassId, 'userId' => $userId]);
    }

    public function findByUserIdAndJoinType($userId, $joinedType)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE  userId = ? AND joinedType = ?";

        return $this->db()->fetchAll($sql, [$userId, $joinedType]);
    }

    public function findByMultiClassIdAndRole($multiClassId, $role)
    {
        return $this->findByFields(['multiClassId' => $multiClassId, 'role' => $role]);
    }

    public function deleteByCourseIdAndRole($courseId, $role)
    {
        return $this->db()->delete($this->table(), ['courseId' => $courseId, 'role' => $role]);
    }

    public function deleteByMultiClassAndRole($multiClassId, $role)
    {
        return $this->db()->delete($this->table(), ['multiClassId' => $multiClassId, 'role' => $role]);
    }

    public function findMultiClassIdsByUserId($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $sql = "SELECT multiClassId FROM {$this->table()} WHERE userId = ? AND role = 'teacher'";

        return $this->db()->fetchAll($sql, [$userId]);
    }

    public function deleteByCourseId($courseId)
    {
        return $this->db()->delete($this->table(), ['courseId' => $courseId]);
    }

    public function findByUserIdAndCourseIds($studentId, $courseIds)
    {
        if (empty($courseIds)) {
            return [];
        }

        $marks = str_repeat('?,', count($courseIds) - 1).'?';
        $sql = "SELECT * FROM {$this->table()} WHERE userId = ? AND role = 'student' AND courseId in ($marks)";

        return $this->db()->fetchAll($sql, array_merge([$studentId], $courseIds));
    }

    public function findByUserIdAndClassroomId($userId, $classroomId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE userId = ? AND classroomId = ?;";

        return $this->db()->fetchAll($sql, [$userId, $classroomId]);
    }

    public function findByUserIdsAndClassroomId($userIds, $classroomId)
    {
        if (empty($userIds)) {
            return [];
        }
        $marks = str_repeat('?,', count($userIds) - 1).'?';
        $sql = "SELECT * FROM {$this->table} WHERE classroomId = ? AND userId IN ({$marks});";

        return $this->db()->fetchAll($sql, array_merge([$classroomId], $userIds));
    }

    public function findByUserIdsAndRole($userIds, $role)
    {
        if (empty($userIds)) {
            return [];
        }

        $marks = str_repeat('?,', count($userIds) - 1).'?';
        $sql = "SELECT * FROM {$this->table} WHERE role = ? AND userId IN ({$marks});";

        return $this->db()->fetchAll($sql, array_merge([$role], $userIds));
    }

    public function countLearningMembers($conditions)
    {
        $sql = "SELECT COUNT(m.id) FROM {$this->table()} m ";
        $sql .= ' INNER JOIN course_v8 c ON m.courseId = c.id ';
        $sql .= ' WHERE ';

        list($sql, $params) = $this->applySqlParams($conditions, $sql);

        $sql .= '(m.learnedCompulsoryTaskNum < c.compulsoryTaskNum) ';

        return $this->db()->fetchColumn($sql, $params);
    }

    public function findLearningMembers($conditions, $start, $limit)
    {
        $sql = "SELECT m.* FROM {$this->table()} m ";
        $sql .= ' INNER JOIN course_v8 c ON m.courseId = c.id ';
        $sql .= ' WHERE ';

        list($sql, $params) = $this->applySqlParams($conditions, $sql);

        $sql .= '(m.learnedCompulsoryTaskNum < c.compulsoryTaskNum) ';

        $sql = $this->sql($sql, ['createdTime' => 'DESC'], $start, $limit);

        return $this->db()->fetchAll($sql, $params) ?: [];
    }

    public function countUserLearnCourses($userId)
    {
        $sql = "SELECT COUNT(DISTINCT courseId) FROM {$this->table()} WHERE userId = ? and role = 'student'";

        return $this->db()->fetchColumn($sql, [$userId]);
    }

    public function findUserLearnCourseIds($userId)
    {
        $sql = "SELECT DISTINCT courseId FROM {$this->table()} WHERE userId = ? and role = 'student'";

        return $this->db()->fetchAll($sql, [$userId]) ?: [];
    }

    public function countLearnedMembers($conditions)
    {
        $sql = "SELECT COUNT(m.id) FROM {$this->table()} m ";
        $sql .= ' INNER JOIN course_v8 c ON m.courseId = c.id ';
        $sql .= ' WHERE c.compulsoryTaskNum > 0 AND ';

        list($sql, $params) = $this->applySqlParams($conditions, $sql);
        $sql .= 'm.learnedCompulsoryTaskNum >= c.compulsoryTaskNum ';
        $sql = $this->sql($sql);

        return $this->db()->fetchColumn($sql, $params);
    }

    public function findLearnedMembers($conditions, $start, $limit)
    {
        $sql = "SELECT m.* FROM {$this->table()} m ";
        $sql .= ' INNER JOIN course_v8 c ON m.courseId = c.id ';
        $sql .= ' WHERE c.compulsoryTaskNum > 0 AND ';
        list($sql, $params) = $this->applySqlParams($conditions, $sql);

        $sql .= 'm.learnedCompulsoryTaskNum >= c.compulsoryTaskNum ';

        $sql = $this->sql($sql, ['createdTime' => 'DESC'], $start, $limit);

        return $this->db()->fetchAll($sql, $params) ?: [];
    }

    public function searchMemberCountGroupByFields($conditions, $groupBy, $start, $limit)
    {
        $this->filterStartLimit($start, $limit);
        $builder = $this->createQueryBuilder($conditions)
            ->select("{$groupBy}, COUNT(id) AS count")
            ->groupBy($groupBy)
            ->orderBy('count', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($limit);

        return $builder->execute()->fetchAll() ?: [];
    }

    public function findByUserIdAndRole($userId, $role)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE userId = ? AND role =  ?";

        return $this->db()->fetchAll($sql, [$userId, $role]);
    }

    /**
     * @param  $userId
     * @param  $courseSetId
     * @param  $role
     *
     * @return array
     */
    public function findByUserIdAndCourseSetIdAndRole($userId, $courseSetId, $role)
    {
        return $this->findByFields([
            'userId' => $userId,
            'courseSetId' => $courseSetId,
            'role' => $role,
        ]);
    }

    public function findByConditionsGroupByUserId($conditions, $orderBy, $offset, $limit)
    {
        $fields = array_keys($conditions);
        array_walk($fields, function (&$value) {
            $value = $value.' = ? ';
        });

        $wherePart = '('.implode(') '.'AND'.' (', $fields).')';

        $declares = $this->declares();
        $selectFields = array_merge(['id'], $declares['orderbys']);
        array_walk($selectFields, function (&$value) {
            $value = 'MAX('.$value.') AS '.$value;
        });

        $selectList = implode(',', $selectFields);

        $sql = "SELECT {$selectList} FROM {$this->table} WHERE {$wherePart} GROUP BY UserId";

        return $this->db()->fetchAll($this->sql($sql, $orderBy, $offset, $limit), array_values($conditions));
    }

    public function findMembersNotInClassroomByUserIdAndRole($userId, $role, $start, $limit, $onlyPublished = true, $filterReservation = false)
    {
        $sql = "SELECT m.* FROM {$this->table} m ";
        $sql .= ' JOIN  course_v8 AS c ON m.userId = ? ';
        $sql .= ' AND m.role =  ? AND m.courseId = c.id AND c.parentId = 0';

        if ($onlyPublished) {
            $sql .= " AND c.status = 'published' ";
        }

        if ($filterReservation) {
            $sql .= " AND c.type != 'reservation'";
        }

        $sql .= ' ORDER BY createdTime DESC';
        $sql = $this->sql($sql, [], $start, $limit);

        return $this->db()->fetchAll($sql, [$userId, $role]);
    }

    public function searchMemberIds($conditions, $orderBys, $start, $limit)
    {
        $this->filterStartLimit($start, $limit);
        $builder = $this->createQueryBuilder($conditions);
        $declares = $this->declares();
        foreach ($orderBys ?: [] as $order => $sort) {
            $this->checkOrderBy($order, $sort, $declares['orderbys']);
            $builder->addOrderBy($order, $sort);
        }

        if (isset($conditions['unique'])) {
            $builder->select('userId');
            $builder->from('('.$builder->getSQL().')', $this->table());
            //when we use distinct in strict mode, it's not allowed to order by field that is not in select part,
            //so we use a sub query, and reset result field here.
            $builder->select('distinct userId');
            $builder->resetQueryPart('where');
            $builder->resetQueryPart('orderBy');
        } else {
            $builder->select('userId');
        }

        $builder->setFirstResult($start);
        $builder->setMaxResults($limit);

        return $builder->execute()->fetchAll() ?: [];
    }

    public function updateMembers($conditions, $updateFields)
    {
        return $this->db()->update($this->table, $updateFields, $conditions);
    }

    public function changeMembersDeadlineByCourseId($courseId, $day)
    {
        $sql = "UPDATE course_member SET deadline = deadline {$day} WHERE courseId = {$courseId} and deadline != 0;";

        return $this->db()->executeUpdate($sql, [$courseId, $day]);
    }

    public function countThreadsByCourseIdAndUserId($courseId, $userId, $type = 'discussion')
    {
        $sql = "SELECT count(id) FROM course_thread WHERE type='{$type}' AND courseId = ? AND userId = ?";

        return $this->db()->fetchColumn($sql, [$courseId, $userId]);
    }

    public function countActivitiesByCourseIdAndUserId($courseId, $userId)
    {
        $sql = 'SELECT count(distinct(activityId)) FROM course_task_result WHERE courseId = ? AND userId = ?';

        return $this->db()->fetchColumn($sql, [$courseId, $userId]);
    }

    public function countPostsByCourseIdAndUserId($courseId, $userId)
    {
        $sql = "SELECT count(id) FROM course_thread_post WHERE userId = ? and threadId IN (SELECT id FROM course_thread WHERE courseId = ? AND type='discussion')";

        return $this->db()->fetchColumn($sql, [$userId, $courseId]);
    }

    public function countMemberNotInClassroomByUserIdAndCourseTypeAndIsLearned($userId, $role, $type, $isLearned)
    {
        $sql = "SELECT COUNT( m.courseId ) FROM {$this->table} m ";
        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= ' AND m.role = ? AND c.type =  ? AND m.isLearned = ? AND m.courseId = c.id  AND c.parentId = 0';

        return $this->db()->fetchColumn($sql, [$userId, $role, $type, $isLearned]);
    }

    public function countMemberNotInClassroomByUserIdAndRoleAndIsLearned($userId, $role, $isLearned, $filterReservation = false)
    {
        $sql = "SELECT COUNT( m.courseId ) FROM {$this->table} m ";
        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= ' AND m.role = ? AND m.isLearned = ? AND m.courseId = c.id  AND c.parentId = 0';
        if ($filterReservation) {
            $sql .= " AND c.type != 'reservation'";
        }

        return $this->db()->fetchColumn($sql, [$userId, $role, $isLearned]);
    }

    public function countMemberNotInClassroomByUserIdAndRole($userId, $role, $onlyPublished = true)
    {
        $sql = "SELECT COUNT( m.courseId ) FROM {$this->table} m ";
        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= ' AND m.role =  ? AND m.courseId = c.id AND c.parentId = 0';

        if ($onlyPublished) {
            $sql .= " AND c.status = 'published' ";
        }

        return $this->db()->fetchColumn($sql, [$userId, $role]);
    }

    public function findMembersNotInClassroomByUserIdAndCourseTypeAndIsLearned($userId, $role, $type, $isLearned, $start, $limit)
    {
        $sql = "SELECT m.* FROM {$this->table} m";
        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= 'AND m.role = ? AND c.type = ?  AND m.isLearned = ? AND m.courseId = c.id AND c.parentId = 0';
        $sql .= " ORDER BY createdTime DESC LIMIT {$start}, {$limit}";

        return $this->db()->fetchAll($sql, [$userId, $role, $type, $isLearned]);
    }

    public function findMembersNotInClassroomByUserIdAndRoleAndIsLearned($userId, $role, $isLearned, $start, $limit, $filterReservation = false)
    {
        $sql = "SELECT m.* FROM {$this->table} m ";
        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= 'AND m.role =  ? AND m.isLearned = ? AND m.courseId = c.id AND c.parentId = 0';

        if ($filterReservation) {
            $sql .= " AND c.type != 'reservation'";
        }

        $sql .= ' ORDER BY createdTime DESC';

        $sql = $this->sql($sql, [], $start, $limit);

        return $this->db()->fetchAll($sql, [$userId, $role, $isLearned]);
    }

    public function countMemberByUserIdAndCourseTypeAndIsLearned($userId, $role, $type, $isLearned)
    {
        $sql = "SELECT COUNT( m.courseId ) FROM {$this->table} m ";
        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= ' AND c.type =  ? AND m.courseId = c.id  AND m.isLearned = ? AND m.role = ?';

        return $this->db()->fetchColumn($sql, [$userId, $type, $isLearned, $role]);
    }

    public function countMemberByUserIdAndRoleAndIsLearned($userId, $role, $isLearned, $filterReservation = false)
    {
        //        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE  userId = ? AND role = ? AND isLearned = ?";
        $sql = "SELECT COUNT( m.courseId ) FROM {$this->table} m ";
        $sql .= ' JOIN '.CourseDao::TABLE_NAME.' AS c ON m.courseId = c.id AND m.userId = ? AND m.role = ? AND m.isLearned = ?';
        if ($filterReservation) {
            $sql .= " AND c.type != 'reservation'";
        }

        return $this->db()->fetchColumn($sql, [$userId, $role, $isLearned]);
    }

    public function findMembersNotInClassroomByUserIdAndRoleAndType($userId, $role, $type, $start, $limit, $onlyPublished = true)
    {
        $sql = "SELECT m.* FROM {$this->table} m ";

        $sql .= ' JOIN  '.CourseDao::TABLE_NAME.' AS c ON m.userId = ? ';
        $sql .= ' AND m.role =  ? AND c.type = ? AND m.courseId = c.id AND c.parentId = 0';

        if ($onlyPublished) {
            $sql .= " AND c.status = 'published' ";
        }

        $sql .= ' ORDER BY createdTime DESC';
        $sql = $this->sql($sql, [], $start, $limit);

        return $this->db()->fetchAll($sql, [$userId, $role, $type]);
    }

    public function updateByClassroomIdAndUserId($classroomId, $userId, array $fields)
    {
        return $this->update([
            'classroomId' => $classroomId,
            'userId' => $userId,
        ], $fields);
    }

    public function updateByClassroomId($classroomId, array $fields)
    {
        return $this->update([
            'classroomId' => $classroomId,
        ], $fields);
    }

    /**
     * @param $conditions
     * @param string $format
     *
     * @return array
     */
    public function searchMemberCountsByConditionsGroupByCreatedTimeWithFormat($conditions, $format = '%Y-%m-%d')
    {
        $builder = $this->createQueryBuilder($conditions)
            ->select("COUNT(id) as count, from_unixtime(createdTime, '{$format}') as date")
            ->from($this->table, $this->table)
            ->groupBy('date')
            ->orderBy('date', 'ASC');

        return $builder->execute()->fetchAll();
    }

    public function isFieldExist($filedName)
    {
        $sql = "DESCRIBE `{$this->table}` `{$filedName}`;";
        $result = $this->db()->fetchAssoc($sql);

        return empty($result) ? false : true;
    }

    public function getMultiClassMembers($courseId, $multiClassId, $role)
    {
        $sql = "SELECT m.userId,u.nickname from {$this->table} as m";
        $sql .= ' LEFT JOIN user as u ON m.userId = u.id';
        $sql .= ' where `courseId` = ? and `multiClassId` = ? and `role` = ?';

        return $this->db()->fetchAll($sql, [$courseId, $multiClassId, $role]);
    }

    public function countGroupByCourseId($conditions)
    {
        $builder = $this->createQueryBuilder($conditions)
            ->select('count(*) as count, courseId')
            ->groupBy('courseId');

        return $builder->execute()->fetchAll();
    }

    public function declares()
    {
        return [
            'timestamps' => ['createdTime', 'updatedTime'],
            'orderbys' => [
                'createdTime',
                'lastLearnTime',
                'classroomId',
                'id',
                'updatedTime',
                'lastViewTime',
                'seq',
                'learnedCompulsoryTaskNum',
                'deadline',
                'stickyTime',
            ],
            'conditions' => [
                'id NOT IN (:excludeIds)',
                'userId = :userId',
                'courseSetId = :courseSetId',
                'multiClassId = :multiClassId',
                'multiClassId IN (:multiClassIds)',
                'courseId = :courseId',
                'isLearned = :isLearned',
                'joinedType = :joinedType',
                'role = :role',
                'isVisible = :isVisible',
                'classroomId = :classroomId',
                'userId NOT IN (:excludeUserIds)',
                'noteNum > :noteNumGreaterThan',
                'createdTime >= :startTimeGreaterThan',
                'createdTime < :startTimeLessThan',
                'courseId IN (:courseIds)',
                'courseSetId IN (:courseSetIds)',
                'userId IN (:userIds)',
                'learnedCompulsoryTaskNum >= :learnedCompulsoryTaskNumGreaterThan',
                'learnedCompulsoryTaskNum < :learnedCompulsoryTaskNumLT',
                'learnedNum >= :learnedNumGreaterThan',
                'learnedNum < :learnedNumLessThan',
                'deadline <= :deadlineLessThen',
                'deadline >= :deadlineGreaterThan',
                'lastViewTime >= :lastViewTime_GE',
                'lastLearnTime = :lastLearnTime',
                'lastLearnTime >= :lastLearnTimeGreaterThan',
                'lastLearnTime < :lastLearnTimeLessThen',
                'lastLearnTime > :lastLearnTime_GT',
                'updatedTime >= :updatedTime_GE',
                'finishedTime >= :finishedTime_GE',
                'finishedTime <= :finishedTime_LE',
                'finishedTime > :finishedTime_GT',
                'lastLearnTime <= :lastLearnTime_LE',
                'deadlineNotified = :deadlineNotified',
                'classroomId IN (:classroomIds)',
                'startLearnTime > :startLearnTime_GT',
            ],
        ];
    }

    /**
     * @param  $conditions
     * @param  $sql
     *
     * @return array
     */
    protected function applySqlParams($conditions, $sql)
    {
        $params = [];
        $conditions = array_filter($conditions, function ($value) {
            return !empty($value);
        });
        foreach ($conditions as $key => $value) {
            $sql .= $key.' = ? AND ';
            array_push($params, $value);
        }

        return [$sql, $params];
    }

    private function createDaoException($message = '', $code = 0)
    {
        return new DaoException($message, $code);
    }

    private function checkOrderBy($order, $sort, $allowOrderBys)
    {
        if (!in_array($order, $allowOrderBys, true)) {
            throw $this->createDaoException(sprintf("SQL order by field is only allowed '%s', but you give `{$order}`.", implode(',', $allowOrderBys)));
        }
        if (!in_array(strtoupper($sort), ['ASC', 'DESC'], true)) {
            throw $this->createDaoException("SQL order by direction is only allowed `ASC`, `DESC`, but you give `{$sort}`.");
        }
    }
}
