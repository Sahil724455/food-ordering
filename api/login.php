<?php
require '../db.php';


$input = file_get_contents('php://input');
$data = json_decode($input, true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';


if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['message' => 'Email and password are required']);
    exit;
}

try {
    
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
    
        $token = bin2hex(random_bytes(16));

        echo json_encode([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid email or password']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error', 'error' => $e->getMessage()]);
}
