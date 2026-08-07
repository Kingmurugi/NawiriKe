<?php
/**
 * NawiriKe CRM Database Connection
 * Simple PDO connection for student project
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'nawirike';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Create database connection
     * @return PDO|null
     */
    public function connect() {
        $this->conn = null;

        try {
            // Create PDO connection
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );

            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode to associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            // For debugging, you can uncomment the next line
            // echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

    /**
     * Get current connection
     * @return PDO|null
     */
    public function getConnection() {
        if ($this->conn === null) {
            return $this->connect();
        }
        return $this->conn;
    }

    /**
     * Close database connection
     */
    public function close() {
        $this->conn = null;
    }
}
?>
