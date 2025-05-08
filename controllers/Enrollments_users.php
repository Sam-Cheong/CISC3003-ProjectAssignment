<?php
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';
const permiteeID = 3;

//僅允許已登入使用者操作
if (!isset($_SESSION['userID'])) {
    redirect('/CISC3003-ProjectAssignment/views/login.php');
}elseif(!$_SESSION['roleID']===permiteeID){
    flash('Access Denied', 'You do not have permission to access this page.');
    // redirect('/CISC3003-ProjectAssignment/views/manager/login.php');
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
    public function enroll() {
        $enrollmentdata = $this->combineEnrollmentData();
        if ($this->isComplete($enrollmentdata)) {
            if ($this->enrollmentModel->createEnrollment($enrollmentdata)) {
                flash('Enroll', 'Enrolled successfully.');
                redirect('/CISC3003-ProjectAssignment/views/profile.php');
            } else {
                flash('Enroll', 'Course already enrolled.');
                redirect('/CISC3003-ProjectAssignment/views/courses.php');
            }
        } else {
            flash('Enroll', 'Enrollment failed.');
            redirect('/CISC3003-ProjectAssignment/views/courses.php');
        }
    }

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

    public function showSelfEnrollments() {
        $enrollments = $this->enrollmentModel->getEnrollmentsByUser($_SESSION['userID']);
        return $enrollments;
    }
}

$enrollments = new Enrollments();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'enroll':
        $enrollments->enroll();
        break;
    case 'remove':
        $enrollments->remove();
        break;
    default:
        $enrollments->showSelfEnrollments();
        break;
}