<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("The access was denied! Only admins are allowed.");
}
?>

