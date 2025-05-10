<?php
// views/user/profile.php
session_start();
require_once __DIR__ . '/../../helpers/session_helper.php';
require_once __DIR__ . '/../../models/Enrollment.php';
require_once __DIR__ . '/../../models/Course.php';

$title   = $_SESSION['username'] ?? 'Profile';
$userID  = (int)($_SESSION['userID']   ?? 0);
$roleID  = (int)($_SESSION['roleID']   ?? 0);

$enrollmentModel = new Enrollment();
$records = $enrollmentModel->getEnrollmentsByUser($userID);
$heading = 'Your Enrollments';

require_once __DIR__ . '/../layouts/header.php';
?>

<main class="profile-page">
    <section class="profile-info">
        <h2>Profile Information</h2>
        <p><strong>Name: </strong><?= htmlspecialchars(ucwords($_SESSION['username'])) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
    </section>

    <section class="records">
        <h2><?= $heading ?></h2>

        <?php flash("success"); ?>
        <?php flash("cancel"); ?>

        <?php if (!empty($records) && $_SESSION['roleID'] == 3): ?>
            <div class="table-wrapper">
                <table class="enrollment-table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Teacher</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Enrolled On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r->course_name) ?></td>
                                <td><?= htmlspecialchars($r->teacher) ?></td>
                                <td><?= htmlspecialchars($r->schedule) ?></td>
                                <td><?= htmlspecialchars($r->status) ?></td>
                                <td><?= htmlspecialchars($r->createdAt) ?></td>
                                <td>
                                    <form action="/CISC3003-ProjectAssignment/controllers/Enrollments_users.php" method="post">
                                        <input type="hidden" name="action" value="cancel">
                                        <input
                                            type="hidden"
                                            name="enrollment_id"
                                            value="<?= htmlspecialchars($r->enrollmentID) ?>">
                                        <button type="submit" class="cancel-btn" onclick="return confirm('Are you sure to cancel the enrollment?');">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No Enrollment found.</p>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>