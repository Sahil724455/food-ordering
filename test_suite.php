<?php
/**
 * Food Ordering System - Complete Test Suite
 * No external dependencies required
 */

class FoodOrderingTestSuite {
    private $results = [];
    
    public function runAllTests() {
        echo "=========================================\n";
        echo "🍕 FOOD ORDERING SYSTEM - TEST SUITE\n";
        echo "=========================================\n";
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo "Running from: " . getcwd() . "\n";
        echo "=========================================\n\n";
        
        $this->testFileStructure();
        $this->testDatabaseConfiguration();
        $this->testPhpSyntax();
        $this->testApiEndpoints();
        $this->testMenuFunctionality();
        $this->testPaymentFunctionality();
        
        $this->printResults();
        return $this->getFinalResult();
    }
    
    private function testFileStructure() {
        $this->logSection("📁 FILE STRUCTURE");
        
        // Core application files
        $coreFiles = [
            'add_payment_method.php' => 'Payment method management',
            'create_menu_table.php' => 'Menu table creation',
            'create_menu.php' => 'Menu creation',
            'createdb.php' => 'Database setup',
            'db.php' => 'Database configuration',
            'index.php' => 'Main application entry',
            'insert_menu.php' => 'Menu data insertion',
            'insert_sample_data.php' => 'Sample data population',
            'test_all.php' => 'Comprehensive testing',
            'test_db.php' => 'Database testing',
            'test-auth.html' => 'Authentication test'
        ];
        
        foreach ($coreFiles as $file => $description) {
            $this->checkFileExists($file, $description);
        }
        
        // Directory structure
        $directories = [
            'api' => 'API endpoints',
            'migrations' => 'Database migrations',
            'App models' => 'Application models',
            'apimodels' => 'API models',
            'database' => 'Database files',
            'swagger' => 'API documentation'
        ];
        
        foreach ($directories as $dir => $description) {
            $this->checkDirectoryExists($dir, $description);
        }
        
        // Configuration files
        $this->checkFileExists('.htaccess', 'Apache configuration');
    }
    
    private function testDatabaseConfiguration() {
        $this->logSection("🗄️ DATABASE CONFIGURATION");
        
        if (!$this->checkFileExists('db.php', 'Database configuration file')) {
            return;
        }
        
        // Test database configuration
        try {
            include_once 'db.php';
            $this->addResult('DB_CONFIG_LOAD', 'PASS', 'db.php loaded without syntax errors');
            
            // Check for different configuration styles
            $configFound = false;
            
            if (defined('DB_HOST')) {
                $this->addResult('DB_CONSTANTS', 'PASS', 'Database constants defined');
                $configFound = true;
                echo "   Host: " . DB_HOST . "\n";
                echo "   User: " . DB_USER . "\n";
                echo "   Database: " . DB_NAME . "\n";
            }
            
            if (isset($db_host)) {
                $this->addResult('DB_VARIABLES', 'PASS', 'Database variables set');
                $configFound = true;
                echo "   Host: $db_host\n";
                echo "   User: $db_user\n";
                echo "   Database: $db_name\n";
            }
            
            if (!$configFound) {
                $this->addResult('DB_CONFIG', 'FAIL', 'No database configuration found');
                return;
            }
            
            // Test database connection
            $this->testDatabaseConnection();
            
        } catch (Exception $e) {
            $this->addResult('DB_CONFIG_LOAD', 'FAIL', 'Error loading db.php: ' . $e->getMessage());
        }
    }
    
    private function testDatabaseConnection() {
        try {
            $host = defined('DB_HOST') ? DB_HOST : (isset($db_host) ? $db_host : 'localhost');
            $user = defined('DB_USER') ? DB_USER : (isset($db_user) ? $db_user : 'root');
            $pass = defined('DB_PASS') ? DB_PASS : (isset($db_pass) ? $db_pass : '');
            $name = defined('DB_NAME') ? DB_NAME : (isset($db_name) ? $db_name : 'food_ordering');
            
            $db = @new mysqli($host, $user, $pass, $name);
            
            if ($db->connect_error) {
                $this->addResult('DB_CONNECTION', 'FAIL', 'Database connection failed: ' . $db->connect_error);
                return;
            }
            
            $this->addResult('DB_CONNECTION', 'PASS', 'Database connection successful');
            
            // Test basic query
            $result = $db->query("SELECT 1 as test_value");
            if ($result) {
                $row = $result->fetch_assoc();
                $this->addResult('DB_QUERY', 'PASS', 'Basic database query works');
            } else {
                $this->addResult('DB_QUERY', 'FAIL', 'Basic database query failed');
            }
            
            $db->close();
            
        } catch (Exception $e) {
            $this->addResult('DB_CONNECTION', 'FAIL', 'Database connection error: ' . $e->getMessage());
        }
    }
    
