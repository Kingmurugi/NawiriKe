<?php
/**
 * NawiriKe CRM Authentication Controller
 * Handles user registration and login functionality
 */

// Start session for all authentication operations
session_start();

// Include database connection
require_once 'database.php';
require_once 'reports.php';

// Smallest donation or distribution the system accepts, in KES
const MINIMUM_DONATION_AMOUNT = 100;

// Role each POST action requires. 'any' means any logged-in user; actions absent
// from this list (register, login, logout) are open to anonymous callers.
const ACTION_REQUIRED_ROLES = [
    'approve_victim' => 'admin',
    'reject_victim' => 'admin',
    'distribute_general_fund' => 'admin',
    'update_victim_application' => 'victim',
    'make_donation' => 'donor',
    'initiate_mpesa_payment' => 'donor',
    'confirm_mpesa_payment' => 'donor',
];

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
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "Email already registered";
            }
        } catch(PDOException $e) {
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
        $result = $stmt->execute([$name, $email, $password_hash, $role]);
        
        if ($result) {
            // Get the newly created user ID
            $user_id = $conn->lastInsertId();
            
            // Create corresponding profile record based on role
            if ($role === 'donor') {
                $stmt = $conn->prepare("INSERT INTO donors (user_id, contact) VALUES (?, ?)");
                $stmt->execute([$user_id, $contact]);
            } elseif ($role === 'victim') {
                $stmt = $conn->prepare("INSERT INTO victims (user_id, location, vulnerability_description, verification_status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, '', '', 'Pending']);
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
        return ['success' => false, 'errors' => $errors];
    }
    
    // Authenticate user
    try {
        // Check if database connection is working
        if (!$conn) {
            return ['success' => false, 'errors' => ['Database connection failed']];
        }
        
        // Get user from database
        $stmt = $conn->prepare("SELECT user_id, name, email, password_hash, role FROM users WHERE email = ?");
        if (!$stmt) {
            error_log("Database query preparation failed");
            return ['success' => false, 'errors' => ['Database query preparation failed']];
        }
        
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // One message for both cases, so the form cannot be used to discover
        // which email addresses are registered.
        if (!$user) {
            return ['success' => false, 'errors' => ['Invalid email or password']];
        }
        
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct, start session
            session_regenerate_id(true); // Prevent session fixation
            
            // Store user data in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
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
            
            return ['success' => true, 'message' => 'Login successful!', 'role' => $user['role'], 'redirect_url' => $redirect_url];
        } else {
            return ['success' => false, 'errors' => ['Invalid email or password']];
        }
        
    } catch(PDOException $e) {
        error_log('Login Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Login failed. Please try again.']];
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
            header('Location: admin_dashboard.php');
            break;
        case 'donor':
            header('Location: donor_dashboard.php');
            break;
        case 'victim':
            header('Location: victim_dashboard.php');
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
        header('Location: login.html');
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
        header('Location: login.html');
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
        
        $stmt = $conn->prepare("UPDATE victims SET verification_status = ? WHERE victim_id = ?");
        $stmt->execute([$newStatus, $victimId]);
        
        if ($stmt->rowCount() === 0) {
            return ['success' => false, 'errors' => ['Application not found or already ' . $newStatus]];
        }
        
        return ['success' => true, 'message' => "Victim application {$newStatus} successfully"];
        
    } catch(PDOException $e) {
        error_log('Victim Approval Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Failed to update victim status']];
    }
}

/**
 * Handle Victim Application Update
 */
function handleVictimApplicationUpdate($conn, $userId, $location, $vulnerability, $urgentNeeds) {
    if ($urgentNeeds === '') {
        // urgent_needs is an ENUM; an empty selection means "leave it unchanged".
        $urgentNeeds = null;
    }

    try {
        $stmt = $conn->prepare("
            UPDATE victims 
            SET location = ?, vulnerability_description = ?, urgent_needs = COALESCE(?, urgent_needs) 
            WHERE user_id = ?
        ");
        $stmt->execute([$location, $vulnerability, $urgentNeeds, $userId]);

        // Victims without an application row would otherwise be told the save
        // succeeded while nothing was stored.
        $stmt = $conn->prepare("SELECT victim_id FROM victims WHERE user_id = ?");
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['No application found for your account. Please contact an administrator.']];
        }

        return ['success' => true, 'message' => 'Application updated successfully!'];
        
    } catch(PDOException $e) {
        error_log('Application Update Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Failed to update application']];
    }
}

