<?php
$title = "Courses";
require_once __DIR__ . '/../../helpers/session_helper.php';
include __DIR__ . '/../layouts/header.php';
?>

    <main>
        <h1>All Courses</h1>
        <div class="course-container">
            <?php 
            foreach ($courses as $course) {
                echo $course->course_name;
                echo "<br>";
            }
            ?>
        </div>
    </main>
</body>
</html>