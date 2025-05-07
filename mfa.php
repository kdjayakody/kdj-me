<?php
// Set page specific variables
$title = "Two-Factor Authentication";
$description = "Verify your identity with two-factor authentication";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }
    .mfa-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    .otp-input {
        width: 3rem;
        height: 3rem;
        font-size: 1.5rem;
        text-align: center;
        border-radius: 0.375rem;
    }
    .otp-input:focus {
        outline: none;
        border-color: #cb2127;
        box-shadow: 0 0 0 2px rgba(203, 33, 39, 0.2);
    }
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="mfa-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-kdj-red bg-opacity-10 mb-4">
                <i class="fas fa-shield-alt text-kdj-red text-2xl"></i>
            </div>
            <h2 class="mt-2 text-3xl font-extrabold text-kdj-dark">
                Two-Factor Authentication
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Please enter the verification code from your authenticator app
            </p>
        </div>
        
        <div id="errorMessage" class="bg-red-50 p-4 rounded-md border border-red-200 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800" id="errorTitle">Authentication failed</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p id="errorDetail">Invalid verification code. Please try again.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <form id="mfaForm" class="mt-8 space-y-6">
            <input type="hidden" name="methods" id="methodsInput">
            
            <div class="rounded-md shadow-sm space-y-5">
                <!-- Authentication method selector -->
                <div id="methodSelectorContainer" class="hidden">
                    <label for="authMethod" class="block text-sm font-medium text-gray-700 mb-1">Authentication Method</label>
                    <select id="authMethod" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red">
                        <option value="totp">Authenticator App</option>
                        <option value="backup">Backup Code</option>
                    </select>
                </div>
                
                <!-- Authenticator app code input -->
                <div id="totpContainer">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Verification Code</label>
                    <div class="flex justify-center space-x-2 mb-3">
                        <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:ring-kdj-red focus:border-kdj-red" id="otp1" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:ring-kdj-red focus:border-kdj-red" id="otp2" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:ring-kdj-red focus:border-kdj-red" id="otp3" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:ring-kdj-red focus:border-kdj-red" id="otp4" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:ring-kdj-red focus:border-kdj-red" id="otp5" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:ring-kdj-red focus:border-kdj-red" id="otp6" autocomplete="off">
                    </div>
                    <input type="hidden" id="totpCode" name="totpCode">
                    <p class="text-xs text-gray-500 text-center">Enter the 6-digit code from your authenticator app</p>
                </div>
                
                <!-- Backup code input -->
                <div id="backupContainer" class="hidden">
                    <label for="backupCode" class="block text-sm font-medium text-gray-700 mb-1">Backup Code</label>
                    <input type="text" id="backupCode" name="backupCode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" placeholder="XXXX-XXXX-XXXX">
                    <p class="mt-1 text-xs text-gray-500">Enter one of your backup codes</p>
                </div>
            </div>

            <div>
                <button type="submit" id="verifyButton" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock"></i>
                    </span>
                    Verify
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-xs text-gray-600" id="backupText">
                    Lost access to your authenticator app? <a href="#" id="showBackupLink" class="font-medium text-kdj-red hover:text-red-800">Use backup code</a>.
                </p>
                <p class="text-xs text-gray-600 mt-2">
                    <a href="index.php" class="font-medium text-kdj-red hover:text-red-800">
                        <i class="fas fa-arrow-left mr-1"></i> Back to login
                    </a>
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
    
    // DOM elements
    const mfaForm = document.getElementById('mfaForm');
    const methodsInput = document.getElementById('methodsInput');
    const authMethodSelect = document.getElementById('authMethod');
    const methodSelectorContainer = document.getElementById('methodSelectorContainer');
    const totpContainer = document.getElementById('totpContainer');
    const backupContainer = document.getElementById('backupContainer');
    const verifyButton = document.getElementById('verifyButton');
    const showBackupLink = document.getElementById('showBackupLink');
    const backupText = document.getElementById('backupText');
    const errorMessage = document.getElementById('errorMessage');
    const errorTitle = document.getElementById('errorTitle');
    const errorDetail = document.getElementById('errorDetail');
    
    // OTP input elements
    const otpInputs = [
        document.getElementById('otp1'),
        document.getElementById('otp2'),
        document.getElementById('otp3'),
        document.getElementById('otp4'),
        document.getElementById('otp5'),
        document.getElementById('otp6')
    ];
    
    // Verify auth token and redirect if not present
    function checkAuthToken() {
        const authToken = sessionStorage.getItem('auth_token');
        if (!authToken) {
            // If no token found, redirect to login
            window.location.href = 'index.php';
            return false;
        }
        return true;
    }
    
    // Parse available methods from URL query parameter
    function parseAvailableMethods() {
        const urlParams = new URLSearchParams(window.location.search);
        const methodsParam = urlParams.get('methods');
        let availableMethods = ['totp']; // Default to TOTP if not specified
        
        if (methodsParam) {
            availableMethods = methodsParam.split(',');
            methodsInput.value = methodsParam;
            
            // Update UI based on available methods
            updateMethodsUI(availableMethods);
        }
        
        return availableMethods;
    }
    
    // Update UI based on available authentication methods
    function updateMethodsUI(methods) {
        // Show method selector if multiple methods are available
        methodSelectorContainer.classList.toggle('hidden', methods.length <= 1);
        
        // Check if backup code is available
        if (!methods.includes('backup')) {
            // Hide backup option if not available
            const backupOption = Array.from(authMethodSelect.options).find(option => option.value === 'backup');
            if (backupOption) backupOption.remove();
            
            // Hide the backup link
            backupText.classList.add('hidden');
        }
        
        // Update dropdown options
        while (authMethodSelect.options.length > 0) {
            authMethodSelect.options.remove(0);
        }
        
        if (methods.includes('totp')) {
            const option = document.createElement('option');
            option.value = 'totp';
            option.text = 'Authenticator App';
            authMethodSelect.add(option);
        }
        
        if (methods.includes('backup')) {
            const option = document.createElement('option');
            option.value = 'backup';
            option.text = 'Backup Code';
            authMethodSelect.add(option);
        }
        
        // Default to the first available method
        if (methods.length > 0) {
            authMethodSelect.value = methods[0];
            updateMethodDisplay(methods[0]);
        }
    }
    
    // Update method display based on selected method
    function updateMethodDisplay(method) {
        if (method === 'totp') {
            totpContainer.classList.remove('hidden');
            backupContainer.classList.add('hidden');
            otpInputs[0].focus();
        } else if (method === 'backup') {
            totpContainer.classList.add('hidden');
            backupContainer.classList.remove('hidden');
            document.getElementById('backupCode').focus();
        }
    }
    
    // Initialize OTP inputs
    function initOTPInputs() {
        // Focus on the first input
        otpInputs[0].focus();
        
        otpInputs.forEach((input, index) => {
            // Move to next input when a digit is entered
            input.addEventListener('input', function() {
                // Update the value to ensure it's only a digit
                const sanitizedValue = this.value.replace(/[^0-9]/g, '');
                this.value = sanitizedValue;
                
                if (sanitizedValue && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                // Combine all values into the hidden input
                const combinedCode = otpInputs.map(input => input.value).join('');
                document.getElementById('totpCode').value = combinedCode;
                
                // Submit the form if all 6 digits are entered
                if (combinedCode.length === 6 && /^[0-9]{6}$/.test(combinedCode)) {
                    setTimeout(() => {
                        mfaForm.dispatchEvent(new Event('submit'));
                    }, 250);
                }
            });
            
            // Handle backspace key
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            // Allow pasting the entire OTP
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text');
                if (/^[0-9]{6}$/.test(pasteData)) {
                    // Distribute the 6 digits among the inputs
                    for (let i = 0; i < otpInputs.length; i++) {
                        otpInputs[i].value = pasteData.charAt(i);
                    }
                    document.getElementById('totpCode').value = pasteData;
                    
                    // Submit the form
                    setTimeout(() => {
                        mfaForm.dispatchEvent(new Event('submit'));
                    }, 250);
                }
            });
        });
    }
    
    // Show the backup code input
    function showBackupCodeInput() {
        const availableMethods = parseAvailableMethods();
        
        if (availableMethods.includes('backup')) {
            authMethodSelect.value = 'backup';
            updateMethodDisplay('backup');
            
            // Show method selector if it was hidden
            methodSelectorContainer.classList.remove('hidden');
        } else {
            showError('Backup codes are not enabled for your account.');
        }
    }
    
    // Show error message
    function showError(message, title = 'Verification Failed') {
        errorTitle.textContent = title;
        errorDetail.textContent = message;
        errorMessage.classList.remove('hidden');
        
        // Scroll to error message
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    // Hide error message
    function hideError() {
        errorMessage.classList.add('hidden');
    }
    
    // Verify MFA code
    async function verifyMFACode(code, method) {
        if (!checkAuthToken()) return;
        
        // Validate input
        if (method === 'totp' && (code.length !== 6 || !/^[0-9]{6}$/.test(code))) {
            showError('Please enter a valid 6-digit verification code.');
            return;
        } else if (method === 'backup' && code.trim().length < 10) {
            showError('Please enter a valid backup code.');
            return;
        }
        
        // Disable button and show loading
        verifyButton.disabled = true;
        const originalButtonText = verifyButton.innerHTML;
        verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
        showLoading();
        hideError();
        
        // Get auth token from session storage
        const authToken = sessionStorage.getItem('auth_token');
        
        try {
            const response = await fetch(`\${apiBaseUrl}/auth/mfa/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer \${authToken}`
                },
                body: JSON.stringify({
                    code: code,
                    method: method
                }),
                credentials: 'include'
            });
            
            const responseData = await response.json();
            
            if (response.ok) {
                showToast('Authentication successful!', 'success');
                
                // Update token if provided
                if (responseData.access_token) {
                    sessionStorage.setItem('auth_token', responseData.access_token);
                    
                    // Update expiry time if provided
                    if (responseData.expires_in) {
                        const expiryTime = Date.now() + (responseData.expires_in * 1000);
                        sessionStorage.setItem('token_expiry', expiryTime.toString());
                    }
                }
                
                // Get the redirect URL if available, otherwise use default
                const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
                
                // Redirect to destination page
                setTimeout(() => {
                    if (redirectAfterLogin) {
                        // Clear the redirect URL before navigating
                        sessionStorage.removeItem('redirectAfterLogin');
                        window.location.href = redirectAfterLogin;
                    } else {
                        window.location.href = redirectUrlAfterLogin;
                    }
                }, 1000);
            } else {
                let errorMessage = 'Verification failed. ';
                
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        if (responseData.detail.includes('Invalid code')) {
                            errorMessage = 'Invalid verification code. Please try again.';
                        } else if (responseData.detail.includes('expired')) {
                            errorMessage = 'Verification code expired. Please generate a new code.';
                        } else {
                            errorMessage += responseData.detail;
                        }
                    } else {
                        errorMessage += JSON.stringify(responseData.detail);
                    }
                } else {
                    errorMessage += `Error code: \${response.status}`;
                }
                
                showError(errorMessage);
                
                // Clear OTP inputs
                if (method === 'totp') {
                    otpInputs.forEach(input => { input.value = ''; });
                    document.getElementById('totpCode').value = '';
                    otpInputs[0].focus();
                }
            }
        } catch (error) {
            console.error('MFA verification error:', error);
            showError('Failed to verify code. Please check your connection and try again.');
        } finally {
            // Re-enable button and hide loading
            verifyButton.disabled = false;
            verifyButton.innerHTML = originalButtonText;
            hideLoading();
        }
    }
    
    // Event Listeners
    
    // Method selection change
    authMethodSelect.addEventListener('change', function() {
        updateMethodDisplay(this.value);
    });
    
    // Show backup code link
    showBackupLink.addEventListener('click', function(e) {
        e.preventDefault();
        showBackupCodeInput();
    });
    
    // Form submission
    mfaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const method = authMethodSelect.value;
        let code = '';
        
        if (method === 'totp') {
            code = document.getElementById('totpCode').value;
        } else if (method === 'backup') {
            code = document.getElementById('backupCode').value.replace(/[^A-Za-z0-9]/g, '');
        }
        
        verifyMFACode(code, method);
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user has a valid auth token
        if (!checkAuthToken()) return;
        
        // Parse available methods from URL
        parseAvailableMethods();
        
        // Initialize OTP inputs
        initOTPInputs();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>