/**
 * Handle Donation Processing
 * Supports both general pool (victim_id = NULL) and specific victim donations
 * Supports both cash and M-Pesa payment methods
 */
function handleDonation($conn, $donorId, $victimId, $amount, $donationType, $description, $paymentMethod = 'cash', $mpesaPhone = null) {
    $amountErrors = validateDonationAmount($amount);
    if (!empty($amountErrors)) {
        return ['success' => false, 'errors' => $amountErrors];
    }
    $amount = round((float)$amount, 2);

    try {
        // Donation insert and donor running totals must move together, otherwise
        // the admin dashboard and the donor's own figures drift apart.
        $conn->beginTransaction();

        $stmt = $conn->prepare("
            INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method, mpesa_phone) 
            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'completed', ?, ?)
        ");
        $stmt->execute([$donorId, $victimId, $amount, $donationType, $description, $paymentMethod, $mpesaPhone]);

        $stmt = $conn->prepare("UPDATE donors SET total_donated = total_donated + ?, donation_count = donation_count + 1 WHERE donor_id = ?");
        $stmt->execute([$amount, $donorId]);

        if ($stmt->rowCount() === 0) {
            $conn->rollBack();
            return ['success' => false, 'errors' => ['Donor record not found']];
        }

        $conn->commit();

        $message = ($victimId) 
            ? 'Donation processed successfully!' 
            : 'Donation added to general fund successfully!';

        return ['success' => true, 'message' => $message];

    } catch(PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log('Donation Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Failed to process donation']];
    }
}

/**
 * Resolve the donors row belonging to a user
 * @return int donor_id, or 0 when the user has no donor record
 */
function getDonorIdForUser($conn, $userId) {
    $stmt = $conn->prepare("SELECT donor_id FROM donors WHERE user_id = ?");
    $stmt->execute([$userId]);
    $donor = $stmt->fetch();

    return $donor ? (int)$donor['donor_id'] : 0;
}

/**
 * Validate a donation or distribution amount
 * @return array List of error messages (empty when valid)
 */
function validateDonationAmount($amount) {
    if (!is_numeric($amount)) {
        return ['Amount must be a number'];
    }
    if ((float)$amount < MINIMUM_DONATION_AMOUNT) {
        return ['Minimum amount is KES ' . number_format(MINIMUM_DONATION_AMOUNT, 2)];
    }
    return [];
}

/**
 * Handle M-Pesa Payment Initiation (Fake)
 * Simulates M-Pesa STK push for donations
 */
function initiateMpesaPayment($conn, $donorId, $victimId, $amount, $donationType, $description, $mpesaPhone) {
    $amountErrors = validateDonationAmount($amount);
    if (!empty($amountErrors)) {
        return ['success' => false, 'errors' => $amountErrors];
    }
    $amount = round((float)$amount, 2);

    if (!preg_match('/^254[0-9]{9}$/', (string)$mpesaPhone)) {
        return ['success' => false, 'errors' => ['M-Pesa phone number must be in the format 2547XXXXXXXX']];
    }

    try {
        // Generate fake transaction ID
        $transactionId = 'MPESA' . time() . rand(1000, 9999);
        $receiptNumber = 'REC' . time() . rand(1000, 9999);
        
        // Insert donation with pending M-Pesa status
        $stmt = $conn->prepare("
            INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method, mpesa_phone, mpesa_transaction_id, mpesa_receipt_number, mpesa_status) 
            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'pending', 'mpesa', ?, ?, ?, 'pending')
        ");
        $stmt->execute([$donorId, $victimId, $amount, $donationType, $description, $mpesaPhone, $transactionId, $receiptNumber]);

        // Simulate successful STK push initiation. The donation stays 'pending'
        // until confirmMpesaPayment() stands in for the Daraja callback.
        return [
            'success' => true, 
            'message' => 'M-Pesa STK Push initiated successfully',
            'transaction_id' => $transactionId,
            'amount' => $amount
        ];
        
    } catch(PDOException $e) {
        error_log('M-Pesa Initiation Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Failed to initiate M-Pesa payment']];
    }
}

