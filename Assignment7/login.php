<?php
session_start();
require 'project/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT id, password, role FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) 
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['role'];
        header("Location: project/maintenance.php");
        exit();
    } 
    else 
    {
        $error = "Invalid login credentials";
    }
}
?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" value="Login">
</form>
<?php if(isset($error)) echo $error; ?>

