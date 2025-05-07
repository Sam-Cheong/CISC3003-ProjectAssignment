<?php
require_once __DIR__ . '/../../helpers/session_helper.php';
include __DIR__ . '/../layouts/header.php';
?>

<main>
    <form method="post" action="../../controllers/Courses.php">
        <input type="hidden" name="action" value="create">
        <input type="text" name="course_code" placeholder="Course Code" required>
        <input type="text" name="course_name" placeholder="Course Name" required>
        <input type="text" name="teacher" placeholder="Teacher" required>
        <input type="text" name="schedule" placeholder="Schedule" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit">Create Course</button>
    </form>
</main>