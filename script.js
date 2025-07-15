// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    console.log('Web Application Loaded Successfully!');
    
    // Initialize all features
    initializeNavigation();
    initializeDynamicButton();
    initializeFormValidation();
    initializeAnimations();
    initializeMobileMenu();
});

// Navigation smooth scrolling
function initializeNavigation() {
    const navLinks = document.querySelectorAll('.nav-links a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Dynamic button functionality
function initializeDynamicButton() {
    const dynamicBtn = document.getElementById('dynamicBtn');
    const messages = [
        'Hello World!',
        'Welcome to our site!',
        'Thanks for clicking!',
        'You\'re awesome!',
        'Keep exploring!',
        'Have a great day!'
    ];
    
    let clickCount = 0;
    
    dynamicBtn.addEventListener('click', function() {
        clickCount++;
        
        // Show loading animation
        this.innerHTML = '<span class="loading"></span> Loading...';
        this.disabled = true;
        
        // Simulate async operation
        setTimeout(() => {
            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            this.innerHTML = `${randomMessage} (Clicked ${clickCount} times)`;
            this.disabled = false;
            
            // Add some visual feedback
            this.style.background = getRandomGradient();
            
            // Reset after 3 seconds
            setTimeout(() => {
                this.innerHTML = 'Click Me!';
                this.style.background = '';
            }, 3000);
        }, 1000);
    });
}

// Form validation
function initializeFormValidation() {
    const contactForm = document.getElementById('contactForm');
    const inputs = contactForm.querySelectorAll('input, textarea');
    
    // Real-time validation
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearValidationError(this);
        });
    });
    
    // Form submission
    contactForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showNotification('Please fix the errors before submitting.', 'error');
        } else {
            showNotification('Form submitted successfully!', 'success');
        }
    });
}

// Field validation function
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    let isValid = true;
    let errorMessage = '';
    
    // Clear previous errors
    clearValidationError(field);
    
    // Required field check
    if (value === '') {
        errorMessage = `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required.`;
        isValid = false;
    }
    
    // Email validation
    if (fieldName === 'email' && value !== '') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            errorMessage = 'Please enter a valid email address.';
            isValid = false;
        }
    }
    
    // Name validation
    if (fieldName === 'name' && value !== '') {
        if (value.length < 2) {
            errorMessage = 'Name must be at least 2 characters long.';
            isValid = false;
        }
    }
    
    // Message validation
    if (fieldName === 'message' && value !== '') {
        if (value.length < 10) {
            errorMessage = 'Message must be at least 10 characters long.';
            isValid = false;
        }
    }
    
    if (!isValid) {
        showValidationError(field, errorMessage);
    }
    
    return isValid;
}

// Show validation error
function showValidationError(field, message) {
    field.style.borderColor = '#dc3545';
    
    let errorDiv = field.parentNode.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        field.parentNode.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
}

// Clear validation error
function clearValidationError(field) {
    field.style.borderColor = '#ddd';
    
    const errorDiv = field.parentNode.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Animations on scroll
function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 1s ease-out forwards';
            }
        });
    }, observerOptions);
    
    // Observe all content sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        observer.observe(section);
    });
}

// Mobile menu functionality
function initializeMobileMenu() {
    // This would be expanded for a full mobile menu
    // For now, we'll just handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            console.log('Mobile view activated');
        } else {
            console.log('Desktop view activated');
        }
    });
}

// Utility functions
function getRandomGradient() {
    const colors = [
        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
        'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
    ];
    
    return colors[Math.floor(Math.random() * colors.length)];
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        z-index: 9999;
        animation: slideInRight 0.3s ease-out;
    `;
    
    // Set background color based on type
    if (type === 'success') {
        notification.style.backgroundColor = '#28a745';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#dc3545';
    } else {
        notification.style.backgroundColor = '#667eea';
    }
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    // ESC key to close any active elements
    if (e.key === 'Escape') {
        // Close any open modals, menus, etc.
        console.log('Escape key pressed');
    }
    
    // Enter key on buttons
    if (e.key === 'Enter' && e.target.tagName === 'BUTTON') {
        e.target.click();
    }
});

// Page performance monitoring
window.addEventListener('load', function() {
    const loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart;
    console.log(`Page loaded in ${loadTime}ms`);
    
    // Show load time to user (for development)
    if (loadTime > 2000) {
        console.warn('Page load time is slow:', loadTime + 'ms');
    }
});

// Error handling
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    showNotification('An error occurred. Please refresh the page.', 'error');
});

// Export functions for testing (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validateField,
        showNotification,
        getRandomGradient
    };
}