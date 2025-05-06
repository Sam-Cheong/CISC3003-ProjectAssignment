<?php
$title = "Courses";
require_once __DIR__ . '/../../helpers/session_helper.php';
include __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../models/Course.php';  // 新增引入课程模型
//chnage model to controller

$courseModel = new Course();
$courses = $courseModel->getAllCourses();  // 获取所有课程
?>
<body>
    <main>
        <section id="hero">
            <h1 class="visually-hidden">Course Management System</h1>
        </section>
        <section class="course-list">
            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course) : ?>
                    <div class="course-card">
                        <div class="course-code"><?= htmlspecialchars($course->course_code) ?></div>
                        <h3 class="course-name"><?= htmlspecialchars($course->course_name) ?></h3>
                        <div class="course-teacher">
                            <i class="ri-user-line"></i>
                            <?= htmlspecialchars($course->teacher) ?>
                        </div>
                        <div class="course-schedule">
                            <i class="ri-time-line"></i>
                            <?= htmlspecialchars($course->schedule) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="no-courses">No Courses Found</div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>