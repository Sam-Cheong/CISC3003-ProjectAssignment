-- 先創建 roles 表 (若不存在)
CREATE TABLE IF NOT EXISTS roles (
  roleID INT PRIMARY KEY AUTO_INCREMENT,
  roleName VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 修正後的 users 表
CREATE TABLE IF NOT EXISTS users (
  userID     INT            PRIMARY KEY AUTO_INCREMENT,
  userName   VARCHAR(255)   NOT NULL,
  userEmail  VARCHAR(255)   NOT NULL UNIQUE,
  userPwd    VARCHAR(255)   NOT NULL,
  roleID     INT            NOT NULL COMMENT 'FK → roles.roleID',
  createdAt  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP 
                              ON UPDATE CURRENT_TIMESTAMP,
  INDEX ix_users_role (roleID),  -- 修正索引語法
  CONSTRAINT fk_users_roles 
    FOREIGN KEY (roleID) REFERENCES roles(roleID)
      ON DELETE RESTRICT 
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 修正後的 password_resets 表
CREATE TABLE IF NOT EXISTS password_resets (
  resetID     INT            NOT NULL AUTO_INCREMENT PRIMARY KEY,
  userID      INT            NOT NULL COMMENT 'FK → users.userID',
  token       VARCHAR(255)   NOT NULL UNIQUE,
  expiresAt   DATETIME       NOT NULL,
  createdAt   DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX ix_pr_user (userID),  -- 修正索引語法
  CONSTRAINT fk_pr_user 
    FOREIGN KEY (userID) REFERENCES users(userID)
      ON DELETE CASCADE 
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS courses (
  course_id INT AUTO_INCREMENT PRIMARY KEY,
  course_code VARCHAR(50) UNIQUE NOT NULL,
  course_name VARCHAR(100) NOT NULL,
  teacher VARCHAR(100) NOT NULL,
  schedule VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (userName, userEmail, userPwd, roleID) 
VALUES ('manager', 'manager@example.com', '123456', 2);

