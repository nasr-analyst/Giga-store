<?php
$host = 'localhost';   // use default XAMPP host/socket
$db_user = 'root';
$db_pass = '';
$dbname = 'gigastore_db';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $db_user, $db_pass, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    http_response_code(500);
    die('Database connection failed. Please ensure MySQL is running and configured.');
}
?>