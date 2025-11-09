<?php
echo "🧪 SIMPLE MENU TESTS\n";
echo "====================\n";

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

$menuFiles = ['create_menu_table.php', 'create_menu.php', 'insert_menu.php', 'insert_sample_data.php'];
foreach ($menuFiles as $file) {
    test(file_exists($file), "$file should exist");
}

echo "\n📊 RESULTS: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);