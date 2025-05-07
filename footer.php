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
                    <a href="https://facebook.com/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://instagram.com/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://linkedin.com/company/kdjlanka" class="text-gray-400 hover:text-kdj-red transition duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                
                <div class="text-sm text-gray-400">
                    <a href="/privacy-policy.php" class="hover:text-kdj-red transition duration-300 mr-4">Privacy Policy</a>
                    <a href="/terms-of-service.php" class="hover:text-kdj-red transition duration-300">Terms of Service</a>
                </div>
            </div>
            <div class="mt-6 border-t border-gray-700 pt-4 text-center text-gray-400 text-sm">
                &copy; <?php echo date('Y'); ?> KDJ Lanka. All rights reserved.
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <!-- Toast container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50"></div>

    <!-- Toast notification script -->
    <script>
        // Toast notification function
        function showToast(message, type = 'success', duration = 5000) {
            const toastContainer = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `mb-3 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'} transform transition-all duration-300 translate-x-full`;
            
            const iconClass = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${iconClass} mr-3"></i>
                    <p>${message}</p>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Show toast with slide-in animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
            // Auto-remove toast after duration
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, duration);
        }
        
        // Loading indicator functions
        function showLoading() {
            document.getElementById('loadingIndicator').style.display = 'flex';
        }
        
        function hideLoading() {
            document.getElementById('loadingIndicator').style.display = 'none';
        }
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Logout functionality
        const logoutBtn = document.getElementById('logoutBtn');
        const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
        
        function handleLogout() {
            showLoading();
            
            const authToken = sessionStorage.getItem('auth_token');
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
            
            // Add auth token if available
            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`;
            }
            
            fetch('https://auth.kdj.lk/api/v1/auth/logout', {
                method: 'POST',
                credentials: 'include',
                headers: headers
            })
            .then(response => {
                // Clear session storage regardless of response
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token_expiry');
                sessionStorage.removeItem('refresh_token');
                localStorage.removeItem('refresh_token');
                localStorage.removeItem('user_id');
                
                // Redirect to login page
                window.location.href = '/index.php';
            })
            .catch(error => {
                // Still clear storage and redirect on error
                console.error('Logout error:', error);
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token_expiry');
                sessionStorage.removeItem('refresh_token');
                localStorage.removeItem('refresh_token');
                localStorage.removeItem('user_id');
                
                window.location.href = '/index.php';
            });
        }
        
        if (logoutBtn) {
            logoutBtn.addEventListener('click', handleLogout);
        }
        
        if (mobileLogoutBtn) {
            mobileLogoutBtn.addEventListener('click', handleLogout);
        }

        // Security helper for sanitizing inputs
        function sanitizeHTML(input) {
            if (!input) return '';
            
            const div = document.createElement('div');
            div.textContent = input;
            return div.innerHTML;
        }
        
        // Check if user is logged in on protected pages
        function checkUserAuth() {
            const protectedPages = [
                'dashboard.php', 
                'profile.php', 
                'settings.php',
                'security.php'
            ];
            
            const currentPage = window.location.pathname.split('/').pop();
            
            if (protectedPages.includes(currentPage)) {
                // Check for auth token
                const authToken = sessionStorage.getItem('auth_token');
                
                if (!authToken) {
                    // No token, redirect to login page
                    // Store current URL for redirect after login
                    sessionStorage.setItem('redirectAfterLogin', window.location.href);
                    window.location.href = '/index.php';
                    return;
                }
                
                // Verify token by making an API call
                const headers = {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                };
                
                fetch('https://auth.kdj.lk/api/v1/users/me', {
                    method: 'GET',
                    credentials: 'include',
                    headers: headers
                })
                .then(response => {
                    if (!response.ok) {
                        // Try to refresh the token
                        refreshAuthToken().then(refreshed => {
                            if (!refreshed) {
                                // Clear session storage
                                sessionStorage.removeItem('auth_token');
                                sessionStorage.removeItem('token_expiry');
                                
                                // Save current URL for redirect after login
                                sessionStorage.setItem('redirectAfterLogin', window.location.href);
                                
                                // Redirect to login page
                                window.location.href = '/index.php';
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Auth check error:', error);
                    // Redirect to login page
                    window.location.href = '/index.php';
                });
            }
        }

        // Refresh auth token function
        async function refreshAuthToken() {
            const refreshToken = localStorage.getItem('refresh_token') || sessionStorage.getItem('refresh_token');
            if (!refreshToken) return false;
            
            try {
                const response = await fetch('https://auth.kdj.lk/api/v1/auth/refresh-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
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
                        if (localStorage.getItem('refresh_token')) {
                            localStorage.setItem('refresh_token', data.refresh_token);
                        } else {
                            sessionStorage.setItem('refresh_token', data.refresh_token);
                        }
                    }
                    
                    return true;
                }
                
                return false;
            } catch (error) {
                console.error('Token refresh error:', error);
                return false;
            }
        }

        // Run auth check on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkUserAuth();
        });
    </script>
    
    <?php if (isset($additional_scripts)) echo $additional_scripts; ?>
</body>
</html>