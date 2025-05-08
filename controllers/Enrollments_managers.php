<?php

require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';

class EnrollmentsManagers {
    private $enrollmentModel;

    public function __construct() {
        $this->enrollmentModel = new Enrollment();
    }

    /**
     * Show all enrollments for managers.
     */
    public function index() {
        $enrollments = $this->enrollmentModel->getEnrollments();
        require_once __DIR__ . '/../views/manager/enrollments.php';
    }

    /**
     * Show all enrollments for managers.
     */
    public function showAllEnrollments() {
        return $this->enrollmentModel->getEnrollments();
    }

    /**
     * Update enrollment status.
     */
    public function updateStatus() {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $data = [
            'enrollmentID' => intval($_POST['enrollmentID'] ?? 0),
            'status'       => trim($_POST['status'] ?? '')
        ];

        if (empty($data['enrollmentID']) || empty($data['status'])) {
            flash('error', 'Invalid data provided.', 'form-message form-message-red');
            redirect('../views/manager/enrollments.php');
        }

        if ($this->enrollmentModel->updateStatus($data['enrollmentID'], $data['status'])) {
            flash('success', 'Enrollment status updated successfully!', 'form-message form-message-green');
        } else {
            flash('error', 'Failed to update enrollment status.', 'form-message form-message-red');
        }

        redirect('../views/manager/enrollments.php');
    }
}

$init = new EnrollmentsManagers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action'] ?? '') {
        case 'updateStatus':
            $init->updateStatus();
            break;
        default:
            redirect('../views/manager/enrollments.php');
    }
} else {
    $init->index();
}