<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function closeConnection() {
    global $conn;
    $conn->close();
}

?>
