<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - NawiriKe CRM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 18px;
        }
        
        .content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .main-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        .section p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }
        
        .section ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .section li {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        
        .contact-info h3 {
            color: #007bff;
            margin-bottom: 15px;
        }
        
        .contact-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .contact-item strong {
            min-width: 80px;
            color: #333;
        }
        
        .contact-item span {
            color: #555;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        
        .stat-card h3 {
            color: #007bff;
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 14px;
        }
        
        .team {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .team-member {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        
        .team-member h4 {
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .team-member p {
            color: #666;
            font-size: 14px;
        }
        
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        
        .back-link a {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
        }
        
        .back-link a:hover {
            background: #0056b3;
        }
        
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>About NawiriKe CRM</h1>
            <p>Empowering Communities Through Compassionate Support</p>
        </div>
        
        <div class="content">
        
            <div class="main-content">
                
                <div class="section">
                    <h2>Our Mission</h2>
                    <p>NawiriKe CRM is dedicated to connecting those in need with generous donors who can make a real difference in their lives. We believe that every person deserves access to basic necessities and support during challenging times.</p>
                    <p>Our platform serves as a bridge between victims of various circumstances and compassionate donors who want to contribute to meaningful causes in their community.</p>
                </div>
                
            
                <div class="section">
                    <h2>What We Do</h2>
                    <p>Through our comprehensive CRM system, we facilitate:</p>
                    <ul>
                        <li>Victim registration and application processing</li>
                        <li>Donor management and donation tracking</li>
                        <li>Secure and transparent donation distribution</li>
                        <li>Real-time impact monitoring and reporting</li>
                        <li>Community support and resource coordination</li>
                    </ul>
                </div>
                
                <!-- Our Values -->
                <div class="section">
                    <h2>Our Values</h2>
                    <p>Our work is guided by core principles that ensure we serve our community with integrity and compassion:</p>
                    <ul>
                        <li><strong>Compassion:</strong> We approach every situation with empathy and understanding</li>
                        <li><strong>Transparency:</strong> All donations and distributions are fully tracked and reported</li>
                        <li><strong>Dignity:</strong> We treat every individual with respect and preserve their dignity</li>
                        <li><strong>Efficiency:</strong> We ensure resources reach those who need them most, quickly and effectively</li>
                        <li><strong>Community:</strong> We foster a sense of belonging and mutual support</li>
                    </ul>
                </div>
                
                <!-- Impact Stats -->
                <div class="section">
                    <h2>Our Impact</h2>
                    <div class="stats">
                        <div class="stat-card">
                            <h3>1000+</h3>
                            <p>Lives Impacted</p>
                        </div>
                        <div class="stat-card">
                            <h3>500+</h3>
                            <p>Active Donors</p>
                        </div>
                        <div class="stat-card">
                            <h3>50+</h3>
                            <p>Partner Organizations</p>
                        </div>
                        <div class="stat-card">
                            <h3>24/7</h3>
                            <p>Support Available</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Contact Information -->
                <div class="contact-info">
                    <h3>Contact Us</h3>
                    <div class="contact-item">
                        <strong>Phone:</strong>
                        <span>0111611916</span>
                    </div>
                    <div class="contact-item">
                        <strong>Email:</strong>
                        <span>info@nawirike.org</span>
                    </div>
                    <div class="contact-item">
                        <strong>Address:</strong>
                        <span>Nairobi, Kenya</span>
                    </div>
                    <div class="contact-item">
                        <strong>Hours:</strong>
                        <span>Mon-Fri: 9AM-6PM</span>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="section" style="margin-top: 30px;">
                    <h3>Quick Links</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="login.html" style="color: #007bff; text-decoration: none;">Login</a></li>
                        <li><a href="signup.html" style="color: #007bff; text-decoration: none;">Sign Up</a></li>
                        <li><a href="#" style="color: #007bff; text-decoration: none;">Donate Now</a></li>
                        <li><a href="#" style="color: #007bff; text-decoration: none;">Volunteer</a></li>
                    </ul>
                </div>
                
                <!-- Emergency Contact -->
                <div class="contact-info" style="margin-top: 30px; background: #fff3cd; border-left-color: #ffc107;">
                    <h3 style="color: #856404;">Emergency Support</h3>
                    <p style="color: #856404; font-size: 14px;">If you or someone you know needs immediate assistance, please don't hesitate to reach out.</p>
                    <div class="contact-item">
                        <strong>Hotline:</strong>
                        <span style="color: #dc3545; font-weight: bold;">0111611916</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Team Section -->
        <div class="main-content">
            <div class="section">
                <h2>Our Dedicated Team</h2>
                <div class="team">
                    <div class="team-member">
                        <h4>Sarah Johnson</h4>
                        <p>Executive Director</p>
                    </div>
                    <div class="team-member">
                        <h4>Michael Chen</h4>
                        <p>Operations Manager</p>
                    </div>
                    <div class="team-member">
                        <h4>Grace Wangari</h4>
                        <p>Community Outreach</p>
                    </div>
                    <div class="team-member">
                        <h4>David Mutiso</h4>
                        <p>Technical Director</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Back Link -->
        <div class="back-link">
            <a href="login.html">Back to Login</a>
        </div>
    </div>
</body>
</html>
