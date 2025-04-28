/**
 * KDJ Lanka - Common JavaScript Utilities
 * -----------------------------------
 * This file contains common JavaScript utility functions used across the KDJ Lanka website.
 */

// API configuration
const API_BASE_URL = 'https://auth.kdj.lk/api/v1';
// Default redirect destination if no valid redirect_uri is provided
const DEFAULT_DASHBOARD_URL = 'dashboard.php';

/**
 * Display a toast notification
 *
 * @param {string} message - The message to display
 * @param {string} type - The type of toast: 'success', 'error', or 'info'
 * @param {number} duration - Duration in milliseconds to show the toast
 */
function showToast(message, type = 'success', duration = 5000) {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        // Create toast container if it doesn't exist
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-[100]'; // Ensure high z-index
        document.body.appendChild(container);
        toastContainer = container;
    }

    const toast = document.createElement('div');
    toast.className = `mb-3 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'} transform transition-all duration-300 translate-x-full max-w-sm`; // Added max-w-sm

    const iconClass = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconClass} mr-3"></i>
            <p class="flex-1">${sanitizeHTML(message)}</p>
             <button class="ml-2 text-xl leading-none" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;

    toastContainer.appendChild(toast);

    // Show toast with slide-in animation
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full');
    });

    // Auto-remove toast after duration
    const timeoutId = setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300); // Wait for fade out transition
    }, duration);

    // Allow manual dismissal
    toast.addEventListener('click', () => {
       clearTimeout(timeoutId);
       toast.classList.add('opacity-0', 'translate-x-full');
       setTimeout(() => {
            toast.remove();
       }, 300);
    });
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
        loadingIndicator.className = 'fixed top-0 left-0 w-full h-full flex items-center justify-center bg-white bg-opacity-80 z-[200]'; // Ensure very high z-index
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

    // Use 'si-LK' locale for Sinhala formatting if needed, otherwise 'en-US'
    const locale = document.documentElement.lang === 'si' ? 'si-LK' : 'en-US';
    try {
        return date.toLocaleString(locale, options);
    } catch (e) {
        // Fallback to en-US if locale is not supported
        return date.toLocaleString('en-US', options);
    }
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
 * Make an API request with proper error handling and auth
 *
 * @param {string} endpoint - The API endpoint (will be appended to API_BASE_URL)
 * @param {object} options - Fetch options (method, headers, body)
 * @returns {Promise<Response>} - Promise that resolves to the Fetch API Response object
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    // Add auth token if available
    const authToken = localStorage.getItem('sessionToken'); // Using localStorage now
    if (authToken) {
        defaultHeaders['Authorization'] = `Bearer ${authToken}`;
    }

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...(options.headers || {}),
        },
        // credentials: 'include' // Only needed if using httpOnly cookies set by backend
    };

    // Add request timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
    config.signal = controller.signal;

    try {
        const response = await fetch(url, config);
        clearTimeout(timeoutId);

        // Handle 401 Unauthorized (e.g., token expired) - Basic handling
        if (response.status === 401 && endpoint !== '/auth/login' && endpoint !== '/auth/register') {
             console.warn("API request returned 401 Unauthorized. Clearing token and redirecting to login.");
             handleLogout(); // Use logout function to clear session and redirect
             // Throw an error to stop further processing in the original caller
             throw new Error('Unauthorized: Session expired or invalid.');
        }

        return response; // Return the raw response object

    } catch (error) {
        clearTimeout(timeoutId); // Ensure timeout is cleared on error too
        if (error.name === 'AbortError') {
            console.error(`Request to ${endpoint} timed out.`);
            throw new Error('Request timeout. Please check your internet connection and try again.');
        }
        console.error(`API call to ${endpoint} failed:`, error);
        throw error; // Re-throw the error for the caller to handle
    }
}


/**
 * Sanitize HTML to prevent XSS attacks
 *
 * @param {string} input - The HTML string to sanitize
 * @returns {string} - The sanitized HTML string
 */
function sanitizeHTML(input) {
    if (typeof input !== 'string') return '';
    const temp = document.createElement('div');
    temp.textContent = input;
    return temp.innerHTML;
}


/**
 * Validate the redirect URI to ensure it's a safe, allowed KDJ domain.
 *
 * @param {string} uri - The redirect URI to validate.
 * @returns {boolean} - True if the URI is valid and allowed, false otherwise.
 */
