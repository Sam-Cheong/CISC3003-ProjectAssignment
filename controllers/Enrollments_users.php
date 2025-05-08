<?php
// controllers/EnrollmentsUsers.php

require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';

// ensure only logged-in customers (roleID = 3) get through
verifyPermitee(3);

class EnrollmentsUsers
{
    private Enrollment $enrollmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new Enrollment();
    }

    /**
     * Display the list of this user’s enrollments.
     */
    public function index(): void
    {
        $userID      = (int)($_SESSION['userID'] ?? 0);
        $enrollments = $this->enrollmentModel->getEnrollmentsByUser($userID);
        require_once __DIR__ . '/../views/user/enrollments.php';
    }

    /**
     * Process an enroll-in-course POST.
     */
    public function enroll(): void
    {
        $userID   = (int)($_SESSION['userID'] ?? 0);
        $courseID = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);

        if (!$courseID) {
            flash('error', 'Invalid course selection.');
            redirect("/CISC3003-ProjectAssignment/views/course/detail.php?course_id={$courseID}");
        }

        // createEnrollment should return false if already enrolled
        $ok = $this->enrollmentModel->createEnrollment([
            'userID'    => $userID,
            'course_id' => $courseID
        ]);

        if ($ok) {
            flash('success', 'You have been enrolled successfully.', "form-message form-message-green");
            redirect("/CISC3003-ProjectAssignment/views/user/profile.php");
        } else {
            flash('error', 'You are already enrolled in this course.');
            redirect("/CISC3003-ProjectAssignment/views/course/detail.php" . "?course_id={$courseID}");
        }        
    }

    public function cancel(): void
    {
        // validate input
        $eid = filter_input(INPUT_POST, 'enrollment_id', FILTER_VALIDATE_INT);
        if (!$eid) {
            flash('cancel', 'Invalid enrollment ID.', 'form-message form-message-red');
            redirect('/CISC3003-ProjectAssignment/views/user/profile.php');
        }

        // pass an object with enrollmentID to the model
        $enrollment       = new stdClass();
        $enrollment->enrollmentID = $eid;
        $ok = $this->enrollmentModel->removeEnrollment($enrollment);

        if ($ok) {
            flash('cancel', 'Enrollment cancelled.', 'form-message form-message-green');
        } else {
            flash('cancel', 'Failed to cancel enrollment.', 'form-message form-message-red');
        }

        redirect('/CISC3003-ProjectAssignment/views/user/profile.php');
    }
}

$controller = new EnrollmentsUsers();

// route POST→enroll(), otherwise GET→index()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action'] ?? '') {
        case 'enroll':
            $controller->enroll();
            break;
        case 'cancel':
            $controller->cancel();
            break;
        default:
            flash('error', 'Invalid action.', 'form-message form-message-red');
            redirect('/CISC3003-ProjectAssignment/views/course/detail.php?course_id=' . $_POST['course_id']);
            break;
    }       
} else {
    $controller->index();
}
