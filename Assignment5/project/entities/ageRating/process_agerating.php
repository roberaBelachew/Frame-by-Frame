<?php
require_once '../../db_connect.php';

$label = $_POST['label'] ?? '';

if ($label === '') {
    header("Location: feedback_agerating.php?status=error&msg=Missing+rating+label");
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO AgeRating (label) VALUES (?)");
    $stmt->execute([$label]);
    header("Location: feedback_agerating.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: feedback_agerating.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

