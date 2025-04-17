<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ඇතුල් වන්න - KDJ Lanka</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom spinner animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
        /* Base styles */
        body {
            background-color: #f8fafc; /* Slightly different light gray */
        }
        /* Focus styles to mimic the subtle outline often seen */
        .form-input:focus {
             outline: none;
             border-color: #f87171; /* Lighter red border on focus */
             box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2); /* Subtle red ring */
        }
        /* Custom checkbox style (optional, if default plugin style isn't enough) */
        /* .custom-checkbox { */
            /* Add custom styles here */
        /* } */
    </style>
    <script>
      // Define brand color for consistency
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-red': '#cb2127', // Primary Red from previous examples
              'brand-red-dark': '#8c0e12', // Darker shade for hover
              'brand-gray-light': '#f8fafc',
              'brand-gray-medium': '#e5e7eb',
              'brand-gray-dark': '#6b7280',
              'brand-text': '#1f2937', // Darker text color
            }
          }
        }
      }
    </script>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="px-8 py-10 sm:px-10 sm:py-12">
        <div class="text-center">
            <img src="assets/img/kdjcolorlogo.png" style="width:40%; margin: auto;">
            <h2 class="text-2xl sm:text-3xl font-bold text-brand-text">ගිණුමට පිවිසෙන්න</h2>
            <p class="mt-2 text-sm text-brand-gray-dark">නැවතත් ඔබව සාදරයෙන් පිළිගනිමු!</p>
        </div>

         <div id="messageArea" class="my-6 p-3 rounded-md text-center font-medium text-sm hidden"></div>

        <form id="loginForm" class="mt-8 space-y-6">
            <div>
                <label for="email" class="block text-xs font-medium text-brand-gray-dark mb-1">ඊමේල් ලිපිනය</label>
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 text-center text-brand-gray-dark">
                         <i class="fas fa-envelope text-base"></i>
                    </div>
                    <input type="email" id="email" name="email" required
                           class="form-input flex-grow block w-full border border-brand-gray-medium rounded-md shadow-sm py-2 px-3 focus:border-brand-red focus:ring-brand-red sm:text-sm">
                </div>
                <p class="mt-1 text-xs text-red-500" id="emailError" style="display: none;"></p>
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-brand-gray-dark mb-1">මුරපදය</label>
                <div class="flex items-center relative">
                     <div class="flex-shrink-0 w-8 text-center text-brand-gray-dark">
                         <i class="fas fa-lock text-base"></i>
                    </div>
                    <input type="password" id="password" name="password" required
                           class="form-input flex-grow block w-full border border-brand-gray-medium rounded-md shadow-sm py-2 px-3 pr-10 focus:border-brand-red focus:ring-brand-red sm:text-sm"> <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-brand-gray-dark hover:text-brand-text" id="togglePassword">
                        <i class="fas fa-eye text-sm"></i>
                    </span>
                </div>
                 <p class="mt-1 text-xs text-red-500" id="passwordError" style="display: none;"></p>
            </div>

            <div class="flex items-center justify-between text-xs sm:text-sm">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me"
                           class="custom-checkbox h-4 w-4 text-brand-red focus:ring-brand-red border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-gray-700">මතක තබාගන්න</label>
                </div>

                <div>
                    <a href="forgot_password.php" class="font-medium text-brand-red hover:text-brand-red-dark hover:underline">මුරපදය අමතකද?</a>
                </div>
            </div>

            <div>
                <button type="submit" id="submitButton"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-red hover:bg-brand-red-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red transition duration-150 ease-in-out disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="buttonText">ඇතුල් වන්න</span>
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm">
            <span class="text-gray-600">ගිණුමක් නැද්ද?</span>
            <a href="register.php" class="font-medium text-brand-red hover:text-brand-red-dark hover:underline ml-1">ලියාපදිංචි වන්න</a>
         </div>
    </div>
</div>

