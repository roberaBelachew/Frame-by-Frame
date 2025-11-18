<?php
include __DIR__ . '/../../auth_admin.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once '../../db_connect.php';

// Get form input
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate inputs
if ($email === '' || $password === '') {
    header("Location: feedback_users.php?status=error&msg=Missing+email+or+password");
    exit;
}

try {
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the Users table
    $stmt = $pdo->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
    $stmt->execute([$email, $hashedPassword]);

    header("Location: feedback_users.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_users.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

