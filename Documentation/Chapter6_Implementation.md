# CHAPTER 6: IMPLEMENTATION

## 6.1 Chapter Introduction

This chapter describes the implementation of the NawiriKe Community Support Management System, translating the design specifications from the previous chapter into a working system. The chapter covers user interface implementation, database implementation, process design implementation, and module testing. The implementation demonstrates how the system meets the specified requirements and functions correctly in real-world scenarios.

## 6.2 User Interface Implementation

User interface implementation involved creating the front-end components that users interact with, following the design specifications established in Chapter 5. The interfaces were implemented using HTML5, CSS3, and JavaScript, with emphasis on user experience, responsiveness, and visual appeal.

**Landing Page Implementation:**

The landing page (index.php) was implemented with a modern, professional design featuring:
- Gradient background with animated floating elements for visual appeal
- Fixed navigation bar with logo and menu items (Home, About Us, How It Works, Success Stories, Contact Us, Login, Sign Up)
- Hero section with compelling headline and call-to-action buttons
- Features section highlighting key platform benefits
- Statistics section showing impact metrics
- Responsive design that adapts to various screen sizes
- Professional typography and color scheme consistent with brand identity

The landing page serves as the primary entry point for users, providing information about the platform and directing users to registration or login based on their needs.

**Login Page Implementation:**

The login page (login.html) was implemented with:
- Centered card-based layout with glassmorphism effect
- Email and password input fields with appropriate validation
- Client-side validation using JavaScript for immediate feedback
- Secure form submission to authentication endpoint
- Link to registration page for new users
- Link back to landing page
- Error message display area for authentication failures
- Responsive design for mobile compatibility

The login interface provides a secure and user-friendly authentication experience, with clear visual feedback and intuitive navigation.

**Registration Page Implementation:**

The registration page (signup.html) was implemented with:
- Dynamic form fields based on selected role (donor, victim, admin)
- Contact number field for donors (required for M-Pesa integration)
- Admin code field for administrators (verification requirement)
- Password and confirm password fields with matching validation
- Client-side validation for all required fields
- Secure form submission to registration endpoint
- Link to login page for existing users
- Link back to landing page
- Error message display area for registration failures
- Scrollable design to accommodate all form fields on smaller screens
- Responsive design for mobile compatibility

The registration interface provides a streamlined onboarding experience with role-specific fields and comprehensive validation.

**Dashboard Implementation:**

Three role-specific dashboards were implemented:

**Donor Dashboard (donor_dashboard.php):**
- Personal donation history with filtering and sorting
- Donation statistics (total donated, number of donations)
- Available victims for direct donation
- Donation submission form
- Real-time donation tracking
- Responsive design with clear information hierarchy

**Victim Dashboard (victim_dashboard.php):**
- Personal information display
- Application status tracking
- Received donations history
- Distribution notifications
- Responsive design optimized for readability

**Admin Dashboard (admin_dashboard.php):**
- Comprehensive system statistics (total users, donors, victims, donations, distributions)
- General pool statistics (total pool, total distributed, available funds)
- Victim application management (approve/reject functionality)
- Fund distribution interface
- Report generation with dropdown selection
- Recent donations and distributions lists
- Top donors ranking
- Responsive design with tabbed interface to reduce scrolling

All dashboards implement role-based access control, ensuring users only see functionality appropriate to their role.

## 6.3 Database Implementation

Database implementation involved creating the MySQL database and tables according to the schema defined in Chapter 5, establishing relationships, and implementing constraints to ensure data integrity.

**Database Creation:**

The database named "nawirike" was created using MySQL. The database connection is established through the Database class in database.php, which uses MySQLi for secure database interactions.

**Table Creation:**

Tables were created according to the data dictionary specifications:

