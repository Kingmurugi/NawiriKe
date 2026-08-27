# REFERENCES

## References/Bibliography

Anderson, R., & Lee, S. (2021). User experience design principles for nonprofit donation platforms. *Journal of Digital Philanthropy, 8*(2), 45-62.

Brown, T., Garcia, M., & Patel, N. (2021). Data-driven decision making in humanitarian organizations. *International Journal of Nonprofit Management, 15*(3), 112-129.

Central Bank of Kenya. (2023). *Mobile money statistics report*. Central Bank of Kenya Publications.

Chen, L., Wang, H., & Zhang, Y. (2018). Crowdfunding platforms: A comprehensive analysis of success factors. *Electronic Commerce Research, 18*(4), 789-815.

Charity Navigator. (2023). *Annual report 2023*. Charity Navigator.

DonorsChoose. (2023). *Impact report 2023*. DonorsChoose.org.

GiveDirectly. (2023). *Annual impact report*. GiveDirectly.

GlobalGiving. (2023). *Annual report 2023*. GlobalGiving.

GoFundMe. (2023). *Company overview and statistics*. GoFundMe.

Jack, W., & Suri, T. (2011). Mobile money: The economics of M-PESA. *NBER Working Paper Series, 16721*.

Johnson, M., & Smith, R. (2022). Digital transformation in charitable organizations: Efficiency and transparency gains. *Nonprofit Management Review, 34*(1), 89-105.

Kiva. (2023). *Annual impact report*. Kiva Microfunds.

Kenya Red Cross. (2023). *Annual report 2023*. Kenya Red Cross Society.

M-Changa. (2023). *Platform statistics and impact report*. M-Changa.

Mutua, J., & Kimani, P. (2022). Mobile-first design approaches for African contexts. *African Journal of Information Technology, 9*(1), 23-38.

Safaricom Foundation. (2023). *Corporate social responsibility report*. Safaricom PLC.

Smith, A., & Johnson, B. (2022). Technology adoption in nonprofit organizations. *Journal of Technology for Social Good, 6*(2), 78-95.

Thompson, K., & Davis, L. (2020). CRM systems for nonprofit organizations: A comprehensive review. *Nonprofit Quarterly, 25*(4), 56-71.

Williams, J. (2005). Traditional approaches to community support management. *Community Development Journal, 40*(3), 245-258.

Williams, R. (2023). Multi-channel payment integration for donation platforms. *Fintech for Good, 12*(1), 33-48.

---

# APPENDICES

## Appendix A: Sample Questionnaires

### User Requirements Questionnaire

**Section 1: Demographic Information**

1. Age: _______
2. Gender: _______
3. Occupation: _______
4. Location: _______

**Section 2: Current Donation Practices**

5. How often do you make donations to community support initiatives?
   - Monthly
   - Quarterly
   - Annually
   - Occasionally
   - Never

6. What methods do you typically use to make donations?
   - Cash
   - Mobile money (M-Pesa)
   - Bank transfer
   - Other: _______

7. What motivates you to donate?
   - Personal connection to cause
   - Tax benefits
   - Social recognition
   - Religious beliefs
   - Other: _______

**Section 3: Platform Preferences**

8. How important is real-time tracking of your donations?
   - Very important
   - Somewhat important
   - Not important
   - Not sure

9. Would you prefer to donate to specific individuals or to a general fund?
   - Specific individuals
   - General fund
   - Both options
   - Not sure

10. What features would you like to see in a community support platform?
    - Real-time donation tracking
    - Direct donor-to-victim connection
    - Mobile money integration
    - Impact reports
    - Social sharing
    - Other: _______

---

## Appendix B: Sample Interviews

### Interview with Community Organization Administrator

**Interviewer:** What are the main challenges you face in managing community support initiatives?

**Administrator:** The biggest challenge is transparency. Donors want to know how their money is being used, but with our manual system, it's difficult to provide detailed tracking. We also struggle with record keeping - paper records get lost or damaged, and it's hard to analyze trends or measure impact.

**Interviewer:** How do you currently process donations and distribute funds?

**Administrator:** We collect donations during community meetings, mostly in cash. We record these in a ledger, but it's not very detailed. For distributions, we have meetings where we give cash to approved individuals. The process is time-consuming and not very systematic.

**Interviewer:** What would you like to see in a digital system?

**Administrator:** I'd want real-time tracking so donors can see exactly where their money goes. I'd also want better record keeping and reporting capabilities. It would be great to have a systematic way to review applications and make fair decisions about who receives support.

---

### Interview with Donor

**Interviewer:** What would make you more likely to donate to community support initiatives?

**Donor:** Transparency is key. I want to know that my donation is actually helping people and not being misused. If I could track my donation and see the impact, I'd be more willing to give. Convenience is also important - being able to donate through M-Pesa would make it much easier.

**Interviewer:** Would you prefer to donate to specific individuals or to a general fund?

**Donor:** I think both options are good. Sometimes I want to help a specific person with a specific need, but other times I trust the organization to distribute funds where they're most needed. Having both options would be ideal.

---

## Appendix C: Budget Schedule

### Project Budget Estimate

