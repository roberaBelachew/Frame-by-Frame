<?php
require_once "../../db_connect.php";

// Validate input
if (!isset($_GET['limit']) || !is_numeric($_GET['limit']) || intval($_GET['limit']) < 1) {
    echo "<h2>Invalid input. Please enter a positive number.</h2>";
    exit();
}

$limit = intval($_GET['limit']);

echo "<link rel='stylesheet' href='../../style.css'>";
echo "<h2>Top $limit Most Liked Reviews</h2>";

$query = "
    SELECT 
        r.id AS reviewID,
        r.movieID,
        m.title AS movieTitle,
        r.rating,
        COUNT(l.reviewID) AS likeCount
    FROM Reviews r
    LEFT JOIN Likes l 
        ON r.id = l.reviewID
    JOIN Movies m
        ON r.movieID = m.id
    WHERE r.flags = 0
    GROUP BY r.id, r.movieID, m.title, r.rating
    ORDER BY likeCount DESC
    LIMIT :limit
";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) === 0) {
    echo "<p>No reviews found.</p>";
} else {
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Review ID</th>
                <th>Movie Title</th>
                <th>Rating</th>
                <th>Likes</th>
            </tr>";

    foreach ($results as $row) {
        echo "<tr>";
        echo "<td>{$row['reviewID']}</td>";
        echo "<td>{$row['movieTitle']}</td>";
        echo "<td>{$row['rating']}</td>";
        echo "<td>{$row['likeCount']}</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<p><a href='search_likedreviews.html'>Back to Search</a></p>";
echo "<p><a href='https://clabsql.constructor.university/~rbelachew/index.php'>Return Home</a></p>";
?>

