/**
 * KDJ Lanka - Common JavaScript Utilities
 * -----------------------------------
 * This file contains common JavaScript utility functions used across the KDJ Lanka website.
 *
 * Key improvements:
 * - Robust apiRequest function with token refresh and retry logic.
 * - Centralized token expiration check and refresh mechanism.
 * - Standardized logout procedure.
 * - Helper functions for UI feedback (toasts, loading indicators).
 */

// API configuration
const API_BASE_URL = 'https://auth.kdj.lk/api/v1'; // Ensure this is your correct API base URL
const LOGIN_PAGE_URL = '/index.php'; // Define your login page URL
const DEFAULT_REDIRECT_AFTER_LOGIN = '/dashboard.php';

/**
 * Display a toast notification.
 *
 * @param {string} message - The message to display.
 * @param {string} type - The type of toast: 'success', 'error', or 'info'. Defaults to 'success'.
 * @param {number} duration - Duration in milliseconds to show the toast. Defaults to 5000.
 */
function showToast(message, type = 'success', duration = 5000) {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50 w-full max-w-xs sm:max-w-sm'; // Added max-width
        document.body.appendChild(container);
        toastContainer = container; // Assign the created container
    }

    const toast = document.createElement('div');
    toast.className = `mb-3 p-4 rounded-lg shadow-xl text-white text-sm break-words ${
        type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } transform transition-all duration-300 translate-x-full opacity-0`;

    const iconClass = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconClass} mr-3 text-lg"></i>
            <p>${sanitizeHTML(message)}</p>
        </div>
    `;

    toastContainer.appendChild(toast);

    // Animate toast in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);

    // Auto-remove toast
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.remove();
            if (toastContainer.children.length === 0) {
                // Optional: remove container if empty, or leave it.
                // toastContainer.remove();
            }
        }, 300); // Allow time for fade-out animation
    }, duration);
}

/**
 * Show a global loading indicator.
 */
function showLoading() {
    let loadingIndicator = document.getElementById('loadingIndicator');
    if (!loadingIndicator) {
        loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loadingIndicator';
        // Ensure styling makes it a full overlay
        loadingIndicator.className = 'fixed top-0 left-0 w-full h-full flex items-center justify-center bg-white bg-opacity-80 z-[100]'; // Higher z-index
        loadingIndicator.innerHTML = '<div class="loader h-16 w-16 border-4 border-gray-200 rounded-full" style="border-top-color: #cb2127; animation: spin 1s linear infinite;"></div>';

        // Add keyframe animation if not already defined (idempotent check)
        if (!document.getElementById('loaderAnimationStyle')) {
            const style = document.createElement('style');
            style.id = 'loaderAnimationStyle';
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .loader { /* Ensure loader class is defined if not using Tailwind for it */
                    display: inline-block;
                    border-style: solid;
                }
            `;
            document.head.appendChild(style);
        }
        document.body.appendChild(loadingIndicator);
    }
    loadingIndicator.style.display = 'flex';
}

/**
 * Hide the global loading indicator.
 */
function hideLoading() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
}

/**
 * Check if the authentication token is expiring soon.
 * @param {number} thresholdMilliseconds - Check if token expires within this threshold (e.g., 5 minutes). Defaults to 300000 (5 minutes).
 * @returns {boolean} True if token is expiring soon or not present, false otherwise.
 */
function isTokenExpiringSoon(thresholdMilliseconds = 300000) {
    const tokenExpiry = sessionStorage.getItem('token_expiry');
    if (!tokenExpiry) return true; // No expiry means we should treat it as expired or needing verification

    return parseInt(tokenExpiry) - thresholdMilliseconds < Date.now();
}

/**
 * Refresh the authentication token using the refresh token.
 * Stores the new access token and its expiry in sessionStorage.
 * Updates the refresh token if a new one is provided by the server.
 *
 * @returns {Promise<boolean>} True if refresh was successful, false otherwise.
 */
