<?php
<<<<<<< HEAD
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';
=======
require '../db.php';
>>>>>>> 206961b794b4876ba20ae88eb049be94a543c1f8

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['message' => 'Email and password required']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // In real app, generate JWT or session
    echo json_encode([
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid credentials']);
}
?>
