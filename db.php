<?php
// db.php — Database connection for SnoBlo Inc.

define('DB_HOST', 'localhost');
define('DB_USER', 'xue43_local');
define('DB_PASS', 'Y&4qwf<M');
define('DB_NAME', 'xue43_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>
