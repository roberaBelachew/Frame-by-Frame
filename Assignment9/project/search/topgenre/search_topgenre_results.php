<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

$genre = $_GET['genre'] ?? '';

if (empty($genre)) {
    echo "<p style='color:red;'>Please select a genre.</p>
          <p><a href='search_topgenre.html'>← Back</a></p>";
    exit;
}

try {
    // Query for selected genre
    $query = "
        SELECT 
            t.value AS genre,
            AVG(r.rating) AS avg_rating,
            COUNT(r.id) AS review_count
        FROM Tags t
        JOIN MovieTags mt ON t.id = mt.tagID
        JOIN Movies m ON mt.movieID = m.id
        LEFT JOIN Reviews r ON m.id = r.movieID AND r.flags = 0
        WHERE t.type = 'Genre' AND t.value = :genre
        GROUP BY t.value
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['genre' => $genre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Genre Results</title>
  <link rel="stylesheet" href="../../style.css">
</head>

<body>

<h2>Search Results for Genre: "<?= htmlspecialchars($genre) ?>"</h2>

<?php if ($result): ?>
<table border="1" cellpadding="8" cellspacing="0">
    <tr><th>Genre</th><td><?= htmlspecialchars($result['genre']) ?></td></tr>
    <tr><th>Average Rating</th><td><?= number_format($result['avg_rating'], 2) ?></td></tr>
    <tr><th>Number of Reviews</th><td><?= $result['review_count'] ?></td></tr>
    <tr>
        <th>Details</th>
        <td>
            <a href="search_topgenre_detail.php?genre=<?= urlencode($genre) ?>">
                View Movies
            </a>
        </td>
    </tr>
</table>

<?php else: ?>
    <p>No results found for this genre.</p>
<?php endif; ?>

<p>
  <a href="search_topgenre.html">← New Search</a> |
  <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
</p>

</body>
</html>

