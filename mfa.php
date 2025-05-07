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
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
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
                <p class="text-xs text-gray-600">
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
    
    // Parse available methods from URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const methodsParam = urlParams.get('methods');
    let availableMethods = ['totp'];
    
    if (methodsParam) {
        availableMethods = methodsParam.split(',');
        document.getElementById('methodsInput').value = methodsParam;
    }
    
    // Show method selector if multiple methods are available
    if (availableMethods.length > 1) {
        document.getElementById('methodSelectorContainer').classList.remove('hidden');
    }
    
    // Check if there's a token in session storage (from login)
    const authToken = sessionStorage.getItem('auth_token');
    if (!authToken) {
        // Redirect to login if no token
        window.location.href = 'index.php';
    }
    
    // OTP input handling
    const otpInputs = [
        document.getElementById('otp1'),
        document.getElementById('otp2'),
        document.getElementById('otp3'),
        document.getElementById('otp4'),
        document.getElementById('otp5'),
        document.getElementById('otp6')
    ];
    
    // Focus on the first input when page loads
    document.addEventListener('DOMContentLoaded', function() {
        otpInputs[0].focus();
    });
    
    // Handle OTP input behavior
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
                    document.getElementById('mfaForm').dispatchEvent(new Event('submit'));
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
                    document.getElementById('mfaForm').dispatchEvent(new Event('submit'));
                }, 250);
            }
        });
    });
    
    // Handle method selection
    const authMethodSelect = document.getElementById('authMethod');
    const totpContainer = document.getElementById('totpContainer');
    const backupContainer = document.getElementById('backupContainer');
    
    authMethodSelect.addEventListener('change', function() {
        if (this.value === 'totp') {
            totpContainer.classList.remove('hidden');
            backupContainer.classList.add('hidden');
            otpInputs[0].focus();
        } else if (this.value === 'backup') {
            totpContainer.classList.add('hidden');
            backupContainer.classList.remove('hidden');
            document.getElementById('backupCode').focus();
        }
    });
    
    // Handle "Use backup code" link
    document.getElementById('showBackupLink').addEventListener('click', function(e) {
        e.preventDefault();
        
        if (availableMethods.includes('backup')) {
            authMethodSelect.value = 'backup';
            totpContainer.classList.add('hidden');
            backupContainer.classList.remove('hidden');
            document.getElementById('backupCode').focus();
            
            // Show method selector if it was hidden
            document.getElementById('methodSelectorContainer').classList.remove('hidden');
        } else {
            showToast('Backup codes are not enabled for your account.', 'error');
        }
    });
    
    // Handle form submission
    const mfaForm = document.getElementById('mfaForm');
    const verifyButton = document.getElementById('verifyButton');
    
    mfaForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const method = authMethodSelect.value;
        let code = '';
        
        if (method === 'totp') {
            code = document.getElementById('totpCode').value;
            if (code.length !== 6 || !/^[0-9]{6}$/.test(code)) {
                showToast('Please enter a valid 6-digit verification code.', 'error');
                return;
            }
        } else if (method === 'backup') {
            code = document.getElementById('backupCode').value.replace(/[^A-Za-z0-9]/g, '');
            if (code.length < 10) {
                showToast('Please enter a valid backup code.', 'error');
                return;
            }
        }
        
        // Disable button and show loading
        verifyButton.disabled = true;
        const originalButtonText = verifyButton.innerHTML;
        verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
        showLoading();
        
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
                }
                
                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = redirectUrlAfterLogin;
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
                
                showToast(errorMessage, 'error');
                
                // Clear OTP inputs
                if (method === 'totp') {
                    otpInputs.forEach(input => { input.value = ''; });
                    document.getElementById('totpCode').value = '';
                    otpInputs[0].focus();
                }
            }
        } catch (error) {
            console.error('MFA verification error:', error);
            showToast('Failed to verify code. Please try again.', 'error');
        } finally {
            // Re-enable button and hide loading
            verifyButton.disabled = false;
            verifyButton.innerHTML = originalButtonText;
            hideLoading();
        }
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>