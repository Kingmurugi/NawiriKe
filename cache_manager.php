<?php
/**
 * NawiriKe CRM Cache Manager
 * Simple file-based caching for better performance
 */

class CacheManager {
    private static $instance = null;
    private $cache_dir;
    private $default_ttl = 300; // 5 minutes
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->cache_dir = sys_get_temp_dir() . '/nawirike_cache';
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }

    /**
     * Set cache data
     */
    public function set($key, $data, $ttl = null) {
        $ttl = $ttl ?? $this->default_ttl;
        $cache_file = $this->getCacheFile($key);
        $cache_data = [
            'data' => $data,
            'created_at' => time(),
            'expires_at' => time() + $ttl
        ];
        
        file_put_contents($cache_file, serialize($cache_data), LOCK_EX);
        return true;
    }

    /**
     * Get cache data
     */
    public function get($key) {
        $cache_file = $this->getCacheFile($key);
        
        if (!file_exists($cache_file)) {
            return null;
        }
        
        $cache_data = unserialize(file_get_contents($cache_file));
        
        if (time() > $cache_data['expires_at']) {
            $this->delete($key);
            return null;
        }
        
        return $cache_data['data'];
    }

    /**
     * Delete cache data
     */
    public function delete($key) {
        $cache_file = $this->getCacheFile($key);
        if (file_exists($cache_file)) {
            unlink($cache_file);
            return true;
        }
        return false;
    }

    /**
     * Clear all cache
     */
    public function clear() {
        $files = glob($this->cache_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * Clean expired cache files
     */
    public function cleanup() {
        $files = glob($this->cache_dir . '/*');
        $current_time = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $cache_data = unserialize(file_get_contents($file));
                if ($current_time > $cache_data['expires_at']) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Get cache file path
     */
    private function getCacheFile($key) {
        return $this->cache_dir . '/' . md5($key) . '.cache';
    }

    /**
     * Cache database query results
     */
    public function cacheQuery($query, $params, $data, $ttl = 300) {
        $key = 'query_' . md5($query . serialize($params));
        return $this->set($key, $data, $ttl);
    }

    /**
     * Get cached query results
     */
    public function getCachedQuery($query, $params) {
        $key = 'query_' . md5($query . serialize($params));
        return $this->get($key);
    }

    /**
     * Cache user data
     */
    public function cacheUser($user_id, $data, $ttl = 600) {
        $key = 'user_' . $user_id;
        return $this->set($key, $data, $ttl);
    }

    /**
     * Get cached user data
     */
    public function getCachedUser($user_id) {
        $key = 'user_' . $user_id;
        return $this->get($key);
    }

    /**
     * Cache dashboard statistics
     */
    public function cacheDashboardStats($role, $data, $ttl = 180) {
        $key = 'dashboard_' . $role;
        return $this->set($key, $data, $ttl);
    }

    /**
     * Get cached dashboard statistics
     */
    public function getCachedDashboardStats($role) {
        $key = 'dashboard_' . $role;
        return $this->get($key);
    }
}
?>
