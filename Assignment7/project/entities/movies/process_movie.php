<?php
include __DIR__ . '/../../auth_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../db_connect.php';
$title = trim($_POST['title'] ?? '');
$releaseYear = trim($_POST['releaseYear'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '' || $releaseYear === '') 
{
    header("Location: feedback_movie.php?status=error&msg=Missing+title+or+releaseYear");
    exit;
}

try 
{
    $stmt = $pdo->prepare("
        INSERT INTO Movies (title, releaseYear, description)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$title, $releaseYear, $description]);
    header("Location: feedback_movie.php?status=success");
    exit;
} 
catch (Exception $e) 
{
    header("Location: feedback_movie.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>


