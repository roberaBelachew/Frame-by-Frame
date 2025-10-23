<?php
// Show errors during testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
require_once '../../db_connect.php';

// Collect form inputs
$userID = $_POST['userID'] ?? '';
$movieID = $_POST['movieID'] ?? '';
$rating = $_POST['rating'] ?? '';
$flags = $_POST['flags'] ?? 0;

// Validate required fields
if ($userID === '' || $movieID === '' || $rating === '') {
    header("Location: feedback_reviews.php?status=error&msg=Missing+required+fields");
    exit;
}

try {
    // Insert into Reviews table
    $stmt = $pdo->prepare("INSERT INTO Reviews (userID, movieID, rating, flags) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userID, $movieID, $rating, $flags]);

    header("Location: feedback_reviews.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_reviews.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

