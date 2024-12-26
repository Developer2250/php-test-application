<?php

$host = 'localhost';
$username = 'root';
$password = 'admin@123';
$database = 'test_PHP';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
