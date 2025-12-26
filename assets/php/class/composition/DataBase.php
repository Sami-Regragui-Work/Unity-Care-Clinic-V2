<?php
require __DIR__ . "/../../vendor/autoload.php";

use Dotenv\Dotenv;

class DataBase
{
    private static ?self $theOnlyDB = null;
    private PDO $pdo;

    private function __construct()
    {
        /* private to prevent instanciation, 
        not abstract because we still need to instanciate it from a getter with some conditions
        */

        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        // DB_HOST
        // DB_PORT
        // DB_NAME
        // DB_USER
        // DB_PASS
        // APP_ENV
        // APP_DEBUG

        $h = $_ENV["DB_HOST"];
        $db = (string) $_ENV["DB_NAME"];
        $un = $_ENV["DB_USER"];
        $pass = $_ENV["DB_PASS"];
        $port = $_ENV["DB_PORT"];

        $dns = "mysql:host={$h};port={$port};dbname={$db};charset=utf8mb4";

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dns, $un, $pass, $opt);
            // echo "siiiir";
        } catch (PDOException $err) {
            die("connection failed: " . $err->getMessage());
        }
    }

    public static function getTheOnlyDB(): self
    {
        if (is_null(self::$theOnlyDB)) self::$theOnlyDB = new self();
        return self::$theOnlyDB;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    private function __clone() {}
}
