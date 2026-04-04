<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

// Fetch user
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Check account lock (30 minutes)
    if ($user['login_attempts'] >= 3 && strtotime($user['last_attempt_time']) > strtotime("-30 minutes")) {
        echo "Account locked. Try after 30 minutes.";
        exit();
    }

    // Verify password
    if (password_verify($password, $user['password'])) {

        // Reset attempts
        $conn->query("UPDATE users SET login_attempts = 0 WHERE username = '$username'");

        $_SESSION['username'] = $username;
        header("Location: dashboard.php");

    } else {
        // Increase attempts
        $conn->query("UPDATE users SET login_attempts = login_attempts + 1, last_attempt_time = NOW() WHERE username = '$username'");
        echo "Invalid Password!";
    }

} else {
    echo "User not found!";
}
?>