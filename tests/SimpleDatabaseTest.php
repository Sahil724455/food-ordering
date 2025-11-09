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

test(file_exists('db.php'), 'db.php should exist');
test(file_exists('createdb.php'), 'createdb.php should exist');
test(file_exists('test_db.php'), 'test_db.php should exist');

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);