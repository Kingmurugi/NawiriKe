<?php
/**
 * NawiriKe CRM Authentication Controller
 * Handles user registration and login functionality
 */

// Start session for all authentication operations
session_start();

// Include database connection
require_once 'database.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

/**
 * Handle User Registration
 * Processes form data from register.html
 */
function handleRegistration($conn) {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'victim'; // Default role if not specified
    $contact = trim($_POST['contact'] ?? '');
    $admin_code = trim($_POST['admin_code'] ?? '');
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Contact is only required for donors
    if ($role === 'donor' && empty($contact)) {
        $errors[] = "Contact information is required for donors";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate admin code if role is admin
    if ($role === 'admin') {
        if (empty($admin_code)) {
            $errors[] = "Admin code is required for admin registration";
        } elseif ($admin_code !== 'NawiriKeAdmin2024') {
            $errors[] = "Invalid admin code";
        }
    }
    
    // Check if email already exists
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->fetch_assoc()) {
                $errors[] = "Email already registered";
            }
        } catch(Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If there are errors, return them
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Insert new user
    try {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password_hash, $role);
        $result = $stmt->execute();
        
        if ($result) {
            // Get the newly created user ID
            $user_id = $conn->insert_id;
            
            // Create corresponding profile record based on role
            if ($role === 'donor') {
                $stmt = $conn->prepare("INSERT INTO donors (user_id, contact) VALUES (?, ?)");
                $stmt->bind_param("is", $user_id, $contact);
                $stmt->execute();
            } elseif ($role === 'victim') {
                $stmt = $conn->prepare("INSERT INTO victims (user_id, location, vulnerability_description, verification_status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, '', '', 'Pending');
                $stmt->execute();
            } elseif ($role === 'admin') {
                // Admin users don't need additional profile records
                // They get full admin privileges through the role field
            }
            
            return ['success' => true, 'message' => 'Registration successful! Please login.'];
        } else {
            return ['success' => false, 'errors' => ['Registration failed']];
        }
        
    } catch(PDOException $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle User Login
 * Processes form data from login.html
 */
function handleLogin($conn) {
    // Get form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Debug logging
    error_log("Login attempt - Email: " . $email);
    error_log("Login attempt - Password length: " . strlen($password));
    
    // Validate input
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If there are errors, return them
    if (!empty($errors)) {
        error_log("Login validation errors: " . implode(', ', $errors));
        return ['success' => false, 'errors' => $errors];
    }
    
    // Authenticate user
    try {
        // Check if database connection is working
        if (!$conn) {
            error_log("Database connection is null");
            return ['success' => false, 'errors' => ['Database connection failed']];
        }
        
        error_log("Database connection type: " . get_class($conn));
        
        // Get user from database using mysqli
        $stmt = $conn->prepare("SELECT user_id, name, email, password_hash, role FROM users WHERE email = ?");
        if (!$stmt) {
            error_log("Database query preparation failed: " . $conn->error);
            return ['success' => false, 'errors' => ['Database query preparation failed']];
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        error_log("Database query executed, user found: " . ($user ? 'yes' : 'no'));
        
        if (!$user) {
            error_log("User not found for email: " . $email);
            return ['success' => false, 'errors' => ['User not found']];
        }
        
        if (password_verify($password, $user['password_hash'])) {
            error_log("Password verification successful for user: " . $user['email']);
            
            // Password is correct, start session
            session_regenerate_id(true); // Prevent session fixation
            
            // Store user data in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            error_log("Session data stored for user ID: " . $user['user_id'] . ", Role: " . $user['role']);
            
            // Determine redirect URL based on role
            $redirect_url = '';
            switch($user['role']) {
                case 'admin':
                    $redirect_url = 'admin_dashboard.php';
                    break;
                case 'donor':
                    $redirect_url = 'donor_dashboard.php';
                    break;
                case 'victim':
                    $redirect_url = 'victim_dashboard.php';
                    break;
                default:
                    $redirect_url = 'login.html';
                    break;
            }
            
            error_log("Login successful, redirecting to: " . $redirect_url);
            return ['success' => true, 'message' => 'Login successful!', 'role' => $user['role'], 'redirect_url' => $redirect_url];
        } else {
            error_log("Password verification failed for user: " . $user['email']);
            return ['success' => false, 'errors' => ['Invalid password']];
        }
        
    } catch(Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle User Logout
 * Clears session and redirects to login
 */
function handleLogout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    return ['success' => true, 'message' => 'Logged out successfully'];
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Get current user data from session
 * @return array|null
 */
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['name'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

/**
 * Redirect based on user role
 */
function redirectByRole($role) {
    switch($role) {
        case 'admin':
            header('Location: admin/dashboard.php');
            break;
        case 'donor':
            header('Location: donor/dashboard.php');
            break;
        case 'victim':
            header('Location: victim/dashboard.php');
            break;
        default:
            header('Location: login.html');
            break;
    }
    exit();
}

/**
 * Protect pages - redirect to login if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.html');
        exit();
    }
}

/**
 * Protect admin pages - redirect if not admin
 */
function requireAdmin() {
    requireLogin();
    $user = getCurrentUser();
    if (!$user || $user['role'] !== 'admin') {
        header('Location: ../login.html');
        exit();
    }
}

/**
 * Handle Victim Approval
 */
function handleVictimApproval($conn, $victimId, $action) {
    try {
        // Update victim verification status
        $newStatus = ($action === 'approve') ? 'Approved' : 'Rejected';
        
        $stmt = $conn->prepare("UPDATE victims SET verification_status = ?, last_updated = CURRENT_TIMESTAMP WHERE victim_id = ?");
        $result = $stmt->execute([$newStatus, $victimId]);
        
        if ($result) {
            return ['success' => true, 'message' => "Victim application {$newStatus} successfully"];
        } else {
            return ['success' => false, 'errors' => ['Failed to update victim status']];
        }
        
    } catch(PDOException $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle Victim Application Update
 */
function handleVictimApplicationUpdate($conn, $userId, $location, $vulnerability, $urgentNeeds) {
    try {
        // Update victim information
        $stmt = $conn->prepare("
            UPDATE victims 
            SET location = ?, vulnerability_description = ?, urgent_needs = ?, last_updated = CURRENT_TIMESTAMP 
            WHERE user_id = ?
        ");
        $stmt->bind_param("sssi", $location, $vulnerability, $urgentNeeds, $userId);
        $result = $stmt->execute();
        
        if ($result) {
            return ['success' => true, 'message' => 'Application updated successfully!'];
        } else {
            return ['success' => false, 'errors' => ['Failed to update application']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle Donation Processing
 * Supports both general pool (victim_id = NULL) and specific victim donations
 * Supports both cash and M-Pesa payment methods
 */
function handleDonation($conn, $donorId, $victimId, $amount, $donationType, $description, $paymentMethod = 'cash', $mpesaPhone = null) {
    try {
        // Insert new donation (victim_id can be NULL for general pool)
        $stmt = $conn->prepare("
            INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method, mpesa_phone) 
            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'completed', ?, ?)
        ");
        $stmt->bind_param("iidssss", $donorId, $victimId, $amount, $donationType, $description, $paymentMethod, $mpesaPhone);
        $result = $stmt->execute();
        
        if ($result) {
            // Update donor total donations
            $stmt = $conn->prepare("UPDATE donors SET total_donated = total_donated + ?, donation_count = donation_count + 1 WHERE donor_id = ?");
            $stmt->bind_param("di", $amount, $donorId);
            $stmt->execute();
            
            $message = ($victimId) 
                ? 'Donation processed successfully!' 
                : 'Donation added to general fund successfully!';
            
            return ['success' => true, 'message' => $message];
        } else {
            return ['success' => false, 'errors' => ['Failed to process donation']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle M-Pesa Payment Initiation (Fake)
 * Simulates M-Pesa STK push for donations
 */
function initiateMpesaPayment($conn, $donorId, $victimId, $amount, $donationType, $description, $mpesaPhone) {
    try {
        // Generate fake transaction ID
        $transactionId = 'MPESA' . time() . rand(1000, 9999);
        $receiptNumber = 'REC' . time() . rand(1000, 9999);
        
        // Insert donation with pending M-Pesa status
        $stmt = $conn->prepare("
            INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method, mpesa_phone, mpesa_transaction_id, mpesa_receipt_number, mpesa_status) 
            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'pending', 'mpesa', ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iidsssss", $donorId, $victimId, $amount, $donationType, $description, $mpesaPhone, $transactionId, $receiptNumber);
        $result = $stmt->execute();
        
        if ($result) {
            // Update donor total donations
            $stmt = $conn->prepare("UPDATE donors SET total_donated = total_donated + ?, donation_count = donation_count + 1 WHERE donor_id = ?");
            $stmt->bind_param("di", $amount, $donorId);
            $stmt->execute();
            
            // Simulate successful STK push initiation
            return [
                'success' => true, 
                'message' => 'M-Pesa STK Push initiated successfully',
                'transaction_id' => $transactionId,
                'amount' => $amount
            ];
        } else {
            return ['success' => false, 'errors' => ['Failed to initiate M-Pesa payment']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle General Fund Distribution
 * Admin distributes general pool funds to specific victims using distributions table
 */
function distributeGeneralFund($conn, $adminUserId, $victimId, $amount, $notes = '') {
    try {
        // Check if sufficient general pool funds available
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM donations WHERE victim_id IS NULL");
        $stmt->execute();
        $result = $stmt->get_result();
        $generalPoolTotal = $result->fetch_assoc()['total'];
        
        // Get already distributed amount
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as distributed FROM distributions");
        $stmt->execute();
        $result = $stmt->get_result();
        $totalDistributed = $result->fetch_assoc()['distributed'];
        
        $available = $generalPoolTotal - $totalDistributed;
        
        if ($amount > $available) {
            return ['success' => false, 'errors' => ['Insufficient general pool funds. Available: KES ' . number_format($available, 2)]];
        }
        
        // Find an available general pool donation to link to this distribution
        $stmt = $conn->prepare("
            SELECT donation_id, amount 
            FROM donations 
            WHERE victim_id IS NULL 
            AND donation_id NOT IN (SELECT donation_id FROM distributions)
            LIMIT 1
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $availableDonation = $result->fetch_assoc();
        
        if (!$availableDonation) {
            return ['success' => false, 'errors' => ['No available general pool donations to distribute']];
        }
        
        // Create distribution record
        $stmt = $conn->prepare("
            INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?)
        ");
        $stmt->bind_param("iiids", $availableDonation['donation_id'], $victimId, $amount, $adminUserId, $notes);
        $result = $stmt->execute();
        
        if ($result) {
            return ['success' => true, 'message' => 'General fund distributed successfully!'];
        } else {
            return ['success' => false, 'errors' => ['Failed to distribute funds']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Get General Pool Statistics
 */
function getGeneralPoolStats($conn) {
    try {
        // Total general pool donations
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM donations WHERE victim_id IS NULL");
        $stmt->execute();
        $result = $stmt->get_result();
        $totalPool = $result->fetch_assoc()['total'];
        
        // Total distributed from general pool using distributions table
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as distributed FROM distributions");
        $stmt->execute();
        $result = $stmt->get_result();
        $totalDistributed = $result->fetch_assoc()['distributed'];
        
        // Remaining available
        $available = $totalPool - $totalDistributed;
        
        // Number of distributions made
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM distributions");
        $stmt->execute();
        $result = $stmt->get_result();
        $distributionCount = $result->fetch_assoc()['count'];
        
        return [
            'total_pool' => $totalPool,
            'total_distributed' => $totalDistributed,
            'available' => $available,
            'distribution_count' => $distributionCount
        ];
        
    } catch(Exception $e) {
        error_log('General Pool Stats Error: ' . $e->getMessage());
        return [
            'total_pool' => 0,
            'total_distributed' => 0,
            'available' => 0,
            'distribution_count' => 0
        ];
    }
}

/**
 * Handle User Update
 * Updates user information in the database
 */
function handleUpdateUser($conn, $userId, $name, $email, $role) {
    try {
        // Validate input
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Name is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        if (empty($role) || !in_array($role, ['admin', 'donor', 'victim'])) {
            $errors[] = "Invalid role";
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check if email already exists for another user
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()) {
            return ['success' => false, 'errors' => ['Email already in use by another user']];
        }
        
        // Update user
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $userId);
        $result = $stmt->execute();
        
        if ($result) {
            return ['success' => true, 'message' => 'User updated successfully'];
        } else {
            return ['success' => false, 'errors' => ['Failed to update user']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

/**
 * Handle User Deletion
 * Deletes a user from the database
 */
function handleDeleteUser($conn, $userId) {
    try {
        // Check if user exists
        $stmt = $conn->prepare("SELECT user_id, role FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user) {
            return ['success' => false, 'errors' => ['User not found']];
        }
        
        // Prevent deletion of the current admin
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            return ['success' => false, 'errors' => ['Cannot delete your own account']];
        }
        
        // Delete related records based on role
        if ($user['role'] === 'donor') {
            // Delete donor record and related donations
            $stmt = $conn->prepare("DELETE FROM donations WHERE donor_id IN (SELECT donor_id FROM donors WHERE user_id = ?)");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            
            $stmt = $conn->prepare("DELETE FROM donors WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        } elseif ($user['role'] === 'victim') {
            // Delete victim record and related distributions
            $stmt = $conn->prepare("DELETE FROM distributions WHERE victim_id IN (SELECT victim_id FROM victims WHERE user_id = ?)");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            
            $stmt = $conn->prepare("DELETE FROM victims WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }
        
        // Delete user record
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $result = $stmt->execute();
        
        if ($result) {
            return ['success' => true, 'message' => 'User deleted successfully'];
        } else {
            return ['success' => false, 'errors' => ['Failed to delete user']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}

// Handle GET requests (for logout via direct link)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'logout') {
        handleLogout();
        header('Location: index.php');
        exit();
    }
}

// Main controller logic - handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Ensure session is started for AJAX requests
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $action = $_POST['action'] ?? '';
    
    // Debug logging
    error_log("AuthController: Action received: " . $action);
    error_log("AuthController: POST data: " . print_r($_POST, true));
    
    switch($action) {
        case 'register':
            $result = handleRegistration($conn);
            echo json_encode($result);
            break;
            
        case 'login':
            $result = handleLogin($conn);
            echo json_encode($result);
            break;
            
        case 'logout':
            $result = handleLogout();
            echo json_encode($result);
            break;
            
        case 'approve_victim':
            $victimId = $_POST['victim_id'] ?? 0;
            $result = handleVictimApproval($conn, $victimId, 'approve');
            echo json_encode($result);
            break;
            
        case 'reject_victim':
            $victimId = $_POST['victim_id'] ?? 0;
            $result = handleVictimApproval($conn, $victimId, 'reject');
            echo json_encode($result);
            break;
            
        case 'update_victim_application':
            $userId = $_POST['user_id'] ?? 0;
            $location = $_POST['location'] ?? '';
            $vulnerability = $_POST['vulnerability'] ?? '';
            $urgentNeeds = $_POST['urgent_needs'] ?? '';
            $result = handleVictimApplicationUpdate($conn, $userId, $location, $vulnerability, $urgentNeeds);
            echo json_encode($result);
            break;
            
        case 'make_donation':
            $donorId = $_POST['donor_id'] ?? 0;
            $victimId = !empty($_POST['victim_id']) ? $_POST['victim_id'] : null;
            $amount = $_POST['amount'] ?? 0;
            $donationType = $_POST['donation_type'] ?? 'monetary';
            $description = $_POST['description'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'cash';
            $mpesaPhone = $_POST['mpesa_phone'] ?? null;
            $result = handleDonation($conn, $donorId, $victimId, $amount, $donationType, $description, $paymentMethod, $mpesaPhone);
            echo json_encode($result);
            break;
            
        case 'initiate_mpesa_payment':
            $donorId = $_POST['donor_id'] ?? 0;
            $victimId = !empty($_POST['victim_id']) ? $_POST['victim_id'] : null;
            $amount = $_POST['amount'] ?? 0;
            $donationType = $_POST['donation_type'] ?? 'monetary';
            $description = $_POST['description'] ?? '';
            $mpesaPhone = $_POST['mpesa_phone'] ?? null;
            $result = initiateMpesaPayment($conn, $donorId, $victimId, $amount, $donationType, $description, $mpesaPhone);
            echo json_encode($result);
            break;
            
        case 'distribute_general_fund':
            $adminUserId = $_POST['admin_user_id'] ?? 0;
            $victimId = $_POST['victim_id'] ?? 0;
            $amount = $_POST['amount'] ?? 0;
            $notes = $_POST['notes'] ?? '';
            $result = distributeGeneralFund($conn, $adminUserId, $victimId, $amount, $notes);
            echo json_encode($result);
            break;
            
        case 'update_user':
            $userId = $_POST['user_id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? '';
            $result = handleUpdateUser($conn, $userId, $name, $email, $role);
            echo json_encode($result);
            break;
            
        case 'delete_user':
            $userId = $_POST['user_id'] ?? 0;
            $result = handleDeleteUser($conn, $userId);
            echo json_encode($result);
            break;
            
        default:
            echo json_encode(['success' => false, 'errors' => ['Invalid action']]);
            break;
    }
}
?>
