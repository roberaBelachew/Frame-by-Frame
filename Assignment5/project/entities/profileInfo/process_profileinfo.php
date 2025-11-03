<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once '../../db_connect.php';

// Get form data
$userID = $_POST['userID'] ?? '';
$joinDate = $_POST['joinDate'] ?? '';
$username = trim($_POST['username'] ?? '');

// Validate inputs
if ($userID === '' || $joinDate === '' || $username === '') {
    header("Location: feedback_profileinfo.php?status=error&msg=Missing+required+fields");
    exit;
}

try {
    // Insert new profile info
    $stmt = $pdo->prepare("INSERT INTO ProfileInfo (userID, joinDate, username) VALUES (?, ?, ?)");
    $stmt->execute([$userID, $joinDate, $username]);

    header("Location: feedback_profileinfo.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_profileinfo.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

