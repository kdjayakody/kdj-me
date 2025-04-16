<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - kdj.lk</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kdjred: '#cb2127',
                        kdjdark: '#141a20',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-8">
        <div class="text-center mb-8">
            <!-- Add KDJ logo here if available -->
            <h2 class="text-2xl font-bold text-kdjdark">ඇතුල් වන්න</h2>
        </div>
        
        <form id="loginForm" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-kdjdark mb-1">ඊමේල් ලිපිනය:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" required 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-kdjred focus:border-kdjred sm:text-sm"
                        placeholder="your@email.com">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-kdjdark mb-1">මුරපදය:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" required 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-kdjred focus:border-kdjred sm:text-sm">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center">
                <input id="remember_me" name="remember_me" type="checkbox" 
                    class="h-4 w-4 border-gray-300 rounded text-kdjred focus:ring-kdjred">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">මතක තබාගන්න</label>
            </div>
            
            <div>
                <button type="submit" id="submitButton" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-kdjred hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdjred transition duration-150">
                    <span id="buttonText">ඇතුල් වන්න</span>
                    <span id="buttonLoader" class="hidden ml-2">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        </form>
        
        <div id="message" class="mt-4 p-3 rounded-md text-center hidden"></div>
        
        <div class="mt-6 text-center space-y-2">
            <a href="register.php" class="block text-sm font-medium text-kdjred hover:text-red-800 transition">ලියාපදිංචි වන්න</a>
            <a href="forgot_password.php" class="block text-sm font-medium text-kdjred hover:text-red-800 transition">මුරපදය අමතකද?</a>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const loginApiUrl = 'https://auth.kdj.lk/api/v1/auth/login';
        const redirectUrlAfterLogin = 'dashboard.php'; // Change as needed
        // --------------------

        const loginForm = document.getElementById('loginForm');
        const messageDiv = document.getElementById('message');
        const submitButton = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const buttonLoader = document.getElementById('buttonLoader');
        const togglePasswordButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        // Toggle password visibility
        togglePasswordButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Toggle icon
            this.innerHTML = type === 'password' ? '<i class="fa-regular fa-eye"></i>' : '<i class="fa-regular fa-eye-slash"></i>';
        });

        // Check if user is already logged in
        async function checkSession() {
            try {
                submitButton.disabled = true;
                buttonText.textContent = 'සැසිය පරීක්ෂා කරමින්...';
                buttonLoader.classList.remove('hidden');
                
                const response = await fetch('https://auth.kdj.lk/api/v1/users/me', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'include'
                });
                
                if (response.ok) {
                    // User is already logged in, redirect to dashboard
                    window.location.href = redirectUrlAfterLogin;
                } else {
                    submitButton.disabled = false;
                    buttonText.textContent = 'ඇතුල් වන්න';
                    buttonLoader.classList.add('hidden');
                }
            } catch (error) {
                // Ignore errors - just proceed with login form
                console.log("No active session found");
                submitButton.disabled = false;
                buttonText.textContent = 'ඇතුල් වන්න';
                buttonLoader.classList.add('hidden');
            }
        }

        // Call check session on page load
        checkSession();

        function showMessage(text, isError = false) {
            messageDiv.textContent = text;
            messageDiv.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            
            if (isError) {
                messageDiv.classList.add('bg-red-100', 'text-red-800');
            } else {
                messageDiv.classList.add('bg-green-100', 'text-green-800');
            }
        }

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember_me').checked;

            // Clear previous messages
            messageDiv.classList.add('hidden');
            
            // Basic validation
            if (!email || !password) {
                showMessage('කරුණාකර ඊමේල් සහ මුරපදය ඇතුලත් කරන්න.', true);
                return;
            }

            // Disable button to prevent multiple submissions
            submitButton.disabled = true;
            buttonText.textContent = 'ඇතුල් වෙමින්...';
            buttonLoader.classList.remove('hidden');

            try {
                const response = await fetch(loginApiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember_me: rememberMe
                    }),
                    credentials: 'include'
                });

                const responseData = await response.json();

                if (response.ok) {
                    showMessage('සාර්ථකව ඇතුල් විය!', false);
                    
                    // Handle MFA if required
                    if (responseData.mfa_required) {
                        // Redirect to MFA verification page
                        window.location.href = 'mfa_verify.php';
                        return;
                    }

                    // Store tokens in localStorage if needed (for API calls)
                    if (responseData.access_token) {
                        localStorage.setItem('access_token', responseData.access_token);
                        if (responseData.refresh_token) {
                            localStorage.setItem('refresh_token', responseData.refresh_token);
                        }
                    }

                    // Redirect after successful login
                    setTimeout(() => {
                        window.location.href = redirectUrlAfterLogin;
                    }, 1000);
                } else {
                    // Login failed
                    let errorMessage = 'ඇතුල් වීමට නොහැක. ';
                    
                    if (responseData.detail) {
                        if (typeof responseData.detail === 'string') {
                            // Map common error messages to Sinhala
                            if (responseData.detail.includes('Invalid email or password')) {
                                errorMessage = 'වලංගු නොවන ඊමේල් හෝ මුරපදය.';
                            } else if (responseData.detail.includes('Token expired')) {
                                errorMessage = 'සැසිය කල් ඉකුත් වී ඇත. නැවත පුරනය කරන්න.';
                            } else if (responseData.detail.includes('Account temporarily locked')) {
                                errorMessage = 'ගිණුම තාවකාලිකව අගුළු දමා ඇත. පසුව නැවත උත්සාහ කරන්න.';
                            } else {
                                errorMessage += responseData.detail;
                            }
                        } else if (Array.isArray(responseData.detail)) {
                            errorMessage += responseData.detail.map(err => `${err.loc.join('.')}: ${err.msg}`).join(', ');
                        } else {
                            errorMessage += JSON.stringify(responseData.detail);
                        }
                    } else {
                        errorMessage += `දෝෂ කේතය: ${response.status}`;
                    }
                    
                    showMessage(errorMessage, true);
                }
            } catch (error) {
                // Network error or other issue
                showMessage('ඇතුල් වීමේ ඉල්ලීම යැවීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සාහ කරන්න.', true);
                console.error('Login error:', error);
            } finally {
                // Re-enable button
                submitButton.disabled = false;
                buttonText.textContent = 'ඇතුල් වන්න';
                buttonLoader.classList.add('hidden');
            }
        });
    </script>
</body>
</html>