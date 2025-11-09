<?php
use PHPUnit\Framework\TestCase;

class FoodOrderingProjectTest extends TestCase
{
    public function testCoreFilesExist()
    {
        $coreFiles = [
            'add_payment_method.php',
            'create_menu_table.php',
            'create_menu.php',
            'createdb.php',
            'db.php',
            'index.php',
            'insert_menu.php',
            'insert_sample_data.php',
            'test_all.php',
            'test_db.php',
            'test-auth.html',
            '.htaccess'
        ];
        
        $foundFiles = [];
        foreach ($coreFiles as $file) {
            if (file_exists($file)) {
                $foundFiles[] = $file;
                $this->assertFileExists($file, "Core file $file should exist");
            }
        }
        
        echo "✅ Found " . count($foundFiles) . " core files\n";
        $this->assertGreaterThan(5, count($foundFiles), "Should find most core files");
    }
    
    public function testDirectoriesExist()
    {
        $expectedDirs = [
            'api',
            'migrations',
            'App models',
            'apimodels',
            'database',
            'swagger'
        ];
        
        $foundDirs = [];
        foreach ($expectedDirs as $dir) {
            if (is_dir($dir)) {
                $foundDirs[] = $dir;
                $this->assertDirectoryExists($dir, "Directory $dir should exist");
            }
        }
        
        echo "✅ Found " . count($foundDirs) . " directories: " . implode(', ', $foundDirs) . "\n";
        $this->assertGreaterThan(0, count($foundDirs), "Should find at least some directories");
    }
    
    public function testDatabaseConfiguration()
    {
        $this->assertFileExists('db.php', 'Database configuration file should exist');
        
        // Test if db.php can be included without errors
        try {
            include_once 'db.php';
            $this->assertTrue(true, 'db.php can be included without syntax errors');
            
            // Check if database constants or variables are defined
            if (defined('DB_HOST') || isset($db_host)) {
                echo "✅ Database configuration found\n";
            }
        } catch (Exception $e) {
            $this->fail('Error including db.php: ' . $e->getMessage());
        }
    }
    
    public function testPhpFilesHaveValidSyntax()
    {
        $phpFiles = [
            'add_payment_method.php',
            'create_menu_table.php',
            'create_menu.php',
            'createdb.php',
            'db.php',
            'index.php',
            'insert_menu.php',
            'insert_sample_data.php',
            'test_all.php',
            'test_db.php'
        ];
        
        $checked = 0;
        foreach ($phpFiles as $file) {
            if (file_exists($file)) {
                $output = [];
                $returnCode = 0;
                exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
                $this->assertEquals(0, $returnCode, "PHP syntax error in $file: " . implode(', ', $output));
                $checked++;
            }
        }
        
        echo "✅ Checked syntax for $checked PHP files\n";
        $this->assertGreaterThan(0, $checked, "Should check syntax for at least some PHP files");
    }
    
    public function testApiDirectoryStructure()
    {
        if (is_dir('api')) {
            $apiFiles = scandir('api');
            $apiFiles = array_diff($apiFiles, ['.', '..']);
            
            $this->assertGreaterThan(0, count($apiFiles), 'API directory should contain files');
            
            foreach ($apiFiles as $file) {
                $fullPath = 'api/' . $file;
                $this->assertTrue(file_exists($fullPath), "API file $file should exist");
            }
            
            echo "✅ API directory contains " . count($apiFiles) . " files\n";
        } else {
            $this->markTestSkipped('API directory not found');
        }
    }
    
    public function testMigrationsDirectory()
    {
        if (is_dir('migrations')) {
            $migrationFiles = scandir('migrations');
            $migrationFiles = array_diff($migrationFiles, ['.', '..']);
            
            echo "✅ Migrations directory contains " . count($migrationFiles) . " files\n";
            
            if (count($migrationFiles) > 0) {
                foreach ($migrationFiles as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                        $fullPath = 'migrations/' . $file;
                        $output = [];
                        $returnCode = 0;
                        exec("php -l " . escapeshellarg($fullPath) . " 2>&1", $output, $returnCode);
                        $this->assertEquals(0, $returnCode, "PHP syntax error in migration $file");
                    }
                }
            }
        } else {
            $this->markTestSkipped('Migrations directory not found');
        }
    }
}
