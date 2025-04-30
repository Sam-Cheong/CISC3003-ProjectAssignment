<?php
// session_start();
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';



// 僅允許已登入使用者操作
// if (!isset($_SESSION['userID'])) {
//     redirect('/CISC3003-ProjectAssignment/views/login.php');
// }

/* $enrollmentdata = [
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

    private function isComplete(array $enrollmentdata):bool{
        if ($enrollmentdata['userID'] != null && $enrollmentdata['course_code'] != null){
            return true;
        }
        return false;
    }

    private function combine2enrollmentdata(){
        //需確保function不會被call第二次才能使用這個method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $enrollmentdata = [
                'userID' => $_SESSION['user_id'],
                'course_code' => trim($_POST['course_code']),
            ];
            return $enrollmentdata;
        }else{return false;}
    }

    public function enroll() {
        $enrollmentdata = $this->combine2enrollmentdata();
        if ($this->isComplete($enrollmentdata)) {
            if ($this->enrollmentModel->createEnrollment($enrollmentdata)) {
                flash('Enroll', 'Enrolled successfully.');
                redirect('/CISC3003-ProjectAssignment/views/profile.php');
            } else {
                flash('Enroll', 'Enrollment already enrolled.');
                redirect('/CISC3003-ProjectAssignment/views/courses.php');
            }
        } else {
            flash('Enroll', 'Enrollment failed.');
            redirect('/CISC3003-ProjectAssignment/views/courses.php');
        }
    }

    public function remove() {
        $enrollmentdata = $this->combine2enrollmentdata();
        if ($this->isComplete($enrollmentdata)) {
            if ($this->enrollmentModel->createEnrollment($enrollmentdata)) {
                flash('Remove', 'Removed successfully.');
                redirect('/CISC3003-ProjectAssignment/views/profile.php');
            } else {
                flash('Remove', 'Enrollment does not exist.');
                redirect('/CISC3003-ProjectAssignment/views/courses.php');
            }
        } else {
            flash('Remove', 'Removed failed.');
            redirect('/CISC3003-ProjectAssignment/views/courses.php');
        }
    }

    public function getSelfEnrollments() {
        $enrollments = $this->enrollmentModel->getEnrollmentsByUser($_SESSION['userID']);
    }
}

// 基本的路由機制：根據 GET 參數 action 處理不同請求
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'enroll':
        $this->enroll();
        break;
    case 'remove':
        $this->remove();
        break;
    default:
        $this->getSelfEnrollments();
        break;
}
?>