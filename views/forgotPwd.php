<?php
// views/forgot.php
require_once __DIR__ . '/../helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Forgot Password</h2>

        <?php flash('forgot'); ?>

        <form action="../controllers/Users.php" method="post">
            <input type="hidden" name="type" value="forgot">

            <div class="form-group">
                <label for="email">Enter your email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <button type="submit">Send Reset Link</button>
        </form>

        <p class="alt-action">
            Remembered? <a href="./login.php">Log in</a>
        </p>
    </div>
</body>

</html>