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
            
            fetch('https://auth.kdj.lk/api/v1/auth/logout', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '/index.php';
                } else {
                    hideLoading();
                    showToast('Logout failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showToast('Network error. Please try again.', 'error');
            });
        }
        
        if (logoutBtn) {
            logoutBtn.addEventListener('click', handleLogout);
        }
        
        if (mobileLogoutBtn) {
            mobileLogoutBtn.addEventListener('click', handleLogout);
        }

        // CSRF protection - Add token to all requests
        function generateCSRFToken() {
            return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        }
        
        // Store CSRF token in session storage if not already set
        if (!sessionStorage.getItem('csrf_token')) {
            sessionStorage.setItem('csrf_token', generateCSRFToken());
        }
        
        // Security helper for sanitizing inputs
        function sanitizeInput(input) {
            const div = document.createElement('div');
            div.textContent = input;
            return div.innerHTML;
        }
        
        // Check if user is logged in by trying to fetch profile
        function checkUserAuth() {
    if (window.location.pathname.includes('dashboard') || 
        window.location.pathname.includes('profile') || 
        window.location.pathname.includes('settings') ||
        window.location.pathname.includes('security')) {
        
        // Get auth token from sessionStorage
        const authToken = sessionStorage.getItem('auth_token');
        
        // Set up request headers with Authorization token if available
        const headers = {
            'Accept': 'application/json'
        };
        
        if (authToken) {
            headers['Authorization'] = `Bearer ${authToken}`;
        }
        
        fetch('https://auth.kdj.lk/api/v1/users/me', {
            method: 'GET',
            credentials: 'include',
            headers: headers
        })
        .then(response => {
            if (!response.ok) {
                console.error('Authentication failed:', response.status);
                // Redirect to login if not authenticated
                window.location.href = '/index.php';
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                // Update user name in the header
                const userDisplayName = document.getElementById('userDisplayName');
                if (userDisplayName) {
                    userDisplayName.textContent = data.display_name || data.email;
                }
            }
        })
        .catch(error => {
            console.error('Auth check error:', error);
            // Redirect to login on error
            window.location.href = '/index.php';
        });
    }
}

        // Run auth check on protected pages
        document.addEventListener('DOMContentLoaded', function() {
            checkUserAuth();
        });
    </script>
    
    <?php if (isset($additional_scripts)) echo $additional_scripts; ?>
</body>
</html>