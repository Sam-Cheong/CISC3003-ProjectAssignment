<?php
// index.php
session_start();
require_once __DIR__ . '/helpers/session_helper.php';
require_once __DIR__ . '/models/Course.php';

$courseModel = new Course();
$courses = $courseModel->getAllCourses();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CISC3003 | Home </title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="./public/css/courses.css">
</head>

<body>
    <?php require_once './views/layouts/header.php' ?>
    <main>
        <section id="hero">
        </section>
        <section class="course-list">
            <h1 class="visually-hidden">All Courses</h1>
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
                <div class="no-courses">No Course Found</div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>
