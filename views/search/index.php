<?php 

    $title = "Search Results";
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../../controllers/Searches.php';
?>

<main id="courses">
    <h1>Course List</h1>
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
                        <a href= <?php echo "../course/detail.php?course_id=" . $course->course_id; ?> >View</a>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No courses found.</p>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>