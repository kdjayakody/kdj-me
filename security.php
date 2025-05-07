<?php
// Set page specific variables
$title = "Security";
$description = "Manage your KDJ Lanka account security settings";
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
                    <a href="settings.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="security.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
                        <a href="settings.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="security.php" class="sidebar-link active flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
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
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-kdj-dark mb-1">Security Settings</h1>
                    <p class="text-gray-600">Manage your account security and protection</p>
                </div>
            </div>
            
            <!-- Security Overview Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Security Overview</h2>
                    <p class="text-sm text-gray-600">Current security status of your account</p>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Security Checklist</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <div id="emailVerifiedIndicator" class="flex-shrink-0 h-5 w-5 text-green-500 mt-0.5">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Email verified</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div id="strongPasswordIndicator" class="flex-shrink-0 h-5 w-5 text-green-500 mt-0.5">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Strong password set</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div id="mfaEnabledIndicator" class="flex-shrink-0 h-5 w-5 text-red-500 mt-0.5">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Two-factor authentication (2FA)</p>
                                    <p class="text-xs text-gray-500" id="mfaStatusText">Not enabled</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div id="recentLoginIndicator" class="flex-shrink-0 h-5 w-5 text-green-500 mt-0.5">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Recent account activity</p>
                                    <p class="text-xs text-gray-500" id="lastLoginText">Last login: Loading...</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <div id="securityScoreContainer" class="bg-yellow-50 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shield-alt text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Security Score</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div id="securityScoreBar" class="bg-yellow-400 h-2.5 rounded-full" style="width: 66%"></div>
                                            </div>
                                            <span id="securityScoreText" class="ml-2 text-sm font-medium text-yellow-800">66%</span>
                                        </div>
                                        <p class="mt-2" id="securityScoreMessage">Your account security can be improved. Enable two-factor authentication for better protection.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Two-Factor Authentication Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-medium text-kdj-dark">Two-Factor Authentication (2FA)</h2>
                            <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                        </div>
                        <div>
                            <span id="mfaStatusBadge" class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center">
                                <i class="fas fa-times-circle mr-1 text-gray-500"></i>
                                Disabled
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- MFA Disabled State -->
                    <div id="mfaDisabledState">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-lock text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Secure your account with 2FA</h3>
                                <p class="text-sm text-gray-500">Two-factor authentication adds an additional security layer to your account, requiring a security code along with your password.</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-md mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">How it works:</h4>
                            <ol class="list-decimal list-inside text-sm text-gray-700 space-y-2">
                                <li>Set up an authenticator app on your mobile device</li>
                                <li>Scan the QR code or enter the setup key</li>
                                <li>Enter the verification code to confirm setup</li>
                                <li>Next time you log in, you'll need to provide a code from your app</li>
                            </ol>
                        </div>
                        
                        <div class="flex justify-end">
                            <button id="setupMfaBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Enable Two-Factor Authentication
                            </button>
                        </div>
                    </div>
                    
                    <!-- MFA Setup State -->
                    <div id="mfaSetupState" class="hidden">
                        <div class="bg-gray-50 p-4 rounded-md mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Setup Instructions</h3>
                            <ol class="list-decimal list-inside text-sm text-gray-700 space-y-4">
                                <li>
                                    <p>Download and install an authenticator app on your mobile device:</p>
                                    <div class="flex flex-wrap mt-2 gap-2">
                                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md text-xs text-gray-700 hover:bg-gray-50">
                                            <i class="fab fa-google mr-1"></i> Google Authenticator
                                        </a>
                                        <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md text-xs text-gray-700 hover:bg-gray-50">
                                            <i class="fab fa-apple mr-1"></i> Google Authenticator
                                        </a>
                                        <a href="https://authy.com/download/" target="_blank" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md text-xs text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-mobile-alt mr-1"></i> Authy
                                        </a>
                                    </div>
                                </li>
                                <li class="pt-2">
                                    <p>Scan this QR code with your authenticator app:</p>
                                    <div class="mt-2 flex justify-center py-4">
                                        <div id="qrCodeContainer" class="p-2 bg-white border-2 border-gray-300 rounded-md">
                                            <div id="qrCode" class="w-48 h-48 flex items-center justify-center bg-gray-100">
                                                <div class="animate-pulse text-gray-400">
                                                    <i class="fas fa-spinner fa-spin text-3xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="pt-2">
                                    <p>Or manually enter this setup key in your app:</p>
                                    <div class="mt-2 flex items-center justify-center">
                                        <div class="bg-gray-100 px-4 py-2 rounded text-mono text-sm" id="setupKey">Loading...</div>
                                        <button type="button" id="copySetupKeyBtn" class="ml-2 text-gray-500 hover:text-gray-700">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </li>
                                <li class="pt-4">
                                    <p>Enter the 6-digit code from your authenticator app:</p>
                                    <form id="verifyMfaForm" class="mt-3">
                                        <div class="flex items-center">
                                            <input type="text" id="verificationCode" maxlength="6" class="w-40 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" placeholder="000000" required pattern="[0-9]{6}">
                                            <button type="submit" id="verifyMfaBtn" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                                Verify
                                            </button>
                                        </div>
                                    </form>
                                </li>
                            </ol>
                        </div>
                        
                        <div class="flex justify-between items-center mt-6">
                            <button id="cancelMfaSetupBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                                Cancel
                            </button>
                        </div>
                    </div>
                    
                    <!-- MFA Enabled State -->
                    <div id="mfaEnabledState" class="hidden">
                        <div class="flex items-center mb-6">
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Two-Factor Authentication is enabled</h3>
                                <p class="text-sm text-gray-500">Your account has an extra layer of security. You'll need your authenticator app to log in.</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-md mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Recovery Backup Codes</h4>
                            <p class="text-sm text-gray-700 mb-4">Use these backup codes to log in if you lose access to your authenticator app. Each code can only be used once.</p>
                            
                            <div id="backupCodesContainer" class="grid grid-cols-2 gap-2 mb-4">
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                                <div class="text-mono text-sm bg-white p-2 rounded border border-gray-300">Loading...</div>
                            </div>
                            
                            <div class="flex flex-wrap justify-center gap-3">
                                <button id="downloadBackupCodesBtn" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-kdj-red">
                                    <i class="fas fa-download mr-1.5"></i> Download Codes
                                </button>
                                <button id="copyBackupCodesBtn" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-kdj-red">
                                    <i class="far fa-copy mr-1.5"></i> Copy All Codes
                                </button>
                                <button id="printBackupCodesBtn" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-kdj-red">
                                    <i class="fas fa-print mr-1.5"></i> Print Codes
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button id="disableMfaBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Disable Two-Factor Authentication
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Login Activity Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-kdj-dark">Recent Login Activity</h2>
                    <p class="text-sm text-gray-600">Monitor recent logins to your account</p>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device / Browser</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="loginActivityTable">
                                <!-- Table will be populated by JS -->
                                <tr class="animate-pulse">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Loading...</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Loading...</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Loading...</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Loading...</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Disable MFA Confirmation Modal -->
