<?php
echo "🍕 FOOD ORDERING SYSTEM - COMPREHENSIVE TEST\n";
echo "============================================\n";

function testFile($file, $description) {
    if (file_exists($file)) {
        echo "✅ $description: $file exists\n";
        
        // Check PHP syntax
        $output = [];
        $returnCode = 0;
        exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
        if ($returnCode === 0) {
            echo "   ✅ Valid PHP syntax\n";
        } else {
            echo "   ❌ Syntax error: " . implode(', ', $output) . "\n";
        }
        return true;
    } else {
        echo "❌ $description: $file missing\n";
        return false;
    }
}

function testDirectory($dir, $description) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        $files = array_diff($files, ['.', '..']);
        echo "✅ $description: $dir exists (" . count($files) . " files)\n";
        return true;
    } else {
        echo "❌ $description: $dir missing\n";
        return false;
    }
}

// Test core files
echo "\n📁 CORE FILES:\n";
$coreFiles = [
    'add_payment_method.php' => 'Payment method',
    'create_menu_table.php' => 'Menu table creation',
    'create_menu.php' => 'Menu creation',
    'createdb.php' => 'Database setup',
    'db.php' => 'Database config',
    'index.php' => 'Main application',
    'insert_menu.php' => 'Menu data',
    'insert_sample_data.php' => 'Sample data',
    'test_all.php' => 'Test suite',
    'test_db.php' => 'Database test'
];

foreach ($coreFiles as $file => $desc) {
    testFile($file, $desc);
}

// Test directories
echo "\n📁 DIRECTORIES:\n";
$directories = [
    'api' => 'API endpoints',
    'migrations' => 'Database migrations',
    'App models' => 'Application models',
    'apimodels' => 'API models',
    'database' => 'Database files',
    'swagger' => 'API documentation'
];

foreach ($directories as $dir => $desc) {
    testDirectory($dir, $desc);
}

echo "\n🎯 TESTING COMPLETE\n";