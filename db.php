<?php
// db.php — Database connection for SnoBlo Inc.

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$configPath = __DIR__ . '/includes/db_config.php';
if (!file_exists($configPath)) {
    throw new RuntimeException('Database configuration file is missing.');
}

$config = require $configPath;
$dbHost = $config['host'] ?? 'localhost';
$dbPort = (int)($config['port'] ?? 3306);
$dbName = $config['database'] ?? '';
$dbUser = $config['username'] ?? '';
$dbPass = $config['password'] ?? '';

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
} catch (mysqli_sql_exception $e) {
    throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
}

$conn->set_charset('utf8mb4');
?>