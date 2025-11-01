<?php
require '../db.php';
$order_id = $_GET['order_id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
$stmt->execute([$order_id]);

echo json_encode(['message' => 'Order deleted']);
