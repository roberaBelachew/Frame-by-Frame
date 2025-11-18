<?php
include __DIR__ . '/../../auth_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../db_connect.php';

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') 
{
    header("Location: feedback_users.php?status=error&msg=Missing+email+or+password");
    exit;
}

try 
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
    $stmt->execute([$email, $hashedPassword]);
    header("Location: feedback_users.php?status=success");
    exit;
} 
catch (Exception $e) 
{
    header("Location: feedback_users.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>

