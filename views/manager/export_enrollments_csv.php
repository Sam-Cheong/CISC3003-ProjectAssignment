<?php
require_once __DIR__ . '/../../models/Enrollment.php';
require_once __DIR__ . '/../../helpers/session_helper.php';

if (!isset($_SESSION['userID'])) {
    redirect('/CISC3003-ProjectAssignment/views/login.php');
} elseif ($_SESSION['roleID'] !== 2) {
    flash('Access Denied', 'You do not have permission to access this page.');
    redirect('../../index.php');
}

$enrollmentModel = new Enrollment();
$enrollments = $enrollmentModel->getEnrollments();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=enrollments.csv');


$output = fopen('php://output', 'w');

fputcsv($output, ['Student ID', 'Student Name', 'Course Code', 'Course Name', 'Enrollment Date', 'Status']);

foreach ($enrollments as $enrollment) {
    fputcsv($output, [
        $enrollment->userID,
        ucfirst($enrollment->userName),
        $enrollment->course_code,
        $enrollment->course_name,
        $enrollment->createdAt,
        ucfirst($enrollment->status)
    ]);
}

fclose($output);
exit();