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
                    <img src="./public/images/hero.jpg" alt="">
                </div>
            </div>
        </section>
        <section id="popular">
            <div class="popular-container">
                <h1>Popular Courses</h1>
                <div class="popular-courses">
                    <div class="popular-course">
                        <h2>Course Name</h2>
                        <h3>Course Code</h3>
                        <p>Time</p>
                    </div>
                    <div class="popular-course">
                        <h2>Web</h2>
                    </div>
                    <div class="popular-course">
                        <h2>Web</h2>
                    </div>
                </div>
            </div>

        </section>
        <section>

        </section>
    </main>
</body>

</html>
