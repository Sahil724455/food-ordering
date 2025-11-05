<?php
require "../../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["id"], $data["quantity"])) {
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    if($stmt->execute([$data["quantity"], $data["id"]])) {
        echo json_encode(["message"=>"Cart updated successfully"]);
    } else {
        echo json_encode(["message"=>"Update failed"]);
    }
} else {
    echo json_encode(["message"=>"Invalid input"]);
}
?>
