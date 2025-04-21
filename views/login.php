<?php
// views/login.php
require_once __DIR__ . '/../helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Log In</h2>

        <?php flash('login'); ?>

        <form action="/controllers/Users.php" method="post">
            <!-- Tell controller this is a login -->
            <input type="hidden" name="type" value="login">

            <div class="form-group">
                <label for="username">Username or Email</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required>
            </div>

            <button type="submit">Log In</button>
        </form>

        <p class="alt-action">
            Don’t have an account?
            <a href="/views/register.php">Register now</a>
        </p>
    </div>
</body>

</html>