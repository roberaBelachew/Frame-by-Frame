<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

try {
    // Query to find top rated genre
    $query = "
      WITH GenreRatings AS (
          SELECT
              t.value AS genre,
              AVG(r.rating) AS avg_rating,
              COUNT(r.id) AS review_count
          FROM Tags t
          JOIN MovieTags mt ON t.id = mt.tagID
          JOIN Movies m ON mt.movieID = m.id
          JOIN Reviews r ON m.id = r.movieID AND r.flags = 0
          WHERE t.type = 'Genre'
          GROUP BY t.value
      )
      SELECT genre, ROUND(avg_rating, 2) AS avg_genre_rating, review_count
      FROM GenreRatings
      ORDER BY avg_rating DESC
      LIMIT 5
    ";
    
    $stmt = $pdo->prepare($query);
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
  <title>Top Rated Genres</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <h2>Top Rated Genres</h2>
  
  <?php if (count($results) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Rank</th>
        <th>Genre</th>
        <th>Average Rating</th>
        <th>Number of Reviews</th>
        <th>Details</th>
      </tr>
      <?php $rank = 1; ?>
      <?php foreach ($results as $row): ?>
        <tr>
          <td><?= $rank++ ?></td>
          <td><?= htmlspecialchars($row['genre']) ?></td>
          <td><?= htmlspecialchars($row['avg_genre_rating']) ?></td>
          <td><?= htmlspecialchars($row['review_count']) ?></td>
          <td><a href="search_topgenre_detail.php?genre=<?= urlencode($row['genre']) ?>">View Movies</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No genre data available.</p>
  <?php endif; ?>
  
  <p>
    <a href="search_topgenre.html">← Back</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.html">Back to Project Home</a>
  </p>
</body>
</html>
