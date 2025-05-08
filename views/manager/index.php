<!-- filepath: c:\xampp\htdocs\CISC3003-ProjectAssignment\views\manager\manage.php -->
<?php

$title = "Dashboard";

require_once __DIR__ . '\..\..\helpers\session_helper.php';

if (!isset($_SESSION['userID']) || $_SESSION['roleID'] != 2) {
    flash('error', 'Unauthorized access.', 'form-message form-message-red');
    redirect('../../index.php');
}

require_once __DIR__ . '/../../models/Course.php';

$courseModel = new Course();
$courses = $courseModel->getAllCourses();

require_once __DIR__ . '\..\layouts\header.php';
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

<main id="manager-dashboard">
    <div class="manage-header">
        <div class="add-course-btn btn">
            <a href="/CISC3003-ProjectAssignment/views/manager/add_course.php" class="btn-submit"><i class="ri-add-fill"></i> Add Course</a>
        </div>
    </div>
    <table class="courses-table">
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
                                <div class="view-course-btn btn">
                                    <a href="/CISC3003-ProjectAssignment/views/course/detail.php?id=<?= $course->course_id ?>" class="btn-view">
                                        View
                                    </a>
                                </div>
                                <div class="edit-course-btn btn">
                                    <a href="/CISC3003-ProjectAssignment/views/manager/edit_course.php?course_id=<?= $course->course_id ?>" class="btn-edit">
                                        Edit
                                    </a>
                                </div>
                                <form method="post" action="../../controllers/Courses.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="course_id" value="<?= $course->course_id ?>">
                                    <button type="submit" class="delete-course-btn" onclick="return confirm('Are you sure?')">Delete</button>
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