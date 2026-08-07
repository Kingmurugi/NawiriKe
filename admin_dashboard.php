<?php

require_once 'authController.php';

requireAdmin();

$currentUser = getCurrentUser();

try {
    // Total users count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $totalUsers = $stmt->fetch()['total'];
    
    // Total donors count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM donors d JOIN users u ON d.user_id = u.user_id");
    $stmt->execute();
    $totalDonors = $stmt->fetch()['total'];
    
    // Total victims count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM victims v JOIN users u ON v.user_id = u.user_id");
    $stmt->execute();
    $totalVictims = $stmt->fetch()['total'];
    
    // Total donations sum
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM donations");
    $stmt->execute();
    $totalDonations = $stmt->fetch()['total'];
    
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
    $approvedVictims = $stmt->fetchAll();
    
    // Get pending victims for approval
    $stmt = $conn->prepare("
        SELECT v.victim_id, u.name, u.email, v.location, v.verification_status, v.date_registered 
        FROM victims v 
        JOIN users u ON v.user_id = u.user_id 
        WHERE v.verification_status = 'Pending' 
        ORDER BY v.date_registered DESC
    ");
    $stmt->execute();
    $pendingVictims = $stmt->fetchAll();
    
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
    $allUsers = $stmt->fetchAll();
    
} catch(PDOException $e) {
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
        
        .admin-actions {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .admin-actions h2 {
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
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
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
        
        <!-- All Users -->
        <div class="admin-actions">
            <h2>All Users</h2>
            <?php if (empty($allUsers)): ?>
                <p style="color: #666;">No users found in the system.</p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Name</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Email</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Role</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Additional Info</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;"><?php echo htmlspecialchars($user['name']); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td style="padding: 12px;">
                                    <span style="background: <?php 
                                        echo $user['role'] === 'admin' ? '#dc3545' : 
                                            ($user['role'] === 'donor' ? '#28a745' : '#ffc107'); 
                                    ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                                        <?php echo strtoupper($user['role']); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    <?php 
                                    if ($user['role'] === 'donor') {
                                        echo 'Donated: KES ' . number_format($user['additional_info'], 2);
                                    } elseif ($user['role'] === 'victim') {
                                        echo 'Status: ' . htmlspecialchars($user['additional_info']);
                                    } else {
                                        echo 'Administrator';
                                    }
                                    ?>
                                </td>
                                <td style="padding: 12px;"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
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
