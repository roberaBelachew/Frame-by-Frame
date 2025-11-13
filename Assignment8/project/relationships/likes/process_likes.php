<?php
include __DIR__ . '/../../auth_admin.php';

require_once '../../db_connect.php';

$userID = $_POST['userID'] ?? null;
$reviewID = $_POST['reviewID'] ?? null;

if (!$userID || !$reviewID) {
    header("Location: feedback_likes.php?status=error&msg=Missing+userID+or+reviewID");
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO Likes (userID, reviewID) VALUES (?, ?)");
    $stmt->execute([$userID, $reviewID]);

    header("Location: feedback_likes.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_likes.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

