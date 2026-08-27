<?php
/**
 * How It Works Page - NawiriKe CRM
 * Explains the platform's functionality
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works - NawiriKe CRM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s ease;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100px;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 15px;
            align-items: center;
        }
        
        .nav-link {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .nav-link:hover {
            color: #667eea;
            background: rgba(102,126,234,0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 20px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
        
        .page-content {
            padding: 150px 40px 80px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .page-header h1 {
            font-size: 56px;
            font-weight: 900;
            color: white;
            margin-bottom: 20px;
        }
        
        .page-header p {
            font-size: 20px;
            color: rgba(255,255,255,0.9);
        }
        
        .steps-container {
            display: grid;
            gap: 30px;
        }
        
        .step-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .step-card h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 15px;
            color: #333;
        }
        
        .step-card p {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
        }
        
        .footer {
            background: #1a1a2e;
            color: white;
            padding: 60px 40px 30px;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            text-align: center;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">NK</div>
                NawiriKe
            </a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="about.php" class="nav-link">About Us</a></li>
                <li><a href="how-it-works.php" class="nav-link">How It Works</a></li>
                <li><a href="success-stories.php" class="nav-link">Success Stories</a></li>
                <li><a href="contact.php" class="nav-link">Contact Us</a></li>
                <li><a href="login.html" class="btn-primary">Login</a></li>
                <li><a href="signup.html" class="btn-primary">Sign Up</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="page-content">
        <div class="page-header">
            <h1>How It Works</h1>
            <p>Simple steps to make a difference in your community</p>
        </div>
        
        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <h2>Create Your Account</h2>
                <p>Sign up as a donor, victim, or admin. Complete your profile with necessary information to get started on our platform.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <h2>Connect & Donate</h2>
                <p>Browse verified individuals in need or contribute to our general fund. Make secure donations that directly impact lives.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <h2>Track Your Impact</h2>
                <p>Monitor your donations in real-time. See how your contributions are being used and the difference you're making.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">4</div>
                <h2>Build Community</h2>
                <p>Join a network of compassionate individuals working together to create lasting change in communities across Kenya.</p>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="about.php">About Us</a>
                <a href="how-it-works.php">How It Works</a>
                <a href="success-stories.php">Success Stories</a>
                <a href="contact.php">Contact Us</a>
                <a href="login.html">Login</a>
                <a href="signup.html">Sign Up</a>
            </div>
            <p>&copy; 2024 NawiriKe CRM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
