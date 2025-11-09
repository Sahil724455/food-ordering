<?php
echo "🍕 FOOD ORDERING SYSTEM - SIMPLE UNIT TESTS\n";
echo "===========================================\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Running from: " . getcwd() . "\n";
echo "=========================================\n\n";

$testSuites = [
    'Database' => 'tests/SimpleDatabaseTest.php',
    'Menu' => 'tests/SimpleMenuTest.php', 
    'Payment' => 'tests/SimplePaymentTest.php',
    'API' => 'tests/SimpleApiTest.php'
];

$results = [];

foreach ($testSuites as $suiteName => $testFile) {
    if (!file_exists($testFile)) {
        echo "❌ Test suite not found: $testFile\n";
        $results[$suiteName] = false;
        continue;
    }
    
    echo "\n" . str_repeat("🚀", 20) . "\n";
    echo "RUNNING: $suiteName Tests\n";
    echo str_repeat("🚀", 20) . "\n";
    
    // Run test suite and capture exit code
    $output = [];
    $returnCode = 0;
    exec("php " . escapeshellarg($testFile) . " 2>&1", $output, $returnCode);
    
    // Display output
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    $results[$suiteName] = ($returnCode === 0);
    
    echo "\n" . str_repeat("─", 50) . "\n";
    echo "RESULT: " . ($returnCode === 0 ? "✅ PASSED" : "❌ FAILED") . "\n";
    echo str_repeat("─", 50) . "\n";
}

// Final summary
echo "\n" . str_repeat("📊", 20) . "\n";
echo "FINAL TEST SUMMARY\n";
echo str_repeat("📊", 20) . "\n";

$passed = 0;
$failed = 0;

foreach ($results as $suiteName => $result) {
    echo $result ? "✅ " : "❌ ";
    echo "$suiteName: " . ($result ? "PASSED" : "FAILED") . "\n";
    
    if ($result) $passed++;
    else $failed++;
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "TOTAL: $passed passed, $failed failed\n";
echo "SUCCESS RATE: " . round(($passed / count($results)) * 100, 2) . "%\n";

$overallSuccess = ($failed === 0);
echo "OVERALL: " . ($overallSuccess ? "✅ ALL TESTS PASSED" : "❌ SOME TESTS FAILED") . "\n";
echo str_repeat("=", 50) . "\n";

exit($overallSuccess ? 0 : 1);