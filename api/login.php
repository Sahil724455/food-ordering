<?php
require "../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["email"], $data["password"])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data["email"]]);
    $user = $stmt->fetch();
    if($user && password_verify($data["password"], $user["password"])) {
        echo json_encode(["message"=>"Login successful","user"=>$user]);
    } else {
        echo json_encode(["message"=>"Invalid credentials"]);
    }
} else {
    echo json_encode(["message"=>"Invalid input"]);
}
?>
