<!-- /views/login.php -->
<?php
$title = "Login";
require_once __DIR__ . '/../../helpers/session_helper.php';
include __DIR__ . '/../layouts/header.php';
?>

    <main id="login">
        <div class="form-container">
            <h1>Login</h1>
            <div id="error-container"></div>
            <form id="login-form" action="../../controllers/Users.php" method="post">
                <input type="hidden" name="type" value="login">
                <div class="form-group">
                    <input type="text" name="username-email" id="username-email" class="form-input" placeholder="Username or Email">
                    <label class="form-label" for="username-email">Username or Email</label>
                    <i class="ri-mail-line"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-input" placeholder="Password">
                    <label class="form-label" for="password">Password</label>
                    <i class="ri-lock-line"></i>
                </div>
                <?php flash('login'); ?>
                <p class="alt-action"><a href="forgotPwd.php">Forgot Password?</a></p>
                <button type="submit" class="form-btn">Login</button>
            </form>
            <p class="alt-action">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            success = document.querySelector(".form-message-green");
            if (success) {
                setTimeout(function() {
                    // register 成功後跳 login.php，login 成功後跳 dashboard.php
                    const current = window.location.pathname;
                    if (current.endsWith('register.php')) {
                        window.location.href = 'login.php';
                    } else if (current.endsWith('login.php')) {
                        window.location.href = '../../index.php';
                    }
                }, 2000)
            }
        })
    </script>
</body>

</html>