<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['message' => 'All fields are required']);
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword]);
    echo json_encode(['message' => 'User registered successfully']);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Duplicate email
        http_response_code(409);
        echo json_encode(['message' => 'Email already exists']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Server error']);
    }
}
?>
