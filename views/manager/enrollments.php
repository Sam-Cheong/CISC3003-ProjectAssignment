<!-- filepath: c:\xampp\htdocs\CISC3003-ProjectAssignment\views\manager\manage.php -->
<?php

$title = "Enrollments";

require_once __DIR__ . '\..\..\helpers\session_helper.php';

if (!isset($_SESSION['userID']) || $_SESSION['roleID'] != 2) {
    flash('error', 'Unauthorized access.', 'form-message form-message-red');
    redirect('../../index.php');
}

require_once __DIR__ . '/../../models/Enrollment.php';

$enrollmentModel = new Enrollment();
$enrollments = $enrollmentModel->getEnrollments();

require_once __DIR__ . '\..\layouts\header.php';
?>

<aside>
    <div class="sidebar">
        <h2>Enrollments Dashboard</h2>
        <ul class="side-links">
            <li><a href="/CISC3003-ProjectAssignment/views/manager/"><i class="ri-graduation-cap-line"></i> Courses</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/manager/enrollments.php"><i class="ri-school-line"></i> Enrollments</a></li>
        </ul>
    </div>
</aside>

<main id="manager-dashboard">
    <div class="manage-header">
        <h2>Enrollments</h2>
    </div>
    <table class="enrollments-table">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Enrollment Date</th>
                <th>Status</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($enrollments)) : ?>
                <?php foreach ($enrollments as $enrollment): ?>
                    <tr>
                        <td><?= htmlspecialchars($enrollment->userID) ?></td>
                        <td><?= htmlspecialchars(ucfirst($enrollment->userName)) ?></td>
                        <td><?= htmlspecialchars($enrollment->course_code) ?></td>
                        <td><?= htmlspecialchars($enrollment->course_name) ?></td>
                        <td><?= htmlspecialchars($enrollment->createdAt) ?></td>
                        <td class="status-td <?php echo strtolower($enrollment->status); ?>">
                            <?= htmlspecialchars(ucfirst($enrollment->status)) ?>
                        </td>
                        <!-- <td>
                            <div class="action-buttons">
                                <form method="post" action="../../controllers/Enrollments.php" style="display:inline;">
                                    <input type="hidden" name="action" value="unenroll">
                                    <input type="hidden" name="enrollment_id" value="<?= $enrollment->enrollment_id ?>">
                                    <button type="submit" class="unenroll-btn" onclick="return confirm('Are you sure you want to unenroll this student?')">Unenroll</button>
                                </form>
                            </div>
                        </td> -->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No enrollments available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
