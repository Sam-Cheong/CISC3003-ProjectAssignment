<!-- /views/register.php -->
<?php
$title = "Register";
require_once __DIR__ . '/../../helpers/session_helper.php';
include __DIR__ . '/../layouts/header.php';

if (isset($_SESSION['userID'])) {
    header('Location: /CISC3003-ProjectAssignment/index.php');
    exit;
}
?>

    <main id="register">
        <div class="form-container">
            
            <h1>Register</h1>
            <div id="error-container"></div>
            <form id="register-form" action="../../controllers/Users.php" method="post">
                <input type="hidden" name="type" value="register">
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-input" placeholder=" ">
                    <label class="form-label" for="username">Username</label>
                    <i class="ri-user-line"></i>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-input" placeholder=" ">
                    <label class="form-label" for="email">Email</label>
                    <i class="ri-mail-line"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-input" placeholder=" ">
                    <label class="form-label" for="password">Password</label>
                    <i class="ri-lock-line"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="repeatPwd" id="repeatPwd" class="form-input" placeholder=" ">
                    <label class="form-label" for="repeatPwd">Confirm Password</label>
                    <i class="ri-lock-line"></i>
                </div>
                <?php flash('register'); ?>
                <button type="submit" class="form-btn">Register</button>
            </form>
            <p class="alt-action">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </main>
</body>

</html>