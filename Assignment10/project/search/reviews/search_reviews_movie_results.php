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
      <p><a href='search_reviews_movie.html'>← Back to Search</a></p>
    </body>
    </html>";
    exit;
}

try {
    // Find matching movies
    $query = "
      SELECT DISTINCT m.id, m.title
      FROM Movies m
      WHERE m.title LIKE :title
      ORDER BY m.title ASC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['title' => $title . '%']);
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie Review Results</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <h2>Search Results for "<?= htmlspecialchars($title) ?>"</h2>

  <?php if (count($movies) === 0): ?>
    <p>No movies found.</p>
    <p><a href="search_reviews_movie.html">← New Search</a></p>
  <?php endif; ?>

  <?php foreach ($movies as $movie): ?>

    <?php
    $movieID = $movie['id'];

    // Fetch associated review ratings + usernames
    $q2 = "
      SELECT r.rating, r.flags, u.email AS username
      FROM Reviews r
      JOIN Users u ON r.userID = u.id
      WHERE r.movieID = :movieID
      ORDER BY r.rating DESC
    ";

    $stmt2 = $pdo->prepare($q2);
    $stmt2->execute(['movieID' => $movieID]);
    $reviews = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h3><?= htmlspecialchars($movie['title']) ?></h3>

    <?php if (count($reviews) > 0): ?>
      <table border="1" cellpadding="8" cellspacing="0">
        <tr>
          <th>Rating</th>
          <th>User</th>
          <th>Flags</th>
        </tr>

        <?php foreach ($reviews as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['rating']) ?></td>
            <td><?= htmlspecialchars($r['username']) ?></td>
            <td><?= htmlspecialchars($r['flags']) ?></td>
          </tr>
        <?php endforeach; ?>

      </table>
    <?php else: ?>
      <p><em>No reviews yet for this movie.</em></p>
    <?php endif; ?>

    <hr>

  <?php endforeach; ?>

  <p>
    <a href="search_reviews_movie.html">← New Search</a> |
    <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
  </p>

</body>
</html>
