<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KDJ Lanka</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'kdj-red': '#cb2127',
                        'kdj-dark': '#141a20',
                        'kdj-white': '#ffffff',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">ඇතුල් වන්න</h2>
        <p class="text-center text-gray-600 mb-6">ඔබගේ ගිණුමට ප්‍රවේශ වන්න</p>
        
        <form id="loginForm" class="space-y-4">
            <div class="relative">
                <label for="email" class="block text-gray-700 font-bold mb-2">ඊමේල් ලිපිනය</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-kdj-red"
                    >
                </div>
            </div>
            
            <div class="relative">
                <label for="password" class="block text-gray-700 font-bold mb-2">මුරපදය</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-kdj-red"
                    >
                    <span 
                        id="togglePassword" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-kdj-red"
                    >
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember_me" 
                        name="remember_me" 
                        class="mr-2 text-kdj-red focus:ring-kdj-red"
                    >
                    <label for="remember_me" class="text-gray-700">මතක තබාගන්න</label>
                </div>
                <a href="forgot_password.php" class="text-kdj-red hover:underline">මුරපදය අමතකද?</a>
            </div>
            
            <button 
                type="submit" 
                id="submitButton" 
                class="w-full bg-kdj-red text-white py-2 rounded-md hover:bg-red-700 transition duration-300 flex items-center justify-center space-x-2"
            >
                <i class="fas fa-sign-in-alt"></i>
                <span>ඇතුල් වන්න</span>
            </button>
        </form>
        
        <div 
            id="messageArea" 
            class="mt-4 p-3 rounded-md text-center hidden"
            :class="{
                'bg-green-100 text-green-800': type === 'success',
                'bg-red-100 text-red-800': type === 'error'
            }"
        ></div>
        
        <div class="text-center mt-4 text-sm">
            ගිණුමක් නැද්ද? 
            <a href="register.php" class="text-kdj-red hover:underline">ලියාපදිංචි වන්න</a>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const rememberMeInput = document.getElementById('remember_me');
        const messageArea = document.getElementById('messageArea');
        const submitButton = document.getElementById('submitButton');
        const togglePassword = document.getElementById('togglePassword');

        const API_BASE_URL = 'https://auth.kdj.lk';
        const REDIRECT_URL = 'dashboard.php';

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            const icon = togglePassword.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            messageArea.style.display = 'none';
            messageArea.className = 'mt-4 p-3 rounded-md text-center';
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> සකසමින්...';

            const email = emailInput.value;
            const password = passwordInput.value;
            const rememberMe = rememberMeInput.checked;

            if (!email || !password) {
                showMessage('කරුණාකර ඊමේල් සහ මුරපදය ඇතුළත් කරන්න', 'error');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> ඇතුල් වන්න';
                return;
            }

            if (!isValidEmail(email)) {
                showMessage('කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න', 'error');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> ඇතුල් වන්න';
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember_me: rememberMe
                    }),
                    credentials: 'include'
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage('සාර්ථකව ඇතුල් විය!', 'success');

                    if (data.access_token) {
                        sessionStorage.setItem('auth_token', data.access_token);
                        if (data.expires_in) {
                            const expiryTime = Date.now() + (data.expires_in * 1000);
                            sessionStorage.setItem('token_expiry', expiryTime.toString());
                        }
                        if (data.refresh_token) {
                            sessionStorage.setItem('refresh_token', data.refresh_token);
                        }
                        if (rememberMe && data.user_id) {
                            localStorage.setItem('user_id', data.user_id);
                        }
                    }

                    if (data.mfa_required && data.mfa_methods && data.mfa_methods.length > 0) {
                        window.location.href = `mfa.php?methods=${data.mfa_methods.join(',')}`;
                        return;
                    }

                    setTimeout(() => {
                        window.location.href = REDIRECT_URL;
                    }, 1000);
                } else {
                    let errorMessage = 'ඇතුල් වීමට නොහැක. ';
                    if (data.detail) {
                        if (typeof data.detail === 'string') {
                            if (data.detail.includes('Invalid email or password')) {
                                errorMessage = 'වලංගු නොවන ඊමේල් හෝ මුරපදය.';
                            } else if (data.detail.includes('Token expired')) {
                                errorMessage = 'සැසිය කල් ඉකුත් වී ඇත. නැවත පුරනය කරන්න.';
                            } else {
                                errorMessage += data.detail;
                            }
                        } else {
                            errorMessage += JSON.stringify(data.detail);
                        }
                    } else {
                        errorMessage += `Error code: ${response.status}`;
                    }
                    showMessage(errorMessage, 'error');
                }
            } catch (error) {
                showMessage('ඉල්ලීම යැවීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> ඇතුල් වන්න';
            }
        });

        function showMessage(msg, type) {
            messageArea.textContent = msg;
            messageArea.classList.add(type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
            messageArea.style.display = 'block';
        }

        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
</body>
</html>