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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo:hover {
            color: #0056b3;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        
        .nav-item {
            margin-left: 30px;
        }
        
        .nav-link {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 5px 0;
        }
        
        .nav-link:hover {
            color: #007bff;
        }
        
        .nav-cta {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        
        .nav-cta:hover {
            background: #0056b3;
            color: white;
        }
        
        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 20px 80px;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="30" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
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
            font-size: 48px;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-text p {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: white;
            color: #007bff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            padding: 15px 30px;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #007bff;
        }
        
        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .hero-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .hero-card h3 {
            color: white;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .hero-card p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
        }
        
        /* Features Section */
        .features {
            padding: 80px 20px;
            background: #f8f9fa;
        }
        
        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .features h2 {
            text-align: center;
            font-size: 36px;
            color: #333;
            margin-bottom: 50px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: white;
        }
        
        .feature-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Stats Section */
        .stats {
            padding: 80px 20px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        
        .stats h2 {
            font-size: 36px;
            margin-bottom: 50px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
        }
        
        .stat-label {
            font-size: 18px;
            opacity: 0.9;
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #007bff;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .nav-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                transition: left 0.3s ease;
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .nav-item {
                margin: 10px 0;
            }
            
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }
            
            .hero-text h1 {
                font-size: 36px;
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
                <p>Connect with those in need and make a real difference in their lives through our comprehensive support system.</p>
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
            <p>&copy; 2026 NawiriKe CRM. All rights reserved. | Empowering Communities Through Support</p>
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
