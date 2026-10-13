<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

// Ensure user is logged in
require_login();

// Build MySQL dump using PDO (works on any shared hosting without requiring mysqldump CLI)
try {
    $pdo = get_db();
    
    // Set response headers for download
    $filename = 'prince_art_packages_backup_' . date('Y-m-d_His') . '.sql';
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo "-- Prince Art Packages Database Backup\n";
    echo "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    echo "-- Server: " . (defined('DB_HOST') ? DB_HOST : 'localhost') . "\n\n";
    echo "SET FOREIGN_KEY_CHECKS=0;\n";
    echo "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    echo "SET time_zone = \"+00:00\";\n\n";

    $tablesStmt = $pdo->query("SHOW TABLES");
    $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        // Skip log tables if desired, but here we include everything
        echo "-- --------------------------------------------------------\n";
        echo "-- Table structure for table `{$table}`\n";
        echo "-- --------------------------------------------------------\n";
        echo "DROP TABLE IF EXISTS `{$table}`;\n";

        $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
        $createRow = $createStmt->fetch(PDO::FETCH_ASSOC);
        echo $createRow['Create Table'] . ";\n\n";

        echo "-- Dumping data for table `{$table}`\n";
        $rowsStmt = $pdo->query("SELECT * FROM `{$table}`");
        $rows = $rowsStmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $columns = array_keys($rows[0]);
            $colList = implode('`, `', $columns);

            foreach ($rows as $row) {
                $vals = [];
                foreach ($row as $val) {
                    if ($val === null) {
                        $vals[] = 'NULL';
                    } else {
                        $vals[] = $pdo->quote($val);
                    }
                }
                echo "INSERT INTO `{$table}` (`{$colList}`) VALUES (" . implode(', ', $vals) . ");\n";
            }
            echo "\n";
        }
    }

    echo "SET FOREIGN_KEY_CHECKS=1;\n";
    exit;

} catch (\Throwable $e) {
    http_response_code(500);
    echo "Database export failed: " . htmlspecialchars($e->getMessage());
    exit;
}
