<?php
// index.php (Login Page)

// Page specific variables
$title = "Login";
$description = "Sign in to your KDJ Lanka account to access services and manage your profile.";
$lang = "si"; // Default language for the page

// Add page-specific styles or head elements if necessary
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png'); /* Ensure path is correct */
        background-size: cover;
        background-position: center;
        /* Consider adding a fallback background color if the image fails to load */
        /* background-color: #f0f2f5; */
    }
    .login-card {
        backdrop-filter: blur(8px); /* Slightly adjusted blur */
        background-color: rgba(255, 255, 255, 0.97); /* Very slightly more opaque */
        border: 1px solid rgba(0,0,0,0.05); /* Subtle border */
    }
    /* Custom focus style for inputs, if Tailwind's default needs override */
    .form-input:focus {
        /* border-color: #cb2127; */ /* kdj-red - Tailwind focus:border-kdj-red should handle this */
        /* box-shadow: 0 0 0 3px rgba(203, 33, 39, 0.2); */ /* kdj-red with opacity - Tailwind focus:ring-kdj-red focus:ring-opacity-xx should handle */
    }
    .google-signin-btn:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); /* Enhanced hover shadow */
    }
    #messageArea {
        transition: opacity 0.3s ease-in-out;
    }
    #messageArea.error {
        background-color: #fff5f5; /* Tailwind red-50 */
        border-color: #fecaca; /* Tailwind red-200 */
        color: #991b1b; /* Tailwind red-800 */
    }
    #messageArea.success {
        background-color: #f0fdf4; /* Tailwind green-50 */
        border-color: #bbf7d0; /* Tailwind green-200 */
        color: #166534; /* Tailwind green-800 */
    }
    .input-error-text {
        font-size: 0.875rem; /* text-sm */
        color: #dc2626; /* Tailwind red-600 */
        font-weight: 500; /* medium */
    }
</style>
HTML;

include 'header.php'; // Includes Firebase initialization and global head elements
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="login-card max-w-lg w-full space-y-8 p-8 sm:p-12 bg-white rounded-xl shadow-xl">
        <div class="text-center">
            <a href="/" aria-label="KDJ Lanka Home">
                <img src="/assets/img/kdjcolorlogo.png" alt="KDJ Lanka Logo" class="mx-auto w-36 sm:w-44 mb-5 transition-transform hover:scale-105">
            </a>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-kdj-dark">
                ගිණුමට පිවිසෙන්න
            </h1>
            <p class="mt-3 text-base text-gray-600">
                නැවතත් ඔබව සාදරයෙන් පිළිගනිමු!
            </p>
        </div>

        <div id="messageArea" class="my-5 p-4 rounded-lg text-center font-semibold text-sm hidden border-2" role="alert">
            </div>

        <form id="loginForm" class="mt-8 space-y-6" novalidate>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-800 mb-1.5">ඊමේල් ලිපිනය</label>
                <div class="relative group">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-kdj-red transition-colors">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" required autocomplete="email"
                        class="form-input pl-10 w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:border-kdj-red focus:ring-2 focus:ring-kdj-red focus:ring-opacity-50 transition-shadow sm:text-sm"
                        placeholder="உங்களது மின்னஞ்சல் முகவரி"> <?php // Example Tamil placeholder for i18n thought ?>
                </div>
                <p class="mt-1.5 input-error-text" id="emailError" style="display: none;"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-800 mb-1.5">මුරපදය</label>
                <div class="relative group">
                     <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-kdj-red transition-colors">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="form-input pl-10 w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 pr-10 focus:border-kdj-red focus:ring-2 focus:ring-kdj-red focus:ring-opacity-50 transition-shadow sm:text-sm"
                        placeholder="உங்கள் கடவுச்சொல்"> <?php // Example Tamil placeholder ?>
                    <button type="button" tabindex="-1" aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500 hover:text-kdj-red transition-colors" id="togglePassword">
                        <i class="fas fa-eye text-base"></i>
                    </button>
                </div>
                <p class="mt-1.5 input-error-text" id="passwordError" style="display: none;"></p>
            </div>

            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me"
                        class="h-4 w-4 text-kdj-red focus:ring-kdj-red border-gray-400 rounded cursor-pointer">
                    <label for="remember_me" class="ml-2 block text-gray-700 cursor-pointer">මතක තබාගන්න</label>
                </div>
                <div>
                    <a href="forgot_password.php" class="font-semibold text-kdj-red hover:text-red-700 hover:underline transition-colors">මුරපදය අමතකද?</a>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton"
                    class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-lg shadow-md text-base font-semibold text-white bg-kdj-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red active:bg-red-800 transition-all duration-150 ease-in-out disabled:opacity-60 disabled:cursor-wait">
                    <span id="buttonSpinner" class="hidden animate-spin mr-2"><i class="fas fa-circle-notch"></i></span>
                    <span id="buttonText">ඇතුල් වන්න</span>
                </button>
            </div>
        </form>

        <div class="my-6 flex items-center" aria-hidden="true">
            <div class="border-t border-gray-300 flex-grow"></div>
            <span class="px-3 text-sm font-medium text-gray-500">හෝ</span>
            <div class="border-t border-gray-300 flex-grow"></div>
        </div>

        <div>
            <button type="button" id="googleSignInButton" class="google-signin-btn w-full flex justify-center items-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red active:bg-gray-100 transition-all duration-150 ease-in-out disabled:opacity-60 disabled:cursor-wait">
                <img src="/assets/images/google-logo.svg" alt="Google G Logo" class="w-5 h-5 mr-2.5"> <?php // Using a local or themed Google logo if preferred ?>
                <span id="googleButtonText">Google සමඟින් පිවිසෙන්න</span>
                <span id="googleButtonSpinner" class="hidden animate-spin ml-2"><i class="fas fa-circle-notch"></i></span>
            </button>
        </div>

        <div class="mt-10 text-center text-sm">
            <span class="text-gray-600">ගිණුමක් නැද්ද? </span>
            <a href="register.php" class="font-semibold text-kdj-red hover:text-red-700 hover:underline transition-colors">ලියාපදිංචි වන්න</a>
        </div>
    </div>
