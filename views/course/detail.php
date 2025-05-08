<?php
$title = "Course Detail";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../models/Course.php';

// 获取课程 ID
$courseID = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

$courseModel = new Course();
$course = $courseModel->getCourseById($courseID);

if (!$course) {
    echo "<p>Course not found.</p >";
    require_once __DIR__ . '/../layouts/footer.php';
    exit;
}
?>

<main>
    <section class="course-detail">
        <h1><?= htmlspecialchars($course->course_name) ?></h1>
        <p class="course-code"><strong>Course Code:</strong> <?= htmlspecialchars($course->course_code) ?></p >
        <p class="course-name"><strong>Teacher:</strong> <?= htmlspecialchars($course->teacher) ?></p >
        <p class="course-schedule"><strong>Schedule:</strong> <?= htmlspecialchars($course->schedule) ?></p >
        <?php if (isset($course->description) && !empty($course->description)) : ?>
            <p class="course-description"><strong>Description:</strong> <?= htmlspecialchars($course->description) ?></p >
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>