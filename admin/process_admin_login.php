<?php
require_once '../includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // trim() removes any accidental spaces from the beginning or end
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // 1. Check if the admin exists
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        // 2. Verify the hashed password
        if ($admin && password_verify($password, $admin['password'])) {
            // Regeneration of session ID is good for security (prevents session fixation)
            session_regenerate_id();
            
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['name'] = $admin['name'];
            $_SESSION['role'] = 'admin';
            
            header("Location: students.php");
            exit();
        } else {
            // Redirect back with an error message
            header("Location: login.php?error=Invalid credentials.");
            exit();
        }
    } catch (PDOException $e) {
        // Log error and redirect
        header("Location: login.php?error=Database error occurred.");
        exit();
    }
} else {
    // If someone tries to access this file directly via URL
    header("Location: login.php");
    exit();
}
?>