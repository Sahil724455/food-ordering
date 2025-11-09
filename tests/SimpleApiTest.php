<?php
echo "🧪 SIMPLE API TESTS\n";
echo "===================\n";

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

test(is_dir('api'), 'api directory should exist');

if (is_dir('api')) {
    $files = scandir('api');
    $files = array_diff($files, ['.', '..']);
    test(count($files) > 0, 'api directory should contain files');
}

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);