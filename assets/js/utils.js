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
 * @param {string} type - The type of toast: 'success', 'error', 'warning', or 'info'
 * @param {number} duration - Duration in milliseconds to show the toast
 */
function showToast(message, type = 'success', duration = 5000) {
    // Get or create toast container
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'fixed top-4 right-4 z-50';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    
    // Set appropriate color based on type
    let bgColor, textColor, iconClass;
    switch(type) {
        case 'success':
            bgColor = 'bg-green-500';
            textColor = 'text-white';
            iconClass = 'fa-check-circle';
            break;
        case 'error':
            bgColor = 'bg-red-500';
            textColor = 'text-white';
            iconClass = 'fa-exclamation-circle';
            break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            textColor = 'text-white';
            iconClass = 'fa-exclamation-triangle';
            break;
        case 'info':
        default:
            bgColor = 'bg-blue-500';
            textColor = 'text-white';
            iconClass = 'fa-info-circle';
    }
    
    toast.className = `mb-3 p-4 rounded-lg shadow-lg ${textColor} ${bgColor} transform transition-all duration-300 translate-x-full`;
    
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
    let loadingIndicator = document.getElementById('loadingIndicator');
    
    // Create loading indicator if it doesn't exist
    if (!loadingIndicator) {
        loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loadingIndicator';
        loadingIndicator.className = 'fixed top-0 left-0 w-full h-full flex items-center justify-center bg-white bg-opacity-80 z-50';
        loadingIndicator.innerHTML = '<div class="loader h-16 w-16 border-4 border-gray-200 rounded-full" style="border-top-color: #cb2127; animation: spin 1s linear infinite;"></div>';
        
        // Add keyframe animation if not already defined
        if (!document.getElementById('loaderAnimationStyle')) {
            const style = document.createElement('style');
            style.id = 'loaderAnimationStyle';
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(loadingIndicator);
    }
    
    loadingIndicator.style.display = 'flex';
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
        errors: errors,
        score: (hasUppercase ? 1 : 0) + (hasLowercase ? 1 : 0) + (hasDigits ? 1 : 0) + 
               (hasSpecialChars ? 1 : 0) + (password.length >= minLength ? 1 : 0)
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
    if (isNaN(date.getTime())) return 'Invalid date';
    
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
 * Update greeting on page elements
 */
function updatePageGreeting() {
    const greeting = getTimeBasedGreeting();
    const elements = [
        document.getElementById('sidebarGreeting'),
        document.getElementById('mobileSidebarGreeting')
    ];
    
    elements.forEach(el => {
        if (el) el.textContent = greeting;
    });
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
    try {
        // Create default headers
        const defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        
        // Add auth token to headers if available
        const authToken = sessionStorage.getItem('auth_token');
        if (authToken) {
            defaultHeaders['Authorization'] = `Bearer ${authToken}`;
            console.log('Using auth token:', `Bearer ${authToken.substring(0, 10)}...`);
        } else {
            console.warn('No auth token found in sessionStorage for API request to', endpoint);
        }
        
        // Create fetch config
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
        
        // Check if token needs refresh before making request
        if (isTokenExpiringSoon() && endpoint !== '/auth/refresh-token') {
            console.log('Token is expiring soon, attempting to refresh');
            await refreshAuthToken();
            
            // Update authorization header with new token
            const newToken = sessionStorage.getItem('auth_token');
            if (newToken) {
                config.headers['Authorization'] = `Bearer ${newToken}`;
                console.log('Using refreshed auth token');
            } else {
                console.warn('Failed to refresh token');
            }
        }
        
        // Add request timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
        config.signal = controller.signal;
        
        // Log request details (in dev mode)
        console.log(`API Request: ${API_BASE_URL}${endpoint}`, {
            method: config.method || 'GET',
            includesAuth: !!config.headers['Authorization'],
            includesCredentials: config.credentials === 'include'
        });
        
        // Make the request
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        clearTimeout(timeoutId); // Clear timeout on successful response
        
        console.log(`API Response: ${endpoint}`, {
            status: response.status,
            ok: response.ok
        });
        
        // Handle unauthorized error by trying to refresh token once
        if (response.status === 401 && endpoint !== '/auth/refresh-token' && endpoint !== '/auth/login') {
            console.log('Received 401 Unauthorized, attempting token refresh');
            // Try to refresh the token
            const refreshed = await refreshAuthToken();
            
            if (refreshed) {
                console.log('Token refreshed successfully, retrying original request');
                // Retry the original request with new token
                const newToken = sessionStorage.getItem('auth_token');
                if (newToken) {
                    config.headers['Authorization'] = `Bearer ${newToken}`;
                }
                
                // Create new abort controller for retry
                const retryController = new AbortController();
                const retryTimeoutId = setTimeout(() => retryController.abort(), 30000);
                config.signal = retryController.signal;
                
                const retryResponse = await fetch(`${API_BASE_URL}${endpoint}`, config);
                clearTimeout(retryTimeoutId);
                
                console.log('Retry response:', {
                    status: retryResponse.status,
                    ok: retryResponse.ok
                });
                
                if (retryResponse.status === 401) {
                    // Still unauthorized after token refresh, redirect to login
                    console.error('Still unauthorized after token refresh');
                    handleAuthFailure();
                    throw new Error('Session expired. Please log in again.');
                }
                
                return retryResponse;
            } else {
                // Token refresh failed, redirect to login
                console.error('Token refresh failed, redirecting to login');
                handleAuthFailure();
                throw new Error('Session expired. Please log in again.');
            }
        }
        
        return response;
    } catch (error) {
        if (error.name === 'AbortError') {
            console.error('Request timed out:', endpoint);
            throw new Error('Request timeout. Please check your internet connection and try again.');
        }
        
        console.error(`API call to ${endpoint} failed:`, error);
        throw error;
    }
}

/**
 * Handle authentication failure by clearing tokens and redirecting to login
 */
function handleAuthFailure() {
    console.log('Handling authentication failure');
    // Clear all authentication data
    sessionStorage.removeItem('auth_token');
    sessionStorage.removeItem('token_expiry');
    sessionStorage.removeItem('refresh_token');
    localStorage.removeItem('refresh_token');
    
    // Save current URL to redirect back after login
    const currentPage = window.location.pathname;
    console.log('Saving current page for redirect:', currentPage);
    sessionStorage.setItem('redirectAfterLogin', window.location.href);
    
    // Redirect to login page
    window.location.href = '/index.php';
}

/**
 * Check if token expiration time is coming up
 * @returns {boolean} True if token is expiring soon
 */
function isTokenExpiringSoon() {
    const tokenExpiry = sessionStorage.getItem('token_expiry');
    if (!tokenExpiry) return true;
    
    // Check if token expires in the next 5 minutes (300000 ms)
    return parseInt(tokenExpiry) - 300000 < Date.now();
}

/**
 * Refresh the auth token using the refresh token
 * @returns {Promise<boolean>} True if refresh was successful
 */
async function refreshAuthToken() {
    // Try to get refresh token from session storage first, then local storage
    const refreshToken = sessionStorage.getItem('refresh_token') || localStorage.getItem('refresh_token');
    if (!refreshToken) {
        console.warn('No refresh token found in storage');
        return false;
    }
    
    try {
        console.log('Attempting to refresh token');
        const response = await fetch(`${API_BASE_URL}/auth/refresh-token`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ refresh_token: refreshToken }),
            credentials: 'include'
        });
        
        if (!response.ok) {
            console.error('Failed to refresh token:', response.status);
            return false;
        }
        
        const data = await response.json();
        
        if (data.access_token) {
            // Store the new token
            sessionStorage.setItem('auth_token', data.access_token);
            console.log('Stored new access token in sessionStorage');
            
            // Update expiry time
            if (data.expires_in) {
                const expiryTime = Date.now() + (data.expires_in * 1000);
                sessionStorage.setItem('token_expiry', expiryTime.toString());
                console.log('Updated token expiry time');
            }
            
            // Store refresh token if provided
            if (data.refresh_token) {
                // Keep it in the same storage it was found in
                if (localStorage.getItem('refresh_token')) {
                    localStorage.setItem('refresh_token', data.refresh_token);
                    console.log('Updated refresh token in localStorage');
                } else {
                    sessionStorage.setItem('refresh_token', data.refresh_token);
                    console.log('Updated refresh token in sessionStorage');
                }
            }
            
            return true;
        }
        
        console.warn('No access token in refresh response');
        return false;
    } catch (error) {
        console.error('Token refresh error:', error);
        return false;
    }
}

