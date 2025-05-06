<?php
// views/forgot.php
require_once __DIR__ . '/../../helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CISC3003 | Forgot Password</title>
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/style.css">
</head>

<body>
    <main id="forgot-pwd">
        <div class="form-container">
            <h1>Forgot Password</h1>
            <form action="../../controllers/Users.php" method="post">
                <?php flash('forgot'); ?>
                <input type="hidden" name="type" value="forgot">
                <div class="form-group">
                    <input class="form-input" type="email" id="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <label class="form-label" for="email">Enter your email</label>
                </div>
                <button class="form-btn" type="submit">Send Reset Link</button>
            </form>
            <p class="alt-action">
                Remembered? <a href="./login.php"> Click here login</a>
            </p>
        </div>
    </main>
</body>

</html>