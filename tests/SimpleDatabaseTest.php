<?php
echo "🧪 DATABASE TESTS\n";
echo "================\n";

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

// Test files that actually exist
test(file_exists('db.php'), 'db.php exists');
test(file_exists('createdb.php'), 'createdb.php exists');
test(file_exists('test_db.php'), 'test_db.php exists');

// Test database directories
test(is_dir('database'), 'database directory exists');
test(is_dir('migrations'), 'migrations directory exists');

// Test database models
test(is_dir('App models'), 'App models directory exists');
test(is_dir('apimodels'), 'apimodels directory exists');

// Check if we can load db.php
if (file_exists('db.php')) {
    try {
        include_once 'db.php';
        test(true, 'db.php loads without syntax errors');
    } catch (Exception $e) {
        test(false, 'db.php has errors: ' . $e->getMessage());
    }
}

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);