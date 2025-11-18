<?php
include __DIR__ . '/../../auth_admin.php';

require_once("../../db_connect.php");

$userID = $_POST['userID'] ?? null;
$movieID = $_POST['movieID'] ?? null;
$status = $_POST['status'] ?? null;

try {
    if (!$userID || !$movieID) {
        throw new Exception("User ID and Movie ID are required.");
    }

    $stmt = $pdo->prepare("INSERT INTO Watchlist (userID, movieID, status) VALUES (?, ?, ?)");
    $stmt->execute([$userID, $movieID, $status]);

    $message = "✅ Watchlist entry added successfully!";
} catch (PDOException $e) {
    $message = "❌ Error: " . $e->getMessage();
} catch (Exception $e) {
    $message = "❌ Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Watchlist Feedback</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <h1>Watchlist Submission Feedback</h1>
  <p><?php echo $message; ?></p>

  <a href="input_watchlist.html">Add Another</a> |
  <a href="../../index.html">Back</a>
</body>
</html>

