<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL server connection (no DB yet)
$host = 'localhost';
$user = 'root';
$pass = ''; // XAMPP default password is empty

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS food_order_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo "Database 'food_order_db' created successfully!\n";

    // Connect to the new database
    $pdo = new PDO("mysql:host=$host;dbname=food_order_db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create users table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    );
    echo "Table 'users' created successfully!\n";

    // Create orders table (user_id as BIGINT UNSIGNED to match users.id)
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            status VARCHAR(50) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
    );
    echo "Table 'orders' created successfully!\n";

    // Create cart table (use BIGINT UNSIGNED for foreign keys to match other tables)
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cart (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            menu_item_id BIGINT(20) UNSIGNED NOT NULL,
            quantity INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
    );
    echo "Table 'cart' created successfully!\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