function isValidKdjRedirectUri(uri) {
    if (!uri || typeof uri !== 'string') {
        return false;
    }
    try {
        const url = new URL(uri);

        // 1. Check Protocol: Only allow HTTPS
        if (url.protocol !== 'https:') {
            console.warn(`Invalid redirect URI: Protocol is not HTTPS (${uri})`);
            return false;
        }

        // 2. Check Allowed Domains/Subdomains: Must end with .kdj.lk or be kdj.lk
        const allowedSuffix = '.kdj.lk';
        const allowedDomain = 'kdj.lk';
        const hostname = url.hostname.toLowerCase();

        if (hostname !== allowedDomain && !hostname.endsWith(allowedSuffix)) {
            console.warn(`Invalid redirect URI: Hostname (${hostname}) is not allowed.`);
            return false;
        }

        // 3. Optional: Prevent redirecting to the auth domain itself unless specifically needed
        if (hostname === 'auth.kdj.lk' || hostname === 'me.kdj.lk') {
             // Allow redirecting within auth/me subdomains IF the path is safe
             // Add more specific path checks here if needed, otherwise allow
             // console.log(`Redirect URI is within auth domain: ${uri}`);
             // return true; // Or add path validation
             return true; // Allowing redirection within auth/me for now
        }


        // 4. Optional: Basic path check (e.g., ensure it doesn't contain potentially harmful patterns)
        // Example: Disallow data: or javascript: in the path (though URL constructor might handle some cases)
        if (url.pathname.includes(':') || url.search.includes(':')) {
             console.warn(`Invalid redirect URI: Potentially unsafe characters in path/query (${uri})`);
             return false;
        }


        console.log(`Validated redirect URI: ${uri}`);
        return true; // Passed all checks

    } catch (e) {
        // Invalid URL format
        console.warn(`Invalid redirect URI: Parsing failed (${uri})`, e);
        return false;
    }
}

/**
 * Handles actions after a successful login.
 * Stores tokens and redirects the user appropriately.
 *
 * @param {object} loginData - The response data from the /auth/login API call.
 * Expected to contain access_token, refresh_token, etc.
 * @param {string|null} redirectUri - The potential redirect URI from query params.
 */
function handleLoginSuccess(loginData, redirectUri) {
    if (!loginData || !loginData.access_token) {
        console.error("handleLoginSuccess called with invalid data", loginData);
        showToast('Login process failed. Please try again.', 'error');
        return;
    }

    console.log("Login successful. Storing tokens...");
    // Store tokens (Using localStorage for simplicity, consider secure alternatives)
    localStorage.setItem('sessionToken', loginData.access_token);
    if (loginData.refresh_token) {
        localStorage.setItem('refreshToken', loginData.refresh_token); // Store refresh token
    }
     if (loginData.user_id) {
        localStorage.setItem('user_id', loginData.user_id); // Store user ID if needed
    }
    // Optional: Store expiry time for proactive refresh (if needed)
    // if (loginData.expires_in) {
    //     const expiryTime = Date.now() + (loginData.expires_in * 1000);
    //     localStorage.setItem('tokenExpiry', expiryTime.toString());
    // }

    // Determine redirect target
    let targetUrl = DEFAULT_DASHBOARD_URL; // Default
    if (isValidKdjRedirectUri(redirectUri)) {
        targetUrl = redirectUri;
        console.log(`Redirecting to provided valid URI: ${targetUrl}`);
    } else {
        console.log(`No valid redirect URI provided or validation failed. Redirecting to default: ${targetUrl}`);
    }

    // Perform the redirect
    window.location.href = targetUrl;
}


// --- Other existing utility functions like validatePasswordStrength, formatDate etc. ---
// --- Keep the rest of your existing functions here ---


/**
 * Check if user is logged in by trying to fetch profile
 * (Assumes apiRequest handles token attachment)
 *
 * @returns {Promise<object|null>} - The user data if logged in, null otherwise
 */
