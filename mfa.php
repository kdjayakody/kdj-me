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
const apiBaseUrl = 'https://auth.kdj.lk/api/v1'; // Make sure this matches your setup
const redirectUrlAfterLogin = 'dashboard.php'; // Default if no 'next' param

// Parse available methods from URL query parameter
const urlParamsMFA = new URLSearchParams(window.location.search); // Renamed to avoid conflict if any
const methodsParam = urlParamsMFA.get('methods');
let availableMethods = ['totp']; // Default

if (methodsParam) {
    availableMethods = methodsParam.split(',');
    if(document.getElementById('methodsInput')) { // Ensure element exists
        document.getElementById('methodsInput').value = methodsParam;
    }
}

// Show method selector if multiple methods are available
if (availableMethods.length > 1 && document.getElementById('methodSelectorContainer')) {
    document.getElementById('methodSelectorContainer').classList.remove('hidden');
}

// Check if there's a token in session storage (from login)
const authTokenMFA = sessionStorage.getItem('auth_token'); // Renamed to avoid conflict
if (!authTokenMFA) {
    window.location.href = 'index.php'; // Redirect to login if no token
}

// OTP input handling
const otpInputs = Array.from(document.querySelectorAll('.otp-input')); // Ensure these have the class 'otp-input'
const totpCodeInput = document.getElementById('totpCode'); // Hidden input for combined code

if (otpInputs.length > 0 && otpInputs[0]) { // Ensure inputs exist
    otpInputs[0].focus(); // Focus on the first input when page loads

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); // Allow only digits
            if (this.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            if (totpCodeInput) { // Ensure hidden input exists
                totpCodeInput.value = otpInputs.map(inp => inp.value).join('');
            }
            // Auto-submit if all 6 digits are entered
            if (totpCodeInput && totpCodeInput.value.length === 6 && /^[0-9]{6}$/.test(totpCodeInput.value)) {
                 if(document.getElementById('mfaForm')) { // Ensure form exists
                    setTimeout(() => { // Short delay to allow input update
                        document.getElementById('mfaForm').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    }, 100);
                 }
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            if (/^[0-9]{6}$/.test(pasteData)) {
                pasteData.split('').forEach((char, i) => {
                    if (otpInputs[i]) otpInputs[i].value = char;
                });
                if (totpCodeInput) totpCodeInput.value = pasteData;
                if (otpInputs.length > 0 && otpInputs[otpInputs.length - 1]) otpInputs[otpInputs.length - 1].focus(); // Focus last input
                 if(document.getElementById('mfaForm')) { // Ensure form exists
                    setTimeout(() => { // Auto-submit after paste
                         document.getElementById('mfaForm').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    }, 100);
                 }
            }
        });
    });
}


// Handle method selection
const authMethodSelect = document.getElementById('authMethod');
const totpContainer = document.getElementById('totpContainer');
const backupContainer = document.getElementById('backupContainer');
const backupCodeInputEl = document.getElementById('backupCode'); // Renamed for clarity

if (authMethodSelect) { // Ensure select element exists
    authMethodSelect.addEventListener('change', function() {
        if (this.value === 'totp') {
            if(totpContainer) totpContainer.classList.remove('hidden');
            if(backupContainer) backupContainer.classList.add('hidden');
            if (otpInputs.length > 0 && otpInputs[0]) otpInputs[0].focus();
        } else if (this.value === 'backup') {
            if(totpContainer) totpContainer.classList.add('hidden');
            if(backupContainer) backupContainer.classList.remove('hidden');
            if(backupCodeInputEl) backupCodeInputEl.focus();
        }
    });
}

// Handle "Use backup code" link
const showBackupLink = document.getElementById('showBackupLink');
if (showBackupLink) { // Ensure link exists
    showBackupLink.addEventListener('click', function(e) {
        e.preventDefault();
        if (availableMethods.includes('backup')) {
            if(authMethodSelect) authMethodSelect.value = 'backup'; // Ensure select exists
            if(totpContainer) totpContainer.classList.add('hidden');
            if(backupContainer) backupContainer.classList.remove('hidden');
            if(backupCodeInputEl) backupCodeInputEl.focus();
            if(document.getElementById('methodSelectorContainer')) { // Ensure container exists
                document.getElementById('methodSelectorContainer').classList.remove('hidden');
            }
        } else {
            if (typeof showToast === 'function') showToast('Backup codes are not enabled for your account.', 'error');
        }
    });
}

// Handle form submission
const mfaForm = document.getElementById('mfaForm');
const verifyButton = document.getElementById('verifyButton');

