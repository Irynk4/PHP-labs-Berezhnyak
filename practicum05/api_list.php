<?php
require_once 'db.php';
header('Content-Type: application/json');

$q = isset($_GET['q']) ? (int)$_GET['q'] : 0;

try {
    if ($q > 0) {
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE capacity >= :capacity ORDER BY capacity ASC");
        $stmt->execute(['capacity' => $q]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    echo json_encode($rows);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}