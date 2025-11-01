<?php
require "../../db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["id"], $data["status"])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if($stmt->execute([$data["status"], $data["id"]])) {
        echo json_encode(["message"=>"Order updated successfully"]);
    } else {
        echo json_encode(["message"=>"Update failed"]);
    }
} else {
    echo json_encode(["message"=>"Invalid input"]);
}
?>
