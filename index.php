<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - kdj.lk</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Important */
        }
        .form-group input[type="checkbox"] {
            margin-right: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        #message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }
        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .links {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ඇතුල් වන්න</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="email">ඊමේල් ලිපිනය:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">මුරපදය:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">මතක තබාගන්න</label>
            </div>
            <div class="form-group">
                <button type="submit" id="submitButton">ඇතුල් වන්න</button>
            </div>
        </form>
        <div id="message" style="display: none;"></div>
        <div class="links">
            <a href="register.php">ලියාපදිංචි වන්න</a>
            <a href="forgot_password.php">මුරපදය අමතකද?</a>
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

        // Check if user is already logged in
        async function checkSession() {
            try {
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
                }
            } catch (error) {
                // Ignore errors - just proceed with login form
                console.log("No active session found");
            }
        }

        // Call check session on page load
        checkSession();

        function showMessage(text, isError = false) {
            messageDiv.textContent = text;
            messageDiv.className = isError ? 'message-error' : 'message-success';
            messageDiv.style.display = 'block';
        }

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember_me').checked;

            // Clear previous messages
            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = '';

            // Disable button to prevent multiple submissions
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

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
                            } else {
                                errorMessage += responseData.detail;
                            }
                        } else if (Array.isArray(responseData.detail)) {
                            errorMessage += responseData.detail.map(err => `${err.loc.join('.')}: ${err.msg}`).join(', ');
                        } else {
                            errorMessage += JSON.stringify(responseData.detail);
                        }
                    } else {
                        errorMessage += `Error code: ${response.status}`;
                    }
                    
                    showMessage(errorMessage, true);
                }
            } catch (error) {
                // Network error or other issue
                showMessage('Login request එක යැවීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', true);
            } finally {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = 'ඇතුල් වන්න';
            }
        });
    </script>
</body>
</html>