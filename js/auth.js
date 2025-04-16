/**
 * KDJ Auth - Authentication Module
 * 
 * Handles authentication state, tokens, and session management
 */

const auth = {
    // Token storage keys
    TOKEN_KEY: 'kdj_auth_token',
    REFRESH_TOKEN_KEY: 'kdj_refresh_token',
    TOKEN_EXPIRY_KEY: 'kdj_token_expiry',
    USER_KEY: 'kdj_user',
    
    /**
     * Store authentication tokens
     * @param {object} authData - Authentication data from API
     */
    setSession(authData) {
        if (!authData || !authData.access_token) {
            console.error('Invalid authentication data');
            return;
        }
        
        // Calculate expiry timestamp
        const expiresAt = authData.expires_in ? 
            new Date().getTime() + (authData.expires_in * 1000) : 
            new Date().getTime() + (30 * 60 * 1000); // Default to 30 minutes
        
        // Store in localStorage if it's available, fall back to sessionStorage
        const storage = window.localStorage || window.sessionStorage;
        
        // Save token data
        storage.setItem(this.TOKEN_KEY, authData.access_token);
        storage.setItem(this.TOKEN_EXPIRY_KEY, expiresAt);
        
        // Save refresh token if available
        if (authData.refresh_token) {
            storage.setItem(this.REFRESH_TOKEN_KEY, authData.refresh_token);
        }
        
        // Parse and store user info from token
        this.parseAndStoreUserFromToken(authData.access_token);
    },
    
    /**
     * Get the current auth token
     * @returns {string|null} The auth token or null if not found/expired
     */
    getToken() {
        const storage = window.localStorage || window.sessionStorage;
        const token = storage.getItem(this.TOKEN_KEY);
        const expiresAt = parseInt(storage.getItem(this.TOKEN_EXPIRY_KEY), 10);
        
        // Check if token is expired
        if (!token || !expiresAt || expiresAt < new Date().getTime()) {
            return null;
        }
        
        return token;
    },
    
    /**
     * Get the refresh token
     * @returns {string|null} The refresh token or null if not found
     */
    getRefreshToken() {
        const storage = window.localStorage || window.sessionStorage;
        return storage.getItem(this.REFRESH_TOKEN_KEY);
    },
    
    /**
     * Check if the user is authenticated
     * @returns {boolean} True if authenticated, false otherwise
     */
    isAuthenticated() {
        return !!this.getToken();
    },
    
    /**
     * Get the user object from storage
     * @returns {object|null} User object or null if not authenticated
     */
    getUser() {
        const storage = window.localStorage || window.sessionStorage;
        const userJson = storage.getItem(this.USER_KEY);
        
        try {
            return userJson ? JSON.parse(userJson) : null;
        } catch (error) {
            console.error('Error parsing user data:', error);
            return null;
        }
    },
    
    /**
     * Clear authentication data and log out
     */
    clearSession() {
        const storage = window.localStorage || window.sessionStorage;
        storage.removeItem(this.TOKEN_KEY);
        storage.removeItem(this.REFRESH_TOKEN_KEY);
        storage.removeItem(this.TOKEN_EXPIRY_KEY);
        storage.removeItem(this.USER_KEY);
    },
    
    /**
     * Parse user info from JWT token and store it
     * @param {string} token - JWT token
     */
    parseAndStoreUserFromToken(token) {
        try {
            // Parse JWT payload
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(atob(base64).split('').map(c => {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
            
            const payload = JSON.parse(jsonPayload);
            
            // Extract user data
            const user = {
                uid: payload.sub,
                email: payload.email,
                email_verified: payload.email_verified,
                roles: payload.roles || [],
                mfa_enabled: payload.mfa_enabled || false
            };
            
            // Store user data
            const storage = window.localStorage || window.sessionStorage;
            storage.setItem(this.USER_KEY, JSON.stringify(user));
            
            return user;
        } catch (error) {
            console.error('Error parsing token:', error);
            return null;
        }
    },
    
    /**
     * Register a new user
     * @param {object} userData - User registration data
     * @returns {Promise} API response
     */
    async register(userData) {
        try {
            return await api.register(userData);
        } catch (error) {
            throw error;
        }
    },
    
    /**
     * Login a user
     * @param {string} email - User email
     * @param {string} password - User password
     * @param {boolean} rememberMe - Whether to remember the user
     * @returns {Promise} Authentication data
     */
    async login(email, password, rememberMe = false) {
        try {
            const authData = await api.login(email, password, rememberMe);
            
            // Don't set the session if MFA is required
            // This will be done after MFA verification
            if (!authData.mfa_required) {
                this.setSession(authData);
            }
            
            return authData;
        } catch (error) {
            throw error;
        }
    },
    
    /**
     * Logout the current user
     * @returns {Promise} Logout result
     */
    async logout() {
        try {
            // Call the API if authenticated
            if (this.isAuthenticated()) {
                await api.logout();
            }
        } catch (error) {
            console.error('Logout error:', error);
            // Continue with local logout even if API call fails
        }
        
        // Clear local session data
        this.clearSession();
        
        return { success: true };
    },
    
     /**
     * Verify MFA during login
     * @param {string} code - The verification code
     * @param {string} method - The MFA method (totp or backup)
     * @returns {Promise} Authentication result
     */
    async verifyMfaLogin(code, method = 'totp') {
        try {
            // Get the pending MFA token
            const pendingToken = localStorage.getItem('pending_mfa_token');
            const userId = localStorage.getItem('pending_mfa_user_id');
            
            if (!pendingToken || !userId) {
                throw new Error('No pending MFA verification found');
            }
            
            // Prepare normalized code (remove formatting from backup codes)
            const normalizedCode = method === 'backup' 
                ? code.replace(/[-\s]/g, '') 
                : code;
            
            try {
                // Temporarily set the token for the API call
                const storage = window.localStorage || window.sessionStorage;
                storage.setItem(this.TOKEN_KEY, pendingToken);
                
                // Call MFA verify endpoint through our API client, which will use the token
                const result = await api.fetchWithAuth('/auth/mfa/verify', 'POST', {
                    code: normalizedCode,
                    method: method
                }, true);
                
                // If successful, set up the full session
                const authData = {
                    access_token: pendingToken,
                    token_type: 'bearer',
                    expires_in: 1800, // 30 minutes
                    user_id: userId,
                    mfa_required: false
                };
                
                // Set the session
                this.setSession(authData);
                
                // Clean up pending MFA data
                localStorage.removeItem('pending_mfa_token');
                localStorage.removeItem('pending_mfa_user_id');
                
                return result;
            } catch (error) {
                console.error('MFA API verification error:', error);
                
                // Specific error messages based on the error
                if (error.message.includes('Invalid') || error.message.includes('verification')) {
                    if (method === 'backup') {
                        throw new Error('Invalid backup code. Please try again or use a different backup code.');
                    } else {
                        throw new Error('Invalid verification code. Please check your authenticator app and try again.');
                    }
                } else if (error.message.includes('401') || error.message.includes('Unauthorized')) {
                    throw new Error('Authentication error. Please try again or return to login.');
                }
                
                throw error;
            }
        } catch (error) {
            console.error('MFA verification error:', error);
            throw error;
        }
    },
    
    /**
     * Refresh the auth token using the refresh token
     * @returns {Promise} New auth data
     */
    async refreshToken() {
        try {
            const refreshToken = this.getRefreshToken();
            
            if (!refreshToken) {
                throw new Error('No refresh token available');
            }
            
            const authData = await api.refreshAccessToken(refreshToken);
            this.setSession(authData);
            
            return authData;
        } catch (error) {
            this.clearSession();
            throw error;
        }
    },
    
    /**
     * Auto-refresh the token before it expires
     */
    setupTokenRefresh() {
        const storage = window.localStorage || window.sessionStorage;
        const expiresAt = parseInt(storage.getItem(this.TOKEN_EXPIRY_KEY), 10);
        
        if (!expiresAt) return;
        
        // Calculate time to refresh (5 minutes before expiry)
        const timeUntilRefresh = expiresAt - new Date().getTime() - (5 * 60 * 1000);
        
        if (timeUntilRefresh <= 0) {
            // Token is already (nearly) expired, refresh now
            this.refreshToken().catch(() => {
                // If refresh fails, redirect to login
                window.location.href = 'index.html?message=session_expired&type=info';
            });
        } else {
            // Set timer to refresh token
            setTimeout(() => {
                this.refreshToken().catch(() => {
                    // If refresh fails, redirect to login
                    window.location.href = 'index.html?message=session_expired&type=info';
                });
            }, timeUntilRefresh);
        }
    }
};

// Setup token refresh timer if authenticated
if (auth.isAuthenticated()) {
    auth.setupTokenRefresh();
}