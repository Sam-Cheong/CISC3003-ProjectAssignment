<?php
// views/reset.php
require_once __DIR__ . '/../helpers/session_helper.php';
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Reset Password</h2>

        <?php flash('reset'); ?>

        <form action="../controllers/Users.php" method="post">
            <input type="hidden" name="type" value="reset">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <label for="password">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required>
            </div>

            <div class="form-group">
                <label for="confirmPwd">Confirm Password</label>
                <input
                    type="password"
                    id="confirmPwd"
                    name="confirmPwd"
                    required>
            </div>

            <button type="submit">Reset Password</button>
        </form>

        <p class="alt-action">
            Didn't request this? <a href="./login.php">Back to login</a>
        </p>
    </div>
    <script src="../public/js/flash.js"></script>
</body>

</html>