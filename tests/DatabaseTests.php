<?php
require_once 'TestFramework.php';

echo "DATABASE UNIT TESTS\n";
echo "==================\n";

// Test 1: Database Configuration
test('Database Configuration File', function($test) {
    $test->assertFileExists('db.php', 'db.php should exist');
    
    if (file_exists('db.php')) {
        include_once 'db.php';
        $test->assertTrue(true, 'db.php should load without errors');
    }
});

// Test 2: Database Connection
test('Database Connection', function($test) {
    if (!file_exists('db.php')) {
        $test->assertTrue(false, 'db.php missing - skipping connection test');
        return;
    }
    
    include_once 'db.php';
    
    // Try to detect configuration
    $host = defined('DB_HOST') ? DB_HOST : (isset($db_host) ? $db_host : 'localhost');
    $user = defined('DB_USER') ? DB_USER : (isset($db_user) ? $db_user : 'root');
    $pass = defined('DB_PASS') ? DB_PASS : (isset($db_pass) ? $db_pass : '');
    $name = defined('DB_NAME') ? DB_NAME : (isset($db_name) ? $db_name : 'food_ordering');
    
    try {
        $db = @new mysqli($host, $user, $pass, $name);
        $test->assertFalse($db->connect_error, 'Database connection should work');
        
        if (!$db->connect_error) {
            // Test basic query
            $result = $db->query("SELECT 1 as test_value");
            $test->assertTrue($result !== false, 'Should execute basic query');
            
            if ($result) {
                $row = $result->fetch_assoc();
                $test->assertEquals(1, $row['test_value'], 'Query should return correct value');
            }
            
            $db->close();
        }
    } catch (Exception $e) {
        $test->assertTrue(false, 'Database connection failed: ' . $e->getMessage());
    }
});

// Test 3: Database Creation Script
test('Database Creation Script', function($test) {
    $test->assertFileExists('createdb.php', 'Database creation script should exist');
    
    if (file_exists('createdb.php')) {
        $content = file_get_contents('createdb.php');
        $test->assertTrue(strpos($content, 'CREATE DATABASE') !== false || 
                         strpos($content, 'CREATE TABLE') !== false,
                         'Should contain database/table creation SQL');
    }
});

// Run all database tests
$success = runTests();
exit($success ? 0 : 1);