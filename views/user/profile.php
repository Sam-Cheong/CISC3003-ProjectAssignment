<?php

require_once __DIR__ . '/../../helpers/session_helper.php';

if($_SESSION['roleID'] === 2) {
    require_once __DIR__ . '/../../controllers/Enrollments_managers.php';
    $enrollmentscontroller = new Enrollments();
    $enrollments = $enrollmentscontroller->showAllEnrollments();

    require_once __DIR__ . '/../../controllers/Courses.php';
    $coursescontroller = new Courses();
    $userCourses = $coursescontroller->showAllCourses($_SESSION['userID']);
}elseif($_SESSION['roleID'] === 3) {
    require_once __DIR__ . '/../../controllers/Enrollments_users.php';
    $enrollmentscontroller = new Enrollments();
    $enrollments = $enrollmentscontroller->showSelfEnrollments();
    
    require_once __DIR__ . '/../../controllers/Courses.php';
    $coursescontroller = new Courses();
    $userCourses = $coursescontroller->showSelfCourses($_SESSION['userID']);
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
                <h2 class="subtitle">Courses</h2>
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
                                <div class="course-btns">
                                    <a href="/CISC3003-ProjectAssignment/views/course/detail.php?course_id=<?= htmlspecialchars($course->course_id); ?>" class="detail-btn">View Details</a>
                                </div>
                            </div> 
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>You haven't added any courses yet.</p>
                <?php endif; ?>
            </div>
            <div class="profile-enrollments">
                <?php if ($_SESSION['roleID'] === 3) : ?>
                    <h2 class="subtitle">My Enrollments</h2>
                <?php elseif ($_SESSION['roleID'] === 2) :?>
                    <h2 class="subtitle">Enrollments</h2>
                <?php endif; ?>
                <?php if (!empty($enrollments)) : ?>
                    <div class="course-list">
                        <?php foreach ($enrollments as $index => $enrollment) : ?>
                            <div class="enrollment-card">
                                <div class="enrollment-number"><?= $index + 1 ?></div>
                                <h3 class="enrollmentID"><?= htmlspecialchars($enrollment->enrollmentID) ?></h3>
                                <div class="enrollment_course_id">
                                    <i class="ri-user-line"></i>
                                    <?= htmlspecialchars($enrollment->course_id) ?>
                                </div>
                                <div class="enrollmentStatus">
                                    <i class="ri-time-line"></i>
                                    <?= htmlspecialchars($enrollment->status) ?>
                                </div>
                                <div class="enrollmentduel">
                                    <i class="ri-time-line"></i>
                                    <?= htmlspecialchars($enrollment->createdAt) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>You haven't added any enrollment yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>