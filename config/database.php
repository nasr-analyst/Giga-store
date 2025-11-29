<?php echo "Test File for database configuration\n";




// Use TCP (127.0.0.1) to avoid socket "No such file or directory" when localhost resolves to a socket
$localhost = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$dbname = 'gigastore_db';

// Make mysqli throw exceptions so calling code can handle connection errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($localhost, $db_user, $db_pass, $dbname);
    // optional: set charset if needed
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // log and rethrow so including scripts see the failure
    error_log('Database connection error: ' . $e->getMessage());
    throw $e;
}
echo "connected successfully";


?>




























?>