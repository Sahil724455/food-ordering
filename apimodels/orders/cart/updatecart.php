<?php
require '../db.php';
$data = json_decode(file_get_contents('php://input'), true);

$cart_id = $data['cart_id'] ?? 0;
$quantity = $data['quantity'] ?? 1;

$stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
$stmt->execute([$quantity, $cart_id]);

echo json_encode(['message' => 'Cart updated']);
