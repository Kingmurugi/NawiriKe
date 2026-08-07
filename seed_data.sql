-- ========================================
-- NawiriKe CRM Seed Data
-- ========================================
-- Populates the system with enough activity for every admin report to
-- return at least 20 rows:
--   23 donors, 25 victims, 70 donations, 26 benefit distributions
--
-- USAGE (run nawirike.sql first to create the schema):
--   mysql -u root -p nawirike < seed_data.sql
--
-- This script CLEARS all existing rows before inserting, so the ids below
-- always line up. Do not run it against data you want to keep.
--
-- Every seeded account uses the password: password
-- Admin login: admin@nawirike.org
-- Sample donor login: john.kamau@example.com
-- Sample victim login: mary.wambui@example.com
-- ========================================

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE distributions;
TRUNCATE TABLE donations;
TRUNCATE TABLE donors;
TRUNCATE TABLE victims;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- ========================================
-- USERS (1 admin, 23 donors, 25 victims)
-- ========================================
INSERT INTO users (name, email, password_hash, role, created_at) VALUES
('System Administrator', 'admin@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2024-06-01 08:00:00'),
('John Kamau', 'john.kamau@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-05 09:00:00'),
('Peter Otieno', 'peter.otieno@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-07 09:00:00'),
('Grace Wanjiru', 'grace.wanjiru@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-09 09:00:00'),
('Samuel Mwangi', 'samuel.mwangi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-11 09:00:00'),
('Alice Njeri', 'alice.njeri@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-13 09:00:00'),
('David Kiptoo', 'david.kiptoo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-15 09:00:00'),
('Faith Achieng', 'faith.achieng@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-17 09:00:00'),
('Brian Mutua', 'brian.mutua@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-19 09:00:00'),
('Esther Nyambura', 'esther.nyambura@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-21 09:00:00'),
('Joseph Barasa', 'joseph.barasa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-23 09:00:00'),
('Lydia Chebet', 'lydia.chebet@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-25 09:00:00'),
('Kevin Omondi', 'kevin.omondi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-27 09:00:00'),
('Mercy Wairimu', 'mercy.wairimu@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-06-29 09:00:00'),
('Daniel Kilonzo', 'daniel.kilonzo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-01 09:00:00'),
('Rose Atieno', 'rose.atieno@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-03 09:00:00'),
('Anne Kariuki', 'anne.kariuki@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-05 09:00:00'),
('Felix Wekesa', 'felix.wekesa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-07 09:00:00'),
('Purity Mwende', 'purity.mwende@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-09 09:00:00'),
('George Ndungu', 'george.ndungu@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-11 09:00:00'),
('Sylvia Moraa', 'sylvia.moraa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-13 09:00:00'),
('Elias Kirui', 'elias.kirui@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-15 09:00:00'),
('Naomi Wangui', 'naomi.wangui@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-17 09:00:00'),
('Victor Mulwa', 'victor.mulwa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor', '2024-07-19 09:00:00'),
('Mary Wambui', 'mary.wambui@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-10 10:00:00'),
('Grace Adhiambo', 'grace.adhiambo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-12 10:00:00'),
('Paul Kariuki', 'paul.kariuki@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-14 10:00:00'),
('Janet Muthoni', 'janet.muthoni@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-16 10:00:00'),
('Isaac Wafula', 'isaac.wafula@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-18 10:00:00'),
('Sarah Kerubo', 'sarah.kerubo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-20 10:00:00'),
('Michael Ochieng', 'michael.ochieng@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-22 10:00:00'),
('Beatrice Nduta', 'beatrice.nduta@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-24 10:00:00'),
('Anthony Mureithi', 'anthony.mureithi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-26 10:00:00'),
('Caroline Auma', 'caroline.auma@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-28 10:00:00'),
('Dennis Kimani', 'dennis.kimani@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-06-30 10:00:00'),
('Hellen Chepkoech', 'hellen.chepkoech@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-02 10:00:00'),
('Patrick Njoroge', 'patrick.njoroge@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-04 10:00:00'),
('Susan Kalondu', 'susan.kalondu@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-06 10:00:00'),
('James Mbugua', 'james.mbugua@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-08 10:00:00'),
('Nancy Wangari', 'nancy.wangari@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-10 10:00:00'),
('Elijah Rotich', 'elijah.rotich@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-12 10:00:00'),
('Priscilla Anyango', 'priscilla.anyango@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-14 10:00:00'),
('Simon Maina', 'simon.maina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-16 10:00:00'),
('Agnes Kavata', 'agnes.kavata@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-18 10:00:00'),
('Timothy Ouma', 'timothy.ouma@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-20 10:00:00'),
('Damaris Nasimiyu', 'damaris.nasimiyu@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-22 10:00:00'),
('Charles Gitonga', 'charles.gitonga@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-24 10:00:00'),
('Winnie Jelagat', 'winnie.jelagat@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-26 10:00:00'),
('Robert Onyango', 'robert.onyango@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim', '2024-07-28 10:00:00');

