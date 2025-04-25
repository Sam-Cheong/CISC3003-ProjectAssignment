<?php
// views/register.php
require_once __DIR__ . '/../helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Create Account</h2>

        <?php flash('register'); ?>

        <form action="../controllers/Users.php" method="post">

            <input type="hidden" name="type" value="register">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    >
            </div>

            <div class="form-group">
                <label for="repeatPwd">Confirm Password</label>
                <input
                    type="password"
                    id="repeatPwd"
                    name="repeatPwd"
                    >
            </div>

            <button type="submit">Register</button>
        </form>

        <p class="alt-action">
            Already have an account?
            <a href="./login.php">Log in here</a>
        </p>
    </div>
    <script src="../public/js/flash.js"></script>
</body>

</html>