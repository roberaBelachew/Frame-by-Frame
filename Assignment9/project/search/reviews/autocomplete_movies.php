<?php
require_once("../../db_connect.php");
header('Content-Type: application/json; charset=utf-8');

$term = $_GET['term'] ?? '';

try {
    $stmt = $pdo->prepare("
        SELECT title
        FROM Movies
        WHERE title LIKE :term
        ORDER BY title ASC
        LIMIT 20
    ");
    $like = $term . "%";
    $stmt->execute(['term' => $like]);

    $titles = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $titles[] = $row['title'];
    }

    echo json_encode($titles);

} catch (PDOException $e) {
    echo json_encode([]);
}
