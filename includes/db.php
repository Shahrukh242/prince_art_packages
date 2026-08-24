<?php
// includes/db.php — single database connection point.
// Update these 4 values when you deploy to StackCP (Databases section will give you these).

define('DB_HOST', 'localhost');
define('DB_NAME', 'prince_art_packages');
define('DB_USER', 'root');        // change on live server
define('DB_PASS', '');            // change on live server

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
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
