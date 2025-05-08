<?php
$title = "Courses";
require_once __DIR__ . '/../../helpers/session_helper.php';
include __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../models/Course.php';  // 新增引入课程模型
//chnage model to controller

$courseModel = new Course();
$courses = $courseModel->getAllCourses();  // 获取所有课程
?>

<main id="courses">
    <h1>All Courses</h1>
    <section class="course-container">
        <?php if (!empty($courses)) : ?>
            <?php foreach ($courses as $course) : ?>
                <div class="course-card">
                    <p class="course-name"><?= htmlspecialchars($course->course_name) ?></p>
                    <p class="course-code"><?= htmlspecialchars($course->course_code) ?></p>
                    <p class="course-teacher">
                        <i class="ri-user-line"></i>
                        <?= htmlspecialchars($course->teacher) ?>
                    </p>
                    <p class="course-schedule">
                        <i class="ri-time-line"></i>
                        <?= htmlspecialchars($course->schedule) ?>
                    </p>
                    <span class="course-btn">
                        <a href= <?php echo "./detail.php?course_id=" . $course->course_id; ?> >View</a>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="no-courses">No Courses Found</div>
        <?php endif; ?>
    </section>
</main>