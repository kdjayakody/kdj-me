```php
<?php
// Set page specific variables
$title = "Verify Email";
$description = "Verify your email address for KDJ Lanka account";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .auth-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
    }
    .verify-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    .loader {
        border-top-color: #cb2127;
        width: 3rem;
        height: 3rem;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="auth-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="verify-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <div id="loadingIcon" class="mx-auto flex items-center justify-center">
                <div class="loader border-4 border-gray-200 rounded-full"></div>
            </div>
            <div id="successIcon" class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4 hidden">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <div id="errorIcon" class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 hidden">
                <i class="fas fa-times text-3xl text-red-600"></i>
            </div>
            
            <h2 class="mt-6 text-3xl font-extrabold text-kdj-dark">
                ඊමේල් තහවුරු කිරීම
            </h2>
            <p class="mt-2 text-sm text-gray-600" id="statusMessage">
                ඔබගේ ඊමේල් ලිපිනය තහවුරු කරමින් පවතී...
            </p>
        </div>
        
        <!-- Success State -->
        <div id="successState" class="mt-8 hidden">
            <div class="bg-green-50 p-4 rounded-md border border-green-200 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">ඊමේල් තහවුරු කිරීම සාර්ථකයි!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>ඔබගේ ඊමේල් ලිපිනය සාර්ථකව තහවුරු කර ඇත. ඔබට දැන් ඔබගේ KDJ Lanka ගිණුමට පිවිසිය හැක.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center">
                <a href="index.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    පිවිසුම් පිටුවට
                </a>
            </div>
        </div>
        
        <!-- Error State -->
        <div id="errorState" class="mt-8 hidden">
            <div class="bg-red-50 p-4 rounded-md border border-red-200 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">තහවුරු කිරීමේ දෝෂයක්</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p id="errorMessage">තහවුරු කිරීමේ සබැඳිය අවලංගු හෝ කල් ඉකුත් වී ඇත. නව ඊමේල් තහවුරු කිරීමේ සබැඳියක් ඉල්ලන්න.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center space-x-4">
                <a href="index.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <i class="fas fa-arrow-left mr-2"></i>
                    ආපසු යන්න
                </a>
                <button id="resendBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                    <i class="fas fa-envelope mr-2"></i>
                    නැවත යවන්න
                </button>
            </div>
        </div>
        
        <!-- Resend Form (hidden by default) -->
        <div id="resendFormContainer" class="mt-8 hidden">
            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-envelope text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">නව තහවුරු කිරීමේ සබැඳියක් ඉල්ලන්න</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>කරුණාකර ඔබගේ ඊමේල් ලිපිනය ඇතුලත් කරන්න. අපි ඔබට නව තහවුරු කිරීමේ සබැඳියක් යවන්නෙමු.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="resendForm" class="mt-6">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">ඊමේල් ලිපිනය</label>
                    <input type="email" id="resendEmail" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-kdj-red focus:border-kdj-red" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" id="resendSubmitBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                        <i class="fas fa-paper-plane mr-2"></i>
                        යවන්න
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    
    // Elements
    const loadingIcon = document.getElementById('loadingIcon');
    const successIcon = document.getElementById('successIcon');
    const errorIcon = document.getElementById('errorIcon');
    const statusMessage = document.getElementById('statusMessage');
    const successState = document.getElementById('successState');
    const errorState = document.getElementById('errorState');
    const errorMessage = document.getElementById('errorMessage');
    const resendBtn = document.getElementById('resendBtn');
    const resendFormContainer = document.getElementById('resendFormContainer');
    const resendForm = document.getElementById('resendForm');
    const resendEmail = document.getElementById('resendEmail');
    const resendSubmitBtn = document.getElementById('resendSubmitBtn');
    
    // Get verification token from URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    
    // Verify email when page loads
    document.addEventListener('DOMContentLoaded', function() {
        verifyEmail();
    });
    
    // Handle errors with missing token
    if (!token) {
        setTimeout(() => {
            loadingIcon.classList.add('hidden');
            errorIcon.classList.remove('hidden');
            statusMessage.textContent = 'තහවුරු කිරීමේ සබැඳිය වලංගු නොවේ.';
            errorMessage.textContent = 'තහවුරු කිරීමේ ටෝකනයක් සපයා නැත. කරුණාකර ඊමේල් එකේ ඇති සබැඳිය හරහා පිවිසෙන්න.';
            errorState.classList.remove('hidden');
        }, 1000);
    }
    
    // Verify email function
    async function verifyEmail() {
        if (!token) return;
        
        try {
            const response = await fetch(`\${apiBaseUrl}/auth/verify-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ token: token })
            });
            
            const responseData = await response.json();
            
            // Hide loading icon
            loadingIcon.classList.add('hidden');
            
            if (response.ok) {
                // Show success state
                successIcon.classList.remove('hidden');
                statusMessage.textContent = 'ඊමේල් තහවුරු කිරීම සාර්ථකයි!';
                successState.classList.remove('hidden');
            } else {
                // Show error state
                errorIcon.classList.remove('hidden');
                statusMessage.textContent = 'ඊමේල් තහවුරු කිරීම අසාර්ථකයි.';
                
                // Set appropriate error message
                if (responseData.detail) {
                    if (typeof responseData.detail === 'string') {
                        if (responseData.detail.includes('expired')) {
                            errorMessage.textContent = 'තහවුරු කිරීමේ සබැඳිය කල් ඉකුත් වී ඇත. කරුණාකර නව සබැඳියක් ඉල්ලන්න.';
                        } else if (responseData.detail.includes('invalid')) {
                            errorMessage.textContent = 'තහවුරු කිරීමේ සබැඳිය වලංගු නොවේ. කරුණාකර නව සබැඳියක් ඉල්ලන්න.';
                        } else if (responseData.detail.includes('already verified')) {
                            errorMessage.textContent = 'ඔබගේ ඊමේල් ලිපිනය දැනටමත් තහවුරු කර ඇත.';
                            // Redirect to login after 3 seconds
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 3000);
                        } else {
                            errorMessage.textContent = responseData.detail;
                        }
                    } else {
                        errorMessage.textContent = 'ඊමේල් තහවුරු කිරීමේදී දෝෂයක් ඇති විය.';
                    }
                } else {
                    errorMessage.textContent = 'ඊමේල් තහවුරු කිරීමේදී දෝෂයක් ඇති විය.';
                }
                
                errorState.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Email verification error:', error);
            
            // Hide loading icon and show error state
            loadingIcon.classList.add('hidden');
            errorIcon.classList.remove('hidden');
            statusMessage.textContent = 'ඊමේල් තහවුරු කිරීම අසාර්ථකයි.';
            errorMessage.textContent = 'ඊමේල් තහවුරු කිරීමේදී දෝෂයක් ඇති විය. කරුණාකර පසුව නැවත උත්සාහ කරන්න.';
            errorState.classList.remove('hidden');
        }
    }
    
    // Show resend form
    resendBtn.addEventListener('click', function() {
        errorState.classList.add('hidden');
        resendFormContainer.classList.remove('hidden');
        resendEmail.focus();
    });
    
    // Handle resend form submission
    resendForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const email = resendEmail.value;
        
        if (!email) {
            showToast('කරුණාකර ඊමේල් ලිපිනයක් ඇතුළත් කරන්න.', 'error');
            return;
        }
        
        // Disable button and show loading
        resendSubmitBtn.disabled = true;
        const originalButtonText = resendSubmitBtn.innerHTML;
        resendSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> යවමින්...';
        
        try {
            const response = await fetch(`\${apiBaseUrl}/auth/resend-verification-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            });
            
            const responseData = await response.json();
            
            // Always show success message for security (don't reveal if email exists)
            showToast('තහවුරු කිරීමේ ඊමේල් පණිවිඩය සාර්ථකව යවන ලදි. කරුණාකර ඔබගේ ඊමේල් inbox එක පරීක්ෂා කරන්න.', 'success');
            
            // Hide resend form and show success state with modified message
            resendFormContainer.classList.add('hidden');
            loadingIcon.classList.add('hidden');
            successIcon.classList.remove('hidden');
            successState.classList.remove('hidden');
            statusMessage.textContent = 'නව තහවුරු කිරීමේ සබැඳියක් යවා ඇත';
            
            // Update success state message
            const successStateMessage = successState.querySelector('.text-green-700 p');
            if (successStateMessage) {
                successStateMessage.textContent = 'නව තහවුරු කිරීමේ සබැඳියක් ඔබගේ ඊමේල් ලිපිනයට යවා ඇත. කරුණාකර ඔබගේ ඊමේල් inbox එක පරීක්ෂා කරන්න.';
            }
            
        } catch (error) {
            console.error('Resend verification email error:', error);
            showToast('ඊමේල් යැවීමේදී දෝෂයක් ඇති විය. කරුණාකර පසුව නැවත උත්සාහ කරන්න.', 'error');
        } finally {
            // Re-enable button
            resendSubmitBtn.disabled = false;
            resendSubmitBtn.innerHTML = originalButtonText;
        }
    });
</script>
HTML;

// Include footer
include 'footer.php';
?>
```