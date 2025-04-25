<?php
// views/login.php
require_once __DIR__ . '/../helpers/session_helper.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Log In</h2>

        <?php flash('login'); ?>

        <form action="../controllers/Users.php" method="post">
            <input type="hidden" name="type" value="login">
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="text" id="email" name="email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <p class="alt-action"><a href="./forgotPwd.php">Forgot your password?</a></p>
            <button type="submit">Log In</button>
        </form>
        <p class="alt-action">Don’t have an account? <a href="./register.php">Register now</a></p>
    </div>
    <script src="../public/js/flash.js"></script>
</body>
</html>