<?php 
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../../controllers/Searches.php';
?>

<main>

    <section class="course-list">
        <h1>Course List</h1>
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
            <p>No courses found.</p>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>