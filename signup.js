// Registration functionality for NawiriKe CRM
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('form');
    const errorMessage = document.getElementById('error-message');
    
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const name = document.getElementById('firstname-input').value.trim();
            const email = document.getElementById('email-input').value.trim();
            const contact = document.getElementById('contact-input').value.trim();
            const password = document.getElementById('password-input').value;
            const confirmPassword = document.getElementById('repeat-password-input').value;
            const role = document.getElementById('role-select').value;
            const adminCode = document.getElementById('admin-code').value.trim();
            
            // Basic validation
            let errors = [];
            
            if (!name) {
                errors.push('Name is required');
            }
            
            if (!email) {
                errors.push('Email is required');
            } else if (!isValidEmail(email)) {
                errors.push('Please enter a valid email address');
            }
            
            // Contact is only required for donors
            if (role === 'donor' && !contact) {
                errors.push('Contact information is required for donors');
            }
            
            if (!password) {
                errors.push('Password is required');
            } else if (password.length < 8) {
                errors.push('Password must be at least 8 characters long');
            }
            
            if (password !== confirmPassword) {
                errors.push('Passwords do not match');
            }
            
            if (!role) {
                errors.push('Please select a role');
            }
            
            if (role === 'admin' && !adminCode) {
                errors.push('Admin code is required for admin registration');
            }
            
            // Display errors if any
            if (errors.length > 0) {
                errorMessage.textContent = errors.join('. ');
                errorMessage.style.color = 'red';
                return;
            }
            
            // Show loading state
            const submitBtn = signupForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Creating Account...';
            submitBtn.disabled = true;
            
            // Send registration request to authController.php
            const formData = new FormData();
            formData.append('action', 'register');
            formData.append('name', name);
            formData.append('email', email);
            formData.append('contact', contact);
            formData.append('password', password);
            formData.append('confirm_password', confirmPassword);
            formData.append('role', role);
            formData.append('admin_code', adminCode);
            
            fetch('authController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Registration successful
                    errorMessage.textContent = data.message;
                    errorMessage.style.color = 'green';
                    
                    // Clear form
                    signupForm.reset();
                    
                    // Redirect to login after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    // Registration failed - show errors
                    errorMessage.textContent = data.errors.join('. ');
                    errorMessage.style.color = 'red';
                    
                    // Reset button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Registration error:', error);
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.style.color = 'red';
                
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Clear error message when user starts typing
    const inputs = signupForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (errorMessage.textContent) {
                errorMessage.textContent = '';
            }
        });
    });
});

// Email validation function
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Phone number validation function (basic)
function isValidPhone(phone) {
    const phoneRegex = /^[\d\s\-\+\(\)]+$/;
    return phoneRegex.test(phone) && phone.length >= 10;
}
