<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

$limit = $_GET['limit'] ?? 5;
$limit = (int)$limit; // Ensure it's an integer

if ($limit <= 0 || $limit > 100) {
    $limit = 5; // Default to 5 if invalid
}

try {
    $query = "
      WITH ReviewLikes AS (
          SELECT
              r.id AS review_id,
              p.username,
              m.title AS movie_title,
              r.rating,
              COUNT(l.userID) AS like_count
          FROM Reviews r
          JOIN ProfileInfo p ON r.userID = p.userID
          JOIN Movies m ON r.movieID = m.id
          LEFT JOIN Likes l ON r.id = l.reviewID
          WHERE r.flags = 0
          GROUP BY r.id, p.username, m.title, r.rating
      )
      SELECT review_id, username, movie_title, rating, like_count
      FROM ReviewLikes
      ORDER BY like_count DESC, rating DESC
      LIMIT :limit
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Most Liked Reviews</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <h2>Top <?= $limit ?> Most Liked Reviews</h2>
  
  <?php if (count($results) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Rank</th>
        <th>Username</th>
        <th>Movie</th>
        <th>Rating</th>
        <th>Likes</th>
        <th>Details</th>
      </tr>
      <?php $rank = 1; ?>
      <?php foreach ($results as $row): ?>
        <tr>
          <td><?= $rank++ ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['movie_title']) ?></td>
          <td><?= number_format($row['rating'], 1) ?></td>
          <td><?= htmlspecialchars($row['like_count']) ?></td>
          <td><a href="search_likedreviews_detail.php?reviewID=<?= $row['review_id'] ?>">View Review</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No reviews found.</p>
  <?php endif; ?>
  
  <p>
    <a href="search_likedreviews.html">← New Search</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
  </p>
</body>
</html>
