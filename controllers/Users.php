<?php
// controllers/Users.php
\session_start();

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
     * Step 1: Handle initial registration form
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

        // 1. Validation
        if (in_array('', $data, true)) {
            flash('register', 'Please fill out all fields.', 'form-message form-message-red');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $data['username'])) {
            flash('register', 'Invalid username.', 'form-message form-message-red');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            flash('register', 'Invalid email address.', 'form-message form-message-red');
        }

        if (strlen($data['password']) < 8) {
            flash('register', 'Password must be at least 8 characters.', 'form-message form-message-red');
        }

        if ($data['password'] !== $data['repeatPwd']) {
            flash('register', 'Passwords do not match.', 'form-message form-message-red');
        }

        // 2. Uniqueness
        if ($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])) {
            flash('register', 'Username or email already taken.', 'form-message form-message-red');
            redirect('../views/register.php');
        }

        // 3. Hash and temp store
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $code   = random_int(100000, 999999);

        $_SESSION['temp_user'] = [
            'username' => $data['username'],
            'email'   => $data['email'],
            'password' => $hashed,
            'roleID'  => 3
        ];
        $_SESSION['email_verification'] = ['email' => $data['email'], 'code' => $code, 'ts' => time()];

        // 4. Send code
        if (!Mailer::sendVerification($data['email'], $code)) {
            flash('register', 'Failed to send verification email. Please try again.', 'form-message form-message-red');
            redirect('../views/register.php');
        }

        // 5. Redirect to verify
        redirect('../views/verify.php');
    }

    /**
     * Step 2: Verification form
     */
    public function verify()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $entered = intval($_POST['code'] ?? 0);
        $temp    = $_SESSION['temp_user'] ?? null;

        if (!$temp || !User::verifyEmailCode($temp['email'], $entered)) {
            flash('verify', 'Invalid or expired code. Please try again.', 'form-message form-message-red');
            redirect('../views/verify.php');
        }

        // Persist
        $this->userModel->register([
            'username' => $temp['username'],
            'email'   => $temp['email'],
            'password' => $temp['password'],
            'roleID'  => $temp['roleID']
        ]);

        unset($_SESSION['temp_user'], $_SESSION['email_verification']);

        flash('register_success', 'Registration complete! Please log in.', 'form-message form-message-green');
        redirect('../views/login.php');
    }

    /**
     * Login with username or email
     */
    public function login()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        $login    = trim($_POST['username-email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($login === '' || $password === '') {
            flash('login', 'Please fill out all fields.', 'form-message form-message-red');
            redirect('../views/login.php');
        }

        $user = $this->userModel->login($login, $password);

        if ($user) {
            flash('login', 'Login Success', 'form-message form-message-green');
            $this->createUserSession($user);
        } else {
            flash('login', 'Invalid username/email or password.', 'form-message form-message-red');
            redirect('../views/login.php');
        }
    }

    public function createUserSession($user)
    {
        // Map DB fields to session keys correctly
        $_SESSION['userID']   = $user->userID;
        $_SESSION['email']    = $user->userEmail;   // from users.userEmail column
        $_SESSION['username'] = $user->userName;    // from users.userName column
        redirect('../views/login.php');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        redirect('../index.php');
    }

    /**
     * Forgot password
     */
    public function forgot()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('forgot', 'Please enter a valid email.', 'form-message form-message-red');
            redirect('../views/forgot.php');
        }

        $user = $this->userModel->findUserByEmailOrUsername($email, $email);
        if (!$user) {
            flash('forgot', 'No account found with that email.', 'form-message form-message-red');
            redirect('../views/forgot.php');
        }

        $token   = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600);

        $this->userModel->createPasswordReset($user->userID, $token, $expires);

        $link = "http://localhost/CISC3003-ProjectAssignment/views/resetPwd.php?token={$token}";

        if (!Mailer::sendPasswordReset($email, $link)) {
            flash('forgot', 'Unable to send reset email. Try again later.', 'form-message form-message-red');
            redirect('../views/forgot.php');
        }

        flash('login', 'A reset link has been sent to your email', 'form-message form-message-orange');
        redirect('../views/login.php');
    }

    /**
     * Reset password
     */
    public function reset()
    {
        $_POST      = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        $token      = $_POST['token'] ?? '';
        $password   = trim($_POST['password'] ?? '');
        $confirmPwd = trim($_POST['confirmPwd'] ?? '');

        if (!$token || $password === '' || $confirmPwd === '') {
            flash('reset', 'Please fill out all fields.', 'form-message form-message-red');
            redirect("../views/reset.php?token={$token}");
        }
        if ($password !== $confirmPwd) {
            flash('reset', 'Passwords do not match.', 'form-message form-message-red');
            redirect("../views/reset.php?token={$token}");
        }

        $reset = $this->userModel->getPasswordResetByToken($token);
        if (!$reset || $reset->expiresAt < date('Y-m-d H:i:s')) {
            flash('reset', 'Invalid or expired token.', 'form-message form-message-red');
            redirect('../views/forgot.php');
        }

        $newHash = password_hash($password, PASSWORD_DEFAULT);
        if ($this->userModel->resetPassword($newHash, $reset->userEmail)) {
            $this->userModel->deletePasswordReset($token);
            flash('login', 'Your password has been reset. Please log in.', 'form-message form-message-orange');
            redirect('../views/login.php');
        } else {
            flash('reset', 'Failed to reset password. Try again.', 'form-message form-message-red');
            redirect("../views/reset.php?token={$token}");
        }
    }
}

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
