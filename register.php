<?php
// Set page specific variables
$title = "Register";
$description = "Create a new KDJ Lanka account to access our services";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
    }
    .register-card {
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
    <div class="register-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                ලියාපදිංචි වන්න
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                නව ගිණුමක් සාදන්න
            </p>
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
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">මුරපදය තහවුරු කරන්න</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="මුරපදය නැවත ඇතුලත් කරන්න">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    ලියාපදිංචි වන්න
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    දැනටමත් ගිණුමක් තිබේද? <a href="index.php" class="font-medium text-kdj-red hover:text-red-800">ඇතුල් වන්න</a>
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
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
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
    const registerForm = document.getElementById('registerForm');
    const submitButton = document.getElementById('submitButton');
    const confirmPassword = document.getElementById('confirm_password');
    
    registerForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const email = document.getElementById('email').value;
        const displayName = document.getElementById('display_name').value;
        const phoneNumber = document.getElementById('phone_number').value;
        const password = passwordInput.value;
        const passwordConfirm = confirmPassword.value;
        
        // Client-side validation
        if (!isValidEmail(email)) {
            showToast('කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුලත් කරන්න', 'error');
            return;
        }
        
        if (password !== passwordConfirm) {
            showToast('මුරපද දෙක සමාන නොවේ!', 'error');
            return;
        }
        
        // Check password strength
        if (
            password.length < 12 ||
            !/[A-Z]/.test(password) ||
            !/[a-z]/.test(password) ||
            !/\d/.test(password) ||
            !/[!@#$%^&*(),.?":{}|<>]/.test(password)
        ) {
            showToast('මුරපදය ප්‍රමාණවත් තරම් ශක්තිමත් නොවේ', 'error');
            return;
        }
        
        // Validate phone number if provided
        if (phoneNumber && !phoneNumber.match(/^\\+[1-9]\\d{1,14}$/)) {
            showToast('දුරකථන අංකය E.164 ආකෘතියෙන් විය යුතුය (උදා: +947XXXXXXXX)', 'error');
            return;
        }
        
        // Prepare registration data
        const registrationData = {
            email: email,
            password: password,
            display_name: displayName || null,
            phone_number: phoneNumber || null
        };
        
        // Disable button and show loading
        submitButton.disabled = true;
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> සකසමින්...';
        showLoading();
        
        try {
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
                showToast(responseData.message || 'සාර්ථකව ලියාපදිංචි විය! කරුණාකර ඔබගේ ඊමේල් පරීක්ෂා කරන්න.', 'success');
                registerForm.reset();
                
                // Show more detailed success message
                const successHtml = `
                    <div class="bg-green-50 p-4 rounded-md border border-green-200 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">ලියාපදිංචි වීම සාර්ථකයි!</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>ඔබගේ ඊමේල් ලිපිනයට තහවුරු කිරීමේ පණිවිඩයක් යවා ඇත. කරුණාකර ඔබගේ ඊමේල් පරීක්ෂා කර ඔබගේ ගිණුම සක්‍රිය කරන්න.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.querySelector('.register-card > form').insertAdjacentHTML('beforebegin', successHtml);
                
                // Redirect to login page after 5 seconds
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 5000);
            } else {
                let errorMessage = 'ලියාපදිංචි වීමට නොහැක. ';
                
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        // Map common error messages to Sinhala
                        if (responseData.detail.includes('Email already exists')) {
                            errorMessage = 'මෙම ඊමේල් ලිපිනය දැනටමත් භාවිතා කරයි.';
                        } else if (responseData.detail.includes('Password must')) {
                            errorMessage = responseData.detail.replace('Password must', 'මුරපදය අනිවාර්යයෙන්');
                        } else {
                            errorMessage += responseData.detail;
                        }
                    } else if (Array.isArray(responseData.detail)) {
                        errorMessage += responseData.detail.map(err => `\${err.loc.join('.')}: \${err.msg}`).join(', ');
                    } else {
                        errorMessage += JSON.stringify(responseData.detail);
                    }
                } else {
                    errorMessage += `Error code: \${response.status}`;
                }
                
                showToast(errorMessage, 'error');
            }
        } catch (error) {
            showToast('ලියාපදිංචි වීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', 'error');
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