<?php
require '../db.php';
$data = json_decode(file_get_contents('php://input'), true);

$order_id = $data['order_id'] ?? 0;
$status = $data['status'] ?? '';

if (!$order_id || !$status) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->execute([$status, $order_id]);

echo json_encode(['message' => 'Order updated']);
