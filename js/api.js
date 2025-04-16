/**
 * KDJ Auth - API Client
 * 
 * Handles all API calls to the backend
 */

const API_BASE_URL = 'https://auth.kdj.lk/api/v1';

const api = {
   /**
     * Perform a fetch request with common settings
     * @param {string} endpoint - API endpoint
     * @param {string} method - HTTP method
     * @param {object} data - Request data (for POST, PUT, etc.)
     * @param {boolean} includeAuth - Whether to include auth token
     * @returns {Promise} - Fetch promise
     */
   async fetchWithAuth(endpoint, method = 'GET', data = null, includeAuth = true) {
    const url = `${API_BASE_URL}${endpoint}`;
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'include' // Include cookies
    };
    
    // Add auth token if needed
    if (includeAuth) {
        const token = auth.getToken();
        if (token) {
            options.headers['Authorization'] = `Bearer ${token}`;
            console.log(`Adding Authorization header for ${endpoint}`);
        } else if (endpoint === '/auth/mfa/verify') {
            // Special case for MFA verification - try to get token from localStorage
            const pendingToken = localStorage.getItem('pending_mfa_token');
            if (pendingToken) {
                options.headers['Authorization'] = `Bearer ${pendingToken}`;
                console.log('Using pending MFA token for authorization');
            }
        }
    }
    
    // Add request body for non-GET requests
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    try {
        console.log(`Sending ${method} request to ${url}`);
        const response = await fetch(url, options);
        console.log(`Response status: ${response.status}`);
        
        // For MFA verification, handle 401 specially
        if (response.status === 401 && endpoint === '/auth/mfa/verify') {
            throw new Error('Authentication failed for MFA verification. Please return to login and try again.');
        }
        
        // Check for 401 Unauthorized
        if (response.status === 401) {
            // If refresh token available, try to refresh the token
            if (auth.getRefreshToken() && endpoint !== '/auth/refresh-token') {
                try {
                    await auth.refreshToken();
                    // Retry the original request with new token
                    return this.fetchWithAuth(endpoint, method, data, includeAuth);
                } catch (refreshError) {
                    // If refresh fails, logout user
                    auth.logout();
                    throw new Error('Session expired. Please log in again.');
                }
            } else {
                // No refresh token or refresh endpoint called, logout user
                auth.logout();
                throw new Error('Authentication required. Please log in.');
            }
        }
        
        // For responses with status 204 No Content, return empty object
        if (response.status === 204) {
            return {};
        }
        
        try {
            // Parse response JSON
            const result = await response.json();
            
            // Check for API errors
            if (!response.ok) {
                let errorMessage = result.detail || 'An error occurred';
                throw new Error(errorMessage);
            }
            
            return result;
        } catch (jsonError) {
            // Handle case where response is not JSON
            if (!response.ok) {
                throw new Error(`Request failed with status ${response.status}`);
            }
            return {}; // Return empty object for successful non-JSON responses
        }
    } catch (error) {
        if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
            // Network error
            throw new Error('Network error. Please check your connection.');
        }
        throw error;
    }
},
    
    /**
     * Register a new user
     * @param {object} userData - User registration data
     * @returns {Promise} API response
     */
    async register(userData) {
        return this.fetchWithAuth('/auth/register', 'POST', userData, false);
    },
    
    /**
     * Login a user
     * @param {string} email - User email
     * @param {string} password - User password
     * @param {boolean} rememberMe - Whether to remember the user
     * @returns {Promise} API response with tokens
     */
    async login(email, password, rememberMe = false) {
        return this.fetchWithAuth('/auth/login', 'POST', {
            email,
            password,
            remember_me: rememberMe
        }, false);
    },
    
    /**
     * Logout the current user
     * @returns {Promise} API response
     */
    async logout() {
        return this.fetchWithAuth('/auth/logout', 'POST');
    },
    
    /**
     * Request a password reset
     * @param {string} email - User email
     * @returns {Promise} API response
     */
    async requestPasswordReset(email) {
        return this.fetchWithAuth('/auth/reset-password/request', 'POST', { email }, false);
    },
    
    /**
     * Confirm a password reset
     * @param {string} token - Reset token
     * @param {string} newPassword - New password
     * @returns {Promise} API response
     */
    async confirmPasswordReset(token, newPassword) {
        return this.fetchWithAuth('/auth/reset-password/confirm', 'POST', {
            token,
            new_password: newPassword
        }, false);
    },
    
    /**
     * Verify an email address
     * @param {string} token - Verification token
     * @returns {Promise} API response
     */
    async verifyEmail(token) {
        return this.fetchWithAuth('/auth/verify-email', 'POST', { token }, false);
    },
    
    /**
     * Resend verification email
     * @returns {Promise} API response
     */
    async resendVerificationEmail() {
        return this.fetchWithAuth('/auth/verify-email/resend', 'POST');
    },
    
    /**
     * Fetch user profile
     * @returns {Promise} User profile data
     */
    async fetchProfile() {
        return this.fetchWithAuth('/users/me');
    },
    
    /**
     * Update user profile
     * @param {object} profileData - Updated profile data
     * @returns {Promise} Updated profile
     */
    async updateProfile(profileData) {
        return this.fetchWithAuth('/users/me', 'PUT', profileData);
    },
    
    /**
     * Update user password
     * @param {string} currentPassword - Current password
     * @param {string} newPassword - New password
     * @returns {Promise} API response
     */
    async updatePassword(currentPassword, newPassword) {
        return this.fetchWithAuth('/users/me/password', 'PUT', {
            current_password: currentPassword,
            new_password: newPassword
        });
    },
    
    /**
     * Delete user account
     * @returns {Promise} API response
     */
    async deleteAccount() {
        return this.fetchWithAuth('/users/me', 'DELETE');
    },
    
    /**
     * Set up MFA
     * @returns {Promise} MFA setup data
     */
    async setupMfa() {
        return this.fetchWithAuth('/auth/mfa/setup', 'POST');
    },
    
   /**
     * Verify MFA code
     * @param {string} code - Verification code
     * @param {string} method - MFA method (totp or backup)
     * @returns {Promise} API response
     */
   async verifyMfa(code, method = 'totp') {
    // Normalize the code - remove spaces and hyphens for backup codes
    const normalizedCode = method === 'backup' ? code.replace(/[-\s]/g, '') : code;
    
    try {
        return await this.fetchWithAuth('/auth/mfa/verify', 'POST', {
            code: normalizedCode,
            method
        });
    } catch (error) {
        // Provide more user-friendly error messages
        if (error.message.includes('Invalid')) {
            if (method === 'totp') {
                throw new Error('Invalid verification code. Please check your authenticator app and try again.');
            } else if (method === 'backup') {
                throw new Error('Invalid backup code. Please make sure you entered it correctly.');
            }
        }
        throw error;
    }
},
    
    /**
     * Disable MFA
     * @returns {Promise} API response
     */
    async disableMfa() {
        return this.fetchWithAuth('/auth/mfa/disable', 'POST');
    },
    
    /**
     * Refresh auth token using refresh token
     * @param {string} refreshToken - Refresh token
     * @returns {Promise} New tokens
     */
    async refreshAccessToken(refreshToken) {
        return this.fetchWithAuth('/auth/refresh-token', 'POST', {
            refresh_token: refreshToken
        }, false);
    }
};