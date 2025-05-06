<?php
// views/reset.php

require_once __DIR__ . '/../../helpers/session_helper.php';
$token = $_GET['token'] ?? '';
require_once '../layouts/header.php';
?>

    <main id="reset-pwd">
        <div class="form-container">
            <h1>Reset Password</h1>
            <form id="reset-pwd" action="../../controllers/Users.php" method="post">
                <?php flash('reset'); ?>
                <input type="hidden" name="type" value="reset">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-group">
                    <input class="form-input" type="password" id="password" name="password" placeholder="New Password">
                    <label class="form-label" for="password">New Password</label>
                </div>
                <div class="form-group">
                    <input class="form-input" type="password" id="confirmPwd" name="confirmPwd" placeholder="Confirm Password">
                    <label class="form-label" for="confirmPwd">Confirm Password</label>
                </div>
                <button class="form-btn" type="submit">Reset Password</button>
            </form>
            <p class="alt-action">
                Didn't request this? <a href="./login.php">Back to login</a>
            </p>
        </div>
    </main>
</body>

</html>