<?php
// index.php
session_start();
require_once __DIR__ . '/helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CISC3003 | Home </title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/style.css">
</head>

<body>

    <?php require_once './views/layouts/header.php' ?>

<<<<<<< HEAD
    <main>
        <section id="hero">
            <h1></h1>
        </section>
        <section>

        </section>
    </main>
=======
            <!-- Logout form -->
            <form action="./controllers/Users.php" method="get" style="margin-top: 20px;">
                <input type="hidden" name="q" value="logout">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <h2>Please log in or register</h2>
            <p class="alt-action">
                <a href="./views/login.php">Log in</a>
                <br>
                <a href="./views/register.php">Register</a>
            </p>
        <?php endif; ?>
    </div>
    <script src="./public/js/flash.js"></script>
>>>>>>> 584fc42eda2ff353330a5e1de8a3c588107e54f0
</body>

</html>