<?php
require "../../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["user_id"], $data["total"])) {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    if($stmt->execute([$data["user_id"], $data["total"]])) {
        echo json_encode(["message"=>"Order created successfully"]);
    } else {
        echo json_encode(["message"=>"Order creation failed"]);
    }
} else {
    echo json_encode(["message"=>"Invalid input"]);
}
?>