/**
 * Sanitize HTML to prevent XSS attacks
 * 
 * @param {string} input - The HTML string to sanitize
 * @returns {string} - The sanitized HTML string
 */
function sanitizeHTML(input) {
    if (!input) return '';
    
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
        const authToken = sessionStorage.getItem('auth_token');
        if (!authToken) {
            console.log('No auth token in sessionStorage, user is not authenticated');
            return null;
        }
        
        console.log('Found auth token, checking if valid by fetching user profile');
        const response = await apiRequest('/users/me', {
            method: 'GET'
        });
        
        if (!response.ok) {
            console.warn('User profile request failed, token may be invalid');
            return null;
        }
        
        console.log('User profile request successful, user is authenticated');
        return await response.json();
    } catch (error) {
        console.error('Authentication check failed:', error);
        return null;
    }
}

/**
 * Redirect user to login page if not authenticated
 * @returns {Promise<object|null>} - The user data if logged in, null otherwise
 */
async function requireAuthentication() {
    console.log('Checking authentication for protected page');
    const userData = await checkUserAuthentication();
    if (!userData) {
        console.log('User is not authenticated, redirecting to login');
        // Save current URL to redirect back after login
        sessionStorage.setItem('redirectAfterLogin', window.location.href);
        window.location.href = 'index.php';
        return null;
    }
    console.log('User is authenticated, continuing to protected page');
    return userData;
}

