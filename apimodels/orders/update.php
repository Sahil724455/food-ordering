<?php
require '../db.php';

// Helper function to send JSON response
function sendResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper function to update a record
function updateOrderStatus($pdo, $orderId, $status) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $orderId]);
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'] ?? 0;
$status = $data['status'] ?? '';

// Validate input
if (!$order_id || !$status) {
    sendResponse(['message' => 'Missing required fields'], 400);
}

try {
    if (updateOrderStatus($pdo, $order_id, $status)) {
        sendResponse(['message' => 'Order updated'], 200);
    } else {
        sendResponse(['message' => 'Order not found or no changes made'], 404);
    }
} catch (PDOException $e) {
    sendResponse(['message' => 'Database error', 'error' => $e->getMessage()], 500);
}
