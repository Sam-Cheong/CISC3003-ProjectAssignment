<!-- filepath: c:\xampp\htdocs\CISC3003-ProjectAssignment\views\manager\manage.php -->
<?php

$title = "Dashboard";

require_once __DIR__ . '\..\..\helpers\session_helper.php';

if (!isset($_SESSION['userID']) || $_SESSION['roleID'] != 2) {
    flash('error', 'Unauthorized access.', 'form-message form-message-red');
    redirect('../../index.php');
}

require_once __DIR__ . '/../../models/Course.php'; // Include the Course model

$courseModel = new Course();
$courses = $courseModel->getAllCourses(); // Fetch all courses

require_once __DIR__ . '\..\layouts\header.php';
?>

<aside>
    <div class="sidebar">
        <h2>Course Management</h2>
        <ul class="side-links">
            <li><a href="/CISC3003-ProjectAssignment/views/manager/">Courses</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/manager/enrollments.php">Enrollments</a></li>
        </ul>
    </div>
</aside>

<main id="manager-dashboard">
    <div class="container">
        <a href="/CISC3003-ProjectAssignment/views/manager/add_course.php" class="btn-submit">Add Course</a>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Teacher</th>
                <th>Schedule</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= htmlspecialchars($course->course_code) ?></td>
                        <td><?= htmlspecialchars($course->course_name) ?></td>
                        <td><?= htmlspecialchars($course->teacher) ?></td>
                        <td><?= htmlspecialchars($course->schedule) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="/CISC3003-ProjectAssignment/views/manager/edit_course.php?course_id=<?= $course->course_id ?>" class="btn-edit">Edit</a>
                                <form method="post" action="../../controllers/Courses.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="course_id" value="<?= $course->course_id ?>">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No courses available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>