<script>
    // --- Get DOM Elements ---
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberMeInput = document.getElementById('remember_me');
    const messageArea = document.getElementById('messageArea');
    const submitButton = document.getElementById('submitButton');
    const togglePassword = document.getElementById('togglePassword');
    const buttonText = document.getElementById('buttonText');

    const emailErrorEl = document.getElementById('emailError');
    const passwordErrorEl = document.getElementById('passwordError');

    // --- Constants ---
    const API_BASE_URL = 'https://auth.kdj.lk';
    const REDIRECT_URL = 'dashboard.php'; // Adjust if needed

    // --- Event Listeners ---
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        const icon = togglePassword.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearMessages();
        disableSubmitButton('සකසමින්...'); // Show loading state

        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const rememberMe = rememberMeInput.checked;

        // --- Basic Client Validation ---
        let isValid = validateInputs(email, password);
        if (!isValid) {
            enableSubmitButton();
            return;
        }

        // --- API Call ---
        try {
            const response = await fetch(`${API_BASE_URL}/api/v1/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, password, remember_me: rememberMe }),
                credentials: 'include'
            });

            const data = await response.json();

            if (response.ok) {
                handleLoginSuccess(data, rememberMe);
            } else {
                handleApiError(data, response.status);
                enableSubmitButton();
            }
        } catch (error) {
            handleNetworkError(error);
            enableSubmitButton();
        }
    });

    // --- Helper Functions ---

    function validateInputs(email, password) {
        let isValid = true;
        clearMessages(); // Clear previous errors first

        if (!email) {
            showInputError(emailErrorEl, 'ඊමේල් ලිපිනය අවශ්‍යයි.');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showInputError(emailErrorEl, 'කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න.');
            isValid = false;
        }

        if (!password) {
            showInputError(passwordErrorEl, 'මුරපදය අවශ්‍යයි.');
            isValid = false;
        }
        return isValid;
    }

    function handleLoginSuccess(data, rememberMe) {
        showMessage('සාර්ථකව ඇතුල් විය! යොමු කරමින්...', 'success');
        handleTokenStorage(data, rememberMe);

        // Redirect based on MFA status
        const redirectTarget = (data.mfa_required && data.mfa_methods?.length > 0)
            ? `mfa.php?methods=${data.mfa_methods.join(',')}` // MFA page
            : REDIRECT_URL; // Dashboard

        setTimeout(() => { window.location.href = redirectTarget; }, 1000);
    }


     function handleTokenStorage(data, rememberMe) {
        const storage = rememberMe ? localStorage : sessionStorage;
        const otherStorage = rememberMe ? sessionStorage : localStorage; // For clearing opposite storage

         if (data.access_token) {
            sessionStorage.setItem('auth_token', data.access_token); // Access token always in session storage
            if (data.expires_in) {
                const expiryTime = Date.now() + (data.expires_in * 1000);
                sessionStorage.setItem('token_expiry', expiryTime.toString());
            }
        }

         if (data.refresh_token) {
             storage.setItem('refresh_token', data.refresh_token); // Store refresh token based on rememberMe
             otherStorage.removeItem('refresh_token'); // Clear from the other storage
         } else {
            // Ensure refresh token is cleared if not provided
             localStorage.removeItem('refresh_token');
             sessionStorage.removeItem('refresh_token');
         }


        if (data.user_id) {
             storage.setItem('user_id', data.user_id);
             otherStorage.removeItem('user_id');
         } else {
            // Ensure user ID is cleared if not provided
             localStorage.removeItem('user_id');
             sessionStorage.removeItem('user_id');
         }
    }


    function handleApiError(data, status) {
        let errorMessage = 'ඇතුල් වීමට නොහැක. ';
        if (data?.detail) {
            if (typeof data.detail === 'string') {
                if (data.detail.includes('Invalid email or password') || data.detail.includes('INVALID_LOGIN_CREDENTIALS')) {
                    errorMessage = 'වලංගු නොවන ඊමේල් හෝ මුරපදය.';
                    showInputError(emailErrorEl, ' '); emailInput.focus();
                    showInputError(passwordErrorEl, ' ');
                } else if (data.detail.includes('Account temporarily locked')) {
                    errorMessage = data.detail; // Show lockout message from API
                } else if (data.detail.includes('Account disabled')) {
                    errorMessage = 'ඔබගේ ගිණුම අක්‍රිය කර ඇත.';
                } else {
                    errorMessage += data.detail;
                }
            } else {
                errorMessage += JSON.stringify(data.detail);
            }
        } else {
            errorMessage += `සේවාදායකයේ දෝෂයක් (කේතය: ${status})`;
        }
        showMessage(errorMessage, 'error');
    }

    function handleNetworkError(error) {
        console.error('Login Fetch Error:', error);
        showMessage('ඉල්ලීම යැවීමේදී දෝෂයක් ඇතිවිය. ඔබගේ සම්බන්ධතාවය පරීක්ෂා කර නැවත උත්සහ කරන්න.', 'error');
    }


    function showMessage(msg, type) {
        messageArea.textContent = msg;
        // Base classes + type specific classes
        let typeClasses = 'border ';
        if (type === 'success') {
            typeClasses += 'bg-green-50 border-green-300 text-green-700';
        } else if (type === 'error') {
            typeClasses += 'bg-red-50 border-red-300 text-red-700';
        } else { // Info or default
            typeClasses += 'bg-blue-50 border-blue-300 text-blue-700';
        }
        messageArea.className = `my-6 p-3 rounded-md text-center font-medium text-sm ${typeClasses}`;
        messageArea.style.display = 'block';
    }

    function showInputError(element, message) {
        if (!element) return;
        element.textContent = message;
        element.style.display = 'block';
        const input = element.closest('div')?.querySelector('input'); // Find input in parent div
        if (input) {
            input.classList.add('border-red-500'); // Add red border
            input.classList.remove('focus:border-brand-red','focus:ring-brand-red'); // Remove default focus
            input.classList.add('focus:border-red-500','focus:ring-red-500'); // Add red focus
        }
    }

     function clearMessages() {
        messageArea.style.display = 'none';
        messageArea.textContent = '';
        if (emailErrorEl) emailErrorEl.style.display = 'none';
        if (passwordErrorEl) passwordErrorEl.style.display = 'none';

        // Remove red borders and restore default focus
        [emailInput, passwordInput].forEach(input => {
            input?.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            input?.classList.add('focus:border-brand-red', 'focus:ring-brand-red');
        });
    }


    function disableSubmitButton(text) {
        submitButton.disabled = true;
        buttonText.textContent = text;
        // Add spinner using font awesome - ensure it replaces original text/icon structure correctly
        submitButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i><span>${text}</span>`;
    }

    function enableSubmitButton() {
        submitButton.disabled = false;
         // Restore original button text/structure
        submitButton.innerHTML = `<span id="buttonText">ඇතුල් වන්න</span>`;
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

</script>

</body>
</html>