/**
 * Handle common logout functionality
 */
async function handleLogout() {
    showLoading();
    
    try {
        const response = await apiRequest('/auth/logout', {
            method: 'POST'
        });
        
        // Clear all auth data regardless of response
        clearAuthData();
        
        if (response.ok) {
            // Redirect to login page
            window.location.href = 'index.php';
        } else {
            const data = await response.json().catch(() => ({ detail: 'Logout failed.' }));
            showToast(`Logout failed: ${data.detail || response.statusText}`, 'error');
            
            // Still redirect to login page
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
            
            hideLoading();
        }
    } catch (error) {
        console.error('Logout error:', error);
        
        // Clear auth data anyway
        clearAuthData();
        
        showToast('Network error during logout. You have been logged out locally.', 'warning');
        
        // Redirect to login page
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000);
        
        hideLoading();
    }
}

/**
 * Clear all authentication data from storage
 */
function clearAuthData() {
    console.log('Clearing all authentication data');
    sessionStorage.removeItem('auth_token');
    sessionStorage.removeItem('token_expiry');
    sessionStorage.removeItem('refresh_token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('user_id');
    sessionStorage.removeItem('user_id');
}

/**
 * Store authentication tokens after login
 * @param {object} data - The response data from login API
 * @param {boolean} rememberMe - Whether to remember the user
 */
function storeAuthData(data, rememberMe) {
    console.log('Storing authentication data', { rememberMe, hasAccessToken: !!data.access_token });
    
    // Access token always goes in session storage
    if (data.access_token) {
        sessionStorage.setItem('auth_token', data.access_token);
        console.log('Stored access token in sessionStorage');
        
        // Store token expiry if provided
        if (data.expires_in) {
            const expiryTime = Date.now() + (data.expires_in * 1000);
            sessionStorage.setItem('token_expiry', expiryTime.toString());
            console.log('Stored token expiry time:', new Date(expiryTime).toISOString());
        }
    } else {
        console.warn('No access token provided in login response');
    }
    
    // Refresh token goes in localStorage if rememberMe, otherwise sessionStorage
    if (data.refresh_token) {
        if (rememberMe) {
            localStorage.setItem('refresh_token', data.refresh_token);
            sessionStorage.removeItem('refresh_token');
            console.log('Stored refresh token in localStorage (remember me)');
        } else {
            sessionStorage.setItem('refresh_token', data.refresh_token);
            localStorage.removeItem('refresh_token');
            console.log('Stored refresh token in sessionStorage');
        }
    } else {
        console.warn('No refresh token provided in login response');
    }
    
    // Store user ID if provided
    if (data.user_id) {
        if (rememberMe) {
            localStorage.setItem('user_id', data.user_id);
            console.log('Stored user ID in localStorage');
        } else {
            sessionStorage.setItem('user_id', data.user_id);
            console.log('Stored user ID in sessionStorage');
        }
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

/**
 * Initialize mobile sidebar functionality
 */
function initMobileSidebar() {
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileSidebarContent = document.getElementById('mobileSidebarContent');
    const closeMobileSidebar = document.getElementById('closeMobileSidebar');
    
    if (!mobileSidebarToggle || !mobileSidebar || !mobileSidebarContent) return;
    
    mobileSidebarToggle.addEventListener('click', function() {
        mobileSidebar.classList.remove('hidden');
        setTimeout(() => {
            mobileSidebarContent.classList.remove('-translate-x-full');
        }, 10);
    });
    
    const closeSidebar = function() {
        mobileSidebarContent.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileSidebar.classList.add('hidden');
        }, 300);
    };
    
    if (closeMobileSidebar) {
        closeMobileSidebar.addEventListener('click', closeSidebar);
    }
    
    mobileSidebar.addEventListener('click', function(e) {
        if (e.target === mobileSidebar) {
            closeSidebar();
        }
    });
}

/**
 * Initialize logout buttons
 */
function initLogoutButtons() {
    // Get all logout buttons
    const logoutButtons = [
        document.getElementById('logoutBtn'),
        document.getElementById('mobileLogoutBtn'),
        document.getElementById('sidebarLogoutBtn'),
        document.getElementById('mobileSidebarLogoutBtn')
    ];
    
    // Add click event to all existing logout buttons
    logoutButtons.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', handleLogout);
        }
    });
}

