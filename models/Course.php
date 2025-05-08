<?php
// models/Course.php

// Ensure the path is correct and the class name matches the file name
require_once __DIR__ . '/../config/Database.php';

class Course {
    // Assume Database class provides query(), bind(), execute(), resultSet(), single(), rowCount() methods
    private $db;

    public function __construct() {
        // Instantiate the Database helper class
        $this->db = new Database();
    }

    /**
     * Creates a new course, including description.
     * Returns true on success, false on failure (e.g., duplicate code).
     */
    public function createCourse($data): bool {
        // Check for existing course code first
        $this->db->query('SELECT `course_id` FROM `courses` WHERE `course_code` = :code');
        $this->db->bind(':code', $data['course_code']);
        $existing = $this->db->single(); // Use single() which returns object or null

        if ($existing) {
            error_log("Attempted to create course with existing code: " . $data['course_code']);
            return false; // Indicate failure due to duplicate
        }

        // Proceed with insertion, including description
        $this->db->query('
            INSERT INTO `courses`
            (`course_code`, `course_name`, `teacher`, `schedule`, `description`) -- Added description column
            VALUES (:code, :name, :teacher, :schedule, :description)        -- Added :description placeholder
        ');

        // Bind values from the data array
        $this->db->bind(':code', $data['course_code']);
        $this->db->bind(':name', $data['course_name']);
        $this->db->bind(':teacher', $data['teacher']);
        $this->db->bind(':schedule', $data['schedule']);
        // Bind description, allowing null if not provided
        $this->db->bind(':description', $data['description'] ?? null, $data['description'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR); // Added binding for description

        try {
            if ($this->db->execute()) {
                 error_log("Course created successfully: " . $data['course_code']);
                 return true;
            } else {
                 error_log("Course creation failed for code: " . $data['course_code'] . " - DB execute returned false.");
                 return false;
            }
        } catch (PDOException $e) {
            error_log("Database Error during course creation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all courses, including description, ordered by creation date descending.
     * Returns an array of course objects.
     */
    public function getAllCourses(): array {
        $this->db->query('
            SELECT course_id, course_code, course_name, teacher, schedule, description, created_at -- Added description
            FROM courses
            ORDER BY created_at DESC
        ');
        // Assuming resultSet returns an array of objects or an empty array
        return $this->db->getResults() ?: [];
    }

    public function getCoursesByUser(int $userID): array {
        $this->db->query('
            SELECT c.course_id, c.course_code, c.course_name, c.teacher, c.schedule, c.description, c.created_at -- Added description
            FROM courses c
            JOIN enrollments e ON c.course_id = e.course_id
            WHERE e.userID = :uid
            ORDER BY c.created_at DESC
        ');
        $this->db->bind(':uid', $userID);
        return $this->db->getResults() ?: [];
    }

    // 在合适位置（比如 getAllCourses 方法后面）添加：
    public function searchCourses(string $keyword): array {
        $this->db->query('
            SELECT course_id, course_code, course_name, teacher, schedule, description, created_at
            FROM courses
            WHERE course_name LIKE :keyword 
                OR course_code LIKE :keyword 
                OR teacher LIKE :keyword
            ORDER BY created_at DESC
        ');
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->getResults() ?: [];
    }

    /**
     * Retrieves a single course by its ID, including description.
     * Returns a course object or null if not found.
     */
    public function getCourseById(int $id): ?object {
        // SELECT * will include the description column automatically
        $this->db->query('SELECT * FROM courses WHERE course_id = :id');
        $this->db->bind(':id', $id, PDO::PARAM_INT); // Explicitly bind as INT
        return $this->db->single(); // Returns object or null
    }

    /**
     * Updates an existing course, including description.
     * Returns true on success, false on failure.
     */
    public function updateCourse($data): bool {
        // Optional: Add a check here to prevent updating to an existing course_code

        $this->db->query('
            UPDATE courses
            SET course_code = :code,
                course_name = :name,
                teacher = :teacher,
                schedule = :schedule,
                description = :description -- Added description update
            WHERE course_id = :id
        ');

        // Bind values from the data array
        $this->db->bind(':code', $data['course_code']);
        $this->db->bind(':name', $data['course_name']);
        $this->db->bind(':teacher', $data['teacher']);
        $this->db->bind(':schedule', $data['schedule']);
         // Bind description, allowing null
        $this->db->bind(':description', $data['description'] ?? null, $data['description'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR); // Added binding for description
        $this->db->bind(':id', $data['course_id'], PDO::PARAM_INT); // Bind ID as INT

        try {
             if ($this->db->execute()) {
                 error_log("Course updated successfully: ID " . $data['course_id']);
                 // Check if any rows were actually affected if needed
                 // return $this->db->rowCount() > 0;
                 return true; // Assume success if execute doesn't throw error
             } else {
                  error_log("Course update failed for ID: " . $data['course_id'] . " - DB execute returned false.");
                  return false;
             }
        } catch (PDOException $e) {
            error_log("Database Error during course update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a course by its ID.
     * Returns true on success, false on failure.
     */
    public function deleteCourse(int $id): bool {
        $this->db->query('DELETE FROM courses WHERE course_id = :id');
        $this->db->bind(':id', $id, PDO::PARAM_INT); // Bind ID as INT

        try {
             if ($this->db->execute()) {
                 // Check if a row was actually deleted for more accuracy
                 if ($this->db->rowCount() > 0) {
                     error_log("Course deleted successfully: ID " . $id);
                     return true;
                 } else {
                     error_log("Course deletion attempt for ID: " . $id . " - No rows affected (already deleted?).");
                     return false; // Indicate nothing was deleted
                 }
             } else {
                 error_log("Course deletion failed for ID: " . $id . " - DB execute returned false.");
                 return false;
             }
        } catch (PDOException $e) {
            error_log("Database Error during course deletion: " . $e->getMessage());
            // Check for foreign key constraints if needed
            // if (strpos($e->getMessage(), 'FOREIGN KEY constraint fails') !== false) { ... }
            return false;
        }
    }
}
