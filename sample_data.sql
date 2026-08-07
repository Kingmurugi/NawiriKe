-- NawiriKe CRM Sample Data
-- Insert sample records for testing the system

-- Note: Passwords are hashed using password_hash() in PHP
-- For testing, you can use these plain passwords and hash them in your PHP code

-- Insert sample users (passwords will be hashed in PHP)
INSERT INTO users (firstname, email, contact, password, role) VALUES
('Admin', 'admin@nawirike.org', '+254700000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John', 'john.donor@email.com', '+254711111111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Mary', 'mary.victim@email.com', '+254722222222', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Peter', 'peter.donor@email.com', '+254733333333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Grace', 'grace.victim@email.com', '+254744444444', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim');

-- Insert sample donors
INSERT INTO donors (user_id, first_name, last_name, contact_number, email, organization_name, donor_type, address, city, country, postal_code, preferred_donation_method) VALUES
(2, 'John', 'Mwangi', '+254711111111', 'john.donor@email.com', NULL, 'individual', '123 Nairobi Road', 'Nairobi', 'Kenya', '00100', 'bank_transfer'),
(4, 'Peter', 'Kamau', '+254733333333', 'peter.donor@email.com', 'Helping Hands Foundation', 'organization', '456 Mombasa Road', 'Mombasa', 'Kenya', '80100', 'mobile_money');

-- Insert sample victims
INSERT INTO victims (user_id, first_name, last_name, contact_number, email, id_number, date_of_birth, gender, address, emergency_contact_name, emergency_contact_phone, case_status, case_description, assistance_required, assigned_social_worker) VALUES
(3, 'Mary', 'Wanjiru', '+254722222222', 'mary.victim@email.com', '12345678', '1990-05-15', 'female', '789 Thika Road, Nairobi', 'James Wanjiru', '+254755555555', 'active', 'Lost home due to fire, needs temporary shelter and basic supplies', 'Shelter, food, clothing', 'Social Worker Jane'),
(5, 'Grace', 'Atieno', '+254744444444', 'grace.victim@email.com', '87654321', '1985-08-22', 'female', '321 Kisumu Road, Kisumu', 'Robert Atieno', '+254766666666', 'pending', 'Medical emergency requiring hospitalization funds', 'Medical expenses, medication', 'Social Worker John');

-- Insert sample donations
INSERT INTO donations (donor_id, victim_id, donation_date, amount, currency, donation_type, donation_method, transaction_id, description, purpose, status, receipt_number) VALUES
(1, 1, '2024-01-15', 5000.00, 'KES', 'monetary', 'bank_transfer', 'TRX001', 'Monthly donation for victim support', 'Emergency shelter', 'completed', 'RCP001'),
(2, 1, '2024-01-20', 10000.00, 'KES', 'monetary', 'mobile_money', 'MPESA002', 'Donation for medical expenses', 'Medical treatment', 'completed', 'RCP002'),
(1, 2, '2024-01-25', 3000.00, 'KES', 'monetary', 'bank_transfer', 'TRX003', 'Food and supplies donation', 'Basic necessities', 'completed', 'RCP003'),
(2, NULL, '2024-02-01', 15000.00, 'KES', 'monetary', 'mobile_money', 'MPESA004', 'General fund donation', 'General operations', 'completed', 'RCP004');

-- Insert sample in-kind donation items
INSERT INTO donation_items (donation_id, item_name, item_category, quantity, unit_value, total_value, condition_status, description) VALUES
(4, 'Rice Bags', 'Food', 10, 500.00, 5000.00, 'good', '10kg bags of rice for food distribution'),
(4, 'Blankets', 'Clothing', 20, 800.00, 16000.00, 'new', 'Warm blankets for shelter residents'),
(4, 'Medical Supplies', 'Medical', 5, 2000.00, 10000.00, 'new', 'First aid kits and basic medical supplies');

-- Insert sample audit log entries
INSERT INTO audit_log (user_id, action, table_name, record_id, new_values, ip_address, user_agent) VALUES
(1, 'CREATE', 'users', 1, '{"firstname": "Admin", "email": "admin@nawirike.org", "role": "admin"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(2, 'CREATE', 'donations', 1, '{"amount": 5000.00, "donor_id": 1, "victim_id": 1}', '192.168.1.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'),
(1, 'UPDATE', 'victims', 1, '{"case_status": "active"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

-- Update donor total donations
UPDATE donors SET total_donations = (
    SELECT COALESCE(SUM(amount), 0) FROM donations WHERE donor_id = donors.id
), last_donation_date = (
    SELECT MAX(donation_date) FROM donations WHERE donor_id = donors.id
);

-- Note: For the sample data above, the password 'password' was hashed as:
-- $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- You can use this hash for testing, or hash new passwords using PHP's password_hash() function
