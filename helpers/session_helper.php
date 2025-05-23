<?php
// helpers/session_helper.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Flash a one‑time message to the user.
 *
 * Usage:
 *   // To set a flash message:
 *   flash('register', 'Please fill out all fields.');
 *
 *   // To display and clear the message:
 *   flash('register');
 *
 * @param string $name    Key under which message is stored
 * @param string $message (optional) Message to set; if omitted, will display & clear any existing message
 * @param string $class   CSS class to apply when rendering
 */
function flash(string $name = '', string $message = '', string $class = 'form-message form-message-red'): void
{
    if (empty($name)) {
        return;
    }

    // Set message
    if (!empty($message) && empty($_SESSION[$name])) {
        $_SESSION[$name]         = $message;
        $_SESSION[$name . '_class'] = $class;
    }
    // Display & clear message
    else if (empty($message) && !empty($_SESSION[$name])) {
        $msgClass = $_SESSION[$name . '_class'] ?? $class;
        echo '<div class="' . htmlspecialchars($msgClass) . '">'
            . htmlspecialchars($_SESSION[$name])
            . '</div>';
        unset($_SESSION[$name], $_SESSION[$name . '_class']);
    }
}

/**
 * Redirect to a new location and terminate script.
 *
 * @param string $location URL or path to redirect to
 */
function redirect(string $location): void
{
    header('Location: ' . $location);
    exit();
}

// helpers/session_helper.php
function isLoggedIn() {
    return isset($_SESSION['userID']);
}

function isPermitee($permiteeID) {
    return $permiteeID === $_SESSION['roleID'];
}

function verifyPermitee(int $permiteeID, ?int $anotherPermiteeID = null) {
    if (!isLoggedIn()) {
        redirect('/CISC3003-ProjectAssignment/views/login.php');
    } elseif ($_SESSION['roleID'] != $permiteeID && $_SESSION['roleID'] != $anotherPermiteeID) {
        flash('Access Denied', 'You do not have permission to access this page.');
        exit();
    }
}
