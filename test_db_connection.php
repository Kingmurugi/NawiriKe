<?php
/**
 * Database Connection Test
 * This script helps diagnose database connection issues
 */

echo "<h2>Database Connection Test</h2>";

// Test 1: Check if MySQL is running
echo "<h3>Test 1: MySQL Service Status</h3>";
try {
    $conn = new PDO('mysql:host=localhost', 'root', 'Muraga0987#');
    echo "<p style='color: green;'>✓ MySQL is running and accessible with password</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ MySQL connection failed with password: " . $e->getMessage() . "</p>";
    
    // Try with empty password
    echo "<p>Trying with empty password...</p>";
    try {
        $conn = new PDO('mysql:host=localhost', 'root', '');
        echo "<p style='color: green;'>✓ MySQL is running with empty password</p>";
        echo "<p><strong>Update database.php password to empty string</strong></p>";
    } catch(PDOException $e2) {
        echo "<p style='color: red;'>✗ MySQL connection failed with empty password too: " . $e2->getMessage() . "</p>";
        echo "<p><strong>Possible solutions:</strong></p>";
        echo "<ul>";
        echo "<li>Check if MySQL/XAMPP Apache is running</li>";
        echo "<li>Verify the MySQL password in phpMyAdmin</li>";
        echo "<li>Stop MySQL 8.0 service if running</li>";
        echo "</ul>";
    }
    exit;
}

// Test 2: Check if nawirike database exists
echo "<h3>Test 2: Database Existence</h3>";
try {
    $result = $conn->query("SHOW DATABASES LIKE 'nawirike'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Database 'nawirike' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Database 'nawirike' does not exist</p>";
        echo "<p><strong>Solution:</strong> Create the database using phpMyAdmin or import nawirike.sql</p>";
        echo "<p>Available databases:</p>";
        $result = $conn->query("SHOW DATABASES");
        $databases = $result->fetchAll(PDO::FETCH_COLUMN);
        echo "<ul>";
        foreach ($databases as $db) {
            if ($db !== 'information_schema' && $db !== 'mysql' && $db !== 'performance_schema') {
                echo "<li>$db</li>";
            }
        }
        echo "</ul>";
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error checking database: " . $e->getMessage() . "</p>";
}

// Test 3: Try connecting to nawirike database
echo "<h3>Test 3: Full Connection Test</h3>";
try {
    $conn = new PDO('mysql:host=localhost;dbname=nawirike', 'root', 'Muraga0987#');
    echo "<p style='color: green;'>✓ Successfully connected to nawirike database</p>";
    
    // Test 4: Check if tables exist
    echo "<h3>Test 4: Tables in nawirike database</h3>";
    $result = $conn->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    if (count($tables) > 0) {
        echo "<p style='color: green;'>✓ Found " . count($tables) . " tables:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠ No tables found in database</p>";
        echo "<p><strong>Solution:</strong> Import nawirike.sql to create tables</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Connection to nawirike failed: " . $e->getMessage() . "</p>";
}

echo "<h3>Configuration Summary</h3>";
echo "<ul>";
echo "<li>Host: localhost</li>";
echo "<li>Database: nawirike</li>";
echo "<li>Username: root</li>";
echo "<li>Password: Muraga0987#</li>";
echo "</ul>";

echo "<p><a href='index.php'>Return to Home</a></p>";
?>