-- ========================================
-- DONORS (23 records)
-- total_donated and donation_count are set from the donations below.
-- ========================================
INSERT INTO donors (user_id, contact) VALUES
(2, '+254711000000'),
(3, '+254712234567'),
(4, '+254713469134'),
(5, '+254714703701'),
(6, '+254715938268'),
(7, '+254717172835'),
(8, '+254718407402'),
(9, '+254719641969'),
(10, '+254720876536'),
(11, '+254722111103'),
(12, '+254723345670'),
(13, '+254724580237'),
(14, '+254725814804'),
(15, '+254727049371'),
(16, '+254728283938'),
(17, '+254729518505'),
(18, '+254730753072'),
(19, '+254731987639'),
(20, '+254733222206'),
(21, '+254734456773'),
(22, '+254735691340'),
(23, '+254736925907'),
(24, '+254738160474');

-- ========================================
-- VICTIMS / APPLICATIONS
-- 18 Approved, 5 Pending, 2 Rejected
-- ========================================
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status, date_registered) VALUES
(25, 'Kibera, Nairobi', 'Lost home in a fire; family of four needs temporary shelter and basic supplies.', 'shelter', 'Approved', '2024-06-15 11:00:00'),
(26, 'Nyalenda, Kisumu', 'Single parent of three retrenched from casual work; unable to buy food.', 'food', 'Approved', '2024-06-17 11:00:00'),
(27, 'Mathare, Nairobi', 'Requires surgery after a road accident and cannot afford the hospital bill.', 'medical', 'Approved', '2024-06-19 11:00:00'),
(28, 'Bangladesh, Mombasa', 'Household displaced by flooding; all clothing and bedding were destroyed.', 'clothing', 'Approved', '2024-06-21 11:00:00'),
(29, 'Kawangware, Nairobi', 'Orphaned teenager needs school fees to continue secondary education.', 'education', 'Approved', '2024-06-23 11:00:00'),
(30, 'Manyatta, Kisumu', 'Widow caring for two grandchildren with no reliable source of income.', 'other', 'Approved', '2024-06-25 11:00:00'),
(31, 'Korogocho, Nairobi', 'Chronic illness requires monthly medication the family cannot sustain.', 'shelter', 'Approved', '2024-06-27 11:00:00'),
(32, 'Kisauni, Mombasa', 'Family evicted after the landlord demolished the rental units.', 'food', 'Approved', '2024-06-29 11:00:00'),
(33, 'Mukuru, Nairobi', 'Casual labourer injured at work and now unable to provide for the family.', 'medical', 'Approved', '2024-07-01 11:00:00'),
(34, 'Langas, Eldoret', 'Household of six sharing one room with no beddings after a house fire.', 'clothing', 'Approved', '2024-07-03 11:00:00'),
(35, 'Bondeni, Nakuru', 'Lost home in a fire; family of four needs temporary shelter and basic supplies.', 'education', 'Approved', '2024-07-05 11:00:00'),
(36, 'Majengo, Mombasa', 'Single parent of three retrenched from casual work; unable to buy food.', 'other', 'Approved', '2024-07-07 11:00:00'),
(37, 'Obunga, Kisumu', 'Requires surgery after a road accident and cannot afford the hospital bill.', 'shelter', 'Approved', '2024-07-09 11:00:00'),
(38, 'Kaptembwo, Nakuru', 'Household displaced by flooding; all clothing and bedding were destroyed.', 'food', 'Approved', '2024-07-11 11:00:00'),
(39, 'Huruma, Nairobi', 'Orphaned teenager needs school fees to continue secondary education.', 'medical', 'Approved', '2024-07-13 11:00:00'),
(40, 'Munyaka, Eldoret', 'Widow caring for two grandchildren with no reliable source of income.', 'clothing', 'Approved', '2024-07-15 11:00:00'),
(41, 'Mwiki, Nairobi', 'Chronic illness requires monthly medication the family cannot sustain.', 'education', 'Approved', '2024-07-17 11:00:00'),
(42, 'Nyawita, Kisumu', 'Family evicted after the landlord demolished the rental units.', 'other', 'Approved', '2024-07-19 11:00:00'),
(43, 'Githurai, Nairobi', 'Casual labourer injured at work and now unable to provide for the family.', 'shelter', 'Pending', '2024-07-21 11:00:00'),
(44, 'Kiandutu, Thika', 'Household of six sharing one room with no beddings after a house fire.', 'food', 'Pending', '2024-07-23 11:00:00'),
(45, 'Shauri Moyo, Nairobi', 'Lost home in a fire; family of four needs temporary shelter and basic supplies.', 'medical', 'Pending', '2024-07-25 11:00:00'),
(46, 'Kambi Somali, Nakuru', 'Single parent of three retrenched from casual work; unable to buy food.', 'clothing', 'Pending', '2024-07-27 11:00:00'),
(47, 'Chaani, Mombasa', 'Requires surgery after a road accident and cannot afford the hospital bill.', 'education', 'Pending', '2024-07-29 11:00:00'),
(48, 'Kipkaren, Eldoret', 'Household displaced by flooding; all clothing and bedding were destroyed.', 'other', 'Rejected', '2024-07-31 11:00:00'),
(49, 'Dandora, Nairobi', 'Orphaned teenager needs school fees to continue secondary education.', 'shelter', 'Rejected', '2024-08-02 11:00:00');