/**
 * Setup password toggles (show/hide password)
 */
function setupPasswordToggles() {
    const toggles = document.querySelectorAll('.password-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.closest('div').querySelector('input');
            if (!input) return;
            
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    });
}

/**
 * Initialize the password strength meter
 * @param {HTMLElement} passwordInput - The password input element
 * @param {HTMLElement} strengthMeter - The strength meter element
 * @param {Object} requirements - Object containing requirement elements
 */
function initPasswordStrengthMeter(passwordInput, strengthMeter, requirements) {
    if (!passwordInput || !strengthMeter || !requirements) return;
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const result = validatePasswordStrength(password);
        const maxScore = 5;
        
        // Update requirement indicators
        if (requirements.length && 'querySelector' in requirements.length) {
            updateRequirement(requirements.length, password.length >= 12);
        }
        if (requirements.uppercase && 'querySelector' in requirements.uppercase) {
            updateRequirement(requirements.uppercase, /[A-Z]/.test(password));
        }
        if (requirements.lowercase && 'querySelector' in requirements.lowercase) {
            updateRequirement(requirements.lowercase, /[a-z]/.test(password));
        }
        if (requirements.number && 'querySelector' in requirements.number) {
            updateRequirement(requirements.number, /\d/.test(password));
        }
        if (requirements.special && 'querySelector' in requirements.special) {
            updateRequirement(requirements.special, /[!@#$%^&*(),.?":{}|<>]/.test(password));
        }
        
        // Update strength meter
        const percentage = (result.score / maxScore) * 100;
        strengthMeter.style.width = `${percentage}%`;
        
        // Update color based on strength
        if (percentage < 40) {
            strengthMeter.className = 'password-strength-meter bg-red-500';
        } else if (percentage < 80) {
            strengthMeter.className = 'password-strength-meter bg-yellow-500';
        } else {
            strengthMeter.className = 'password-strength-meter bg-green-500';
        }
    });
    
    function updateRequirement(element, isFulfilled) {
        const icon = element.querySelector('i');
        if (!icon) return;
        
        if (isFulfilled) {
            icon.className = 'fas fa-check-circle text-green-500 mr-1';
        } else {
            icon.className = 'fas fa-times-circle text-red-500 mr-1';
        }
    }
}

