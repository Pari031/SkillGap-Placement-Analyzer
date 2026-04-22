<?php
require_once 'includes/db_connect.php';

// This will set the password for admin@niet.co.in to exactly 'niet'
$new_hash = password_hash('niet', PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = 'admin@niet.co.in'");
    $stmt->execute([$new_hash]);
    echo "Success! Admin password has been reset to: niet<br>";
    echo "New Hash in DB is: " . $new_hash;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>