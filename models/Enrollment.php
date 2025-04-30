<?php
require_once __DIR__ . '/../config/Database.php';

class Enrollment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function __destruct() {
        $this->db = null;
    }

    /*  數據格式
        $enrollmentdata = [
        'enrollmentID' => isset($_POST['enrollmentID']) ? trim($_POST['enrollmentID']) : null,
        'userID'   => trim($_POST['userID']),
        'courseID' => trim($_POST['courseID'])
        'status' => ENUM('pending', 'confirmed', 'active', 'finished')
        'createdAt' => DATETIME NOT NULL CURRENT_TIMESTAMP
        ];

        (array) enrollmentdata: 以array存儲的純數據，通常是尚未寫入數據庫的申請
        (stdClass) enrollment: 以stdClass存儲的記錄，通常是從數據庫讀取的單一記錄
        (array) enrollments: 以array存儲的記錄組合，通常是從數據庫讀取的一組記錄
    **/

    // Check if the enrollment already exists
    // Or convert (array) enrollmentdata to (stdClass) enrollment
    public function exist(array $enrollmentdata){
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
        if ($this->exist($enrollmentdata)) {
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
}