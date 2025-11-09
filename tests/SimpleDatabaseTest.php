<?php
echo "🧪 SIMPLE DATABASE TESTS\n";
echo "=======================\n";

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

// Test 1: Database file exists
test(file_exists('db.php'), 'db.php should exist');

// Test 2: Database file can be included
if (file_exists('db.php')) {
    try {
        include_once 'db.php';
        test(true, 'db.php can be included without errors');
    } catch (Exception $e) {
        test(false, 'db.php inclusion failed: ' . $e->getMessage());
    }
}

// Test 3: Database creation script exists
test(file_exists('createdb.php'), 'createdb.php should exist');

// Test 4: Test database script exists
test(file_exists('test_db.php'), 'test_db.php should exist');

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);