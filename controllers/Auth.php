<?php
// controllers/Auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php'; // Needed if User model needs DB passed
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session_helper.php'; // For redirect()

class Auth {
    private $userModel;

    public function __construct() {
        // If your User model constructor needs the Database object:
        // $db = new Database();
        // $this->userModel = new User($db);
        // Otherwise, if User model creates its own DB connection:
        $this->userModel = new User();
    }

    /**
     * Displays the manager/admin login form.
     * If already logged in as manager/admin, redirects to the course page.
     */
    public function showLoginForm() {
        // Check if already logged in as an authorized user
        if (isset($_SESSION['user_id']) && isset($_SESSION['role_id']) &&
            ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2))
        {
            // Already logged in, redirect to the main manager page
            header('Location: Courses.php?action=index');
            exit();
        }

        // Not logged in or not authorized role, show the login view
        require_once __DIR__ . '/../views/manager/login.php';
    }

    /**
     * Processes the manager/admin login form submission.
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
            $password = trim($_POST['password'] ?? ''); // No direct filter, use model validation

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email and Password are required.';
                header('Location: Auth.php?action=login'); // Redirect back to login form
                exit();
            }

             // Validate email format client-side and server-side
             if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $_SESSION['error'] = 'Invalid email format.';
                 header('Location: Auth.php?action=login');
                 exit();
             }


            // Attempt login using the User model
            $user = $this->userModel->login($email, $password); // Assumes login takes email/identifier

            if ($user) {
                // User found and password verified, check role
                if ($user->roleID == 1 || $user->roleID == 2) { // Admin or Manager
                    // Regenerate session ID for security
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id'] = $user->userID;
                    $_SESSION['username'] = $user->userName;
                    $_SESSION['email'] = $user->userEmail;
                    $_SESSION['role_id'] = $user->roleID;

                    // Redirect to the main course management page
                    header('Location: Courses.php?action=index');
                    exit();
                } else {
                    // User exists but does not have the required role
                    $_SESSION['error'] = 'You do not have permission to access this area.';
                     error_log("Unauthorized login attempt for user: {$user->userName} (Role: {$user->roleID})");
                     header('Location: Auth.php?action=login'); // Redirect back to login form
                     exit();
                }
            } else {
                // Login failed (user not found or incorrect password)
                $_SESSION['error'] = 'Email or Password is incorrect.';
                 error_log("Failed manager login attempt for email: {$email}");
                 header('Location: Auth.php?action=login'); // Redirect back to login form
                 exit();
            }
        } else {
             // If accessed via GET, redirect to login form display
             header('Location: Auth.php?action=login');
             exit();
        }
    }

    /**
     * Logs the manager/admin out.
     */
    public function logout() {
         // Log the logout action before destroying session data
         $userId = $_SESSION['user_id'] ?? 'Unknown';
         error_log("Manager/Admin logout initiated for user ID: " . $userId);

        // Unset all session variables
        $_SESSION = array();

        // Delete the session cookie if used
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Redirect to the manager login page
        header('Location: Auth.php?action=login');
        exit();
    }
}

// --- Routing for Auth Controller ---
$action = $_GET['action'] ?? 'login'; // Default action is login
$controller = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') { // Or use a hidden input like $_POST['action'] === 'processLogin'
        $controller->processLogin();
    } else {
         // Invalid POST action for Auth controller
         $_SESSION['error'] = 'Invalid authentication request.';
         header('Location: Auth.php?action=login');
         exit();
    }
} else { // Handle GET requests
    switch ($action) {
        case 'logout':
            $controller->logout();
            break;
        case 'login':
        default:
            $controller->showLoginForm(); // Display the login page
            break;
    }
}
