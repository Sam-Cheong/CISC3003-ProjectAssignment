<?php
$title = "About";
require_once __DIR__ . '/../helpers/session_helper.php';
include __DIR__ . '/layouts/header.php';
?>

<main id="about">
    <section class="about-section">
        <h1>About Our Course Enrollment System</h1>

        <h2>Background</h2>
        <p>
            In Macau, the number of education centers is increasing rapidly as parents and educators place more emphasis on fostering children's interests and talents.
            Recognizing these trends, our project has been developed as a comprehensive Course Enrollment System to meet the specific needs of these educational centers.
        </p>

        <h2>Objectives</h2>
        <p>
            Our system provides a single-stop portal enabling educational centers to publish and advertise their courses. Key objectives include:
        </p>
        <ul>
            <li><strong>Enhance Efficiency:</strong> Streamline the entire enrollment process to reduce administrative overhead and eliminate manual processing.</li>
            <li><strong>Improve Visibility:</strong> Allow centers to reach a wider audience by showcasing detailed course information in a user-friendly portal.</li>
            <li><strong>Centralize Management:</strong> Enable centers to manage enrollments, monitor customer orders, and maintain financial records in one integrated system.</li>
            <li><strong>Elevate User Experience:</strong> Provide an intuitive, responsive interface for both administrators and customers across various devices.</li>
        </ul>

        <h2>Types of Users</h2>
        <p>
            The system supports multiple types of users:
        </p>
        <ul>
            <li><strong>System Administrator:</strong> Manages user accounts, roles, and permissions.</li>
            <li><strong>Courses Manager:</strong> Creates, updates, and deletes course offerings, monitors enrollment statistics, and downloads payment records.</li>
            <li><strong>Customer:</strong> Includes both Guest (unregistered) users and Registered users who can search, view detailed course information, and enroll in courses.</li>
        </ul>

        <h2>Requirements & Modules</h2>
        <p>
            To build a robust and scalable solution, our system integrates several key modules:
        </p>
        <ul>
            <li><strong>Courses Management System:</strong> Manages all aspects of course data – from creation to real-time updates.</li>
            <li><strong>Account Management:</strong> Supports secure user registration, login, and profile maintenance.</li>
            <li><strong>Ledger System:</strong> Provides detailed records of financial transactions.</li>
            <li><strong>Search & Filter:</strong> Equips users with advanced search tools to quickly locate desired course offerings.</li>
        </ul>

        <h2>Technology Stack</h2>
        <p>
            Our platform is developed using:
        </p>
        <ul>
            <li><strong>Frontend:</strong> HTML, CSS, JavaScript (with Tailwind CSS for styling).</li>
            <li><strong>Backend:</strong> PHP with an MVC architecture, communicating with a MySQL database, hosted on an XAMPP platform.</li>
            <li><strong>Database & Server:</strong> MySQL via XAMPP's Apache server.</li>
            <li><strong>Others:</strong> Composer, PHPMailer, GitHub, and Live Server for continuous development and testing.</li>
        </ul>

        <h2>Our Team</h2>
        <p>
            Developed by Team05, this project is a collaborative effort among:
        </p>
        <ul>
            <li><strong>Pair 02:</strong> DC226373 Cheong Iok Cheong, DC226696 Chan Hin Ieong</li>
            <li><strong>Pair 03:</strong> DC226877 Wong Chak Wun, DC226735 Ng Cheok In</li>
        </ul>

        <h2>Contact Us</h2>
        <p>
            For feedback, questions, or assistance, please contact us at 
            <a href="mailto:support@example.com">support@example.com</a>.
        </p>
    </section>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>