**Users Table:**
```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'victim') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Donors Table:**
```sql
CREATE TABLE donors (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    total_donated DECIMAL(10,2) DEFAULT 0.00,
    donation_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```

**Victims Table:**
```sql
CREATE TABLE victims (
    victim_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    need_description TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```

**Donations Table:**
```sql
CREATE TABLE donations (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    victim_id INT,
    amount DECIMAL(10,2) NOT NULL,
    donation_type ENUM('direct', 'general') NOT NULL,
    description TEXT,
    donated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    payment_method ENUM('cash', 'mpesa') NOT NULL,
    mpesa_phone VARCHAR(20),
    FOREIGN KEY (donor_id) REFERENCES donors(donor_id),
    FOREIGN KEY (victim_id) REFERENCES victims(victim_id)
);
```

**Distributions Table:**
```sql
CREATE TABLE distributions (
    distribution_id INT AUTO_INCREMENT PRIMARY KEY,
    victim_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    distributed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (victim_id) REFERENCES victims(victim_id)
);
```

**Relationships and Constraints:**

Foreign key constraints were established to enforce referential integrity. The relationships ensure that:
- Each donor record is linked to a valid user
- Each victim record is linked to a valid user
- Each donation is linked to a valid donor
- Each donation to a victim is linked to a valid victim
- Each distribution is linked to a valid victim

The database implementation follows the normalized schema designed in Chapter 5, ensuring data integrity and eliminating redundancy.

## 6.4 Process Design Implementation

Process design implementation involved translating the flowchart logic documented in Chapter 5 into executable PHP code. Key processes were implemented with appropriate error handling, validation, and security measures.

**User Registration Process:**

The registration process is implemented in authController.php through the handleRegistration function:
1. Receives registration data from signup.html form
2. Validates input data (email format, password strength, required fields)
3. Checks if email already exists in database
4. Hashes password using bcrypt for security
5. Inserts user record into users table
6. Based on role, inserts additional record into donors or victims table
7. Returns success or error response in JSON format

**User Authentication Process:**

The authentication process is implemented in authController.php through the handleLogin function:
1. Receives login credentials from login.html form
2. Validates input data
3. Queries database for user with matching email
4. Verifies password hash using bcrypt
5. If authentication successful, creates session
6. Redirects to appropriate dashboard based on user role
7. If authentication fails, returns error message

**Donation Process:**

The donation process is implemented in authController.php through the handleDonation function:
1. Receives donation data from donor dashboard form
2. Validates input data (amount, recipient, payment method)
3. Processes payment through selected method (cash or M-Pesa)
4. Inserts donation record into donations table
5. Updates donor total_donated and donation_count
6. If general pool donation, updates general pool statistics
7. Returns success or error response

**Victim Application Process:**

The victim application process is implemented in authController.php through the handleVictimApplication function:
1. Receives application data from victim dashboard form
2. Validates input data
3. Inserts victim record into victims table with status 'pending'
4. Returns success or error response

**Application Approval Process:**

The application approval process is implemented in authController.php through the handleVictimApplicationUpdate function:
1. Receives approval decision from admin dashboard
2. Updates victim status to 'approved' or 'rejected'
3. Returns success or error response

**Fund Distribution Process:**

The fund distribution process is implemented in authController.php through the distributeGeneralFund function:
1. Receives distribution data from admin dashboard form
2. Validates sufficient funds are available in general pool
3. Inserts distribution record into distributions table
4. Updates general pool statistics
5. Returns success or error response

**Security Implementation:**

Security measures were implemented throughout:
- All database queries use prepared statements to prevent SQL injection
- Passwords are hashed using bcrypt before storage
- Session management maintains authentication state
- Input validation prevents malicious data submission
- Role-based access control restricts functionality based on user role

## 6.5 Module Testing

Module testing was conducted to ensure each system component functions correctly according to specifications. Testing covered user interface, database operations, and business logic.

**Table 6.1: Test Cases for Login Module**

| Test Case | Description | Expected Result | Actual Result | Status |
|-----------|-------------|-----------------|---------------|--------|
| TC-LM-01 | Valid credentials | Successful login, redirect to dashboard | Successful login, redirect to dashboard | Pass |
| TC-LM-02 | Invalid email | Error message "Invalid credentials" | Error message displayed | Pass |
| TC-LM-03 | Invalid password | Error message "Invalid credentials" | Error message displayed | Pass |
| TC-LM-04 | Empty fields | Validation error "All fields required" | Validation error displayed | Pass |
| TC-LM-05 | Non-existent user | Error message "Invalid credentials" | Error message displayed | Pass |

**Table 6.2: Test Cases for Donation Module**

| Test Case | Description | Expected Result | Actual Result | Status |
|-----------|-------------|-----------------|---------------|--------|
| TC-DM-01 | Valid direct donation | Donation recorded, donor updated | Donation recorded, donor updated | Pass |
| TC-DM-02 | Valid general pool donation | Donation recorded, pool updated | Donation recorded, pool updated | Pass |
| TC-DM-03 | Invalid amount | Validation error "Invalid amount" | Validation error displayed | Pass |
| TC-DM-04 | Insufficient funds (M-Pesa) | Error "Payment failed" | Error displayed | Pass |
| TC-DM-05 | Empty required fields | Validation error "All fields required" | Validation error displayed | Pass |

**User Interface Testing:**

User interface testing verified:
- All pages render correctly in various browsers (Chrome, Firefox, Safari, Edge)
- Responsive design functions correctly on different screen sizes (desktop, tablet, mobile)
- Form validation provides appropriate feedback
- Navigation links function correctly
- Error messages display clearly
- Loading states provide user feedback

**Database Testing:**

Database testing verified:
- All tables created successfully with correct structure
- Foreign key constraints enforce referential integrity
- Data insertion and retrieval functions correctly
- Queries return expected results
- Transactions maintain data consistency
- Backup and recovery procedures function correctly

**Integration Testing:**

Integration testing verified:
- User interface correctly submits data to backend
- Backend correctly processes requests and returns responses
- Database operations integrate correctly with business logic
- Session management maintains authentication state
- Role-based access control functions correctly
- Error handling works across system layers

## 6.6 Chapter Summary

This chapter described the implementation of the NawiriKe Community Support Management System. User interface implementation included landing page, login page, registration page, and role-specific dashboards with emphasis on user experience and responsiveness. Database implementation involved creating MySQL tables according to the designed schema with appropriate relationships and constraints.

Process design implementation translated flowchart logic into executable PHP code for key processes including user registration, authentication, donation processing, victim application management, and fund distribution. Security measures including prepared statements, password hashing, session management, and role-based access control were implemented throughout.

Module testing covered login and donation modules with comprehensive test cases, all of which passed successfully. User interface, database, and integration testing verified correct functionality across system components.

The implementation successfully translates the design specifications into a working system that meets the requirements identified in previous chapters. The system provides a comprehensive solution for community support management with enhanced transparency, efficiency, and accountability compared to the manual system.

The project report concludes with references and appendices containing supplementary materials to support the research and implementation.
