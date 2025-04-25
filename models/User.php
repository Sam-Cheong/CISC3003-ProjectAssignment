<?php
// models/User.php

require_once __DIR__ . '/../helpers/session_helper.php';
// ← updated this line to your config folder:
require_once __DIR__ . '/../config/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        // Database.php should define a class Database with query(), bind(), execute(), getResult(), rowCount()
        $this->db = new Database;
    }

    /**
     * Check for an existing user by email OR username.
     */
    public function findUserByEmailOrUsername(string $email, string $username)
    {
        $this->db->query(
            'SELECT * FROM users 
             WHERE userName = :username 
                OR userEmail    = :email'
        );
        $this->db->bind(':username', $username);
        $this->db->bind(':email',    $email);
        $row = $this->db->getResult();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Insert a new user (username, email, hashed password).
     */
    public function register(array $data): bool
    {
        $this->db->query(
            'INSERT INTO users 
             (userName, userEmail, userPwd, roleID)
           VALUES 
             (:username, :email, :password, :roleID)'
        );
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email',    $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':roleID',   $data['roleID'], PDO::PARAM_INT);

        return $this->db->execute();
    }


    /**
     * Attempt login by username/email + plain password.
     */
    public function login(string $login, string $pwd)
    {
        // This will SELECT ... WHERE userName = :username OR userEmail = :email
        $row = $this->findUserByEmailOrUsername($login, $login);
        if (! $row) {
            return false;
        }

        // Note: use ->userPwd, not ->password
        if (password_verify($pwd, $row->userPwd)) {
            return $row;
        }

        return false;
    }

    /**
     * Verify the 6‑digit code stored in session.
     */
    public static function verifyEmailCode(string $email, int $code): bool
    {
        if (empty($_SESSION['email_verification'])) {
            return false;
        }
        $v = $_SESSION['email_verification'];
        if (
            $v['email'] === $email
            && $v['code']  === $code
            && (time() - $v['ts']) < 600
        ) {
            unset($_SESSION['email_verification']);
            return true;
        }
        return false;
    }

    /**
     * Create a password_reset entry.
     */
    public function createPasswordReset(int $userID, string $token, string $expiresAt): bool
    {
        $sql = 'INSERT INTO password_resets (userID, token, expiresAt)
                VALUES (:uid, :token, :exp)';
        $this->db->query($sql);
        $this->db->bind(':uid',   $userID);
        $this->db->bind(':token', $token);
        $this->db->bind(':exp',   $expiresAt);
        return $this->db->execute();
    }

    /**
     * Fetch a reset record (with user email) by token.
     */
    public function getPasswordResetByToken(string $token)
    {
        $sql = 'SELECT pr.*, u.userEmail
                FROM password_resets pr
                JOIN users u ON pr.userID = u.userID
                WHERE pr.token = :token';
        $this->db->query($sql);
        $this->db->bind(':token', $token);
        return $this->db->getResult();  // returns object or false
    }

    /**
     * Delete a reset record.
     */
    public function deletePasswordReset(string $token): bool
    {
        $this->db->query('DELETE FROM password_resets WHERE token = :token');
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }

    /**
     * Update the user’s password.
     */
    public function resetPassword(string $newPwdHash, string $email): bool
    {
        $this->db->query(
            'UPDATE users 
             SET userPwd = :pwd 
             WHERE userEmail = :email'
        );
        $this->db->bind(':pwd',   $newPwdHash);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }
}
