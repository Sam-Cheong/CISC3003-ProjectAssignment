-- DATABASE initialization
CREATE DATABASE IF NOT EXISTS course_enrollment;

USE course_enrollment;

CREATE TABLE
    IF NOT EXISTS roles (
        roleID INT PRIMARY KEY AUTO_INCREMENT,
        roleName VARCHAR(50) NOT NULL UNIQUE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE
    IF NOT EXISTS users (
        userID INT PRIMARY KEY AUTO_INCREMENT,
        userName VARCHAR(255) NOT NULL,
        userEmail VARCHAR(255) NOT NULL UNIQUE,
        userPwd VARCHAR(255) NOT NULL,
        roleID INT NOT NULL COMMENT 'FK → roles.roleID',
        createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX ix_users_role (roleID), -- 修正索引語法
        CONSTRAINT fk_users_roles FOREIGN KEY (roleID) REFERENCES roles (roleID) ON DELETE RESTRICT ON UPDATE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE
    IF NOT EXISTS password_resets (
        resetID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        userID INT NOT NULL COMMENT 'FK → users.userID',
        token VARCHAR(255) NOT NULL UNIQUE,
        expiresAt DATETIME NOT NULL,
        createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX ix_pr_user (userID),
        CONSTRAINT fk_pr_user FOREIGN KEY (userID) REFERENCES users (userID) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE
    IF NOT EXISTS courses (
        course_id INT AUTO_INCREMENT PRIMARY KEY,
        course_code VARCHAR(50) UNIQUE NOT NULL,
        course_name VARCHAR(100) NOT NULL,
        teacher VARCHAR(100) NOT NULL,
        schedule VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS enrollments (
        enrollmentID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT NOT NULL COMMENT 'FK -> users.userID',
        course_id INT NOT NULL COMMENT 'FK -> courses.course_id',
        status ENUM('pending', 'active', 'finished') NOT NULL DEFAULT 'pending',
        -- 'status' ENUM('pending', 'confirmed', 'active', 'finished') NOT NULL DEFAULT 'pending',
        -- fee INT NOT NULL
        createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        -- cancellAt DATETIME DEFAULT NULL
        -- paidAt DATETIME DEFAULT NULL
        CONSTRAINT fk_enroll_user FOREIGN KEY (userID) REFERENCES users (userID) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_enroll_course FOREIGN KEY (course_id) REFERENCES courses (course_id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;


-- Test Case
-- INSERT INTO users (userName, userEmail, userPwd, roleID) 
--  VALUES ('manager', 'manager@example.com', '123456', 2);
-- 插入管理员用户（roleID = 2），密码为 "managerpass"
-- INSERT INTO users (userName, userEmail, userPwd, roleID)
-- VALUES ('manager', 'manager@example.com', '$2y$10$wHST7Q7V/vR7sT.lQ.nLUu7A.TF6Ea5je0Zr2fN3vApR2t1mv6Bxu', 2);

-- -- 插入普通用户 Alice（roleID = 3），密码为 "password123"
-- INSERT INTO users (userName, userEmail, userPwd, roleID)
-- VALUES ('Alice', 'alice@example.com', '$2y$10$wJST7Q7V/vR7sT.lQ.nLUu7A.TF6Ea5je0Zr2fN3vApR2t1mv6Bxu', 3);

-- -- 插入普通用户 Bob（roleID = 3），密码为 "mypassword"
-- INSERT INTO users (userName, userEmail, userPwd, roleID)
-- VALUES ('Bob', 'bob@example.com', '$2y$10$3LZk7cRFS0vbqC3L7BAnPeKCqRhwLk1yuuPrD5mBjEXwTknDxOj1a', 3);


-- -- 示例 1：用户 1（manager）报名课程 2 (Math)，状态设置为 active
-- INSERT INTO enrollments (userID, course_id, status) 
-- VALUES (1, 2, 'active');

-- -- 示例 3：用户 3（bob）报名课程 4 (History)，状态设置为 finished
-- INSERT INTO enrollments (userID, course_id, status)
-- VALUES (3, 4, 'finished');

-- -- 示例 5：用户 1（manager）报名课程 6 (Art)，使用默认的 pending 状态
-- INSERT INTO enrollments (userID, course_id)
-- VALUES (1, 6);