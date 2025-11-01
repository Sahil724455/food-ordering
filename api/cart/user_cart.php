<?php
require "../../db.php";
header("Content-Type: application/json");

$user_id = $_GET["user_id"] ?? null;

if($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    echo json_encode($stmt->fetchAll());
} else {
    echo json_encode(["message"=>"User ID missing"]);
}
?>
