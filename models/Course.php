<?php
// models/Course.php
require_once __DIR__ . '/../config/Database.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }


    public function createCourse($data) {

        $this->db->query('SELECT `course_id` FROM `courses` WHERE `course_code` = :code');
        $this->db->bind(':code', $data['course_code']);
        $existing = $this->db->single();
        
        if ($existing) {
            $_SESSION['error'] = 'Course code already exists!';
            return false;
        }
        
        $this->db->query('
            INSERT INTO `courses` 
            (`course_code`, `course_name`, `teacher`, `schedule`)
            VALUES (:code, :name, :teacher, :schedule)
        ');
        
        $this->db->bind(':code', $data['course_code']);
        $this->db->bind(':name', $data['course_name']);
        $this->db->bind(':teacher', $data['teacher']);
        $this->db->bind(':schedule', $data['schedule']);
        
        try {
            $this->db->execute();
            error_log("Course created successfully: " . $data['course_code']);
            return true;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }



    }
    
    public function getAllCourses() {
        $this->db->query('
            SELECT course_id, course_code, course_name, teacher, schedule, created_at 
            FROM courses 
            ORDER BY created_at DESC
        ');
        return $this->db->resultSet();
    }

    public function getCourseById($id) {
        $this->db->query('SELECT * FROM courses WHERE course_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateCourse($data) {
        $this->db->query('
            UPDATE courses 
            SET course_code = :code, 
                course_name = :name, 
                teacher = :teacher, 
                schedule = :schedule
            WHERE course_id = :id
        ');
        
        $this->db->bind(':code', $data['course_code']);
        $this->db->bind(':name', $data['course_name']);
        $this->db->bind(':teacher', $data['teacher']);
        $this->db->bind(':schedule', $data['schedule']);
        $this->db->bind(':id', $data['course_id']);
        
        try {
            $this->db->execute();
            error_log("Course updated successfully: " . $data['course_code']);
            return true;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCourse($id) {
        $this->db->query('DELETE FROM courses WHERE course_id = :id');
        $this->db->bind(':id', $id);
        
        try {
            $this->db->execute();
            error_log("Course deleted successfully: ID " . $id);
            return true;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

}
