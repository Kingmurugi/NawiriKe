-- NawiriKe CRM Comprehensive Sample Data
-- Populates the database with 20+ donors, victims, donations, and benefits for testing reports

-- ========================================
-- CLEAR EXISTING SAMPLE DATA
-- ========================================
-- Delete existing sample data to avoid duplicates
DELETE FROM distributions WHERE distribution_id > 5;
DELETE FROM donations WHERE donation_id > 5;
DELETE FROM victims WHERE victim_id > 5;
DELETE FROM donors WHERE donor_id > 5;
DELETE FROM users WHERE user_id > 5 AND role IN ('donor', 'victim');

-- ========================================
-- INSERT 25 DONORS
-- ========================================
-- First, insert 25 donor users
INSERT INTO users (name, email, password_hash, role) VALUES
('James Kamau', 'james.kamau@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Mary Wanjiku', 'mary.wanjiku@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Peter Ochieng', 'peter.ochieng@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Grace Akinyi', 'grace.akinyi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('David Mwangi', 'david.mwangi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Sarah Njeri', 'sarah.njeri@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('John Kipchoge', 'john.kipchoge@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Lucy Chebet', 'lucy.chebet@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Michael Otieno', 'michael.otieno@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Hannah Wambui', 'hannah.wambui@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Robert Kiplimo', 'robert.kiplimo@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Emily Nyambura', 'emily.nyambura@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Samuel Kimani', 'samuel.kimani@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Ruth Muthoni', 'ruth.muthoni@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Daniel Kipkorir', 'daniel.kipkorir@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Mercy Jepchumba', 'mercy.jepchumba@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Anthony Mutua', 'anthony.mutua@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Beatrice Naliaka', 'beatrice.naliaka@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Francis Maina', 'francis.maina@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Lydia Mumbi', 'lydia.mumbi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Christopher Wanyonyi', 'christopher.wanyonyi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Purity Chepkoech', 'purity.chepkoech@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Stephen Ngugi', 'stephen.ngugi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Ann Wairimu', 'ann.wairimu@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor'),
('Benard Kipkoech', 'benard.kipkoech@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donor');

-- Insert donor profiles using email to match users
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111111', 0, 0 FROM users WHERE email = 'james.kamau@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111112', 0, 0 FROM users WHERE email = 'mary.wanjiku@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111113', 0, 0 FROM users WHERE email = 'peter.ochieng@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111114', 0, 0 FROM users WHERE email = 'grace.akinyi@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111115', 0, 0 FROM users WHERE email = 'david.mwangi@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111116', 0, 0 FROM users WHERE email = 'sarah.njeri@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111117', 0, 0 FROM users WHERE email = 'john.kipchoge@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111118', 0, 0 FROM users WHERE email = 'lucy.chebet@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111119', 0, 0 FROM users WHERE email = 'michael.otieno@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111120', 0, 0 FROM users WHERE email = 'hannah.wambui@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111121', 0, 0 FROM users WHERE email = 'robert.kiplimo@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111122', 0, 0 FROM users WHERE email = 'emily.nyambura@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111123', 0, 0 FROM users WHERE email = 'samuel.kimani@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111124', 0, 0 FROM users WHERE email = 'ruth.muthoni@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111125', 0, 0 FROM users WHERE email = 'daniel.kipkorir@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111126', 0, 0 FROM users WHERE email = 'mercy.jepchumba@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111127', 0, 0 FROM users WHERE email = 'anthony.mutua@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111128', 0, 0 FROM users WHERE email = 'beatrice.naliaka@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111129', 0, 0 FROM users WHERE email = 'francis.maina@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111130', 0, 0 FROM users WHERE email = 'lydia.mumbi@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111131', 0, 0 FROM users WHERE email = 'christopher.wanyonyi@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111132', 0, 0 FROM users WHERE email = 'purity.chepkoech@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111133', 0, 0 FROM users WHERE email = 'stephen.ngugi@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111134', 0, 0 FROM users WHERE email = 'ann.wairimu@nawirike.org';
INSERT INTO donors (user_id, contact, total_donated, donation_count)
SELECT user_id, '+254711111135', 0, 0 FROM users WHERE email = 'benard.kipkoech@nawirike.org';

-- ========================================
-- INSERT 25 VICTIMS
-- ========================================
-- Insert 25 victim users
INSERT INTO users (name, email, password_hash, role) VALUES
('Alice Muthoni', 'alice.muthoni@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Beatrice Adhiambo', 'beatrice.adhiambo@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Charles Njoroge', 'charles.njoroge@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Dorcas Nyanchama', 'dorcas.nyanchama@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Elijah Kiprono', 'elijah.kiprono@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Florence Wanjiru', 'florence.wanjiru@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('George Omondi', 'george.omondi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Hellen Chepkemoi', 'hellen.chepkemoi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Isaac Muriuki', 'isaac.muriuki@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Jackline Akinyi', 'jackline.akinyi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Kevin Kipkemboi', 'kevin.kipkemboi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Lilian Njoki', 'lilian.njoki@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Moses Ochieng', 'moses.ochieng@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Nancy Chebet', 'nancy.chebet@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Oliver Kipkoech', 'oliver.kipkoech@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Peninah Wanjiku', 'peninah.wanjiku@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Quincy Otieno', 'quincy.otieno@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Rachael Nyamira', 'rachael.nyamira@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Simon Kipruto', 'simon.kipruto@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Teresia Mumbi', 'teresia.mumbi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Ulysses Kiplagat', 'ulysses.kiplagat@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Victoria Wambui', 'victoria.wambui@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('William Njenga', 'william.njenga@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Xavier Omondi', 'xavier.omondi@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim'),
('Yvonne Chepngeno', 'yvonne.chepngeno@nawirike.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'victim');

-- Insert victim profiles using email to match users
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Mathare', 'Lost home due to fire. Family of 5 needs temporary shelter and basic supplies for recovery.', 'shelter', 'Approved' FROM users WHERE email = 'alice.muthoni@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Mombasa, Old Town', 'Medical emergency requiring surgery. Unable to afford hospital bills and medication costs.', 'medical', 'Approved' FROM users WHERE email = 'beatrice.adhiambo@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Kisumu, Nyalenda', 'Flood victim. Lost all household items and crops. Needs food assistance and shelter.', 'food', 'Pending' FROM users WHERE email = 'charles.njoroge@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Eldoret, Langas', 'Single mother with 3 children. Lost job and unable to pay rent or buy food.', 'food', 'Approved' FROM users WHERE email = 'dorcas.nyanchama@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nakuru, Rhonda', 'Elderly person living alone. Needs medication and food support.', 'medical', 'Pending' FROM users WHERE email = 'elijah.kiprono@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Kibera', 'Domestic violence survivor. Needs shelter, counseling, and basic supplies.', 'shelter', 'Approved' FROM users WHERE email = 'florence.wanjiru@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Garissa, Bulla Iftin', 'Drought-affected family. Lost livestock and needs food and water support.', 'food', 'Pending' FROM users WHERE email = 'george.omondi@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Mombasa, Likoni', 'Street children rehabilitation program needs educational materials and clothing.', 'education', 'Approved' FROM users WHERE email = 'hellen.chepkemoi@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Kayole', 'Disabled person needs wheelchair and medical support.', 'medical', 'Pending' FROM users WHERE email = 'isaac.muriuki@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Kisumu, Manyatta', 'Orphaned children living with grandmother. Needs school fees and food support.', 'education', 'Approved' FROM users WHERE email = 'jackline.akinyi@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Eldoret, Huruma', 'Fire accident victim with severe burns needs medical treatment and supplies.', 'medical', 'Pending' FROM users WHERE email = 'kevin.kipkemboi@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Kawangware', 'Family evicted from rental house. Needs temporary shelter and household items.', 'shelter', 'Approved' FROM users WHERE email = 'lilian.njoki@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Mombasa, Changamwe', 'Fishing community affected by bad weather. Lost fishing equipment.', 'other', 'Pending' FROM users WHERE email = 'moses.ochieng@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nakuru, Naivasha', 'Farm worker lost job during COVID. Needs food and rent support.', 'food', 'Approved' FROM users WHERE email = 'nancy.chebet@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Dandora', 'Youth with disability needs vocational training and support.', 'education', 'Pending' FROM users WHERE email = 'oliver.kipkoech@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Kisumu, Obunga', 'Widow with 6 children. Unable to provide basic needs after husband\'s death.', 'food', 'Approved' FROM users WHERE email = 'peninah.wanjiku@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Eldoret, Kimumu', 'Accident victim needs prosthetic limb and rehabilitation support.', 'medical', 'Pending' FROM users WHERE email = 'quincy.otieno@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Mombasa, Nyali', 'Elderly couple needs home care and medication support.', 'medical', 'Approved' FROM users WHERE email = 'rachael.nyamira@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Eastleigh', 'Refugee family needs integration support and basic supplies.', 'shelter', 'Pending' FROM users WHERE email = 'simon.kipruto@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nakuru, Kabarak', 'Child with special needs needs therapy and educational support.', 'education', 'Approved' FROM users WHERE email = 'teresia.mumbi@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Kisumu, Kondele', 'Small business owner lost shop to fire. Needs capital to restart.', 'other', 'Pending' FROM users WHERE email = 'ulysses.kiplagat@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Eldoret, Kapsoya', 'Family affected by landslides. Lost home and belongings.', 'shelter', 'Approved' FROM users WHERE email = 'victoria.wambui@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Mombasa, Malindi', 'Fishermen affected by ocean pollution. Lost livelihood.', 'other', 'Pending' FROM users WHERE email = 'william.njenga@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nairobi, Ruiru', 'Teenage mother needs baby supplies and support to continue education.', 'education', 'Approved' FROM users WHERE email = 'xavier.omondi@nawirike.org';
INSERT INTO victims (user_id, location, vulnerability_description, urgent_needs, verification_status)
SELECT user_id, 'Nakuru, Gilgil', 'Retired teacher with no pension. Needs food and medical support.', 'medical', 'Pending' FROM users WHERE email = 'yvonne.chepngeno@nawirike.org';

-- ========================================
-- INSERT 50 DONATIONS
-- ========================================
-- Mix of direct donations (with victim_id) and general pool donations (victim_id = NULL)
-- Using email-based lookups for donor_id and victim_id

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 5000.00, 'monetary', 'Direct donation for shelter needs', '2024-01-15 10:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'james.kamau@nawirike.org' AND uv.email = 'alice.muthoni@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 10000.00, 'monetary', 'General pool donation', '2024-01-16 14:20:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'james.kamau@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 7500.00, 'monetary', 'Medical expenses support', '2024-01-17 09:15:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'mary.wanjiku@nawirike.org' AND uv.email = 'beatrice.adhiambo@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 5000.00, 'monetary', 'General fund contribution', '2024-01-18 11:45:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'mary.wanjiku@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 3000.00, 'monetary', 'Food assistance', '2024-01-19 16:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'peter.ochieng@nawirike.org' AND uv.email = 'charles.njoroge@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 4000.00, 'monetary', 'Family support', '2024-01-20 08:00:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'peter.ochieng@nawirike.org' AND uv.email = 'dorcas.nyanchama@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 15000.00, 'monetary', 'Large general pool donation', '2024-01-21 13:15:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'grace.akinyi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 2500.00, 'monetary', 'Elderly support', '2024-01-22 10:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'grace.akinyi@nawirike.org' AND uv.email = 'elijah.kiprono@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 8000.00, 'monetary', 'Shelter and counseling', '2024-01-23 15:45:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'david.mwangi@nawirike.org' AND uv.email = 'florence.wanjiru@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 3000.00, 'monetary', 'General contribution', '2024-01-24 09:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'david.mwangi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 6000.00, 'monetary', 'Drought relief', '2024-01-25 14:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'sarah.njeri@nawirike.org' AND uv.email = 'george.omondi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 7000.00, 'monetary', 'Educational support', '2024-01-26 11:20:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'sarah.njeri@nawirike.org' AND uv.email = 'hellen.chepkemoi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 12000.00, 'monetary', 'General pool donation', '2024-01-27 16:45:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'john.kipchoge@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 9000.00, 'monetary', 'Disability support', '2024-01-28 08:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'john.kipchoge@nawirike.org' AND uv.email = 'isaac.muriuki@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 4500.00, 'monetary', 'Orphan support', '2024-01-29 13:00:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'lucy.chebet@nawirike.org' AND uv.email = 'jackline.akinyi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 5500.00, 'monetary', 'General fund', '2024-01-30 10:15:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'lucy.chebet@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 10000.00, 'monetary', 'Burn treatment support', '2024-01-31 15:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'michael.otieno@nawirike.org' AND uv.email = 'kevin.kipkemboi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 3500.00, 'monetary', 'Eviction support', '2024-02-01 09:00:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'michael.otieno@nawirike.org' AND uv.email = 'lilian.njoki@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 8000.00, 'monetary', 'General pool donation', '2024-02-02 14:20:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'hannah.wambui@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 4000.00, 'monetary', 'Fishing equipment', '2024-02-03 11:45:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'hannah.wambui@nawirike.org' AND uv.email = 'moses.ochieng@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 6500.00, 'monetary', 'Job loss support', '2024-02-04 16:00:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'robert.kiplimo@nawirike.org' AND uv.email = 'nancy.chebet@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 7000.00, 'monetary', 'General contribution', '2024-02-05 08:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'robert.kiplimo@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 8500.00, 'monetary', 'Vocational training', '2024-02-06 13:15:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'emily.nyambura@nawirike.org' AND uv.email = 'oliver.kipkoech@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 5500.00, 'monetary', 'Widow support', '2024-02-07 10:00:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'emily.nyambura@nawirike.org' AND uv.email = 'peninah.wanjiku@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 11000.00, 'monetary', 'Large general donation', '2024-02-08 15:45:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'samuel.kimani@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 9500.00, 'monetary', 'Prosthetic support', '2024-02-09 09:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'samuel.kimani@nawirike.org' AND uv.email = 'quincy.otieno@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 6000.00, 'monetary', 'Elderly care', '2024-02-10 14:15:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'ruth.muthoni@nawirike.org' AND uv.email = 'rachael.nyamira@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 4000.00, 'monetary', 'General fund', '2024-02-11 11:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'ruth.muthoni@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 7500.00, 'monetary', 'Refugee support', '2024-02-12 16:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'daniel.kipkorir@nawirike.org' AND uv.email = 'simon.kipruto@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 5000.00, 'monetary', 'Special needs child', '2024-02-13 08:45:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'daniel.kipkorir@nawirike.org' AND uv.email = 'teresia.mumbi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 9000.00, 'monetary', 'General pool donation', '2024-02-14 13:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'mercy.jepchumba@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 6500.00, 'monetary', 'Business restart', '2024-02-15 10:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'mercy.jepchumba@nawirike.org' AND uv.email = 'ulysses.kiplagat@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 8000.00, 'monetary', 'Landslide relief', '2024-02-16 15:15:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'anthony.mutua@nawirike.org' AND uv.email = 'victoria.wambui@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 5500.00, 'monetary', 'General contribution', '2024-02-17 09:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'anthony.mutua@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 7000.00, 'monetary', 'Livelihood support', '2024-02-18 14:45:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'beatrice.naliaka@nawirike.org' AND uv.email = 'william.njenga@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 4500.00, 'monetary', 'Teen mother support', '2024-02-19 11:30:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'beatrice.naliaka@nawirike.org' AND uv.email = 'xavier.omondi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 10000.00, 'monetary', 'General pool donation', '2024-02-20 16:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'francis.maina@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 6000.00, 'monetary', 'Retired teacher support', '2024-02-21 08:15:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'francis.maina@nawirike.org' AND uv.email = 'yvonne.chepngeno@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 3000.00, 'in-kind', 'Food supplies and household items', '2024-02-22 13:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'lydia.mumbi@nawirike.org' AND uv.email = 'alice.muthoni@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 4000.00, 'monetary', 'General fund', '2024-02-23 10:00:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'lydia.mumbi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 5000.00, 'in-kind', 'Medical supplies and equipment', '2024-02-24 15:45:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'christopher.wanyonyi@nawirike.org' AND uv.email = 'beatrice.adhiambo@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 3500.00, 'monetary', 'Additional food support', '2024-02-25 09:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'christopher.wanyonyi@nawirike.org' AND uv.email = 'charles.njoroge@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 8500.00, 'monetary', 'General pool donation', '2024-02-26 14:15:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'purity.chepkoech@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 4500.00, 'monetary', 'Continued family support', '2024-02-27 11:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'purity.chepkoech@nawirike.org' AND uv.email = 'dorcas.nyanchama@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 6000.00, 'monetary', 'Medication support', '2024-02-28 16:30:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'stephen.ngugi@nawirike.org' AND uv.email = 'elijah.kiprono@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 7000.00, 'monetary', 'General contribution', '2024-02-29 08:45:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'stephen.ngugi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 9000.00, 'monetary', 'Counseling and shelter', '2024-03-01 13:15:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'ann.wairimu@nawirike.org' AND uv.email = 'florence.wanjiru@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 5500.00, 'monetary', 'Additional drought relief', '2024-03-02 10:00:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'ann.wairimu@nawirike.org' AND uv.email = 'george.omondi@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, NULL, 12000.00, 'monetary', 'Large general donation', '2024-03-03 15:30:00', 'completed', 'mpesa' 
FROM donors d JOIN users u ON d.user_id = u.user_id WHERE u.email = 'benard.kipkoech@nawirike.org';

INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method)
SELECT d.donor_id, v.victim_id, 8000.00, 'monetary', 'More educational materials', '2024-03-04 09:15:00', 'completed', 'cash' 
FROM donors d JOIN users u ON d.user_id = u.user_id JOIN victims v JOIN users uv ON v.user_id = uv.user_id 
WHERE u.email = 'benard.kipkoech@nawirike.org' AND uv.email = 'hellen.chepkemoi@nawirike.org';

-- ========================================
-- UPDATE DONOR TOTALS
-- ========================================
-- Recalculate donor totals based on actual donations
UPDATE donors d SET 
    total_donated = (SELECT COALESCE(SUM(amount), 0) FROM donations WHERE donor_id = d.donor_id),
    donation_count = (SELECT COUNT(*) FROM donations WHERE donor_id = d.donor_id);

-- ========================================
-- INSERT DISTRIBUTIONS (for general pool donations)
-- ========================================
-- Admin distributes some general pool funds to specific victims
-- Using email-based lookups for victim_id

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 5000.00, 1, '2024-01-20 10:00:00', 'Flood relief support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 2 AND u.email = 'charles.njoroge@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 3000.00, 1, '2024-01-25 14:00:00', 'Elderly medical support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 4 AND u.email = 'elijah.kiprono@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 3000.00, 1, '2024-01-28 09:00:00', 'Additional food support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 5 AND u.email = 'charles.njoroge@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 8000.00, 1, '2024-02-02 11:00:00', 'Drought relief from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 7 AND u.email = 'george.omondi@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 3000.00, 1, '2024-02-05 15:00:00', 'Additional drought support'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 10 AND u.email = 'george.omondi@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 6000.00, 1, '2024-02-08 10:00:00', 'Disability equipment from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 13 AND u.email = 'isaac.muriuki@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 5500.00, 1, '2024-02-12 14:00:00', 'Orphan education support'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 14 AND u.email = 'jackline.akinyi@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 7000.00, 1, '2024-02-15 09:00:00', 'Burn treatment from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 16 AND u.email = 'kevin.kipkemboi@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 3500.00, 1, '2024-02-18 16:00:00', 'Eviction relief from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 17 AND u.email = 'lilian.njoki@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 4000.00, 1, '2024-02-22 11:00:00', 'Fishing equipment from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 20 AND u.email = 'moses.ochieng@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 7000.00, 1, '2024-02-25 14:00:00', 'Job loss support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 22 AND u.email = 'nancy.chebet@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 8500.00, 1, '2024-03-01 09:00:00', 'Vocational training from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 26 AND u.email = 'oliver.kipkoech@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 5500.00, 1, '2024-03-04 15:00:00', 'Widow support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 28 AND u.email = 'peninah.wanjiku@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 9500.00, 1, '2024-03-07 10:00:00', 'Prosthetic support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 30 AND u.email = 'quincy.otieno@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 4000.00, 1, '2024-03-10 14:00:00', 'Elderly care from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 32 AND u.email = 'rachael.nyamira@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 7500.00, 1, '2024-03-13 09:00:00', 'Refugee support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 34 AND u.email = 'simon.kipruto@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 5000.00, 1, '2024-03-16 16:00:00', 'Special needs support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 36 AND u.email = 'teresia.mumbi@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 6500.00, 1, '2024-03-19 11:00:00', 'Business restart from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 38 AND u.email = 'ulysses.kiplagat@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 8000.00, 1, '2024-03-22 14:00:00', 'Landslide relief from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 40 AND u.email = 'victoria.wambui@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 7000.00, 1, '2024-03-25 09:00:00', 'Livelihood support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 42 AND u.email = 'william.njenga@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 4500.00, 1, '2024-03-28 16:00:00', 'Teen mother support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 44 AND u.email = 'xavier.omondi@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 6000.00, 1, '2024-03-31 11:00:00', 'Retired teacher support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 46 AND u.email = 'yvonne.chepngeno@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 4000.00, 1, '2024-04-03 14:00:00', 'Additional shelter support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 48 AND u.email = 'alice.muthoni@nawirike.org';

INSERT INTO distributions (donation_id, victim_id, amount, distributed_by, distribution_date, notes)
SELECT donation_id, v.victim_id, 5000.00, 1, '2024-04-06 09:00:00', 'Medical support from general pool'
FROM donations JOIN victims v JOIN users u ON v.user_id = u.user_id 
WHERE donation_id = 50 AND u.email = 'beatrice.adhiambo@nawirike.org';

-- ========================================
-- VERIFICATION QUERIES
-- ========================================
-- Check data counts
-- SELECT COUNT(*) as donor_count FROM users WHERE role = 'donor';
-- SELECT COUNT(*) as victim_count FROM users WHERE role = 'victim';
-- SELECT COUNT(*) as donation_count FROM donations;
-- SELECT COUNT(*) as distribution_count FROM distributions;
-- SELECT COUNT(*) as approved_victims FROM victims WHERE verification_status = 'Approved';
