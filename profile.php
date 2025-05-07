<?php
// Set page specific variables
$title = "Profile";
$description = "Manage your KDJ Lanka profile";
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
                    <a href="profile.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-user w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
                        <a href="profile.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-user w-5 h-5 mr-3 text-gray-500"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
                    <h1 class="text-2xl font-bold text-kdj-dark mb-1">Profile</h1>
                    <p class="text-gray-600">Manage your personal information</p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <span id="emailVerificationBadge" class="hidden bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Email Not Verified
                    </span>
                </div>
            </div>
            
            <!-- Profile Information Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Basic Information</h2>
                    <p class="text-sm text-gray-600">Manage your basic account information</p>
                </div>
                
                <div class="p-6">
                    <form id="profileUpdateForm">
                        <!-- User ID (Read-only) -->
                        <div class="mb-6">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User ID</label>
                            <input type="text" id="user_id" class="bg-gray-100 cursor-not-allowed w-full px-3 py-2 border border-gray-300 rounded-md text-gray-500" readonly>
                            <p class="mt-1 text-xs text-gray-500">Your unique user identifier (cannot be changed)</p>
                        </div>
                        
                        <!-- Email (Read-only) -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="flex items-center">
                                <input type="email" id="email" class="bg-gray-100 cursor-not-allowed w-full px-3 py-2 border border-gray-300 rounded-md text-gray-500" readonly>
                                <span id="emailVerifiedBadge" class="ml-2 hidden bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            </div>
                            <div id="emailVerificationSection" class="mt-2 hidden">
                                <p class="text-sm text-yellow-600 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Your email is not verified. Please verify your email to access all features.
                                </p>
                                <button type="button" id="resendVerificationBtn" class="text-xs bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-medium py-1 px-2 rounded">
                                    Resend Verification Email
                                </button>
                            </div>
                        </div>
                        
                        <!-- Display Name -->
                        <div class="mb-6">
                            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                            <input type="text" id="display_name" name="display_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red">
                            <p class="mt-1 text-xs text-gray-500">This is how your name will appear across KDJ Lanka services</p>
                        </div>
                        
                        <!-- Phone Number -->
                        <div class="mb-6">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" placeholder="+947XXXXXXXX">
                            <p class="mt-1 text-xs text-gray-500">Enter in E.164 format (e.g., +94771234567). This is used for account recovery and SMS notifications.</p>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" id="profileUpdateBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Account Management Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Account Management</h2>
                    <p class="text-sm text-gray-600">Manage your account settings and preferences</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Account Creation Date -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700">Account Created</h3>
                        <p id="accountCreatedDate" class="mt-1 text-gray-600">Loading...</p>
                    </div>
                    
                    <!-- Last Login -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700">Last Login</h3>
                        <p id="lastLoginDate" class="mt-1 text-gray-600">Loading...</p>
                    </div>
                    
                    <!-- Roles -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700">Account Roles</h3>
                        <div id="userRoles" class="mt-1 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Loading...
                            </span>
                        </div>
                    </div>
                    
                    <!-- Delete Account -->
                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-red-600">Danger Zone</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Once you delete your account, there is no going back. Please be certain.
                        </p>
                        <button type="button" id="deleteAccountBtn" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Delete Account Confirmation Modal -->
