<?php
require_once '../includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        // Create session variables
        $_SESSION['user_id'] = $user['student_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = 'student';
        
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: login.php?error=Invalid email or password.");
        exit();
    }
}
?>