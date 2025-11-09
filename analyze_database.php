<?php
echo "DATABASE CONFIGURATION ANALYZER\n";
echo "===============================\n\n";

$dbFiles = ['db.php', 'createdb.php', 'test_db.php'];
$foundConfig = false;

foreach ($dbFiles as $file) {
    if (!file_exists($file)) {
        echo "❌ $file not found\n";
        continue;
    }
    
    echo "📄 Analyzing $file:\n";
    echo str_repeat("-", 50) . "\n";
    
    $content = file_get_contents($file);
    $lines = file($file);
    
    // Show first 10 lines to understand structure
    echo "First 10 lines:\n";
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo "  " . ($i + 1) . ": " . trim($lines[$i]) . "\n";
    }
    
    // Check for common patterns
    $patterns = [
        'MySQLi Connection' => 'new mysqli|mysqli_connect',
        'PDO Connection' => 'new PDO',
        'MySQL Connection' => 'mysql_connect',
        'Database Host' => 'host|server|localhost|127.0.0.1',
        'Database User' => 'user|username',
        'Database Password' => 'pass|password|pwd',
        'Database Name' => 'database|dbname|db_name',
        'Variables' => '\$db_|\$conn|\$connection',
        'Constants' => 'define.*DB_'
    ];
    
    echo "\nPatterns found:\n";
    foreach ($patterns as $name => $pattern) {
        if (preg_match("/$pattern/i", $content)) {
            echo "  ✅ $name\n";
        }
    }
    
    // Try to include and see what happens
    echo "\nIncluding $file:\n";
    ob_start();
    try {
        include $file;
        $output = ob_get_clean();
        if ($output) {
            echo "  Output: " . trim($output) . "\n";
        }
    } catch (Exception $e) {
        echo "  ❌ Error including file: " . $e->getMessage() . "\n";
        ob_end_clean();
    }
    
    // Check what variables/constants were set
    $vars = get_defined_vars();
    $dbVars = [];
    foreach ($vars as $key => $value) {
        if (strpos($key, 'db_') === 0 || 
            strpos($key, 'conn') !== false ||
            strpos($key, 'database') !== false) {
            $dbVars[$key] = $value;
        }
    }
    
    if (!empty($dbVars)) {
        echo "\nDatabase-related variables found:\n";
        foreach ($dbVars as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                echo "  \$" . $key . " = '" . $value . "'\n";
            } else if (is_object($value)) {
                echo "  \$" . $key . " = object(" . get_class($value) . ")\n";
            } else {
                echo "  \$" . $key . " = " . gettype($value) . "\n";
            }
        }
        $foundConfig = true;
    }
    
    // Check constants
    $constants = get_defined_constants(true);
    if (isset($constants['user'])) {
        $dbConstants = [];
        foreach ($constants['user'] as $key => $value) {
            if (strpos($key, 'DB_') === 0) {
                $dbConstants[$key] = $value;
            }
        }
        if (!empty($dbConstants)) {
            echo "\nDatabase constants found:\n";
            foreach ($dbConstants as $key => $value) {
                echo "  " . $key . " = '" . $value . "'\n";
            }
            $foundConfig = true;
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

if (!$foundConfig) {
    echo "❌ No standard database configuration found in any file.\n";
    echo "The application might use:\n";
    echo "1. Environment variables\n";
    echo "2. Configuration in other files\n"; 
    echo "3. Hardcoded connection strings\n";
    echo "4. Different variable names\n";
}

// Try common connection attempts
echo "🔄 Testing common database configurations:\n";
echo str_repeat("-", 50) . "\n";

$commonConfigs = [
    ['localhost', 'root', '', 'food_ordering'],
    ['127.0.0.1', 'root', '', 'food_ordering'],
    ['localhost', 'root', 'password', 'food_ordering'],
    ['localhost', 'root', '', 'food_ordering_main'],
];

foreach ($commonConfigs as $config) {
    list($host, $user, $pass, $dbname) = $config;
    
    echo "Testing: $user@$host/$dbname... ";
    
    $mysqli = @new mysqli($host, $user, $pass, $dbname);
    if ($mysqli->connect_error) {
        echo "❌ Failed: " . $mysqli->connect_error . "\n";
    } else {
        echo "✅ Success!\n";
        $mysqli->close();
        break;
    }
}