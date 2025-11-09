<?php
echo "🧪 SIMPLE PAYMENT TESTS\n";
echo "======================\n";

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

test(file_exists('add_payment_method.php'), 'add_payment_method.php should exist');

if (file_exists('add_payment_method.php')) {
    $content = file_get_contents('add_payment_method.php');
    test(strlen($content) > 0, 'add_payment_method.php should not be empty');
    test(strpos($content, '<?php') !== false, 'add_payment_method.php should be a PHP file');
}

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);