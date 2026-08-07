<?php
/**
 * NawiriKe CRM Optimized Database Connection
 * Connection pooling and singleton pattern for better concurrency
 */

class DatabaseOptimized {
    private static $instance = null;
    private static $connections = [];
    private $max_connections = 10;
    private $connection_count = 0;
    
    private $host = 'localhost';
    private $db_name = 'nawirike';
    private $username = 'root';
    private $password = '';

    /**
     * Singleton pattern - prevents multiple instances
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get database connection from pool or create new one
     * @return PDO
     */
    public function getConnection() {
        // Try to reuse existing connection
        foreach (self::$connections as $key => $conn) {
            if ($conn['in_use'] === false && $conn['pdo'] !== null) {
                try {
                    // Test if connection is still alive
                    $conn['pdo']->query("SELECT 1");
                    self::$connections[$key]['in_use'] = true;
                    return $conn['pdo'];
                } catch (PDOException $e) {
                    // Connection is dead, remove it
                    unset(self::$connections[$key]);
                }
            }
        }

        // Create new connection if under limit
        if ($this->connection_count < $this->max_connections) {
            return $this->createConnection();
        }

        // Wait for available connection (simple implementation)
        $attempts = 0;
        while ($attempts < 5) {
            usleep(100000); // Wait 100ms
            foreach (self::$connections as $key => $conn) {
                if ($conn['in_use'] === false) {
                    self::$connections[$key]['in_use'] = true;
                    return $conn['pdo'];
                }
            }
            $attempts++;
        }

        throw new Exception("Maximum database connections reached");
    }

    /**
     * Create new database connection
     * @return PDO
     */
    private function createConnection() {
        try {
            $pdo = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4',
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true, // Persistent connections
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );

            self::$connections[] = [
                'pdo' => $pdo,
                'in_use' => true,
                'created_at' => time()
            ];
            
            $this->connection_count++;
            return $pdo;

        } catch(PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    /**
     * Release connection back to pool
     * @param PDO $connection
     */
    public function releaseConnection($connection) {
        foreach (self::$connections as $key => $conn) {
            if ($conn['pdo'] === $connection) {
                self::$connections[$key]['in_use'] = false;
                return;
            }
        }
    }

    /**
     * Execute query with transaction protection
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function executeQuery($query, $params = []) {
        $conn = $this->getConnection();
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Query Error: ' . $e->getMessage() . ' Query: ' . $query);
            throw $e;
        } finally {
            $this->releaseConnection($conn);
        }
    }

    /**
     * Execute transaction with multiple queries
     * @param array $queries
     * @return bool
     */
    public function executeTransaction($queries) {
        $conn = $this->getConnection();
        
        try {
            $conn->beginTransaction();
            
            foreach ($queries as $query_data) {
                $stmt = $conn->prepare($query_data['query']);
                $stmt->execute($query_data['params'] ?? []);
            }
            
            $conn->commit();
            return true;
            
        } catch (Exception $e) {
            $conn->rollback();
            error_log('Transaction Error: ' . $e->getMessage());
            throw $e;
        } finally {
            $this->releaseConnection($conn);
        }
    }

    /**
     * Clean up old connections
     */
    public function cleanupConnections() {
        $max_age = 300; // 5 minutes
        $current_time = time();
        
        foreach (self::$connections as $key => $conn) {
            if ($current_time - $conn['created_at'] > $max_age && !$conn['in_use']) {
                unset(self::$connections[$key]);
                $this->connection_count--;
            }
        }
    }
}
?>
