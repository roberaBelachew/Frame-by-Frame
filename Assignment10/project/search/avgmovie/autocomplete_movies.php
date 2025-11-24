<?php
require_once "../../db_connect.php";

header('Content-Type: application/json');

if (!isset($_GET['term'])) {
    echo json_encode([]);
    exit;
}

$term = $_GET['term'];

try {
    $stmt = $pdo->prepare("
        SELECT title 
        FROM Movies 
        WHERE title LIKE :term
        ORDER BY title 
        LIMIT 10
    ");

    
    $stmt->execute(['term' => $term . '%']);

    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>

