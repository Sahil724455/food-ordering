<?php
echo "🍕 FOOD ORDERING SYSTEM - UNIT TEST SUITE\n";
echo "=========================================\n";

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
    
    echo "\n🚀 RUNNING: $suiteName Tests\n";
    
    $output = [];
    $returnCode = 0;
    exec("php " . escapeshellarg($testFile) . " 2>&1", $output, $returnCode);
    
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    $results[$suiteName] = ($returnCode === 0);
    echo "RESULT: " . ($returnCode === 0 ? "✅ PASSED" : "❌ FAILED") . "\n";
}

echo "\n📊 FINAL SUMMARY:\n";
$passed = 0;
$failed = 0;

foreach ($results as $suiteName => $result) {
    echo $result ? "✅ " : "❌ ";
    echo "$suiteName: " . ($result ? "PASSED" : "FAILED") . "\n";
    
    if ($result) $passed++;
    else $failed++;
}

echo "\nTOTAL: $passed passed, $failed failed\n";
echo "SUCCESS RATE: " . round(($passed / count($results)) * 100, 2) . "%\n";
exit($failed === 0 ? 0 : 1);