<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

$title = $_GET['title'] ?? '';

if (empty($title)) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Error</title>
      <link rel='stylesheet' href='../../style.css'>
    </head>
    <body>
      <p style='color:red;'>Please enter a movie title.</p>
      <p><a href='search_avgmovie.html'>← Back to Search</a></p>
    </body>
    </html>";
    exit;
}

try {
    $query = "
      SELECT m.id, m.title, AVG(r.rating) AS avg_rating
      FROM Movies m
      LEFT JOIN Reviews r ON m.id = r.movieID
      WHERE m.title LIKE CONCAT('%', :title, '%')
      GROUP BY m.id, m.title
      ORDER BY avg_rating DESC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['title' => $title]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results - Average Movie Rating</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <h2>Search Results for "<?= htmlspecialchars($title) ?>"</h2>
  
  <?php if (count($results) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Movie Title</th>
        <th>Average Rating</th>
        <th>Details</th>
      </tr>
      <?php foreach ($results as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td>
            <?php 
            if ($row['avg_rating'] !== null) {
                echo number_format($row['avg_rating'], 2);
            } else {
                echo "No ratings yet";
            }
            ?>
          </td>
          <td><a href="search_avgmovie_detail.php?movieID=<?= $row['id'] ?>">View Details</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No results found for <strong><?= htmlspecialchars($title) ?></strong>.</p>
  <?php endif; ?>
  
  <p>
    <a href="search_avgmovie.html">← New Search</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.html">Back to Project Home</a>
  </p>
</body>
</html>