/**
 * Initialize common page functionalities
 */
function initCommonPageFunctions() {
    // Set up CSRF token if not already set
    if (!sessionStorage.getItem('csrf_token')) {
        sessionStorage.setItem('csrf_token', generateCSRFToken());
    }
    
    // Update greetings
    updatePageGreeting();
    
    // Initialize mobile sidebar
    initMobileSidebar();
    
    // Initialize logout buttons
    initLogoutButtons();
    
    // Set up password toggles
    setupPasswordToggles();
    
    // Run auth check on protected pages
    checkAuthForProtectedPages();
    
    // Redirect to appropriate page after login if specified
    handleRedirectAfterLogin();
    
    // Set up automatic token refresh check every 5 minutes
    setInterval(async () => {
        if (isTokenExpiringSoon()) {
            await refreshAuthToken();
        }
    }, 300000); // 5 minutes
}

/**
 * Check if current page is a protected page and verify authentication
 */
function checkAuthForProtectedPages() {
    const protectedPages = [
        'dashboard.php', 'profile.php', 'settings.php', 'security.php'
    ];
    
    const currentPage = window.location.pathname.split('/').pop();
    console.log('Current page:', currentPage);
    
    if (protectedPages.includes(currentPage)) {
        console.log('This is a protected page, checking authentication');
        // Check if user is authenticated
        requireAuthentication();
    }
}

/**
 * Handle redirect after login if there's a saved URL
 */
function handleRedirectAfterLogin() {
    const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
    if (redirectAfterLogin && window.location.pathname.includes('index.php')) {
        console.log('Found redirectAfterLogin:', redirectAfterLogin);
        // Check if already logged in
        checkUserAuthentication().then(userData => {
            if (userData) {
                console.log('User is logged in, redirecting to:', redirectAfterLogin);
                // User is logged in, redirect to the saved URL
                sessionStorage.removeItem('redirectAfterLogin');
                window.location.href = redirectAfterLogin;
            } else {
                console.log('User is not logged in, staying on login page');
            }
        });
    }
}

// Debug function for diagnosing authentication problems
function debugAuth() {
    console.group('Auth Debug Info');
    console.log('SessionStorage auth_token:', sessionStorage.getItem('auth_token') ? 'Present' : 'Missing');
    console.log('SessionStorage token_expiry:', sessionStorage.getItem('token_expiry'));
    console.log('SessionStorage refresh_token:', sessionStorage.getItem('refresh_token') ? 'Present' : 'Missing');
    console.log('LocalStorage refresh_token:', localStorage.getItem('refresh_token') ? 'Present' : 'Missing');
    console.log('SessionStorage user_id:', sessionStorage.getItem('user_id'));
    console.log('LocalStorage user_id:', localStorage.getItem('user_id'));
    console.log('Current page:', window.location.pathname);
    console.log('Redirect after login:', sessionStorage.getItem('redirectAfterLogin'));
    console.groupEnd();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing common page functions');
    initCommonPageFunctions();
    
    // Debug authentication status (will be visible in browser console)
    debugAuth();
});