async function checkUserAuthentication() {
    // Don't try to check auth on login/register pages themselves
    const currentPath = window.location.pathname.split('/').pop();
    if (['', 'index.php', 'login.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'verify-email.php'].includes(currentPath)) {
        return null; // Assume not authenticated for these pages
    }

     if (!localStorage.getItem('sessionToken')) {
        console.log("No session token found in localStorage.");
        return null;
    }


    try {
        const response = await apiRequest('/users/me', { method: 'GET' });

        if (!response.ok) {
             if (response.status === 401) {
                 console.log("Auth check failed (401). Token likely invalid/expired.");
                 // Optionally clear stored tokens here if needed, though apiRequest might handle redirection
                 localStorage.removeItem('sessionToken');
                 localStorage.removeItem('refreshToken');
                 localStorage.removeItem('user_id');
             } else {
                console.error(`Authentication check failed with status: ${response.status}`);
             }
            return null; // Not authenticated or error occurred
        }

        const userData = await response.json();
        console.log("User is authenticated.", userData);
        return userData; // Authenticated

    } catch (error) {
        // Network errors or errors thrown by apiRequest (like timeout or 401 handling)
        console.error('Authentication check threw an error:', error);
        return null; // Assume not authenticated on error
    }
}

/**
 * Redirect user to login page if not authenticated.
 * Stores the current page URL to redirect back after login.
 */
async function requireAuthentication() {
    showLoading(); // Show loading while checking auth
    const userData = await checkUserAuthentication();
    hideLoading();

    if (!userData) {
        console.log("User not authenticated. Redirecting to login.");
        // Save current URL to redirect back after login
        // Use the DEFAULT_DASHBOARD_URL as fallback if current page is not suitable
         const currentUrl = window.location.href;
         const redirectBackUrl = currentUrl.includes('index.php') || currentUrl.endsWith('/') ? DEFAULT_DASHBOARD_URL : currentUrl;

        // We need to pass the intended redirect *back* URL as the 'redirect_uri' *to* the login page
        const loginUrl = `index.php?redirect_uri=${encodeURIComponent(redirectBackUrl)}`;

        window.location.href = loginUrl;
        return null; // Indicate redirection is happening
    }
    return userData; // Return user data if authenticated
}


/**
 * Handle logout
 */
async function handleLogout() {
    showLoading();
    const token = localStorage.getItem('sessionToken');

    try {
        // Attempt to call the logout endpoint, even if token is already invalid locally
        // The backend should handle invalid tokens gracefully during logout
        await apiRequest('/auth/logout', { method: 'POST' });
        console.log("Logout API call successful or handled.");

    } catch (error) {
        // Log error but proceed with local cleanup
        console.error('Logout API call failed, but proceeding with local cleanup:', error);
    } finally {
        // ALWAYS clear local storage regardless of API call success/failure
        localStorage.removeItem('sessionToken');
        localStorage.removeItem('refreshToken');
        localStorage.removeItem('user_id');
        localStorage.removeItem('tokenExpiry'); // Clear expiry if you were storing it
        sessionStorage.clear(); // Clear session storage too, just in case

        console.log("Local session cleared. Redirecting to login page.");
        showToast('ඔබව සාර්ථකව ඉවත් කරන ලදී.', 'success', 2000); // You have been logged out successfully.

        // Redirect to login page
        setTimeout(() => {
            window.location.href = 'index.php'; // Redirect to the main login page
             hideLoading();
        }, 1500); // Short delay for toast message
    }
}


// --- Additions for Security Page (if applicable, kept from original) ---
/**
 * Update the security score in the security page
 *
 * @param {object} userData - The user data
 * @param {HTMLElement} scoreBar - The score bar element
 * @param {HTMLElement} scoreText - The score text element
 * @param {HTMLElement} scoreMessage - The score message element
 */
function updateSecurityScore(userData, scoreBar, scoreText, scoreMessage) {
    if (!userData || !scoreBar || !scoreText || !scoreMessage) return; // Basic check

    let score = 0;
    let totalItems = 2; // Start with Email verified, Assume strong password initially

    if (userData.email_verified) score++;
    score++; // Assume strong password (can't verify strength here)

    // Check for MFA
    if (userData.custom_claims && userData.custom_claims.mfa_enabled) {
        score++;
        totalItems++; // Only count MFA if it's a possibility
    } else if ('mfa_enabled' in userData && userData.mfa_enabled) {
         score++;
         totalItems++;
    }


    const scorePercentage = totalItems > 0 ? Math.round((score / totalItems) * 100) : 0;
    scoreBar.style.width = `${scorePercentage}%`;
    scoreText.textContent = `${scorePercentage}%`;

    if (scorePercentage < 70) {
        scoreBar.className = 'bg-yellow-400 h-2.5 rounded-full transition-all duration-500';
        scoreMessage.textContent = 'ඔබගේ ගිණුමේ ආරක්ෂාව වැඩි දියුණු කළ හැක. වඩා හොඳ ආරක්ෂාවක් සඳහා two-factor authentication සක්‍රිය කරන්න.'; // Your account security can be improved...
    } else if (scorePercentage === 100) {
         scoreBar.className = 'bg-green-500 h-2.5 rounded-full transition-all duration-500';
         scoreMessage.textContent = 'ඉතා හොඳයි! ඔබගේ ගිණුමේ නිර්දේශිත සියලුම ආරක්ෂක විශේෂාංග සක්‍රීය කර ඇත.'; // Great job!...
    } else {
         scoreBar.className = 'bg-blue-500 h-2.5 rounded-full transition-all duration-500';
         scoreMessage.textContent = 'ඔබගේ ගිණුම හොඳින් සුරක්ෂිතයි. Two-factor authentication සලකා බලන්න.'; // Your account is well secured...
    }
}


// --- Initialization and Timers (kept from original, ensure they use localStorage if needed) ---
// Removed automatic token refresh interval - rely on 401 handling or check on page load/API call
// Removed CSRF handling using sessionStorage - use backend CSRF protection if needed

document.addEventListener('DOMContentLoaded', function() {
    // Add logout functionality to logout buttons/links
    const logoutButtons = document.querySelectorAll('.logout-button'); // Add class="logout-button" to your logout links/buttons
    logoutButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            handleLogout();
        });
    });

    // Initial auth check for protected pages (if needed immediately)
    // Example: If on dashboard.php, run requireAuthentication()
    // if (window.location.pathname.includes('dashboard.php')) {
    //    requireAuthentication();
    // }
});