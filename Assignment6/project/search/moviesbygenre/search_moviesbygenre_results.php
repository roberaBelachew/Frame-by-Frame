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
      <p style='color:red;'>Please select a genre.</p>
      <p><a href='search_moviesbygenre.html'>← Back to Search</a></p>
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
          t.value AS genre
      FROM Movies m
      JOIN MovieTags mt ON m.id = mt.movieID
      JOIN Tags t ON mt.tagID = t.id
      WHERE t.type = 'Genre' AND t.value = :genre
      ORDER BY m.releaseYear DESC, m.title
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['genre' => $genre]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  
  <?php if (count($results) > 0): ?>
    <p>Found <?= count($results) ?> movie(s) in this genre.</p>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Title</th>
        <th>Release Year</th>
        <th>Details</th>
      </tr>
      <?php foreach ($results as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['releaseYear']) ?></td>
          <td><a href="search_moviesbygenre_detail.php?movieID=<?= $row['id'] ?>">View Details</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No movies found in the <strong><?= htmlspecialchars($genre) ?></strong> genre.</p>
  <?php endif; ?>
  
  <p>
    <a href="search_moviesbygenre.html">← New Search</a> | 
    <a href="../../index.html">Back to Project Home</a>
  </p>
</body>
</html>
