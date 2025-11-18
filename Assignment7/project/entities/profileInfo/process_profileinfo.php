<?php
include __DIR__ . '/../../auth_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../db_connect.php';

$userID = $_POST['userID'] ?? '';
$joinDate = $_POST['joinDate'] ?? '';
$username = trim($_POST['username'] ?? '');
if ($userID === '' || $joinDate === '' || $username === '') 
{
    header("Location: feedback_profileinfo.php?status=error&msg=Missing+required+fields");
    exit;
}

try 
{
    $stmt = $pdo->prepare("INSERT INTO ProfileInfo (userID, joinDate, username) VALUES (?, ?, ?)");
    $stmt->execute([$userID, $joinDate, $username]);
    header("Location: feedback_profileinfo.php?status=success");
    exit;
} 
catch (Exception $e) 
{
    header("Location: feedback_profileinfo.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