<div id="disableMfaModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Disable Two-Factor Authentication</h3>
            <p class="text-sm text-gray-600">
                Are you sure you want to disable two-factor authentication? This will make your account less secure.
            </p>
        </div>
        <div class="mt-6">
            <div class="mb-4">
                <label for="disableMfaPassword" class="block text-sm font-medium text-gray-700 mb-1">Enter your password to confirm</label>
                <input type="password" id="disableMfaPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="Your current password" required>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelDisableMfaBtn" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    Cancel
                </button>
                <button type="button" id="confirmDisableMfaBtn" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Disable 2FA
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
    
    // User data
    let userData = null;
    let mfaData = null;
    let backupCodes = [];
    
    // DOM elements for MFA states
    const mfaDisabledState = document.getElementById('mfaDisabledState');
    const mfaSetupState = document.getElementById('mfaSetupState');
    const mfaEnabledState = document.getElementById('mfaEnabledState');
    
    // DOM elements for MFA setup/verification
    const setupMfaBtn = document.getElementById('setupMfaBtn');
    const qrCode = document.getElementById('qrCode');
    const setupKey = document.getElementById('setupKey');
    const verifyMfaForm = document.getElementById('verifyMfaForm');
    const verificationCode = document.getElementById('verificationCode');
    const verifyMfaBtn = document.getElementById('verifyMfaBtn');
    const cancelMfaSetupBtn = document.getElementById('cancelMfaSetupBtn');
    const copySetupKeyBtn = document.getElementById('copySetupKeyBtn');
    
    // DOM elements for MFA enabled state
    const backupCodesContainer = document.getElementById('backupCodesContainer');
    const downloadBackupCodesBtn = document.getElementById('downloadBackupCodesBtn');
    const copyBackupCodesBtn = document.getElementById('copyBackupCodesBtn');
    const printBackupCodesBtn = document.getElementById('printBackupCodesBtn');
    const disableMfaBtn = document.getElementById('disableMfaBtn');
    
    // DOM elements for security indicators
    const emailVerifiedIndicator = document.getElementById('emailVerifiedIndicator');
    const strongPasswordIndicator = document.getElementById('strongPasswordIndicator');
    const mfaEnabledIndicator = document.getElementById('mfaEnabledIndicator');
    const recentLoginIndicator = document.getElementById('recentLoginIndicator');
    const lastLoginText = document.getElementById('lastLoginText');
    const mfaStatusBadge = document.getElementById('mfaStatusBadge');
    const mfaStatusText = document.getElementById('mfaStatusText');
    const securityScoreBar = document.getElementById('securityScoreBar');
    const securityScoreText = document.getElementById('securityScoreText');
    const securityScoreMessage = document.getElementById('securityScoreMessage');
    const securityScoreContainer = document.getElementById('securityScoreContainer');
    
    // DOM elements for disable MFA modal
    const disableMfaModal = document.getElementById('disableMfaModal');
    const disableMfaPassword = document.getElementById('disableMfaPassword');
    const cancelDisableMfaBtn = document.getElementById('cancelDisableMfaBtn');
    const confirmDisableMfaBtn = document.getElementById('confirmDisableMfaBtn');
    
    // Load user profile with security information
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
            
            userData = await response.json();
            
            // Update sidebar with user info
            document.getElementById('sidebarUserName').textContent = userData.display_name || userData.email;
            document.getElementById('mobileSidebarUserName').textContent = userData.display_name || userData.email;
            
            // Update greeting
            updatePageGreeting();
            
            // Update security overview
            updateSecurityOverview(userData);
            
            // Update MFA state based on user data
            if (userData.mfa_enabled) {
                await loadBackupCodes();
                updateMfaEnabledState();
            } else {
                updateMfaDisabledState();
            }
            
            // Load login activity
            loadLoginActivity();
            
            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to load user profile:', error);
            showToast('Failed to load profile data. Please try refreshing the page.', 'error');
        }
    }
    
    // Update security overview based on user data
    function updateSecurityOverview(user) {
        // Update email verification status
        if (user.email_verified) {
            emailVerifiedIndicator.innerHTML = '<i class="fas fa-check-circle"></i>';
            emailVerifiedIndicator.className = 'flex-shrink-0 h-5 w-5 text-green-500 mt-0.5';
        } else {
            emailVerifiedIndicator.innerHTML = '<i class="fas fa-times-circle"></i>';
            emailVerifiedIndicator.className = 'flex-shrink-0 h-5 w-5 text-red-500 mt-0.5';
        }
        
        // Update MFA status
        if (user.mfa_enabled) {
            mfaEnabledIndicator.innerHTML = '<i class="fas fa-check-circle"></i>';
            mfaEnabledIndicator.className = 'flex-shrink-0 h-5 w-5 text-green-500 mt-0.5';
            mfaStatusText.textContent = 'Enabled';
            
            mfaStatusBadge.innerHTML = '<i class="fas fa-check-circle mr-1 text-green-500"></i> Enabled';
            mfaStatusBadge.className = 'bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center';
        } else {
            mfaEnabledIndicator.innerHTML = '<i class="fas fa-times-circle"></i>';
            mfaEnabledIndicator.className = 'flex-shrink-0 h-5 w-5 text-red-500 mt-0.5';
            mfaStatusText.textContent = 'Not enabled';
            
            mfaStatusBadge.innerHTML = '<i class="fas fa-times-circle mr-1 text-red-500"></i> Disabled';
            mfaStatusBadge.className = 'bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center';
        }
        
        // Update last login info
        if (user.last_login) {
            lastLoginText.textContent = `Last login: \${formatDate(user.last_login)}`;
        } else {
            lastLoginText.textContent = 'No recent login activity';
        }
        
        // Calculate security score
        let score = 0;
        const totalItems = 3; // Email verified, strong password, MFA
        
        if (user.email_verified) score++;
        score++; // Assume strong password (this would be a real check in a production app)
        if (user.mfa_enabled) score++;
        
        const scorePercentage = Math.round((score / totalItems) * 100);
        securityScoreBar.style.width = `\${scorePercentage}%`;
        securityScoreText.textContent = `\${scorePercentage}%`;
        
        // Update score color and message based on percentage
        if (scorePercentage < 70) {
            securityScoreBar.className = 'bg-yellow-400 h-2.5 rounded-full';
            securityScoreContainer.className = 'bg-yellow-50 p-4 rounded-md';
            securityScoreMessage.textContent = 'Your account security can be improved. Enable two-factor authentication for better protection.';
        } else if (scorePercentage === 100) {
            securityScoreBar.className = 'bg-green-500 h-2.5 rounded-full';
            securityScoreContainer.className = 'bg-green-50 p-4 rounded-md';
            securityScoreMessage.textContent = 'Great job! Your account has all recommended security features enabled.';
        } else {
            securityScoreBar.className = 'bg-yellow-400 h-2.5 rounded-full';
            securityScoreContainer.className = 'bg-yellow-50 p-4 rounded-md';
            securityScoreMessage.textContent = 'Your account security can be improved. Complete all security checklist items for better protection.';
        }
    }
    
    // Update UI to show MFA disabled state
    function updateMfaDisabledState() {
        mfaDisabledState.classList.remove('hidden');
        mfaSetupState.classList.add('hidden');
        mfaEnabledState.classList.add('hidden');
    }
    
    // Update UI to show MFA setup state
    function updateMfaSetupState() {
        mfaDisabledState.classList.add('hidden');
        mfaSetupState.classList.remove('hidden');
        mfaEnabledState.classList.add('hidden');
    }
    
    // Update UI to show MFA enabled state
    function updateMfaEnabledState() {
        mfaDisabledState.classList.add('hidden');
        mfaSetupState.classList.add('hidden');
        mfaEnabledState.classList.remove('hidden');
        
        // Update backup codes display
        updateBackupCodesDisplay();
    }
    
    // Start MFA setup process
    async function setupMfa() {
        try {
            showLoading();
            
            // Request MFA setup data from API
            const response = await apiRequest('/auth/mfa/setup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    method: 'totp' 
                })
            });
            
            if (!response.ok) {
                throw new Error('Failed to set up MFA');
            }
            
            mfaData = await response.json();
            
            // Update UI with QR code and setup key
            qrCode.innerHTML = `<img src="\${mfaData.qrcode_url || mfaData.qr_code_url}" alt="QR Code" class="w-48 h-48">`;
            setupKey.textContent = mfaData.secret || mfaData.setup_key;
            
            // Switch to setup state
            updateMfaSetupState();
            
            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to set up MFA:', error);
            showToast('Failed to set up two-factor authentication. Please try again.', 'error');
        }
    }
    
    // Verify MFA setup with verification code
    async function verifyMfaSetup(code) {
        try {
            showLoading();
            
            // Validate code format
            if (!code || code.length !== 6 || !/^\d{6}$/.test(code)) {
                hideLoading();
                showToast('Please enter a valid 6-digit verification code.', 'error');
                return;
            }
            
            // Send verification request to API
            const response = await apiRequest('/auth/mfa/verify-setup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    code: code,
                    secret: mfaData.secret || mfaData.setup_key
                })
            });
            
            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.detail || 'Failed to verify MFA setup');
            }
            
            // Get response data including backup codes
            const responseData = await response.json();
            
            // Store backup codes if provided
            if (responseData.backup_codes) {
                backupCodes = responseData.backup_codes;
            }
            
            // Update user data with MFA enabled
            userData.mfa_enabled = true;
            userData.mfa_methods = ['totp'];
            
            // Update security overview to reflect MFA enabled
            updateSecurityOverview(userData);
            
            // Switch to enabled state
            updateMfaEnabledState();
            
            showToast('Two-factor authentication enabled successfully!', 'success');
            
            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to verify MFA setup:', error);
            showToast('Failed to verify code. Please check your authenticator app and try again.', 'error');
        }
    }
    
    // Load backup codes for an account with MFA already enabled
    async function loadBackupCodes() {
        try {
            // Only try to load backup codes if MFA is enabled
            if (!userData || !userData.mfa_enabled) return;
            
            const response = await apiRequest('/auth/mfa/backup-codes', {
                method: 'GET'
            });
            
            if (!response.ok) {
                throw new Error('Failed to load backup codes');
            }
            
            const data = await response.json();
            
            if (data.backup_codes && data.backup_codes.length > 0) {
                backupCodes = data.backup_codes;
                updateBackupCodesDisplay();
            }
        } catch (error) {
            console.error('Failed to load backup codes:', error);
            // Non-fatal error, continue with UI updates
        }
    }
    
    // Update the display of backup codes in the UI
    function updateBackupCodesDisplay() {
        if (!backupCodes || backupCodes.length === 0) {
            // If no backup codes available, show message
            backupCodesContainer.innerHTML = `
                <div class="col-span-2 text-center text-sm text-gray-500 p-4">
                    No backup codes available. Please generate new backup codes.
                </div>
                <div class="col-span-2 text-center">
                    <button id="generateBackupCodesBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                        <i class="fas fa-key mr-2"></i>
                        Generate Backup Codes
                    </button>
                </div>
            `;
            
            // Add event listener to the generate button
            const generateBtn = document.getElementById('generateBackupCodesBtn');
            if (generateBtn) {
                generateBtn.addEventListener('click', generateNewBackupCodes);
            }
            
            return;
        }
        
        // Clear the container
        backupCodesContainer.innerHTML = '';
        
        // Add each backup code to the container
        backupCodes.forEach(code => {
            const codeElement = document.createElement('div');
            codeElement.className = 'text-mono text-sm bg-white p-2 rounded border border-gray-300';
            codeElement.textContent = formatBackupCode(code);
            backupCodesContainer.appendChild(codeElement);
        });
    }
    
    // Format a backup code for display (add dashes for readability)
    function formatBackupCode(code) {
        if (code.includes('-')) return code; // Already formatted
        
        // Add a dash every 4 characters
        return code.match(/.{1,4}/g).join('-');
    }
    
    // Generate new backup codes
    async function generateNewBackupCodes() {
        try {
            showLoading();
            
            const response = await apiRequest('/auth/mfa/generate-backup-codes', {
                method: 'POST'
            });
            
            if (!response.ok) {
                throw new Error('Failed to generate new backup codes');
            }
            
            const data = await response.json();
            
            if (data.backup_codes) {
                backupCodes = data.backup_codes;
                updateBackupCodesDisplay();
                showToast('New backup codes generated successfully!', 'success');
            } else {
                throw new Error('No backup codes returned from server');
            }
            
            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to generate backup codes:', error);
            showToast('Failed to generate new backup codes. Please try again.', 'error');
        }
    }
    
    // Disable MFA
    async function disableMfa(password) {
        try {
            showLoading();
            
            // Validate input
            if (!password) {
                hideLoading();
                showToast('Password is required to disable two-factor authentication.', 'error');
                return;
            }
            
            // Send request to disable MFA
            const response = await apiRequest('/auth/mfa/disable', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    password: password 
                })
            });
            
            if (!response.ok) {
                const data = await response.json();
                throw new Error(data.detail || 'Failed to disable MFA');
            }
            
            // Update user data
            userData.mfa_enabled = false;
            userData.mfa_methods = [];
            
            // Update security overview
            updateSecurityOverview(userData);
            
            // Switch to disabled state
            updateMfaDisabledState();
            
            showToast('Two-factor authentication disabled successfully.', 'success');
            
            hideLoading();
        } catch (error) {
            hideLoading();
            console.error('Failed to disable MFA:', error);
            showToast('Failed to disable two-factor authentication. Please check your password and try again.', 'error');
        }
    }
    
    // Handle downloading backup codes
    function downloadBackupCodes() {
        if (!backupCodes || backupCodes.length === 0) {
            showToast('No backup codes available to download.', 'error');
            return;
        }
        
        // Create text content for download
        const content = [
            'KDJ LANKA - TWO-FACTOR AUTHENTICATION BACKUP CODES',
            '=====================================================',
            'Keep these backup codes in a safe place. Each code can only be used once.',
            '',
            ...backupCodes.map(code => formatBackupCode(code)),
            '',
            `Generated on: \${new Date().toLocaleString()}`,
            'For: ' + (userData.email || 'Your KDJ Lanka Account')
        ].join('\\n');
        
        // Create blob and download link
        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'kdj-lanka-backup-codes.txt';
        document.body.appendChild(a);
        a.click();
        
        // Clean up
        setTimeout(() => {
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }, 100);
        
        showToast('Backup codes downloaded successfully.', 'success');
    }
    
    // Handle copying backup codes to clipboard
    function copyBackupCodes() {
        if (!backupCodes || backupCodes.length === 0) {
            showToast('No backup codes available to copy.', 'error');
            return;
        }
        
        // Create formatted text for clipboard
        const content = backupCodes.map(code => formatBackupCode(code)).join('\\n');
        
        // Copy to clipboard
        navigator.clipboard.writeText(content)
            .then(() => {
                showToast('Backup codes copied to clipboard.', 'success');
            })
            .catch(err => {
                console.error('Failed to copy backup codes:', err);
                showToast('Failed to copy backup codes. Please try again.', 'error');
            });
    }
    
    // Handle printing backup codes
    function printBackupCodes() {
        if (!backupCodes || backupCodes.length === 0) {
            showToast('No backup codes available to print.', 'error');
            return;
        }
        
        // Create a printable page
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>KDJ Lanka - Backup Codes</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h1 { font-size: 18px; margin-bottom: 10px; }
                    .codes { margin: 20px 0; }
                    .code { 
                        font-family: monospace; 
                        font-size: 16px; 
                        padding: 8px; 
                        margin: 5px 0; 
                        border: 1px solid #ccc; 
                        border-radius: 4px; 
                        background: #f8f8f8; 
                    }
                    .info { font-size: 12px; color: #666; margin-top: 20px; }
                </style>
            </head>
            <body>
                <h1>KDJ Lanka - Two-Factor Authentication Backup Codes</h1>
                <p>Keep these backup codes in a safe place. Each code can only be used once.</p>
                <div class="codes">
                    \${backupCodes.map(code => `<div class="code">\${formatBackupCode(code)}</div>`).join('')}
                </div>
                <div class="info">
                    <p>Generated on: \${new Date().toLocaleString()}</p>
                    <p>For: \${userData.email || 'Your KDJ Lanka Account'}</p>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                    };
                </script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    // Load login activity data
    function loadLoginActivity() {
        // In a real app, this would fetch login history from an API
        // For demo purposes, we'll create sample data with the actual last login from user data
        
        const loginActivity = [
            {
                date: userData && userData.last_login ? new Date(userData.last_login) : new Date(),
                ip: '103.24.55.162',
                device: 'Chrome on Windows',
                location: 'Colombo, Sri Lanka',
                status: 'success'
            },
            {
                date: new Date(Date.now() - 86400000), // 1 day ago
                ip: '103.24.55.162',
                device: 'Safari on iPhone',
                location: 'Colombo, Sri Lanka',
                status: 'success'
            },
            {
                date: new Date(Date.now() - 172800000), // 2 days ago
                ip: '45.121.33.89',
                device: 'Firefox on Mac',
                location: 'Kandy, Sri Lanka',
                status: 'failed'
            }
        ];
        
        const loginActivityTable = document.getElementById('loginActivityTable');
        loginActivityTable.innerHTML = '';
        
        loginActivity.forEach(login => {
            const row = document.createElement('tr');
            
            // Date cell
            const dateCell = document.createElement('td');
            dateCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            dateCell.textContent = formatDate(login.date);
            row.appendChild(dateCell);
            
            // IP cell
            const ipCell = document.createElement('td');
            ipCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            ipCell.textContent = login.ip;
            row.appendChild(ipCell);
            
            // Device cell
            const deviceCell = document.createElement('td');
            deviceCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            deviceCell.textContent = login.device;
            row.appendChild(deviceCell);
            
            // Location cell
            const locationCell = document.createElement('td');
            locationCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            locationCell.textContent = login.location;
            row.appendChild(locationCell);
            
            // Status cell
            const statusCell = document.createElement('td');
            statusCell.className = 'px-6 py-4 whitespace-nowrap text-sm';
            
            const statusBadge = document.createElement('span');
            if (login.status === 'success') {
                statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                statusBadge.textContent = 'Success';
            } else {
                statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                statusBadge.textContent = 'Failed';
            }
            
            statusCell.appendChild(statusBadge);
            row.appendChild(statusCell);
            
            loginActivityTable.appendChild(row);
        });
    }
    
    // Event listeners
    
    // Setup MFA button click
    setupMfaBtn.addEventListener('click', setupMfa);
    
    // Cancel MFA setup button click
    cancelMfaSetupBtn.addEventListener('click', () => {
        updateMfaDisabledState();
    });
    
    // Verify MFA form submission
    verifyMfaForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const code = verificationCode.value.trim();
        verifyMfaSetup(code);
    });
    
    // Disable MFA button click
    disableMfaBtn.addEventListener('click', () => {
        disableMfaModal.classList.remove('hidden');
        disableMfaPassword.value = '';
    });
    
    // Cancel disable MFA button click
    cancelDisableMfaBtn.addEventListener('click', () => {
        disableMfaModal.classList.add('hidden');
    });
    
    // Close disable MFA modal when clicking outside
    disableMfaModal.addEventListener('click', (event) => {
        if (event.target === disableMfaModal) {
            disableMfaModal.classList.add('hidden');
        }
    });
    
    // Confirm disable MFA button click
    confirmDisableMfaBtn.addEventListener('click', () => {
        const password = disableMfaPassword.value;
        
        if (!password) {
            showToast('Please enter your password to confirm', 'error');
            return;
        }
        
        disableMfaModal.classList.add('hidden');
        disableMfa(password);
    });
    
    // Copy setup key button click
    copySetupKeyBtn.addEventListener('click', () => {
        const setupKeyValue = setupKey.textContent;
        navigator.clipboard.writeText(setupKeyValue)
            .then(() => {
                showToast('Setup key copied to clipboard', 'success');
            })
            .catch(err => {
                console.error('Failed to copy setup key:', err);
                showToast('Failed to copy setup key. Please try again.', 'error');
            });
    });
    
    // Download backup codes button click
    downloadBackupCodesBtn.addEventListener('click', downloadBackupCodes);
    
    // Copy backup codes button click
    copyBackupCodesBtn.addEventListener('click', copyBackupCodes);
    
    // Print backup codes button click
    printBackupCodesBtn.addEventListener('click', printBackupCodes);
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Load user profile and security data
        loadUserProfile();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>