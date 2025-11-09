<?php
/**
 * Simple Unit Testing Framework - FIXED VERSION
 * No external dependencies required
 */

class TestCase {
    public $testName;  // Changed from protected to public
    protected $assertions = 0;
    protected $passed = 0;
    protected $failed = 0;
    
    public function assertEquals($expected, $actual, $message = "") {
        $this->assertions++;
        if ($expected === $actual) {
            $this->passed++;
            echo "✅ PASS: $message\n";
            return true;
        } else {
            $this->failed++;
            echo "❌ FAIL: $message (expected: '$expected', got: '$actual')\n";
            return false;
        }
    }
    
    public function assertTrue($condition, $message = "") {
        $this->assertEquals(true, $condition, $message);
    }
    
    public function assertFalse($condition, $message = "") {
        $this->assertEquals(false, $condition, $message);
    }
    
    public function assertNotNull($value, $message = "") {
        $this->assertions++;
        if ($value !== null) {
            $this->passed++;
            echo "✅ PASS: $message\n";
            return true;
        } else {
            $this->failed++;
            echo "❌ FAIL: $message (value is null)\n";
            return false;
        }
    }
    
    public function assertFileExists($file, $message = "") {
        $this->assertions++;
        if (file_exists($file)) {
            $this->passed++;
            echo "✅ PASS: $message\n";
            return true;
        } else {
            $this->failed++;
            echo "❌ FAIL: $message (file not found: $file)\n";
            return false;
        }
    }
    
    public function printResults() {
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "TEST RESULTS: {$this->testName}\n";
        echo "Assertions: {$this->assertions} | Passed: {$this->passed} | Failed: {$this->failed}\n";
        echo str_repeat("=", 50) . "\n";
        return $this->failed === 0;
    }
}

class TestRunner {
    private $tests = [];
    
    public function addTest($testName, $testFunction) {
        $this->tests[$testName] = $testFunction;
    }
    
    public function runAll() {
        $totalPassed = 0;
        $totalFailed = 0;
        
        echo "🚀 RUNNING UNIT TESTS\n";
        echo str_repeat("=", 50) . "\n";
        
        foreach ($this->tests as $testName => $testFunction) {
            echo "\n🧪 TEST: $testName\n";
            echo str_repeat("-", 50) . "\n";
            
            $testCase = new TestCase();
            $testCase->testName = $testName;  // Now this works because testName is public
            
            try {
                $testFunction($testCase);
                if ($testCase->printResults()) {
                    $totalPassed++;
                } else {
                    $totalFailed++;
                }
            } catch (Exception $e) {
                echo "❌ TEST CRASHED: " . $e->getMessage() . "\n";
                $totalFailed++;
            }
        }
        
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "FINAL RESULTS:\n";
        echo "✅ Tests Passed: $totalPassed\n";
        echo "❌ Tests Failed: $totalFailed\n";
        echo "📊 Success Rate: " . (count($this->tests) > 0 ? round(($totalPassed / count($this->tests)) * 100, 2) : 0) . "%\n";
        echo str_repeat("=", 50) . "\n";
        
        return $totalFailed === 0;
    }
}

// Global test runner instance
$GLOBALS['testRunner'] = new TestRunner();

// Function to register tests
function test($name, $function) {
    $GLOBALS['testRunner']->addTest($name, $function);
}

// Run tests when script ends
function runTests() {
    return $GLOBALS['testRunner']->runAll();
}