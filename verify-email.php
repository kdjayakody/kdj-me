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
        min-height: 100vh;
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

<div class="auth-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
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
        
        <!-- Already Verified State -->
        <div id="alreadyVerifiedState" class="mt-8 hidden">
            <div class="bg-blue-50 p-4 rounded-md border border-blue-200 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">ඊමේල් ලිපිනය දැනටමත් තහවුරු කර ඇත</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>ඔබගේ ඊමේල් ලිපිනය දැනටමත් තහවුරු කර ඇත. ඔබට දැන් ඔබගේ ගිණුමට පිවිසිය හැකිය.</p>
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
    </div>
</div>

<?php
// Page specific scripts
$additional_scripts = <<<HTML
<script>
    // Configuration
    const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
    
    // DOM Elements
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
    const alreadyVerifiedState = document.getElementById('alreadyVerifiedState');
    
    // Get verification token from URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    let originalEmail = urlParams.get('email') || '';
    
    // Pre-fill the email field if provided in URL
    if (originalEmail && resendEmail) {
        resendEmail.value = decodeURIComponent(originalEmail);
    }
    
    // Verify email on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (!token) {
            showMissingTokenError();
        } else {
            verifyEmail();
        }
    });
    
    // Show error when token is missing
    function showMissingTokenError() {
        loadingIcon.classList.add('hidden');
        errorIcon.classList.remove('hidden');
        statusMessage.textContent = 'තහවුරු කිරීමේ සබැඳිය වලංගු නොවේ.';
        errorMessage.textContent = 'තහවුරු කිරීමේ ටෝකනයක් සපයා නැත. කරුණාකර ඊමේල් එකේ ඇති සබැඳිය හරහා පිවිසෙන්න හෝ නව තහවුරු කිරීමේ සබැඳියක් ඉල්ලන්න.';
        errorState.classList.remove('hidden');
    }
    
    // Verify email with token
    async function verifyEmail() {
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
                
                // If user is already logged in, update the verification status in the current session
                const authToken = sessionStorage.getItem('auth_token');
                if (authToken) {
                    refreshUserData(authToken);
                }
                
                // Optional: redirect to dashboard after a delay if user is logged in
                if (sessionStorage.getItem('auth_token')) {
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 3000);
                }
            } else {
                // Show appropriate error state
                errorIcon.classList.remove('hidden');
                statusMessage.textContent = 'ඊමේල් තහවුරු කිරීම අසාර්ථකයි.';
                
                // Handle different error cases
                handleVerificationError(responseData, response.status);
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
    
    // Handle different verification error cases
    function handleVerificationError(responseData, status) {
        // Check if email is already verified
        if (status === 400 && responseData.detail && responseData.detail.includes('already verified')) {
            showAlreadyVerifiedState();
            return;
        }
        
        // Set appropriate error message
        if (responseData.detail) {
            if (typeof responseData.detail === 'string') {
                if (responseData.detail.includes('expired')) {
                    errorMessage.textContent = 'තහවුරු කිරීමේ සබැඳිය කල් ඉකුත් වී ඇත. කරුණාකර නව සබැඳියක් ඉල්ලන්න.';
                } else if (responseData.detail.includes('invalid')) {
                    errorMessage.textContent = 'තහවුරු කිරීමේ සබැඳිය වලංගු නොවේ. කරුණාකර නව සබැඳියක් ඉල්ලන්න.';
                } else {
                    errorMessage.textContent = responseData.detail;
                }
            } else {
                errorMessage.textContent = 'ඊමේල් තහවුරු කිරීමේදී දෝෂයක් ඇති විය.';
            }
        } else {
            errorMessage.textContent = 'ඊමේල් තහවුරු කිරීමේදී දෝෂයක් ඇති විය.';
        }
        
        // Show error state
        errorState.classList.remove('hidden');
    }
    
    // Show already verified state
    function showAlreadyVerifiedState() {
        alreadyVerifiedState.classList.remove('hidden');
        errorState.classList.add('hidden');
        statusMessage.textContent = 'ඊමේල් ලිපිනය දැනටමත් තහවුරු කර ඇත.';
        
        // Optional: redirect to login page after a delay
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 3000);
    }
    
    // Refresh user data after verification (if user is logged in)
    async function refreshUserData(authToken) {
        try {
            const response = await fetch(`\${apiBaseUrl}/users/me`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer \${authToken}`
                },
                credentials: 'include'
            });
            
            if (response.ok) {
                const userData = await response.json();
                
                // Update any UI elements that display the verification status
                if (userData.email_verified) {
                    console.log('User email verification status updated in session.');
                }
            }
        } catch (error) {
            console.error('Failed to refresh user data:', error);
            // Non-critical error, don't display to user
        }
    }
    
    // Show resend form
    resendBtn.addEventListener('click', function(e) {
        e.preventDefault();
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
        
        if (!isValidEmail(email)) {
            showToast('කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න.', 'error');
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
            
            // Always show success message for security reasons
            // (don't reveal if email exists or not)
            showToast('තහවුරු කිරීමේ ඊමේල් පණිවිඩය සාර්ථකව යවන ලදි. කරුණාකර ඔබගේ ඊමේල් inbox එක පරීක්ෂා කරන්න.', 'success');
            
            // Hide resend form and show success message
            resendFormContainer.classList.add('hidden');
            loadingIcon.classList.add('hidden');
            successIcon.classList.remove('hidden');
            statusMessage.textContent = 'නව තහවුරු කිරීමේ සබැඳියක් යවා ඇත';
            
            // Create a custom success message
            const customSuccess = document.createElement('div');
            customSuccess.className = 'mt-8';
            customSuccess.innerHTML = `
                <div class="bg-green-50 p-4 rounded-md border border-green-200 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">තහවුරු කිරීමේ ඊමේල් යවා ඇත</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>නව තහවුරු කිරීමේ සබැඳියක් <strong>\${email}</strong> වෙත යවා ඇත. කරුණාකර ඔබගේ ඊමේල් inbox එක පරීක්ෂා කරන්න.</p>
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
            `;
            
            // Remove any existing states and add the custom success
            [errorState, resendFormContainer, successState, alreadyVerifiedState].forEach(el => {
                if (el.classList) el.classList.add('hidden');
            });
            
            document.querySelector('.verify-card').appendChild(customSuccess);
            
        } catch (error) {
            console.error('Resend verification email error:', error);
            showToast('ඊමේල් යැවීමේදී දෝෂයක් ඇති විය. කරුණාකර පසුව නැවත උත්සාහ කරන්න.', 'error');
            
            // Re-enable button
            resendSubmitBtn.disabled = false;
            resendSubmitBtn.innerHTML = originalButtonText;
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