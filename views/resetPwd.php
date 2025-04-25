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
<<<<<<< HEAD
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/style.css">
</head>

<body>
    <main id="reset-pwd">
        <div class="form-container">
            <h1>Reset Password</h1>
            <form id="reset-pwd" action="../controllers/Users.php" method="post">
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
=======
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
>>>>>>> 584fc42eda2ff353330a5e1de8a3c588107e54f0
</body>

</html>