</div>

<?php
// $additional_scripts will be echoed in footer.php
// utils.js is included in footer.php before this $additional_scripts block
$additional_scripts = <<<HTML
<script>
// Ensure all functions from utils.js are available (they are if utils.js is loaded)
// e.g., API_BASE_URL, LOGIN_PAGE_URL, DEFAULT_REDIRECT_AFTER_LOGIN,
// apiRequest, checkUserAuthentication, showToast, showLoading, hideLoading,
// isValidEmail, sanitizeHTML, startAutomaticTokenRefresh

document.addEventListener('DOMContentLoaded', async function() {
    // DOM Elements
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberMeInput = document.getElementById('remember_me');
    const messageArea = document.getElementById('messageArea');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const emailErrorEl = document.getElementById('emailError');
    const passwordErrorEl = document.getElementById('passwordError');
    const googleSignInButton = document.getElementById('googleSignInButton');
    const googleButtonText = document.getElementById('googleButtonText');
    const googleButtonSpinner = document.getElementById('googleButtonSpinner');

    // --- 1. Initial Page Load Actions ---
    async function performInitialAuthCheck() {
        showLoading(); // Show global loader from utils.js
        try {
            const authenticatedUser = await checkUserAuthentication(); // From utils.js
            if (authenticatedUser) {
                showGlobalMessage('You are already logged in. Redirecting to your dashboard...', 'success', false); // Don't use toast here
                const redirectTarget = sessionStorage.getItem('redirectAfterLogin') || DEFAULT_REDIRECT_AFTER_LOGIN;
                sessionStorage.removeItem('redirectAfterLogin');
                window.location.href = redirectTarget;
                return true; // Indicates redirection is happening
            }
        } catch (e) {
            // Error during initial check, usually means utils.js->apiRequest failed fundamentally
            // before even getting to user auth logic. Let the user try to login.
            console.error("Initial auth check error:", e);
        } finally {
            hideLoading(); // Hide global loader from utils.js
        }
        return false; // No redirection, login form should be usable
    }

    if (await performInitialAuthCheck()) {
        return; // Stop further script execution if already redirecting
    }

    // Capture and store external redirect URL from query params
    const urlParams = new URLSearchParams(window.location.search);
    const redirectFromExternal = urlParams.get('redirect');
    if (redirectFromExternal) {
        try {
            const decodedRedirect = decodeURIComponent(redirectFromExternal);
            const url = new URL(decodedRedirect); // Validate URL structure
            // Allow only kdj.lk subdomains or specific trusted domains for security
            if (url.hostname.endsWith('.kdj.lk') || url.hostname === 'kdj.lk' || url.hostname === 'localhost') {
                 sessionStorage.setItem('redirectAfterLogin', decodedRedirect);
            } else {
                console.warn('Blocked potentially unsafe redirect from external source:', decodedRedirect);
            }
        } catch (e) {
            console.warn('Invalid redirect URL parameter:', redirectFromExternal, e.message);
        }
    }

    // --- 2. Event Listeners Setup ---
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            const icon = togglePasswordBtn.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
            passwordInput.focus(); // Keep focus on password input
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', handleRegularLogin);
    }

    if (googleSignInButton) {
        if (typeof firebaseAuth !== 'undefined' && firebaseAuth) {
            googleSignInButton.addEventListener('click', handleGoogleSignIn);
            checkFirebaseAuthRedirectResult(); // For Firebase redirect auth flow
        } else {
            setGoogleButtonState(true, 'Google Sign-In N/A');
            console.warn('Firebase Auth is not initialized. Google Sign-In disabled.');
            // showToast('Google Sign-In is currently unavailable.', 'info');
        }
    }

    // --- 3. Core Logic Functions ---
    async function handleRegularLogin(event) {
        event.preventDefault();
        clearGlobalMessages();
        clearInputErrors();

        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const rememberMe = rememberMeInput.checked;

        if (!validateInputs(email, password)) {
            return;
        }

        setSubmitButtonState(true, 'පිවිසෙමින්...');
        showLoading();

        try {
            const response = await apiRequest('/auth/login', { // From utils.js
                method: 'POST',
                body: JSON.stringify({ email, password, remember_me: rememberMe })
            });
            const data = await response.json(); // Try to parse JSON regardless of ok status for error details

            if (response.ok) {
                processLoginSuccess(data, rememberMe);
            } else {
                processLoginApiError(data, response.status);
            }
        } catch (error) { // Catches errors from apiRequest (network, timeout, or thrown "Session Expired")
            console.error('Login Form Submission Error:', error);
            showGlobalMessage(error.message || 'Login request failed. Please check your internet connection and try again.', 'error', true);
        } finally {
            setSubmitButtonState(false, 'ඇතුල් වන්න');
            hideLoading();
        }
    }

    async function handleGoogleSignIn() {
        if (!firebaseAuth) {
            showGlobalMessage('Google Sign-In is not available.', 'error', true);
            return;
        }
        clearGlobalMessages();
        clearInputErrors();
        setGoogleButtonState(true, 'සකසමින්...');
        showLoading();

        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('email');
            provider.addScope('profile');
            provider.setCustomParameters({ prompt: 'select_account' });

            const result = await firebaseAuth.signInWithPopup(provider);
            const user = result.user;

            if (user) {
                const idToken = await user.getIdToken(true);
                const backendResponse = await apiRequest('/auth/google-login', {
                    method: 'POST',
                    body: JSON.stringify({ id_token: idToken })
                });
                const backendData = await backendResponse.json();

                if (backendResponse.ok) {
                    processLoginSuccess(backendData, true); // Assume "remember me" for social logins
                } else {
                    processLoginApiError(backendData, backendResponse.status);
                }
            } else {
                throw new Error('No user data received from Google Sign-In provider.');
            }
        } catch (error) {
            console.error("Google Sign-In Process Error:", error);
            let displayMessage = 'Google සමඟින් පිවිසීමේදී දෝෂයක් ඇතිවිය. ';
            if (error.code) {
                handleFirebaseError(error.code, displayMessage);
            } else {
                showGlobalMessage(displayMessage + (error.message || 'Please try again.'), 'error', true);
            }
        } finally {
            setGoogleButtonState(false, 'Google සමඟින් පිවිසෙන්න');
            hideLoading();
        }
    }
    
    async function checkFirebaseAuthRedirectResult() {
        if (!firebaseAuth || sessionStorage.getItem('auth_redirect_attempt') !== 'true') {
            return;
        }
        sessionStorage.removeItem('auth_redirect_attempt');
        showLoading();
        setGoogleButtonState(true, 'Google සැසිය පරික්ෂා කරමින්...');
        try {
            const result = await firebaseAuth.getRedirectResult();
            if (result && result.user) {
                const idToken = await result.user.getIdToken(true);
                const backendResponse = await apiRequest('/auth/google-login', {
                    method: 'POST',
                    body: JSON.stringify({ id_token: idToken })
                });
                const backendData = await backendResponse.json();
                if (backendResponse.ok) {
                    processLoginSuccess(backendData, true);
                } else {
                    processLoginApiError(backendData, backendResponse.status);
                }
            }
        } catch (error) {
            console.error("Google Redirect Result Error:", error);
            if (error.code !== 'auth/no-redirect-operation') { // Ignore "no redirect operation" error
                 showGlobalMessage('Failed to process Google Sign-In redirect: ' + error.message, 'error', true);
            }
        } finally {
            hideLoading();
            setGoogleButtonState(false, 'Google සමඟින් පිවිසෙන්න');
        }
    }

    function handleFirebaseError(errorCode, baseMessage = '') {
        let specificMessage = '';
        switch(errorCode) {
            case 'auth/popup-closed-by-user': specificMessage = 'පිවිසුම් කවුළුව ඔබ විසින් වසා දමන ලදී.'; break;
            case 'auth/popup-blocked': specificMessage = 'Pop-up was blocked by the browser. Please allow pop-ups for this site.'; break;
            case 'auth/network-request-failed': specificMessage = 'A network error occurred. Please check your internet connection.'; break;
            case 'auth/cancelled-popup-request': specificMessage = 'Multiple pop-up requests. Please try again.'; break;
            case 'auth/user-disabled': specificMessage = 'This Google account has been disabled.'; break;
            case 'auth/unauthorized-domain': specificMessage = 'This domain is not authorized for Google Sign-In.'; break;
            default: specificMessage = 'An unexpected error occurred (' + errorCode + ').';
        }
        showGlobalMessage(baseMessage + specificMessage, 'error', true);
    }

    // --- 4. UI and State Management Functions ---
    function validateInputs(email, password) {
        let isValid = true;
        if (!email) {
            showInputError(emailErrorEl, 'ඊමේල් ලිපිනය අනිවාර්යයි.');
            isValid = false;
        } else if (!isValidEmail(email)) { // from utils.js
            showInputError(emailErrorEl, 'කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න.');
            isValid = false;
        }
        if (!password) {
            showInputError(passwordErrorEl, 'මුරපදය අනිවාර්යයි.');
            isValid = false;
        }
        // Add password complexity checks here if desired for login, though usually for registration
        return isValid;
    }

    function processLoginSuccess(data, rememberMe) {
        // Store tokens according to "remember me"
        const storageTarget = rememberMe ? localStorage : sessionStorage;
        const otherStorage = rememberMe ? sessionStorage : localStorage;

        if (data.access_token) {
            sessionStorage.setItem('auth_token', data.access_token); // Access token always in session
            if (data.expires_in) {
                const expiryTime = Date.now() + (data.expires_in * 1000);
                sessionStorage.setItem('token_expiry', expiryTime.toString());
            }
        } else {
            console.error("Login success response missing access_token.", data);
            showGlobalMessage("Login successful, but token not received. Please contact support.", "error", true);
            return;
        }

        if (data.refresh_token) {
            storageTarget.setItem('refresh_token', data.refresh_token);
            otherStorage.removeItem('refresh_token');
        } else {
            localStorage.removeItem('refresh_token');
            sessionStorage.removeItem('refresh_token');
        }

        if (data.user_id) {
            storageTarget.setItem('user_id', data.user_id);
            otherStorage.removeItem('user_id');
        } else {
            localStorage.removeItem('user_id');
            sessionStorage.removeItem('user_id');
        }
        
        startAutomaticTokenRefresh(); // From utils.js

        if (data.mfa_required && Array.isArray(data.mfa_methods) && data.mfa_methods.length > 0) {
            showGlobalMessage('Two-Factor Authentication required. Redirecting...', 'success', false);
            setTimeout(() => {
                window.location.href = \`mfa.php?methods=\${encodeURIComponent(data.mfa_methods.join(','))}\`;
            }, 1200);
        } else {
            showGlobalMessage('Login successful! Redirecting to your dashboard...', 'success', false);
            const redirectTarget = sessionStorage.getItem('redirectAfterLogin') || DEFAULT_REDIRECT_AFTER_LOGIN; // DEFAULT_REDIRECT_AFTER_LOGIN from utils.js
            sessionStorage.removeItem('redirectAfterLogin');
            setTimeout(() => { window.location.href = redirectTarget; }, 1200);
        }
    }

    function processLoginApiError(responseData, status) {
        let errorMessage = 'Login failed. ';
        if (responseData?.detail) {
            const detail = responseData.detail;
            if (typeof detail === 'string') {
                if (detail.toLowerCase().includes('invalid email or password') || detail.includes('INVALID_LOGIN_CREDENTIALS')) {
                    errorMessage = 'Invalid email or password. Please check your credentials.';
                    showInputError(emailErrorEl, ' '); // Trigger red border
                    showInputError(passwordErrorEl, ' '); // Trigger red border
                    emailInput.focus();
                } else if (detail.toLowerCase().includes('account temporarily locked')) {
                    errorMessage = 'Account temporarily locked due to too many failed attempts. Please try again later.';
                } else if (detail.toLowerCase().includes('account disabled')) {
                    errorMessage = 'Your account has been disabled. Please contact support.';
                } else if (detail.toLowerCase().includes('email not verified')) {
                    errorMessage = 'Your email address is not verified. Please check your inbox or spam folder for a verification email. You might need to <a href="resend-verification.php" class="font-semibold underline hover:text-kdj-red">request a new one</a>.';
                } else {
                    errorMessage += sanitizeHTML(detail);
                }
            } else if (Array.isArray(detail)) { // Handle FastAPI/Pydantic validation errors
                errorMessage = detail.map(err => \`\${err.loc.join('.')} - \${sanitizeHTML(err.msg)}\`).join('; ');
            } else {
                errorMessage += sanitizeHTML(JSON.stringify(detail));
            }
        } else if (status === 429) { // Too Many Requests
             errorMessage = 'Too many login attempts. Please try again in a few minutes.';
        } else {
            errorMessage += \`A server error occurred (Status: \${status}). Please try again.\`;
        }
        showGlobalMessage(errorMessage, 'error', true); // Show as toast as well
    }

    function showGlobalMessage(msg, type = 'error', useToast = false) {
        if (!messageArea) return;
        messageArea.innerHTML = msg; // Use innerHTML if message contains HTML (like links)
        messageArea.className = 'my-5 p-4 rounded-lg text-center font-semibold text-sm border-2'; // Base classes
        messageArea.classList.add(type); // 'error' or 'success' class for styling
        messageArea.style.display = 'block';
        messageArea.setAttribute('role', type === 'error' ? 'alert' : 'status');
        if (useToast) {
            showToast(messageArea.textContent, type); // Show a toast with the text content
        }
    }

    function clearGlobalMessages() {
        if (messageArea) {
            messageArea.style.display = 'none';
            messageArea.textContent = '';
            messageArea.className = 'my-5 p-4 rounded-lg text-center font-semibold text-sm hidden border-2';
        }
    }

    function showInputError(element, message) {
        if (!element) return;
        element.textContent = sanitizeHTML(message); // Sanitize from utils.js
        element.style.display = 'block';
        const inputField = element.previousElementSibling?.querySelector('input'); // More specific query
        if (inputField) {
            inputField.classList.add('border-red-500', 'focus:border-red-600', 'focus:ring-red-600');
            inputField.classList.remove('border-gray-300','focus:border-kdj-red', 'focus:ring-kdj-red');
            inputField.setAttribute('aria-invalid', 'true');
            inputField.setAttribute('aria-describedby', element.id);
        }
    }

    function clearInputErrors() {
        [emailErrorEl, passwordErrorEl].forEach(el => {
            if (el) {
                el.style.display = 'none';
                el.textContent = '';
            }
        });
        [emailInput, passwordInput].forEach(input => {
            if (input) {
                input.classList.remove('border-red-500', 'focus:border-red-600', 'focus:ring-red-600');
                input.classList.add('border-gray-300','focus:border-kdj-red', 'focus:ring-kdj-red');
                input.removeAttribute('aria-invalid');
                input.removeAttribute('aria-describedby');
            }
        });
    }

    function setSubmitButtonState(isLoading, text = 'ඇතුල් වන්න') {
        if (submitButton && buttonText && buttonSpinner) {
            submitButton.disabled = isLoading;
            buttonText.textContent = text;
            buttonSpinner.style.display = isLoading ? 'inline-block' : 'none';
        }
    }
    function setGoogleButtonState(isLoading, text = 'Google සමඟින් පිවිසෙන්න') {
         if (googleSignInButton && googleButtonText && googleButtonSpinner) {
            googleSignInButton.disabled = isLoading;
            googleButtonText.textContent = text;
            googleButtonSpinner.style.display = isLoading ? 'inline-block' : 'none';
        }
    }
});
</script>
HTML;

include 'footer.php'; // Includes utils.js and then the $additional_scripts above
?>