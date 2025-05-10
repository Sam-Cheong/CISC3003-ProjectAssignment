<?php
// views/manager/course.php

// Start session if not already started (best practice: start in controller/front-controller)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Variable Checks ---
// Ensure variables passed from the controller exist to avoid errors
$courses = $courses ?? []; // Default to empty array if not set
$editCourse = $editCourse ?? null; // Default to null if not set
$formData = $_SESSION['form_data'] ?? []; // Get form data for repopulation
if (isset($_SESSION['form_data'])) { unset($_SESSION['form_data']); } // Clear after use

$user_name = $_SESSION['username'] ?? 'Manager'; // Get username for display

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive meta tag -->
    <title>Course Management</title>
    <!-- Consider using a CDN for a CSS framework like Bootstrap for faster setup, or Font Awesome for icons -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/courses.css"> <!-- Ensure path is correct -->
    <!-- Optional: Add Font Awesome for icons -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
</head>
<body>
    <div class="container">

        <div class="user-info">
            <span>Welcome, <?= htmlspecialchars($user_name) ?>!</span>
            <a href="Auth.php?action=logout" class="logout-btn">Logout</a>
        </div>

        <!-- Display Session Messages -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error">
                <!-- Optional Icon: <i class="fas fa-exclamation-triangle"></i> -->
                <?= /* Use nl2br if error message might contain intended newlines */ nl2br(htmlspecialchars($_SESSION['error'])); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['success'])): ?>
             <div class="alert success">
                 <!-- Optional Icon: <i class="fas fa-check-circle"></i> -->
                 <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Edit Course Form (Displayed conditionally) -->
        <?php if ($editCourse): ?>
        <div class="course-form">
            <h2>Edit Course</h2>
            <form method="post" action="../../controllers/Courses.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="course_id" value="<?= htmlspecialchars((string)($editCourse->course_id ?? '')) ?>">

                <div class="form-group">
                    <label for="edit_course_code">Course Code:</label>
                    <input type="text" id="edit_course_code" name="course_code" value="<?= htmlspecialchars($editCourse->course_code ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_course_name">Course Name:</label>
                    <input type="text" id="edit_course_name" name="course_name" value="<?= htmlspecialchars($editCourse->course_name ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_teacher">Teacher:</label>
                    <input type="text" id="edit_teacher" name="teacher" value="<?= htmlspecialchars($editCourse->teacher ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_schedule">Time Schedule:</label>
                    <input type="text" id="edit_schedule" name="schedule" value="<?= htmlspecialchars($editCourse->schedule ?? '') ?>" required>
                </div>
                <!-- *** CHANGED Description Field to input type="text" *** -->
                <div class="form-group">
                    <label for="edit_description">Description:</label>
                    <input type="text" id="edit_description" name="description" value="<?= htmlspecialchars($editCourse->description ?? '') ?>">
                    <!-- <textarea id="edit_description" name="description" rows="4"><?= htmlspecialchars($editCourse->description ?? '') ?></textarea> -->
                </div>
                <!-- End Description Field -->
                <button type="submit" class="btn-submit">Update Course</button>
                <a href="Courses.php?action=index" class="btn-cancel">Cancel</a>
            </form>
        </div>

        <!-- Create Course Form (Displayed if not editing) -->
        <?php else: ?>
        <div class="course-form">
            <h2>Create New Course</h2>
            <form method="post" action="../../controllers/Courses.php">
                 <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="create_course_code">Course Code:</label>
                    <input type="text" id="create_course_code" name="course_code" value="<?= htmlspecialchars($formData['course_code'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="create_course_name">Course Name:</label>
                    <input type="text" id="create_course_name" name="course_name" value="<?= htmlspecialchars($formData['course_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="create_teacher">Teacher:</label>
                    <input type="text" id="create_teacher" name="teacher" value="<?= htmlspecialchars($formData['teacher'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="create_schedule">Time Schedule:</label>
                    <input type="text" id="create_schedule" name="schedule" value="<?= htmlspecialchars($formData['schedule'] ?? '') ?>" required>
                </div>
                 <!-- *** CHANGED Description Field to input type="text" *** -->
                <div class="form-group">
                    <label for="create_description">Description:</label>
                     <input type="text" id="create_description" name="description" value="<?= htmlspecialchars($formData['description'] ?? '') ?>">
                    <!-- <textarea id="create_description" name="description" rows="4"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea> -->
                </div>
                <!-- End Description Field -->
                <button type="submit" class="btn-submit">Create Course</button>
            </form>
        </div>
        <?php endif; ?>


        <!-- Course List -->
        <div class="course-list">
            <h2>Current Course List</h2>
            <?php if (empty($courses)): ?>
                <p>No courses found.</p>
            <?php else: ?>
                <div style="overflow-x: auto;"> <!-- Add wrapper for horizontal scroll on small screens -->
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Teacher</th>
                                <th>Schedule</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course) : ?>
                                <?php
                                    // Prepare description display
                                    $description_text = !empty($course->description) ? $course->description : null;
                                    // Since it's stored as single-line now, just display or show N/A
                                    $display_description = $description_text ? htmlspecialchars($description_text) : '<span class="na-text">N/A</span>';
                                    $title_description = $description_text ? htmlspecialchars($description_text) : 'No description available';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($course->course_code) ?></td>
                                    <td><?= htmlspecialchars($course->course_name) ?></td>
                                    <td><?= htmlspecialchars($course->teacher) ?></td>
                                    <td><?= htmlspecialchars($course->schedule) ?></td>
                                    <!-- Updated Description Display -->
                                    <td class="description-col" title="<?= $title_description ?>">
                                        <?= $display_description /* Display N/A or text */ ?>
                                    </td>
                                    <td><?= isset($course->created_at) ? date('Y-m-d H:i', strtotime($course->created_at)) : '<span class="na-text">N/A</span>' ?></td>
                                    <td class="action-buttons">
                                        <a href="Courses.php?action=edit&id=<?= $course->course_id ?>" class="btn-edit">Edit</a>
                                        <form method="post" action="Courses.php" onsubmit="return confirm('Are you sure you want to delete this course?');">
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
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
