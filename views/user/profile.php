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
                    <?php
                        // 根據使用者角色顯示相應的課程列表
                        if ($_SESSION['roleID'] === 2) {
                            require_once __DIR__ . '/../../controllers/Courses.php';
                        } elseif ($_SESSION['roleID'] === 3) {
                            
                        }
                    ?>
            </div>
        </section>
    </main>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>