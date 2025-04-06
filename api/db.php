<?php
$servername = "Localhost";
$username = "logi_Moris"; // Your database username
$password = "8ozM-u+E7!Gq1Al0"; // Your database password
$dbname = "logi_Movers"; // Your database name
$socket = '/run/mysqld/mysqld.sock';

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname, null, $socket);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>