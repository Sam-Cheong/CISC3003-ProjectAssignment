<?php
// views/course/detail.php

session_start();
$title = "Course Detail";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../models/Course.php';

$roleID   = (int)($_SESSION['roleID'] ?? 0);    // ← get role
$courseID = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

$courseModel = new Course();
$course      = $courseModel->getCourseById($courseID);

if (!$course) {
    echo "<p>Course not found.</p>";
    require_once __DIR__ . '/../layouts/footer.php';
    exit;
}
?>

<main>
    <section class="course-detail">
        <?php flash("error"); ?>
        <h1><?= htmlspecialchars($course->course_name) ?></h1>
        <p class="course-code"><strong>Course Code:</strong> <?= htmlspecialchars($course->course_code) ?></p>
        <p class="course-name"><strong>Teacher:</strong> <?= htmlspecialchars($course->teacher) ?></p>
        <p class="course-schedule"><strong>Schedule:</strong> <?= htmlspecialchars($course->schedule) ?></p>
        <?php if (!empty($course->description)): ?>
            <p class="course-description"><strong>Description:</strong> <?= htmlspecialchars($course->description) ?></p>
        <?php endif; ?>

        <?php if ($roleID === 3): ?>
        <div class="course-actions">
                <form action="/CISC3003-ProjectAssignment/controllers/Enrollments_users.php" method="post">
                    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course->course_id) ?>">
                    <input type="hidden" name="action" value="enroll">
                    <button type="submit" class="btn-enroll" onclick="return confirm('Are you confirm to enroll this course?');">Enroll</button>
                </form>
            </div>
            <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>