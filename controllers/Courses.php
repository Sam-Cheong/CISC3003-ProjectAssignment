<?php
session_start();
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session_helper.php';

class Courses
{
    private $courseModel;
    private $userModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->userModel = new User();
        $this->checkAuth();// Fix to session-helper.php
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
            $_SESSION['error'] = 'You must be logged in as an administrator or manager to access this page!';
            header('Location: ../views/manager/login.php');
            exit();
        }
    }

    public function index()
    {
        $courses = $this->courseModel->getAllCourses();
        require_once __DIR__ . '/../views/manager/course.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'course_code' => trim($_POST['course_code']),
                'course_name' => trim($_POST['course_name']),
                'teacher'     => trim($_POST['teacher']),
                'schedule'    => trim($_POST['schedule'])
            ];

            if ($this->courseModel->createCourse($data)) {
                $_SESSION['success'] = 'Course created successfully!';
            } else {
                $_SESSION['error'] = 'Error: Course Code already exists!';
            }
        }
        header('Location: course.php');
        exit();
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'course_id'   => $_POST['course_id'],
                'course_code' => trim($_POST['course_code']),
                'course_name' => trim($_POST['course_name']),
                'teacher'     => trim($_POST['teacher']),
                'schedule'    => trim($_POST['schedule'])
            ];

            if ($this->courseModel->updateCourse($data)) {
                $_SESSION['success'] = 'The course was updated successfully!';
            } else {
                $_SESSION['error'] = 'Error updating course!';
            }
        }
        header('Location: course.php');
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
            if ($this->courseModel->deleteCourse($_POST['course_id'])) {
                $_SESSION['success'] = 'Course deleted successfully!';
            } else {
                $_SESSION['error'] = 'Error deleting course!';
            }
        }
        header('Location: course.php');
        exit();
    }

    public function edit($id)
    {
        $course = $this->courseModel->getCourseById($id);
        $courses = $this->courseModel->getAllCourses();
        require_once __DIR__ . '/../views/manager/course.php';
    }
}

//Fix get to post method
$action = $_GET['action'] ?? 'index';
$controller = new Courses();

if ($action === 'create') {
    $controller->create();
} elseif ($action === 'update') {
    $controller->update();
} elseif ($action === 'delete') {
    $controller->delete();
} elseif ($action === 'edit' && isset($_GET['id'])) {
    $controller->edit($_GET['id']);
} else {
    $controller->index();
}

