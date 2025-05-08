<?php
require_once __DIR__ . '\..\..\helpers\session_helper.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CISC3003 | <?php if (isset($title)) { echo $title; } ?></title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/CISC3003-ProjectAssignment/public/css/style.css">
</head>

<body>

    <header>
        <span class="nav-logo">
            <a href="/CISC3003-ProjectAssignment/index.php">
                <img src="/CISC3003-ProjectAssignment/public/images/UMLogo.png" alt="Logo" draggable="false">
            </a>
        </span>

        <div class="nav-search">
            <form id="search-form" action="/CISC3003-ProjectAssignment/views/search/index.php" method="get">
                <input type="hidden" name="action" value="search">
                <input type="text" name="q" id="nav-course-search" placeholder="Search" 
                    value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <i class="ri-search-line" onclick="document.getElementById('search-form').submit();"></i>
            </form>       
        </div>

        <ul class="nav-links">
            <li><a href="/CISC3003-ProjectAssignment/index.php" class="nav-link">Home</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/course/" class="nav-link">Courses</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/about.php" class="nav-link">About</a></li>
            <?php if (isset($_SESSION['userID'])): ?>
                <li><a href="/CISC3003-ProjectAssignment/views/user/profile.php" class="nav-link">Profile</a></li>
                <?php if (isset($_SESSION['roleID']) && $_SESSION['roleID'] === 2): ?>
                    <li><a href="/CISC3003-ProjectAssignment/views/manager/" class="nav-link">Manage</a></li>
                <?php elseif (isset($_SESSION['roleID']) && $_SESSION['roleID'] === 3): ?>
                    <li><a href="/CISC3003-ProjectAssignment/views/user/profile.php" class="nav-link">Enrollments</a></li>
                <?php endif ?>
                <li><a href="/CISC3003-ProjectAssignment/controllers/Users.php?q=logout" class="nav-btn">Logout</a></li>
            <?php else: ?>
                <li><a href="/CISC3003-ProjectAssignment/views/user/login.php" class="nav-link">Login</a></li>
            <?php endif; ?>
        </ul>
        <?php if (!isset($_SESSION['userID'])): ?>
        <div class="nav-btns">
            <a href="/CISC3003-ProjectAssignment/views/user/register.php" class="nav-btn">Sign Up</a>
        </div>
        <?php endif; ?>
    </header>