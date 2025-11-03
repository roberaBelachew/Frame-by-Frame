<?php
include __DIR__ . '/../../auth_admin.php';

// Show errors for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once '../../db_connect.php';

// Get form data
$type = $_POST['type'] ?? '';
$value = trim($_POST['value'] ?? '');

// Validate required fields
if ($type === '' || $value === '') {
    header("Location: feedback_tag.php?status=error&msg=Missing+type+or+value");
    exit;
}

try {
    // Insert into Tags table
    $stmt = $pdo->prepare("INSERT INTO Tags (type, value) VALUES (?, ?)");
    $stmt->execute([$type, $value]);

    // Redirect on success
    header("Location: feedback_tag.php?status=success");
    exit;
} catch (Exception $e) {
    // Redirect on error
    header("Location: feedback_tag.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

