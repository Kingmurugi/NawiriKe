<?php
/**
 * Database Setup Script
 * Automatically creates the nawirike database and imports schema/data
 */

echo "<h2>NawiriKe Database Setup</h2>";
echo "<p>This script will automatically set up your database.</p>";

// Database connection without specifying database
try {
    $conn = new PDO('mysql:host=localhost', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to MySQL server</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Failed to connect to MySQL: " . $e->getMessage() . "</p>";
    echo "<p>Please ensure XAMPP MySQL is running.</p>";
    exit;
}

// Step 1: Check if database exists
echo "<h3>Step 1: Checking database existence</h3>";
$result = $conn->query("SHOW DATABASES LIKE 'nawirike'");
if ($result->rowCount() > 0) {
    echo "<p style='color: orange;'>⚠ Database 'nawirike' already exists</p>";
    $dbExists = true;
} else {
    echo "<p style='color: blue;'>ℹ Database 'nawirike' does not exist, creating...</p>";
    try {
        $conn->exec("CREATE DATABASE nawirike CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p style='color: green;'>✓ Database 'nawirike' created successfully</p>";
        $dbExists = false;
    } catch(PDOException $e) {
        echo "<p style='color: red;'>✗ Failed to create database: " . $e->getMessage() . "</p>";
        exit;
    }
}

// Connect to the nawirike database
$conn = new PDO('mysql:host=localhost;dbname=nawirike', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Step 2: Check if tables exist
echo "<h3>Step 2: Checking tables</h3>";
$result = $conn->query("SHOW TABLES");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);
if (count($tables) > 0) {
    echo "<p style='color: orange;'>⚠ Tables already exist: " . implode(', ', $tables) . "</p>";
    echo "<p>Skipping schema import.</p>";
} else {
    echo "<p style='color: blue;'>ℹ No tables found, importing schema...</p>";
    
    // Read and execute nawirike.sql
    $sqlFile = __DIR__ . '/nawirike.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        try {
            // Split SQL into individual statements
            $statements = explode(';', $sql);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            echo "<p style='color: green;'>✓ Schema imported successfully</p>";
        } catch(PDOException $e) {
            echo "<p style='color: red;'>✗ Failed to import schema: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Schema file not found: nawirike.sql</p>";
    }
}

// Step 3: Check if sample data exists
echo "<h3>Step 3: Checking sample data</h3>";
$result = $conn->query("SELECT COUNT(*) FROM users");
$userCount = $result->fetchColumn();
if ($userCount > 0) {
    echo "<p style='color: orange;'>⚠ Sample data already exists ($userCount users)</p>";
    echo "<p>Skipping sample data import.</p>";
} else {
    echo "<p style='color: blue;'>ℹ No sample data found, importing...</p>";
    
    // Read and execute comprehensive_sample_data.sql
    $sqlFile = __DIR__ . '/comprehensive_sample_data.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        try {
            // Split SQL into individual statements
            $statements = explode(';', $sql);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            echo "<p style='color: green;'>✓ Sample data imported successfully</p>";
        } catch(PDOException $e) {
            echo "<p style='color: red;'>✗ Failed to import sample data: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Sample data file not found: comprehensive_sample_data.sql</p>";
    }
}

// Step 4: Verify setup
echo "<h3>Step 4: Verification</h3>";
$result = $conn->query("SHOW TABLES");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);
echo "<p style='color: green;'>✓ Database contains " . count($tables) . " tables:</p>";
echo "<ul>";
foreach ($tables as $table) {
    echo "<li>$table</li>";
}
echo "</ul>";

$result = $conn->query("SELECT COUNT(*) as count FROM users");
$userCount = $result->fetch()['count'];
echo "<p style='color: green;'>✓ Total users: $userCount</p>";

echo "<h3>Setup Complete!</h3>";
echo "<p>You can now login with:</p>";
echo "<ul>";
echo "<li><strong>Admin:</strong> admin@nawirike.org / password</li>";
echo "<li><strong>Donor:</strong> james.kamau@nawirike.org / password</li>";
echo "<li><strong>Victim:</strong> alice.muthoni@nawirike.org / password</li>";
echo "</ul>";
echo "<p><a href='index.php'>Go to Home Page</a> | <a href='login.html'>Login Page</a></p>";
?>
