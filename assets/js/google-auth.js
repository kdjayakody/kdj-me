/**
 * Google Authentication Utilities
 * 
 * This file provides helper functions for Google Authentication with Firebase
 * and handles common errors and edge cases.
 */

// Google Authentication Configuration
const FIREBASE_PROJECT_ID = 'kdj-lanka';
const API_ENDPOINT = '/api/v1/auth/google-login';
const DEBUG_MODE = true; // Set to false in production

/**
 * Initialize Firebase authentication
 * @returns {Object} Object containing firebaseApp and firebaseAuth instances
 */
function initializeFirebaseAuth() {
    let firebaseApp;
    let firebaseAuth;
    
    try {
        // Check if Firebase is available
        if (typeof firebase === 'undefined') {
            console.error('Firebase SDK not loaded. Make sure to include the Firebase scripts.');
            return null;
        }
        
        // Initialize Firebase with config from the global firebaseConfig variable
        // This config should be set in your main page
        if (typeof firebaseConfig === 'undefined') {
            console.error('Firebase configuration not found. Make sure firebaseConfig is defined.');
            return null;
        }
        
        // Initialize Firebase if not already initialized
        firebaseApp = firebase.apps.length ? 
            firebase.apps[0] : 
            firebase.initializeApp(firebaseConfig);
        
        // Get Firebase Auth instance
        firebaseAuth = firebase.auth(firebaseApp);
        
        // Set persistence to SESSION (clears when window/tab closes)
        firebaseAuth.setPersistence(firebase.auth.Auth.Persistence.SESSION)
            .then(() => {
                if (DEBUG_MODE) console.log("Firebase Auth Persistence set to SESSION");
            })
            .catch((error) => {
                console.error("Error setting auth persistence:", error);
            });
            
        if (DEBUG_MODE) console.log("Firebase Auth initialized successfully");
        
        return { firebaseApp, firebaseAuth };
    } catch (e) {
        console.error("Error initializing Firebase Auth:", e);
        return null;
    }
}

/**
 * Handle Google Sign-In with detailed error handling
 * @param {Object} firebaseAuth - Firebase Auth instance
 * @param {Function} onSuccess - Success callback
 * @param {Function} onError - Error callback
 * @param {Function} onLoading - Loading state callback
 */
async function handleGoogleSignIn(firebaseAuth, onSuccess, onError, onLoading) {
    if (!firebaseAuth) {
        onError('Google authentication could not be initialized. Please try again later.');
        return;
    }

    onLoading(true);

    try {
        // Create a Google Auth Provider with explicit scopes
        const provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        provider.addScope('profile');
        
        // Set custom parameters for better UX
        provider.setCustomParameters({
            // Forces account selection even when one account is available
            prompt: 'select_account',
            // Specify Firebase project domain for better security
            auth_domain: `${FIREBASE_PROJECT_ID}.firebaseapp.com`
        });

        // First try the popup method
        try {
            const result = await firebaseAuth.signInWithPopup(provider);
            handleAuthResult(result, onSuccess, onError);
        } catch (popupError) {
            console.error("Google Sign-In Popup Error:", popupError);
            
            // If popup fails, try redirect method for specific errors
            if (shouldUseRedirectMethod(popupError.code)) {
                onError('Attempting redirect method instead...', 'info');
                
                // Save that we're attempting redirect auth
                sessionStorage.setItem('auth_redirect_attempt', 'true');
                
                // Use redirect method instead
                try {
                    await firebaseAuth.signInWithRedirect(provider);
                } catch (redirectError) {
                    console.error("Redirect auth fallback error:", redirectError);
                    onError(getErrorMessage(redirectError));
                }
            } else {
                // For other errors, show appropriate message
                onError(getErrorMessage(popupError));
            }
        }
    } catch (error) {
        console.error("Google Sign-In Error:", error);
        onError(getErrorMessage(error));
        onLoading(false);
    }
}

/**
 * Handle redirect result from Google Sign-In
 * @param {Object} firebaseAuth - Firebase Auth instance
 * @param {Function} onSuccess - Success callback
 * @param {Function} onError - Error callback 
 * @param {Function} onLoading - Loading state callback
 */
function handleRedirectResult(firebaseAuth, onSuccess, onError, onLoading) {
    if (!firebaseAuth || !sessionStorage.getItem('auth_redirect_attempt')) {
        return;
    }
    
    // Clear the flag
    sessionStorage.removeItem('auth_redirect_attempt');
    
    onLoading(true);
    
    firebaseAuth.getRedirectResult()
        .then(result => {
            handleAuthResult(result, onSuccess, onError);
        })
        .catch(error => {
            console.error("Redirect result error:", error);
            onError(getErrorMessage(error));
            onLoading(false);
        });
}

/**
 * Process the authentication result
 * @param {Object} result - Auth result from Firebase
 * @param {Function} onSuccess - Success callback
 * @param {Function} onError - Error callback
 */
async function handleAuthResult(result, onSuccess, onError) {
    if (result && result.user) {
        try {
            // Get the ID token
            const idToken = await result.user.getIdToken(true);
            
            // Call the success handler with the token
            onSuccess(idToken);
        } catch (tokenError) {
            console.error("Error getting ID token:", tokenError);
            onError('Failed to get authentication token. Please try again.');
        }
    } else {
        onError('Authentication failed. Please try again.');
    }
}

