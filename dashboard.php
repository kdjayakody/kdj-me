<?php
// Set page specific variables
$title = "Dashboard";
$description = "KDJ Lanka User Dashboard";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    /* Sidebar styles */
    @media (min-width: 768px) {
        .sidebar {
            width: 250px;
        }
        .content {
            margin-left: 250px;
        }
    }
    
    /* Dashboard card styles */
    .dashboard-card {
        transition: all 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Active sidebar link */
    .sidebar-link.active {
        background-color: rgba(203, 33, 39, 0.1);
        color: #cb2127;
        border-left: 3px solid #cb2127;
    }
</style>
HTML;

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
                    <a href="dashboard.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
                    <a href="https://singlish.kdj.lk" target="_blank" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-language w-5 h-5 mr-3 text-gray-500"></i>
                        <span>KDJ Singlish</span>
                    </a>
                </li>
                <li>
                    <a href="https://events.kdj.lk" target="_blank" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
                        <a href="dashboard.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
                        <a href="https://singlish.kdj.lk" target="_blank" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-language w-5 h-5 mr-3 text-gray-500"></i>
                            <span>KDJ Singlish</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://events.kdj.lk" target="_blank" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-kdj-dark mb-1">Dashboard</h1>
                    <p class="text-gray-600">Welcome to your KDJ Lanka account dashboard</p>
                </div>
                
                <div class="mt-4 md:mt-0 flex items-center space-x-2">
                    <span id="accountStatus" class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                        <span class="h-2 w-2 bg-green-500 rounded-full mr-1"></span>
                        Active Account
                    </span>
                    <span id="emailVerificationBadge" class="hidden bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Email Not Verified
                    </span>
                </div>
            </div>
            
            <!-- User Profile Summary -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="mb-4 md:mb-0 md:mr-6">
                        <div class="bg-kdj-red rounded-full p-4 text-white">
                            <i class="fas fa-user text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold mb-2" id="profileName">Loading...</h2>
                        <div class="flex flex-col md:flex-row md:space-x-6 text-gray-600">
                            <div class="flex items-center mb-2 md:mb-0">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span id="profileEmail">Loading...</span>
                            </div>
                            <div class="flex items-center mb-2 md:mb-0" id="profilePhoneContainer">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span id="profilePhone">Not set</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt mr-2 text-gray-400"></i>
                                <span id="profileMFA">MFA: Disabled</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="profile.php" class="text-kdj-red hover:text-red-800 flex items-center">
                            Edit Profile
                            <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <h2 class="text-xl font-semibold mb-4 text-kdj-dark">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="dashboard-card bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">Account Security</h3>
                        <div class="p-2 bg-blue-100 rounded-md">
                            <i class="fas fa-shield-alt text-blue-500"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Protect your account with additional security features.</p>
                    <a href="security.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        Configure Security
                        <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                </div>
                
                <div class="dashboard-card bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">Update Profile</h3>
                        <div class="p-2 bg-green-100 rounded-md">
                            <i class="fas fa-user-edit text-green-500"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Keep your profile information up to date.</p>
                    <a href="profile.php" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                        Edit Profile
                        <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                </div>
                
                <div class="dashboard-card bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">Change Password</h3>
                        <div class="p-2 bg-purple-100 rounded-md">
                            <i class="fas fa-key text-purple-500"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Update your password regularly for better security.</p>
                    <a href="settings.php" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center">
                        Change Password
                        <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- KDJ Services -->
            <h2 class="text-xl font-semibold mb-4 text-kdj-dark">KDJ Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="dashboard-card bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">KDJ Singlish</h3>
                        <div class="p-2 bg-yellow-100 rounded-md">
                            <i class="fas fa-language text-yellow-500"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Sinhala-English translation and language tools.</p>
                    <a href="https://singlish.kdj.lk" target="_blank" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium flex items-center">
                        Access Singlish
                        <i class="fas fa-external-link-alt ml-2"></i>
                    </a>
                </div>
                
                <div class="dashboard-card bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">KDJ Events</h3>
                        <div class="p-2 bg-pink-100 rounded-md">
                            <i class="fas fa-calendar-alt text-pink-500"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Discover and register for upcoming events in Sri Lanka.</p>
                    <a href="https://events.kdj.lk" target="_blank" class="text-pink-600 hover:text-pink-800 text-sm font-medium flex items-center">
                        Explore Events
                        <i class="fas fa-external-link-alt ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    
    // DOM elements
    const profileName = document.getElementById('profileName');
    const profileEmail = document.getElementById('profileEmail');
    const profilePhone = document.getElementById('profilePhone');
    const profileMFA = document.getElementById('profileMFA');
    const emailVerificationBadge = document.getElementById('emailVerificationBadge');
    const accountStatus = document.getElementById('accountStatus');
    
    // Load user profile data
    async function loadUserProfile() {
        try {
            // First check authentication
            await requireAuthentication();
            
            showLoading();
            
            // Make API request to get user data
            const response = await apiRequest('/users/me', {
                method: 'GET'
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch profile');
            }
            
            const userData = await response.json();
            
            // Update UI with user data
            updateUI(userData);
            
            hideLoading();
        } catch (error) {
            console.error('Failed to load user profile:', error);
            hideLoading();
            showToast('Failed to load profile data. Please try refreshing the page.', 'error');
        }
    }
    
    // Update UI with user data
    function updateUI(userData) {
        // Update sidebar and mobile sidebar
        document.getElementById('sidebarUserName').textContent = userData.display_name || userData.email;
        document.getElementById('mobileSidebarUserName').textContent = userData.display_name || userData.email;
        
        // Update profile section
        profileName.textContent = userData.display_name || 'No name set';
        profileEmail.textContent = userData.email;
        
        if (userData.phone_number) {
            profilePhone.textContent = userData.phone_number;
        } else {
            profilePhone.textContent = 'Not set';
        }
        
        // Update MFA status
        profileMFA.textContent = userData.mfa_enabled ? 'MFA: Enabled' : 'MFA: Disabled';
        
        // Update email verification badge
        if (!userData.email_verified) {
            emailVerificationBadge.classList.remove('hidden');
            // Update account status color
            accountStatus.className = 'bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center';
            accountStatus.innerHTML = '<span class="h-2 w-2 bg-yellow-500 rounded-full mr-1"></span>Pending Verification';
        } else {
            emailVerificationBadge.classList.add('hidden');
        }
        
        // Update header username if present
        const userDisplayName = document.getElementById('userDisplayName');
        if (userDisplayName) {
            userDisplayName.textContent = userData.display_name || userData.email;
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Update time-based greeting
        updatePageGreeting();
        
        // Load user profile data
        loadUserProfile();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>