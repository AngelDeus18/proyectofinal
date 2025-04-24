<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Problemas de conexión: " . $conn->connect_error);
}

?>