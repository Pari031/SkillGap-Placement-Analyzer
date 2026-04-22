<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function checkLogin($role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
        // Redirect to main root index if not logged in or wrong role
        header("Location: ../index.php");
        exit();
    }
}
?>