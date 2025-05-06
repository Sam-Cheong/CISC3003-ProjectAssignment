<?php

$title = "Home";

require_once __DIR__ . '/helpers/session_helper.php';
include __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/models/Course.php';  // 新增引入课程模型
//chnage model to controller

$courseModel = new Course();
$courses = $courseModel->getAllCourses();  // 获取所有课程
?>

    <main>
        <section id="hero">
            <div class="hero-container">
                
            </div>
        </section>
        <section id="">
            <h1 class="visually-hidden">Course Management System</h1>
        </section>
        <section>

        </section>
    </main>
</body>

</html>
