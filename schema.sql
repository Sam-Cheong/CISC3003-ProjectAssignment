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


-- 插入角色
INSERT INTO roles (roleName) VALUES 
('Admin'), 
('Manager'), 
('User');

-- 插入用户（密码已加密）
-- 明文密码：adminpass, managerpass, userpass1, userpass2
INSERT INTO users (userName, userEmail, userPwd, roleID) VALUES
('admin', 'admin@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQePz8F9jz8F9jz8F9jz8F9jz8F9jz8F9jz', 1), -- adminpass
('manager', 'manager@example.com', '$2y$10$wHST7Q7V/vR7sT.lQ.nLUu7A.TF6Ea5je0Zr2fN3vApR2t1mv6Bxu', 2), -- managerpass
('Alice', 'alice@example.com', '$2y$10$wJST7Q7V/vR7sT.lQ.nLUu7A.TF6Ea5je0Zr2fN3vApR2t1mv6Bxu', 3), -- userpass1
('Bob', 'bob@example.com', '$2y$10$3LZk7cRFS0vbqC3L7BAnPeKCqRhwLk1yuuPrD5mBjEXwTknDxOj1a', 3); -- userpass2

-- 插入课程
INSERT INTO courses (course_code, course_name, teacher, schedule) VALUES
('MATH101', 'Mathematics 101', 'Dr. Smith', 'Mon-Wed-Fri 10:00-11:00'),
('ENG202', 'English Literature', 'Prof. Johnson', 'Tue-Thu 14:00-15:30'),
('HIST303', 'World History', 'Dr. Brown', 'Mon-Wed 13:00-14:30'),
('ART404', 'Introduction to Art', 'Ms. Davis', 'Fri 09:00-12:00');

-- 插入报名记录
-- 用户 1 报名课程 1 和 2
INSERT INTO enrollments (userID, course_id, status) VALUES
(1, 1, 'active'),
(1, 2, 'pending');

-- 用户 2 报名课程 3
INSERT INTO enrollments (userID, course_id, status) VALUES
(2, 3, 'finished');

-- 用户 3 报名课程 4
INSERT INTO enrollments (userID, course_id, status) VALUES
(3, 4, 'active');

-- 用户 4 报名课程 1
INSERT INTO enrollments (userID, course_id, status) VALUES
(4, 1, 'pending');