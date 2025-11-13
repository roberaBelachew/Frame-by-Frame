<?php
include __DIR__ . '/../../auth_admin.php';

// Enable error display (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once '../../db_connect.php';

// Retrieve form data
$movieID = $_POST['movieID'] ?? '';
$tagID = $_POST['tagID'] ?? '';

// Validate inputs
if ($movieID === '' || $tagID === '') {
    header("Location: feedback_movietags.php?status=error&msg=Missing+Movie+or+Tag+ID");
    exit;
}

try {
    // Insert the relationship into MovieTags table
    $stmt = $pdo->prepare("INSERT INTO MovieTags (movieID, tagID) VALUES (?, ?)");
    $stmt->execute([$movieID, $tagID]);

    header("Location: feedback_movietags.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_movietags.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

