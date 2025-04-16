<?php
class Database {
    private static $conn;

    public static function connect() {
        if (!self::$conn) {
            $envPath = __DIR__ . '/../.env';
            if (!file_exists($envPath)) {
                die("Missing .env file!");
            }

            $config = parse_ini_file($envPath);

            $host = $config['DATABASE_HOST'];
            $dbname = $config['DATABASE_NAME'];
            $user = $config['DATABASE_USER'];
            $pass = $config['DATABASE_PASSWORD'];
            $charset = $config['DATABASE_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

            try {
                self::$conn = new PDO($dsn, $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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