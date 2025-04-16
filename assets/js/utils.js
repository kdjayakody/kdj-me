/**
 * KDJ Lanka - Common JavaScript Utilities
 * -----------------------------------
 * This file contains common JavaScript utility functions used across the KDJ Lanka website.
 */

// API configuration
const API_BASE_URL = 'https://auth.kdj.lk/api/v1';
const REDIRECT_AFTER_LOGIN = 'dashboard.php';

/**
 * Display a toast notification
 * 
 * @param {string} message - The message to display
 * @param {string} type - The type of toast: 'success', 'error', or 'info'
 * @param {number} duration - Duration in milliseconds to show the toast
 */
function showToast(message, type = 'success', duration = 5000) {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;
    
    const toast = document.createElement('div');
    toast.className = `mb-3 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'} transform transition-all duration-300 translate-x-full`;
    
    const iconClass = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconClass} mr-3"></i>
            <p>${sanitizeHTML(message)}</p>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Show toast with slide-in animation
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 10);
    
    // Auto-remove toast after duration
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);
}

/**
 * Show loading indicator
 */
function showLoading() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'flex';
    }
}

/**
 * Hide loading indicator
 */
function hideLoading() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
}

/**
 * Validate an email address
 * 
 * @param {string} email - The email address to validate
 * @returns {boolean} - Whether the email is valid
 */
function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Check if a password meets the strength requirements
 * 
 * @param {string} password - The password to check
 * @returns {object} - Object with valid flag and errors array
 */
function validatePasswordStrength(password) {
    const minLength = 12;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasDigits = /\d/.test(password);
    const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    const errors = [];
    
    if (password.length < minLength) {
        errors.push(`Password must be at least ${minLength} characters long`);
    }
    
    if (!hasUppercase) {
        errors.push("Password must contain at least one uppercase letter");
    }
    
    if (!hasLowercase) {
        errors.push("Password must contain at least one lowercase letter");
    }
    
    if (!hasDigits) {
        errors.push("Password must contain at least one digit");
    }
    
    if (!hasSpecialChars) {
        errors.push("Password must contain at least one special character");
    }
    
    return {
        valid: errors.length === 0,
        errors: errors
    };
}

/**
 * Format a date for display
 * 
 * @param {string|Date} dateString - The date to format
 * @param {boolean} includeTime - Whether to include the time in the formatted string
 * @returns {string} - The formatted date string
 */
function formatDate(dateString, includeTime = true) {
    if (!dateString) return 'Never';
    
    const date = new Date(dateString);
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    
    if (includeTime) {
        options.hour = '2-digit';
        options.minute = '2-digit';
    }
    
    return date.toLocaleString('en-US', options);
}

/**
 * Set greeting based on time of day
 * 
 * @returns {string} - A greeting appropriate for the current time of day
 */
function getTimeBasedGreeting() {
    const hour = new Date().getHours();
    let greeting = '';
    
    if (hour < 12) {
        greeting = 'සුභ උදෑසනක්'; // Good morning
    } else if (hour < 17) {
        greeting = 'සුභ දහවලක්'; // Good afternoon
    } else {
        greeting = 'සුභ සන්ධ්‍යාවක්'; // Good evening
    }
    
    return greeting;
}

/**
 * Make an API request with proper error handling
 * 
 * @param {string} endpoint - The API endpoint (will be appended to API_BASE_URL)
 * @param {object} options - Fetch options (method, headers, body)
 * @param {boolean} includeCredentials - Whether to include credentials (cookies)
 * @returns {Promise} - Promise that resolves to the API response
 */
async function apiRequest(endpoint, options = {}, includeCredentials = true) {
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...(options.headers || {}),
        }
    };
    
    if (includeCredentials) {
        config.credentials = 'include';
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        
        // Handle unauthorized error by redirecting to login
        if (response.status === 401) {
            // Save current URL to redirect back after login
            sessionStorage.setItem('redirectAfterLogin', window.location.href);
            window.location.href = 'index.php';
            throw new Error('Unauthorized');
        }
        
        return response;
    } catch (error) {
        console.error(`API call to ${endpoint} failed:`, error);
        throw error;
    }
}

/**
 * Sanitize HTML to prevent XSS attacks
 * 
 * @param {string} input - The HTML string to sanitize
 * @returns {string} - The sanitized HTML string
 */
function sanitizeHTML(input) {
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
}

/**
 * Generate a CSRF token
 * 
 * @returns {string} - A random CSRF token
 */
function generateCSRFToken() {
    return Math.random().toString(36).substring(2, 15) + 
           Math.random().toString(36).substring(2, 15);
}

/**
 * Get a stored CSRF token or generate a new one
 * 
 * @returns {string} - The CSRF token
 */
function getCSRFToken() {
    let token = sessionStorage.getItem('csrf_token');
    if (!token) {
        token = generateCSRFToken();
        sessionStorage.setItem('csrf_token', token);
    }
    return token;
}

/**
 * Check if user is logged in by trying to fetch profile
 * 
 * @returns {Promise<object|null>} - The user data if logged in, null otherwise
 */
async function checkUserAuthentication() {
    try {
        const response = await apiRequest('/users/me', {
            method: 'GET'
        });
        
        if (!response.ok) {
            return null;
        }
        
        return await response.json();
    } catch (error) {
        console.error('Authentication check failed:', error);
        return null;
    }
}

/**
 * Redirect user to login page if not authenticated
 */
async function requireAuthentication() {
    const userData = await checkUserAuthentication();
    if (!userData) {
        window.location.href = 'index.php';
    }
    return userData;
}

/**
 * Handle logout
 */
async function handleLogout() {
    showLoading();
    
    try {
        const response = await apiRequest('/auth/logout', {
            method: 'POST'
        });
        
        if (response.ok) {
            // Clear session storage
            sessionStorage.clear();
            
            // Redirect to login page
            window.location.href = 'index.php';
        } else {
            const data = await response.json().catch(() => ({ detail: 'Logout failed.' }));
            showToast(`Logout failed: ${data.detail || response.statusText}`, 'error');
            hideLoading();
        }
    } catch (error) {
        console.error('Logout error:', error);
        showToast('Network error. Please try again.', 'error');
        hideLoading();
    }
}

/**
 * Update the security score in the security page
 * 
 * @param {object} userData - The user data
 * @param {HTMLElement} scoreBar - The score bar element
 * @param {HTMLElement} scoreText - The score text element
 * @param {HTMLElement} scoreMessage - The score message element
 */
function updateSecurityScore(userData, scoreBar, scoreText, scoreMessage) {
    let score = 0;
    const totalItems = 3; // Email verified, strong password, MFA
    
    if (userData.email_verified) score++;
    score++; // Assume strong password
    if (userData.mfa_enabled) score++;
    
    const scorePercentage = Math.round((score / totalItems) * 100);
    scoreBar.style.width = `${scorePercentage}%`;
    scoreText.textContent = `${scorePercentage}%`;
    
    if (scorePercentage < 70) {
        scoreBar.className = 'bg-yellow-400 h-2.5 rounded-full';
        scoreMessage.textContent = 'Your account security can be improved. Enable two-factor authentication for better protection.';
    } else if (scorePercentage === 100) {
        scoreBar.className = 'bg-green-500 h-2.5 rounded-full';
        scoreMessage.textContent = 'Great job! Your account has all recommended security features enabled.';
    }
}

// Initialize common functionality
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token if not already set
    if (!sessionStorage.getItem('csrf_token')) {
        sessionStorage.setItem('csrf_token', generateCSRFToken());
    }
});