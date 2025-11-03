<?php
require_once '../../db_connect.php';

$movieID = $_POST['movieID'] ?? null;
$sourceID = $_POST['sourceID'] ?? null;

if (!$movieID || !$sourceID) {
    header("Location: feedback_moviesources.php?status=error&msg=Missing+movieID+or+sourceID");
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO MovieSources (movieID, sourceID) VALUES (?, ?)");
    $stmt->execute([$movieID, $sourceID]);
    header("Location: feedback_moviesources.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_moviesources.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

