<?php
require_once __DIR__ . '/../config/Database.php';

class Enrollment {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Enroll a user in a course.
     *
     * @param int $userID   The user's ID.
     * @param int $courseID The course's ID.
     * @param string $status Enrollment status, default is 'active'.
     * @return bool Returns true on success, false if already enrolled or an error occurs.
     */
    public function enrollUser(int $userID, int $courseID, string $status = 'active'): bool {
        // Check if the user is already enrolled in the course.
        $this->db->query('SELECT enrollment_id FROM enrollments WHERE userID = :uid AND course_id = :cid');
        $this->db->bind(':uid', $userID);
        $this->db->bind(':cid', $courseID);
        $existing = $this->db->single();
        if ($existing) {
            // Already enrolled.
            return false;
        }
        
        // Insert a new enrollment.
        $sql = 'INSERT INTO enrollments (userID, course_id, status) VALUES (:uid, :cid, :status)';
        $this->db->query($sql);
        $this->db->bind(':uid', $userID);
        $this->db->bind(':cid', $courseID);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    /**
     * Remove an enrollment by enrollment ID.
     *
     * @param int $enrollmentID The enrollment record identifier.
     * @return bool Returns true on success, false on failure.
     */
    public function removeEnrollment(int $enrollmentID): bool {
        $this->db->query('DELETE FROM enrollments WHERE enrollment_id = :eid');
        $this->db->bind(':eid', $enrollmentID);
        return $this->db->execute();
    }

    /**
     * Get all enrollments (with course details) for a specific user.
     *
     * @param int $userID
     * @return array Returns an array of enrollment records.
     */
    public function getEnrollmentsByUser(int $userID): array {
        $this->db->query('
            SELECT e.*, c.course_code, c.course_name, c.teacher, c.schedule
            FROM enrollments e 
            JOIN courses c ON e.course_id = c.course_id
            WHERE e.userID = :uid
            ORDER BY e.enrollment_date DESC
        ');
        $this->db->bind(':uid', $userID);
        return $this->db->resultSet();
    }

    /**
     * Check if a user is already enrolled in a specific course.
     *
     * @param int $userID
     * @param int $courseID
     * @return bool Returns true if enrolled, false otherwise.
     */
    public function isUserEnrolled(int $userID, int $courseID): bool {
        $this->db->query('SELECT enrollment_id FROM enrollments WHERE userID = :uid AND course_id = :cid');
        $this->db->bind(':uid', $userID);
        $this->db->bind(':cid', $courseID);
        $result = $this->db->single();
        return $result ? true : false;
    }
}