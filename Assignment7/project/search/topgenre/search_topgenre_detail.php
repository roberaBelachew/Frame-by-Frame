<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

$genre = $_GET['genre'] ?? '';

if (empty($genre)) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Error</title>
      <link rel='stylesheet' href='../../style.css'>
    </head>
    <body>
      <p style='color:red;'>No genre selected.</p>
      <p><a href='search_topgenre.html'>← Back to Search</a></p>
    </body>
    </html>";
    exit;
}

try {
    $query = "
      SELECT 
          m.id, 
          m.title, 
          m.releaseYear,
          AVG(r.rating) AS avg_rating,
          COUNT(r.id) AS review_count
      FROM Movies m
      JOIN MovieTags mt ON m.id = mt.movieID
      JOIN Tags t ON mt.tagID = t.id
      LEFT JOIN Reviews r ON m.id = r.movieID AND r.flags = 0
      WHERE t.type = 'Genre' AND t.value = :genre
      GROUP BY m.id, m.title, m.releaseYear
      ORDER BY avg_rating DESC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['genre' => $genre]);
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movies in <?= htmlspecialchars($genre) ?> Genre</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <h2>Movies in "<?= htmlspecialchars($genre) ?>" Genre</h2>
  
  <?php if (count($movies) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Title</th>
        <th>Release Year</th>
        <th>Average Rating</th>
        <th>Reviews</th>
      </tr>
      <?php foreach ($movies as $movie): ?>
        <tr>
          <td><?= htmlspecialchars($movie['title']) ?></td>
          <td><?= htmlspecialchars($movie['releaseYear']) ?></td>
          <td>
            <?php 
            if ($movie['avg_rating'] !== null) {
                echo number_format($movie['avg_rating'], 2);
            } else {
                echo "No ratings";
            }
            ?>
          </td>
          <td><?= htmlspecialchars($movie['review_count']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No movies found in this genre.</p>
  <?php endif; ?>
  
  <p>
    <a href="javascript:history.back()">← Back to Results</a> | 
    <a href="search_topgenre.html">New Search</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
  </p>
</body>
</html>
