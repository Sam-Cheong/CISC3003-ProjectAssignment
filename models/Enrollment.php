<?php
require_once __DIR__ . '/../config/Database.php';

class Enrollment {
    private $db;
    public const ALLOWED_STATUSES = ['pending', 'active', 'finished'];

    public function __construct() {
        $this->db = new Database();
    }

    public function __destruct() {
        $this->db = null;
    }
    public function isExist(array $enrollmentdata){
        if($enrollmentdata['enrollmentID'] != null) {
            // select by enrollmentID
            $this->db->query("SELECT enrollmentID FROM enrollments WHERE enrollmentID = :enrollmentID");
            $this->db->bind(':enrollmentID', $enrollmentdata['enrollmentID']);
            $result = $this->db->single();
            return $result;
        }elseif($enrollmentdata['userID'] != null && $enrollmentdata['courseID'] != null) {
            // select by userID and courseID
            $this->db->query("SELECT enrollmentID FROM enrollments WHERE userID = :userID AND courseID = :courseID");
            $this->db->bind(':userID', $enrollmentdata['userID']);
            $this->db->bind(':courseID', $enrollmentdata['courseID']);
            $result = $this->db->single();
            return $result;
        }else {return null;}
    }
    /**
     * enrollment a user in a course.
     *
     * @param int $userID   The user's ID.
     * @param int $courseID The course's ID.
     * @param string $status enrollment status, default is 'pending'.
     * @return bool Returns true on success, false if already enrollmented or an error occurs.
     */
    public function createEnrollment(array $enrollmentdata): bool {
        // Check if the user is already enrollmented in the course.
        if ($this->isExist($enrollmentdata)) {
            // Already enrollmented.
            return false;
            // enrollment conflict
        }
      
        // Insert a new enrollment.
        $sql = 'INSERT INTO enrollments (userID, course_id) VALUES (:uid, :cid)';
        $this->db->query($sql);
        $this->db->bind(':uid', $enrollmentdata['userID']);
        $this->db->bind(':cid', $enrollmentdata['courseID']);
        return $this->db->execute();
    }

    /**
     * Remove an enrollment by enrollment ID.
     *
     * @param int $enrollmentID The enrollment record identifier.
     * @return bool Returns true on success, false on failure.
     */
    public function removeEnrollment($enrollment): bool {
        $this->db->query('DELETE FROM enrollments WHERE enrollmentID = :eid');
        $this->db->bind(':eid', $enrollment->enrollmentID);
        return $this->db->execute();
    }

    /**
     * Get all enrollments (with course details).
     *
     * @return array[$enrollment] Returns an array of enrollment records.
     */
    public function getEnrollments(): array {
        $this->db->query('
            SELECT e.*, c.course_id, c.course_code, c.course_name, c.teacher, c.schedule, u.userName
            FROM enrollments e 
            JOIN courses c ON e.course_id = c.course_id
            JOIN users u ON e.userID = u.userID
            ORDER BY e.enrollmentID DESC
        ');
        $enrollments = $this->db->resultSet();
        return $enrollments;
    }

    /**
     * Get all enrollments (with course details) for a specific user.
     *
     * @param int $userID
     * @return array[$enrollment] Returns an array of enrollment records.
     */
    public function getEnrollmentsByUser(int $userID): array {
        $this->db->query('
            SELECT e.*, c.course_id, c.course_code, c.course_name, c.teacher, c.schedule
            FROM enrollments e 
            JOIN courses c ON e.course_id = c.course_id
            WHERE e.userID = :uid
            ORDER BY e.enrollmentID DESC
        ');
        $this->db->bind(':uid', $userID);
        $enrollments = $this->db->resultSet();
        return $enrollments;
    }

    public function getEnrollmentsByCourse(int $course_id): array {
        $this->db->query('
            SELECT e.*, u.userID, u.userName, u.userEmail
            FROM enrollments e 
            JOIN users u ON e.userID = u.userID
            WHERE e.course_id = :cid
            ORDER BY e.enrollment_date DESC
        ');
        $this->db->bind(':cid', $course_id);
        $enrollments = $this->db->resultSet();
        return $enrollments;
    }

        /**
     * Update the status of an enrollment.
     *
     * @param int    $enrollmentID The enrollment record identifier.
     * @param string $newStatus    The new status ('pending', 'active', 'finished').
     * @return bool Returns true on success, false on failure.
     */
    public function updateStatus(int $enrollmentID, string $newStatus): bool {
        // 验证新状态是否在允许的枚举值中
        $allowedStatuses = ['pending', 'active', 'finished'];
        if (!in_array($newStatus, $allowedStatuses, true)) {
            error_log("updateStatus: Invalid status '{$newStatus}' for enrollment ID {$enrollmentID}");
            return false;
        }

        $sql = "UPDATE enrollments SET status = :status WHERE enrollmentID = :eid";
        $this->db->query($sql);
        $this->db->bind(':status', $newStatus);
        $this->db->bind(':eid', $enrollmentID, PDO::PARAM_INT);

        try {
            if ($this->db->execute()) {
                return true;
            } else {
                error_log("Failed to update status for enrollment ID {$enrollmentID}");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database Error during status update for enrollment ID {$enrollmentID}: " . $e->getMessage());
            return false;
        }
    }
}