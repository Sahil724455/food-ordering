<?php
require "../../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["user_id"], $data["menu_item_id"], $data["quantity"])) {
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, menu_item_id, quantity) VALUES (?, ?, ?)");
    if($stmt->execute([$data["user_id"], $data["menu_item_id"], $data["quantity"]])) {
        echo json_encode(["message"=>"Item added to cart successfully"]);
    } else {
        echo json_encode(["message"=>"Failed to add item to cart"]);
    }
} else {
    echo json_encode(["message"=>"Invalid input"]);
}
?>
