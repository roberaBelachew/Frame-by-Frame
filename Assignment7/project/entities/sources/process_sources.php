<?php
include __DIR__ . '/../../auth_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../db_connect.php';
$type = $_POST['type'] ?? '';

if ($type === '')
{
    header("Location: feedback_sources.php?status=error&msg=Missing+Source+Type");
    exit;
}

try
{
    $stmt = $pdo->prepare("INSERT INTO Sources (type) VALUES (?)");
    $stmt->execute([$type]);
    header("Location: feedback_sources.php?status=success");
    exit;
} 
catch (Exception $e) 
{
    header("Location: feedback_sources.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

