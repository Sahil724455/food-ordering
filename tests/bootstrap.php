<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Set testing environment
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';

// Include necessary files
if (file_exists(__DIR__ . '/../db.php')) {
    require_once __DIR__ . '/../db.php';
}

// Define constants if not defined in db.php
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'food_ordering_test');
