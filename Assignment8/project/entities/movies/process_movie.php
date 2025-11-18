<?php
include __DIR__ . '/../../auth_admin.php';
// Show all errors for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once '../../db_connect.php';

// Get form data safely
$title = trim($_POST['title'] ?? '');
$releaseYear = trim($_POST['releaseYear'] ?? '');
$description = trim($_POST['description'] ?? '');

// Check required fields
if ($title === '' || $releaseYear === '') {
    header("Location: feedback_movie.php?status=error&msg=Missing+title+or+releaseYear");
    exit;
}

try {
    // Prepare and execute SQL
    $stmt = $pdo->prepare("
        INSERT INTO Movies (title, releaseYear, description)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$title, $releaseYear, $description]);

    // Redirect to feedback page on success
    header("Location: feedback_movie.php?status=success");
    exit;

} catch (Exception $e) {
    // Redirect with error message if something goes wrong
    header("Location: feedback_movie.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>


