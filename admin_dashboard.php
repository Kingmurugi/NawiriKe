<?php

require_once 'authController.php';

requireAdmin();

$currentUser = getCurrentUser();

try {
    // Total users count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $totalUsers = $result->fetch_assoc()['total'];
    
    // Total donors count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM donors d JOIN users u ON d.user_id = u.user_id");
    $stmt->execute();
    $result = $stmt->get_result();
    $totalDonors = $result->fetch_assoc()['total'];
    
    // Total victims count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM victims v JOIN users u ON v.user_id = u.user_id");
    $stmt->execute();
    $result = $stmt->get_result();
    $totalVictims = $result->fetch_assoc()['total'];
    
    // Total donations sum
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM donations");
    $stmt->execute();
    $result = $stmt->get_result();
    $totalDonations = $result->fetch_assoc()['total'];
    
    // Get general pool statistics
    $generalPoolStats = getGeneralPoolStats($conn);
    
    // Get approved victims for fund distribution
    $stmt = $conn->prepare("
        SELECT v.victim_id, u.name, u.email, v.location, v.urgent_needs, v.verification_status
        FROM victims v 
        JOIN users u ON v.user_id = u.user_id 
        WHERE v.verification_status = 'Approved'
        ORDER BY v.date_registered DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $approvedVictims = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get pending victims for approval
    $stmt = $conn->prepare("
        SELECT v.victim_id, u.name, u.email, v.location, v.verification_status, v.date_registered 
        FROM victims v 
        JOIN users u ON v.user_id = u.user_id 
        WHERE v.verification_status = 'Pending' 
        ORDER BY v.date_registered DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $pendingVictims = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get all users list
    $stmt = $conn->prepare("
        SELECT u.user_id, u.name, u.email, u.role, u.created_at,
               CASE 
                   WHEN u.role = 'donor' THEN (SELECT COALESCE(SUM(d.total_donated), 0) FROM donors d WHERE d.user_id = u.user_id)
                   WHEN u.role = 'victim' THEN v.verification_status
                   ELSE NULL
               END as additional_info
        FROM users u 
        LEFT JOIN donors d ON u.user_id = d.user_id 
        LEFT JOIN victims v ON u.user_id = v.user_id 
        ORDER BY u.created_at DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $allUsers = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get donation reports data
    $stmt = $conn->prepare("
        SELECT d.donation_id, u.name as donor_name, v.name as victim_name, 
               d.amount, d.donation_type, d.description, d.donated_at, d.status, d.payment_method
        FROM donations d
        LEFT JOIN donors don ON d.donor_id = don.donor_id
        LEFT JOIN users u ON don.user_id = u.user_id
        LEFT JOIN victims vic ON d.victim_id = vic.victim_id
        LEFT JOIN users v ON vic.user_id = v.user_id
        ORDER BY d.donated_at DESC
        LIMIT 50
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $recentDonations = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get distribution reports data
    $stmt = $conn->prepare("
        SELECT dist.distribution_id, dist.amount, dist.distribution_date, dist.notes,
               u.name as victim_name, v.location
        FROM distributions dist
        LEFT JOIN victims v ON dist.victim_id = v.victim_id
        LEFT JOIN users u ON v.user_id = u.user_id
        ORDER BY dist.distribution_date DESC
        LIMIT 50
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $recentDistributions = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get donor ranking report
    $stmt = $conn->prepare("
        SELECT u.name, u.email, d.total_donated, d.donation_count, d.created_at
        FROM donors d
        JOIN users u ON d.user_id = u.user_id
        ORDER BY d.total_donated DESC
        LIMIT 20
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $topDonors = $result->fetch_all(MYSQLI_ASSOC);
    
} catch(Exception $e) {
    $error = $e->getMessage();
    $totalUsers = $totalDonors = $totalVictims = $totalDonations = 0;
    $pendingVictims = $allUsers = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NawiriKe CRM</title>
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }
        
        .header {
            background: white;
            border-radius: 20px;
            padding: 30px 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 5px solid #667eea;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header h1 {
            color: #333;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info span {
            color: #666;
            font-weight: 500;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,126,234,0.4);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .stat-card .number {
            color: #333;
            font-size: 42px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .admin-actions {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.05);
            animation: fadeIn 0.6s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .admin-actions h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            border-bottom: 4px solid #667eea;
            padding-bottom: 15px;
        }
        
        .role-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(102,126,234,0.3);
        }
        
        .report-section {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .report-section h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            border-bottom: 4px solid #667eea;
            padding-bottom: 15px;
        }
        
        .report-table table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .report-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .report-table th {
            color: white;
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .report-table td {
            padding: 18px;
            border-bottom: 1px solid #e9ecef;
            color: #333;
            font-size: 15px;
        }
        
        .report-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .report-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
        }
        
        .report-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .status-failed {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }
        
        .amount-highlight {
            font-weight: 700;
            color: #667eea;
            font-size: 16px;
        }
        
        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(102,126,234,0.3);
            transition: all 0.3s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(102,126,234,0.4);
        }
        
        .summary-card h4 {
            font-size: 15px;
            margin-bottom: 12px;
            opacity: 0.95;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-card .value {
            font-size: 32px;
            font-weight: 800;
        }
        
        .report-selector {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .report-selector h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .report-selector select {
            width: 100%;
            padding: 15px 20px;
            border: 3px solid #667eea;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 45px;
        }
        
        .report-selector select:hover {
            border-color: #764ba2;
            box-shadow: 0 4px 15px rgba(102,126,234,0.2);
        }
        
        .report-selector select:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.15);
        }
        
        .report-content {
            display: none;
        }
        
        .report-content.active {
            display: block;
        }
        
        .report-table {
            max-height: 450px;
            overflow-y: auto;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        
        .report-table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .report-table::-webkit-scrollbar {
            width: 8px;
        }
        
        .report-table::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .report-table::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
        
        button {
            transition: all 0.3s ease;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        /* Enhanced form styling */
        input[type="number"], 
        input[type="text"],
        select {
            transition: all 0.3s ease;
        }
        
        input[type="number"]:focus, 
        input[type="text"]:focus,
        select:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.2);
        }
        
        /* Enhanced table row styling */
        .report-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .report-table tbody tr:nth-child(even):hover {
            background: linear-gradient(135deg, rgba(102,126,234,0.08) 0%, rgba(118,75,162,0.08) 100%);
        }
        
        /* Enhanced status badges */
        .status-badge {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Enhanced summary cards */
        .summary-card {
            position: relative;
            overflow: hidden;
        }
        
        .summary-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        /* Enhanced header gradient */
        .header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        /* Enhanced stat cards */
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        /* Enhanced admin actions section */
        .admin-actions {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        /* Enhanced report section */
        .report-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        /* Enhanced report selector */
        .report-selector {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>Admin Dashboard</h1>
                <p>NawiriKe CRM Management System</p>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($currentUser['name']); ?></span>
                <span class="role-badge">ADMIN</span>
                <a href="authController.php?action=logout" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?php echo number_format($totalUsers); ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Donors</h3>
                <div class="number"><?php echo number_format($totalDonors); ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Victims</h3>
                <div class="number"><?php echo number_format($totalVictims); ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Donations</h3>
                <div class="number">KES <?php echo number_format($totalDonations, 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>General Pool</h3>
                <div class="number">KES <?php echo number_format($generalPoolStats['total_pool'], 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>Available for Distribution</h3>
                <div class="number">KES <?php echo number_format($generalPoolStats['available'], 2); ?></div>
            </div>
        </div>
        
        <!-- General Fund Distribution -->
        <div class="admin-actions">
            <h2>General Fund Distribution</h2>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <p><strong>General Pool Total:</strong> KES <?php echo number_format($generalPoolStats['total_pool'], 2); ?></p>
                <p><strong>Total Distributed:</strong> KES <?php echo number_format($generalPoolStats['total_distributed'], 2); ?></p>
                <p><strong>Available for Distribution:</strong> KES <?php echo number_format($generalPoolStats['available'], 2); ?></p>
                <p><strong>Distributions Made:</strong> <?php echo $generalPoolStats['distribution_count']; ?></p>
            </div>
            
            <?php if ($generalPoolStats['available'] > 0 && !empty($approvedVictims)): ?>
                <form id="distribution-form" style="margin-bottom: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Select Victim:</label>
                            <select id="victim-select" name="victim_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="">Choose approved victim...</option>
                                <?php foreach ($approvedVictims as $victim): ?>
                                    <option value="<?php echo $victim['victim_id']; ?>">
                                        <?php echo htmlspecialchars($victim['name'] . ' - ' . $victim['location'] . ' (' . ucfirst($victim['urgent_needs']) . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Amount (KES):</label>
                            <input type="number" id="distribution-amount" name="amount" min="100" max="<?php echo $generalPoolStats['available']; ?>" required placeholder="Amount" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Notes (Optional):</label>
                            <input type="text" id="distribution-notes" name="notes" placeholder="Reason for distribution" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div>
                            <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Distribute Funds</button>
                        </div>
                    </div>
                </form>
            <?php elseif ($generalPoolStats['available'] <= 0): ?>
                <p style="color: #666; font-style: italic;">No funds available for distribution.</p>
            <?php elseif (empty($approvedVictims)): ?>
                <p style="color: #666; font-style: italic;">No approved victims available for distribution.</p>
            <?php endif; ?>
        </div>
        
        <!-- Pending Victims -->
        <div class="admin-actions">
            <h2>Pending Victim Applications</h2>
            <?php if (empty($pendingVictims)): ?>
                <p style="color: #666;">No pending victim applications at this time.</p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Name</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Email</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Location</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Applied</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingVictims as $victim): ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;"><?php echo htmlspecialchars($victim['name']); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($victim['email']); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($victim['location']); ?></td>
                                <td style="padding: 12px;"><?php echo date('M j, Y', strtotime($victim['date_registered'])); ?></td>
                                <td style="padding: 12px;">
                                    <button onclick="approveVictim(<?php echo $victim['victim_id']; ?>)" style="background: #007bff; color: white; padding: 6px 12px; border: none; border-radius: 4px; margin-right: 5px; cursor: pointer;">Approve</button>
                                    <button onclick="rejectVictim(<?php echo $victim['victim_id']; ?>)" style="background: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Report Selector -->
        <div class="report-selector">
            <h3>Select Report to View</h3>
            <select id="report-select" onchange="switchReport()">
                <option value="all-users">All Users</option>
                <option value="donations">Donation Reports</option>
                <option value="distributions">Fund Distribution Reports</option>
                <option value="top-donors">Top Donors Ranking</option>
            </select>
        </div>
        
        <!-- All Users Report -->
        <div class="report-content" id="report-all-users">
            <div class="report-section">
                <h2>All Users</h2>
                <div class="report-summary">
                    <div class="summary-card">
                        <h4>Total Users</h4>
                        <div class="value"><?php echo count($allUsers); ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Donors</h4>
                        <div class="value"><?php echo $totalDonors; ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Victims</h4>
                        <div class="value"><?php echo $totalVictims; ?></div>
                    </div>
                </div>
                <?php if (empty($allUsers)): ?>
                    <p style="color: #666;">No users found in the system.</p>
                <?php else: ?>
                    <div class="report-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Additional Info</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="status-badge" style="background: <?php 
                                                echo $user['role'] === 'admin' ? '#dc3545' : 
                                                    ($user['role'] === 'donor' ? '#28a745' : '#ffc107'); 
                                            ?>; color: white;">
                                                <?php echo strtoupper($user['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($user['role'] === 'donor') {
                                                echo '<span class="amount-highlight">KES ' . number_format($user['additional_info'], 2) . '</span>';
                                            } elseif ($user['role'] === 'victim') {
                                                echo '<span class="status-badge ' . ($user['additional_info'] === 'Approved' ? 'status-completed' : 'status-pending') . '">' . htmlspecialchars($user['additional_info']) . '</span>';
                                            } else {
                                                echo 'Administrator';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <button onclick="viewUser(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['name']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', '<?php echo $user['role']; ?>')" style="background: #007bff; color: white; padding: 6px 12px; border: none; border-radius: 4px; margin-right: 5px; cursor: pointer; font-size: 12px;">View</button>
                                            <button onclick="editUser(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['name']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', '<?php echo $user['role']; ?>')" style="background: #ffc107; color: white; padding: 6px 12px; border: none; border-radius: 4px; margin-right: 5px; cursor: pointer; font-size: 12px;">Edit</button>
                                            <button onclick="deleteUser(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['name']); ?>')" style="background: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Donation Reports -->
        <div class="report-content" id="report-donations">
            <div class="report-section">
                <h2>Donation Reports</h2>
                <div class="report-summary">
                    <div class="summary-card">
                        <h4>Total Donations</h4>
                        <div class="value">KES <?php echo number_format($totalDonations, 0); ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Total Transactions</h4>
                        <div class="value"><?php echo count($recentDonations); ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Active Donors</h4>
                        <div class="value"><?php echo $totalDonors; ?></div>
                    </div>
                </div>
                <?php if (empty($recentDonations)): ?>
                    <p style="color: #666;">No donation records found.</p>
                <?php else: ?>
                    <div class="report-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Donor</th>
                                    <th>Victim</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDonations as $donation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['donor_name'] ?? 'General Pool'); ?></td>
                                        <td><?php echo htmlspecialchars($donation['victim_name'] ?? 'General Pool'); ?></td>
                                        <td><span class="amount-highlight">KES <?php echo number_format($donation['amount'], 2); ?></span></td>
                                        <td><?php echo ucfirst($donation['donation_type']); ?></td>
                                        <td><?php echo ucfirst($donation['payment_method']); ?></td>
                                        <td><span class="status-badge status-<?php echo strtolower($donation['status']); ?>"><?php echo ucfirst($donation['status']); ?></span></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($donation['donated_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Distribution Reports -->
        <div class="report-content" id="report-distributions">
            <div class="report-section">
                <h2>Fund Distribution Reports</h2>
                <div class="report-summary">
                    <div class="summary-card">
                        <h4>Total Distributed</h4>
                        <div class="value">KES <?php echo number_format($generalPoolStats['total_distributed'], 0); ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Distributions Made</h4>
                        <div class="value"><?php echo $generalPoolStats['distribution_count']; ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Available Funds</h4>
                        <div class="value">KES <?php echo number_format($generalPoolStats['available'], 0); ?></div>
                    </div>
                </div>
                <?php if (empty($recentDistributions)): ?>
                    <p style="color: #666;">No distribution records found.</p>
                <?php else: ?>
                    <div class="report-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Victim</th>
                                    <th>Location</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDistributions as $distribution): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($distribution['victim_name']); ?></td>
                                        <td><?php echo htmlspecialchars($distribution['location']); ?></td>
                                        <td><span class="amount-highlight">KES <?php echo number_format($distribution['amount'], 2); ?></span></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($distribution['distribution_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($distribution['notes'] ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Top Donors Report -->
        <div class="report-content" id="report-top-donors">
            <div class="report-section">
                <h2>Top Donors Ranking</h2>
                <div class="report-summary">
                    <div class="summary-card">
                        <h4>Total Ranked</h4>
                        <div class="value"><?php echo count($topDonors); ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Total Donated</h4>
                        <div class="value">KES <?php echo number_format($totalDonations, 0); ?></div>
                    </div>
                    <div class="summary-card">
                        <h4>Average Donation</h4>
                        <div class="value">KES <?php echo count($topDonors) > 0 ? number_format($totalDonations / count($topDonors), 0) : 0; ?></div>
                    </div>
                </div>
                <?php if (empty($topDonors)): ?>
                    <p style="color: #666;">No donor records found.</p>
                <?php else: ?>
                    <div class="report-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Donor Name</th>
                                    <th>Email</th>
                                    <th>Total Donated</th>
                                    <th>Donation Count</th>
                                    <th>Member Since</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rank = 1; foreach ($topDonors as $donor): ?>
                                    <tr>
                                        <td><span class="status-badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;"><?php echo $rank++; ?></span></td>
                                        <td><?php echo htmlspecialchars($donor['name']); ?></td>
                                        <td><?php echo htmlspecialchars($donor['email']); ?></td>
                                        <td><span class="amount-highlight">KES <?php echo number_format($donor['total_donated'], 2); ?></span></td>
                                        <td><?php echo $donor['donation_count']; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($donor['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Switch between reports
        function switchReport() {
            const select = document.getElementById('report-select');
            const selectedReport = select.value;
            
            console.log('Selected report:', selectedReport);
            
            // Hide all report contents by removing active class
            document.querySelectorAll('.report-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show selected report by adding active class
            const selectedContent = document.getElementById('report-' + selectedReport);
            if (selectedContent) {
                selectedContent.classList.add('active');
                console.log('Showing report:', selectedReport);
            } else {
                console.log('Report not found:', selectedReport);
            }
        }
        
        // Initialize - show first report by default
        document.addEventListener('DOMContentLoaded', function() {
            // Call switchReport to show the default selected report
            switchReport();
        });
        
        // Handle general fund distribution form
        document.getElementById('distribution-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'distribute_general_fund');
            formData.append('admin_user_id', <?php echo $currentUser['user_id']; ?>);
            
            // Add notes if provided
            const notes = document.getElementById('distribution-notes').value;
            if (notes) {
                formData.append('notes', notes);
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
            
            fetch('authController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('General fund distributed successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Distribution error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Load dashboard statistics
        function loadStats() {
            // This would typically make AJAX calls to get real data
            // For now, showing placeholder data
            document.getElementById('total-users').textContent = '25';
            document.getElementById('total-donors').textContent = '12';
            document.getElementById('total-victims').textContent = '8';
            document.getElementById('total-donations').textContent = 'KES 45,000';
        }
        
        // Victim approval functions
        function approveVictim(victimId) {
            if (confirm('Are you sure you want to approve this victim application?')) {
                fetch('authController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=approve_victim&victim_id=' + victimId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Victim application approved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }
        
        function rejectVictim(victimId) {
            if (confirm('Are you sure you want to reject this victim application?')) {
                fetch('authController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=reject_victim&victim_id=' + victimId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Victim application rejected successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }
        
        // User management functions
        function viewUser(userId, userName, userEmail, userRole) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); display: flex; align-items: center; 
                justify-content: center; z-index: 1000;
            `;
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; width: 90%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #667eea; margin: 0;">User Details</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                        <p style="margin: 10px 0;"><strong>Name:</strong> ${userName}</p>
                        <p style="margin: 10px 0;"><strong>Email:</strong> ${userEmail}</p>
                        <p style="margin: 10px 0;"><strong>Role:</strong> ${userRole.toUpperCase()}</p>
                        <p style="margin: 10px 0;"><strong>User ID:</strong> ${userId}</p>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function editUser(userId, userName, userEmail, userRole) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background: rgba(0,0,0,0.8); display: flex; align-items: center; 
                justify-content: center; z-index: 1000;
            `;
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; width: 90%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #667eea; margin: 0;">Edit User</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    <form id="edit-user-form" onsubmit="updateUser(event, ${userId})">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Name:</label>
                            <input type="text" id="edit-name" value="${userName}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
                            <input type="email" id="edit-email" value="${userEmail}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Role:</label>
                            <select id="edit-role" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="admin" ${userRole === 'admin' ? 'selected' : ''}>Admin</option>
                                <option value="donor" ${userRole === 'donor' ? 'selected' : ''}>Donor</option>
                                <option value="victim" ${userRole === 'victim' ? 'selected' : ''}>Victim</option>
                            </select>
                        </div>
                        <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Update User</button>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function updateUser(event, userId) {
            event.preventDefault();
            const name = document.getElementById('edit-name').value;
            const email = document.getElementById('edit-email').value;
            const role = document.getElementById('edit-role').value;
            
            const formData = new FormData();
            formData.append('action', 'update_user');
            formData.append('user_id', userId);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('role', role);
            
            fetch('authController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('User updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
        
        function deleteUser(userId, userName) {
            if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
                const formData = new FormData();
                formData.append('action', 'delete_user');
                formData.append('user_id', userId);
                
                fetch('authController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.errors ? data.errors.join(', ') : 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }
        
        // Admin action functions (placeholders for now)
        function manageUsers() {
            // Create user management interface
            const userManagement = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">User Management</h3>
                    <div style="margin-bottom: 20px;">
                        <p>As an admin, you can manage all users in the system. Currently, you can:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>View all registered users in the table below</li>
                            <li>Approve or reject victim applications</li>
                            <li>Monitor user activity and statistics</li>
                            <li>Manage user roles and permissions</li>
                        </ul>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Quick Actions:</h4>
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button onclick="location.reload()" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh User List</button>
                            <button onclick="alert('Advanced user management features coming soon!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Advanced Settings</button>
                        </div>
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
                        <h3 style="color: #007bff; margin: 0;">User Management</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${userManagement}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function manageDonors() {
            // Create donor management interface
            const donorManagement = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Donor Management</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Manage donor accounts and track donation activities:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>View all registered donors and their profiles</li>
                            <li>Monitor donation history and patterns</li>
                            <li>Track total donations and impact metrics</li>
                            <li>Communicate with active donors</li>
                        </ul>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Current Statistics:</h4>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 10px;">
                            <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                                <div style="font-size: 24px; font-weight: bold; color: #007bff;"><?php echo $totalDonors; ?></div>
                                <div style="color: #666;">Total Donors</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                                <div style="font-size: 24px; font-weight: bold; color: #007bff;">KES <?php echo number_format($totalDonations, 0); ?></div>
                                <div style="color: #666;">Total Donated</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                                <div style="font-size: 24px; font-weight: bold; color: #007bff;"><?php echo $totalUsers; ?></div>
                                <div style="color: #666;">All Users</div>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="location.reload()" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh Stats</button>
                        <button onclick="alert('Advanced donor analytics coming soon!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">View Analytics</button>
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
                        <h3 style="color: #007bff; margin: 0;">Donor Management</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${donorManagement}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function manageVictims() {
            // Create victim management interface
            const victimManagement = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Victim Management</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Manage victim applications and provide support:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Review and approve victim applications</li>
                            <li>Monitor victim progress and needs</li>
                            <li>Coordinate support services</li>
                            <li>Track assistance distribution</li>
                        </ul>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Application Status Overview:</h4>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 10px;">
                            <div style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 5px;">
                                <div style="font-size: 24px; font-weight: bold; color: #856404;"><?php echo $pendingVictims; ?></div>
                                <div style="color: #856404;">Pending Applications</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #d4edda; border-radius: 5px;">
                                <div style="font-size: 24px; font-weight: bold; color: #155724;"><?php echo $approvedVictims; ?></div>
                                <div style="color: #155724;">Approved Victims</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #f8d7da; border-radius: 5px;">
                                <div style="font-size: 24px; font-weight: bold; color: #721c24;"><?php echo $rejectedVictims; ?></div>
                                <div style="color: #721c24;">Rejected Applications</div>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="location.reload()" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh Applications</button>
                        <button onclick="alert('Advanced victim analytics coming soon!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">View Analytics</button>
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
                        <h3 style="color: #007bff; margin: 0;">Victim Management</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${victimManagement}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function viewDonations() {
            // Create donation history interface
            const donationHistory = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Donation History</h3>
                    <div style="margin-bottom: 20px;">
                        <p>View and analyze all donation activities in the system:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Track all monetary and in-kind donations</li>
                            <li>Monitor donation trends and patterns</li>
                            <li>View donor-victim matching</li>
                            <li>Generate donation reports</li>
                        </ul>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Recent Activity:</h4>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <p style="color: #666; margin: 0;">Total donations processed: <strong><?php echo $totalDonations; ?></strong></p>
                            <p style="color: #666; margin: 5px 0 0 0;">Active donors this month: <strong><?php echo $totalDonors; ?></strong></p>
                            <p style="color: #666; margin: 5px 0 0 0;">Victims receiving support: <strong><?php echo $approvedVictims; ?></strong></p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="location.reload()" style="background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh Data</button>
                        <button onclick="alert('Detailed donation reports coming soon!')" style="background: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Generate Report</button>
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
        
        function approveVictims() {
            // Create victim approval interface
            const victimApproval = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Victim Approval System</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Review and manage victim applications:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Pending applications: <strong><?php echo $pendingVictims; ?></strong></li>
                            <li>Recently approved: <strong><?php echo $approvedVictims; ?></strong></li>
                            <li>Recently rejected: <strong><?php echo $rejectedVictims; ?></strong></li>
                        </ul>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Quick Actions:</h4>
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button onclick="location.reload()" style="background: #28a745; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Refresh Applications</button>
                            <button onclick="alert('Bulk approval features coming soon!')" style="background: #ffc107; color: #000; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Bulk Actions</button>
                        </div>
                    </div>
                    <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; margin-top: 15px;">
                        <p style="margin: 0; color: #0c5460;"><strong>Note:</strong> You can approve or reject individual applications directly from the main dashboard table above.</p>
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
                        <h3 style="color: #007bff; margin: 0;">Victim Approval</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${victimApproval}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function generateReports() {
            // Create report generation interface
            const reportGeneration = `
                <div style="background: white; padding: 20px; border-radius: 10px; max-width: 800px;">
                    <h3 style="color: #007bff; margin-bottom: 20px;">Report Generation</h3>
                    <div style="margin-bottom: 20px;">
                        <p>Generate comprehensive reports for NawiriKe operations:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>User activity and registration reports</li>
                            <li>Donation analytics and financial summaries</li>
                            <li>Victim assistance impact reports</li>
                            <li>System performance and usage statistics</li>
                        </ul>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #007bff;">Available Reports:</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">
                            <button onclick="alert('User report generation coming soon!')" style="background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">User Summary</button>
                            <button onclick="alert('Donation report coming soon!')" style="background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">Donation Report</button>
                            <button onclick="alert('Victim report coming soon!')" style="background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">Victim Impact</button>
                            <button onclick="alert('System report coming soon!')" style="background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">System Stats</button>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 15px;">
                        <p style="margin: 0; color: #666;"><strong>Current Data:</strong> System contains <?php echo $totalUsers; ?> users, <?php echo $totalDonors; ?> donors, and <?php echo $approvedVictims; ?> approved victims.</p>
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
                        <h3 style="color: #007bff; margin: 0;">Report Generation</h3>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                    </div>
                    ${reportGeneration}
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        // Load stats when page loads
        window.onload = loadStats;
    </script>
</body>
</html>
