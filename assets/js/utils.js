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
    if (!toastContainer) {
        // Create toast container if it doesn't exist
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50';
        document.body.appendChild(container);
        toastContainer = container;
    }
    
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
 * Make an API request with proper error handling
 * 
 * @param {string} endpoint - The API endpoint (will be appended to API_BASE_URL)
 * @param {object} options - Fetch options (method, headers, body)
 * @param {boolean} includeCredentials - Whether to include credentials (cookies)
 * @returns {Promise} - Promise that resolves to the API response
 */
async function apiRequest(endpoint, options = {}, includeCredentials = true) {
    try {
        const defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        
        // Add auth token to headers if available
        const authToken = sessionStorage.getItem('auth_token');
        if (authToken) {
            defaultHeaders['Authorization'] = `Bearer ${authToken}`;
        }
        
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
            await refreshAuthToken();
            
            // Update authorization header with new token
            const newToken = sessionStorage.getItem('auth_token');
            if (newToken) {
                config.headers['Authorization'] = `Bearer ${newToken}`;
            }
        }
        
        // Add request timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
        config.signal = controller.signal;
        
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        clearTimeout(timeoutId); // Clear timeout on successful response
        
        // Handle unauthorized error by trying to refresh token once
        if (response.status === 401 && endpoint !== '/auth/refresh-token' && endpoint !== '/auth/login') {
            // Try to refresh the token
            const refreshed = await refreshAuthToken();
            
            if (refreshed) {
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
                
                if (retryResponse.status === 401) {
                    // Still unauthorized after token refresh, redirect to login
                    sessionStorage.removeItem('auth_token');
                    sessionStorage.removeItem('token_expiry');
                    sessionStorage.removeItem('refresh_token');
                    
                    // Save current URL to redirect back after login
                    sessionStorage.setItem('redirectAfterLogin', window.location.href);
                    window.location.href = '/index.php';
                    throw new Error('Session expired. Please log in again.');
                }
                
                return retryResponse;
            } else {
                // Token refresh failed, redirect to login
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token_expiry');
                sessionStorage.removeItem('refresh_token');
                
                // Save current URL to redirect back after login
                sessionStorage.setItem('redirectAfterLogin', window.location.href);
                window.location.href = '/index.php';
                throw new Error('Session expired. Please log in again.');
            }
        }
        
        return response;
    } catch (error) {
        if (error.name === 'AbortError') {
            throw new Error('Request timeout. Please check your internet connection and try again.');
        }
        
        console.error(`API call to ${endpoint} failed:`, error);
        throw error;
    }
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
    const refreshToken = sessionStorage.getItem('refresh_token');
    if (!refreshToken) return false;
    
    try {
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
            
            // Update expiry time
            if (data.expires_in) {
                const expiryTime = Date.now() + (data.expires_in * 1000);
                sessionStorage.setItem('token_expiry', expiryTime.toString());
            }
            
            // Store refresh token if provided
            if (data.refresh_token) {
                sessionStorage.setItem('refresh_token', data.refresh_token);
            }
            
            return true;
        }
        
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
        // Save current URL to redirect back after login
        sessionStorage.setItem('redirectAfterLogin', window.location.href);
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
        
        // Clear session storage regardless of response
        sessionStorage.removeItem('auth_token');
        sessionStorage.removeItem('token_expiry');
        sessionStorage.removeItem('refresh_token');
        localStorage.removeItem('user_id');
        
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
        
        // Clear session storage anyway
        sessionStorage.removeItem('auth_token');
        sessionStorage.removeItem('token_expiry');
        sessionStorage.removeItem('refresh_token');
        localStorage.removeItem('user_id');
        
        showToast('Network error during logout. You have been logged out locally.', 'warning');
        
        // Redirect to login page
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000);
        
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

// Set up automatic token refresh check every 5 minutes
setInterval(async () => {
    if (isTokenExpiringSoon()) {
        await refreshAuthToken();
    }
}, 300000); // 5 minutes

/**
 * Handle cross-domain authentication
 * This function checks for authentication tokens and ensures they work across domains
 * 
 * @param {boolean} forceRedirect - Whether to force redirect to dashboard if authenticated
 * @returns {Promise<boolean>} - Whether the user is authenticated
 */
