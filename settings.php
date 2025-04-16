<?php
// Set page specific variables
$title = "Settings";
$description = "Manage your KDJ Lanka account settings";
$lang = "si";

// Include header
include 'header.php';
?>

<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="sidebar bg-white shadow-md fixed h-full left-0 top-16 hidden md:block overflow-y-auto">
        <div class="py-4 px-3">
            <div class="mb-6 px-4">
                <div class="p-2 bg-kdj-red bg-opacity-10 rounded-lg">
                    <h2 class="text-lg font-semibold text-kdj-dark">
                        <span id="sidebarGreeting">සුභ දවසක්</span>
                    </h2>
                    <p class="text-sm text-gray-600" id="sidebarUserName">පරිශීලක</p>
                </div>
            </div>
            
            <ul class="space-y-2 mt-4">
                <li>
                    <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-user w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="security.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-shield-alt w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Security</span>
                    </a>
                </li>
                
                <li class="border-t border-gray-200 my-4 pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Applications
                    </h3>
                </li>
                
                <li>
                    <a href="https://singlish.kdj.lk" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-language w-5 h-5 mr-3 text-gray-500"></i>
                        <span>KDJ Singlish</span>
                    </a>
                </li>
                <li>
                    <a href="https://events.kdj.lk" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-gray-500"></i>
                        <span>KDJ Events</span>
                    </a>
                </li>
                
                <li class="border-t border-gray-200 my-4 pt-2">
                    <button id="sidebarLogoutBtn" class="w-full flex items-center px-4 py-3 text-kdj-red hover:bg-red-50 rounded-md">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                        <span>Logout</span>
                    </button>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Mobile sidebar toggle -->
    <div class="md:hidden fixed bottom-4 right-4 z-10">
        <button id="mobileSidebarToggle" class="bg-kdj-red text-white rounded-full p-3 shadow-lg">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile sidebar -->
    <div id="mobileSidebar" class="md:hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden">
        <div class="bg-white w-64 h-full overflow-y-auto transform transition-transform -translate-x-full" id="mobileSidebarContent">
            <div class="flex justify-between items-center p-4 border-b">
                <div class="flex items-center">
                    <span class="font-bold text-xl text-kdj-dark">KDJ</span>
                    <span class="font-bold text-xl text-kdj-red">Lanka</span>
                </div>
                <button id="closeMobileSidebar" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="py-4 px-3">
                <div class="mb-6 px-4">
                    <div class="p-2 bg-kdj-red bg-opacity-10 rounded-lg">
                        <h2 class="text-lg font-semibold text-kdj-dark">
                            <span id="mobileSidebarGreeting">සුභ දවසක්</span>
                        </h2>
                        <p class="text-sm text-gray-600" id="mobileSidebarUserName">පරිශීලක</p>
                    </div>
                </div>
                
                <ul class="space-y-2 mt-4">
                    <li>
                        <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-gray-500"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-user w-5 h-5 mr-3 text-gray-500"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="security.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-shield-alt w-5 h-5 mr-3 text-gray-500"></i>
                            <span>Security</span>
                        </a>
                    </li>
                    
                    <li class="border-t border-gray-200 my-4 pt-4">
                        <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Applications
                        </h3>
                    </li>
                    
                    <li>
                        <a href="https://singlish.kdj.lk" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-language w-5 h-5 mr-3 text-gray-500"></i>
                            <span>KDJ Singlish</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://events.kdj.lk" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-gray-500"></i>
                            <span>KDJ Events</span>
                        </a>
                    </li>
                    
                    <li class="border-t border-gray-200 my-4 pt-2">
                        <button id="mobileSidebarLogoutBtn" class="w-full flex items-center px-4 py-3 text-kdj-red hover:bg-red-50 rounded-md">
                            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <main class="content flex-1 p-6 md:p-8 pt-24 md:pt-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-kdj-dark mb-1">Account Settings</h1>
                    <p class="text-gray-600">Manage your security and preference settings</p>
                </div>
            </div>
            
            <!-- Password Update Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Password Settings</h2>
                    <p class="text-sm text-gray-600">Manage your account password</p>
                </div>
                
                <div class="p-6">
                    <form id="passwordUpdateForm">
                        <!-- Current Password -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" required>
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- New Password -->
                        <div class="mb-6">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" required>
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
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
                        
                        <!-- Confirm New Password -->
                        <div class="mb-6">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" required>
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" id="passwordUpdateBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                <i class="fas fa-key mr-2"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Language Settings Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Language Preferences</h2>
                    <p class="text-sm text-gray-600">Choose your preferred language</p>
                </div>
                
                <div class="p-6">
                    <form id="languagePreferencesForm">
                        <div class="mb-6">
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Preferred Language</label>
                            <select id="language" name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red">
                                <option value="si">සිංහල (Sinhala)</option>
                                <option value="en">English</option>
                                <option value="ta">தமிழ் (Tamil)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">This setting affects emails and notifications, but not the website interface.</p>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" id="languageUpdateBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                <i class="fas fa-save mr-2"></i>
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Notification Settings Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Notification Settings</h2>
                    <p class="text-sm text-gray-600">Manage how you receive notifications</p>
                </div>
                
                <div class="p-6">
                    <form id="notificationSettingsForm">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700">Email Notifications</h3>
                                    <p class="text-xs text-gray-500">Receive email notifications about account activity</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="email_notifications" name="email_notifications" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked/>
                                    <label for="email_notifications" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700">Security Alerts</h3>
                                    <p class="text-xs text-gray-500">Receive notifications about security-related events</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="security_alerts" name="security_alerts" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked/>
                                    <label for="security_alerts" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700">Marketing Communications</h3>
                                    <p class="text-xs text-gray-500">Receive updates about new features and services</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="marketing_emails" name="marketing_emails" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                    <label for="marketing_emails" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" id="notificationUpdateBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                <i class="fas fa-save mr-2"></i>
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<style>
    /* Toggle switch styles */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #cb2127;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #cb2127;
    }
