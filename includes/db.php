<?php
// includes/db.php — single database connection point.

define('DB_HOST', 'localhost');
define('DB_NAME', 'pap_dashboard');
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
            // Fallback try for prince_art_packages
            try {
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=prince_art_packages;charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e2) {
                error_log('DB connection failed: ' . $e->getMessage());
                die('Database connection error. Please ensure MySQL is running and pap_dashboard database is imported.');
            }
        }
    }
    return $pdo;
}
