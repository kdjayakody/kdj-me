<?php
// Set page specific variables
$title = "Reset Password";
$description = "Reset your KDJ Lanka account password";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
    }
    .reset-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    .password-strength-meter {
        height: 5px;
        transition: all 0.3s ease;
    }
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="reset-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                නව මුරපදයක් සකසන්න
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                ඔබගේ KDJ Lanka ගිණුම සඳහා නව මුරපදයක් ඇතුලත් කරන්න
            </p>
        </div>
        
        <!-- Token Error Message -->
        <div id="tokenErrorMessage" class="bg-red-50 p-4 rounded-md border border-red-200 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">වලංගු නොවන Token එකක්</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p id="tokenErrorDetail">Reset token එකක් හමුවුනේ නැත. කරුණාකර email එකේ ඇති link එක හරහා පිවිසෙන්න.</p>
                    </div>
                    <div class="mt-4">
                        <a href="forgot_password.php" class="text-sm font-medium text-red-800 hover:text-red-900">
                            නැවත නව reset link එකක් ඉල්ලන්න <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Success Message -->
        <div id="successMessage" class="bg-green-50 p-4 rounded-md border border-green-200 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">මුරපදය සාර්ථකව වෙනස් කරන ලදී!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>ඔබේ මුරපදය සාර්ථකව යාවත්කාලීන කර ඇත. ඔබට දැන් ඔබේ නව මුරපදය භාවිතයෙන් පුරනය විය හැක.</p>
                    </div>
                    <div class="mt-4">
                        <a href="index.php" class="text-sm font-medium text-green-800 hover:text-green-900">
                            පිවිසුම් පිටුවට යන්න <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <form id="resetPasswordForm" class="mt-8 space-y-6">
            <input type="hidden" id="reset_token" name="reset_token">
            
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">නව මුරපදය</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="new_password" name="new_password" type="password" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="නව මුරපදයක් ඇතුලත් කරන්න">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="text-gray-400 focus:outline-none hover:text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="flex w-full h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="password-strength-meter bg-red-500" id="passwordStrengthMeter" style="width: 0%"></div>
                        </div>
                        <ul class="mt-2 text-xs text-gray-600 space-y-1" id="passwordRequirements">
                            <li id="req-length"><i class="fas fa-times-circle text-red-500 mr-1"></i> අඩුම තරමින් අක්ෂර 12ක් විය යුතුය</li>
                            <li id="req-uppercase"><i class="fas fa-times-circle text-red-500 mr-1"></i> අඩුම තරමින් එක් ලොකු අකුරක් තිබිය යුතුය</li>
                            <li id="req-lowercase"><i class="fas fa-times-circle text-red-500 mr-1"></i> අඩුම තරමින් එක් කුඩා අකුරක් තිබිය යුතුය</li>
                            <li id="req-number"><i class="fas fa-times-circle text-red-500 mr-1"></i> අඩුම තරමින් එක් ඉලක්කමක් තිබිය යුතුය</li>
                            <li id="req-special"><i class="fas fa-times-circle text-red-500 mr-1"></i> අඩුම තරමින් එක් විශේෂ අක්ෂරයක් තිබිය යුතුය</li>
                        </ul>
                    </div>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">නව මුරපදය තහවුරු කරන්න</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="නව මුරපදය නැවත ඇතුලත් කරන්න">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-key"></i>
                    </span>
                    මුරපදය යාවත්කාලීන කරන්න
                </button>
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
    
    // Get the token from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const resetTokenInput = document.getElementById('reset_token');
    const tokenErrorMessage = document.getElementById('tokenErrorMessage');
    const tokenErrorDetail = document.getElementById('tokenErrorDetail');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const successMessage = document.getElementById('successMessage');
    
    // Check if token exists
    if (!token) {
        resetPasswordForm.classList.add('hidden');
        tokenErrorMessage.classList.remove('hidden');
        tokenErrorDetail.textContent = 'Reset token එකක් හමුවුනේ නැත. කරුණාකර නැවත reset link එකක් ඉල්ලන්න.';
    } else {
        // Verify token format is correct (basic check)
        if (token.length < 20) {
            resetPasswordForm.classList.add('hidden');
            tokenErrorMessage.classList.remove('hidden');
            tokenErrorDetail.textContent = 'වලංගු නොවන reset token එකක්. කරුණාකර නැවත reset link එකක් ඉල්ලන්න.';
        } else {
            // Set token to hidden input
            resetTokenInput.value = token;
        }
    }
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('new_password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Password strength verification
    const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
    const passwordRequirements = {
        length: document.getElementById('req-length'),
        uppercase: document.getElementById('req-uppercase'),
        lowercase: document.getElementById('req-lowercase'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let score = 0;
        const maxScore = 5;
        
        // Check length
        const hasLength = password.length >= 12;
        updateRequirement('length', hasLength);
        if (hasLength) score++;
        
        // Check uppercase
        const hasUppercase = /[A-Z]/.test(password);
        updateRequirement('uppercase', hasUppercase);
        if (hasUppercase) score++;
        
        // Check lowercase
        const hasLowercase = /[a-z]/.test(password);
        updateRequirement('lowercase', hasLowercase);
        if (hasLowercase) score++;
        
        // Check number
        const hasNumber = /\d/.test(password);
        updateRequirement('number', hasNumber);
        if (hasNumber) score++;
        
        // Check special chars
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        updateRequirement('special', hasSpecial);
        if (hasSpecial) score++;
        
        // Update strength meter
        const percentage = (score / maxScore) * 100;
        passwordStrengthMeter.style.width = `\${percentage}%`;
        
        // Update color based on strength
        if (percentage < 40) {
            passwordStrengthMeter.className = 'password-strength-meter bg-red-500';
        } else if (percentage < 80) {
            passwordStrengthMeter.className = 'password-strength-meter bg-yellow-500';
        } else {
            passwordStrengthMeter.className = 'password-strength-meter bg-green-500';
        }
    });
    
    function updateRequirement(req, isFulfilled) {
        const element = passwordRequirements[req];
        const icon = element.querySelector('i');
        
        if (isFulfilled) {
            icon.className = 'fas fa-check-circle text-green-500 mr-1';
        } else {
            icon.className = 'fas fa-times-circle text-red-500 mr-1';
        }
    }
    
    // Form submission
    const submitButton = document.getElementById('submitButton');
    const confirmPassword = document.getElementById('confirm_password');
    
    resetPasswordForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const token = resetTokenInput.value;
        const newPassword = passwordInput.value;
        const passwordConfirm = confirmPassword.value;
        
        // Client-side validation
        if (newPassword !== passwordConfirm) {
            showToast('නව මුරපද දෙක සමාන නොවේ!', 'error');
            return;
        }
        
        // Check password strength
        if (
            newPassword.length < 12 ||
            !/[A-Z]/.test(newPassword) ||
            !/[a-z]/.test(newPassword) ||
            !/\d/.test(newPassword) ||
            !/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)
        ) {
            showToast('මුරපදය ප්‍රමාණවත් තරම් ශක්තිමත් නොවේ', 'error');
            return;
        }
        
        // Disable button and show loading
        submitButton.disabled = true;
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> සකසමින්...';
        showLoading();
        
        try {
            const response = await fetch(`\${apiBaseUrl}/auth/reset-password/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    token: token,
                    new_password: newPassword
                })
            });
            
            const responseData = await response.json();
            
            if (response.ok) {
                hideLoading();
                resetPasswordForm.classList.add('hidden');
                successMessage.classList.remove('hidden');
                
                // Automatically redirect to login page after 5 seconds
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 5000);
            } else {
                hideLoading();
                
                let errorMessage = 'මුරපදය reset කිරීමට නොහැක. ';
                
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        if (responseData.detail.includes('Token expired')) {
                            errorMessage = 'Reset link එක කල් ඉකුත් වී ඇත. කරුණාකර නැවත link එකක් ඉල්ලන්න.';
                        } else if (responseData.detail.includes('Password must')) {
                            errorMessage = responseData.detail.replace('Password must', 'මුරපදය අනිවාර්යයෙන්');
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
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        } catch (error) {
            hideLoading();
            showToast('මුරපදය reset කිරීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>