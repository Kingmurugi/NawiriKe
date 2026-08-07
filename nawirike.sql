-- ========================================
-- NawiriKe CRM Complete Database Schema
-- Final-Year Computer Science Project
-- ========================================
-- Database: nawirike
-- Purpose: Victim and Donor Management System with Role-Based Authentication
-- 
-- INSTRUCTIONS:
-- 1. Drop existing database if needed: DROP DATABASE IF EXISTS nawirike;
-- 2. Create database: CREATE DATABASE nawirike;
-- 3. Use database: USE nawirike;
-- 4. Run this entire script
-- ========================================

-- ========================================
-- 1. USERS TABLE (CORE LOGIN SYSTEM)
-- ========================================
-- This table stores all system users and handles authentication
-- Used for login and role-based redirection

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'victim') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Indexes for performance
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 2. VICTIMS TABLE (APPLICATION DATA)
-- ========================================
-- Stores victim applications and verification status
-- Victims register and apply for help through this table

CREATE TABLE victims (
    victim_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    location VARCHAR(255) NOT NULL,
    vulnerability_description TEXT NOT NULL,
    urgent_needs ENUM('shelter', 'food', 'medical', 'clothing', 'education', 'other') DEFAULT 'other',
    verification_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign key relationship to users table
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_user_id (user_id),
    INDEX idx_verification_status (verification_status),
    INDEX idx_urgent_needs (urgent_needs),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 3. DONORS TABLE (OPTIONAL EXTENSION)
-- ========================================
-- Stores additional donor information beyond basic user data
-- Donors can make donations and track their contributions

CREATE TABLE donors (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    contact VARCHAR(20) NOT NULL,
    total_donated DECIMAL(15, 2) DEFAULT 0.00,
    donation_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign key relationship to users table
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_user_id (user_id),
    INDEX idx_total_donated (total_donated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 4. DONATIONS TABLE
-- ========================================
-- Tracks all donations made by donors
-- Supports both direct donations (donor → victim) and general pool donations
-- Direct donations are automatically approved (no admin intervention needed)
-- General pool donations require admin distribution to specific victims

CREATE TABLE donations (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NULL,
    victim_id INT NULL COMMENT 'NULL for general pool donations, set for direct donations',
    amount DECIMAL(15, 2) NOT NULL,
    donation_type ENUM('monetary', 'in-kind', 'service') DEFAULT 'monetary',
    description TEXT,
    donated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    payment_method ENUM('cash', 'mpesa') DEFAULT 'cash',
    mpesa_phone VARCHAR(20) NULL,
    mpesa_transaction_id VARCHAR(50) NULL,
    mpesa_receipt_number VARCHAR(50) NULL,
    mpesa_status ENUM('pending', 'completed', 'failed') NULL,
    
    -- Foreign key relationships
    FOREIGN KEY (donor_id) REFERENCES donors(donor_id) ON DELETE RESTRICT,
    FOREIGN KEY (victim_id) REFERENCES victims(victim_id) ON DELETE RESTRICT,
    
    -- Indexes for performance
    INDEX idx_donor_id (donor_id),
    INDEX idx_victim_id (victim_id),
    INDEX idx_donated_at (donated_at),
    INDEX idx_amount (amount),
    INDEX idx_payment_method (payment_method),
    INDEX idx_mpesa_transaction (mpesa_transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 5. DISTRIBUTIONS TABLE
-- ========================================
-- Tracks distribution of general pool funds to specific victims
-- Only admins can create distribution records for general pool donations
-- Links general pool donations (victim_id = NULL) to specific victims
-- Direct donations (victim_id set) do not use this table

CREATE TABLE distributions (
    distribution_id INT AUTO_INCREMENT PRIMARY KEY,
    donation_id INT NOT NULL,
    victim_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    distributed_by INT NOT NULL COMMENT 'Admin user_id who distributed the funds',
    distribution_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    
    -- Foreign key relationships
    FOREIGN KEY (donation_id) REFERENCES donations(donation_id) ON DELETE RESTRICT,
    FOREIGN KEY (victim_id) REFERENCES victims(victim_id) ON DELETE RESTRICT,
    FOREIGN KEY (distributed_by) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_donation_id (donation_id),
    INDEX idx_victim_id (victim_id),
    INDEX idx_distributed_by (distributed_by),
    INDEX idx_distribution_date (distribution_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 6. SAMPLE DATA INSERTION
-- ========================================
-- Insert sample users for testing the system
-- Password: 'password' (hashed using PHP's password_hash())

INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Donor', 'john.donor@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Mary Victim', 'mary.victim@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Peter Donor', 'peter.donor@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Grace Victim', 'grace.victim@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim');

-- Insert sample donors
INSERT INTO donors (user_id, contact, total_donated, donation_count) VALUES
(2, '+254711111111', 15000.00, 3),
(4, '+254733333333', 8000.00, 2);

-- Insert sample victims with applications
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status) VALUES
(3, 'Nairobi, Kenya', 'Lost home due to fire. Family of 4 needs temporary shelter and basic supplies for recovery.', 'shelter', 'Approved'),
(5, 'Mombasa, Kenya', 'Medical emergency requiring surgery. Unable to afford hospital bills and medication costs.', 'medical', 'Pending');

-- Insert sample donations
-- donor_id references donors.donor_id (1 and 2 from donors table)
-- victim_id references victims.victim_id (1 and 2 from victims table)
-- victim_id = NULL indicates general pool donations (require admin distribution)
-- victim_id set indicates direct donations (automatically approved)
INSERT INTO donations (donor_id, victim_id, amount, donation_type, description) VALUES
(1, 1, 10000.00, 'monetary', 'Direct donation to Mary Victim'),  -- Direct donation
(2, 1, 5000.00, 'monetary', 'Direct donation to Mary Victim'),   -- Direct donation
(1, 2, 3000.00, 'monetary', 'Direct donation to Grace Victim'),   -- Direct donation
(2, NULL, 5000.00, 'monetary', 'General pool donation for distribution'),  -- General pool
(1, NULL, 7500.00, 'monetary', 'General pool donation for distribution');  -- General pool

-- Insert sample distributions
-- Admin distributes funds from general pool to specific victims
-- donation_id references donations.donation_id (4 and 5 are general pool donations)
-- victim_id references victims.victim_id (1 and 2 from victims table)
-- distributed_by references users.user_id (1 = Admin User)
INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, notes) VALUES
(4, 1, 5000.00, 1, 'Distribution from general pool to Mary Victim for shelter needs'),
(5, 2, 7500.00, 1, 'Distribution from general pool to Grace Victim for medical needs');

-- ========================================
-- 7. VERIFICATION QUERIES
-- ========================================
-- Check if data was inserted correctly

-- ========================================
-- 8. LOGIN EXAMPLE QUERIES
-- ========================================
-- Example query for user authentication
/*
SELECT user_id, name, email, role 
FROM users 
WHERE email = 'user@example.com' 
AND password_hash = 'hashed_password';
*/

-- Example query for role-based redirection
/*
-- After successful login, redirect based on role:
-- admin -> admin dashboard
-- donor -> donor dashboard  
-- victim -> victim dashboard
*/

-- ========================================
-- 9. SAMPLE PHP LOGIN REDIRECTION
-- ========================================
/*
// After successful login authentication:
switch($user['role']) {
    case 'admin':
        header('Location: admin_dashboard.php');
        break;
    case 'donor':
        header('Location: donor_dashboard.php');
        break;
    case 'victim':
        header('Location: victim_dashboard.php');
        break;
}
*/

-- ========================================
-- SCHEMA SUMMARY
-- ========================================
/*
This schema provides:
1. Centralized user authentication with role-based access
2. Victim application and verification system
3. Donor management and tracking
4. Complete donation tracking between donors and victims
5. Proper foreign key relationships for data integrity
6. Sample data for testing all user roles

The system supports:
- Admin login to manage and approve victim applications
- Donor login to make and track donations
- Victim login to apply for help and check status
- Role-based redirection after login
- General pool donations (victim_id = NULL)
- M-Pesa payment tracking
- Admin distribution of general pool funds
*/

-- ========================================
-- TESTING INFORMATION
-- ========================================
/*
TEST LOGIN CREDENTIALS:
=======================
Admin User:
- Email: admin@nawirike.org
- Password: password
- Role: admin

Donor User:
- Email: john.donor@nawirike.org
- Password: password
- Role: donor

Victim User:
- Email: mary.victim@nawirike.org
- Password: password
- Role: victim

NOTES:
======
1. The password hash above is for "password"
2. Use PHP's password_hash() for new passwords
3. Execute this entire script in MySQL Workbench
4. Make sure you're connected to the 'nawirike' database
5. This schema is fully compatible with authController.php
*/
