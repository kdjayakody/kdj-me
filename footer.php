<?php
// Check if this is an authentication page
$is_auth_page = in_array(basename($_SERVER['PHP_SELF']), 
    ['index.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'verify-email.php', 'mfa.php']);
?>

<?php if (!$is_auth_page): ?>
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

<!-- Common JavaScript functionality -->
<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>

<?php if (isset($additional_scripts)) echo $additional_scripts; ?>
</body>
</html>