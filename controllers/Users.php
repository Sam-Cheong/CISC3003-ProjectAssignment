<?php
// controllers/Users.php

session_start();

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Mailer.php';
require_once __DIR__ . '/../helpers/session_helper.php';

class Users
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

    /**
     * Step 1: Handle initial registration form:
     *   - validate input
     *   - check uniqueness
     *   - hash password
     *   - generate & send verification code
     *   - store temp data + code in session
     */
    public function register()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'repeatPwd' => trim($_POST['repeatPwd'] ?? '')
        ];

        // 1. Basic validation
        if (in_array('', $data, true)) {
            flash("register", "Please fill out all fields.");
            redirect("../views/register.php");
        }
        if (!preg_match("/^[a-zA-Z0-9]+$/", $data['username'])) {
            flash("register", "Invalid username.");
            redirect("../views/register.php");
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            flash("register", "Invalid email address.");
            redirect("../views/register.php");
        }
        if (strlen($data['password']) < 6) {
            flash("register", "Password must be at least 6 characters.");
            redirect("../views/register.php");
        }
        if ($data['password'] !== $data['repeatPwd']) {
            flash("register", "Passwords do not match.");
            redirect("../views/register.php");
        }

        // 2. Uniqueness check
        if ($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])) {
            flash("register", "Username or email already taken.");
            redirect("../views/register.php");
        }

        // 3. Hash password
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);

        // 4. Generate verification code
        $code = random_int(100000, 999999);

        // 5. Store temp registration + code in session
        $_SESSION['temp_user'] = [
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $hashed,
            'roleID'   => 3
        ];
        $_SESSION['email_verification'] = [
            'email' => $data['email'],
            'code'  => $code,
            'ts'    => time()
        ];

        // 6. Send email
        if (!Mailer::sendVerification($data['email'], $code)) {
            flash("register", "Failed to send verification email. Please try again.");
            redirect("../views/register.php");
        }

        // 7. Redirect to verification form
        redirect("../views/verify.php");
    }

    /**
     * Step 2: Handle verification form POST
     *   - check code + expiry
     *   - on success, create the real user
     */
    public function verify()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $enteredCode = intval($_POST['code'] ?? 0);
        $temp        = $_SESSION['temp_user'] ?? null;

        if (!$temp || !User::verifyEmailCode($temp['email'], $enteredCode)) {
            flash("verify", "Invalid or expired code. Please try again.");
            redirect("../views/verify.php");
        }

        // All good: persist user in database
        $this->userModel->register([
            'username' => $temp['username'],
            'email'    => $temp['email'],
            'password' => $temp['password'],
            'roleID'   => $temp['roleID']
        ]);


        // Cleanup session
        unset($_SESSION['temp_user'], $_SESSION['email_verification']);

        flash("register_success", "Registration complete! Please log in.");
        redirect("../views/login.php");
    }

    /**
     * Existing login flow (unchanged)
     */
    public function login()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        $login    = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // 1) Check both fields
        if ($login === '' || $password === '') {
            flash('login', 'Please fill out all fields.');
            redirect('../views/login.php');
        }

        // 2) Attempt login by either username or email
        $user = $this->userModel->login($login, $password);

        if ($user) {
            // 3) Success
            $this->createUserSession($user);
        } else {
            // 4) Failure
            flash('login', 'Invalid username/email or password.');
            redirect('../views/login.php');
        }
    }

    public function createUserSession($user)
    {
        $_SESSION['userID']   = $user->userID;
        $_SESSION['email']    = $user->email;
        $_SESSION['username'] = $user->username;
        redirect("../index.php");
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        redirect("../index.php");
    }

    /**
     * Step A: Handle “Forgot Password” form submission.
     */
    public function forgot()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('forgot', 'Please enter a valid email.');
            redirect('../views/forgot.php');
        }

        // Does user exist?
        $user = $this->userModel->findUserByEmailOrUsername($email, $email);
        if (!$user) {
            flash('forgot', 'No account found with that email.');
            redirect('../views/forgot.php');
        }

        // Generate token + expiry
        $token   = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600);  // 1 hour

        // Store in DB
        $this->userModel->createPasswordReset($user->userID, $token, $expires);

        // Send reset email
        // $appUrl = rtrim($_ENV['APP_URL'] ?? '/');
        $link   = "http://localhost/CISC3003-ProjectAssignment/views/resetPwd.php?token={$token}";

        if (!Mailer::sendPasswordReset($email, $link)) {
            flash('forgot', 'Unable to send reset email. Try again later.');
            redirect('../views/forgot.php');
        }

        flash('forgot', 'A reset link has been sent to your email.');
        redirect('../views/login.php');
    }

    /**
     * Step B: Handle “Reset Password” form submission.
     */
    public function reset()
    {
        $_POST      = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        $token      = $_POST['token'] ?? '';
        $password   = trim($_POST['password']   ?? '');
        $confirmPwd = trim($_POST['confirmPwd'] ?? '');

        if (!$token || empty($password) || empty($confirmPwd)) {
            flash('reset', 'Please fill out all fields.');
            redirect("../views/reset.php?token={$token}");
        }
        if ($password !== $confirmPwd) {
            flash('reset', 'Passwords do not match.');
            redirect("../views/reset.php?token={$token}");
        }

        // Lookup the reset record
        $reset = $this->userModel->getPasswordResetByToken($token);
        if (!$reset || $reset->expiresAt < date('Y-m-d H:i:s')) {
            flash('reset', 'Invalid or expired token.');
            redirect('../views/forgot.php');
        }

        // Update the user’s password
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        if ($this->userModel->resetPassword($newHash, $reset->userEmail)) {
            // Clean up
            $this->userModel->deletePasswordReset($token);

            flash('login', 'Your password has been reset. Please log in.');
            redirect('../views/login.php');
        } else {
            flash('reset', 'Failed to reset password. Try again.');
            redirect("../views/reset.php?token={$token}");
        }
    }
}

// === bootstrap ===
$init = new Users();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['type'] ?? '') {
        case 'register':
            $init->register();
            break;
        case 'verify':
            $init->verify();
            break;
        case 'login':
            $init->login();
            break;
        case 'forgot':
            $init->forgot();
            break;
        case 'reset':
            $init->reset();
            break;
        default:
            redirect('../index.php');
    }
} else {
    if (($_GET['q'] ?? '') === 'logout') {
        $init->logout();
    } else {
        redirect('../index.php');
    }
}
