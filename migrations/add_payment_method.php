<?php
require __DIR__ . '/../db.php';
try {
    $pdo->exec("ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) DEFAULT NULL");
    echo "ALTER_OK\n";
} catch (Exception $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
?>