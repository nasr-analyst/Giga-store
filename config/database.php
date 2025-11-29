<?php echo "Test File for database configuration\n"; 




$localhost="localhost";
define('username', 'root');
define('password','');
$dbname='gigastore_db';

$conn=new mysqli($localhost,username,password,$dbname);

if($conn->connect_error)
{
    die("connection failed".$conn->connect_error);
}
echo "connected successfully";


?>




























?>