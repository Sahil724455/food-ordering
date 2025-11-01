<?php
require "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["name"], $data["email"], $data["password"])) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $hashed = password_hash($data["password"], PASSWORD_DEFAULT);
    if($stmt->execute([$data["name"], $data["email"], $hashed])) {
        echo json_encode(["message"=>"User registered successfully"]);
    } else {
        echo json_encode(["message"=>"Registration failed"]);
    }
} else {
    echo json_encode(["message"=>"Invalid input"]);
}
?>
