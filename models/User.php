<?php
// models/User.php

require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../config/Database.php';

class User
{
    private $db;

    public function __construct()
    {
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
                OR userEmail = :email'
        );
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $row = $this->db->getResult();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    /**
     * Insert a new user (username, email, hashed password).
     * By default, registers as a regular user (roleID = 3)
     */
    public function register(array $data): bool
    {
        // 如果没有指定角色ID，默认设为3（普通用户）
        if (!isset($data['roleID'])) {
            $data['roleID'] = 3; // 普通用户
        }
        
        $this->db->query(
            'INSERT INTO users 
             (userName, userEmail, userPwd, roleID)
           VALUES 
             (:username, :email, :password, :roleID)'
        );
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':roleID', $data['roleID'], PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Attempt login by username/email + plain password.
     */
    public function login(string $login, string $pwd)
    {
        // This will SELECT ... WHERE userName = :username OR userEmail = :email
        $row = $this->findUserByEmailOrUsername($login, $login);
        if (!$row) {
            return false;
        }

        // 验证密码 (使用password_verify)
        if (password_verify($pwd, $row->userPwd)) {
            return $row;
        }
        
        // 尝试简单密码匹配（适用于测试环境）
        if ($pwd === $row->userPwd) {
            return $row;
        }

        return false;
    }

    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE userID = :id');
        $this->db->bind(':id', $id);
        return $this->db->getResult();
    }

    /**
     * Check if user has manager or admin privileges
     */
    public function isManager($userId) {
        $this->db->query('SELECT roleID FROM users WHERE userID = :id');
        $this->db->bind(':id', $userId);
        $user = $this->db->getResult();
        
        if($user && ($user->roleID == 1 || $user->roleID == 2)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get user role name
     */
    public function getUserRole($userId) {
        $this->db->query('SELECT r.roleName 
                          FROM users u 
                          JOIN roles r ON u.roleID = r.roleID 
                          WHERE u.userID = :id');
        $this->db->bind(':id', $userId);
        $result = $this->db->getResult();
        
        return $result ? $result->roleName : null;
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
     * Update the user's password.
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
