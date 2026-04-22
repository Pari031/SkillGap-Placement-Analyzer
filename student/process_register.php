<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Note: Removed branch, age, gender, 10th%, 12th% as per new schema
    $sql = "INSERT INTO students (name, roll_number, year, cgpa, passing_year, email, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            $_POST['name'], 
            $_POST['roll_number'], 
            $_POST['year'], 
            $_POST['cgpa'], 
            $_POST['passing_year'], 
            $_POST['email'], 
            $_POST['password']
        ]);
        header("Location: login.php?success=Account created! Please login.");
    } catch (PDOException $e) {
        // Handle duplicate emails or roll numbers
        header("Location: register.php?error=Registration failed. Email or Roll Number may already exist.");
    }
    exit();
}
?>