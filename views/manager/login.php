<?php
session_start();
require_once __DIR__ . '/../../helpers/session_helper.php';

if(isset($_SESSION['user_id']) && isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)) {
    header('Location: course.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Login</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

    <?php require_once '../layouts/header.php'; ?>

    <main id="manager-login">
        <div class="form-container">
            <h1>Manager Login</h1>
            <form action="../../controllers/Users.php" method="POST">
                <input type="hidden" name="type" value="manager-login">
                <div class="form-group">
                    <input class="form-input" type="email" id="username-email" name="username-email" placeholder="Username or Email">
                    <label class="form-label" for="username-email">Username or Email</label>
                    <i class="ri-mail-line"></i>
                </div>
                <div class="form-group">
                    <input class="form-input" type="password" id="password" name="password" placeholder="Password">
                    <label class="form-label" for="password">Password</label>
                    <i class="ri-lock-line"></i>
                </div>
                <button type="submit" class="form-btn">Login</button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            success = document.querySelector(".form-message-green");
            if (success) {
                setTimeout(function() {
                    const current = window.location.pathname;
                    if (current.endsWith('register.php')) {
                        window.location.href = 'login.php';
                    } else if (current.endsWith('login.php')) {
                        window.location.href = '../index.php';
                    }
                }, 2000)
            }
        })
    </script>
</body>
</html>
