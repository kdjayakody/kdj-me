<?php
// Set page specific variables
$title = "Register";
$description = "Create a new KDJ Lanka account to access our services";
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
    .register-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    .password-strength-meter {
        height: 5px;
        transition: all 0.3s ease;
    }
    /* Requirement checks */
    .requirement-check i {
        transition: all 0.2s ease;
    }
    .requirement-check.valid i {
        color: #10b981;
    }
    .requirement-check.invalid i {
        color: #ef4444;
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
                    // Redirect already authenticated users to dashboard
                    window.location.href = 'dashboard.php';
                }
            })
            .catch(error => {
                console.error('Auth check error:', error);
                // Token may be invalid, let user register
            });
        }
    });
</script>";

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="register-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <img src="assets/img/kdjcolorlogo.png" alt="KDJ Lanka Logo" class="mx-auto w-40">
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                ලියාපදිංචි වන්න
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                නව ගිණුමක් සාදන්න
            </p>
        </div>
        
        <!-- Success Message (hidden by default) -->
        <div id="successMessage" class="bg-green-50 p-4 rounded-md border border-green-200 mt-4 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">ලියාපදිංචි වීම සාර්ථකයි!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>ඔබගේ ඊමේල් ලිපිනයට තහවුරු කිරීමේ පණිවිඩයක් යවා ඇත. කරුණාකර ඔබගේ ඊමේල් පරීක්ෂා කර ඔබගේ ගිණුම සක්‍රිය කරන්න.</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-green-700">
                            <span id="redirectMessage">අපි ඔබව තත්පර 5 කින් පිවිසුම් පිටුවට යොමු කරමු...</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message (hidden by default) -->
        <div id="errorMessage" class="bg-red-50 p-4 rounded-md border border-red-200 mt-4 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">ලියාපදිංචි වීමේ දෝෂයක්</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p id="errorDetail">ලියාපදිංචි වීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සාහ කරන්න.</p>
                    </div>
                </div>
            </div>
        </div>

        <form id="registerForm" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">ඊමේල් ලිපිනය</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="ඔබගේ ඊමේල් ලිපිනය">
                    </div>
                    <p class="mt-1 text-xs text-red-600 hidden" id="emailError"></p>
                </div>
                
                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700">පෙන්වන නම (Display Name)</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="display_name" name="display_name" type="text" autocomplete="name" 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="ඔබගේ නම (අත්‍යවශ්‍ය නොවේ)">
                    </div>
                    <p class="mt-1 text-xs text-red-600 hidden" id="displayNameError"></p>
                </div>
                
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">දුරකථන අංකය (අත්‍යවශ්‍ය නොවේ)</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input id="phone_number" name="phone_number" type="tel"
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="+947XXXXXXXX">
                        <p class="mt-1 text-xs text-gray-500">E.164 ආකෘතිය භාවිතා කරන්න (උදා: +94771234567)</p>
                    </div>
                    <p class="mt-1 text-xs text-red-600 hidden" id="phoneNumberError"></p>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">මුරපදය</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="මුරපදයක් සාදන්න">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="password-toggle text-gray-400 focus:outline-none hover:text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="flex w-full h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="password-strength-meter bg-red-500" id="passwordStrengthMeter" style="width: 0%"></div>
                        </div>
                        <ul class="mt-2 text-xs text-gray-600 space-y-1" id="passwordRequirements">
                            <li id="req-length" class="requirement-check invalid">
                                <i class="fas fa-times-circle mr-1"></i> අඩුම තරමින් අක්ෂර 12ක් විය යුතුය
                            </li>
                            <li id="req-uppercase" class="requirement-check invalid">
                                <i class="fas fa-times-circle mr-1"></i> අඩුම තරමින් එක් ලොකු අකුරක් තිබිය යුතුය
                            </li>
                            <li id="req-lowercase" class="requirement-check invalid">
                                <i class="fas fa-times-circle mr-1"></i> අඩුම තරමින් එක් කුඩා අකුරක් තිබිය යුතුය
                            </li>
                            <li id="req-number" class="requirement-check invalid">
                                <i class="fas fa-times-circle mr-1"></i> අඩුම තරමින් එක් ඉලක්කමක් තිබිය යුතුය
                            </li>
                            <li id="req-special" class="requirement-check invalid">
                                <i class="fas fa-times-circle mr-1"></i> අඩුම තරමින් එක් විශේෂ අක්ෂරයක් තිබිය යුතුය
                            </li>
                        </ul>
                    </div>
                    <p class="mt-1 text-xs text-red-600 hidden" id="passwordError"></p>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">මුරපදය තහවුරු කරන්න</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="මුරපදය නැවත ඇතුලත් කරන්න">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="password-toggle text-gray-400 focus:outline-none hover:text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-red-600 hidden" id="confirmPasswordError"></p>
                </div>
                
                <div class="flex items-center">
                    <input id="terms_agreement" name="terms_agreement" type="checkbox" required
                        class="h-4 w-4 text-kdj-red focus:ring-kdj-red border-gray-300 rounded">
                    <label for="terms_agreement" class="ml-2 block text-sm text-gray-700">
                        මම <a href="/terms-of-service.php" target="_blank" class="text-kdj-red hover:underline">සේවා කොන්දේසි</a> හා <a href="/privacy-policy.php" target="_blank" class="text-kdj-red hover:underline">පෞද්ගලිකත්ව ප්‍රතිපත්තිය</a> කියවා එකඟ වෙමි.
                    </label>
                </div>
                <p class="mt-1 text-xs text-red-600 hidden" id="termsError"></p>
            </div>

            <div>
                <button type="submit" id="submitButton" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red transition duration-150 ease-in-out disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="buttonText">ලියාපදිංචි වන්න</span>
                </button>
            </div>
            
            <div class="text-center text-sm">
                <span class="text-gray-600">දැනටමත් ගිණුමක් තිබේද?</span>
                <a href="index.php" class="font-medium text-kdj-red hover:text-red-800 hover:underline ml-1">ඇතුල් වන්න</a>
            </div>
        </form>
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = '{$apiBaseUrl}';
    
    // DOM Elements
    const registerForm = document.getElementById('registerForm');
    const emailInput = document.getElementById('email');
    const displayNameInput = document.getElementById('display_name');
    const phoneNumberInput = document.getElementById('phone_number');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const termsCheckbox = document.getElementById('terms_agreement');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    
    // Error message elements
    const emailError = document.getElementById('emailError');
    const displayNameError = document.getElementById('displayNameError');
    const phoneNumberError = document.getElementById('phoneNumberError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const termsError = document.getElementById('termsError');
    
    // Result message containers
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const errorDetail = document.getElementById('errorDetail');
    const redirectMessage = document.getElementById('redirectMessage');
    
    // Password strength elements
    const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
    const passwordRequirements = {
        length: document.getElementById('req-length'),
        uppercase: document.getElementById('req-uppercase'),
        lowercase: document.getElementById('req-lowercase'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };
    
    // Toggle password visibility buttons
    const passwordToggleBtns = document.querySelectorAll('.password-toggle');
    passwordToggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('div').querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
    
    // Password strength validation
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strengthResult = validatePasswordStrength(password);
        
        // Update requirement indicators
        updateRequirementUI('length', password.length >= 12);
        updateRequirementUI('uppercase', /[A-Z]/.test(password));
        updateRequirementUI('lowercase', /[a-z]/.test(password));
        updateRequirementUI('number', /\d/.test(password));
        updateRequirementUI('special', /[!@#$%^&*(),.?":{}|<>]/.test(password));
        
        // Update strength meter
        const percentage = (strengthResult.score / 5) * 100;
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
    
    // Update password requirement UI
    function updateRequirementUI(req, isFulfilled) {
        if (!passwordRequirements[req]) return;
        
        const element = passwordRequirements[req];
        const icon = element.querySelector('i');
        
        if (isFulfilled) {
            element.classList.remove('invalid');
            element.classList.add('valid');
            icon.className = 'fas fa-check-circle mr-1';
        } else {
            element.classList.remove('valid');
            element.classList.add('invalid');
            icon.className = 'fas fa-times-circle mr-1';
        }
    }
    
    // Validate password strength
    function validatePasswordStrength(password) {
        const result = {
            valid: false,
            score: 0,
            errors: []
        };
        
        // Check length
        const hasLength = password.length >= 12;
        if (hasLength) result.score++;
        else result.errors.push('Password must be at least 12 characters long');
        
        // Check uppercase
        const hasUppercase = /[A-Z]/.test(password);
        if (hasUppercase) result.score++;
        else result.errors.push('Password must contain at least one uppercase letter');
        
        // Check lowercase
        const hasLowercase = /[a-z]/.test(password);
        if (hasLowercase) result.score++;
        else result.errors.push('Password must contain at least one lowercase letter');
        
        // Check numbers
        const hasNumber = /\d/.test(password);
        if (hasNumber) result.score++;
        else result.errors.push('Password must contain at least one number');
        
        // Check special characters
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        if (hasSpecial) result.score++;
        else result.errors.push('Password must contain at least one special character');
        
        result.valid = result.errors.length === 0;
        return result;
    }
    
    // Real-time confirmation password validation
    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value === this.value) {
            hideError(confirmPasswordError);
        } else {
            showError(confirmPasswordError, 'මුරපද දෙක සමාන නොවේ');
        }
    });
    
    // Email format validation
    emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email && !isValidEmail(email)) {
            showError(emailError, 'කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න');
        } else {
            hideError(emailError);
        }
    });
    
    // Phone number format validation
    phoneNumberInput.addEventListener('blur', function() {
        const phone = this.value.trim();
        if (phone && !isValidPhoneNumber(phone)) {
            showError(phoneNumberError, 'කරුණාකර E.164 ආකෘතියේ දුරකථන අංකයක් ඇතුළත් කරන්න (උදා: +94771234567)');
        } else {
            hideError(phoneNumberError);
        }
    });
    
    // Terms checkbox validation
    termsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            hideError(termsError);
        } else {
            showError(termsError, 'ලියාපදිංචි වීමට පෙර සේවා කොන්දේසි වලට එකඟ විය යුතුය');
        }
    });
    
    // Form submission
    registerForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        // Clear previous errors
        clearAllErrors();
        
        // Get form values
        const email = emailInput.value.trim();
        const displayName = displayNameInput.value.trim();
        const phoneNumber = phoneNumberInput.value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const termsAgreed = termsCheckbox.checked;
        
        // Validate all inputs
        let isValid = true;
        
        // Email validation
        if (!email) {
            showError(emailError, 'ඊමේල් ලිපිනය අවශ්‍යයි');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError(emailError, 'කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න');
            isValid = false;
        }
        
        // Phone number validation (only if provided)
        if (phoneNumber && !isValidPhoneNumber(phoneNumber)) {
            showError(phoneNumberError, 'කරුණාකර E.164 ආකෘතියේ දුරකථන අංකයක් ඇතුළත් කරන්න (උදා: +94771234567)');
            isValid = false;
        }
        
        // Password validation
        const passwordValidation = validatePasswordStrength(password);
        if (!password) {
            showError(passwordError, 'මුරපදය අවශ්‍යයි');
            isValid = false;
        } else if (!passwordValidation.valid) {
            showError(passwordError, 'මුරපදය ශක්තිමත් නොවේ. ඉහත අවශ්‍යතා සපුරාලන්න.');
            isValid = false;
        }
        
        // Confirm password validation
        if (!confirmPassword) {
            showError(confirmPasswordError, 'මුරපදය තහවුරු කිරීම අවශ්‍යයි');
            isValid = false;
        } else if (password !== confirmPassword) {
            showError(confirmPasswordError, 'මුරපද දෙක සමාන නොවේ');
            isValid = false;
        }
        
        // Terms agreement validation
        if (!termsAgreed) {
            showError(termsError, 'ලියාපදිංචි වීමට පෙර සේවා කොන්දේසි වලට එකඟ විය යුතුය');
            isValid = false;
        }
        
        // If validation failed, stop submission
        if (!isValid) {
            return;
        }
        
        // Show loading state
        disableForm();
        showLoading();
        
        // Create registration data object
        const registrationData = {
            email: email,
            password: password,
            display_name: displayName || null,
            phone_number: phoneNumber || null
        };
        
        try {
            // Send registration request to API
            const response = await fetch(`\${apiBaseUrl}/auth/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(registrationData)
            });
            
            const responseData = await response.json();
            
            if (response.status === 201) {
                // Registration successful
                handleRegistrationSuccess(responseData);
            } else {
                // Registration failed
                handleRegistrationError(responseData, response.status);
            }
        } catch (error) {
            // Network or other error
            console.error('Registration error:', error);
            showGlobalError('ලියාපදිංචි වීමේදී ජාල දෝෂයක් ඇතිවිය. කරුණාකර ඔබේ අන්තර්ජාල සම්බන්ධතාවය පරීක්ෂා කර නැවත උත්සාහ කරන්න.');
        } finally {
            // Hide loading
            hideLoading();
        }
    });
    
    // Handle successful registration
    function handleRegistrationSuccess(data) {
        // Hide form and show success message
        registerForm.classList.add('hidden');
        successMessage.classList.remove('hidden');
        
        // Set redirect countdown
        let countdown = 5;
        const countdownInterval = setInterval(() => {
            countdown--;
            redirectMessage.textContent = `අපි ඔබව තත්පර \${countdown} කින් පිවිසුම් පිටුවට යොමු කරමු...`;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = 'index.php';
            }
        }, 1000);
    }
    
    // Handle registration error
    function handleRegistrationError(responseData, status) {
        // Re-enable form
        enableForm();
        
        // Check for specific error types
        if (responseData.detail) {
            if (typeof responseData.detail === 'string') {
                // Check for common errors
                if (responseData.detail.includes('Email already exists')) {
                    showError(emailError, 'මෙම ඊමේල් ලිපිනය දැනටමත් භාවිතා කරයි');
                    emailInput.focus();
                } else if (responseData.detail.includes('Password')) {
                    showError(passwordError, responseData.detail);
                    passwordInput.focus();
                } else {
                    // Generic error
                    showGlobalError(responseData.detail);
                }
            } else if (Array.isArray(responseData.detail)) {
                // Handle validation errors
                responseData.detail.forEach(err => {
                    const field = err.loc[err.loc.length - 1];
                    switch (field) {
                        case 'email':
                            showError(emailError, err.msg);
                            break;
                        case 'password':
                            showError(passwordError, err.msg);
                            break;
                        case 'display_name':
                            showError(displayNameError, err.msg);
                            break;
                        case 'phone_number':
                            showError(phoneNumberError, err.msg);
                            break;
                        default:
                            showGlobalError(err.msg);
                    }
                });
            } else {
                // Unknown error format
                showGlobalError(`ලියාපදිංචි වීමේදී දෝෂයක් ඇතිවිය (කේතය: \${status})`);
            }
        } else {
            // No detail provided
            showGlobalError(`ලියාපදිංචි වීමේදී දෝෂයක් ඇතිවිය (කේතය: \${status})`);
        }
    }
    
    // Show global error message
    function showGlobalError(message) {
        errorDetail.textContent = message;
        errorMessage.classList.remove('hidden');
        // Scroll to error message
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    // Show field-specific error
    function showError(element, message) {
        if (!element) return;
        
        element.textContent = message;
        element.classList.remove('hidden');
        
        // Add error class to the input field
        const inputField = element.previousElementSibling?.querySelector('input') || 
                          element.previousElementSibling?.previousElementSibling?.querySelector('input');
        
        if (inputField) {
            inputField.classList.add('border-red-500');
            inputField.classList.remove('border-gray-300');
        }
    }
    
    // Hide field-specific error
    function hideError(element) {
        if (!element) return;
        
        element.textContent = '';
        element.classList.add('hidden');
        
        // Remove error class from the input field
        const inputField = element.previousElementSibling?.querySelector('input') || 
                          element.previousElementSibling?.previousElementSibling?.querySelector('input');
        
        if (inputField) {
            inputField.classList.remove('border-red-500');
            inputField.classList.add('border-gray-300');
        }
    }
    
    // Clear all error messages
    function clearAllErrors() {
        // Hide global error message
        errorMessage.classList.add('hidden');
        
        // Hide all field-specific error messages
        [emailError, displayNameError, phoneNumberError, passwordError, confirmPasswordError, termsError]
            .forEach(element => {
                if (element) hideError(element);
            });
        
        // Reset all input borders
        registerForm.querySelectorAll('input').forEach(input => {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        });
    }
    
    // Disable form during submission
    function disableForm() {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> සකසමින්...';
        
        // Disable all inputs
        registerForm.querySelectorAll('input, button').forEach(element => {
            element.disabled = true;
        });
    }
    
    // Enable form after submission
    function enableForm() {
        submitButton.disabled = false;
        submitButton.innerHTML = '<span id="buttonText">ලියාපදිංචි වන්න</span>';
        
        // Enable all inputs
        registerForm.querySelectorAll('input, button').forEach(element => {
            if (element !== submitButton) {
                element.disabled = false;
            }
        });
    }
    
    // Validate email format
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Validate phone number format (E.164)
    function isValidPhoneNumber(phone) {
        const re = /^\+[1-9]\d{1,14}$/;
        return re.test(phone);
    }
</script>
HTML;

// Include footer
include 'footer.php';
?>