<?php
require_once '../../db_connect.php';

$userID = $_POST['userID'] ?? null;
$reviewID = $_POST['reviewID'] ?? null;
$content = trim($_POST['content'] ?? '');

if (!$userID || !$reviewID || $content === '') {
    header("Location: feedback_comments.php?status=error&msg=Missing+required+fields");
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO Comments (userID, reviewID, content) VALUES (?, ?, ?)");
    $stmt->execute([$userID, $reviewID, $content]);

    header("Location: feedback_comments.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_comments.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

