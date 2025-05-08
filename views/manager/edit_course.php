<?php

$title = "Edit Course";
require_once __DIR__ . '/../../helpers/session_helper.php';

if (!isset($_SESSION['userID']) || $_SESSION['roleID'] != 2) {
    flash('error', 'Unauthorized access.', 'form-message form-message-red');
    redirect('../../index.php');
}

require_once __DIR__ . '/../../models/Course.php';

$courseModel = new Course();
$course_id = $_GET['course_id'] ?? null;

if ($course_id) {
    $course = $courseModel->getCourseById($course_id);
} else {
    flash('error', 'Course not found.', 'form-message form-message-red');
    redirect('../../views/manager/manage.php');
}

require_once __DIR__ . '/../layouts/header.php';

?>

<aside>
    <div class="sidebar">
        <h2>Course Dashboard</h2>
        <ul class="side-links">
            <li><a href="/CISC3003-ProjectAssignment/views/manager/"><i class="ri-graduation-cap-line"></i> Courses</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/manager/enrollments.php"><i class="ri-school-line"></i> Enrollments</a></li>
        </ul>
    </div>
</aside>

<main id="edit-course">
    <h1>Edit Course</h1>
    <form method="post" action="../../controllers/Courses.php" class="course-form">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="course_id" value="<?= htmlspecialchars($course->course_id) ?>">
        <div class="form-group">
            <label class="form-label" for="course_code" class="form-label">Course Code</label>
            <input class="form-input" type="text" id="course_code" name="course_code" class="form-input" value="<?= htmlspecialchars($course->course_code) ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="course_name" class="form-label">Course Name</label>
            <input class="form-input" type="text" id="course_name" name="course_name" class="form-input" value="<?= htmlspecialchars($course->course_name) ?>">
        </div>
        <div class="form-group">
            <label for="teacher" class="form-label">Teacher</label>
            <input type="text" id="teacher" name="teacher" class="form-input" value="<?= htmlspecialchars($course->teacher) ?>">
        </div>
        <div class="form-group">
            <label for="schedule" class="form-label">Schedule</label>
            <input type="text" id="schedule" name="schedule" class="form-input" value="<?= htmlspecialchars($course->schedule) ?>">
        </div>
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-input"><?= htmlspecialchars($course->description) ?></textarea>
        </div>
        <button type="submit" class="form-btn">Update Course</button>
    </form>
</main>