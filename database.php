<?php
/**
 * NawiriKe CRM Database Connection
 * MySQLi connection for XAMPP/phpMyAdmin with debugging
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'nawirike';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Create database connection
     * @return mysqli|null
     */
    public function connect() {
        $this->conn = null;

        try {
            // Create MySQLi connection
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name
            );

            // Check connection
            if ($this->conn->connect_error) {
                error_log('Database Connection Error: ' . $this->conn->connect_error);
                return null;
            }

            error_log('Database connection successful');
            return $this->conn;

        } catch(Exception $e) {
            error_log('Database Connection Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current connection
     * @return mysqli|null
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
        if ($this->conn !== null) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}
?>
