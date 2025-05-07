<?php
// Set page specific variables
$title = "Login";
$description = "Sign in to your KDJ Lanka account";
$lang = "si";

// Define API base URL
$apiBaseUrl = 'https://auth.kdj.lk/api/v1';

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }
    .login-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    /* Custom styles for the login form */
    .form-input:focus {
        outline: none;
        border-color: #cb2127;
        box-shadow: 0 0 0 2px rgba(203, 33, 39, 0.2);
    }
    /* Google Sign-In button */
    .google-signin-btn {
        transition: all 0.3s ease;
    }
    .google-signin-btn:hover {
        background-color: #f3f4f6;
    }
</style>
HTML;

// Check if user is already logged in with JavaScript
echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's a valid auth token
        const authToken = sessionStorage.getItem('auth_token');
        
        if (authToken) {
            // Verify token by making an API call
            fetch('{$apiBaseUrl}/users/me', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer \${authToken}`
                },
                credentials: 'include'
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Invalid token');
            })
            .then(userData => {
                // Check if there's a specific redirect URL in sessionStorage
                const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
                
                if (redirectAfterLogin) {
                    // User is logged in, redirect to the saved URL
                    sessionStorage.removeItem('redirectAfterLogin');
                    window.location.href = redirectAfterLogin;
                } else {
                    // No saved redirect, go to dashboard
                    window.location.href = 'dashboard.php';
                }
            })
            .catch(error => {
                console.error('Auth check error:', error);
                // Clear invalid token
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token_expiry');
            });
        }
    });
</script>";

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="login-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <img src="assets/img/kdjcolorlogo.png" alt="KDJ Lanka Logo" class="mx-auto w-40">
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
        
        <div class="my-6 flex items-center justify-center">
            <div class="border-t border-gray-300 flex-grow"></div>
            <span class="px-4 text-sm text-gray-500 bg-white login-card">හෝ</span>
            <div class="border-t border-gray-300 flex-grow"></div>
        </div>
        
        <div>
            <button type="button" id="googleSignInButton" class="google-signin-btn w-full flex justify-center items-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red transition duration-150 ease-in-out">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google icon" class="w-5 h-5 mr-2">
                <span>Google සමඟින් පිවිසෙන්න</span>
            </button>
        </div>

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
    const apiBaseUrl = '{$apiBaseUrl}';
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
    const googleSignInButton = document.getElementById('googleSignInButton');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        const icon = togglePassword.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
    
    // Check if there's a redirect URL in the query parameters
    function checkForRedirectInURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const redirectURL = urlParams.get('redirect');
        
        if (redirectURL) {
            // Store the redirect URL for after login
            sessionStorage.setItem('redirectAfterLogin', decodeURIComponent(redirectURL));
        }
    }
    
    // Run it when page loads
    checkForRedirectInURL();
    
    // Google Sign-In Handler with improved error handling
    async function signInWithGoogle() {
        if (!firebaseApp || !firebaseAuth) {
            showMessage('Google පිවිසුම ක්‍රියාත්මක කිරීමට නොහැක. Firebase සූදානම් නැත.', 'error');
            return;
        }

        clearMessages();
        // Disable button while processing
        googleSignInButton.disabled = true;
        googleSignInButton.querySelector('span').textContent = 'සකසමින්...';
        showLoading();

        try {
            // Create a Google Auth Provider with explicit scopes
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('email');
            provider.addScope('profile');
            
            // Set longer timeout for operations
            const authSettings = {
                appVerificationDisabledForTesting: false,
                // Set a longer timeout (Firebase default is typically 60 seconds)
                timeout: 90000
            };
            
            // Apply settings
            firebaseAuth.settings.appVerificationDisabledForTesting = false;
            
            // Sign in with popup with explicit error handling
            try {
                const result = await firebaseAuth.signInWithPopup(provider);
                
                // Get the user from the result
                const user = result.user;
                
                if (user) {
                    // Get the Firebase ID token
                    const idToken = await user.getIdToken(true);
                    handleGoogleLoginWithToken(idToken);
                } else {
                    showMessage('Google සමඟින් පිවිසීමට නොහැකි විය. කරුණාකර නැවත උත්සහ කරන්න.', 'error');
                    resetGoogleButton();
                }
            } catch (popupError) {
                console.error("Google Sign-In Popup Error:", popupError);
                
                // If popup fails, try redirect method
                if (popupError.code === 'auth/internal-error' || 
                    popupError.code === 'auth/popup-blocked' || 
                    popupError.code === 'auth/popup-closed-by-user') {
                    
                    showMessage('ප්‍රතිදිශාගත කිරීම් ක්‍රමය උත්සාහ කරමින්...', 'info');
                    
                    // Save that we're attempting redirect auth
                    sessionStorage.setItem('auth_redirect_attempt', 'true');
                    
                    // Use redirect method instead
                    try {
                        await firebaseAuth.signInWithRedirect(provider);
                    } catch (redirectError) {
                        console.error("Redirect auth fallback error:", redirectError);
                        showMessage('Google සමඟින් පිවිසීමට නොහැකි විය. තාක්ෂණික දෝෂයක්: ' + 
                                  redirectError.message, 'error');
                        resetGoogleButton();
                    }
                } else {
                    // Handle other error types
                    handleGoogleSignInError(popupError);
                }
            }
        } catch (error) {
            console.error("Google Sign-In Main Error:", error);
            handleGoogleSignInError(error);
        }
    }
    
    // Handle Google Sign-In errors with better user feedback
    function handleGoogleSignInError(error) {
        let displayMessage = 'Google සමඟින් පිවිසීමේදී දෝෂයක් ඇතිවිය: ';
        
        switch(error.code) {
            case 'auth/popup-closed-by-user':
                displayMessage = 'පිවිසුම් කවුළුව වසා දමන ලදී. කරුණාකර නැවත උත්සාහ කරන්න.';
                break;
            case 'auth/popup-blocked':
                displayMessage = 'බ්‍රවුසරය විසින් පොප්-අප් අවහිර කර ඇත. කරුණාකර ඔබේ බ්‍රවුසරයේ පොප්-අප් අවහිර කිරීම් අක්‍රිය කර නැවත උත්සාහ කරන්න.';
                break;
            case 'auth/cancelled-popup-request':
                displayMessage = 'එකවර පිවිසුම් කවුළු කිහිපයක් විවෘත කර ඇත.';
                break;
            case 'auth/network-request-failed':
                displayMessage = 'ජාල දෝෂයක්. ඔබගේ අන්තර්ජාල සම්බන්ධතාව පරීක්ෂා කරන්න.';
                break;
            case 'auth/internal-error':
                displayMessage = 'අභ්‍යන්තර දෝෂයක්. කරුණාකර පසුව නැවත උත්සාහ කරන්න. දෝෂ විස්තරය: ' + error.message;
                // Try fallback method if popup fails - already handled in the main function
                break;
            case 'auth/timeout':
                displayMessage = 'කාල නිමාව ඉක්මවා ඇත. ඔබගේ අන්තර්ජාල සම්බන්ධතාව පරීක්ෂා කර නැවත උත්සාහ කරන්න.';
                break;
            case 'auth/user-token-expired':
                displayMessage = 'පරිශීලක සැසිය කල් ඉකුත් වී ඇත. කරුණාකර නැවත පිවිසෙන්න.';
                break;
            case 'auth/web-storage-unsupported':
                displayMessage = 'ඔබගේ බ්‍රවුසරය වෙබ් ගබඩාව සඳහා සහාය නොදක්වයි. කරුණාකර වෙනත් බ්‍රවුසරයක් භාවිතා කරන්න.';
                break;
            default:
                if (error.message) {
                    displayMessage += error.message;
                } else {
                    displayMessage += "අභ්‍යන්තර දෝෂයක්. කරුණාකර පසුව නැවත උත්සාහ කරන්න.";
                }
        }
        
        showMessage(displayMessage, 'error');
        resetGoogleButton();
    }
    
    // Reset Google button state
    function resetGoogleButton() {
        googleSignInButton.disabled = false;
        googleSignInButton.querySelector('span').textContent = 'Google සමඟින් පිවිසෙන්න';
        hideLoading();
    }
    
    // Send the Google ID token to backend with better error handling
    async function handleGoogleLoginWithToken(idToken) {
        try {
            const backendResponse = await fetch(`\${apiBaseUrl}/auth/google-login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ id_token: idToken }),
                credentials: 'include'
            });

            const backendData = await backendResponse.json();

            if (backendResponse.ok) {
                // Handle login success like regular login
                handleLoginSuccess(backendData, true);
            } else {
                handleApiError(backendData, backendResponse.status);
                resetGoogleButton();
            }
        } catch (error) {
            console.error("Backend Google login error:", error);
            showMessage('සේවාදායකය සමඟ සම්බන්ධ වීමේදී දෝෂයක් ඇතිවිය. කරුණාකර ඔබගේ අන්තර්ජාල සම්බන්ධතාව පරීක්ෂා කර නැවත උත්සාහ කරන්න.', 'error');
            resetGoogleButton();
        }
    }
    
    // Check for redirect result on page load with better error handling
    function checkRedirectResult() {
        if (sessionStorage.getItem('auth_redirect_attempt')) {
            // Clear the flag
            sessionStorage.removeItem('auth_redirect_attempt');
            
            // Show loading while we check the result
            showLoading();
            
            firebaseAuth.getRedirectResult()
                .then(async (result) => {
                    hideLoading();
                    if (result && result.user) {
                        // Get the Firebase ID token
                        try {
                            const idToken = await result.user.getIdToken(true);
                            handleGoogleLoginWithToken(idToken);
                        } catch (tokenError) {
                            console.error("Error getting ID token:", tokenError);
                            showMessage('පරිශීලක හඳුනාගැනීමේ දෝෂයක්. කරුණාකර නැවත උත්සාහ කරන්න.', 'error');
                            resetGoogleButton();
                        }
                    } else {
                        // No result means the redirect failed or was cancelled
                        showMessage('Google පිවිසුම අවලංගු කරන ලදී හෝ අසාර්ථක විය. කරුණාකර නැවත උත්සාහ කරන්න.', 'error');
                        resetGoogleButton();
                    }
                })
                .catch((error) => {
                    hideLoading();
                    console.error("Redirect result error:", error);
                    showMessage('Google පිවිසුම් ප්‍රතිදිශාගත කිරීමේදී දෝෂයක් ඇතිවිය: ' + error.message, 'error');
                    resetGoogleButton();
                });
        }
    }

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
        storeAuthData(data, rememberMe);
        
        // Check if MFA is required
        if (data.mfa_required && data.mfa_methods?.length > 0) {
            // Redirect to MFA page
            setTimeout(() => { 
                window.location.href = `mfa.php?methods=\${data.mfa_methods.join(',')}`; 
            }, 1000);
            return;
        }
        
        // Get the redirect URL if available, otherwise use dashboard
        const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
        
        if (redirectAfterLogin) {
            // Clear the redirect URL before navigating
            sessionStorage.removeItem('redirectAfterLogin');
            setTimeout(() => { window.location.href = redirectAfterLogin; }, 1000);
        } else {
            // Default to dashboard
            setTimeout(() => { window.location.href = REDIRECT_URL; }, 1000);
        }
    }
    
    function handleApiError(responseData, status) {
        let errorMessage = 'ඇතුල් වීමට නොහැක. ';
        if (responseData?.detail) {
            if (typeof responseData.detail === 'string') {
                if (responseData.detail.includes('Invalid email or password') || responseData.detail.includes('INVALID_LOGIN_CREDENTIALS')) {
                    errorMessage = 'වලංගු නොවන ඊමේල් හෝ මුරපදය.';
                    showInputError(emailErrorEl, ' '); emailInput.focus();
                    showInputError(passwordErrorEl, ' ');
                } else if (responseData.detail.includes('Account temporarily locked')) {
                    errorMessage = responseData.detail; // Show lockout message from API
                } else if (responseData.detail.includes('Account disabled')) {
                    errorMessage = 'ඔබගේ ගිණුම අක්‍රිය කර ඇත.';
                } else {
                    errorMessage += responseData.detail;
                }
            } else {
                errorMessage += JSON.stringify(responseData.detail);
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
        
        // Make sure the message is visible
        messageArea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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
    
    // Save referrer information for redirecting back after login
    function checkReferrer() {
        // Check referrer to see if it's from an internal site we should redirect back to
        const referrer = document.referrer;
        
        if (referrer && (
            referrer.includes('events.kdj.lk') || 
            referrer.includes('singlish.kdj.lk')
        )) {
            // If referred from one of our other sites, store it for redirect after login
            sessionStorage.setItem('redirectAfterLogin', referrer);
        }
    }
    
    // Store authentication data with proper handling
    function storeAuthData(data, rememberMe) {
        // Access token always goes in session storage
        if (data.access_token) {
            sessionStorage.setItem('auth_token', data.access_token);
            
            // Store token expiry if provided
            if (data.expires_in) {
                const expiryTime = Date.now() + (data.expires_in * 1000);
                sessionStorage.setItem('token_expiry', expiryTime.toString());
            }
        }
        
        // Refresh token goes in localStorage if rememberMe, otherwise sessionStorage
        if (data.refresh_token) {
            if (rememberMe) {
                localStorage.setItem('refresh_token', data.refresh_token);
                sessionStorage.removeItem('refresh_token');
            } else {
                sessionStorage.setItem('refresh_token', data.refresh_token);
                localStorage.removeItem('refresh_token');
            }
        }
        
        // Store user ID if provided
        if (data.user_id) {
            if (rememberMe) {
                localStorage.setItem('user_id', data.user_id);
            } else {
                sessionStorage.setItem('user_id', data.user_id);
            }
        }
    }
    
    // Add event listener to Google Sign-In Button
    if (googleSignInButton) {
        googleSignInButton.addEventListener('click', signInWithGoogle);
    }
    
    // Initialize when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Check for any Firebase auth redirect results
        checkRedirectResult();
        
        // Check referrer for redirect after login
        checkReferrer();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>