<div id="deleteAccountModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Account</h3>
            <p class="text-sm text-gray-600">
                Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be undone.
            </p>
        </div>
        <div class="mt-6">
            <div class="mb-4">
                <label for="deleteConfirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Enter your password to confirm</label>
                <input type="password" id="deleteConfirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="Your current password">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelDeleteBtn" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    
    // User profile data
    let userData = null;
    
    // DOM elements
    const profileUpdateForm = document.getElementById('profileUpdateForm');
    const profileUpdateBtn = document.getElementById('profileUpdateBtn');
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteConfirmPassword = document.getElementById('deleteConfirmPassword');
    const resendVerificationBtn = document.getElementById('resendVerificationBtn');
    
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
    
    // Format date for display
    function formatDate(dateString) {
        if (!dateString) return 'Never';
        
        const date = new Date(dateString);
        return date.toLocaleString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Load user profile data
    async function loadUserProfile() {
        try {
            showLoading();
            const authToken = sessionStorage.getItem('auth_token'); // Token එක sessionStorage එකෙන් ලබාගන්න

            const headers = {
                'Accept': 'application/json'
            };
            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`; // Authorization header එක මෙතනදි එකතු කරන්න
            }

            const response = await fetch(`\${apiBaseUrl}/users/me`, {
                method: 'GET',
                headers: headers, // යාවත්කාලීන වූ headers මෙතන භාවිතා කරන්න
                credentials: 'include' // Cookies යැවීම සඳහා (අවශ්‍ය නම්)
            });

            if (!response.ok) {
                // මෙතනදිත් 401 ආවොත්, ඒ කියන්නේ checkUserAuth එකෙන් පසුවත් token එක අවලංගු වෙලා
                // නැත්නම් වෙනත් ගැටළුවක්.
                console.error(`Failed to fetch profile from profile.php's loadUserProfile. Status: ${response.status}`);
                throw new Error('Failed to fetch profile'); //මෙමගින් catch block එකට යයි
            }

            userData = await response.json();

            // Update profile form
            updateProfileForm(userData);

            // Update account info
            updateAccountInfo(userData);

            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to load user profile:', error); // දෝෂය console එකේ පෙන්වයි
            showToast('Failed to load profile data. Please try refreshing the page.', 'error'); // රතු පාටින් දෝෂ පණිවිඩය පෙන්වයි
        }
    }

    
    // Update profile form with user data
    function updateProfileForm(user) {
        // Update sidebar
        document.getElementById('sidebarUserName').textContent = user.display_name || user.email;
        document.getElementById('mobileSidebarUserName').textContent = user.display_name || user.email;
        
        // Update form fields
        document.getElementById('user_id').value = user.uid;
        document.getElementById('email').value = user.email;
        document.getElementById('display_name').value = user.display_name || '';
        document.getElementById('phone_number').value = user.phone_number || '';
        
        // Update email verification status
        if (user.email_verified) {
            document.getElementById('emailVerifiedBadge').classList.remove('hidden');
            document.getElementById('emailVerificationSection').classList.add('hidden');
        } else {
            document.getElementById('emailVerificationBadge').classList.add('hidden');
            document.getElementById('emailVerificationSection').classList.remove('hidden');
            document.getElementById('emailVerificationBadge').classList.remove('hidden');
        }
        
        // Update header nav
        const userDisplayName = document.getElementById('userDisplayName');
        if (userDisplayName) {
            userDisplayName.textContent = user.display_name || user.email;
        }
    }
    
    // Update account information
    function updateAccountInfo(user) {
        // Account created date
        document.getElementById('accountCreatedDate').textContent = user.created_at ? formatDate(user.created_at) : 'Unknown';
        
        // Last login date
        document.getElementById('lastLoginDate').textContent = user.last_login ? formatDate(user.last_login) : 'Never';
        
        // User roles
        const rolesContainer = document.getElementById('userRoles');
        rolesContainer.innerHTML = '';
        
        if (user.roles && user.roles.length > 0) {
            user.roles.forEach(role => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                badge.textContent = role;
                rolesContainer.appendChild(badge);
            });
        } else {
            const badge = document.createElement('span');
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
            badge.textContent = 'User';
            rolesContainer.appendChild(badge);
        }
    }
    
    // Handle profile update form submission
    profileUpdateForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const displayName = document.getElementById('display_name').value;
        const phoneNumber = document.getElementById('phone_number').value;
        
        // Basic validation
        if (phoneNumber && !phoneNumber.match(/^\\+[1-9]\\d{1,14}$/)) {
            showToast('Phone number must be in E.164 format (e.g., +94771234567)', 'error');
            return;
        }
        
        // Prepare update data
        const updateData = {
            display_name: displayName || null,
            phone_number: phoneNumber || null
        };
        
        // Disable button and show loading
        profileUpdateBtn.disabled = true;
        const originalButtonText = profileUpdateBtn.innerHTML;
        profileUpdateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
        showLoading();
        
        try {
            const response = await fetch(`\${apiBaseUrl}/users/me`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(updateData),
                credentials: 'include'
            });
            
            const responseData = await response.json();
            
            if (response.ok) {
                // Update user data with response
                userData = responseData;
                
                // Update the form and sidebar
                updateProfileForm(userData);
                
                showToast('Profile updated successfully', 'success');
            } else {
                let errorMessage = 'Failed to update profile. ';
                
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        errorMessage += responseData.detail;
                    } else {
                        errorMessage += JSON.stringify(responseData.detail);
                    }
                } else {
                    errorMessage += `Error code: \${response.status}`;
                }
                
                showToast(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Profile update error:', error);
            showToast('Failed to update profile. Please try again.', 'error');
        } finally {
            // Re-enable button and hide loading
            profileUpdateBtn.disabled = false;
            profileUpdateBtn.innerHTML = originalButtonText;
            hideLoading();
        }
    });
    
    // Handle resend verification email button
    if (resendVerificationBtn) {
        resendVerificationBtn.addEventListener('click', async () => {
            resendVerificationBtn.disabled = true;
            const originalText = resendVerificationBtn.textContent;
            resendVerificationBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
            
            try {
                const response = await fetch(`\${apiBaseUrl}/auth/resend-verification-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: userData.email }),
                    credentials: 'include'
                });
                
                const responseData = await response.json();
                
                if (response.ok) {
                    showToast('Verification email sent. Please check your inbox.', 'success');
                } else {
                    showToast('Failed to send verification email. Please try again later.', 'error');
                }
            } catch (error) {
                console.error('Resend verification error:', error);
                showToast('Failed to send verification email. Please try again later.', 'error');
            } finally {
                resendVerificationBtn.disabled = false;
                resendVerificationBtn.textContent = originalText;
            }
        });
    }
    
    // Handle delete account button
    deleteAccountBtn.addEventListener('click', () => {
        deleteAccountModal.classList.remove('hidden');
        deleteConfirmPassword.value = '';
    });
    
    // Handle cancel delete
    cancelDeleteBtn.addEventListener('click', () => {
        deleteAccountModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    deleteAccountModal.addEventListener('click', (event) => {
        if (event.target === deleteAccountModal) {
            deleteAccountModal.classList.add('hidden');
        }
    });
    
    // Handle confirm delete
    confirmDeleteBtn.addEventListener('click', async () => {
        const password = deleteConfirmPassword.value;
        
        if (!password) {
            showToast('Please enter your password to confirm deletion', 'error');
            return;
        }
        
        // Disable button and show loading
        confirmDeleteBtn.disabled = true;
        const originalButtonText = confirmDeleteBtn.innerHTML;
        confirmDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Deleting...';
        
        try {
            // First authenticate to verify password
            const authResponse = await fetch(`\${apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: userData.email,
                    password: password,
                    remember_me: false
                }),
                credentials: 'include'
            });
            
            if (!authResponse.ok) {
                showToast('Incorrect password. Please try again.', 'error');
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.innerHTML = originalButtonText;
                return;
            }
            
            // Now proceed with account deletion
            const deleteResponse = await fetch(`\${apiBaseUrl}/users/me`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });
            
            if (deleteResponse.ok) {
                showToast('Your account has been deleted successfully', 'success');
                
                // Redirect to login page after a short delay
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);
            } else {
                const responseData = await deleteResponse.json();
                let errorMessage = 'Failed to delete account. ';
                
                if (responseData.detail) {
                    errorMessage += responseData.detail;
                }
                
                showToast(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Delete account error:', error);
            showToast('Failed to delete account. Please try again.', 'error');
        } finally {
            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.innerHTML = originalButtonText;
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
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setGreeting();
        loadUserProfile();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>