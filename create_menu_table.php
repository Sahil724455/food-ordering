<?php
require "db.php";

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS menu (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Menu table created successfully!";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
