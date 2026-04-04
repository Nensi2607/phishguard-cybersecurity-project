<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

echo "Welcome " . $_SESSION['username'];
?>