    private function testPhpSyntax() {
        $this->logSection("🔍 PHP SYNTAX CHECK");
        
        $phpFiles = array_merge(
            glob('*.php'),
            glob('api/*.php'),
            glob('migrations/*.php'),
            glob('database/*.php')
        );
        
        if (empty($phpFiles)) {
            $this->addResult('PHP_SYNTAX', 'SKIP', 'No PHP files found to check');
            return;
        }
        
        $validFiles = 0;
        $totalFiles = 0;
        
        foreach ($phpFiles as $file) {
            if (!file_exists($file)) continue;
            
            $totalFiles++;
            $output = [];
            $returnCode = 0;
            exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
            
            if ($returnCode === 0) {
                $validFiles++;
                echo "   ✅ $file\n";
            } else {
                echo "   ❌ $file: " . implode(', ', $output) . "\n";
            }
        }
        
        if ($validFiles === $totalFiles) {
            $this->addResult('PHP_SYNTAX', 'PASS', "All $totalFiles PHP files have valid syntax");
        } else {
            $this->addResult('PHP_SYNTAX', 'FAIL', "$validFiles/$totalFiles PHP files have valid syntax");
        }
    }
    
    private function testApiEndpoints() {
        $this->logSection("🌐 API ENDPOINTS");
        
        if (!is_dir('api')) {
            $this->addResult('API_DIRECTORY', 'SKIP', 'API directory not found');
            return;
        }
        
        $apiFiles = scandir('api');
        $apiFiles = array_diff($apiFiles, ['.', '..']);
        
        if (empty($apiFiles)) {
            $this->addResult('API_FILES', 'FAIL', 'API directory is empty');
            return;
        }
        
        $this->addResult('API_FILES', 'PASS', 'API directory contains ' . count($apiFiles) . ' files');
        
        foreach ($apiFiles as $file) {
            echo "   📄 $file\n";
        }
        
        // Check for common API files
        $commonApiFiles = ['index.php', 'menu.php', 'orders.php', 'payment.php'];
        foreach ($commonApiFiles as $apiFile) {
            if (file_exists("api/$apiFile")) {
                $this->addResult("API_$apiFile", 'PASS', "API endpoint $apiFile exists");
            }
        }
    }
    
    private function testMenuFunctionality() {
        $this->logSection("📋 MENU FUNCTIONALITY");
        
        $menuFiles = [
            'create_menu_table.php' => 'Menu table creation',
            'create_menu.php' => 'Menu creation',
            'insert_menu.php' => 'Menu data insertion'
        ];
        
        foreach ($menuFiles as $file => $description) {
            $this->checkFileExists($file, $description);
        }
    }
    
    private function testPaymentFunctionality() {
        $this->logSection("💳 PAYMENT FUNCTIONALITY");
        
        $this->checkFileExists('add_payment_method.php', 'Payment method management');
    }
    
    private function checkFileExists($file, $description) {
        if (file_exists($file)) {
            $this->addResult("FILE_$file", 'PASS', "$description: $file exists");
            return true;
        } else {
            $this->addResult("FILE_$file", 'FAIL', "$description: $file missing");
            return false;
        }
    }
    
    private function checkDirectoryExists($dir, $description) {
        if (is_dir($dir)) {
            $itemCount = count(array_diff(scandir($dir), ['.', '..']));
            $this->addResult("DIR_$dir", 'PASS', "$description: $dir exists ($itemCount items)");
            return true;
        } else {
            $this->addResult("DIR_$dir", 'FAIL', "$description: $dir missing");
            return false;
        }
    }
    
    private function addResult($test, $status, $message) {
        $this->results[] = [
            'test' => $test,
            'status' => $status,
            'message' => $message
        ];
    }
    
    private function logSection($title) {
        echo "\n" . $title . "\n";
        echo str_repeat("=", strlen($title)) . "\n";
    }
    
    private function printResults() {
        $this->logSection("📊 TEST RESULTS SUMMARY");
        
        $passed = 0;
        $failed = 0;
        $skipped = 0;
        
        foreach ($this->results as $result) {
            $icon = $result['status'] === 'PASS' ? '✅' : 
                   ($result['status'] === 'FAIL' ? '❌' : '⚠️');
            
            echo "$icon {$result['message']}\n";
            
            if ($result['status'] === 'PASS') $passed++;
            if ($result['status'] === 'FAIL') $failed++;
            if ($result['status'] === 'SKIP') $skipped++;
        }
        
        echo "\n" . str_repeat("=", 40) . "\n";
        echo "TOTAL: {$passed} passed, {$failed} failed, {$skipped} skipped\n";
        echo str_repeat("=", 40) . "\n";
    }
    
    private function getFinalResult() {
        foreach ($this->results as $result) {
            if ($result['status'] === 'FAIL') {
                return false;
            }
        }
        return true;
    }
}

// Run the test suite
$testSuite = new FoodOrderingTestSuite();
$success = $testSuite->runAllTests();

echo $success ? 
    "\n🎉 ALL TESTS PASSED! Your food ordering system is properly structured.\n" :
    "\n❌ SOME TESTS FAILED! Please check the issues above.\n";

exit($success ? 0 : 1);