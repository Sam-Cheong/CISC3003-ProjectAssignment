<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($webTitle)) { echo $webTitle; } ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <ul class="nav-links">
                <li class="nav-link"><a href="/">Home</a></li>
                <li class="nav-link"><a href="#">Courses</a></li>
                <li class="nav-link"><a href="#">Profile</a></li>
                <li class="nav-link nav-btn"><a href="/app/views/register.php">Sign Up</a></li>
                <li class="nav-link nav-btn"><a href="/app/views/login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <main>