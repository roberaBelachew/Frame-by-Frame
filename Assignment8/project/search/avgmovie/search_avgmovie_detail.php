<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

$movieID = $_GET['movieID'] ?? '';

if (empty($movieID)) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Error</title>
      <link rel='stylesheet' href='../../style.css'>
    </head>
    <body>
      <p style='color:red;'>No movie selected.</p>
      <p><a href='search_avgmovie.html'>← Back to Search</a></p>
    </body>
    </html>";
    exit;
}

try {
    $query = "
      SELECT m.id, m.title, m.description, m.releaseYear, 
             AVG(r.rating) AS avg_rating, 
             COUNT(r.rating) AS review_count
      FROM Movies m
      LEFT JOIN Reviews r ON m.id = r.movieID
      WHERE m.id = :movieID
      GROUP BY m.id
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['movieID' => $movieID]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie Details</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <?php if ($movie): ?>
    <h2><?= htmlspecialchars($movie['title']) ?></h2>
    <p><strong>Release Year:</strong> <?= htmlspecialchars($movie['releaseYear']) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($movie['description']) ?></p>
    <p><strong>Average Rating:</strong> 
      <?php 
      if ($movie['avg_rating'] !== null) {
          echo number_format($movie['avg_rating'], 2) . " (based on " . $movie['review_count'] . " review(s))";
      } else {
          echo "No ratings yet";
      }
      ?>
    </p>
  <?php else: ?>
    <p style="color:red;">Movie not found.</p>
  <?php endif; ?>
  
  <p>
    <a href="javascript:history.back()">← Back to Results</a> | 
    <a href="search_avgmovie.html">New Search</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
  </p>
</body>
</html>
