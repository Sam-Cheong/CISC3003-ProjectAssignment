<?php if (isset($_SESSION['error'])) : ?>
                <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']) ?></div>
            <?php endif; ?>
        
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']) ?></div>
            <?php endif; ?>
            <?php if ($editCourse): ?>
            <form method="post" action="../../controllers/Courses.php">
                <h2>Edit Course</h2>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="course_id" value="<?= $editCourse->course_id ?>">
        
                <div class="form-group">
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code"
                           value="<?= htmlspecialchars($editCourse->course_code) ?>" required>
                </div>
                <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <input type="text" id="course_name" name="course_name"
                           value="<?= htmlspecialchars($editCourse->course_name) ?>" required>
                </div>
                <div class="form-group">
                    <label for="teacher">Teacher:</label>
                    <input type="text" id="teacher" name="teacher"
                           value="<?= htmlspecialchars($editCourse->teacher) ?>" required>
                </div>
                <div class="form-group">
                    <label for="schedule">Time Schedule:</label>
                    <input type="text" id="schedule" name="schedule"
                           value="<?= htmlspecialchars($editCourse->schedule) ?>" required>
                </div>
                <button type="submit" class="btn-submit">Update Course</button>
                <a href="course.php" class="btn-submit btn-cancel">Cancel</a>
            </form>
            <?php else: ?>
            <form method="post" action="../../controllers/Courses.php">
                <h2>Create New Course</h2>

                <input type="hidden" name="action" value="create">
        
                <div class="form-group">
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code"
                           value="<?= $_SESSION['form_data']['course_code'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <input type="text" id="course_name" name="course_name"
                           value="<?= $_SESSION['form_data']['course_name'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="teacher">Teacher:</label>
                    <input type="text" id="teacher" name="teacher"
                           value="<?= $_SESSION['form_data']['teacher'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="schedule">Time Schedule:</label>
                    <input type="text" id="schedule" name="schedule"
                           value="<?= $_SESSION['form_data']['schedule'] ?? '' ?>" required>
                </div>
                <button type="submit" class="btn-submit">Create Course</button>
            </form>
            <?php endif; ?>