async function handleCrossDomainAuth(forceRedirect = false) {
    try {
        // Check for tokens in storage
        const authToken = sessionStorage.getItem('auth_token');
        const refreshToken = localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token');
        
        if (!authToken && !refreshToken) {
            return false; // No tokens available
        }
        
        // If we have an auth token, verify it
        if (authToken) {
            try {
                // Use our auth_handler to proxy the request (avoids CORS issues)
                const response = await fetch('/auth_handler.php?action=verify', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    }
                });
                
                if (response.ok) {
                    // Token is valid
                    const userData = await response.json();
                    
                    // If redirect is needed, handle it
                    if (forceRedirect && window.location.pathname.includes('index.php')) {
                        const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
                        if (redirectAfterLogin) {
                            sessionStorage.removeItem('redirectAfterLogin');
                            window.location.href = redirectAfterLogin;
                        } else {
                            window.location.href = 'dashboard.php';
                        }
                    }
                    
                    return true;
                }
                
                // If token validation failed, try to refresh
                if (refreshToken) {
                    const refreshResponse = await fetch('/auth_handler.php?action=refresh', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ refresh_token: refreshToken })
                    });
                    
                    if (refreshResponse.ok) {
                        const data = await refreshResponse.json();
                        if (data.access_token) {
                            // Store the new token
                            sessionStorage.setItem('auth_token', data.access_token);
                            
                            // Update expiry time
                            if (data.expires_in) {
                                const expiryTime = Date.now() + (data.expires_in * 1000);
                                sessionStorage.setItem('token_expiry', expiryTime.toString());
                            }
                            
                            // Store refresh token if provided
                            if (data.refresh_token) {
                                if (localStorage.getItem('refresh_token')) {
                                    localStorage.setItem('refresh_token', data.refresh_token);
                                } else {
                                    sessionStorage.setItem('refresh_token', data.refresh_token);
                                }
                            }
                            
                            // If redirect is needed, handle it
                            if (forceRedirect && window.location.pathname.includes('index.php')) {
                                const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
                                if (redirectAfterLogin) {
                                    sessionStorage.removeItem('redirectAfterLogin');
                                    window.location.href = redirectAfterLogin;
                                } else {
                                    window.location.href = 'dashboard.php';
                                }
                            }
                            
                            return true;
                        }
                    }
                }
            } catch (error) {
                console.error('Auth check error:', error);
            }
        } else if (refreshToken) {
            // No auth token but we have a refresh token, try to use it
            try {
                const refreshResponse = await fetch('/auth_handler.php?action=refresh', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ refresh_token: refreshToken })
                });
                
                if (refreshResponse.ok) {
                    const data = await refreshResponse.json();
                    if (data.access_token) {
                        // Store the new token
                        sessionStorage.setItem('auth_token', data.access_token);
                        
                        // Update expiry time
                        if (data.expires_in) {
                            const expiryTime = Date.now() + (data.expires_in * 1000);
                            sessionStorage.setItem('token_expiry', expiryTime.toString());
                        }
                        
                        // Store refresh token if provided
                        if (data.refresh_token) {
                            if (localStorage.getItem('refresh_token')) {
                                localStorage.setItem('refresh_token', data.refresh_token);
                            } else {
                                sessionStorage.setItem('refresh_token', data.refresh_token);
                            }
                        }
                        
                        // If redirect is needed, handle it
                        if (forceRedirect && window.location.pathname.includes('index.php')) {
                            const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
                            if (redirectAfterLogin) {
                                sessionStorage.removeItem('redirectAfterLogin');
                                window.location.href = redirectAfterLogin;
                            } else {
                                window.location.href = 'dashboard.php';
                            }
                        }
                        
                        return true;
                    }
                }
            } catch (error) {
                console.error('Refresh token error:', error);
            }
        }
    } catch (error) {
        console.error('Cross-domain auth error:', error);
    }
    
    return false;
}

// Initialize common functionality
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token if not already set
    if (!sessionStorage.getItem('csrf_token')) {
        sessionStorage.setItem('csrf_token', generateCSRFToken());
    }
    
    // Redirect to appropriate page after login if specified
    const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
    if (redirectAfterLogin && window.location.pathname.includes('index.php')) {
        // Check if already logged in
        handleCrossDomainAuth(true).then(isAuthenticated => {
            if (isAuthenticated) {
                // User is logged in, redirect to the saved URL
                sessionStorage.removeItem('redirectAfterLogin');
                window.location.href = redirectAfterLogin;
            }
        });
    } else if (window.location.pathname.includes('index.php')) {
        // Always check for auth on index page
        handleCrossDomainAuth(true);
    } else {
        // Check auth but don't force redirect on other pages
        handleCrossDomainAuth(false);
    }
});