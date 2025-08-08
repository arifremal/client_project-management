<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        // Debug output (check what's being received)
        error_log("Login attempt: username=$username, password=$password");
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            error_log("Login failed for username: $username");
            header('Location: login.php?error=1');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Database error. Check server logs.");
    }
}
?>