<?php
include __DIR__ . '/../../auth_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../db_connect.php';

$type = $_POST['type'] ?? '';
$value = trim($_POST['value'] ?? '');

if ($type === '' || $value === '') 
{
    header("Location: feedback_tag.php?status=error&msg=Missing+type+or+value");
    exit;
}

try 
{
    $stmt = $pdo->prepare("INSERT INTO Tags (type, value) VALUES (?, ?)");
    $stmt->execute([$type, $value]);
    header("Location: feedback_tag.php?status=success");
    exit;
} 
catch (Exception $e) 
{
    header("Location: feedback_tag.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

