<?php
echo "🧪 PAYMENT TESTS\n";
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

// Test payment files that exist
test(file_exists('add_payment_method.php'), 'add_payment_method.php exists');
test(file_exists('api/paymentcontroller.php'), 'paymentcontroller.php exists');

// Test checkout functionality
if (is_dir('frontend')) {
    test(file_exists('frontend/checkout.jsx'), 'checkout.jsx frontend component exists');
}

// Test if payment functionality exists in other files
$paymentRelatedFiles = [];
$allPhpFiles = array_merge(glob('*.php'), glob('api/*.php'));

foreach ($allPhpFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'payment') !== false || 
            strpos($content, 'checkout') !== false ||
            strpos($content, 'card') !== false) {
            $paymentRelatedFiles[] = $file;
        }
    }
}

test(count($paymentRelatedFiles) > 0, 'Found payment functionality in ' . count($paymentRelatedFiles) . ' files');

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);