<?php
/**
 * Simple database connection debug
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Debug</h2>";

// Test direct PDO connection
echo "<h3>Test 1: Direct PDO Connection</h3>";
try {
    $conn = new PDO('mysql:host=localhost;dbname=nawirike', 'root', '');
    echo "<p style='color: green;'>✓ Direct PDO connection successful</p>";
    echo "<p>Connection object: " . get_class($conn) . "</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Direct PDO connection failed: " . $e->getMessage() . "</p>";
}

// Test Database class connection
echo "<h3>Test 2: Database Class Connection</h3>";
require_once 'database.php';
$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "<p style='color: green;'>✓ Database class connection successful</p>";
    echo "<p>Connection object: " . get_class($conn) . "</p>";
    echo "<p>Connection is null: " . ($conn === null ? 'yes' : 'no') . "</p>";
} else {
    echo "<p style='color: red;'>✗ Database class connection failed (returned null)</p>";
}

// Test query
echo "<h3>Test 3: Simple Query</h3>";
try {
    $conn = new PDO('mysql:host=localhost;dbname=nawirike', 'root', '');
    $stmt = $conn->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "<p style='color: green;'>✓ Query successful, found $count users</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Query failed: " . $e->getMessage() . "</p>";
}

echo "<h3>Configuration</h3>";
echo "<ul>";
echo "<li>Host: localhost</li>";
echo "<li>Database: nawirike</li>";
echo "<li>Username: root</li>";
echo "<li>Password: (empty)</li>";
echo "</ul>";
?>
