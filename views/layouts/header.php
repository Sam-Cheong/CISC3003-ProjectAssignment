<header>
    <span class="nav-logo">
        <a href="/CISC3003-ProjectAssignment/index.php">
            <img src="/CISC3003-ProjectAssignment/public/images/logo.png" alt="Logo">
        </a>
    </span>

    <div class="nav-search">
        <input type="text" name="nav-course-search" id="nav-course-search" placeholder="Search">
        <i class="ri-search-line"></i>
    </div>

    <ul class="nav-links">
        <li><a href="/CISC3003-ProjectAssignment/index.php" class="nav-link">Home</a></li>
        <li><a href="/CISC3003-ProjectAssignment/views/courses.php" class="nav-link">Courses</a></li>
        <li><a href="/CISC3003-ProjectAssignment/views/about.php" class="nav-link">About</a></li>
        <?php if (isset($_SESSION['userID'])): ?>
            <li><a href="/CISC3003-ProjectAssignment/views/dashboard.php" class="nav-link">Dashboard</a></li>
            <li><a href="/CISC3003-ProjectAssignment/views/profile.php" class="nav-link">Profile</a></li>
            <li><a href="/CISC3003-ProjectAssignment/controllers/Users.php?q=logout" class="nav-btn">Logout</a></li>
        <?php else: ?>
            <li><a href="/CISC3003-ProjectAssignment/views/login.php" class="nav-link">Login</a></li>
        <?php endif; ?>
    </ul>
    <?php if (!isset($_SESSION['userID'])): ?>
    <div class="nav-btns">
        <a href="/CISC3003-ProjectAssignment/views/register.php" class="nav-btn">Sign Up</a>
    </div>
    <?php endif ?>
</header>