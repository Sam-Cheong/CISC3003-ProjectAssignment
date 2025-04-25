<?php
// index.php
session_start();
require_once __DIR__ . '/helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>

<body>
    <div class="form-container">
        <?php flash('login_success'); ?>

        <?php if (isset($_SESSION['userID'])): ?>
            <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

            <!-- Logout form -->
            <form action="./controllers/Users.php" method="get" style="margin-top: 20px;">
                <input type="hidden" name="q" value="logout">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <h2>Please log in or register</h2>
            <p class="alt-action">
                <a href="./views/login.php">Log in</a>
                <br>
                <a href="./views/register.php">Register</a>
            </p>
        <?php endif; ?>
    </div>
    <script src="./public/js/flash.js"></script>
</body>

</html>