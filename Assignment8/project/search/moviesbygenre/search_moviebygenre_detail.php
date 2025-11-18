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
      <p><a href='search_moviesbygenre.html'>← Back to Search</a></p>
    </body>
    </html>";
    exit;
}

try {
    // Get movie details
    $query = "
      SELECT 
          m.id, 
          m.title, 
          m.description, 
          m.releaseYear,
          AVG(r.rating) AS avg_rating,
          COUNT(r.id) AS review_count
      FROM Movies m
      LEFT JOIN Reviews r ON m.id = r.movieID AND r.flags = 0
      WHERE m.id = :movieID
      GROUP BY m.id
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['movieID' => $movieID]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get all tags for this movie
    $tagQuery = "
      SELECT t.type, t.value
      FROM Tags t
      JOIN MovieTags mt ON t.id = mt.tagID
      WHERE mt.movieID = :movieID
      ORDER BY t.type, t.value
    ";
    
    $tagStmt = $pdo->prepare($tagQuery);
    $tagStmt->execute(['movieID' => $movieID]);
    $tags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Organize tags by type
    $tagsByType = [];
    foreach ($tags as $tag) {
        $tagsByType[$tag['type']][] = $tag['value'];
    }
    
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
    <h2><?= htmlspecialchars($movie['title']) ?> (<?= htmlspecialchars($movie['releaseYear']) ?>)</h2>
    
    <p><strong>Description:</strong> <?= htmlspecialchars($movie['description']) ?></p>
    
    <p><strong>Average Rating:</strong> 
      <?php 
      if ($movie['avg_rating'] !== null) {
          echo number_format($movie['avg_rating'], 2) . "/10 (based on " . $movie['review_count'] . " review(s))";
      } else {
          echo "No ratings yet";
      }
      ?>
    </p>
    
    <?php if (!empty($tagsByType)): ?>
      <h3>Movie Information</h3>
      <?php foreach ($tagsByType as $type => $values): ?>
        <p><strong><?= htmlspecialchars($type) ?>:</strong> <?= htmlspecialchars(implode(', ', $values)) ?></p>
      <?php endforeach; ?>
    <?php endif; ?>
    
  <?php else: ?>
    <p style="color:red;">Movie not found.</p>
  <?php endif; ?>
  
  <p>
    <a href="javascript:history.back()">← Back to Results</a> | 
    <a href="search_moviesbygenre.html">New Search</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
  </p>
</body>
</html>
```

---

## Directory Structure

You'll need to create these directories:
```
/project/search/
├── avgmovie/          (already exists)
├── topgenre/          (create this)
├── likedreviews/      (create this)
└── moviesbygenre/     (create this)
