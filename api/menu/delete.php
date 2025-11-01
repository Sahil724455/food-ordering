<?php
require "../../db.php";
$id = $_GET["id"] ?? null;
if($id){
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id=?");
    $stmt->execute([$id]);
}
?>
