<?php
// includes/db.php — single database connection point.

if (file_exists(__DIR__ . '/db_config.php')) {
    require_once __DIR__ . '/db_config.php';
}

$isLocal = empty($_SERVER['HTTP_HOST']) || in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0;

if (!defined('DB_HOST')) define('DB_HOST', $isLocal ? 'localhost' : 'shareddb-i.hosting.stackcp.net');
if (!defined('DB_NAME')) define('DB_NAME', $isLocal ? 'prince_art_packages' : 'princeart-37376455');
if (!defined('DB_USER')) define('DB_USER', $isLocal ? 'root' : 'princeart-37376455');
if (!defined('DB_PASS')) define('DB_PASS', $isLocal ? '' : 'princeart-');

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]
            );
        } catch (PDOException $e) {
            // Never echo raw DB errors to the public site — log and show a generic message
            error_log('DB connection failed: ' . $e->getMessage());
            die('Something went wrong. Please try again shortly.');
        }
    }
    return $pdo;
}