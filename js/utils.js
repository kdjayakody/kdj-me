/**
 * KDJ Auth - Utility Functions
 */

const utils = {
    /**
     * Format a date string
     * @param {string} dateString - ISO date string
     * @param {boolean} includeTime - Whether to include time in the formatted string
     * @returns {string} Formatted date string
     */
    formatDate(dateString, includeTime = false) {
        if (!dateString) return '';
        
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
        
        return date.toLocaleDateString(undefined, options);
    },
    
    /**
     * Validate an email address
     * @param {string} email - Email address to validate
     * @returns {boolean} True if valid, false otherwise
     */
    isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    },
    
    /**
     * Validate a phone number in E.164 format
     * @param {string} phone - Phone number to validate
     * @returns {boolean} True if valid, false otherwise
     */
    isValidPhone(phone) {
        if (!phone) return true; // Phone is optional
        const re = /^\+[1-9]\d{1,14}$/;
        return re.test(phone);
    },
    
    /**
     * Validate password strength
     * @param {string} password - Password to validate
     * @returns {object} Validation result with valid flag and message
     */
    validatePassword(password) {
        const minLength = 12;
        
        if (!password || password.length < minLength) {
            return {
                valid: false,
                message: `Password must be at least ${minLength} characters long`
            };
        }
        
        // Check for uppercase
        if (!/[A-Z]/.test(password)) {
            return {
                valid: false,
                message: 'Password must contain at least one uppercase letter'
            };
        }
        
        // Check for lowercase
        if (!/[a-z]/.test(password)) {
            return {
                valid: false,
                message: 'Password must contain at least one lowercase letter'
            };
        }
        
        // Check for digits
        if (!/\d/.test(password)) {
            return {
                valid: false,
                message: 'Password must contain at least one digit'
            };
        }
        
        // Check for special characters
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            return {
                valid: false,
                message: 'Password must contain at least one special character'
            };
        }
        
        return { valid: true };
    },
    
    /**
     * Get a URL parameter by name
     * @param {string} name - The parameter name
     * @returns {string|null} The parameter value or null if not found
     */
    getUrlParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    },
    
    /**
     * Show a temporary toast notification
     * @param {string} message - Message to display
     * @param {string} type - Notification type (success, error, info)
     * @param {number} duration - Duration in milliseconds
     */
    showToast(message, type = 'info', duration = 3000) {
        // Check if toast container exists
        let toastContainer = document.getElementById('toast-container');
        
        // Create container if it doesn't exist
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed bottom-4 right-4 z-50';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'mb-3 p-4 rounded-md shadow-lg transform transition-all duration-300 ease-in-out';
        
        // Add color based on type
        switch (type) {
            case 'success':
                toast.className += ' bg-green-50 text-green-800 border border-green-200';
                break;
            case 'error':
                toast.className += ' bg-red-50 text-red-800 border border-red-200';
                break;
            default:
                toast.className += ' bg-blue-50 text-blue-800 border border-blue-200';
        }
        
        // Set message
        toast.textContent = message;
        
        // Add to container
        toastContainer.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.add('translate-y-0');
            toast.classList.remove('translate-y-full', 'opacity-0');
        }, 10);
        
        // Remove after duration
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-full');
            toast.classList.remove('translate-y-0');
            
            // Remove from DOM after animation
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
                
                // Remove container if empty
                if (toastContainer.children.length === 0 && toastContainer.parentNode) {
                    toastContainer.parentNode.removeChild(toastContainer);
                }
            }, 300);
        }, duration);
    },
    
    /**
     * Redirect to a URL with a message parameter
     * @param {string} url - URL to redirect to
     * @param {string} message - Message to pass
     * @param {string} type - Message type (success, error, info)
     */
    redirectWithMessage(url, message, type = 'info') {
        const redirectUrl = new URL(url, window.location.origin);
        redirectUrl.searchParams.set('message', message);
        redirectUrl.searchParams.set('type', type);
        window.location.href = redirectUrl.toString();
    },
    
    /**
     * Check and display message from URL parameters
     */
    checkUrlMessage() {
        const message = this.getUrlParam('message');
        const type = this.getUrlParam('type') || 'info';
        
        if (message) {
            // Handle specific message codes
            switch (message) {
                case 'account_deleted':
                    this.showToast('Your account has been successfully deleted.', 'success');
                    break;
                case 'password_reset':
                    this.showToast('Your password has been reset. You can now log in with your new password.', 'success');
                    break;
                case 'email_verified':
                    this.showToast('Your email has been verified. Thank you!', 'success');
                    break;
                case 'session_expired':
                    this.showToast('Your session has expired. Please log in again.', 'info');
                    break;
                default:
                    this.showToast(decodeURIComponent(message), type);
            }
            
            // Remove message from URL without reloading the page
            const url = new URL(window.location.href);
            url.searchParams.delete('message');
            url.searchParams.delete('type');
            window.history.replaceState({}, document.title, url.toString());
        }
    }
};

// Check for URL messages when page loads
document.addEventListener('DOMContentLoaded', () => {
    utils.checkUrlMessage();
});