| Item | Description | Estimated Cost (KES) |
|------|-------------|---------------------|
| Development | System development time | 150,000 |
| Hosting | Web hosting for 1 year | 25,000 |
| Domain | Domain registration | 2,000 |
| Testing | Testing and quality assurance | 30,000 |
| Documentation | Documentation and training materials | 20,000 |
| Contingency | Unexpected expenses (10%) | 22,700 |
| **Total** | | **249,700** |

---

## Appendix D: Time Schedule

### Project Timeline

| Phase | Duration | Start Date | End Date |
|-------|----------|------------|----------|
| Requirement Specification | 3 weeks | Week 1 | Week 3 |
| System Analysis | 3 weeks | Week 4 | Week 6 |
| System Design | 4 weeks | Week 7 | Week 10 |
| Implementation | 6 weeks | Week 11 | Week 16 |
| Testing | 3 weeks | Week 17 | Week 19 |
| Documentation | 2 weeks | Week 20 | Week 21 |
| Deployment | 1 week | Week 22 | Week 22 |
| **Total** | **22 weeks** | | |

---

## Appendix E: Sample Code

### Database Connection Code (database.php)

```php
<?php
/**
 * Database Connection Class
 * Handles MySQL database connections using MySQLi
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'nawirike';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name
            );
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            return $this->conn;
            
        } catch(Exception $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            return null;
        }
    }
}
?>
```

### User Registration Code (authController.php)

```php
<?php
/**
 * Handle User Registration
 * Processes user registration with role-specific fields
 */

function handleRegistration($conn, $data) {
    try {
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'errors' => ['Email already exists']];
        }
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password_hash, $role);
        $result = $stmt->execute();
        
        if ($result) {
            $user_id = $conn->insert_id;
            
            // Create role-specific record
            if ($role === 'donor') {
                $stmt = $conn->prepare("INSERT INTO donors (user_id, firstname, contact) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $data['firstname'], $data['contact']);
                $stmt->execute();
            } elseif ($role === 'victim') {
                $stmt = $conn->prepare("INSERT INTO victims (user_id, firstname, location, need_description) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $data['firstname'], $data['location'], $data['need_description']);
                $stmt->execute();
            }
            
            return ['success' => true, 'message' => 'Registration successful'];
        } else {
            return ['success' => false, 'errors' => ['Registration failed']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}
?>
```

### Donation Processing Code (authController.php)

```php
<?php
/**
 * Handle Donation Processing
 * Processes donations with support for direct and general pool donations
 */

function handleDonation($conn, $donorId, $victimId, $amount, $donationType, $description, $paymentMethod = 'cash', $mpesaPhone = null) {
    try {
        // Insert new donation
        $stmt = $conn->prepare("
            INSERT INTO donations (donor_id, victim_id, amount, donation_type, description, donated_at, status, payment_method, mpesa_phone) 
            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'completed', ?, ?)
        ");
        $stmt->bind_param("iidssss", $donorId, $victimId, $amount, $donationType, $description, $paymentMethod, $mpesaPhone);
        $result = $stmt->execute();
        
        if ($result) {
            // Update donor total donations
            $stmt = $conn->prepare("UPDATE donors SET total_donated = total_donated + ?, donation_count = donation_count + 1 WHERE donor_id = ?");
            $stmt->bind_param("di", $amount, $donorId);
            $stmt->execute();
            
            $message = ($victimId) 
                ? 'Donation processed successfully!' 
                : 'Donation added to general fund successfully!';
            
            return ['success' => true, 'message' => $message];
        } else {
            return ['success' => false, 'errors' => ['Failed to process donation']];
        }
        
    } catch(Exception $e) {
        return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
    }
}
?>
```

---

## Appendix F: System Screenshots

### Figure 6.1: Login Interface
[Description: Professional login page with gradient background, centered card layout, email and password fields, and login button]

### Figure 6.2: Registration Interface
[Description: Registration form with dynamic fields based on role selection, including contact field for donors and admin code field for administrators]

### Figure 6.3: Donor Dashboard
[Description: Donor dashboard showing donation history, statistics, available victims for donation, and donation submission form]

### Figure 6.4: Admin Dashboard
[Description: Admin dashboard with comprehensive statistics, tabbed interface for different reports, victim application management, and fund distribution interface]

---

## Appendix G: User Manual

### Getting Started Guide

**For Donors:**

1. Visit the platform landing page
2. Click "Sign Up" to create an account
3. Select "Donor" as your role
4. Fill in your personal information and contact number
5. Submit the registration form
6. Log in using your email and password
7. Access your dashboard to view donation history and make new donations

**For Victims:**

1. Visit the platform landing page
2. Click "Sign Up" to create an account
3. Select "Victim" as your role
4. Fill in your personal information and need description
5. Submit the registration form
6. Log in using your email and password
7. Access your dashboard to view application status and received distributions

**For Administrators:**

1. Contact the system administrator to obtain an admin code
2. Visit the platform landing page
3. Click "Sign Up" to create an account
4. Select "Admin" as your role
5. Enter the admin verification code
6. Fill in your personal information
7. Submit the registration form
8. Log in using your email and password
9. Access the admin dashboard to manage the system

---

**END OF DOCUMENTATION**
