<?php
// Set page specific variables
$title = "Test Authentication";
$description = "Test authentication across domains for KDJ Lanka";

// Include header
include 'header.php';
?>

<div class="container mx-auto px-4 pt-16 pb-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-kdj-red mb-10">Authentication Test Page</h1>
        
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Current Authentication Status</h2>
            <div id="authStatus" class="p-4 bg-gray-100 rounded">
                <p>Checking authentication status...</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Token Information</h2>
                <div id="tokenInfo" class="p-4 bg-gray-100 rounded">
                    <p>Loading token information...</p>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Test Actions</h2>
                <div class="space-y-4">
                    <button id="checkAuthBtn" class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Check Authentication Status
                    </button>
                    <button id="refreshTokenBtn" class="w-full py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600 transition">
                        Refresh Token
                    </button>
                    <button id="clearTokensBtn" class="w-full py-2 px-4 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                        Clear Local Tokens
                    </button>
                    <button id="goToLoginBtn" class="w-full py-2 px-4 bg-kdj-red text-white rounded hover:bg-red-800 transition">
                        Go to Login Page
                    </button>
                    <button id="goToDashboardBtn" class="w-full py-2 px-4 bg-purple-500 text-white rounded hover:bg-purple-600 transition">
                        Go to Dashboard
                    </button>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-xl font-semibold mb-4">Test Results</h2>
            <div id="testResults" class="p-4 bg-gray-100 rounded">
                <p>No tests have been run yet.</p>
            </div>
        </div>
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // DOM Elements
    const authStatusEl = document.getElementById('authStatus');
    const tokenInfoEl = document.getElementById('tokenInfo');
    const testResultsEl = document.getElementById('testResults');
    const checkAuthBtn = document.getElementById('checkAuthBtn');
    const refreshTokenBtn = document.getElementById('refreshTokenBtn');
    const clearTokensBtn = document.getElementById('clearTokensBtn');
    const goToLoginBtn = document.getElementById('goToLoginBtn');
    const goToDashboardBtn = document.getElementById('goToDashboardBtn');
    
    // Functions
    async function checkAuthStatus() {
        updateTestResults('Checking authentication status...');
        
        try {
            const isAuthenticated = await handleCrossDomainAuth(false);
            
            if (isAuthenticated) {
                authStatusEl.innerHTML = `
                    <div class="flex items-center mb-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-500 rounded-full mr-2 text-white">
                            <i class="fas fa-check text-xs"></i>
                        </span>
                        <span class="font-semibold text-green-700">Authenticated</span>
                    </div>
                    <p class="text-gray-600">Your session is active and you are authenticated.</p>
                `;
                
                updateTestResults('Authentication check successful - you are logged in.', 'success');
            } else {
                authStatusEl.innerHTML = `
                    <div class="flex items-center mb-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-red-500 rounded-full mr-2 text-white">
                            <i class="fas fa-times text-xs"></i>
                        </span>
                        <span class="font-semibold text-red-700">Not Authenticated</span>
                    </div>
                    <p class="text-gray-600">You are not logged in or your session has expired.</p>
                `;
                
                updateTestResults('Authentication check completed - you are not logged in.', 'error');
            }
        } catch (error) {
            console.error('Auth check error:', error);
            authStatusEl.innerHTML = `
                <div class="flex items-center mb-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-yellow-500 rounded-full mr-2 text-white">
                        <i class="fas fa-exclamation text-xs"></i>
                    </span>
                    <span class="font-semibold text-yellow-700">Error Checking Authentication</span>
                </div>
                <p class="text-gray-600">An error occurred while checking your authentication status.</p>
            `;
            
            updateTestResults('Error checking authentication: ' + error.message, 'error');
        }
        
        updateTokenInfo();
    }
    
    function updateTokenInfo() {
        const authToken = sessionStorage.getItem('auth_token');
        const tokenExpiry = sessionStorage.getItem('token_expiry');
        const refreshToken = localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token');
        
        let html = '<dl class="divide-y divide-gray-200">';
        
        // Auth Token
        html += `
            <div class="py-3 flex flex-col md:flex-row">
                <dt class="text-sm font-medium text-gray-500 md:w-1/3">Auth Token</dt>
                <dd class="mt-1 text-sm text-gray-900 md:mt-0 md:w-2/3 break-all">
                    ${authToken ? authToken.slice(0, 20) + '...' : 'None'}
                </dd>
            </div>
        `;
        
        // Token Expiry
        let expiryInfo = 'None';
        if (tokenExpiry) {
            const expiryDate = new Date(parseInt(tokenExpiry));
            const now = new Date();
            const diffMs = expiryDate - now;
            const diffMins = Math.round(diffMs / 60000);
            
            if (diffMins > 0) {
                expiryInfo = `${expiryDate.toLocaleString()} (${diffMins} minutes remaining)`;
            } else {
                expiryInfo = `${expiryDate.toLocaleString()} (EXPIRED)`;
            }
        }
        
        html += `
            <div class="py-3 flex flex-col md:flex-row">
                <dt class="text-sm font-medium text-gray-500 md:w-1/3">Token Expiry</dt>
                <dd class="mt-1 text-sm text-gray-900 md:mt-0 md:w-2/3">
                    ${expiryInfo}
                </dd>
            </div>
        `;
        
        // Refresh Token
        html += `
            <div class="py-3 flex flex-col md:flex-row">
                <dt class="text-sm font-medium text-gray-500 md:w-1/3">Refresh Token</dt>
                <dd class="mt-1 text-sm text-gray-900 md:mt-0 md:w-2/3 break-all">
                    ${refreshToken ? refreshToken.slice(0, 20) + '...' : 'None'}
                </dd>
            </div>
        `;
        
        // Refresh Token Storage
        let storageLocation = 'None';
        if (localStorage.getItem('refresh_token')) {
            storageLocation = 'localStorage (Remember Me enabled)';
        } else if (sessionStorage.getItem('refresh_token')) {
            storageLocation = 'sessionStorage (Remember Me disabled)';
        }
        
        html += `
            <div class="py-3 flex flex-col md:flex-row">
                <dt class="text-sm font-medium text-gray-500 md:w-1/3">Refresh Token Storage</dt>
                <dd class="mt-1 text-sm text-gray-900 md:mt-0 md:w-2/3">
                    ${storageLocation}
                </dd>
            </div>
        `;
        
        // Redirect After Login
        const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
        
        html += `
            <div class="py-3 flex flex-col md:flex-row">
                <dt class="text-sm font-medium text-gray-500 md:w-1/3">Redirect After Login</dt>
                <dd class="mt-1 text-sm text-gray-900 md:mt-0 md:w-2/3 break-all">
                    ${redirectAfterLogin || 'None'}
                </dd>
            </div>
        `;
        
        html += '</dl>';
        
        tokenInfoEl.innerHTML = html;
    }
    
    async function refreshToken() {
        updateTestResults('Attempting to refresh token...');
        
        try {
            const refreshToken = localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token');
            
            if (!refreshToken) {
                updateTestResults('No refresh token found. Please log in first.', 'error');
                return;
            }
            
            const response = await fetch('/auth_handler.php?action=refresh', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ refresh_token: refreshToken })
            });
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.access_token) {
                    // Store the new token
                    sessionStorage.setItem('auth_token', data.access_token);
                    
                    // Update expiry time
                    if (data.expires_in) {
                        const expiryTime = Date.now() + (data.expires_in * 1000);
                        sessionStorage.setItem('token_expiry', expiryTime.toString());
                    }
                    
                    // Store refresh token if provided
                    if (data.refresh_token) {
                        if (localStorage.getItem('refresh_token')) {
                            localStorage.setItem('refresh_token', data.refresh_token);
                        } else {
                            sessionStorage.setItem('refresh_token', data.refresh_token);
                        }
                    }
                    
                    updateTestResults('Token refreshed successfully!', 'success');
                    checkAuthStatus();
                } else {
                    updateTestResults('No access token returned from refresh request.', 'error');
                }
            } else {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                updateTestResults(`Failed to refresh token: ${errorData.message || response.statusText}`, 'error');
            }
        } catch (error) {
            console.error('Token refresh error:', error);
            updateTestResults(`Error refreshing token: ${error.message}`, 'error');
        }
    }
    
    function clearTokens() {
        sessionStorage.removeItem('auth_token');
        sessionStorage.removeItem('token_expiry');
        sessionStorage.removeItem('refresh_token');
        localStorage.removeItem('refresh_token');
        localStorage.removeItem('user_id');
        
        updateTestResults('All tokens have been cleared locally.', 'info');
        checkAuthStatus();
    }
    
    function updateTestResults(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const icon = type === 'success' ? 'check-circle' : 
                    type === 'error' ? 'exclamation-circle' : 
                    'info-circle';
        const color = type === 'success' ? 'text-green-600' : 
                      type === 'error' ? 'text-red-600' : 
                      'text-blue-600';
        
        // Create new result entry
        const resultEntry = document.createElement('div');
        resultEntry.className = 'border-b border-gray-200 py-3 last:border-0';
        resultEntry.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-${icon} ${color}"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm ${color} font-medium">${message}</p>
                    <p class="text-xs text-gray-500">${timestamp}</p>
                </div>
            </div>
        `;
        
        // Clear "No tests" message if present
        if (testResultsEl.textContent.includes('No tests have been run yet')) {
            testResultsEl.innerHTML = '';
        }
        
        // Prepend to top of results
        testResultsEl.insertBefore(resultEntry, testResultsEl.firstChild);
        
        // Limit to 10 entries
        const entries = testResultsEl.querySelectorAll('.border-b');
        if (entries.length > 10) {
            entries[entries.length - 1].remove();
        }
    }
    
    // Event Listeners
    checkAuthBtn.addEventListener('click', checkAuthStatus);
    refreshTokenBtn.addEventListener('click', refreshToken);
    clearTokensBtn.addEventListener('click', clearTokens);
    goToLoginBtn.addEventListener('click', () => window.location.href = 'index.php');
    goToDashboardBtn.addEventListener('click', () => window.location.href = 'dashboard.php');
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        checkAuthStatus();
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>