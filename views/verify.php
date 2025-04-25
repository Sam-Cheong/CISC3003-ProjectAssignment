<?php
// views/verify.php
require_once __DIR__ . '/../helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Email Verification</h2>

        <?php flash('verify'); ?>
        <?php flash('info'); ?>

        <p>Please enter the 6 digit code we sent to your email.</p>

        <form action="../controllers/Users.php" method="post">
            <!-- Tell controller this is the verification step -->
            <input type="hidden" name="type" value="verify">

            <div class="form-group">
                <label for="code">Verification Code</label>
                <input
                    type="text"
                    id="code"
                    name="code"
                    pattern="\d{6}"
                    maxlength="6"
                    required
                    placeholder="E.g. 123456"
                    value="<?= htmlspecialchars($_POST['code'] ?? '') ?>">
            </div>

            <button type="submit">Verify</button>
        </form>

        <p class="alt-action">
            Didn’t receive a code?
            <a href="/views/register.php">Restart registration</a>
        </p>
    </div>
    <script src="../public/js/flash.js"></script>
</body>

</html>