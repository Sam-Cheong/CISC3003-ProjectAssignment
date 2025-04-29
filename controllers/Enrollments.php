<?php
// session_start();
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';

// 僅允許已登入使用者操作
if (!isset($_SESSION['userID'])) {
    redirect('/CISC3003-ProjectAssignment/views/login.php');
}

$enrollmentModel = new Enrollment();

// 基本的路由機制：根據 GET 參數 action 處理不同請求
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'enroll':
        // 處理選課（POST 請求）
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseID = intval($_POST['course_id']);
            $userID   = $_SESSION['userID'];
            $result = $enrollmentModel->enrollUser($userID, $courseID);
            
            if ($result) {
                $_SESSION['message'] = 'Enrolled successfully.';
                redirect('/CISC3003-ProjectAssignment/views/profile.php');
            } else {
                $_SESSION['message'] = 'Enrollment failed or already enrolled.';
                redirect('/CISC3003-ProjectAssignment/views/courses.php');
            }
        }
        break;
        
    case 'remove':
        // 處理取消選課（GET 請求，參數 enrollment_id）
        if (isset($_GET['enrollment_id'])) {
            $enrollmentID = intval($_GET['enrollment_id']);
            $result = $enrollmentModel->removeEnrollment($enrollmentID);
            
            if ($result) {
                $_SESSION['message'] = 'Enrollment removed successfully.';
            } else {
                $_SESSION['message'] = 'Failed to remove enrollment.';
            }
            redirect('/CISC3003-ProjectAssignment/views/profile.php');
        }
        break;
        
    default:
        // 預設動作，顯示目前使用者的所有選課
        $userID = $_SESSION['userID'];
        $userEnrollments = $enrollmentModel->getEnrollmentsByUser($userID);
        // require_once __DIR__ . '/../views/enrollments/list.php';
        break;
}
?>