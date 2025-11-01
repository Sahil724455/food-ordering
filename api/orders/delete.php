<?php
require "../../db.php";
header("Content-Type: application/json");

$id = $_GET["id"] ?? null;

if($id) {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    if($stmt->execute([$id])) {
        echo json_encode(["message"=>"Order deleted successfully"]);
    } else {
        echo json_encode(["message"=>"Delete failed"]);
    }
} else {
    echo json_encode(["message"=>"ID missing"]);
}
?>
