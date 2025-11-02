<?php
require "db.php";

try {
     $sql = "
        CREATE TABLE IF NOT EXISTS menu (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
 $pdo->exec($sql);
    echo "Menu table created successfully!";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
