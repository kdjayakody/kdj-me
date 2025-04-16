<?php
// Set page specific variables
$title = "Login";
$description = "Sign in to your KDJ Lanka account to access our services";
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
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="login-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                ඇතුල් වන්න
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                ඔබගේ ගිණුමට ප්‍රවේශ වන්න
            </p>
        </div>
        
        <form id="loginForm" class="mt-8 space-y-6">
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">ඊමේල් ලිපිනය</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none rounded-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="ඊමේල් ලිපිනය">
                    </div>
                </div>
                <div>
                    <label for="password" class="sr-only">මුරපදය</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none rounded-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="මුරපදය">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="text-gray-400 focus:outline-none hover:text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" 
                        class="h-4 w-4 text-kdj-red focus:ring-kdj-red border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        මතක තබාගන්න
                    </label>
                </div>

                <div class="text-sm">
                    <a href="forgot_password.php" class="font-medium text-kdj-red hover:text-red-800">
                        මුරපදය අමතකද?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    ඇතුල් වන්න
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    ගිණුමක් නැද්ද? <a href="register.php" class="font-medium text-kdj-red hover:text-red-800">ලියාපදිංචි වන්න</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    const redirectUrlAfterLogin = 'dashboard.php';
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Form submission
    const loginForm = document.getElementById('loginForm');
    const submitButton = document.getElementById('submitButton');
    
    // Check if user is already logged in
    async function checkSession() {
        try {
            const response = await fetch(`\${apiBaseUrl}/users/me`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });
            
            if (response.ok) {
                // User is already logged in, redirect to dashboard
                window.location.href = redirectUrlAfterLogin;
            }
        } catch (error) {
            // Ignore errors - just proceed with login form
            console.log("No active session found");
        }
    }
    
    // Call check session on page load
    checkSession();
    
    loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('remember_me').checked;
    
    // Basic client-side validation
    if (!email || !password) {
        showToast('Please enter both email and password', 'error');
        return;
    }
    
    if (!isValidEmail(email)) {
        showToast('Please enter a valid email address', 'error');
        return;
    }
    
    // Disable button and show loading
    submitButton.disabled = true;
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> සකසමින්...';
    showLoading();
    
    try {
        const response = await fetch(`${apiBaseUrl}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password,
                remember_me: rememberMe
            }),
            credentials: 'include'
        });
        
        const responseData = await response.json();
        
        if (response.ok) {
            showToast('සාර්ථකව ඇතුල් විය!', 'success');
            
            // Clear any previously stored tokens
            sessionStorage.removeItem('auth_token');
            
            // Store tokens
            if (responseData.access_token) {
                // Always store in sessionStorage (cleared when browser is closed)
                sessionStorage.setItem('auth_token', responseData.access_token);
                
                // If requested to remember, also store user ID for reference (not the token)
                if (rememberMe && responseData.user_id) {
                    localStorage.setItem('user_id', responseData.user_id);
                }
                
                // For debugging, log token
                console.log('Auth token stored in sessionStorage');
            } else {
                console.error('No access token received from server');
            }
            
            // Check if MFA required
            if (responseData.mfa_required && responseData.mfa_methods && responseData.mfa_methods.length > 0) {
                // Redirect to MFA page
                window.location.href = `mfa.php?methods=${responseData.mfa_methods.join(',')}`;
                return;
            }
            
            // Redirect after successful login
            setTimeout(() => {
                window.location.href = redirectUrlAfterLogin;
            }, 1000);
        } else {
            // Login failed
            let errorMessage = 'ඇතුල් වීමට නොහැක. ';
            
            if (responseData.detail) {
                if (typeof responseData.detail === 'string') {
                    // Map common error messages to Sinhala
                    if (responseData.detail.includes('Invalid email or password')) {
                        errorMessage = 'වලංගු නොවන ඊමේල් හෝ මුරපදය.';
                    } else if (responseData.detail.includes('Token expired')) {
                        errorMessage = 'සැසිය කල් ඉකුත් වී ඇත. නැවත පුරනය කරන්න.';
                    } else {
                        errorMessage += responseData.detail;
                    }
                } else {
                    errorMessage += JSON.stringify(responseData.detail);
                }
            } else {
                errorMessage += `Error code: ${response.status}`;
            }
            
            showToast(errorMessage, 'error');
        }
    } catch (error) {
        // Network error or other issue
        console.error('Login error:', error);
        showToast('ඉල්ලීම යැවීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', 'error');
    } finally {
        // Re-enable button and hide loading
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
        hideLoading();
    }
});
    
    // Email validation helper
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
</script>
HTML;

// Include footer
include 'footer.php';
?>