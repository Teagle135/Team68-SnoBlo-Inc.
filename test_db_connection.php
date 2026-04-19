<?php
// Test database connection with detailed error output
$config = require __DIR__ . '/includes/db_config.php';

echo "Testing connection to: " . $config['host'] . "\n";
echo "Database: " . $config['database'] . "\n";
echo "Username: " . $config['username'] . "\n";
echo "Port: " . $config['port'] . "\n\n";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database'],
        $config['port']
    );
    echo "SUCCESS: Connected to MySQL!\n";
    echo "Server info: " . $conn->server_info . "\n";
    $conn->close();
} catch (mysqli_sql_exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}
?>
