<?php
echo "UNIVERSAL DATABASE CONNECTION TESTER\n";
echo "====================================\n\n";

// Common database configurations to try
$configs = [];

// Read db.php and extract configuration
if (file_exists('db.php')) {
    $content = file_get_contents('db.php');
    
    // Try to extract variables with regex
    if (preg_match('/\$db_host\s*=\s*[\'"]([^\'"]*)[\'"]/', $content, $matches)) {
        $configs['extracted']['host'] = $matches[1];
    }
    if (preg_match('/\$db_user\s*=\s*[\'"]([^\'"]*)[\'"]/', $content, $matches)) {
        $configs['extracted']['user'] = $matches[1];
    }
    if (preg_match('/\$db_pass\s*=\s*[\'"]([^\'"]*)[\'"]/', $content, $matches)) {
        $configs['extracted']['pass'] = $matches[1];
    }
    if (preg_match('/\$db_name\s*=\s*[\'"]([^\'"]*)[\'"]/', $content, $matches)) {
        $configs['extracted']['name'] = $matches[1];
    }
}

// Add common default configurations
$configs['default1'] = ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'food_ordering'];
$configs['default2'] = ['host' => 'localhost', 'user' => 'root', 'pass' => 'password', 'name' => 'food_ordering'];
$configs['default3'] = ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '', 'name' => 'food_ordering'];
$configs['default4'] = ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'food_ordering_main'];

// Test each configuration
foreach ($configs as $configName => $config) {
    if (empty($config['host']) || empty($config['user']) || empty($config['name'])) {
        continue;
    }
    
    echo "Testing configuration: $configName\n";
    echo "  Host: " . $config['host'] . "\n";
    echo "  User: " . $config['user'] . "\n";
    echo "  Database: " . $config['name'] . "\n";
    echo "  Password: " . (empty($config['pass']) ? '(empty)' : '***') . "\n";
    
    $mysqli = @new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);
    
    if ($mysqli->connect_error) {
        echo "  ❌ Connection failed: " . $mysqli->connect_error . "\n\n";
    } else {
        echo "  ✅ Connection successful!\n";
        
        // Get server info
        echo "  Server: " . $mysqli->server_info . "\n";
        
        // Test basic query
        $result = $mysqli->query("SHOW TABLES");
        if ($result) {
            $tableCount = $result->num_rows;
            echo "  Tables in database: $tableCount\n";
            
            if ($tableCount > 0) {
                echo "  Table names:\n";
                while ($row = $result->fetch_array()) {
                    echo "    - " . $row[0] . "\n";
                }
            }
        }
        
        $mysqli->close();
        echo "\n";
        break;
    }
}

// If no connection worked, try connecting without database
echo "Testing connection without database selection:\n";
$mysqli = @new mysqli('localhost', 'root', '');
if ($mysqli->connect_error) {
    echo "❌ Basic connection failed: " . $mysqli->connect_error . "\n";
} else {
    echo "✅ Basic MySQL connection successful\n";
    
    // Show available databases
    $result = $mysqli->query("SHOW DATABASES");
    if ($result) {
        echo "Available databases:\n";
        while ($row = $result->fetch_array()) {
            echo "  - " . $row[0] . "\n";
        }
    }
    $mysqli->close();
}