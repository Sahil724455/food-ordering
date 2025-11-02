<?php
require "db.php";

$menuItems = [
    ["Pizza", "Cheese pizza with tomato base", 10.50],
    ["Burger", "Beef burger with lettuce & cheese", 8.75],
    ["Pasta", "Penne pasta in white sauce", 12.00],
    ["Salad", "Fresh vegetable salad", 5.50]
];

foreach($menuItems as $item){
    foreach($menuItems as $item){
    $stmt = $pdo->prepare("INSERT INTO menu (name, description, price) VALUES (:name, :description, :price)");
    $stmt->execute([
        ':name' => $item[0],
        ':description' => $item[1],
        ':price' => $item[2]
    ]);
}

    
