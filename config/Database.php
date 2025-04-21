<?php
// config/Database.php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();
}

class Database {
    private string $host;
    private string $user;
    private string $password;
    private string $dbname;
    private string $charset;
    private PDO    $dbh;
    private PDOStatement $stmt;
    private string $error;

    public function __construct() {
        $this->host     = $_ENV['DB_HOST']     ?? '127.0.0.1';
        $this->user     = $_ENV['DB_USER']     ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
        $this->dbname   = $_ENV['DB_NAME']     ?? 'test';
        $this->charset  = $_ENV['DB_CHARSET']  ?? 'utf8mb4';

        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Database connection error: ' . htmlspecialchars($this->error);
            exit;
        }
    }

    public function query(string $sql): void {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind(string $param, $value, $type = null): void {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(): bool {
        return $this->stmt->execute();
    }

    /**
     * Fetch all results as objects.
     */
    public function getResults(): array {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Fetch a single result as object.
     */
    public function getResult(): mixed {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get the number of affected rows.
     */
    public function rowCount(): int {
        return $this->stmt->rowCount();
    }
}
