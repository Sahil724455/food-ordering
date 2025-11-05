<?php
require_once __DIR__ . '/../cors.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

try {
    // Get user_id from query parameter
    $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing user_id']);
        exit;
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Get cart items
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.menu_item_id,
            c.quantity,
            c.created_at,
            m.name as item_name,
            m.price,
            m.description
        FROM cart c
        LEFT JOIN menu m ON c.menu_item_id = m.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ");
    
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate total
    $total = array_reduce($items, function($sum, $item) {
        return $sum + ($item['price'] * $item['quantity']);
    }, 0);

    $response = [
        'status' => 'success',
        'data' => [
            'user_id' => $user_id,
            'items' => $items,
            'total' => number_format($total, 2, '.', ''),
            'item_count' => count($items)
        ]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred',
        'debug' => $e->getMessage() // Remove this in production
    ]);
}
?>