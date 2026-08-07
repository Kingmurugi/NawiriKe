<?php
/**
 * Quick Admin Account Creator
 * This file creates an admin account instantly without requiring the admin code
 */

// Include database connection
require_once 'database.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account - NawiriKe CRM</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .submit-btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create Admin Account</h1>
            <p>Quick setup for system administrator</p>
        </div>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            // Validation
            if (empty($name)) {
                $errors[] = "Name is required";
            }
            
            if (empty($email)) {
                $errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }
            
            if (empty($password)) {
                $errors[] = "Password is required";
            } elseif (strlen($password) < 8) {
                $errors[] = "Password must be at least 8 characters long";
            }
            
            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match";
            }
            
            // Check if email already exists
            if (empty($errors)) {
                try {
                    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetch()) {
                        $errors[] = "Email already registered";
                    }
                } catch(PDOException $e) {
                    $errors[] = "Database error: " . $e->getMessage();
                }
            }
            
            // Create admin account if no errors
            if (empty($errors)) {
                try {
                    // Hash password
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert admin user
                    $stmt = $conn->prepare("
                        INSERT INTO users (name, email, password_hash, role, created_at) 
                        VALUES (?, ?, ?, 'admin', NOW())
                    ");
                    $stmt->execute([$name, $email, $password_hash]);
                    
                    echo "<div class='message success'>";
                    echo "<strong>Success!</strong> Admin account created successfully.";
                    echo "<br><br>You can now login with your email and password.";
                    echo "<br><br><a href='login.html' style='color: #007bff; font-weight: bold;'>Go to Login</a>";
                    echo "</div>";
                    
                } catch(PDOException $e) {
                    echo "<div class='message error'>";
                    echo "<strong>Error:</strong> Failed to create admin account. " . $e->getMessage();
                    echo "</div>";
                }
            } else {
                echo "<div class='message error'>";
                echo "<strong>Please fix the following errors:</strong><br>";
                foreach ($errors as $error) {
                    echo " &bull; " . htmlspecialchars($error) . "<br>";
                }
                echo "</div>";
            }
        }
        ?>
        
        <div class="message info">
            <strong>Quick Admin Setup</strong><br>
            This form creates an admin account instantly without requiring an admin code. Use this for initial system setup.
        </div>
        
        <form method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       minlength="8" placeholder="Minimum 8 characters">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       minlength="8" placeholder="Re-enter password">
            </div>
            
            <button type="submit" class="submit-btn">Create Admin Account</button>
        </form>
        
        <div class="back-link">
            <a href="login.html">Back to Login</a> | 
            <a href="signup.html">Regular User Signup</a>
        </div>
    </div>
</body>
</html>
