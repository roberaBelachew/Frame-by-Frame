<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../db_connect.php");

$reviewID = $_GET['reviewID'] ?? '';

if (empty($reviewID)) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Error</title>
      <link rel='stylesheet' href='../../style.css'>
    </head>
    <body>
      <p style='color:red;'>No review selected.</p>
      <p><a href='search_likedreviews.html'>← Back to Search</a></p>
    </body>
    </html>";
    exit;
}

try {
    //we get review details
    $query = "
      SELECT 
          r.id,
          r.rating,
          p.username,
          m.title AS movie_title,
          m.releaseYear,
          m.description,
          COUNT(DISTINCT l.userID) AS like_count,
          COUNT(DISTINCT c.id) AS comment_count
      FROM Reviews r
      JOIN ProfileInfo p ON r.userID = p.userID
      JOIN Movies m ON r.movieID = m.id
      LEFT JOIN Likes l ON r.id = l.reviewID
      LEFT JOIN Comments c ON r.id = c.reviewID
      WHERE r.id = :reviewID
      GROUP BY r.id, r.rating, p.username, m.title, m.releaseYear, m.description
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['reviewID' => $reviewID]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $commentQuery = "
      SELECT 
          c.content,
          c.createdAt,
          p.username
      FROM Comments c
      JOIN ProfileInfo p ON c.userID = p.userID
      WHERE c.reviewID = :reviewID
      ORDER BY c.createdAt DESC
    ";
    
    $commentStmt = $pdo->prepare($commentQuery);
    $commentStmt->execute(['reviewID' => $reviewID]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Review Details</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <?php if ($review): ?>
    <h2>Review by <?= htmlspecialchars($review['username']) ?></h2>
    
    <div style="border: 1px solid #ccc; padding: 15px; margin: 20px 0;">
      <h3><?= htmlspecialchars($review['movie_title']) ?> (<?= htmlspecialchars($review['releaseYear']) ?>)</h3>
      <p><strong>Rating:</strong> <?= number_format($review['rating'], 1) ?>/10</p>
      <p><strong>Movie Description:</strong> <?= htmlspecialchars($review['description']) ?></p>
      <p><strong>Likes:</strong> <?= $review['like_count'] ?> | <strong>Comments:</strong> <?= $review['comment_count'] ?></p>
    </div>
    
    <?php if (count($comments) > 0): ?>
      <h3>Comments (<?= count($comments) ?>)</h3>
      <?php foreach ($comments as $comment): ?>
        <div style="border-left: 3px solid #007bff; padding: 10px; margin: 10px 0; background-color: #f8f9fa;">
          <p><strong><?= htmlspecialchars($comment['username']) ?></strong> - <em><?= date('M d, Y', strtotime($comment['createdAt'])) ?></em></p>
          <p><?= htmlspecialchars($comment['content']) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No comments yet.</p>
    <?php endif; ?>
    
  <?php else: ?>
    <p style="color:red;">Review not found.</p>
  <?php endif; ?>
  
  <p>
    <a href="javascript:history.back()">← Back to Results</a> | 
    <a href="search_likedreviews.html">New Search</a> | 
    <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Project Home</a>
  </p>
</body>
</html>
