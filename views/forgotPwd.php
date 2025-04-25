<?php
// views/forgot.php
require_once __DIR__ . '/../helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/style.css">
</head>

<body>
    <main id="forgot-pwd">
        <div class="form-container">
            <h1>Forgot Password</h1>
            <form action="../controllers/Users.php" method="post">
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
=======
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Forgot Password</h2>

        <?php flash('forgot'); ?>

        <form action="../controllers/Users.php" method="post">
            <input type="hidden" name="type" value="forgot">

            <div class="form-group">
                <label for="email">Enter your email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <button type="submit">Send Reset Link</button>
        </form>

        <p class="alt-action">
            Remembered? <a href="./login.php">Log in</a>
        </p>
    </div>
    <script src="../public/js/flash.js"></script>
>>>>>>> 584fc42eda2ff353330a5e1de8a3c588107e54f0
</body>

</html>