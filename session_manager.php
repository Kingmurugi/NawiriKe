<?php
/**
 * NawiriKe CRM Session Manager
 * Optimized session handling for multiple concurrent users
 */

class SessionManager {
    private static $instance = null;
    private $session_timeout = 3600; // 1 hour
    private $max_sessions = 1000;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize secure session
     */
    public function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            // Custom session save path for better performance
            $session_path = sys_get_temp_dir() . '/nawirike_sessions';
            if (!is_dir($session_path)) {
                mkdir($session_path, 0755, true);
            }
            session_save_path($session_path);
            
            session_start();
            
            // Regenerate session ID for security
            if (!isset($_SESSION['initialized'])) {
                session_regenerate_id(true);
                $_SESSION['initialized'] = true;
                $_SESSION['created_at'] = time();
                $_SESSION['last_activity'] = time();
            }
            
            // Check session timeout
            $this->checkSessionTimeout();
        }
    }

    /**
     * Check if session has timed out
     */
    private function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];
            if ($inactive > $this->session_timeout) {
                $this->destroySession();
                header('Location: login.html?timeout=1');
                exit();
            }
        }
        $_SESSION['last_activity'] = time();
    }

    /**
     * Set secure session data
     */
    public function setSessionData($user_data) {
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['name'] = $user_data['name'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['role'] = $user_data['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();
        
        // Log session creation
        error_log("Session created for user: " . $user_data['email'] . " Role: " . $user_data['role']);
    }

    /**
     * Get current session data
     */
    public function getSessionData() {
        return [
            'user_id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['name'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'logged_in' => $_SESSION['logged_in'] ?? false
        ];
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Check user role
     */
    public function hasRole($required_role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $required_role;
    }

    /**
     * Destroy session
     */
    public function destroySession() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Log session destruction
            error_log("Session destroyed for user: " . ($_SESSION['email'] ?? 'unknown'));
            
            session_unset();
            session_destroy();
            
            // Clear session cookie
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
        }
    }

    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions() {
        $session_path = session_save_path();
        $current_time = time();
        
        if (is_dir($session_path)) {
            $files = glob($session_path . '/sess_*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $file_time = filemtime($file);
                    if ($current_time - $file_time > $this->session_timeout) {
                        unlink($file);
                    }
                }
            }
        }
    }

    /**
     * Get session statistics
     */
    public function getSessionStats() {
        $session_path = session_save_path();
        $active_sessions = 0;
        
        if (is_dir($session_path)) {
            $files = glob($session_path . '/sess_*');
            $active_sessions = count($files);
        }
        
        return [
            'active_sessions' => $active_sessions,
            'max_sessions' => $this->max_sessions,
            'session_timeout' => $this->session_timeout
        ];
    }

    /**
     * Prevent session fixation attacks
     */
    public function preventSessionFixation() {
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }
        
        if (!isset($_SESSION['ip_address'])) {
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        } else if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
            $this->destroySession();
            die('Security violation: IP address changed');
        }
        
        if (!isset($_SESSION['user_agent'])) {
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        } else if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            $this->destroySession();
            die('Security violation: User agent changed');
        }
    }
}
?>
