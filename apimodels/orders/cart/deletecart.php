<?php
require '../db.php';
$cart_id = $_GET['cart_id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
$stmt->execute([$cart_id]);

echo json_encode(['message' => 'Item removed from cart']);
