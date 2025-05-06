<?php
// controllers/Courses.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/session_helper.php'; // For redirect() or other helpers if needed

class Courses {
    private $courseModel;

    public function __construct() {
        $this->courseModel = new Course();
        $this->checkAuth(); // Check authentication for all actions
    }

    /**
     * Checks if the user is logged in and is an admin (1) or manager (2).
     * Redirects to the manager login page if not authorized.
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) ||
            ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2))
        {
            $_SESSION['error'] = 'You must be logged in as an administrator or manager to access this page!';
            header('Location: Auth.php?action=login');
            exit();
        }
    }

    /**
     * Displays the main course management page (list of courses and create/edit form).
     * Handles both the default view and showing the form pre-filled for editing.
     */
    public function index(int $editId = null) {
        $courses = $this->courseModel->getAllCourses();
        $editCourse = null;

        if ($editId !== null) {
            $editCourse = $this->courseModel->getCourseById($editId);
            if (!$editCourse) {
                 $_SESSION['error'] = 'Course not found for editing.';
                 header('Location: Courses.php?action=index');
                 exit();
            }
        }

        // Load the view, passing the necessary data ($courses, $editCourse)
        require_once __DIR__ . '/../views/manager/course.php';
    }

    /**
     * Handles the submission of the "Create Course" form.
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Filter and prepare data from POST request
            $data = [
                'course_code' => trim(filter_input(INPUT_POST, 'course_code', FILTER_SANITIZE_SPECIAL_CHARS)),
                'course_name' => trim(filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_SPECIAL_CHARS)),
                'teacher'     => trim(filter_input(INPUT_POST, 'teacher', FILTER_SANITIZE_SPECIAL_CHARS)),
                'schedule'    => trim(filter_input(INPUT_POST, 'schedule', FILTER_SANITIZE_SPECIAL_CHARS)),
                'description' => trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null // Keep trim, handle empty as null
            ];

            // --- Validation ---
            $errors = [];
            if (empty($data['course_code'])) { $errors[] = 'Course code is required.'; }
            if (empty($data['course_name'])) { $errors[] = 'Course name is required.'; }
            if (empty($data['teacher']))     { $errors[] = 'Teacher is required.'; }
            if (empty($data['schedule']))    { $errors[] = 'Schedule is required.'; }
            // Add validation for description if needed (e.g., max length)
            // Example: if (isset($data['description']) && mb_strlen($data['description']) > 1000) { $errors[] = 'Description cannot exceed 1000 characters.'; }

            // *** LOGIC FIX: Validate FIRST ***
            if (!empty($errors)) {
                 $_SESSION['error'] = implode('<br>', $errors);
                 $_SESSION['form_data'] = $data; // Keep data for repopulation
            } else {
                 // *** LOGIC FIX: Attempt to create the course ONLY if validation passes ***
                 // The model method createCourse already checks for duplicate code
                if ($this->courseModel->createCourse($data)) {
                    $_SESSION['success'] = 'Course created successfully!';
                    // No need to unset form_data here, as it wasn't set on success path
                } else {
                    // Failure could be duplicate code or other DB error from the *single* create attempt
                    $_SESSION['error'] = 'Error: Failed to create course. The Course Code might already exist, or another database error occurred.';
                    $_SESSION['form_data'] = $data; // Keep data for repopulation on failure
                }
            }
        } else {
             $_SESSION['error'] = 'Invalid request method for creating course.';
        }
        // Redirect back to the main course page (index action) regardless of outcome
        header('Location: Courses.php?action=index');
        exit();
    }

    /**
     * Handles the submission of the "Update Course" form.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $courseId = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);

             if (!$courseId) {
                 $_SESSION['error'] = 'Invalid course ID for update.';
                 header('Location: Courses.php?action=index');
                 exit();
             }

            // Filter and prepare data from POST request, including description
            $data = [
                'course_id'   => $courseId,
                'course_code' => trim(filter_input(INPUT_POST, 'course_code', FILTER_SANITIZE_SPECIAL_CHARS)),
                'course_name' => trim(filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_SPECIAL_CHARS)),
                'teacher'     => trim(filter_input(INPUT_POST, 'teacher', FILTER_SANITIZE_SPECIAL_CHARS)),
                'schedule'    => trim(filter_input(INPUT_POST, 'schedule', FILTER_SANITIZE_SPECIAL_CHARS)),
                'description' => trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null // Get description, allow null
            ];

            // --- Validation ---
            $errors = [];
             if (empty($data['course_code'])) { $errors[] = 'Course code is required.'; }
             if (empty($data['course_name'])) { $errors[] = 'Course name is required.'; }
             if (empty($data['teacher']))     { $errors[] = 'Teacher is required.'; }
             if (empty($data['schedule']))    { $errors[] = 'Schedule is required.'; }
             // Add validation for description if needed

             // Optional: Check if the new course code conflicts with another existing course (excluding itself)
             // This requires a dedicated model method, e.g., if ($this->courseModel->courseCodeExists($data['course_code'], $data['course_id'])) { $errors[] = 'Course code already exists for another course.'; }


            // *** LOGIC FIX: Validate FIRST ***
            if (!empty($errors)) {
                  $_SESSION['error'] = implode('<br>', $errors);
                 // Redirect back to the edit form for the SAME course on validation error
                 header('Location: Courses.php?action=edit&id=' . $data['course_id']);
                 exit();
            } else {
                // *** LOGIC FIX: Attempt to update the course ONLY if validation passes ***
                if ($this->courseModel->updateCourse($data)) {
                    $_SESSION['success'] = 'The course was updated successfully!';
                    // Redirect to index after successful update
                    header('Location: Courses.php?action=index');
                    exit();
                } else {
                    $_SESSION['error'] = 'Error updating course! Please check the details and try again. The Course Code might be duplicated, or no changes were made.';
                    // Redirect back to the edit form on failure
                    header('Location: Courses.php?action=edit&id=' . $data['course_id']);
                    exit();
                }
            }
        } else {
              $_SESSION['error'] = 'Invalid request method for updating course.';
               header('Location: Courses.php?action=index');
               exit();
        }
    }


    /**
     * Handles the submission of the "Delete Course" action.
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
             $courseId = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);

             if (!$courseId) {
                 $_SESSION['error'] = 'Invalid course ID for deletion.';
             } else {
                 if ($this->courseModel->deleteCourse($courseId)) {
                     $_SESSION['success'] = 'Course deleted successfully!';
                 } else {
                     // Provide a more informative error if possible (e.g., from model)
                     $_SESSION['error'] = 'Error deleting course! It might already be deleted or associated with other data (e.g., enrollments).';
                 }
             }
        } else {
            $_SESSION['error'] = 'Invalid request for deleting course.';
        }
        // Redirect back to the main course page
        header('Location: Courses.php?action=index');
        exit();
    }

    /**
     * Prepares data for the edit view by calling index() with the ID.
     */
    public function edit(int $id = null) {
         if ($id === null) {
            // If called via GET without ID, handle gracefully
            if (isset($_GET['id'])) {
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                if (!$id) {
                     $_SESSION['error'] = 'Invalid Course ID for edit action.';
                     header('Location: Courses.php?action=index');
                     exit();
                }
            } else {
                 $_SESSION['error'] = 'Course ID missing for edit action.';
                 header('Location: Courses.php?action=index');
                 exit();
            }
         }
        // Call index() to load the main view, passing the ID to fetch the course for editing
        $this->index($id);
    }
}

// --- Routing Logic ---
$action = $_GET['action'] ?? 'index'; // Default action is 'index' if not specified

// Instantiate controller AFTER defining the class
$controller = new Courses(); // Constructor runs checkAuth()

// --- Handle Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Action for POST requests should be determined by a hidden input field
    $postAction = $_POST['action'] ?? null;

    switch ($postAction) {
        case 'create':
            $controller->create();
            break;
        case 'update':
            $controller->update();
            break;
        case 'delete':
             $controller->delete();
             break;
        default:
            // Log this situation?
             error_log("Invalid or missing POST action specified. POST data: " . print_r($_POST, true));
            $_SESSION['error'] = 'Invalid form submission.';
            header('Location: Courses.php?action=index');
            exit();
    }

} else { // Handle GET requests
    switch ($action) {
        case 'edit':
             // Validation of ID is now handled inside the edit() method
            $controller->edit(); // Pass control fully to edit method
            break;
        case 'index':
             // No 'id' parameter needed for the default index view (shows create form)
             $controller->index();
             break;
        default:
            // Handle unknown GET actions
            $_SESSION['error'] = 'Unknown action requested.';
             header('Location: Courses.php?action=index');
             exit();
    }
}

