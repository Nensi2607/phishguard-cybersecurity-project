<?php
$conn = new mysqli("localhost", "root", "", "phishguard");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>