/**
 * Determine if redirect method should be used based on error code
 * @param {string} errorCode - Firebase error code
 * @returns {boolean} Whether to use redirect method
 */
function shouldUseRedirectMethod(errorCode) {
    const redirectErrors = [
        'auth/internal-error',
        'auth/popup-blocked',
        'auth/popup-closed-by-user',
        'auth/cancelled-popup-request',
        'auth/browser-not-supported'
    ];
    
    return redirectErrors.includes(errorCode);
}

/**
 * Get user-friendly error message for Firebase Auth errors
 * @param {Object} error - Error object
 * @returns {string} User-friendly error message
 */
function getErrorMessage(error) {
    const errorCode = error.code || '';
    
    // English error messages (can be translated as needed)
    const errorMessages = {
        'auth/popup-closed-by-user': 'The sign-in popup was closed before completing the sign in. Please try again.',
        'auth/popup-blocked': 'The sign-in popup was blocked by your browser. Please allow popups for this website and try again.',
        'auth/cancelled-popup-request': 'Multiple popup requests were detected. Please try again.',
        'auth/network-request-failed': 'A network error occurred. Please check your internet connection and try again.',
        'auth/internal-error': 'An internal error occurred. Please try again later.',
        'auth/timeout': 'The operation timed out. Please try again.',
        'auth/user-token-expired': 'Your session has expired. Please sign in again.',
        'auth/web-storage-unsupported': 'Your browser does not support web storage. Please try a different browser.',
        'auth/invalid-credential': 'The authentication credential is invalid. Please try again.',
        'auth/user-disabled': 'This account has been disabled. Please contact support.',
        'auth/account-exists-with-different-credential': 'An account already exists with the same email address but different sign-in credentials. Please sign in using the original method.',
        'auth/operation-not-allowed': 'This sign-in method is not allowed. Please contact support.',
        'auth/requires-recent-login': 'This operation requires recent authentication. Please sign in again.',
        'auth/user-not-found': 'No account found with this email. Please check your email or register.',
        'auth/invalid-email': 'The email address is invalid. Please enter a valid email.'
    };
    
    // Return specific message or fallback to generic message
    return errorMessages[errorCode] || 
           error.message || 
           'An unexpected error occurred. Please try again later.';
}

/**
 * Determine if browser environment supports Google Sign-In
 * @returns {Object} Object with supported flag and reason if not supported
 */
function checkBrowserSupport() {
    const result = { supported: true, reason: '' };
    
    // Check for localStorage/sessionStorage support
    if (!window.localStorage || !window.sessionStorage) {
        result.supported = false;
        result.reason = 'Your browser does not support web storage, which is required for authentication.';
        return result;
    }
    
    // Check for cookies enabled
    if (!navigator.cookieEnabled) {
        result.supported = false;
        result.reason = 'Please enable cookies to use Google authentication.';
        return result;
    }
    
    // Check for private browsing mode in Safari
    try {
        localStorage.setItem('test', 'test');
        localStorage.removeItem('test');
    } catch (e) {
        result.supported = false;
        result.reason = 'Your browser may be in private browsing mode. Please try in a normal browsing window.';
        return result;
    }
    
    // Check if in a cross-origin iframe
    try {
        if (window.self !== window.top) {
            // We're in an iframe
            result.supported = false;
            result.reason = 'Google Sign-In is not supported in embedded frames.';
            return result;
        }
    } catch (e) {
        // If accessing window.top throws, we're in a cross-origin iframe
        result.supported = false;
        result.reason = 'Google Sign-In is not supported in embedded frames.';
        return result;
    }
    
    return result;
}

/**
 * Log detailed diagnostics for authentication issues
 * @param {Object} error - Error object
 */
function logAuthDiagnostics(error) {
    if (!DEBUG_MODE) return;
    
    console.group('Google Auth Diagnostics');
    console.log('Error Object:', error);
    console.log('User Agent:', navigator.userAgent);
    console.log('Browser Supports Cookies:', navigator.cookieEnabled);
    console.log('Firebase SDK Version:', firebase.SDK_VERSION);
    
    // Check for common issues
    console.log('In iframe:', window.self !== window.top);
    console.log('Local/Session Storage available:', !!window.localStorage && !!window.sessionStorage);
    
    // Check for popup blocker
    try {
        const testPopup = window.open('about:blank', '_blank', 'width=100,height=100');
        if (!testPopup || testPopup.closed || typeof testPopup.closed === 'undefined') {
            console.log('Popup blocker detected: Yes');
        } else {
            console.log('Popup blocker detected: No');
            testPopup.close();
        }
    } catch (e) {
        console.log('Popup test error:', e);
    }
    
    console.log('Current URL:', window.location.href);
    console.log('Referrer:', document.referrer);
    console.groupEnd();
}

// Export utilities for use in main application
window.GoogleAuth = {
    initialize: initializeFirebaseAuth,
    signIn: handleGoogleSignIn,
    handleRedirect: handleRedirectResult,
    checkSupport: checkBrowserSupport,
    logDiagnostics: logAuthDiagnostics
};