<?php
require_once 'TestFramework.php';

echo "API UNIT TESTS\n";
echo "==============\n";

// Test 1: API Directory Structure
test('API Directory Exists', function($test) {
    $test->assertTrue(is_dir('api'), 'API directory should exist');
});

// Test 2: API Files
test('API Files Exist', function($test) {
    if (is_dir('api')) {
        $apiFiles = scandir('api');
        $apiFiles = array_diff($apiFiles, ['.', '..']);
        $test->assertTrue(count($apiFiles) > 0, 'API directory should contain files');
        
        foreach ($apiFiles as $file) {
            $test->assertFileExists("api/$file", "API file $file should exist");
        }
    }
});

// Test 3: API Endpoint Syntax
test('API Endpoint Syntax', function($test) {
    if (is_dir('api')) {
        $apiFiles = glob('api/*.php');
        foreach ($apiFiles as $file) {
            $output = [];
            $returnCode = 0;
            exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
            $test->assertEquals(0, $returnCode, "API file $file should have valid PHP syntax");
        }
    }
});

// Test 4: API Configuration
test('API Configuration', function($test) {
    $test->assertFileExists('.htaccess', 'Main .htaccess should exist for API routing');
    
    if (is_dir('api')) {
        $test->assertFileExists('api/.htaccess', 'API .htaccess should exist');
    }
});

// Run all API tests
$success = runTests();
exit($success ? 0 : 1);