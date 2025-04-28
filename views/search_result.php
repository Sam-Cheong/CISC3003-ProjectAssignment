<!-- <?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="search-results">
    <h1>Search Result</h1>
    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course) : ?>
            <div class="course-card">
                <h3><?= htmlspecialchars($course->course_name) ?></h3>
                <p>Course code: <?= htmlspecialchars($course->course_code) ?></p>
                <p>教師: <?= htmlspecialchars($course->teacher) ?></p>
                <p>上課時間: <?= htmlspecialchars($course->schedule) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>找不到符合條件的課程</p>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> -->