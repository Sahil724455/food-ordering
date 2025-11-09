<?php
require_once 'TestFramework.php';

echo "PAYMENT FUNCTIONALITY UNIT TESTS\n";
echo "===============================\n";

// Test 1: Payment Method Script
test('Payment Method Script', function($test) {
    $test->assertFileExists('add_payment_method.php', 'Payment method script should exist');
    
    if (file_exists('add_payment_method.php')) {
        $content = file_get_contents('add_payment_method.php');
        $test->assertTrue(strpos($content, 'payment') !== false || 
                         strpos($content, 'card') !== false ||
                         strpos($content, 'INSERT') !== false,
                         'Should contain payment-related logic');
    }
});

// Test 2: Form Processing
test('Payment Form Processing', function($test) {
    if (file_exists('add_payment_method.php')) {
        $content = file_get_contents('add_payment_method.php');
        
        // Check for common form elements
        $hasForm = strpos($content, '<form') !== false || 
                  strpos($content, '$_POST') !== false ||
                  strpos($content, '$_GET') !== false;
        
        $test->assertTrue($hasForm, 'Should contain form processing logic');
    }
});

// Run all payment tests
$success = runTests();
exit($success ? 0 : 1);