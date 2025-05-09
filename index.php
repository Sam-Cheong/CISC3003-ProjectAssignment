<?php

$title = "Home";

require_once __DIR__ . '/helpers/session_helper.php';
include __DIR__ . '/views/layouts/header.php';
?>

    <main>
        <section id="hero">
            <div class="hero-container">
                <div class="hero-left">
                    <h1>Build every possibility for you </h1>
                    <p>Discover and develop your potential skills.</p>
                    <a href="./views/course/" class="btn-3d"><span>View Courses</span></a>
                </div>
                <div class="hero-right">
                    <img src="./public/images/hero.jpg" alt="" draggable="false">
                </div>
            </div>
        </section>
    </main>
<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>