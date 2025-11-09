<?php
echo "🧪 API TESTS\n";
echo "============\n";

$passed = 0;
$failed = 0;

function test($condition, $message) {
    global $passed, $failed;
    if ($condition) {
        echo "✅ PASS: $message\n";
        $passed++;
    } else {
        echo "❌ FAIL: $message\n";
        $failed++;
    }
}

// Test API directory structure
test(is_dir('api'), 'api directory exists');

// Count API files
$apiFiles = scandir('api');
$apiFiles = array_diff($apiFiles, ['.', '..']);
test(count($apiFiles) > 0, 'api directory contains ' . count($apiFiles) . ' files');

// Test key API controllers
$keyApiFiles = [
    'api/authcontroller.php',
    'api/cartcontroller.php',
    'api/ordercontroller.php',
    'api/paymentcontroller.php',
    'api/cors.php',
    'api/api.php'
];

$foundKeyFiles = 0;
foreach ($keyApiFiles as $file) {
    if (file_exists($file)) {
        $foundKeyFiles++;
        test(true, "$file exists");
        
        // Check syntax
        $output = [];
        $returnCode = 0;
        exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
        if ($returnCode === 0) {
            echo "   ✅ Valid PHP syntax\n";
        } else {
            echo "   ❌ Syntax error\n";
        }
    }
}

test($foundKeyFiles > 0, "Found $foundKeyFiles key API controllers");

// Test API models directory
test(is_dir('apimodels'), 'apimodels directory exists');

// Test Swagger documentation
test(is_dir('swagger'), 'swagger documentation exists');
test(file_exists('swagger/openapi.json'), 'openapi.json exists');

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);