<?php
require "db.php";

$menuItems = [
    ["Pizza", "Cheese pizza with tomato base", 10.50],
    ["Burger", "Beef burger with lettuce & cheese", 8.75],
    ["Pasta", "Penne pasta in white sauce", 12.00],
    ["Salad", "Fresh vegetable salad", 5.50]
];

foreach($menuItems as $item){
    $stmt = $pdo->prepare("INSERT INTO menu (name, description, price) VALUES (?, ?, ?)");
    $stmt->execute([$item[0], $item[1], $item[2]]);
}

echo "Menu items added successfully!";
?>