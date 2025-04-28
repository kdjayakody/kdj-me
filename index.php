<?php

$redirect_uri = isset($_GET['redirect_uri']) ? htmlspecialchars($_GET['redirect_uri'], ENT_QUOTES, 'UTF-8') : '';


// Check if user is already logged in
$auth_token = isset($_COOKIE['auth_token']) ? $_COOKIE['auth_token'] : '';
if (empty($auth_token)) {
    // Check for token in session storage via JavaScript
    echo "<script>
        if (sessionStorage.getItem('auth_token')) {
            window.location.href = 'dashboard.php';
        }
    </script>";
}

// Set page specific variables
$title = "Login";
$description = "Sign in to your KDJ Lanka account";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
    }
    .login-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    /* Custom styles for the login form */
    .form-input:focus {
        outline: none;
        border-color: #f87171;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
    }
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="login-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <img src="assets/img/kdjcolorlogo.png" class="mx-auto w-40">
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                ගිණුමට පිවිසෙන්න
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                නැවතත් ඔබව සාදරයෙන් පිළිගනිමු!
            </p>
        </div>

        <div id="messageArea" class="my-6 p-3 rounded-md text-center font-medium text-sm hidden"></div>

        <form id="loginForm" class="mt-8 space-y-6">
            <div>
                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">ඊමේල් ලිපිනය</label>
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 text-center text-gray-700">
                        <i class="fas fa-envelope text-base"></i>
                    </div>
                    <input type="email" id="email" name="email" required
                        class="form-input flex-grow block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:border-kdj-red focus:ring-kdj-red sm:text-sm">
                </div>
                <p class="mt-1 text-xs text-red-500" id="emailError" style="display: none;"></p>
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">මුරපදය</label>
                <div class="flex items-center relative">
                    <div class="flex-shrink-0 w-8 text-center text-gray-700">
                        <i class="fas fa-lock text-base"></i>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="form-input flex-grow block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 pr-10 focus:border-kdj-red focus:ring-kdj-red sm:text-sm"> 
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-700 hover:text-gray-900" id="togglePassword">
                        <i class="fas fa-eye text-sm"></i>
                    </span>
                </div>
                <p class="mt-1 text-xs text-red-500" id="passwordError" style="display: none;"></p>
            </div>

            <div class="flex items-center justify-between text-xs sm:text-sm">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me"
                        class="h-4 w-4 text-kdj-red focus:ring-kdj-red border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-gray-700">මතක තබාගන්න</label>
                </div>

                <div>
                    <a href="forgot_password.php" class="font-medium text-kdj-red hover:text-red-800 hover:underline">මුරපදය අමතකද?</a>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton"
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red transition duration-150 ease-in-out disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="buttonText">ඇතුල් වන්න</span>
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm">
            <span class="text-gray-600">ගිණුමක් නැද්ද?</span>
            <a href="register.php" class="font-medium text-kdj-red hover:text-red-800 hover:underline ml-1">ලියාපදිංචි වන්න</a>
        </div>
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    const REDIRECT_URL = 'dashboard.php';
    
    // DOM Elements
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberMeInput = document.getElementById('remember_me');
    const messageArea = document.getElementById('messageArea');
    const submitButton = document.getElementById('submitButton');
    const togglePassword = document.getElementById('togglePassword');
    const buttonText = document.getElementById('buttonText');
    const emailErrorEl = document.getElementById('emailError');
    const passwordErrorEl = document.getElementById('passwordError');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        const icon = togglePassword.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
    
    // Form submission
    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearMessages();
        disableSubmitButton('සකසමින්...'); // Show loading state
        
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const rememberMe = rememberMeInput.checked;
        
        // Basic Client Validation
        let isValid = validateInputs(email, password);
        if (!isValid) {
            enableSubmitButton();
            return;
        }
        
        // API Call
        try {
            const response = await fetch(`\${apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, password, remember_me: rememberMe }),
                credentials: 'include'
            });
            
            const data = await response.json();
            
            if (response.ok) {
                handleLoginSuccess(data, rememberMe);
            } else {
                handleApiError(data, response.status);
                enableSubmitButton();
            }
        } catch (error) {
            handleNetworkError(error);
            enableSubmitButton();
        }
    });
    
    // Helper Functions
    function validateInputs(email, password) {
        let isValid = true;
        clearMessages(); // Clear previous errors first
        
        if (!email) {
            showInputError(emailErrorEl, 'ඊමේල් ලිපිනය අවශ්‍යයි.');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showInputError(emailErrorEl, 'කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න.');
            isValid = false;
        }
        
        if (!password) {
            showInputError(passwordErrorEl, 'මුරපදය අවශ්‍යයි.');
            isValid = false;
        }
        return isValid;
    }
    
    function handleLoginSuccess(data, rememberMe) {
        showMessage('සාර්ථකව ඇතුල් විය! යොමු කරමින්...', 'success');
        handleTokenStorage(data, rememberMe);
        
        // Redirect based on MFA status
        const redirectTarget = (data.mfa_required && data.mfa_methods?.length > 0)
            ? `mfa.php?methods=\${data.mfa_methods.join(',')}` // MFA page
            : REDIRECT_URL; // Dashboard
        
        setTimeout(() => { window.location.href = redirectTarget; }, 1000);
    }
    
    function handleTokenStorage(data, rememberMe) {
        const storage = rememberMe ? localStorage : sessionStorage;
        const otherStorage = rememberMe ? sessionStorage : localStorage; // For clearing opposite storage
        
        if (data.access_token) {
            sessionStorage.setItem('auth_token', data.access_token); // Access token always in session storage
            if (data.expires_in) {
                const expiryTime = Date.now() + (data.expires_in * 1000);
                sessionStorage.setItem('token_expiry', expiryTime.toString());
            }
        }
        
        if (data.refresh_token) {
            storage.setItem('refresh_token', data.refresh_token); // Store refresh token based on rememberMe
            otherStorage.removeItem('refresh_token'); // Clear from the other storage
        } else {
            // Ensure refresh token is cleared if not provided
            localStorage.removeItem('refresh_token');
            sessionStorage.removeItem('refresh_token');
        }
        
        if (data.user_id) {
            storage.setItem('user_id', data.user_id);
            otherStorage.removeItem('user_id');
        } else {
            // Ensure user ID is cleared if not provided
            localStorage.removeItem('user_id');
            sessionStorage.removeItem('user_id');
        }
    }
    
    function handleApiError(data, status) {
        let errorMessage = 'ඇතුල් වීමට නොහැක. ';
        if (data?.detail) {
            if (typeof data.detail === 'string') {
                if (data.detail.includes('Invalid email or password') || data.detail.includes('INVALID_LOGIN_CREDENTIALS')) {
                    errorMessage = 'වලංගු නොවන ඊමේල් හෝ මුරපදය.';
                    showInputError(emailErrorEl, ' '); emailInput.focus();
                    showInputError(passwordErrorEl, ' ');
                } else if (data.detail.includes('Account temporarily locked')) {
                    errorMessage = data.detail; // Show lockout message from API
                } else if (data.detail.includes('Account disabled')) {
                    errorMessage = 'ඔබගේ ගිණුම අක්‍රිය කර ඇත.';
                } else {
                    errorMessage += data.detail;
                }
            } else {
                errorMessage += JSON.stringify(data.detail);
            }
        } else {
            errorMessage += `සේවාදායකයේ දෝෂයක් (කේතය: \${status})`;
        }
        showMessage(errorMessage, 'error');
    }
    
    function handleNetworkError(error) {
        console.error('Login Fetch Error:', error);
        showMessage('ඉල්ලීම යැවීමේදී දෝෂයක් ඇතිවිය. ඔබගේ සම්බන්ධතාවය පරීක්ෂා කර නැවත උත්සහ කරන්න.', 'error');
    }
    
    function showMessage(msg, type) {
        messageArea.textContent = msg;
        // Base classes + type specific classes
        let typeClasses = 'border ';
        if (type === 'success') {
            typeClasses += 'bg-green-50 border-green-300 text-green-700';
        } else if (type === 'error') {
            typeClasses += 'bg-red-50 border-red-300 text-red-700';
        } else { // Info or default
            typeClasses += 'bg-blue-50 border-blue-300 text-blue-700';
        }
        messageArea.className = `my-6 p-3 rounded-md text-center font-medium text-sm \${typeClasses}`;
        messageArea.style.display = 'block';
    }
    
    function showInputError(element, message) {
        if (!element) return;
        element.textContent = message;
        element.style.display = 'block';
        const input = element.closest('div')?.querySelector('input'); // Find input in parent div
        if (input) {
            input.classList.add('border-red-500'); // Add red border
            input.classList.remove('focus:border-kdj-red','focus:ring-kdj-red'); // Remove default focus
            input.classList.add('focus:border-red-500','focus:ring-red-500'); // Add red focus
        }
    }
    
    function clearMessages() {
        messageArea.style.display = 'none';
        messageArea.textContent = '';
        if (emailErrorEl) emailErrorEl.style.display = 'none';
        if (passwordErrorEl) passwordErrorEl.style.display = 'none';
        
        // Remove red borders and restore default focus
        [emailInput, passwordInput].forEach(input => {
            input?.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            input?.classList.add('focus:border-kdj-red', 'focus:ring-kdj-red');
        });
    }
    
    function disableSubmitButton(text) {
        submitButton.disabled = true;
        buttonText.textContent = text;
        // Add spinner using font awesome
        submitButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i><span>\${text}</span>`;
    }
    
    function enableSubmitButton() {
        submitButton.disabled = false;
        // Restore original button text/structure
        submitButton.innerHTML = `<span id="buttonText">ඇතුල් වන්න</span>`;
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Check for redirect after login and verify logged-in state
    document.addEventListener('DOMContentLoaded', function() {
        // First check if user is already logged in, redirect to dashboard
        const authToken = sessionStorage.getItem('auth_token');
        if (authToken) {
            // Verify token is valid
            fetch(`\${apiBaseUrl}/users/me`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer \${authToken}`
                },
                credentials: 'include'
            })
            .then(response => {
                if (response.ok) {
                    // Check if there's a specific redirect URL in sessionStorage
                    const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
                    
                    if (redirectAfterLogin) {
                        // User is logged in, redirect to the saved URL
                        sessionStorage.removeItem('redirectAfterLogin');
                        window.location.href = redirectAfterLogin;
                    } else {
                        // No saved redirect, go to dashboard
                        window.location.href = REDIRECT_URL;
                    }
                }
            })
            .catch(error => {
                console.error('Auth check error:', error);
                // Token may be invalid, let user login again
            });
        }
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>