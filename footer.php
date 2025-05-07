</main>
    <!-- Footer -->
    <?php if (!in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'register.php', 'forgot_password.php', 'reset_password.php'])): ?>
    <!-- Only show footer on non-auth pages -->
    <footer class="bg-kdj-dark text-white py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="font-bold text-xl text-white">KDJ</span>
                        <span class="font-bold text-xl text-kdj-red">Lanka</span>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Digital Solutions for Sri Lanka</p>
                </div>
                
                <div class="flex space-x-6 mb-4 md:mb-0">
                    <a href="https://facebook.com/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://instagram.com/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://linkedin.com/company/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                
                <div class="text-sm text-gray-400">
                    <a href="/privacy-policy.php" class="hover:text-kdj-red transition duration-300 mr-4"><?php echo t('privacy_policy'); ?></a>
                    <a href="/terms-of-service.php" class="hover:text-kdj-red transition duration-300 mr-4"><?php echo t('terms_of_service'); ?></a>
                    <a href="/contact.php" class="hover:text-kdj-red transition duration-300"><?php echo t('contact_us'); ?></a>
                </div>
            </div>
            <div class="mt-6 border-t border-gray-700 pt-4 text-center text-gray-400 text-sm">
                &copy; <?php echo date('Y'); ?> KDJ Lanka. <?php echo t('all_rights_reserved'); ?>
            </div>
            
            <!-- Cookie consent banner (hidden by default) -->
            <div id="cookieConsent" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 flex flex-col md:flex-row justify-between items-center gap-4 z-50 transform translate-y-full transition-transform duration-300 ease-in-out">
                <div class="text-sm">
                    <p><?php echo t('cookie_notice'); ?> <a href="/privacy-policy.php" class="text-kdj-red hover:underline"><?php echo t('learn_more'); ?></a></p>
                </div>
                <div class="flex space-x-2">
                    <button id="cookieSettings" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded text-sm transition-colors">
                        <?php echo t('cookie_settings'); ?>
                    </button>
                    <button id="acceptAllCookies" class="px-4 py-2 bg-kdj-red hover:bg-red-800 rounded text-sm transition-colors">
                        <?php echo t('accept_all'); ?>
                    </button>
                </div>
            </div>
            
            <!-- Cookie settings modal (hidden by default) -->
            <div id="cookieSettingsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold"><?php echo t('cookie_preferences'); ?></h3>
                            <button id="closeCookieSettings" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center justify-between">
                                    <label for="necessaryCookies" class="font-medium">
                                        <?php echo t('necessary_cookies'); ?>
                                    </label>
                                    <div class="relative inline-block w-12 align-middle">
                                        <input type="checkbox" id="necessaryCookies" class="toggle-checkbox" checked disabled>
                                        <label for="necessaryCookies" class="toggle-label"></label>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo t('necessary_cookies_desc'); ?></p>
                            </div>
                            
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center justify-between">
                                    <label for="analyticsCookies" class="font-medium">
                                        <?php echo t('analytics_cookies'); ?>
                                    </label>
                                    <div class="relative inline-block w-12 align-middle">
                                        <input type="checkbox" id="analyticsCookies" class="toggle-checkbox">
                                        <label for="analyticsCookies" class="toggle-label"></label>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo t('analytics_cookies_desc'); ?></p>
                            </div>
                            
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center justify-between">
                                    <label for="functionalCookies" class="font-medium">
                                        <?php echo t('functional_cookies'); ?>
                                    </label>
                                    <div class="relative inline-block w-12 align-middle">
                                        <input type="checkbox" id="functionalCookies" class="toggle-checkbox">
                                        <label for="functionalCookies" class="toggle-label"></label>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo t('functional_cookies_desc'); ?></p>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between">
                                    <label for="advertisingCookies" class="font-medium">
                                        <?php echo t('advertising_cookies'); ?>
                                    </label>
                                    <div class="relative inline-block w-12 align-middle">
                                        <input type="checkbox" id="advertisingCookies" class="toggle-checkbox">
                                        <label for="advertisingCookies" class="toggle-label"></label>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo t('advertising_cookies_desc'); ?></p>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button id="saveCookieSettings" class="px-4 py-2 bg-kdj-red text-white rounded hover:bg-red-800 transition-colors">
                                <?php echo t('save_preferences'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Feedback widget -->
    <div id="feedbackWidget" class="fixed bottom-5 right-5 z-40 <?php echo in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'register.php', 'forgot_password.php', 'reset_password.php']) ? 'hidden' : ''; ?>">
        <button id="feedbackToggle" class="bg-kdj-red text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-red-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red" aria-label="<?php echo t('provide_feedback'); ?>">
            <i class="fas fa-comment-alt"></i>
        </button>
        
        <!-- Feedback form (hidden by default) -->
        <div id="feedbackForm" class="hidden absolute bottom-16 right-0 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all duration-300 animate-fade-in">
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white"><?php echo t('provide_feedback'); ?></h3>
                    <button id="closeFeedback" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="userFeedbackForm">
                    <div class="mb-4">
                        <label for="feedbackType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo t('feedback_type'); ?></label>
                        <select id="feedbackType" name="feedbackType" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red">
                            <option value="suggestion"><?php echo t('suggestion'); ?></option>
                            <option value="bug"><?php echo t('bug_report'); ?></option>
                            <option value="compliment"><?php echo t('compliment'); ?></option>
                            <option value="other"><?php echo t('other'); ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="feedbackMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo t('your_feedback'); ?></label>
                        <textarea id="feedbackMessage" name="feedbackMessage" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" placeholder="<?php echo t('feedback_placeholder'); ?>" required></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" id="submitFeedback" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                            <?php echo t('submit'); ?>
                        </button>
                    </div>
                </form>
                
                <!-- Success message (hidden by default) -->
                <div id="feedbackSuccess" class="hidden">
                    <div class="text-center p-4">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1"><?php echo t('thank_you'); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo t('feedback_received'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Utility Scripts for all pages -->
    <script>
        // Configuration
        const config = {
            apiBaseUrl: '<?php echo API_BASE_URL; ?>',
            csrfToken: '<?php echo $_SESSION['csrf_token']; ?>',
            lang: '<?php echo $user_lang; ?>',
            theme: '<?php echo $preferred_theme; ?>',
            debug: <?php echo (defined('DEBUG_MODE') && DEBUG_MODE) ? 'true' : 'false'; ?>
        };
        
        // Toast notification system
        const toastContainer = document.getElementById('toastContainer');
        
        function showToast(message, type = 'success', duration = 5000) {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `mb-3 p-4 rounded-lg shadow-lg text-white ${getToastColorClass(type)} transform toast-slide-in flex items-center justify-between`;
            
            // Get appropriate icon class based on type
            const iconClass = getToastIconClass(type);
            
            // Set toast content
            toast.innerHTML = `
                <div class="flex items-center pr-2">
                    <i class="fas ${iconClass} mr-3"></i>
                    <p class="max-w-[calc(100%-50px)]">${sanitizeHTML(message)}</p>
                </div>
                <button class="text-white hover:text-gray-200 toast-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add toast to container
            toastContainer.appendChild(toast);
            
            // Set up close button
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                removeToast(toast);
            });
            
            // Auto-remove toast after duration
            const timeoutId = setTimeout(() => {
                removeToast(toast);
            }, duration);
            
            // Store timeout ID on the toast element for cleanup
            toast._timeoutId = timeoutId;
            
            return toast;
        }
        
        function removeToast(toast) {
            // Cancel the timeout if it exists
            if (toast._timeoutId) {
                clearTimeout(toast._timeoutId);
            }
            
            // Add slide-out animation
            toast.classList.remove('toast-slide-in');
            toast.classList.add('toast-slide-out');
            
            // Remove after animation completes
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
        
        function getToastColorClass(type) {
            switch (type) {
                case 'success': return 'bg-green-500';
                case 'error': return 'bg-red-500';
                case 'warning': return 'bg-yellow-500';
                case 'info': 
                default: return 'bg-blue-500';
            }
        }
        
        function getToastIconClass(type) {
            switch (type) {
                case 'success': return 'fa-check-circle';
                case 'error': return 'fa-exclamation-circle';
                case 'warning': return 'fa-exclamation-triangle';
                case 'info':
                default: return 'fa-info-circle';
            }
        }
        
        // Loading indicator functions
        function showLoading(message = null) {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const loadingMessage = document.getElementById('loadingMessage');
            
            if (message && loadingMessage) {
                loadingMessage.textContent = message;
            }
            
            if (loadingIndicator) {
                loadingIndicator.style.display = 'flex';
            }
        }
        
        function hideLoading() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
        }
        
        // Utility functions
        function sanitizeHTML(input) {
            if (!input) return '';
            
            const div = document.createElement('div');
            div.textContent = input;
            return div.innerHTML;
        }
        
        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        
        function formatDate(dateString, includeTime = true) {
            if (!dateString) return 'Never';
            
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'Invalid date';
            
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            
            if (includeTime) {
                options.hour = '2-digit';
                options.minute = '2-digit';
            }
            
            return date.toLocaleString('<?php echo $user_lang === 'en' ? 'en-US' : ($user_lang === 'si' ? 'si-LK' : 'ta-LK'); ?>', options);
        }
        
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }
        
        function setCookie(name, value, days) {
            let expires = '';
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = `; expires=${date.toUTCString()}`;
            }
            document.cookie = `${name}=${value}${expires}; path=/; SameSite=Lax; Secure`;
        }
        
        // API request wrapper with proper error handling
        async function apiRequest(endpoint, options = {}, includeCredentials = true) {
            try {
                const defaultHeaders = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': config.csrfToken
                };
                
                // Add auth token to headers if available
                const authToken = sessionStorage.getItem('auth_token');
                if (authToken) {
                    defaultHeaders['Authorization'] = `Bearer ${authToken}`;
                }
                
                const requestConfig = {
                    ...options,
                    headers: {
                        ...defaultHeaders,
                        ...(options.headers || {}),
                    }
                };
                
                if (includeCredentials) {
                    requestConfig.credentials = 'include';
                }
                
                // Add request timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                requestConfig.signal = controller.signal;
                
                const response = await fetch(`${config.apiBaseUrl}${endpoint}`, requestConfig);
                clearTimeout(timeoutId); // Clear timeout on successful response
                
                // Handle unauthorized error by trying to refresh token once
                if (response.status === 401 && endpoint !== '/auth/refresh-token' && endpoint !== '/auth/login') {
                    const refreshed = await refreshAuthToken();
                    
                    if (refreshed) {
                        // Retry the original request with new token
                        const newToken = sessionStorage.getItem('auth_token');
                        if (newToken) {
                            requestConfig.headers['Authorization'] = `Bearer ${newToken}`;
                        }
                        
                        // Create new abort controller for retry
                        const retryController = new AbortController();
                        const retryTimeoutId = setTimeout(() => retryController.abort(), 30000);
                        requestConfig.signal = retryController.signal;
                        
                        const retryResponse = await fetch(`${config.apiBaseUrl}${endpoint}`, requestConfig);
                        clearTimeout(retryTimeoutId);
                        
                        if (retryResponse.status === 401) {
                            handleSessionExpired();
                            return null;
                        }
                        
                        return retryResponse;
                    } else {
                        handleSessionExpired();
                        return null;
                    }
                }
                
                return response;
            } catch (error) {
                if (error.name === 'AbortError') {
                    showToast('Request timeout. Please check your internet connection and try again.', 'error');
                    throw new Error('Request timeout');
                }
                
                console.error(`API call to ${endpoint} failed:`, error);
                throw error;
            }
        }
        
        function handleSessionExpired() {
            // Clear session storage
            sessionStorage.removeItem('auth_token');
            sessionStorage.removeItem('token_expiry');
            sessionStorage.removeItem('refresh_token');
            
            // Save current URL to redirect back after login
            sessionStorage.setItem('redirectAfterLogin', window.location.href);
            
            // Show toast and redirect
            showToast('Your session has expired. Please log in again.', 'warning');
            setTimeout(() => {
                window.location.href = '/index.php';
            }, 2000);
        }
        
        // Check if token expiration time is coming up
        function isTokenExpiringSoon() {
            const tokenExpiry = sessionStorage.getItem('token_expiry');
            if (!tokenExpiry) return true;
            
            // Check if token expires in the next 5 minutes (300000 ms)
            return parseInt(tokenExpiry) - 300000 < Date.now();
        }
        
        // Refresh auth token
        async function refreshAuthToken() {
            const refreshToken = sessionStorage.getItem('refresh_token');
            if (!refreshToken) return false;
            
            try {
                const response = await fetch(`${config.apiBaseUrl}/auth/refresh-token`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-Token': config.csrfToken
                    },
                    body: JSON.stringify({ refresh_token: refreshToken }),
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    console.error('Failed to refresh token:', response.status);
                    return false;
                }
                
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
                        sessionStorage.setItem('refresh_token', data.refresh_token);
                    }
                    
                    return true;
                }
                
                return false;
            } catch (error) {
                console.error('Token refresh error:', error);
                return false;
            }
        }
        
        // Logout handling
        async function handleLogout() {
            showLoading('<?php echo t('logging_out'); ?>');
            
            try {
                await apiRequest('/auth/logout', {
                    method: 'POST'
                });
                
                // Clear storage regardless of response
                sessionStorage.clear();
                localStorage.removeItem('user_id');
                
                // Redirect to login page
                window.location.href = '/index.php';
            } catch (error) {
                console.error('Logout error:', error);
                
                // Clear storage anyway
                sessionStorage.clear();
                localStorage.removeItem('user_id');
                
                showToast('Network error during logout. You have been logged out locally.', 'warning');
                
                // Redirect to login page after a short delay
                setTimeout(() => {
                    window.location.href = '/index.php';
                }, 1500);
                
                hideLoading();
            }
        }
        
        // Attach logout event handlers
        function setupLogoutHandlers() {
            const logoutBtn = document.getElementById('logoutBtn');
            const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', handleLogout);
            }
            
            if (mobileLogoutBtn) {
                mobileLogoutBtn.addEventListener('click', handleLogout);
            }
        }
        
        // Mobile menu toggle
        function setupMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    const expanded = mobileMenu.classList.toggle('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', !expanded);
                });
            }
            
            // Mobile language menu
            const mobileLangBtn = document.getElementById('mobileLangBtn');
            const mobileLangMenu = document.getElementById('mobileLangMenu');
            
            if (mobileLangBtn && mobileLangMenu) {
                mobileLangBtn.addEventListener('click', function() {
                    const expanded = mobileLangMenu.classList.toggle('hidden');
                    mobileLangBtn.setAttribute('aria-expanded', !expanded);
                });
            }
        }
        
        // Theme toggle functionality
        function setupThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const mobileThemeToggle = document.getElementById('mobileThemeToggle');
            
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    setCookie('theme', 'light', 365);
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    setCookie('theme', 'dark', 365);
                }
            }
            
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }
            
            if (mobileThemeToggle) {
                mobileThemeToggle.addEventListener('click', toggleTheme);
            }
        }
        
        // Cookie consent functionality
        function setupCookieConsent() {
            const cookieConsent = document.getElementById('cookieConsent');
            const acceptAllCookies = document.getElementById('acceptAllCookies');
            const cookieSettings = document.getElementById('cookieSettings');
            const cookieSettingsModal = document.getElementById('cookieSettingsModal');
            const closeCookieSettings = document.getElementById('closeCookieSettings');
            const saveCookieSettings = document.getElementById('saveCookieSettings');
            
            // Check if user has already set cookie preferences
            const hasConsent = getCookie('cookie_consent');
            
            if (!hasConsent && cookieConsent) {
                // Show the cookie banner after a short delay
                setTimeout(() => {
                    cookieConsent.classList.remove('translate-y-full');
                }, 1000);
            }
            
            if (acceptAllCookies) {
                acceptAllCookies.addEventListener('click', () => {
                    setCookie('cookie_consent', 'all', 365);
                    setCookie('cookie_analytics', 'true', 365);
                    setCookie('cookie_functional', 'true', 365);
                    setCookie('cookie_advertising', 'true', 365);
                    
                    cookieConsent.classList.add('translate-y-full');
                });
            }
            
            if (cookieSettings) {
                cookieSettings.addEventListener('click', () => {
                    if (cookieSettingsModal) {
                        cookieSettingsModal.classList.remove('hidden');
                    }
                });
            }
            
            if (closeCookieSettings) {
                closeCookieSettings.addEventListener('click', () => {
                    cookieSettingsModal.classList.add('hidden');
                });
            }
            
            if (saveCookieSettings) {
                saveCookieSettings.addEventListener('click', () => {
                    const analyticsCookies = document.getElementById('analyticsCookies').checked;
                    const functionalCookies = document.getElementById('functionalCookies').checked;
                    const advertisingCookies = document.getElementById('advertisingCookies').checked;
                    
                    setCookie('cookie_consent', 'custom', 365);
                    setCookie('cookie_analytics', analyticsCookies ? 'true' : 'false', 365);
                    setCookie('cookie_functional', functionalCookies ? 'true' : 'false', 365);
                    setCookie('cookie_advertising', advertisingCookies ? 'true' : 'false', 365);
                    
                    cookieSettingsModal.classList.add('hidden');
                    cookieConsent.classList.add('translate-y-full');
                });
            }
            
            // Load existing preferences if available
            if (hasConsent === 'custom') {
                const analyticsCookies = document.getElementById('analyticsCookies');
                const functionalCookies = document.getElementById('functionalCookies');
                const advertisingCookies = document.getElementById('advertisingCookies');
                
                if (analyticsCookies) analyticsCookies.checked = getCookie('cookie_analytics') === 'true';
                if (functionalCookies) functionalCookies.checked = getCookie('cookie_functional') === 'true';
                if (advertisingCookies) advertisingCookies.checked = getCookie('cookie_advertising') === 'true';
            }
        }
        
        // Feedback widget functionality
        function setupFeedbackWidget() {
            const feedbackToggle = document.getElementById('feedbackToggle');
            const feedbackForm = document.getElementById('feedbackForm');
            const closeFeedback = document.getElementById('closeFeedback');
            const userFeedbackForm = document.getElementById('userFeedbackForm');
            const feedbackSuccess = document.getElementById('feedbackSuccess');
            
            if (feedbackToggle && feedbackForm) {
                feedbackToggle.addEventListener('click', () => {
                    feedbackForm.classList.toggle('hidden');
                });
            }
            
            if (closeFeedback) {
                closeFeedback.addEventListener('click', () => {
                    feedbackForm.classList.add('hidden');
                });
            }
            
            if (userFeedbackForm) {
                userFeedbackForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    
                    const feedbackType = document.getElementById('feedbackType').value;
                    const feedbackMessage = document.getElementById('feedbackMessage').value;
                    
                    if (!feedbackMessage.trim()) {
                        showToast('Please enter your feedback', 'error');
                        return;
                    }
                    
                    const submitButton = document.getElementById('submitFeedback');
                    const originalText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                    
                    try {
                        const response = await apiRequest('/feedback/submit', {
                            method: 'POST',
                            body: JSON.stringify({
                                type: feedbackType,
                                message: feedbackMessage,
                                page: window.location.pathname,
                                user_agent: navigator.userAgent
                            })
                        });
                        
                        if (response && response.ok) {
                            userFeedbackForm.classList.add('hidden');
                            feedbackSuccess.classList.remove('hidden');
                            
                            // Reset form
                            userFeedbackForm.reset();
                            
                            // Hide success message and form after 5 seconds
                            setTimeout(() => {
                                feedbackForm.classList.add('hidden');
                                userFeedbackForm.classList.remove('hidden');
                                feedbackSuccess.classList.add('hidden');
                            }, 5000);
                        } else {
                            showToast('Failed to submit feedback. Please try again.', 'error');
                        }
                    } catch (error) {
                        console.error('Feedback submission error:', error);
                        showToast('Failed to submit feedback. Please try again.', 'error');
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                });
            }
        }
        
        // Run page initialization
        document.addEventListener('DOMContentLoaded', function() {
            setupLogoutHandlers();
            setupMobileMenu();
            setupThemeToggle();
            setupCookieConsent();
            setupFeedbackWidget();
            
            // Set up automatic token refresh check
            setInterval(async () => {
                if (isTokenExpiringSoon()) {
                    await refreshAuthToken();
                }
            }, 300000); // Check every 5 minutes
            
            // Check if authenticated session is still valid
            <?php if (!in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'register.php', 'forgot_password.php', 'reset_password.php'])): ?>
            if (sessionStorage.getItem('auth_token')) {
                apiRequest('/users/me', { method: 'GET' })
                    .catch(error => {
                        console.error('Error checking authentication:', error);
                    });
            } else {
                // Not logged in, but on a protected page - redirect to login
                handleSessionExpired();
            }
            <?php endif; ?>
            
            // Initialize toggle checkboxes styling
            document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
                if (checkbox.checked) {
                    checkbox.nextElementSibling.classList.add('bg-kdj-red');
                } else {
                    checkbox.nextElementSibling.classList.remove('bg-kdj-red');
                }
                
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        this.nextElementSibling.classList.add('bg-kdj-red');
                    } else {
                        this.nextElementSibling.classList.remove('bg-kdj-red');
                    }
                });
            });
        });
    </script>
    
    <!-- Check for page-specific scripts -->
    <?php if (isset($additional_scripts)) echo $additional_scripts; ?>
    
    <?php if (config('analytics_enabled') && getCookie('cookie_analytics') !== 'false'): ?>
    <!-- Analytics script - Only included if allowed by cookie consent -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo config('analytics_id'); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo config('analytics_id'); ?>', { 'anonymize_ip': true });
    </script>
    <?php endif; ?>
    
    <style>
        /* Toggle checkbox styles */
        .toggle-checkbox {
            @apply right-0 border-4 absolute block h-6 w-6 rounded-full bg-white appearance-none cursor-pointer;
        }
        .toggle-checkbox:checked {
            @apply right-0 border-kdj-red;
        }
        .toggle-label {
            @apply block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer;
        }
        .toggle-checkbox:checked + .toggle-label {
            @apply bg-kdj-red;
        }
    </style>
</body>
</html>