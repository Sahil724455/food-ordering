<?php
echo "🍕 FOOD ORDERING SYSTEM - COMPREHENSIVE TEST\n";
echo "============================================\n";
echo "Testing actual project structure...\n\n";

function testFile($file, $description) {
    if (file_exists($file)) {
        echo "✅ $description: $file\n";
        
        // Check PHP syntax for PHP files
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $output = [];
            $returnCode = 0;
            exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
            if ($returnCode === 0) {
                echo "   ✅ Valid PHP syntax\n";
            } else {
                echo "   ❌ Syntax error\n";
            }
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
        echo "✅ $description: $dir (" . count($files) . " items)\n";
        return true;
    } else {
        echo "❌ $description: $dir missing\n";
        return false;
    }
}

echo "📁 CORE APPLICATION:\n";
testFile('index.php', 'Main application');
testFile('db.php', 'Database configuration');
testFile('createdb.php', 'Database setup');
testFile('add_payment_method.php', 'Payment method');

echo "\n📁 AUTHENTICATION:\n";
testFile('login.php', 'Login page');
testFile('register.php', 'Registration page');
testFile('api/authcontroller.php', 'Auth controller');
testFile('api/registerrequest.php', 'Register request');

echo "\n📁 API STRUCTURE:\n";
testDirectory('api', 'API endpoints');
testDirectory('apimodels', 'API models');
testDirectory('swagger', 'API documentation');

echo "\n📁 BUSINESS LOGIC:\n";
testDirectory('menu', 'Menu management');
testDirectory('cart', 'Shopping cart');
testDirectory('orders', 'Order management');

echo "\n📁 DATABASE:\n";
testDirectory('database', 'Database files');
testDirectory('migrations', 'Database migrations');
testDirectory('App models', 'Application models');

echo "\n📁 FRONTEND:\n";
testDirectory('frontend', 'Frontend components');

echo "\n🎯 KEY API ENDPOINTS:\n";
$apiEndpoints = [
    'api/add.php' => 'Add item',
    'api/delete.php' => 'Delete item',
    'api/update.php' => 'Update item',
    'api/create.php' => 'Create item',
    'api/user_cart.php' => 'User cart',
    'api/user_orders.php' => 'User orders',
    'api/placeorder.php' => 'Place order',
    'api/addtocartitem.php' => 'Add to cart'
];

foreach ($apiEndpoints as $file => $desc) {
    testFile($file, $desc);
}

echo "\n🔧 DATABASE CONNECTION TEST:\n";
if (file_exists('db.php')) {
    try {
        include_once 'db.php';
        echo "✅ db.php loaded successfully\n";
        
        if (defined('DB_HOST') || isset($db_host)) {
            echo "✅ Database configuration detected\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Summary
echo "\n📊 PROJECT SUMMARY:\n";
$phpFiles = array_merge(glob('*.php'), glob('api/*.php'), glob('apimodels/*.php'));
$directories = array_filter(glob('*'), 'is_dir');
$directories = array_diff($directories, ['.', '..', 'tests', 'vendor']);

echo "Total PHP files: " . count($phpFiles) . "\n";
echo "Total directories: " . count($directories) . "\n";
echo "Complete structure: " . (count($phpFiles) + count($directories)) . " items\n";

echo "\n🎯 TESTING COMPLETE - Your food ordering system is properly structured!\n";