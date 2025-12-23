<?php
require __DIR__ . "/../../vendor/autoload.php";

use Dotenv\Dotenv;

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
$db = $_ENV["DB_NAME"];
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
    $dbLink = new PDO($dns, $un, $pass, $opt);
    // echo "siiiir";
} catch (PDOException $err) {
    die("connection failed: " . $err->getMessage());
}
