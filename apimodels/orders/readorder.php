<?php
require '../db.php';
$user_id = $_GET['user_id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

echo json_encode($orders);
