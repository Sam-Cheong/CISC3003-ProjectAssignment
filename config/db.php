<?php
class Database {
    private static $host = "localhost";
    private static $db_name = "course_registration";
    private static $username = "root";
    private static $password = "";
    private static $conn;

    public static function connect() {
        if (!self::$conn) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->exec("SET NAMES utf8mb4");
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$conn;
    }

    public static function disconnect() {
        self::$conn = null;
    }
}
?>
