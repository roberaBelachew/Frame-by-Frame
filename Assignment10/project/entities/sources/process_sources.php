<?php
include __DIR__ . '/../../auth_admin.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once '../../db_connect.php';

// Get the selected source type
$type = $_POST['type'] ?? '';

if ($type === '') {
    header("Location: feedback_sources.php?status=error&msg=Missing+Source+Type");
    exit;
}

try {
    // Insert new source into the Sources table
    $stmt = $pdo->prepare("INSERT INTO Sources (type) VALUES (?)");
    $stmt->execute([$type]);

    header("Location: feedback_sources.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_sources.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

