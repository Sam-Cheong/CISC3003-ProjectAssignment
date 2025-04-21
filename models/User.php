<?php
// models/User.php

require_once __DIR__ . '/../helpers/session_helper.php';
// ← updated this line to your config folder:
require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;

    public function __construct() {
        // Database.php should define a class Database with query(), bind(), execute(), getResult(), rowCount()
        $this->db = new Database;
    }

    /**
     * Check for an existing user by email OR username.
     */
    public function findUserByEmailOrUsername(string $email, string $username) {
        $this->db->query(
            'SELECT * FROM users 
             WHERE username = :username 
                OR email    = :email'
        );
        $this->db->bind(':username', $username);
        $this->db->bind(':email',    $email);
        $row = $this->db->getResult();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Insert a new user (username, email, hashed password).
     */
    public function register(array $data): bool {
        $this->db->query(
            'INSERT INTO users (username, email, password)
             VALUES (:username, :email, :password)'
        );
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email',    $data['email']);
        $this->db->bind(':password', $data['password']);
        return $this->db->execute();
    }

    /**
     * Attempt login by username/email + plain password.
     */
    public function login(string $login, string $pwd) {
        $row = $this->findUserByEmailOrUsername($login, $login);
        if (! $row) {
            return false;
        }
        return password_verify($pwd, $row->password)
             ? $row
             : false;
    }

    /**
     * Update a user's password by email.
     */
    public function resetPassword(string $newPwdHash, string $email): bool {
        $this->db->query(
            'UPDATE users 
             SET password = :password 
             WHERE email = :email'
        );
        $this->db->bind(':password', $newPwdHash);
        $this->db->bind(':email',    $email);
        return $this->db->execute();
    }

    /**
     * Verify the 6‑digit code stored in session.
     */
    public static function verifyEmailCode(string $email, int $code): bool {
        if (empty($_SESSION['email_verification'])) {
            return false;
        }
        $v = $_SESSION['email_verification'];
        if ($v['email'] === $email
         && $v['code']  === $code
         && (time() - $v['ts']) < 600) {
            unset($_SESSION['email_verification']);
            return true;
        }
        return false;
    }
}