if (mfaForm) { // Ensure form exists
    mfaForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const currentMethod = authMethodSelect ? authMethodSelect.value : 'totp'; // Default to totp if selector not present
        let code = '';

        if (currentMethod === 'totp') {
            if (totpCodeInput) code = totpCodeInput.value; // Ensure hidden input exists
            if (code.length !== 6 || !/^[0-9]{6}$/.test(code)) {
                if (typeof showToast === 'function') showToast('Please enter a valid 6-digit verification code.', 'error');
                return;
            }
        } else if (currentMethod === 'backup') {
            if (backupCodeInputEl) code = backupCodeInputEl.value.replace(/[^A-Za-z0-9-]/g, ''); // Allow hyphens
            if (code.length < 8) { // Backup codes are often longer, e.g. XXXX-XXXX
                if (typeof showToast === 'function') showToast('Please enter a valid backup code.', 'error');
                return;
            }
        }

        if (verifyButton) { // Ensure button exists
            verifyButton.disabled = true;
            verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
        }
        if (typeof showLoading === 'function') showLoading();

        try {
            const response = await fetch(`\${apiBaseUrl}/auth/mfa/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer \${authTokenMFA}` // Use the token fetched at the start
                },
                body: JSON.stringify({ code: code, method: currentMethod }),
                credentials: 'include'
            });

            const responseData = await response.json();
            if (typeof hideLoading === 'function') hideLoading();

            if (response.ok) {
                if (typeof showToast === 'function') showToast('Authentication successful!', 'success');

                if (responseData.access_token) { // If API returns a new token after MFA
                    sessionStorage.setItem('auth_token', responseData.access_token);
                     if (responseData.expires_in) {
                        const expiryTime = Date.now() + (responseData.expires_in * 1000);
                        sessionStorage.setItem('token_expiry', expiryTime.toString());
                    }
                }

                // --- අලුතින්: MFA එකෙන් පස්සේ redirect වෙන තැන තීරණය කිරීම ---
                const nextUrlAfterMfa = urlParamsMFA.get('next'); // Login page එකෙන් pass කරපු 'next' URL එක
                const finalRedirectAfterMfa = nextUrlAfterMfa || redirectUrlAfterLogin; // redirectUrlAfterLogin is 'dashboard.php'

                setTimeout(() => {
                    window.location.href = finalRedirectAfterMfa;
                }, 1000);

            } else {
                let errorMessage = 'Verification failed. ';
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        if (responseData.detail.includes('Invalid code')) {
                            errorMessage = 'Invalid verification code. Please try again.';
                        } else if (responseData.detail.includes('expired')) {
                            errorMessage = 'Verification code expired. Please use a new code.';
                        } else {
                            errorMessage += responseData.detail;
                        }
                    } else {
                        errorMessage += JSON.stringify(responseData.detail);
                    }
                } else {
                    errorMessage += `Error code: \${response.status}`;
                }
                if (typeof showToast === 'function') showToast(errorMessage, 'error');

                if (currentMethod === 'totp') {
                    otpInputs.forEach(input => { if(input) input.value = ''; });
                    if(totpCodeInput) totpCodeInput.value = '';
                    if (otpInputs.length > 0 && otpInputs[0]) otpInputs[0].focus();
                } else if (currentMethod === 'backup' && backupCodeInputEl) {
                    backupCodeInputEl.value = '';
                    backupCodeInputEl.focus();
                }
            }
        } catch (error) {
            if (typeof hideLoading === 'function') hideLoading();
            console.error('MFA verification error:', error);
            if (typeof showToast === 'function') showToast('Failed to verify code. Network or server error.', 'error');
        } finally {
            if (verifyButton) { // Ensure button exists
                verifyButton.disabled = false;
                verifyButton.innerHTML = '<span class="absolute left-0 inset-y-0 flex items-center pl-3"><i class="fas fa-lock"></i></span>Verify';
            }
        }
    });
}

// Initial setup based on available methods
document.addEventListener('DOMContentLoaded', function() {
    if (authMethodSelect && availableMethods.length <= 1 && availableMethods.includes('totp')) {
        // If only TOTP is available and it's the default, hide selector
        if(document.getElementById('methodSelectorContainer')) {
            document.getElementById('methodSelectorContainer').classList.add('hidden');
        }
        if(totpContainer) totpContainer.classList.remove('hidden');
        if(backupContainer) backupContainer.classList.add('hidden');
    } else if (authMethodSelect && !availableMethods.includes(authMethodSelect.value)) {
        // If current selection is not available, switch to the first available one
        authMethodSelect.value = availableMethods[0];
        authMethodSelect.dispatchEvent(new Event('change')); // Trigger change handler
    }
});
</script>
HTML;

// Include footer
include 'footer.php';
?>