-- ========================================
-- DONATIONS
-- victim_id NULL = general pool (awaiting admin distribution)
-- victim_id set  = direct donation to an approved victim
-- The last few M-Pesa pushes are left 'pending' to exercise the
-- confirmation step; pending money is excluded from all totals.
-- ========================================
INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method, mpesa_phone, mpesa_transaction_id, mpesa_receipt_number, mpesa_status) VALUES
(1, NULL, 13000.00, 'monetary', 'General pool contribution', '2024-08-01 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(2, 2, 6000.00, 'monetary', 'Contribution for food supplies', '2024-09-04 10:00:00', 'completed', 'mpesa', '254717654321', 'MPESA17700036072001', 'REC17700036075001', 'completed'),
(3, 3, 7000.00, 'monetary', 'Help with hospital bill', '2024-10-07 11:00:00', 'completed', 'mpesa', '254725308642', 'MPESA17700072142002', 'REC17700072145002', 'completed'),
(4, NULL, 38000.00, 'monetary', 'General pool contribution', '2024-11-10 12:00:00', 'completed', 'mpesa', '254732962963', 'MPESA17700108212003', 'REC17700108215003', 'completed'),
(5, 5, 4000.00, 'service', 'Blankets and clothing', '2024-12-13 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(6, 6, 7000.00, 'monetary', 'Monthly pledge contribution', '2025-01-16 14:00:00', 'completed', 'mpesa', '254748271605', 'MPESA17700180352005', 'REC17700180355005', 'completed'),
(7, 7, 1000.00, 'monetary', 'Emergency relief contribution', '2025-02-19 15:00:00', 'completed', 'mpesa', '254755925926', 'MPESA17700216422006', 'REC17700216425006', 'completed'),
(8, NULL, 39000.00, 'monetary', 'General pool contribution', '2025-03-22 16:00:00', 'completed', 'mpesa', '254763580247', 'MPESA17700252492007', 'REC17700252495007', 'completed'),
(9, 9, 5000.00, 'in-kind', 'Support towards rent arrears', '2025-04-25 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(10, 10, 5000.00, 'service', 'Contribution for food supplies', '2025-05-02 10:00:00', 'completed', 'mpesa', '254778888889', 'MPESA17700324632009', 'REC17700324635009', 'completed'),
(11, NULL, 19000.00, 'monetary', 'General pool contribution', '2025-06-05 11:00:00', 'completed', 'mpesa', '254786543210', 'MPESA17700360702010', 'REC17700360705010', 'completed'),
(12, 12, 1000.00, 'monetary', 'Back-to-school support', '2025-07-08 12:00:00', 'completed', 'mpesa', '254794197531', 'MPESA17700396772011', 'REC17700396775011', 'completed'),
(13, 13, 1000.00, 'monetary', 'Blankets and clothing', '2025-08-11 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(14, 14, 2000.00, 'in-kind', 'Monthly pledge contribution', '2025-09-14 14:00:00', 'completed', 'mpesa', '254719506174', 'MPESA17700468912013', 'REC17700468915013', 'completed'),
(15, NULL, 27000.00, 'monetary', 'General pool contribution', '2025-10-17 15:00:00', 'completed', 'mpesa', '254727160495', 'MPESA17700504982014', 'REC17700504985014', 'completed'),
(16, 16, 1000.00, 'monetary', 'Community fundraiser proceeds', '2025-11-20 16:00:00', 'completed', 'mpesa', '254734814816', 'MPESA17700541052015', 'REC17700541055015', 'completed'),
(17, 17, 3500.00, 'monetary', 'Support towards rent arrears', '2025-12-23 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(18, NULL, 22000.00, 'monetary', 'General pool contribution', '2026-01-26 10:00:00', 'completed', 'mpesa', '254750123458', 'MPESA17700613192017', 'REC17700613195017', 'completed'),
(19, 1, 6500.00, 'in-kind', 'Help with hospital bill', '2026-02-03 11:00:00', 'completed', 'mpesa', '254757777779', 'MPESA17700649262018', 'REC17700649265018', 'completed'),
(20, 2, 4000.00, 'service', 'Back-to-school support', '2026-03-06 12:00:00', 'completed', 'mpesa', '254765432100', 'MPESA17700685332019', 'REC17700685335019', 'completed'),
(21, 3, 6000.00, 'monetary', 'Blankets and clothing', '2026-04-09 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(22, NULL, 32000.00, 'monetary', 'General pool contribution', '2026-05-12 14:00:00', 'completed', 'mpesa', '254780740742', 'MPESA17700757472021', 'REC17700757475021', 'completed'),
(23, 5, 2000.00, 'monetary', 'Emergency relief contribution', '2026-06-15 15:00:00', 'pending', 'mpesa', '254788395063', 'MPESA17700793542022', 'REC17700793545022', 'pending'),
(1, 6, 3000.00, 'in-kind', 'Community fundraiser proceeds', '2026-07-18 16:00:00', 'pending', 'mpesa', '254796049384', 'MPESA17700829612023', 'REC17700829615023', 'pending'),
(2, NULL, 34000.00, 'monetary', 'General pool contribution', '2024-08-21 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(3, 8, 4000.00, 'monetary', 'Contribution for food supplies', '2024-09-24 10:00:00', 'completed', 'mpesa', '254721358027', 'MPESA17700901752025', 'REC17700901755025', 'completed'),
(4, 9, 6000.00, 'monetary', 'Help with hospital bill', '2024-10-01 11:00:00', 'completed', 'mpesa', '254729012348', 'MPESA17700937822026', 'REC17700937825026', 'completed'),
(5, 10, 2000.00, 'monetary', 'Back-to-school support', '2024-11-04 12:00:00', 'completed', 'mpesa', '254736666669', 'MPESA17700973892027', 'REC17700973895027', 'completed'),
(6, NULL, 29000.00, 'monetary', 'General pool contribution', '2024-12-07 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(7, 12, 1500.00, 'service', 'Monthly pledge contribution', '2025-01-10 14:00:00', 'completed', 'mpesa', '254751975311', 'MPESA17701046032029', 'REC17701046035029', 'completed'),
(8, 13, 1000.00, 'monetary', 'Emergency relief contribution', '2025-02-13 15:00:00', 'completed', 'mpesa', '254759629632', 'MPESA17701082102030', 'REC17701082105030', 'completed'),
(9, NULL, 37000.00, 'monetary', 'General pool contribution', '2025-03-16 16:00:00', 'completed', 'mpesa', '254767283953', 'MPESA17701118172031', 'REC17701118175031', 'completed'),
(10, 15, 4500.00, 'monetary', 'Support towards rent arrears', '2025-04-19 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(11, 16, 3500.00, 'in-kind', 'Contribution for food supplies', '2025-05-22 10:00:00', 'completed', 'mpesa', '254782592595', 'MPESA17701190312033', 'REC17701190315033', 'completed'),
(12, 17, 2500.00, 'service', 'Help with hospital bill', '2025-06-25 11:00:00', 'completed', 'mpesa', '254790246916', 'MPESA17701226382034', 'REC17701226385034', 'completed'),
(13, NULL, 18000.00, 'monetary', 'General pool contribution', '2025-07-02 12:00:00', 'completed', 'mpesa', '254797901237', 'MPESA17701262452035', 'REC17701262455035', 'completed'),
(14, 1, 6500.00, 'monetary', 'Blankets and clothing', '2025-08-05 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(15, 2, 4500.00, 'monetary', 'Monthly pledge contribution', '2025-09-08 14:00:00', 'completed', 'mpesa', '254723209880', 'MPESA17701334592037', 'REC17701334595037', 'completed'),
(16, NULL, 32000.00, 'monetary', 'General pool contribution', '2025-10-11 15:00:00', 'completed', 'mpesa', '254730864201', 'MPESA17701370662038', 'REC17701370665038', 'completed'),
(17, 4, 3000.00, 'service', 'Community fundraiser proceeds', '2025-11-14 16:00:00', 'completed', 'mpesa', '254738518522', 'MPESA17701406732039', 'REC17701406735039', 'completed'),
(18, 5, 6500.00, 'monetary', 'Support towards rent arrears', '2025-12-17 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(19, 6, 5500.00, 'monetary', 'Contribution for food supplies', '2026-01-20 10:00:00', 'completed', 'mpesa', '254753827164', 'MPESA17701478872041', 'REC17701478875041', 'completed'),
(20, NULL, 37000.00, 'monetary', 'General pool contribution', '2026-02-23 11:00:00', 'completed', 'mpesa', '254761481485', 'MPESA17701514942042', 'REC17701514945042', 'completed'),
(21, 8, 4000.00, 'in-kind', 'Back-to-school support', '2026-03-26 12:00:00', 'completed', 'mpesa', '254769135806', 'MPESA17701551012043', 'REC17701551015043', 'completed'),
(22, 9, 7500.00, 'service', 'Blankets and clothing', '2026-04-03 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(23, NULL, 33000.00, 'monetary', 'General pool contribution', '2026-05-06 14:00:00', 'completed', 'mpesa', '254784444448', 'MPESA17701623152045', 'REC17701623155045', 'completed'),
(1, 11, 7500.00, 'monetary', 'Emergency relief contribution', '2026-06-09 15:00:00', 'pending', 'mpesa', '254792098769', 'MPESA17701659222046', 'REC17701659225046', 'pending'),
(2, 12, 6500.00, 'monetary', 'Community fundraiser proceeds', '2026-07-12 16:00:00', 'pending', 'mpesa', '254799753090', 'MPESA17701695292047', 'REC17701695295047', 'pending'),
(3, 13, 5500.00, 'in-kind', 'Support towards rent arrears', '2024-08-15 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(4, NULL, 20000.00, 'monetary', 'General pool contribution', '2024-09-18 10:00:00', 'completed', 'mpesa', '254725061733', 'MPESA17701767432049', 'REC17701767435049', 'completed'),
(5, 15, 6000.00, 'monetary', 'Help with hospital bill', '2024-10-21 11:00:00', 'completed', 'mpesa', '254732716054', 'MPESA17701803502050', 'REC17701803505050', 'completed'),
(6, 16, 5000.00, 'monetary', 'Back-to-school support', '2024-11-24 12:00:00', 'completed', 'mpesa', '254740370375', 'MPESA17701839572051', 'REC17701839575051', 'completed'),
(7, NULL, 33000.00, 'monetary', 'General pool contribution', '2024-12-01 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(8, 18, 5000.00, 'in-kind', 'Monthly pledge contribution', '2025-01-04 14:00:00', 'completed', 'mpesa', '254755679017', 'MPESA17701911712053', 'REC17701911715053', 'completed'),
(9, 1, 5000.00, 'service', 'Emergency relief contribution', '2025-02-07 15:00:00', 'completed', 'mpesa', '254763333338', 'MPESA17701947782054', 'REC17701947785054', 'completed'),
(10, 2, 7000.00, 'monetary', 'Community fundraiser proceeds', '2025-03-10 16:00:00', 'completed', 'mpesa', '254770987659', 'MPESA17701983852055', 'REC17701983855055', 'completed'),
(11, NULL, 19000.00, 'monetary', 'General pool contribution', '2025-04-13 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(12, 4, 3500.00, 'monetary', 'Contribution for food supplies', '2025-05-16 10:00:00', 'completed', 'mpesa', '254786296301', 'MPESA17702055992057', 'REC17702055995057', 'completed'),
(13, 5, 7500.00, 'in-kind', 'Help with hospital bill', '2025-06-19 11:00:00', 'completed', 'mpesa', '254793950622', 'MPESA17702092062058', 'REC17702092065058', 'completed'),
(14, NULL, 14000.00, 'monetary', 'General pool contribution', '2025-07-22 12:00:00', 'completed', 'mpesa', '254711604944', 'MPESA17702128132059', 'REC17702128135059', 'completed'),
(15, 7, 4000.00, 'monetary', 'Blankets and clothing', '2025-08-25 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(16, 8, 2000.00, 'monetary', 'Monthly pledge contribution', '2025-09-02 14:00:00', 'completed', 'mpesa', '254726913586', 'MPESA17702200272061', 'REC17702200275061', 'completed'),
(17, 9, 2500.00, 'monetary', 'Emergency relief contribution', '2025-10-05 15:00:00', 'completed', 'mpesa', '254734567907', 'MPESA17702236342062', 'REC17702236345062', 'completed'),
(18, NULL, 23000.00, 'monetary', 'General pool contribution', '2025-11-08 16:00:00', 'completed', 'mpesa', '254742222228', 'MPESA17702272412063', 'REC17702272415063', 'completed'),
(19, 11, 3500.00, 'service', 'Support towards rent arrears', '2025-12-11 09:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(20, 12, 1000.00, 'monetary', 'Contribution for food supplies', '2026-01-14 10:00:00', 'completed', 'mpesa', '254757530870', 'MPESA17702344552065', 'REC17702344555065', 'completed'),
(21, NULL, 30000.00, 'monetary', 'General pool contribution', '2026-02-17 11:00:00', 'completed', 'mpesa', '254765185191', 'MPESA17702380622066', 'REC17702380625066', 'completed'),
(22, 14, 3500.00, 'monetary', 'Back-to-school support', '2026-03-20 12:00:00', 'completed', 'mpesa', '254772839512', 'MPESA17702416692067', 'REC17702416695067', 'completed'),
(23, 15, 7500.00, 'in-kind', 'Blankets and clothing', '2026-04-23 13:00:00', 'completed', 'cash', NULL, NULL, NULL, NULL),
(1, 16, 1500.00, 'service', 'Monthly pledge contribution', '2026-05-26 14:00:00', 'completed', 'mpesa', '254788148154', 'MPESA17702488832069', 'REC17702488835069', 'completed');

-- ========================================
-- DISTRIBUTIONS (BENEFITS PAID FROM THE GENERAL POOL)
-- distributed_by = 1 (the seeded administrator)
-- ========================================
INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes) VALUES
(1, 1, 7000.00, 1, '2024-08-06 09:00:00', 'Rent paid directly to the landlord for two months'),
(4, 2, 3000.00, 1, '2024-11-16 12:00:00', 'Food hamper and cooking essentials delivered'),
(8, 3, 8000.00, 1, '2025-03-29 16:00:00', 'Hospital bill settled at the district facility'),
(11, 4, 11000.00, 1, '2025-06-13 11:00:00', 'School fees paid for term two'),
(15, 5, 7000.00, 1, '2025-10-26 15:00:00', 'Mattress, blankets and clothing purchased'),
(18, 6, 6000.00, 1, '2026-02-05 10:00:00', 'Iron sheets provided for shelter repair'),
(22, 7, 11000.00, 1, '2026-05-23 14:00:00', 'Medication for three months collected'),
(25, 8, 8000.00, 1, '2024-09-02 09:00:00', 'Transport and relocation assistance provided'),
(29, 9, 9000.00, 1, '2024-12-20 13:00:00', 'Water tank and sanitation supplies provided'),
(32, 10, 6000.00, 1, '2025-03-30 16:00:00', 'Start-up stock for a small vegetable kiosk'),
(36, 11, 4000.00, 1, '2025-07-17 12:00:00', 'Rent paid directly to the landlord for two months'),
(39, 12, 6000.00, 1, '2025-10-27 15:00:00', 'Food hamper and cooking essentials delivered'),
(43, 13, 4000.00, 1, '2026-03-12 11:00:00', 'Hospital bill settled at the district facility'),
(46, 14, 4000.00, 1, '2026-05-24 14:00:00', 'School fees paid for term two'),
(50, 15, 5000.00, 1, '2024-10-07 10:00:00', 'Mattress, blankets and clothing purchased'),
(53, 16, 8000.00, 1, '2024-12-21 13:00:00', 'Iron sheets provided for shelter repair'),
(57, 17, 11000.00, 1, '2025-05-04 09:00:00', 'Medication for three months collected'),
(60, 18, 3000.00, 1, '2025-08-13 12:00:00', 'Transport and relocation assistance provided'),
(64, 1, 8000.00, 1, '2025-12-01 16:00:00', 'Water tank and sanitation supplies provided'),
(67, 2, 7000.00, 1, '2026-03-13 11:00:00', 'Start-up stock for a small vegetable kiosk'),
(1, 3, 3000.00, 1, '2024-08-06 09:00:00', 'Rent paid directly to the landlord for two months'),
(4, 4, 4000.00, 1, '2024-11-16 12:00:00', 'Food hamper and cooking essentials delivered'),
(8, 5, 9000.00, 1, '2025-03-29 16:00:00', 'Hospital bill settled at the district facility'),
(15, 6, 11000.00, 1, '2025-10-25 15:00:00', 'School fees paid for term two'),
(18, 7, 8000.00, 1, '2026-02-04 10:00:00', 'Mattress, blankets and clothing purchased'),
(22, 8, 7000.00, 1, '2026-05-22 14:00:00', 'Iron sheets provided for shelter repair');

-- ========================================
-- DONOR RUNNING TOTALS
-- Recomputed from completed donations so the cached columns on donors
-- match the donations table exactly.
-- ========================================
UPDATE donors d SET
    total_donated = (SELECT COALESCE(SUM(amount), 0) FROM donations WHERE donor_id = d.donor_id AND status = 'completed'),
    donation_count = (SELECT COUNT(*) FROM donations WHERE donor_id = d.donor_id AND status = 'completed');

