<?php
require "db.php";

// Insert sample user
$pdo->exec("INSERT INTO users (name, email, password) VALUES
    ('John Doe', 'john@example.com', '".password_hash("123456", PASSWORD_DEFAULT)."'),
    ('Jane Smith', 'jane@example.com', '".password_hash("abcdef", PASSWORD_DEFAULT)."')");

// Insert sample orders
$pdo->exec("INSERT INTO orders (user_id, total, status) VALUES
    (1, 25.50, 'pending'),
    (2, 40.00, 'completed')");

// Insert sample cart items
$pdo->exec("INSERT INTO cart (user_id, menu_item_id, quantity) VALUES
    (1, 101, 2),
    (1, 102, 1),
    (2, 103, 3)");

echo "Sample data inserted successfully!";
?>
