<?php
/**
 * NawiriKe CRM Database Connection Test
 * Test script to verify database connection works
 */

// Include the database class
require_once 'database.php';

// Create database instance
$database = new Database();

// Test connection
echo "<h2>NawiriKe CRM - Database Connection Test</h2>";
echo "<hr>";

try {
    // Attempt to connect
    $conn = $database->connect();
    
    if ($conn) {
        echo "<p style='color: green; font-weight: bold;'>✓ SUCCESS: Database connection established!</p>";
        
        // Display connection info
        echo "<h3>Connection Details:</h3>";
        echo "<ul>";
        echo "<li><strong>Database:</strong> nawirike</li>";
        echo "<li><strong>Host:</strong> localhost</li>";
        echo "<li><strong>Username:</strong> root</li>";
        echo "<li><strong>Connection Type:</strong> PDO MySQL</li>";
        echo "</ul>";
        
        // Test a simple query to show tables
        echo "<h3>Available Tables:</h3>";
        $query = "SHOW TABLES";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>" . htmlspecialchars($table) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: orange;'>⚠ No tables found in database. You may need to run your schema script.</p>";
        }
        
        // Test database version
        echo "<h3>MySQL Information:</h3>";
        $version = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
        echo "<p><strong>MySQL Version:</strong> " . htmlspecialchars($version) . "</p>";
        
        // Test a sample query if users table exists
        if (in_array('users', $tables)) {
            echo "<h3>Sample Data Test:</h3>";
            $query = "SELECT COUNT(*) as user_count FROM users";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            echo "<p><strong>Total Users:</strong> " . $result['user_count'] . "</p>";
            
            // Show sample users
            $query = "SELECT name, email, role FROM users LIMIT 3";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll();
            
            echo "<h4>Sample Users:</h4>";
            echo "<table border='1' style='border-collapse: collapse; padding: 5px;'>";
            echo "<tr><th>Name</th><th>Email</th><th>Role</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Close connection
        $database->close();
        echo "<p style='color: blue;'>ℹ Connection closed successfully.</p>";
        
    } else {
        echo "<p style='color: red; font-weight: bold;'>✗ FAILED: Could not establish database connection.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>✗ ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p style='color: orange;'>⚠ Please check your database credentials and ensure MySQL is running.</p>";
}

echo "<hr>";
echo "<p><small>Test completed at: " . date('Y-m-d H:i:s') . "</small></p>";
?>
