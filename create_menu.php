<?php
require "db.php";
$pdo->exec("CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$sampleMenu = [
    ["Pizza","Cheese pizza with tomato base",10.50],
    ["Burger","Beef burger with lettuce & cheese",8.75],
    ["Pasta","Penne pasta in white sauce",12.00],
    ["Salad","Fresh vegetable salad",5.50],
];

foreach($sampleMenu as $m){
    $stmt = $pdo->prepare("INSERT INTO menu (name,description,price) VALUES (?,?,?)");
    $stmt->execute($m);
}

echo "Menu table created and sample items inserted!";
?>
