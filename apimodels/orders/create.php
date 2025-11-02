<?php
require '../db.php';

// Helper function to send JSON response
function sendResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper function to insert a record and return the inserted ID
function insertOrder($pdo, $userId, $total) {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->execute([$userId, $total]);
    return $pdo->lastInsertId();
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? 0;
$total = $data['total'] ?? 0;

// Validate input
if (!$user_id || !$total) {
    sendResponse(['message' => 'Missing required fields'], 400);
}

try {
    $order_id = insertOrder($pdo, $user_id, $total);
    sendResponse(['message' => 'Order created', 'order_id' => $order_id], 201);
} catch (PDOException $e) {
    sendResponse(['message' => 'Database error', 'error' => $e->getMessage()], 500);
}
