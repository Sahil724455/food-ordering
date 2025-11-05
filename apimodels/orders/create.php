<?php
require '../db.php';
$data = json_decode(file_get_contents('php://input'), true);

$user_id = $data['user_id'] ?? 0;
$total = $data['total'] ?? 0;

if (!$user_id || !$total) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$stmt->execute([$user_id, $total]);

echo json_encode(['message' => 'Order created', 'order_id' => $pdo->lastInsertId()]);
