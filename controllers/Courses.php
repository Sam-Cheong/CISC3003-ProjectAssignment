<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/session_helper.php';

class Courses
{
    private $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        // $this->checkAuth(); // Ensure only managers can access
    }

    /**
     * Check if the user is logged in and is a manager.
     */
    // private function checkAuth()
    // {
    //     if (!isset($_SESSION['userID']) || $_SESSION['roleID'] != 2) {
    //         flash('error', 'Unauthorized access.', 'form-message form-message-red');
    //         redirect('../../index.php');
    //     }
    // }

    /**
     * Display the course management page.
     */
    public function index()
    {
        $courses = $this->courseModel->getAllCourses();
        require_once __DIR__ . '/../views/manager/index.php'; 
    }

    /**
     * Handle course creation.
     */
    public function create()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $data = [
            'course_code' => trim($_POST['course_code'] ?? ''),
            'course_name' => trim($_POST['course_name'] ?? ''),
            'teacher'     => trim($_POST['teacher'] ?? ''),
            'schedule'    => trim($_POST['schedule'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        // Validate input
        if (empty($data['course_code']) || empty($data['course_name']) || empty($data['teacher']) || empty($data['schedule'])) {
            flash('error', 'All fields are required.', 'form-message form-message-red');
            redirect('../views/manager/index.php');
        }

        // Create course
        if ($this->courseModel->createCourse($data)) {
            flash('success', 'Course created successfully!', 'form-message form-message-green');
        } else {
            flash('error', 'Failed to create course. Course code might already exist.', 'form-message form-message-red');
        }

        redirect('../views/manager/index.php');
    }

    /**
     * Handle course update.
     */
    public function update()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $data = [
            'course_id'   => intval($_POST['course_id'] ?? 0),
            'course_code' => trim($_POST['course_code'] ?? ''),
            'course_name' => trim($_POST['course_name'] ?? ''),
            'teacher'     => trim($_POST['teacher'] ?? ''),
            'schedule'    => trim($_POST['schedule'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        // Validate input
        if (empty($data['course_id']) || empty($data['course_code']) || empty($data['course_name']) || empty($data['teacher']) || empty($data['schedule'])) {
            flash('error', 'All fields are required.', 'form-message form-message-red');
            redirect('../views/manager/manage.php');
        }

        // Update course
        if ($this->courseModel->updateCourse($data)) {
            flash('success', 'Course updated successfully!', 'form-message form-message-green');
        } else {
            flash('error', 'Failed to update course.', 'form-message form-message-red');
        }

        redirect('../views/manager/index.php');
    }

    /**
     * Handle course deletion.
     */
    public function delete()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $courseId = intval($_POST['course_id'] ?? 0);

        if (!$courseId) {
            flash('error', 'Invalid course ID.', 'form-message form-message-red');
            redirect('../views/manager/manage.php');
        }

        if ($this->courseModel->deleteCourse($courseId)) {
            flash('success', 'Course deleted successfully!', 'form-message form-message-green');
        } else {
            flash('error', 'Failed to delete course.', 'form-message form-message-red');
        }

        redirect('../views/manager/index.php');
    }

    public function showAllCourses()
    {
        $courses = $this->courseModel->getAllCourses();
        return $courses;
    }

    /**
     * 获取当前用户的课程（报名的课程详情）。
     *
     * @param int $userID 当前用户ID
     * @return array 返回包含课程详情的数组
     */
    public function showSelfCourses(int $userID): array {
        $courses = $this->courseModel->getCoursesByUser($userID);
        return $courses;
    }
}

// Initialize the controller
$init = new Courses();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action'] ?? '') {
        case 'create':
            $init->create();
            break;
        case 'update':
            $init->update();
            break;
        case 'delete':
            $init->delete();
            break;
        default:
            redirect('../views/manager/index.php');
    } 
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['action']) && $_GET['action'] === 'showSelfCourses') {
            // 获取当前用户ID
            $userID = $_SESSION['userID'] ?? 0;
            // 调用 showSelfCourses() 方法
            $courses = $init->showSelfCourses($userID);
            // 将结果传递到一个视图页面展示
            require_once __DIR__ . '/../views/user/profile.php'; 
            exit();
        }elseif (isset($_GET['action']) && $_GET['action'] === 'showSelfCourses') {
            // 获取当前用户ID
            $userID = $_SESSION['userID'] ?? 0;
            // 调用 showSelfCourses() 方法
            $courses = $init->showSelfCourses($userID);
            // 将结果传递到一个视图页面展示
            require_once __DIR__ . '/../views/user/profile.php'; 
            exit();
        }
    }
    else {
        $init->index();
    }
