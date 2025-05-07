<?php
// views/manager/course.php
session_start();
require_once __DIR__ . '/../../helpers/session_helper.php';

if (isset($_SESSION['userID']) && isset($_SESSION['roleID'])) {
    if ($_SESSION['roleID'] !== 2) {
        redirect("../../index.php");
        exit();
    }
}

require_once '../layouts/header.php';

// --- Variable Checks ---
// Ensure variables passed from the controller exist to avoid errors
$courses = $courses ?? []; // Default to empty array if not set
$editCourse = $editCourse ?? null; // Default to null if not set
$formData = $_SESSION['form_data'] ?? []; // Get form data for repopulation
if (isset($_SESSION['form_data'])) { unset($_SESSION['form_data']); } // Clear after use

$user_name = $_SESSION['username'] ?? 'Manager'; // Get username for display
?>
    <main>
        <div class="container">
            <div class="user-info">
                <div>
                    <strong>Welcome!<?= htmlspecialchars($_SESSION['username']) ?></strong>
                    (<?= $_SESSION['roleID'] == 1 ? 'admin' : 'manager' ?>)
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']) ?></div>
            <?php endif; ?>
        
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']) ?></div>
            <?php endif; ?>
            <?php if ($editCourse): ?>
            <form method="post" action="../../controllers/Courses.php">
                <h2>Edit Course</h2>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="course_id" value="<?= $editCourse->course_id ?>">
        
                <div class="form-group">
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code"
                           value="<?= htmlspecialchars($editCourse->course_code) ?>" required>
                </div>
                <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <input type="text" id="course_name" name="course_name"
                           value="<?= htmlspecialchars($editCourse->course_name) ?>" required>
                </div>
                <div class="form-group">
                    <label for="teacher">Teacher:</label>
                    <input type="text" id="teacher" name="teacher"
                           value="<?= htmlspecialchars($editCourse->teacher) ?>" required>
                </div>
                <div class="form-group">
                    <label for="schedule">Time Schedule:</label>
                    <input type="text" id="schedule" name="schedule"
                           value="<?= htmlspecialchars($editCourse->schedule) ?>" required>
                </div>
                <button type="submit" class="btn-submit">Update Course</button>
                <a href="course.php" class="btn-submit btn-cancel">Cancel</a>
            </form>
            <?php else: ?>
            <form method="post" class="course-form">
                <h2>Create New Course</h2>
        
                <div class="form-group">
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code"
                           value="<?= $_SESSION['form_data']['course_code'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <input type="text" id="course_name" name="course_name"
                           value="<?= $_SESSION['form_data']['course_name'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="teacher">Teacher:</label>
                    <input type="text" id="teacher" name="teacher"
                           value="<?= $_SESSION['form_data']['teacher'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="schedule">Time Schedule:</label>
                    <input type="text" id="schedule" name="schedule"
                           value="<?= $_SESSION['form_data']['schedule'] ?? '' ?>" required>
                </div>
                <button type="submit" class="btn-submit">Create Course</button>
            </form>
            <?php endif; ?>
            <div class="course-list">
                <h2>Current Course List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Teacher</th>
                            <th>Schedule</th>
                            <th>Created Time</th>
                            <th>Edit / Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course) : ?>
                            <tr>
                                <td><?= htmlspecialchars($course->course_code) ?></td>
                                <td><?= htmlspecialchars($course->course_name) ?></td>
                                <td><?= htmlspecialchars($course->teacher) ?></td>
                                <td><?= htmlspecialchars($course->schedule) ?></td>
                                <td><?= isset($course->created_at) ? date('Y-m-d H:i', strtotime($course->created_at)) : 'unknown' ?></td>
                                <td class="action-buttons">
                                    <a href="course.php?edit=<?= $course->course_id ?>" class="btn-edit">Edit</a>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('are you sure delete this course?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="course_id" value="<?= $course->course_id ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
<?php unset($_SESSION['form_data']); ?>
