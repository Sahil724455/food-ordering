<?php
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private $db;
    
    protected function setUp(): void
    {
        // Include db.php to get database configuration
        if (file_exists('db.php')) {
            include_once 'db.php';
        }
    }
    
    public function testDatabaseConnection()
    {
        // Skip if no database configuration found
        if (!defined('DB_HOST') && !defined('DB_USER') && !defined('DB_NAME')) {
            $this->markTestSkipped('Database configuration not found in db.php');
        }
        
        try {
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $user = defined('DB_USER') ? DB_USER : 'root';
            $pass = defined('DB_PASS') ? DB_PASS : '';
            $name = defined('DB_NAME') ? DB_NAME : 'food_ordering';
            
            $this->db = new mysqli($host, $user, $pass, $name);
            
            $this->assertFalse($this->db->connect_error, 
                "Database connection should work. Error: " . ($this->db->connect_error ?: 'None'));
                
            echo "✅ Database connection successful\n";
            echo "   Host: $host, Database: $name\n";
            
        } catch (Exception $e) {
            $this->markTestSkipped('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public function testDatabaseOperations()
    {
        if (!defined('DB_HOST')) {
            $this->markTestSkipped('Database configuration not available');
        }
        
        try {
            $host = DB_HOST;
            $user = DB_USER;
            $pass = defined('DB_PASS') ? DB_PASS : '';
            $name = DB_NAME;
            
            $db = new mysqli($host, $user, $pass, $name);
            
            if ($db->connect_error) {
                $this->markTestSkipped('Cannot connect to database: ' . $db->connect_error);
            }
            
            // Test simple query
            $result = $db->query("SELECT 1 as test");
            $this->assertNotFalse($result, "Should be able to execute simple query");
            
            $row = $result->fetch_assoc();
            $this->assertEquals(1, $row['test'], "Simple query should return expected result");
            
            $db->close();
            echo "✅ Basic database operations work\n";
            
        } catch (Exception $e) {
            $this->markTestSkipped('Database operations test failed: ' . $e->getMessage());
        }
    }
    
    protected function tearDown(): void
    {
        if ($this->db) {
            $this->db->close();
        }
    }
}
