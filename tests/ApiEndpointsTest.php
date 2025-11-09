<?php
use PHPUnit\Framework\TestCase;

class ApiEndpointsTest extends TestCase
{
    public function testApiFilesExist()
    {
        if (!is_dir('api')) {
            $this->markTestSkipped('API directory not found');
        }
        
        $apiFiles = glob('api/*.php');
        $this->assertGreaterThan(0, count($apiFiles), 'Should find PHP files in API directory');
        
        foreach ($apiFiles as $file) {
            $this->assertFileExists($file, "API file $file should exist");
            
            // Check syntax
            $output = [];
            $returnCode = 0;
            exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
            $this->assertEquals(0, $returnCode, "PHP syntax error in API file $file");
        }
        
        echo "✅ Found " . count($apiFiles) . " API files with valid syntax\n";
    }
    
    public function testHtaccessExists()
    {
        $htaccessFiles = [
            '.htaccess',
            'api/.htaccess'
        ];
        
        $found = false;
        foreach ($htaccessFiles as $file) {
            if (file_exists($file)) {
                $this->assertFileExists($file, "HTAccess file $file should exist");
                $found = true;
                echo "✅ Found: $file\n";
            }
        }
        
        if (!$found) {
            $this->markTestSkipped('No .htaccess files found');
        }
    }
}
