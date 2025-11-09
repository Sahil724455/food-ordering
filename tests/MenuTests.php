<?php
require_once 'TestFramework.php';

echo "MENU FUNCTIONALITY UNIT TESTS\n";
echo "=============================\n";

// Test 1: Menu Table Creation
test('Menu Table Creation Script', function($test) {
    $test->assertFileExists('create_menu_table.php', 'Menu table creation script should exist');
    
    if (file_exists('create_menu_table.php')) {
        $content = file_get_contents('create_menu_table.php');
        $test->assertTrue(strpos($content, 'CREATE TABLE') !== false, 
                         'Should contain CREATE TABLE statement');
        $test->assertTrue(strpos($content, 'menu') !== false, 
                         'Should reference menu table');
    }
});

// Test 2: Menu Creation Script
test('Menu Creation Script', function($test) {
    $test->assertFileExists('create_menu.php', 'Menu creation script should exist');
    
    if (file_exists('create_menu.php')) {
        $content = file_get_contents('create_menu.php');
        $test->assertTrue(strpos($content, 'INSERT') !== false || 
                         strpos($content, 'menu') !== false,
                         'Should contain menu-related operations');
    }
});

// Test 3: Menu Data Insertion
test('Menu Data Insertion', function($test) {
    $test->assertFileExists('insert_menu.php', 'Menu data insertion script should exist');
    
    if (file_exists('insert_menu.php')) {
        $content = file_get_contents('insert_menu.php');
        $test->assertTrue(strpos($content, 'INSERT INTO') !== false, 
                         'Should contain INSERT INTO statements');
    }
});

// Test 4: Sample Data
test('Sample Data Script', function($test) {
    $test->assertFileExists('insert_sample_data.php', 'Sample data script should exist');
});

// Run all menu tests
$success = runTests();
exit($success ? 0 : 1);