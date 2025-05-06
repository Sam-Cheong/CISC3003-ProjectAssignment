<?php
session_start();
require_once __DIR__ . '/../helpers/session_helper.php';

// 若使用者未登入則導回登入頁
if (!isset($_SESSION['userID'])) {
    redirect('/CISC3003-ProjectAssignment/views/login.php');
}elseif($_SESSION['roleID']===2){
    require_once __DIR__ . '/../controllers/Enrollments_managers.php';
}elseif($_SESSION['roleID']===3){
    require_once __DIR__ . '/../controllers/Enrollments_users.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/style.css">
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/courses.css">
</head>
<body>
    <?php require_once './layouts/header.php'; ?>
    <main>
        <section class="profile-section" style="max-width:1200px; margin:40px auto; padding:0 20px;">
            <h1 class="profile-title" style="font-size:2rem; margin-bottom:20px;">User Profile</h1>
            <div class="profile-info" style="margin-bottom:40px;">
                <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
                <!-- 可根據需要補充更多個人資訊 -->
            </div>
            <div class="profile-courses">
                <h2 class="subtitle">My Courses</h2>
                <?php if (!empty($userCourses)) : ?>
                    <div class="course-list">
                        <?php foreach ($userCourses as $course) : ?>
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
                    </div>
                <?php else : ?>
                    <p>You haven't added any courses yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php require_once './layouts/footer.php'; ?>
</body>
</html>