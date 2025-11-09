<?php
echo "DATABASE CONNECTION TEST\n";
echo "========================\n";

if (!file_exists('db.php')) {
    echo "❌ db.php not found\n";
    exit(1);
}

include_once 'db.php';

// Determine configuration style
if (defined('DB_HOST')) {
    echo "Using constants configuration:\n";
    $host = DB_HOST;
    $user = DB_USER;
    $pass = defined('DB_PASS') ? DB_PASS : '';
    $name = DB_NAME;
} elseif (isset($db_host)) {
    echo "Using variables configuration:\n";
    $host = $db_host;
    $user = $db_user;
    $pass = isset($db_pass) ? $db_pass : '';
    $name = $db_name;
} else {
    echo "❌ No database configuration found\n";
    exit(1);
}

echo "Host: $host\n";
echo "User: $user\n";
echo "Database: $name\n";

try {
    $db = new mysqli($host, $user, $pass, $name);
    
    if ($db->connect_error) {
        echo "❌ Connection failed: " . $db->connect_error . "\n";
        exit(1);
    }
    
    echo "✅ Connection successful!\n";
    
    // Test query
    $result = $db->query("SELECT 1 as test");
    if ($result) {
        echo "✅ Query execution successful\n";
    }
    
    $db->close();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}