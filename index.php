<?php
// index.php
session_start();
require_once __DIR__ . '/helpers/session_helper.php';
require_once __DIR__ . '/models/Course.php';  // 新增引入课程模型

$courseModel = new Course();
$courses = $courseModel->getAllCourses();  // 获取所有课程
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CISC3003 | Home </title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/style.css">
    <!-- 新增课程列表专属样式 -->
    <style>
        .course-list {
            max-width: 1200px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 0 20px;
        }

        .course-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-code {
            color: #2973B2;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .course-teacher {
            color: #666;
            font-size: 0.95em;
            margin: 10px 0;
        }

        .course-schedule {
            background: #f2f4f7;
            padding: 8px;
            border-radius: 6px;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <?php require_once './views/layouts/header.php' ?>

    <main>
        <section id="hero">
            <h1 class="visually-hidden">课程管理系统</h1>
        </section>

        <!-- 新增课程列表区块 -->
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
                <div class="no-courses">暂无课程数据</div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>
