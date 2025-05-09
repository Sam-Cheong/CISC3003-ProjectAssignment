<!-- filepath: c:\xampp\htdocs\CISC3003-ProjectAssignment\views\manager\manage.php -->
<?php

$title = "Manage Enrollments";

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
        <h2>Enrollments</h2>
        <ul class="side-links">
            <li><a href="/CISC3003-ProjectAssignment/views/manager/"><i class="ri-graduation-cap-line"></i> Courses</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/manager/enrollments.php"><i class="ri-school-line"></i> Enrollments</a></li>
        </ul>
    </div>
</aside>

<main id="manager-dashboard">
    <div class="manage-header">
        <div class="download-enrollments-btn btn">
            <a href="/CISC3003-ProjectAssignment/views/manager/export_enrollments_csv.php" class="btn-submit">
                <i class="ri-printer-fill"></i> Download CSV
            </a>
        </div>
    </div>
    <table class="enrollments-table">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Course</th>
                <th>Enrollment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($enrollments)) : ?>
                <?php foreach ($enrollments as $index => $enrollment): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($enrollment->userName) ?></td>
                        <td><?= htmlspecialchars($enrollment->course_name) ?></td>
                        <td><?= htmlspecialchars($enrollment->createdAt) ?></td>
                        <td><?= htmlspecialchars($enrollment->status) ?></td>
                        <td>
                            <form method="post" action="/CISC3003-ProjectAssignment/controllers/Enrollments_managers.php" class="status-form"> <!-- Fixed action URL -->
                                <input type="hidden" name="action" value="updateStatus">
                                <input type="hidden" name="enrollmentID" value="<?= $enrollment->enrollmentID ?>">
                                <select name="status" class="form-input">
                                    <option value="pending" <?= $enrollment->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="active" <?= $enrollment->status === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="finished" <?= $enrollment->status === 'finished' ? 'selected' : '' ?>>Finished</option>
                                </select>
                                <button type="submit" class="form-btn" onclick="return confirm('Confirm update the status?');">Update</button>
                            </form>
                        </td>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>