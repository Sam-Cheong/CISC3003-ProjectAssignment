<?php

require_once __DIR__ . '/../../helpers/session_helper.php';
// require_once __DIR__ . '/../../controllers/Enrollments.php';

if (!isset($_SESSION['userID'])) {
    redirect('./login.php');
}

require_once '../layouts/header.php';

?>
    <main>
        <section class="profile-section">
            <h1 class="profile-title">User Profile</h1>
            <div class="profile-info">
                <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
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
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>