async function refreshAuthToken() {
    const currentRefreshToken = localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token');
    if (!currentRefreshToken) {
        console.warn('No refresh token found for refreshing session.');
        return false;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/auth/refresh-token`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ refresh_token: currentRefreshToken }),
            credentials: 'include', // Important for HttpOnly refresh token cookies if used by backend
        });

        if (!response.ok) {
            console.error('Failed to refresh token. Status:', response.status);
            // If refresh token is invalid (e.g., 401/403/422 from refresh endpoint), clear tokens.
            if (response.status === 401 || response.status === 403 || response.status === 422) {
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token_expiry');
                sessionStorage.removeItem('refresh_token');
                localStorage.removeItem('refresh_token');
                localStorage.removeItem('user_id'); // Also clear user_id
                 // No automatic redirect here; let the caller (apiRequest) handle it.
            }
            return false;
        }

        const data = await response.json();

        if (data.access_token) {
            sessionStorage.setItem('auth_token', data.access_token);
            if (data.expires_in) {
                const expiryTime = Date.now() + data.expires_in * 1000;
                sessionStorage.setItem('token_expiry', expiryTime.toString());
            }
            // If a new refresh token is issued, update it where the old one was stored
            if (data.refresh_token) {
                if (localStorage.getItem('refresh_token')) { // If original was in localStorage
                    localStorage.setItem('refresh_token', data.refresh_token);
                } else { // Otherwise, assume it was in sessionStorage
                    sessionStorage.setItem('refresh_token', data.refresh_token);
                }
            }
            console.log('Token refreshed successfully.');
            return true;
        }
        return false;
    } catch (error) {
        console.error('Error during token refresh:', error);
        return false;
    }
}

/**
 * Make an API request with proper error handling, authorization, and token refresh.
 *
 * @param {string} endpoint - The API endpoint (e.g., '/users/me').
 * @param {object} options - Fetch options (method, headers, body, etc.).
 * @param {boolean} includeCredentials - Whether to include credentials (cookies). Defaults to true.
 * @returns {Promise<Response>} Promise that resolves to the API response.
 * @throws {Error} If the request fails irreversibly or times out.
 */
async function apiRequest(endpoint, options = {}, includeCredentials = true) {
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    const currentAuthToken = sessionStorage.getItem('auth_token');
    if (currentAuthToken) {
        defaultHeaders['Authorization'] = `Bearer ${currentAuthToken}`;
    }

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...(options.headers || {}),
        },
    };

    if (includeCredentials) {
        config.credentials = 'include';
    }

    // Check if token needs refresh *before* making the request
    // Exclude login/refresh endpoints from pre-emptive refresh to avoid loops
    if (currentAuthToken && endpoint !== '/auth/refresh-token' && endpoint !== '/auth/login' && endpoint !== '/auth/google-login' && isTokenExpiringSoon()) {
        console.log(`Token for ${endpoint} expiring soon, attempting refresh.`);
        const refreshed = await refreshAuthToken();
        if (refreshed) {
            const newAuthToken = sessionStorage.getItem('auth_token');
            if (newAuthToken) {
                config.headers['Authorization'] = `Bearer ${newAuthToken}`;
            }
        } else if (!sessionStorage.getItem('auth_token')) { // If refresh failed and cleared the token
            console.warn('Session expired after failed refresh attempt. Redirecting to login.');
            sessionStorage.setItem('redirectAfterLogin', window.location.href);
            window.location.href = LOGIN_PAGE_URL;
            throw new Error('Session expired. Please log in again.');
        }
    }

    const controller = new AbortController();
    const timeoutId = setTimeout(() => {
        controller.abort();
        console.error(`Request to ${endpoint} timed out.`);
    }, 30000); // 30-second timeout
    config.signal = controller.signal;

    try {
        let response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        clearTimeout(timeoutId);

        if (response.status === 401 && endpoint !== '/auth/refresh-token' && endpoint !== '/auth/login' && endpoint !== '/auth/google-login') {
            console.log(`Received 401 for ${endpoint}, attempting token refresh.`);
            const refreshed = await refreshAuthToken();

            if (refreshed) {
                const newAuthToken = sessionStorage.getItem('auth_token');
                if (newAuthToken) {
                    config.headers['Authorization'] = `Bearer ${newAuthToken}`; // Update header for retry
                }

                console.log(`Retrying request to ${endpoint} with new token.`);
                const retryController = new AbortController(); // New controller for retry
                const retryTimeoutId = setTimeout(() => retryController.abort(), 30000);
                config.signal = retryController.signal;

                response = await fetch(`${API_BASE_URL}${endpoint}`, config); // Retry the request
                clearTimeout(retryTimeoutId);

                if (response.status === 401) {
                    console.warn('Still unauthorized after token refresh. Redirecting to login.');
                    sessionStorage.removeItem('auth_token');
                    sessionStorage.removeItem('token_expiry');
                    sessionStorage.removeItem('refresh_token');
                    localStorage.removeItem('refresh_token');
                    localStorage.removeItem('user_id');
                    sessionStorage.setItem('redirectAfterLogin', window.location.href);
                    window.location.href = LOGIN_PAGE_URL;
                    throw new Error('Session expired. Please log in again.');
                }
            } else {
                console.warn('Token refresh failed. Redirecting to login.');
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token_expiry');
                sessionStorage.removeItem('refresh_token');
                localStorage.removeItem('refresh_token');
                localStorage.removeItem('user_id');
                sessionStorage.setItem('redirectAfterLogin', window.location.href);
                window.location.href = LOGIN_PAGE_URL;
                throw new Error('Session expired. Please log in again.');
            }
        }
        return response;
    } catch (error) {
        clearTimeout(timeoutId); // Ensure timeout is cleared on any error
        if (error.name === 'AbortError') {
            throw new Error('Request timeout. Please check your internet connection and try again.');
        }
        console.error(`API call to ${endpoint} failed:`, error.message);
        throw error; // Re-throw the error to be handled by the caller
    }
}

/**
 * Sanitize HTML to prevent XSS attacks.
 *
 * @param {string} input - The HTML string to sanitize.
 * @returns {string} - The sanitized HTML string.
 */
function sanitizeHTML(input) {
    if (input === null || typeof input === 'undefined') return '';
    const temp = document.createElement('div');
    temp.textContent = input;
    return temp.innerHTML;
}

/**
 * Validate an email address.
 *
 * @param {string} email - The email address to validate.
 * @returns {boolean} - Whether the email is valid.
 */
function isValidEmail(email) {
    if (!email) return false;
    // Regex for basic email validation
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Check if a password meets the strength requirements.
 *
 * @param {string} password - The password to check.
 * @returns {object} - Object with 'valid' (boolean) flag and 'errors' (array of strings).
 */
function validatePasswordStrength(password) {
    const errors = [];
    if (!password) { // Handle empty password case
        errors.push("Password cannot be empty.");
        return { valid: false, errors: errors };
    }

    const minLength = 12; // Example: KDJ Lanka's requirement
    if (password.length < minLength) {
        errors.push(`Password must be at least ${minLength} characters long.`);
    }
    if (!/[A-Z]/.test(password)) {
        errors.push('Password must contain at least one uppercase letter.');
    }
    if (!/[a-z]/.test(password)) {
        errors.push('Password must contain at least one lowercase letter.');
    }
    if (!/\d/.test(password)) {
        errors.push('Password must contain at least one digit.');
    }
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        errors.push('Password must contain at least one special character.');
    }
    return {
        valid: errors.length === 0,
        errors: errors,
    };
}

/**
 * Format a date string for display.
 *
 * @param {string|Date} dateString - The date to format.
 * @param {boolean} includeTime - Whether to include time. Defaults to true.
 * @returns {string} - The formatted date string, or "Never" / "Invalid date".
 */
function formatDate(dateString, includeTime = true) {
    if (!dateString) return 'Never';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return 'Invalid date';

    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    };
    if (includeTime) {
        options.hour = '2-digit';
        options.minute = '2-digit';
        options.second = '2-digit'; // Optional: add seconds
    }
    return date.toLocaleString('en-US', options); // Consider 'si-LK' for Sinhala locale if desired
}

/**
 * Get a greeting based on the time of day.
 *
 * @returns {string} - A greeting appropriate for the current time.
 */
function getTimeBasedGreeting() {
    const hour = new Date().getHours();
    if (hour < 12) {
        return 'සුභ උදෑසනක්'; // Good morning
    } else if (hour < 17) {
        return 'සුභ දහවලක්'; // Good afternoon
    } else {
        return 'සුභ සන්ධ්‍යාවක්'; // Good evening
    }
}


/**
 * Check if a user is currently authenticated by fetching their profile.
 * Does not handle redirection, only returns user data or null.
 *
 * @returns {Promise<object|null>} User data object if authenticated, null otherwise.
 */
async function checkUserAuthentication() {
    try {
        const response = await apiRequest('/users/me', { method: 'GET' });
        if (!response.ok) {
            // apiRequest handles 401 and refresh attempts. If still not ok, auth check fails.
            return null;
        }
        return await response.json();
    } catch (error) {
        // Errors (including "Session expired..." from apiRequest) mean auth check fails.
        console.error('Authentication check failed:', error.message);
        return null;
    }
}

/**
 * Require authentication for a page. If not authenticated, redirects to login.
 * Stores the current URL to redirect back after successful login.
 *
 * @returns {Promise<object|null>} User data if authenticated, or null if redirection occurs.
 */
async function requireAuthentication() {
    const userData = await checkUserAuthentication();
    if (!userData) {
        // If checkUserAuthentication returns null, apiRequest should have handled redirection for auth failures.
        // However, as a fallback or if the page is accessed directly without a token:
        if (!window.location.pathname.includes(LOGIN_PAGE_URL.substring(1))) { // Avoid redirect loop
            sessionStorage.setItem('redirectAfterLogin', window.location.href);
            window.location.href = LOGIN_PAGE_URL;
        }
        return null;
    }
    return userData;
}

/**
 * Handle user logout.
 * Clears local session/storage and calls the logout API endpoint.
 * Redirects to the login page.
 */
async function handleLogout() {
    showLoading();
    try {
        // Attempt to call the backend logout endpoint
        // apiRequest will handle attaching the token.
        // We don't strictly need to await this if we clear client-side tokens regardless.
        await apiRequest('/auth/logout', { method: 'POST' }, false); // Credentials false if logout doesn't rely on cookies
    } catch (error) {
        console.warn('Logout API call failed or was not needed, proceeding with client-side logout:', error.message);
        // Proceed with client-side cleanup even if API call fails
    } finally {
        // Clear all relevant storage
        sessionStorage.removeItem('auth_token');
        sessionStorage.removeItem('token_expiry');
        sessionStorage.removeItem('refresh_token');
        sessionStorage.removeItem('redirectAfterLogin'); // Clear any pending redirect
        sessionStorage.removeItem('csrf_token'); // If you use CSRF tokens from session

        localStorage.removeItem('refresh_token');
        localStorage.removeItem('user_id');
        // Add any other localStorage items to clear

        hideLoading();
        showToast('You have been logged out.', 'info');
        // Redirect to login page
        setTimeout(() => {
            window.location.href = LOGIN_PAGE_URL;
        }, 1500); // Delay for toast visibility
    }
}

/**
 * Generate a simple CSRF-like token (if needed for non-API form posts).
 * For an API-driven app with Bearer tokens, traditional CSRF might be less relevant.
 *
 * @returns {string} A random string.
 */
function generateCSRFToken() {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
}

/**
 * Get a stored CSRF token or generate a new one and store it in sessionStorage.
 *
 * @returns {string} The CSRF token.
 */
function getCSRFToken() {
    let token = sessionStorage.getItem('csrf_token');
    if (!token) {
        token = generateCSRFToken();
        sessionStorage.setItem('csrf_token', token);
    }
    return token;
}


// --- Automatic Token Refresh Setup ---
// This interval checks periodically if the token is expiring and refreshes it.
// This is useful for keeping the session alive during long periods of inactivity on a page.
let tokenRefreshIntervalId = null;

function startAutomaticTokenRefresh() {
    if (tokenRefreshIntervalId) {
        clearInterval(tokenRefreshIntervalId); // Clear existing interval if any
    }
    tokenRefreshIntervalId = setInterval(async () => {
        const authToken = sessionStorage.getItem('auth_token');
        const refreshToken = localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token');

        // Only attempt refresh if both tokens are present and the access token is expiring soon
        if (authToken && refreshToken && isTokenExpiringSoon()) {
            console.log('Automatic token refresh check: Token expiring soon, attempting refresh.');
            await refreshAuthToken();
        } else if (!authToken && refreshToken) {
            // If no access token but a refresh token exists (e.g., after browser restart with "remember me")
            console.log('Automatic token refresh check: No access token, attempting refresh with existing refresh token.');
            await refreshAuthToken();
        }
    }, 4 * 60 * 1000); // Check every 4 minutes (slightly less than 5 min expiry window)
    console.log('Automatic token refresh process started.');
}

function stopAutomaticTokenRefresh() {
    if (tokenRefreshIntervalId) {
        clearInterval(tokenRefreshIntervalId);
        tokenRefreshIntervalId = null;
        console.log('Automatic token refresh process stopped.');
    }
}

// Initialize common functionality on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token if not already set (if your app uses it)
    // getCSRFToken(); // Call it if you have forms that need this.

    // Start automatic token refresh if a refresh token exists,
    // indicating a potentially resumable session.
    if (localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token')) {
        startAutomaticTokenRefresh();
    }

    // Handle redirection after login if 'redirectAfterLogin' is stored
    const redirectTarget = sessionStorage.getItem('redirectAfterLogin');
    // Only redirect if on the login page and a target exists
    if (redirectTarget && window.location.pathname.endsWith(LOGIN_PAGE_URL.substring(1))) {
        // Before redirecting, quickly check if user is actually authenticated now
        checkUserAuthentication().then(user => {
            if (user) {
                sessionStorage.removeItem('redirectAfterLogin');
                window.location.href = redirectTarget;
            }
        });
    }
});

// Example of how you might update the security score (if needed in utils)
// This is just a placeholder, adapt as per your actual security page logic.
/*
function updateSecurityScoreDisplay(userData, scoreBarElement, scoreTextElement, scoreMessageElement) {
    if (!userData || !scoreBarElement || !scoreTextElement || !scoreMessageElement) return;

    let score = 0;
    const totalItems = 3; // Example: Email verified, strong password (assumed), MFA

    if (userData.email_verified) score++;
    // Assuming password strength is handled elsewhere or implied
    score++; // Placeholder for password strength
    if (userData.mfa_enabled) score++;

    const scorePercentage = Math.round((score / totalItems) * 100);
    scoreBarElement.style.width = `${scorePercentage}%`;
    scoreTextElement.textContent = `${scorePercentage}%`;

    if (scorePercentage < 70) {
        scoreBarElement.className = scoreBarElement.className.replace(/bg-\w+-500/, 'bg-yellow-400'); // Adjust color
        scoreMessageElement.textContent = 'Improve your account security. Consider enabling all security features.';
    } else if (scorePercentage === 100) {
        scoreBarElement.className = scoreBarElement.className.replace(/bg-\w+-400/, 'bg-green-500'); // Adjust color
        scoreMessageElement.textContent = 'Excellent! Your account has top-notch security.';
    } else {
         scoreBarElement.className = scoreBarElement.className.replace(/bg-\w+-500/, 'bg-blue-500'); // Adjust color for mid-range
         scoreMessageElement.textContent = 'Good security status. Review any pending recommendations.';
    }
}
*/