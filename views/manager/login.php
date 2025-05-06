<?php
// views/manager/login.php
// Session should be started by the Auth controller before requiring this view
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Fallback
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/courses-loginphp.css">
</head>
<body>
    <div class="login-container">
        <h2>Manager Login</h2>

        <!-- Display Session Messages -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['success'])): // Might show success message from other actions redirecting here ?>
             <div class="alert success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
         <?php if(isset($_SESSION['info'])): // Optional info message ?>
             <div class="alert info"><?= htmlspecialchars($_SESSION['info']); unset($_SESSION['info']); ?></div>
         <?php endif; ?>


        <!-- Form submits to the Auth controller's processLogin action -->
        <form method="post" action="Auth.php?action=login">
            <!-- You could add a hidden input for action instead of using URL param if preferred -->
             <!-- <input type="hidden" name="action" value="processLogin"> -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>
         <!-- Optional: Link to forgot password or public site -->
         <div style="text-align: center; margin-top: 20px;">
             <a href="../index.php">Back to Course Page</a>
         </div>
    </div>
</body>
</html>
