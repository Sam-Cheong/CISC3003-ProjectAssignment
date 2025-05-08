<?php

$title = "Add Course";

require_once __DIR__ . '/../../helpers/session_helper.php';

if (!isset($_SESSION['userID']) || $_SESSION['roleID'] != 2) {
    flash('error', 'Unauthorized access.', 'form-message form-message-red');
    redirect('../../index.php');
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

<main id="add-course">
    <h1>Add New Course</h1>
    <form method="post" action="../../controllers/Courses.php" class="course-form">
        <input type="hidden" name="action" value="create">
        <div class="form-group">
            <label for="course_code" class="form-label">Course Code</label>
            <input type="text" id="course_code" name="course_code" class="form-input" placeholder="Enter course code">
        </div>
        <div class="form-group">
            <label for="course_name" class="form-label">Course Name</label>
            <input type="text" id="course_name" name="course_name" class="form-input" placeholder="Enter course name">
        </div>
        <div class="form-group">
            <label for="teacher" class="form-label">Teacher</label>
            <input type="text" id="teacher" name="teacher" class="form-input" placeholder="Enter teacher name">
        </div>
        <div class="form-group">
            <label for="schedule" class="form-label">Schedule</label>
            <input type="text" id="schedule" name="schedule" class="form-input" placeholder="Enter schedule">
        </div>
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-input" placeholder="Enter course description"></textarea>
        </div>
        <button type="submit" class="form-btn">Add Course</button>
    </form>
</main>