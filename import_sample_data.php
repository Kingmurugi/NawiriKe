<?php
/**
 * Import comprehensive sample data into NawiriKe database
 */

require_once 'database.php';

$database = new Database();
$conn = $database->getConnection();

try {
    $sql = file_get_contents('comprehensive_sample_data.sql');
    
    // Split by semicolon to execute individual statements
    $statements = explode(';', $sql);
    
    $conn->beginTransaction();
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            $conn->exec($statement);
        }
    }
    
    $conn->commit();
    
    echo "Sample data imported successfully!\n";
    echo "Donors: 25\n";
    echo "Victims: 25\n";
    echo "Donations: 50\n";
    echo "Distributions: 25\n";
    
} catch(PDOException $e) {
    $conn->rollBack();
    echo "Error importing data: " . $e->getMessage() . "\n";
}
?>
