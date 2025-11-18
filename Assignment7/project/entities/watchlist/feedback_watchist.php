<?php
require_once("../../db_connect.php");
try 
{
    $stmt = $pdo->query
    ("
        SELECT W.id, U.email AS user_email, M.title AS movie_title, W.status
        FROM Watchlist W
        JOIN Users U ON W.userID = U.id
        JOIN Movies M ON W.movieID = M.id
    ");
    $watchlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
catch (PDOException $e) 
{
    die("Error fetching data: " . $e->getMessage());
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
  <h1>All Watchlist Entries</h1>

  <?php if (count($watchlist) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>ID</th>
        <th>User Email</th>
        <th>Movie Title</th>
        <th>Status</th>
      </tr>
      <?php foreach ($watchlist as $entry): ?>
        <tr>
          <td><?php echo htmlspecialchars($entry['id']); ?></td>
          <td><?php echo htmlspecialchars($entry['user_email']); ?></td>
          <td><?php echo htmlspecialchars($entry['movie_title']); ?></td>
          <td><?php echo htmlspecialchars($entry['status']); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No entries found in Watchlist</p>
  <?php endif; ?>

  <br>
  <a href="input_watchlist.html">Add Another</a> |
  <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back</a>
</body>
</html>