</style>

<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    
    // User profile data
    let userData = null;
    
    // DOM elements
    const passwordUpdateForm = document.getElementById('passwordUpdateForm');
    const passwordUpdateBtn = document.getElementById('passwordUpdateBtn');
    const languagePreferencesForm = document.getElementById('languagePreferencesForm');
    const languageUpdateBtn = document.getElementById('languageUpdateBtn');
    const notificationSettingsForm = document.getElementById('notificationSettingsForm');
    const notificationUpdateBtn = document.getElementById('notificationUpdateBtn');
    const passwordToggleBtns = document.querySelectorAll('.password-toggle');
    
    // Password strength meter elements
    const newPasswordInput = document.getElementById('new_password');
    const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
    const passwordRequirements = {
        length: document.getElementById('req-length'),
        uppercase: document.getElementById('req-uppercase'),
        lowercase: document.getElementById('req-lowercase'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };
    
    // Set greeting based on time of day
    function setGreeting() {
        const hour = new Date().getHours();
        let greeting = '';
        
        if (hour < 12) {
            greeting = 'සුභ උදෑසනක්';
        } else if (hour < 17) {
            greeting = 'සුභ දහවලක්';
        } else {
            greeting = 'සුභ සන්ධ්‍යාවක්';
        }
        
        document.getElementById('sidebarGreeting').textContent = greeting;
        document.getElementById('mobileSidebarGreeting').textContent = greeting;
    }
    
    // Load user profile data
    async function loadUserProfile() {
        try {
            showLoading();
            
            const response = await fetch(`\${apiBaseUrl}/users/me`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch profile');
            }
            
            userData = await response.json();
            
            // Update sidebar with user name
            document.getElementById('sidebarUserName').textContent = userData.display_name || userData.email;
            document.getElementById('mobileSidebarUserName').textContent = userData.display_name || userData.email;
            
            // Update language preference
            // This would normally come from user settings in your database
            const userLang = localStorage.getItem('lang') || 'si';
            document.getElementById('language').value = userLang;
            
            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to load user profile:', error);
            showToast('Failed to load profile data. Please try refreshing the page.', 'error');
        }
    }
    
    // Toggle password visibility
    passwordToggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const passwordInput = this.parentNode.querySelector('input');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
    
    // Password strength meter
    newPasswordInput.addEventListener('input', function() {
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
    
    // Handle password update form submission
    passwordUpdateForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Basic validation
        if (!currentPassword || !newPassword || !confirmPassword) {
            showToast('Please fill in all password fields', 'error');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            showToast('New passwords do not match', 'error');
            return;
        }
        
        // Validate password strength
        if (
            newPassword.length < 12 ||
            !/[A-Z]/.test(newPassword) ||
            !/[a-z]/.test(newPassword) ||
            !/\d/.test(newPassword) ||
            !/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)
        ) {
            showToast('New password does not meet the strength requirements', 'error');
            return;
        }
        
        // Disable button and show loading
        passwordUpdateBtn.disabled = true;
        const originalButtonText = passwordUpdateBtn.innerHTML;
        passwordUpdateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        showLoading();
        
        try {
            const response = await fetch(`\${apiBaseUrl}/users/me/password`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword
                }),
                credentials: 'include'
            });
            
            const responseData = await response.json();
            
            if (response.ok) {
                showToast('Password updated successfully', 'success');
                passwordUpdateForm.reset();
                
                // Reset password strength meter
                passwordStrengthMeter.style.width = '0%';
                passwordStrengthMeter.className = 'password-strength-meter bg-red-500';
                
                // Reset password requirement indicators
                Object.keys(passwordRequirements).forEach(req => {
                    const element = passwordRequirements[req];
                    const icon = element.querySelector('i');
                    icon.className = 'fas fa-times-circle text-red-500 mr-1';
                });
            } else {
                let errorMessage = 'Failed to update password. ';
                
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        if (responseData.detail.includes('Invalid current password')) {
                            errorMessage = 'Current password is incorrect. Please try again.';
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
            }
        } catch (error) {
            console.error('Password update error:', error);
            showToast('Failed to update password. Please try again.', 'error');
        } finally {
            // Re-enable button and hide loading
            passwordUpdateBtn.disabled = false;
            passwordUpdateBtn.innerHTML = originalButtonText;
            hideLoading();
        }
    });
    
    // Handle language preferences form submission
    languagePreferencesForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const language = document.getElementById('language').value;
        
        // Disable button and show loading
        languageUpdateBtn.disabled = true;
        const originalButtonText = languageUpdateBtn.innerHTML;
        languageUpdateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
        
        try {
            // Save to localStorage for now (in a real app, you'd send this to your backend)
            localStorage.setItem('lang', language);
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            showToast('Language preferences saved successfully', 'success');
        } catch (error) {
            console.error('Language update error:', error);
            showToast('Failed to save language preferences. Please try again.', 'error');
        } finally {
            // Re-enable button
            languageUpdateBtn.disabled = false;
            languageUpdateBtn.innerHTML = originalButtonText;
        }
    });
    
    // Handle notification settings form submission
    notificationSettingsForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const emailNotifications = document.getElementById('email_notifications').checked;
        const securityAlerts = document.getElementById('security_alerts').checked;
        const marketingEmails = document.getElementById('marketing_emails').checked;
        
        // Disable button and show loading
        notificationUpdateBtn.disabled = true;
        const originalButtonText = notificationUpdateBtn.innerHTML;
        notificationUpdateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
        
        try {
            // Save to localStorage for now (in a real app, you'd send this to your backend)
            localStorage.setItem('notifications', JSON.stringify({
                email_notifications: emailNotifications,
                security_alerts: securityAlerts,
                marketing_emails: marketingEmails
            }));
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            showToast('Notification preferences saved successfully', 'success');
        } catch (error) {
            console.error('Notification settings update error:', error);
            showToast('Failed to save notification preferences. Please try again.', 'error');
        } finally {
            // Re-enable button
            notificationUpdateBtn.disabled = false;
            notificationUpdateBtn.innerHTML = originalButtonText;
        }
    });
    
    // Mobile sidebar toggle
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileSidebarContent = document.getElementById('mobileSidebarContent');
    const closeMobileSidebar = document.getElementById('closeMobileSidebar');
    
    mobileSidebarToggle.addEventListener('click', function() {
        mobileSidebar.classList.remove('hidden');
        setTimeout(() => {
            mobileSidebarContent.classList.remove('-translate-x-full');
        }, 10);
    });
    
    function closeSidebar() {
        mobileSidebarContent.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileSidebar.classList.add('hidden');
        }, 300);
    }
    
    closeMobileSidebar.addEventListener('click', closeSidebar);
    
    mobileSidebar.addEventListener('click', function(e) {
        if (e.target === mobileSidebar) {
            closeSidebar();
        }
    });
    
    // Sidebar logout
    const sidebarLogoutBtn = document.getElementById('sidebarLogoutBtn');
    const mobileSidebarLogoutBtn = document.getElementById('mobileSidebarLogoutBtn');
    
    function handleSidebarLogout() {
        handleLogout();
    }
    
    if (sidebarLogoutBtn) {
        sidebarLogoutBtn.addEventListener('click', handleSidebarLogout);
    }
    
    if (mobileSidebarLogoutBtn) {
        mobileSidebarLogoutBtn.addEventListener('click', handleSidebarLogout);
    }
    
    // Load notification settings from localStorage
    function loadNotificationSettings() {
        const savedSettings = localStorage.getItem('notifications');
        if (savedSettings) {
            const settings = JSON.parse(savedSettings);
            document.getElementById('email_notifications').checked = settings.email_notifications ?? true;
            document.getElementById('security_alerts').checked = settings.security_alerts ?? true;
            document.getElementById('marketing_emails').checked = settings.marketing_emails ?? false;
        }
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setGreeting();
        loadUserProfile();
        loadNotificationSettings();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>