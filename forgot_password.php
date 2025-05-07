<?php
// Set page specific variables
$title = "Forgot Password";
$description = "Reset your KDJ Lanka account password";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
    }
    .forgot-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="forgot-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                මුරපදය අමතකද?
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                ඔබගේ ඊමේල් ලිපිනය ඇතුලත් කරන්න. මුරපදය නැවත සකස් කිරීමේ සබැඳියක් අපි එවන්නෙමු.
            </p>
        </div>
        
        <div id="successMessage" class="bg-green-50 p-4 rounded-md border border-green-200 mt-4 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">ඉල්ලීම සාර්ථකයි!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>ඔබගේ ඊමේල් ලිපිනය ලියාපදිංචි වී ඇත්නම්, ඔබට මුරපදය නැවත සකස් කිරීමේ සබැඳියක් ලැබෙනු ඇත.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <form id="forgotPasswordForm" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">ඊමේල් ලිපිනය</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red focus:z-10 sm:text-sm" 
                            placeholder="ඔබගේ ඊමේල් ලිපිනය ඇතුලත් කරන්න">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-paper-plane"></i>
                    </span>
                    Reset Link එක එවන්න
                </button>
            </div>
            
            <div class="flex items-center justify-center space-x-4">
                <a href="index.php" class="text-sm font-medium text-kdj-red hover:text-red-800">
                    <i class="fas fa-arrow-left mr-1"></i> Login පිටුවට යන්න
                </a>
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
    
    // Form submission
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const submitButton = document.getElementById('submitButton');
    const successMessage = document.getElementById('successMessage');
    
    forgotPasswordForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const email = document.getElementById('email').value;
        
        // Client-side validation
        if (!isValidEmail(email)) {
            showToast('කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුලත් කරන්න', 'error');
            return;
        }
        
        // Disable button and show loading
        submitButton.disabled = true;
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> සකසමින්...';
        showLoading();
        
        try {
            const response = await fetch(`\${apiBaseUrl}/auth/reset-password/request`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            });
            
            const responseData = await response.json();
            
            // For security reasons, show success message regardless of whether email exists
            hideLoading();
            forgotPasswordForm.classList.add('hidden');
            successMessage.classList.remove('hidden');
            
            // Automatically redirect to login page after 5 seconds
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 5000);
            
        } catch (error) {
            // For security reasons, still show success message on error
            hideLoading();
            forgotPasswordForm.classList.add('hidden');
            successMessage.classList.remove('hidden');
            
            // Log error for troubleshooting
            console.error('Error during forgot password request:', error);
            
            // Automatically redirect to login page after 5 seconds
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 5000);
        } finally {
            // Re-enable button just in case
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
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