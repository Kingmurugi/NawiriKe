// Login functionality for NawiriKe CRM
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('form');
    const errorMessage = document.getElementById('error-message');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const email = document.getElementById('email-input').value.trim();
            const password = document.getElementById('password-input').value;
            
            // Basic validation
            let errors = [];
            
            if (!email) {
                errors.push('Email is required');
            } else if (!isValidEmail(email)) {
                errors.push('Please enter a valid email address');
            }
            
            if (!password) {
                errors.push('Password is required');
            }
            
            // Display errors if any
            if (errors.length > 0) {
                errorMessage.textContent = errors.join('. ');
                errorMessage.style.color = 'red';
                return;
            }
            
            // Show loading state
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Logging in...';
            submitBtn.disabled = true;
            
            // Send login request to authController.php
            const formData = new FormData();
            formData.append('action', 'login');
            formData.append('email', email);
            formData.append('password', password);
            
            console.log('Sending login request for email:', email);
            console.log('Form data:', Object.fromEntries(formData));
            
            fetch('authController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    // Login successful - redirect based on role
                    errorMessage.textContent = data.message;
                    errorMessage.style.color = 'green';
                    
                    console.log('Login successful, redirecting to:', data.redirect_url);
                    
                    // Redirect to appropriate dashboard
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1000);
                } else {
                    // Login failed - show errors
                    console.log('Login failed:', data.errors);
                    errorMessage.textContent = data.errors.join('. ');
                    errorMessage.style.color = 'red';
                    
                    // Reset button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.style.color = 'red';
                
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Clear error message when user starts typing
    const inputs = loginForm.querySelectorAll('input');
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
