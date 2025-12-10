
<?php
// Use TCP (127.0.0.1) to avoid socket issues when localhost resolves to socket
$localhost = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$dbname = 'gigastore_db';

// Make mysqli throw exceptions so calling code can handle connection errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($localhost, $db_user, $db_pass, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    // rethrow so includes fail fast
    throw $e;
}
?>