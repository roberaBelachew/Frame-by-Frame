<?php
require_once("../../db_connect.php");

header("Content-Type: application/json");

$term = $_GET['term'] ?? '';

if (strlen($term) < 1) {
    echo json_encode([]);
    exit;
}

try {
    $query = "
        SELECT DISTINCT value
        FROM Tags
        WHERE type = 'Genre'
          AND value LIKE CONCAT(:term, '%')
        ORDER BY value ASC
        LIMIT 20
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['term' => $term]);

    $genres = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($genres);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>

