<?php
/**
 * Home Page - NawiriKe CRM
 * Professional landing page with modern design
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NawiriKe CRM - Empowering Communities Through Support</title>
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
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
        
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 40px 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }
        
        .hero-content h1 {
            font-size: 56px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 25px;
            color: white;
        }
        
        .hero-content p {
            font-size: 20px;
            line-height: 1.8;
            color: rgba(255,255,255,0.9);
            margin-bottom: 40px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
        }
        
        .hero-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transform: perspective(1000px) rotateY(-5deg);
        }
        
        .hero-card h3 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 15px;
            color: #667eea;
        }
        
        .features {
            padding: 100px 40px;
            background: white;
        }
        
        .features-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-header h2 {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 20px;
            color: #333;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 15px;
            color: #333;
        }
        
        .stats {
            padding: 100px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stats-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        
        .stat-card {
            text-align: center;
            padding: 35px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 2px solid rgba(255,255,255,0.2);
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: 900;
            color: white;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 16px;
            color: rgba(255,255,255,0.9);
            font-weight: 600;
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
        
        @media (max-width: 1024px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .hero-card {
                transform: none;
            }
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .hero h1 {
                font-size: 36px;
            }
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
    
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Transform Lives Through Collective Support</h1>
                <p>Join our innovative platform connecting donors with those in need. Every contribution creates lasting impact and builds stronger communities.</p>
                <div class="hero-buttons">
                    <a href="signup.html" class="btn-primary">Get Started</a>
                </div>
            </div>
            <div class="hero-card">
                <h3>Make a Difference Today</h3>
                <p>Your support can change lives. Join thousands of donors making real impact in communities across Kenya.</p>
            </div>
        </div>
    </section>
    
    <section class="features" id="features">
        <div class="features-container">
            <div class="section-header">
                <h2>Why Choose NawiriKe?</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">💝</div>
                    <h3>Direct Donations</h3>
                    <p>Connect directly with individuals or contribute to our general fund.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Transparent Tracking</h3>
                    <p>Track every donation with real-time updates and impact reports.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure Platform</h3>
                    <p>Bank-grade security protects all transactions and personal information.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Active Donors</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1M+</div>
                <div class="stat-label">KES Distributed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">200+</div>
                <div class="stat-label">Lives Changed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Communities</div>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="#features">Features</a>
                <a href="login.html">Login</a>
                <a href="signup.html">Sign Up</a>
            </div>
            <p>&copy; 2024 NawiriKe CRM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
