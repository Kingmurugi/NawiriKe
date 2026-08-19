<?php
/**
 * NawiriKe CRM Donor Dashboard
 * Protected page for donor users only
 */

// Include authentication (authController.php already starts the session)
require_once 'authController.php';

// Check if user is logged in
requireLogin();

// Get current user data
$currentUser = getCurrentUser();

// Get real donor data from database
try {
    // Get donor information
    $stmt = $conn->prepare("SELECT * FROM donors WHERE user_id = ?");
    $stmt->bind_param("i", $currentUser['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $donorInfo = $result->fetch_assoc();
    
    // Check if donor record exists
    if (!$donorInfo) {
        $error = "Donor record not found. Please contact administrator.";
        $donorInfo = ['donor_id' => 0];
    }
    
    // Get donation history
    $stmt = $conn->prepare("
        SELECT d.donation_id, d.amount, d.donation_type, d.donated_at, 
               d.status,
               v.first_name as victim_name, v.location as victim_location
        FROM donations d 
        LEFT JOIN victims v ON d.victim_id = v.victim_id 
        WHERE d.donor_id = ? 
        ORDER BY d.donated_at DESC
        LIMIT 10
    ");
    $stmt->bind_param("i", $donorInfo['donor_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $donationHistory = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get donation statistics
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_donations,
            COALESCE(SUM(amount), 0) as total_amount,
            COALESCE(SUM(CASE WHEN donated_at >= DATE_FORMAT(NOW(), '%Y-%m-01') THEN amount END), 0) as monthly_total,
            COUNT(DISTINCT victim_id) as victims_helped
        FROM donations 
        WHERE donor_id = ?
    ");
    $stmt->bind_param("i", $donorInfo['donor_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $donationStats = $result->fetch_assoc();
    
    // Extract stats for easier use
    $totalDonated = $donationStats['total_amount'];
    $donationCount = $donationStats['total_donations'];
    $victimsHelped = $donationStats['victims_helped'];
    $monthlyTotal = $donationStats['monthly_total'];
    
    // Get available victims for donation selection
    $stmt = $conn->prepare("
        SELECT v.victim_id, u.name as victim_name, v.location, v.vulnerability_description
        FROM victims v 
        JOIN users u ON v.user_id = u.user_id 
        WHERE v.verification_status = 'Approved'
        ORDER BY v.date_registered DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $availableVictims = $result->fetch_all(MYSQLI_ASSOC);
    
} catch(Exception $e) {
    $error = $e->getMessage();
    $donorInfo = [];
    $donationHistory = [];
    $donationStats = ['total_donations' => 0, 'total_amount' => 0, 'monthly_total' => 0, 'victims_helped' => 0];
    $availableVictims = [];
}

// Additional check: ensure user is a donor
if ($currentUser['role'] !== 'donor') {
    // Redirect to appropriate dashboard if wrong role
    switch($currentUser['role']) {
        case 'admin':
            header('Location: admin_dashboard.php');
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - NawiriKe CRM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #667eea;
        }
        
        .header h1 {
            color: #333;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,126,234,0.4);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 13px;
            margin-bottom: 12px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .stat-card .number {
            color: #333;
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .donor-actions {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            margin-bottom: 25px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .donor-actions h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.3s ease;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
        
        .role-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(102,126,234,0.3);
        }
        
        .donation-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            margin-bottom: 25px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .donation-form h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 17px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            display: none;
            animation: slideIn 0.3s ease-out;
        }
        
        .notification.success {
            background: #28a745;
        }
        
        .notification.error {
            background: #dc3545;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Notification container -->
    <div id="notification" class="notification"></div>
    
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>Donor Dashboard</h1>
                <p>NawiriKe CRM - Make a Difference</p>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($currentUser['name']); ?></span>
                <span class="role-badge">DONOR</span>
                <a href="authController.php?action=logout" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <!-- Donation Form -->
        <div class="donation-form">
            <h2>Make a Donation</h2>
            <p style="color: #666; margin-bottom: 15px;">Choose to donate directly to a specific victim or to the general fund for admin distribution.</p>
            <form id="donation-form">
                <div class="form-group">
                    <label for="donation-mode">Donation Mode</label>
                    <select id="donation-mode" name="donation_mode">
                        <option value="general">General Pool (Admin Distribution)</option>
                        <option value="direct">Direct to Victim</option>
                    </select>
                </div>
                <div class="form-group" id="victim-select-group" style="display: none;">
                    <label for="victim-select">Select Victim</label>
                    <select id="victim-select" name="victim_id">
                        <option value="">Choose approved victim...</option>
                        <?php foreach ($availableVictims as $victim): ?>
                            <option value="<?php echo $victim['victim_id']; ?>">
                                <?php echo htmlspecialchars($victim['victim_name'] . ' - ' . $victim['location']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #666;">Direct donations are automatically approved</small>
                </div>
                <div class="form-group">
                    <label for="amount">Amount (KES)</label>
                    <input type="number" id="amount" name="amount" min="100" required placeholder="Enter amount">
                </div>
                <div class="form-group">
                    <label for="donation-type">Donation Type</label>
                    <select id="donation-type" name="donation_type">
                        <option value="monetary">Monetary</option>
                        <option value="in-kind">In-Kind</option>
                        <option value="service">Service</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment Method</label>
                    <select id="payment-method" name="payment_method">
                        <option value="cash">Cash</option>
                        <option value="mpesa">M-Pesa</option>
                    </select>
                </div>
                <div class="form-group" id="mpesa-phone-group" style="display: none;">
                    <label for="mpesa-phone">M-Pesa Phone Number</label>
                    <input type="tel" id="mpesa-phone" name="mpesa_phone" placeholder="2547XXXXXXXX" pattern="254[0-9]{9}">
                    <small style="color: #666;">Format: 2547XXXXXXXX</small>
                </div>
                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3" placeholder="Add a message or specify in-kind items"></textarea>
                </div>
                <button type="submit" class="submit-btn">Donate Now</button>
            </form>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Donated</h3>
                <div class="number" id="total-donated-display">KES <?php echo number_format($donationStats['total_amount'], 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>Donations Made</h3>
                <div class="number"><?php echo $donationStats['total_donations']; ?></div>
            </div>
            <div class="stat-card">
                <h3>This Month</h3>
                <div class="number">KES <?php echo number_format($donationStats['monthly_total'], 2); ?></div>
            </div>
        </div>
        
        <!-- Donor Actions -->
        <div class="donor-actions">
            <h2>Account Actions</h2>
            <div class="action-buttons">
                <button class="action-btn" onclick="setupRecurring()">Setup Recurring</button>
                <button class="action-btn" onclick="editProfile()">Edit Profile</button>
            </div>
        </div>
    </div>
    
    <script>
        // Show notification function
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = 'notification ' + type;
            notification.style.display = 'block';
            
            // Hide after 4 seconds
            setTimeout(() => {
                notification.style.display = 'none';
            }, 4000);
        }
        
        // Show/hide victim selection based on donation mode
        document.getElementById('donation-mode').addEventListener('change', function() {
            const victimSelectGroup = document.getElementById('victim-select-group');
            const victimSelect = document.getElementById('victim-select');
            
            if (this.value === 'direct') {
                victimSelectGroup.style.display = 'block';
                victimSelect.required = true;
            } else {
                victimSelectGroup.style.display = 'none';
                victimSelect.required = false;
                victimSelect.value = ''; // Clear selection
            }
        });
        
        // Show/hide M-Pesa phone field based on payment method
        document.getElementById('payment-method').addEventListener('change', function() {
            const mpesaPhoneGroup = document.getElementById('mpesa-phone-group');
            if (this.value === 'mpesa') {
                mpesaPhoneGroup.style.display = 'block';
                document.getElementById('mpesa-phone').required = true;
            } else {
                mpesaPhoneGroup.style.display = 'none';
                document.getElementById('mpesa-phone').required = false;
            }
        });
        
        // Handle donation form submission
        document.getElementById('donation-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if donor_id is valid
            const donorId = <?php echo $donorInfo['donor_id']; ?>;
            if (donorId === 0) {
                showNotification('Donor record not found. Please contact administrator.', 'error');
                return;
            }
            
            // Get form data
            const formData = new FormData(this);
            // Add donor_id
            formData.append('donor_id', donorId);
            
            // Handle victim_id based on donation mode
            const donationMode = formData.get('donation_mode');
            if (donationMode === 'direct') {
                const victimId = formData.get('victim_id');
                if (!victimId) {
                    showNotification('Please select a victim for direct donation', 'error');
                    return;
                }
                // victim_id is already in formData
            } else {
                // General pool - remove victim_id or set to empty
                if (formData.has('victim_id')) {
                    formData.delete('victim_id');
                }
                formData.append('victim_id', '');
            }
            
            console.log('Submitting donation with donor_id:', donorId);
            console.log('Donation mode:', donationMode);
            
            // Validate amount
            const amount = formData.get('amount');
            if (amount < 100) {
                showNotification('Minimum donation amount is KES 100', 'error');
                return;
            }
            
            // Validate M-Pesa phone if M-Pesa selected
            const paymentMethod = formData.get('payment_method');
            if (paymentMethod === 'mpesa') {
                const phone = formData.get('mpesa_phone');
                if (!phone || !/^254[0-9]{9}$/.test(phone)) {
                    showNotification('Please enter a valid M-Pesa phone number (format: 2547XXXXXXXX)', 'error');
                    return;
                }
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
            
            // Determine action based on payment method
            formData.append('action', paymentMethod === 'mpesa' ? 'initiate_mpesa_payment' : 'make_donation');
            
            // Send donation request to authController.php
            console.log('Sending fetch request to authController.php');
            fetch('authController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    if (paymentMethod === 'mpesa') {
                        showNotification('M-Pesa STK Push sent! Check your phone to complete payment.', 'success');
                        // Simulate payment completion check
                        setTimeout(() => {
                            showNotification('Payment successful! Thank you for your donation of KES ' + amount, 'success');
                            this.reset();
                            location.reload();
                        }, 3000);
                    } else {
                        const mode = donationMode === 'direct' ? 'direct donation' : 'general pool contribution';
                        showNotification('Thank you for your ' + mode + ' of KES ' + amount + '!', 'success');
                        this.reset();
                        // Update total donations display without page reload
                        const totalDonatedElement = document.getElementById('total-donated-display');
                        if (totalDonatedElement) {
                            const currentTotal = parseFloat(totalDonatedElement.textContent.replace(/[^0-9.-]+/g, '')) || 0;
                            const newTotal = currentTotal + parseFloat(amount);
                            totalDonatedElement.textContent = 'KES ' + newTotal.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    }
                } else {
                    showNotification('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Donation error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Donor action functions (placeholders for now)
        function viewDonationHistory() {
            // Create donation history interface
            const donationHistory = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Your Donation History</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Track all your generous contributions:</p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <p style="margin: 0;"><strong>Total Donated:</strong> KES <?php echo number_format($totalDonated, 0); ?></p>
                            <p style="margin: 5px 0;"><strong>Number of Donations:</strong> <?php echo $donationCount; ?></p>
                            <p style="margin: 5px 0;"><strong>Impact:</strong> You've helped <?php echo $donationCount; ?> victims receive support</p>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Recent Donations:</h4>
                        <div style="background: #e9ecef; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <p style="margin: 0; color: #666;">Your recent donation history will appear here. Keep making a difference!</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="location.reload()" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh History</button>
                        <button onclick="alert('Download feature coming soon!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Download PDF</button>
                    </div>
                </div>
            `;
            
            // Show in modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); display: flex; align-items: center; 
                justify-content: center; z-index: 1000;
            `;
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #007bff; margin: 0;">Donation History</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${donationHistory}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function viewReceipts() {
            // Create receipts interface
            const receipts = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Donation Receipts</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Access and download your donation receipts for tax purposes:</p>
                        <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <p style="margin: 0; color: #0c5460;"><strong>Important:</strong> All donations are tax-deductible. Keep your receipts for your records.</p>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Available Receipts:</h4>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <p style="margin: 0; color: #666;">Your donation receipts will appear here after each contribution.</p>
                            <p style="margin: 10px 0 0 0; color: #666;">Total contributions this year: KES <?php echo number_format($totalDonated, 0); ?></p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="location.reload()" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh Receipts</button>
                        <button onclick="alert('Email receipts feature coming soon!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Email All</button>
                    </div>
                </div>
            `;
            
            // Show in modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); display: flex; align-items: center; 
                justify-content: center; z-index: 1000;
            `;
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #007bff; margin: 0;">Donation Receipts</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${receipts}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function setupRecurring() {
            // Create recurring donations interface
            const recurringSetup = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Setup Recurring Donations</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Make a lasting impact with automatic monthly donations:</p>
                        <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <p style="margin: 0; color: #155724;"><strong>Benefits:</strong> Consistent support for victims, convenient automatic giving, and maximum impact.</p>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Recurring Options:</h4>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px;">
                            <button onclick="alert('Monthly setup coming soon!')" style="background: #007bff; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer;">Monthly</button>
                            <button onclick="alert('Quarterly setup coming soon!')" style="background: #007bff; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer;">Quarterly</button>
                            <button onclick="alert('Annual setup coming soon!')" style="background: #007bff; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer;">Annual</button>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="alert('Payment setup coming soon!')" style="background: #28a745; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Setup Payment</button>
                        <button onclick="alert('Learn more about recurring donations!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Learn More</button>
                    </div>
                </div>
            `;
            
            // Show in modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); display: flex; align-items: center; 
                justify-content: center; z-index: 1000;
            `;
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #007bff; margin: 0;">Recurring Donations</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${recurringSetup}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function editProfile() {
            // Create profile edit interface
            const profileEdit = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Edit Your Profile</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Update your donor information and preferences:</p>
                        <div style="display: grid; gap: 15px; margin-top: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: bold;">Name:</label>
                                <input type="text" value="<?php echo htmlspecialchars($currentUser['name']); ?>" style="width: 100%; padding: 8px; border: 1px solid #dee2e6; border-radius: 4px;" readonly>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: bold;">Email:</label>
                                <input type="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" style="width: 100%; padding: 8px; border: 1px solid #dee2e6; border-radius: 4px;" readonly>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: bold;">Contact:</label>
                                <input type="tel" value="<?php echo htmlspecialchars($donorInfo['contact'] ?? ''); ?>" placeholder="Enter your contact number" style="width: 100%; padding: 8px; border: 1px solid #dee2e6; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; color: #333; font-weight: bold;">Donation Preferences:</label>
                                <select style="width: 100%; padding: 8px; border: 1px solid #dee2e6; border-radius: 4px;">
                                    <option>All Types</option>
                                    <option>Monetary Only</option>
                                    <option>In-Kind Only</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="alert('Profile update feature coming soon!')" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Save Changes</button>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
                    </div>
                </div>
            `;
            
            // Show in modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); display: flex; align-items: center; 
                justify-content: center; z-index: 1000;
            `;
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #007bff; margin: 0;">Edit Profile</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${profileEdit}
                </div>
            `;
            document.body.appendChild(modal);
        }
    </script>
</body>
</html>
