<?php
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';
const permiteeID = 2;

//僅允許已登入使用者操作
if (!isset($_SESSION['userID'])) {
    redirect('/CISC3003-ProjectAssignment/views/login.php');
} elseif ($_SESSION['roleID'] !== 2) {
    flash('Access Denied', 'You do not have permission to access this page.');
    exit();
}

/* 
    $enrollmentdata = [
        'userID'   => trim($_POST['userID']),
        'course_code' => trim($_POST['course_code'])
        'status' => ENUM('pending', 'confirmed', 'active', 'finished')
    ];

    (array) enrollmentdata: 以array存儲的純數據，通常是尚未寫入數據庫的申請
    (stdClass) enrollment: 以stdClass存儲的記錄，通常是從數據庫讀取的單一記錄
    (array) enrollments: 以array存儲的記錄組合，通常是從數據庫讀取的一組記錄
**/

class Enrollments { 
    private $enrollmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new Enrollment();
    }

    private function isComplete(array $enrollmentdata): bool {
        return (!empty($enrollmentdata['userID']) && !empty($enrollmentdata['course_code']));
    }

    private function combineEnrollmentData(){
        //需確保function不會被call第二次才能使用這個method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $enrollmentdata = [
                'userID' => $_SESSION['user_id'],
                'course_code' => trim($_POST['course_code']),
            ];
            return $enrollmentdata;
        }else{
            $enrollmentdata = null;
            return null;}
    }

    //Public Method
    // public function enroll() {
    //     $enrollmentdata = $this->combineEnrollmentData();
    //     if ($this->isComplete($enrollmentdata)) {
    //         if ($this->enrollmentModel->createEnrollment($enrollmentdata)) {
    //             flash('Enroll', 'Enrolled successfully.');
    //             redirect('/CISC3003-ProjectAssignment/views/profile.php');
    //         } else {
    //             flash('Enroll', 'Course already enrolled.');
    //             redirect('/CISC3003-ProjectAssignment/views/courses.php');
    //         }
    //     } else {
    //         flash('Enroll', 'Enrollment failed.');
    //         redirect('/CISC3003-ProjectAssignment/views/courses.php');
    //     }
    // }

    public function remove(): void {
        $enrollmentdata = $this->combineEnrollmentData();
        if ($enrollmentdata === null || !$this->isComplete($enrollmentdata)) {
            flash('Remove', 'Removal failed: Missing required data.');
            redirect('/CISC3003-ProjectAssignment/views/profile.php');
        }

        $enrollment = $this->enrollmentModel->isExist($enrollmentdata);
        if ($enrollment) {
            if ($this->enrollmentModel->removeEnrollment($enrollment)) {
                flash('Remove', 'Removed successfully.');
                redirect('/CISC3003-ProjectAssignment/views/profile.php');
            } else {
                flash('Remove', 'Removal failed.');
                redirect('/CISC3003-ProjectAssignment/views/profile.php');
            }
        } else {
            flash('Remove', 'Enrollment does not exist.');
            redirect('/CISC3003-ProjectAssignment/views/profile.php');
        }
    }

    public function showAllEnrollments() {
        $enrollments = $this->enrollmentModel->getEnrollments();
        return $enrollments;
    }

    public function updateStatusFlow($currentStatus, $newStatus): void {
        // $allowedStatuses = ['pending', 'active', 'finished'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $enrollmentID = intval($_POST['enrollmentID'] ?? 0);
            $currentStatus = trim($_POST['currentStatus'] ?? '');
            
            if ($enrollmentID <= 0 || empty($currentStatus)) {
                flash('Update', 'Missing enrollment data.');
                redirect('/CISC3003-ProjectAssignment/views/manager/enrollments.php');
            }
            
            // 根据当前状态决定新的状态
            if ($currentStatus === 'pending' && $newStatus === 'finished') {
                flash('Update', 'Cannot update status from the current state.');
                redirect('/CISC3003-ProjectAssignment/views/manager/enrollments.php');
            }
            
            if ($this->enrollmentModel->updateStatus($enrollmentID, $newStatus)) {
                flash('Update', "Enrollment status updated to {$newStatus}.");
            } else {
                flash('Update', 'Status update failed.');
            }
            redirect('/CISC3003-ProjectAssignment/views/manager/enrollments.php');
        } else {
            flash('Error', 'Unauthorized request method.');
            redirect('/CISC3003-ProjectAssignment/views/manager/enrollments.php');
        }
    }
}

$enrollments = new Enrollments();
$action = $_GET['action'] ?? '';

switch ($action) {
    // case 'enroll':
    //     $enrollments->enroll();
    //     break;
    case 'remove':
        $enrollments->remove();
        break;
    default:
        $enrollments->showAllEnrollments();
        break;
}