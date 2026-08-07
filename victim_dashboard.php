<?php
/**
 * NawiriKe CRM Victim Dashboard
 * Protected page for victim users only
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include authentication (authController.php already starts the session)
require_once 'authController.php';

// Check if user is logged in
requireLogin();

// Get current user data
$currentUser = getCurrentUser();

// Get real victim data from database
try {
    // Get victim information
    $stmt = $conn->prepare("
        SELECT v.*, u.email, u.created_at as user_created_at 
        FROM victims v 
        JOIN users u ON v.user_id = u.user_id 
        WHERE v.user_id = ?
    ");
    $stmt->execute([$currentUser['user_id']]);
    $victimInfo = $stmt->fetch();
    
    // Get donations received by this victim (only if victim exists)
    $donationsReceived = [];
    if ($victimInfo && !empty($victimInfo['victim_id'])) {
        $stmt = $conn->prepare("
            SELECT d.amount, d.donation_type, d.donated_at, d.description,
                   CASE 
                       WHEN d.donor_id IS NOT NULL THEN u.name
                       ELSE 'General Fund'
                   END as donor_name,
                   CASE 
                       WHEN d.donor_id IS NOT NULL THEN u.email
                       ELSE 'N/A'
                   END as donor_email,
                   CASE 
                       WHEN d.donor_id IS NULL THEN 'General Fund Distribution'
                       ELSE 'Direct Donation'
                   END as donation_source
            FROM donations d 
            LEFT JOIN donors dn ON d.donor_id = dn.donor_id 
            LEFT JOIN users u ON dn.user_id = u.user_id 
            WHERE d.victim_id = ? 
            ORDER BY d.donated_at DESC
            LIMIT 5
        ");
        $stmt->execute([$victimInfo['victim_id']]);
        $donationsReceived = $stmt->fetchAll();
    }
    
    // Calculate total donations received (only if victim exists)
    $donationStats = ['total_received' => 0, 'donation_count' => 0];
    if ($victimInfo && !empty($victimInfo['victim_id'])) {
        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(amount), 0) as total_received,
                COUNT(*) as donation_count
            FROM donations 
            WHERE victim_id = ?
        ");
        $stmt->execute([$victimInfo['victim_id']]);
        $donationStats = $stmt->fetch();
    }
    
} catch(PDOException $e) {
    $error = $e->getMessage();
    $victimInfo = [];
    $donationsReceived = [];
    $donationStats = ['total_received' => 0, 'donation_count' => 0];
}

// Note: nl2br() is a built-in PHP function, no need to redefine it

// Additional check: ensure user is a victim
if ($currentUser['role'] !== 'victim') {
    // Redirect to appropriate dashboard if wrong role
    switch($currentUser['role']) {
        case 'admin':
            header('Location: admin_dashboard.php');
            break;
        case 'donor':
            header('Location: donor_dashboard.php');
            break;
        default:
            header('Location: login.html');
            break;
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Victim Dashboard - NawiriKe CRM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info span {
            color: #666;
        }
        
        .logout-btn {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: #0056b3;
        }
        
        .application-status {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #ffc107;
            color: #856404;
        }
        
        .status-approved {
            background: #007bff;
            color: white;
        }
        
        .status-rejected {
            background: #007bff;
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .stat-card .number {
            color: #333;
            font-size: 32px;
            font-weight: bold;
        }
        
        .victim-actions {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .victim-actions h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            background: #007bff;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: background 0.3s;
        }
        
        .action-btn:hover {
            background: #0056b3;
        }
        
        .role-badge {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .application-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .submit-btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .submit-btn:hover {
            background: #0056b3;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-info {
            background: #cce5ff;
            color: #004085;
            border: 1px solid #99ccff;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>Victim Dashboard</h1>
                <p>NawiriKe CRM - Get Help & Support</p>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($currentUser['name']); ?></span>
                <span class="role-badge">VICTIM</span>
                <a href="authController.php?action=logout" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <!-- Application Status -->
        <div class="application-status">
            <h2>Application Status</h2>
            <div class="alert alert-info">
                <?php if ($victimInfo && !empty($victimInfo)): ?>
                    <strong>Status:</strong> <span class="status-badge status-<?php echo strtolower($victimInfo['verification_status']); ?>"><?php echo htmlspecialchars($victimInfo['verification_status']); ?></span>
                    <p style="margin-top: 10px;">
                        Application Date: <?php echo date('F j, Y', strtotime($victimInfo['date_registered'])); ?><br>
                        Location: <?php echo htmlspecialchars($victimInfo['location']); ?><br>
                        <?php if ($victimInfo['verification_status'] === 'Pending'): ?>
                            Your application is currently being reviewed by our admin team. You will be notified once a decision has been made.
                        <?php elseif ($victimInfo['verification_status'] === 'Approved'): ?>
                            Congratulations! Your application has been approved. You are now eligible to receive donations.
                        <?php else: ?>
                            Your application was not approved. Please contact support for more information.
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <strong>Status:</strong> <span class="status-badge status-pending">Not Started</span>
                    <p style="margin-top: 10px;">
                        You haven't completed your victim application yet.<br>
                        Please fill out the application form below to get started.<br>
                        Location: Not specified<br>
                        Your application will be reviewed by our admin team once submitted.
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Update Application Form -->
        <div class="application-form">
            <h2>Update Application</h2>
            <form id="application-form">
            <input type="hidden" name="action" value="update_victim_application">
            <input type="hidden" name="user_id" value="<?php echo $currentUser['user_id']; ?>">
                                <div class="form-group">
                    <label for="location">Current Location</label>
                    <input type="text" id="location" name="location" placeholder="Enter your current location" value="<?php echo htmlspecialchars($victimInfo['location'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="vulnerability">Vulnerability Description</label>
                    <textarea id="vulnerability" name="vulnerability" rows="4" placeholder="Describe your situation and help you need"><?php echo htmlspecialchars($victimInfo['vulnerability_description'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="urgent-needs">Urgent Needs</label>
                    <select id="urgent-needs" name="urgent_needs">
                        <option value="">Select your urgent need</option>
                        <option value="shelter" <?php echo ($victimInfo['urgent_needs'] ?? '') == 'shelter' ? 'selected' : ''; ?>>Shelter</option>
                        <option value="food" <?php echo ($victimInfo['urgent_needs'] ?? '') == 'food' ? 'selected' : ''; ?>>Food</option>
                        <option value="medical" <?php echo ($victimInfo['urgent_needs'] ?? '') == 'medical' ? 'selected' : ''; ?>>Medical Assistance</option>
                        <option value="clothing" <?php echo ($victimInfo['urgent_needs'] ?? '') == 'clothing' ? 'selected' : ''; ?>>Clothing</option>
                        <option value="education" <?php echo ($victimInfo['urgent_needs'] ?? '') == 'education' ? 'selected' : ''; ?>>Education Support</option>
                        <option value="other" <?php echo ($victimInfo['urgent_needs'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="additional-info">Additional Information</label>
                    <textarea id="additional-info" name="additional_info" rows="3" placeholder="Any additional information that may help with your application"></textarea>
                </div>
                <button type="submit" class="submit-btn">Update Application</button>
            </form>
        </div>
        
        <!-- Donations Received -->
        <div class="victim-actions">
            <h2>Donations Received</h2>
            <?php if (empty($donationsReceived)): ?>
                <p style="color: #666;">No donations received yet.</p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Date</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Amount</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Type</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Source</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Donor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donationsReceived as $donation): ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;"><?php echo date('M j, Y', strtotime($donation['donated_at'])); ?></td>
                                <td style="padding: 12px;">KES <?php echo number_format($donation['amount'], 2); ?></td>
                                <td style="padding: 12px;"><?php echo ucfirst($donation['donation_type']); ?></td>
                                <td style="padding: 12px;">
                                    <span style="background: <?php echo $donation['donation_source'] === 'General Fund Distribution' ? '#ffc107' : '#28a745'; ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                                        <?php echo htmlspecialchars($donation['donation_source']); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Received</h3>
                <div class="number">KES <?php echo number_format($donationStats['total_received'], 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>Donations Count</h3>
                <div class="number"><?php echo $donationStats['donation_count']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Application Date</h3>
                <div class="number"><?php echo $victimInfo && !empty($victimInfo['date_registered']) ? date('M j, Y', strtotime($victimInfo['date_registered'])) : 'Not Set'; ?></div>
            </div>
            <div class="stat-card">
                <h3>Status</h3>
                <div class="number"><?php echo $victimInfo && !empty($victimInfo['verification_status']) ? htmlspecialchars($victimInfo['verification_status']) : 'Not Started'; ?></div>
            </div>
        </div>
        
        <!-- Victim Actions -->
        <div class="victim-actions">
            <h2>My Actions</h2>
            <div class="action-buttons">
                <button class="action-btn" onclick="viewApplicationDetails()">View Application</button>
                <button class="action-btn" onclick="contactSupport()">Contact Support</button>
                <button class="action-btn" onclick="viewHelpHistory()">Help History</button>
                <button class="action-btn" onclick="updateProfile()">Update Profile</button>
            </div>
        </div>
    </div>
    
    <script>
        // Handle application form submission
        document.getElementById('application-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;
            
            // Send update request to authController.php
            fetch('authController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Application updated successfully! Our team will review your updates.');
                    location.reload(); // Refresh to show updated data
                } else {
                    alert('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Victim action functions (simple placeholders)
        function viewApplicationDetails() {
            alert('Application Details - Feature coming soon!');
        }
        
        function contactSupport() {
            alert('Contact Support - Feature coming soon!');
        }
        
        function viewHelpHistory() {
            alert('Help History - Feature coming soon!');
        }
        
        function updateProfile() {
            alert('Update Profile - Feature coming soon!');
        }
    </script>
</body>
</html>
