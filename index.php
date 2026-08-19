<?php
/**
 * Home Page - NawiriKe CRM
 * Main landing page with navigation bar
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
        }
        
        /* Navigation Bar */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            border-bottom: 3px solid #667eea;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 90px;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 800;
            color: #667eea;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            letter-spacing: -0.5px;
        }
        
        .logo:hover {
            color: #764ba2;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(102,126,234,0.4);
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        
        .nav-item {
            margin-left: 20px;
        }
        
        .nav-link {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 12px 20px;
            border-radius: 25px;
            position: relative;
            font-size: 15px;
        }
        
        .nav-link:hover {
            color: #667eea;
            background: rgba(102,126,234,0.1);
        }
        
        .nav-cta {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
            font-size: 15px;
        }
        
        .nav-cta:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102,126,234,0.4);
        }
        
        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #333;
            padding: 10px;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 140px 20px 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="30" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .hero-text h1 {
            font-size: 58px;
            font-weight: 900;
            color: white;
            margin-bottom: 25px;
            line-height: 1.1;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
            animation: fadeInUp 1s ease-out;
            letter-spacing: -1px;
        }
        
        .hero-text p {
            font-size: 22px;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 35px;
            line-height: 1.7;
            animation: fadeInUp 1s ease-out 0.2s backwards;
            font-weight: 400;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.4s backwards;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            padding: 18px 40px;
            border: none;
            border-radius: 30px;
            font-size: 17px;
            font-weight: 800;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        }
        
        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            background: #f8f9fa;
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            padding: 18px 40px;
            border: 3px solid white;
            border-radius: 30px;
            font-size: 17px;
            font-weight: 800;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }
        
        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .hero-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(25px);
            border-radius: 28px;
            padding: 50px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .hero-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25);
        }
        
        .hero-card h3 {
            color: white;
            font-size: 26px;
            margin-bottom: 18px;
            font-weight: 700;
        }
        
        .hero-card p {
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 25px;
            font-size: 18px;
        }
        
        /* Features Section */
        .features {
            padding: 120px 20px;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        }
        
        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .features h2 {
            text-align: center;
            font-size: 48px;
            color: #333;
            margin-bottom: 25px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        
        .features-subtitle {
            text-align: center;
            font-size: 19px;
            color: #666;
            margin-bottom: 70px;
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.2);
            border-color: #667eea;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 26px;
            color: white;
            box-shadow: 0 6px 20px rgba(102,126,234,0.4);
        }
        
        .feature-card h3 {
            color: #333;
            margin-bottom: 18px;
            font-size: 22px;
            font-weight: 700;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.7;
            font-size: 16px;
        }
        
        /* Stats Section */
        .stats {
            padding: 120px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        
        .stats h2 {
            font-size: 48px;
            margin-bottom: 25px;
            font-weight: 800;
            position: relative;
            z-index: 1;
            letter-spacing: -1px;
        }
        
        .stats-subtitle {
            font-size: 19px;
            opacity: 0.95;
            margin-bottom: 70px;
            position: relative;
            z-index: 1;
            line-height: 1.7;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        
        .stat-item {
            text-align: center;
            padding: 35px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .stat-item:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-10px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.25);
        }
        
        .stat-number {
            font-size: 60px;
            font-weight: 900;
            margin-bottom: 12px;
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            font-size: 19px;
            opacity: 0.95;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            color: white;
            padding: 70px 20px;
            text-align: center;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
        }
        
        .footer-links a:hover {
            color: #667eea;
            background: rgba(255,255,255,0.15);
            transform: translateY(-3px);
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .nav-container {
                max-width: 100%;
                padding: 0 20px;
            }
            
            .nav-item {
                margin-left: 15px;
            }
            
            .nav-link {
                padding: 10px 16px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .nav-menu {
                position: fixed;
                top: 90px;
                left: -100%;
                width: 100%;
                background: white;
                flex-direction: column;
                padding: 25px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                transition: left 0.3s ease;
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .nav-item {
                margin: 12px 0;
            }
            
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }
            
            .hero-text h1 {
                font-size: 42px;
                letter-spacing: -0.5px;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">N</div>
                <strong>NawiriKe</strong> CRM
            </a>
            
            <button class="menu-toggle" onclick="toggleMenu()">&#9776;</button>
            
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="about.php" class="nav-link">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">How It Works</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Success Stories</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact</a>
                </li>
                <li class="nav-item">
                    <a href="login.html" class="nav-cta">Login</a>
                </li>
                <li class="nav-item">
                    <a href="signup.html" class="nav-cta">Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Empowering Communities Through Compassionate Support</h1>
                <p>Connect donors with victims in need, ensuring every contribution makes a meaningful impact through our transparent and efficient support system.</p>
                <div class="hero-buttons">
                    <a href="signup.html" class="btn-primary">Get Started</a>
                    <a href="about.php" class="btn-secondary">Learn More</a>
                </div>
            </div>
            
            <div class="hero-image">
                <div class="hero-card">
                    <h3>Make an Impact Today</h3>
                    <p>Join our community of donors and volunteers dedicated to creating positive change.</p>
                    <a href="signup.html" class="btn-primary">Join Now</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <h2>How We Help</h2>
            <p class="features-subtitle">Our comprehensive support system connects generous donors with verified victims, ensuring every contribution creates meaningful impact in our communities.</p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"> Victims</div>
                    <h3>Support for Victims</h3>
                    <p>Provide essential support to individuals and families facing challenging circumstances through our comprehensive aid system.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"> Donors</div>
                    <h3>Empower Donors</h3>
                    <p>Connect generous donors with verified causes, ensuring your contributions make the maximum impact in the community.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"> Track</div>
                    <h3>Transparent Tracking</h3>
                    <p>Monitor your donations and see exactly how your support is making a difference with real-time impact reporting.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"> Community</div>
                    <h3>Build Community</h3>
                    <p>Foster a supportive network where compassion meets action, creating lasting positive change in society.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"> Support</div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated team is always available to provide assistance and guidance to both donors and recipients.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"> Impact</div>
                    <h3>Measure Impact</h3>
                    <p>Track the real-world impact of your contributions through detailed reports and success stories.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <h2>Our Impact Together</h2>
            <p class="stats-subtitle">Together we're creating real change in communities across Kenya through compassionate support and transparent giving.</p>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">1,000+</div>
                    <div class="stat-label">Lives Impacted</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Active Donors</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Partner Organizations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support Available</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="about.php">About Us</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact</a>
            </div>
            <p>&copy; 2026 NawiriKe CRM. All rights reserved. | Empowering Communities Through Compassionate Support</p>
        </div>
    </footer>
    
    <script>
        // Mobile Menu Toggle
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.getElementById('navMenu');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                navMenu.classList.remove('active');
            }
        });
        
        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>
