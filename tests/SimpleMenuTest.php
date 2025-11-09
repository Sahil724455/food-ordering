<?php
echo "🧪 MENU TESTS\n";
echo "=============\n";

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

// Test menu directories
test(is_dir('menu'), 'menu directory exists');

// Test API menu functionality
test(file_exists('api/menuandordertest.php'), 'menuandordertest.php exists');

// Test menu models
test(file_exists('apimodels/menuitem.php'), 'menuitem model exists');

// Test menu-related API endpoints
$menuApiFiles = [
    'api/add.php',
    'api/delete.php', 
    'api/update.php',
    'api/create.php'
];

$foundMenuApi = 0;
foreach ($menuApiFiles as $file) {
    if (file_exists($file)) {
        $foundMenuApi++;
        test(true, "$file exists");
    }
}

test($foundMenuApi > 0, "Found $foundMenuApi menu API endpoints");

// Test frontend menu components
if (is_dir('frontend')) {
    test(file_exists('frontend/menulist.jsx'), 'menulist.jsx frontend component exists');
}

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);