/**
 * Confirm a simulated M-Pesa Payment
 * Stands in for the Daraja STK push callback: settles the pending donation so it
 * counts towards the donor totals and the admin dashboard figures.
 */
function confirmMpesaPayment($conn, $transactionId, $donorId) {
    if (empty($transactionId)) {
        return ['success' => false, 'errors' => ['Transaction ID is required']];
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("
            SELECT donation_id, donor_id, amount 
            FROM donations 
            WHERE mpesa_transaction_id = ? AND donor_id = ? AND status = 'pending' 
            FOR UPDATE
        ");
        $stmt->execute([$transactionId, $donorId]);
        $donation = $stmt->fetch();

        if (!$donation) {
            $conn->rollBack();
            return ['success' => false, 'errors' => ['No pending M-Pesa donation found for this transaction']];
        }

        $stmt = $conn->prepare("
            UPDATE donations 
            SET status = 'completed', mpesa_status = 'completed' 
            WHERE donation_id = ?
        ");
        $stmt->execute([$donation['donation_id']]);

        $stmt = $conn->prepare("UPDATE donors SET total_donated = total_donated + ?, donation_count = donation_count + 1 WHERE donor_id = ?");
        $stmt->execute([$donation['amount'], $donation['donor_id']]);

        $conn->commit();

        return ['success' => true, 'message' => 'M-Pesa payment confirmed', 'amount' => $donation['amount']];

    } catch(PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log('M-Pesa Confirmation Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Failed to confirm M-Pesa payment']];
    }
}

/**
 * Handle General Fund Distribution
 * Admin distributes general pool funds to specific victims using distributions table
 */
function distributeGeneralFund($conn, $adminUserId, $victimId, $amount, $notes = '') {
    $amountErrors = validateDonationAmount($amount);
    if (!empty($amountErrors)) {
        return ['success' => false, 'errors' => $amountErrors];
    }
    $amount = round((float)$amount, 2);

    try {
        // The availability check and the insert run in one transaction so two
        // concurrent distributions cannot both pass the check and overdraw the pool.
        $conn->beginTransaction();

        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM donations 
            WHERE victim_id IS NULL AND status = 'completed'
            FOR UPDATE
        ");
        $stmt->execute();
        $generalPoolTotal = (float)$stmt->fetch()['total'];

        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as distributed FROM distributions");
        $stmt->execute();
        $totalDistributed = (float)$stmt->fetch()['distributed'];

        $available = $generalPoolTotal - $totalDistributed;

        if ($amount > $available) {
            $conn->rollBack();
            return ['success' => false, 'errors' => ['Insufficient general pool funds. Available: KES ' . number_format($available, 2)]];
        }

        // Link the distribution to the general pool donation with the most
        // undistributed money left, so a distribution is never booked against a
        // donation smaller than itself.
        $stmt = $conn->prepare("
            SELECT d.donation_id, d.amount - COALESCE(SUM(ds.amount), 0) AS remaining
            FROM donations d
            LEFT JOIN distributions ds ON ds.donation_id = d.donation_id
            WHERE d.victim_id IS NULL AND d.status = 'completed'
            GROUP BY d.donation_id, d.amount
            HAVING remaining >= ?
            ORDER BY remaining ASC
            LIMIT 1
        ");
        $stmt->execute([$amount]);
        $availableDonation = $stmt->fetch();

        if (!$availableDonation) {
            $conn->rollBack();
            return ['success' => false, 'errors' => ['No single general pool donation has KES ' . number_format($amount, 2) . ' left to distribute. Try a smaller amount.']];
        }

        $stmt = $conn->prepare("
            INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?)
        ");
        $stmt->execute([$availableDonation['donation_id'], $victimId, $amount, $adminUserId, $notes]);

        $conn->commit();

        return ['success' => true, 'message' => 'General fund distributed successfully!'];

    } catch(PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log('Distribution Error: ' . $e->getMessage());
        return ['success' => false, 'errors' => ['Failed to distribute funds']];
    }
}

/**
 * Get General Pool Statistics
 */
function getGeneralPoolStats($conn) {
    try {
        // Total general pool donations
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM donations WHERE victim_id IS NULL AND status = 'completed'");
        $stmt->execute();
        $totalPool = $stmt->fetch()['total'];
        
        // Total distributed from general pool using distributions table
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as distributed FROM distributions");
        $stmt->execute();
        $totalDistributed = $stmt->fetch()['distributed'];
        
        // Remaining available
        $available = $totalPool - $totalDistributed;
        
        // Number of distributions made
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM distributions");
        $stmt->execute();
        $distributionCount = $stmt->fetch()['count'];
        
        return [
            'total_pool' => $totalPool,
            'total_distributed' => $totalDistributed,
            'available' => $available,
            'distribution_count' => $distributionCount
        ];
        
    } catch(PDOException $e) {
        error_log('General Pool Stats Error: ' . $e->getMessage());
        return [
            'total_pool' => 0,
            'total_distributed' => 0,
            'available' => 0,
            'distribution_count' => 0
        ];
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
    
    // Authorize before dispatching. Actor identity always comes from the session,
    // never from the request body, so a caller cannot act as another user.
    $actor = getCurrentUser();
    $requiredRole = ACTION_REQUIRED_ROLES[$action] ?? null;
    
    if ($requiredRole !== null) {
        if (!$actor) {
            echo json_encode(['success' => false, 'errors' => ['You must be logged in to do that']]);
            exit();
        }
        if ($requiredRole !== 'any' && $actor['role'] !== $requiredRole) {
            echo json_encode(['success' => false, 'errors' => ['You are not allowed to do that']]);
            exit();
        }
    }
    
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
            // A victim may only update their own application.
            $userId = $actor['user_id'];
            $location = $_POST['location'] ?? '';
            $vulnerability = $_POST['vulnerability'] ?? '';
            $urgentNeeds = $_POST['urgent_needs'] ?? '';
            $result = handleVictimApplicationUpdate($conn, $userId, $location, $vulnerability, $urgentNeeds);
            echo json_encode($result);
            break;
            
        case 'make_donation':
            $donorId = getDonorIdForUser($conn, $actor['user_id']);
            $victimId = !empty($_POST['victim_id']) ? $_POST['victim_id'] : null;
            $amount = $_POST['amount'] ?? 0;
            $donationType = $_POST['donation_type'] ?? 'monetary';
            $description = $_POST['description'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'cash';
            $mpesaPhone = $_POST['mpesa_phone'] ?? null;
            $result = handleDonation($conn, $donorId, $victimId, $amount, $donationType, $description, $paymentMethod, $mpesaPhone);
            echo json_encode($result);
            break;
            
        case 'confirm_mpesa_payment':
            $result = confirmMpesaPayment($conn, $_POST['transaction_id'] ?? '', getDonorIdForUser($conn, $actor['user_id']));
            echo json_encode($result);
            break;
            
        case 'initiate_mpesa_payment':
            $donorId = getDonorIdForUser($conn, $actor['user_id']);
            $victimId = !empty($_POST['victim_id']) ? $_POST['victim_id'] : null;
            $amount = $_POST['amount'] ?? 0;
            $donationType = $_POST['donation_type'] ?? 'monetary';
            $description = $_POST['description'] ?? '';
            $mpesaPhone = $_POST['mpesa_phone'] ?? null;
            $result = initiateMpesaPayment($conn, $donorId, $victimId, $amount, $donationType, $description, $mpesaPhone);
            echo json_encode($result);
            break;
            
        case 'distribute_general_fund':
            $adminUserId = $actor['user_id'];
            $victimId = $_POST['victim_id'] ?? 0;
            $amount = $_POST['amount'] ?? 0;
            $notes = $_POST['notes'] ?? '';
            $result = distributeGeneralFund($conn, $adminUserId, $victimId, $amount, $notes);
            echo json_encode($result);
            break;
            
        default:
            echo json_encode(['success' => false, 'errors' => ['Invalid action']]);
            break;
    }
}
?>
