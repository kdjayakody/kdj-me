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
                <button type="submit">ඇතුල් වන්න</button>
            </div>
        </form>
        <div id="message" style="display: none;"></div> </div>

    <script>
        // --- Configuration ---
        // ඔයාගේ FastAPI Backend එකේ login endpoint එක මෙතන දාන්න
        const loginApiUrl = 'https://auth.kdj.lk/api/v1/auth/login';
        // Login උනාට පස්සේ redirect වෙන්න ඕන තැන මෙතන දාන්න (උදා: dashboard එකක්)
        // හිස් තිබ්බොත් redirect වෙන්නේ නැහැ, success message එකක් පෙන්වයි.
        const redirectUrlAfterLogin = 'https://events.kdj.lk'; // Example
        // --------------------

        const loginForm = document.getElementById('loginForm');
        const messageDiv = document.getElementById('message');

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault(); // Form එක default විදිහට submit වෙන එක නවත්තනවා

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember_me').checked;
            const messageDiv = document.getElementById('message');

            // Clear previous messages
            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = ''; // Remove previous classes

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
                    credentials: 'include' // Very important! Allows browser to handle cookies from backend
                });

                const responseData = await response.json();

                if (response.ok) {
                    // Login සාර්ථකයි!
                    console.log('Login successful:', responseData);
                    messageDiv.textContent = 'සාර්ථකව ඇතුල් විය!';
                    messageDiv.className = 'message-success';
                    messageDiv.style.display = 'block';

                    // Optional: Store access token if frontend needs it (cookie handles backend auth)
                    // if (responseData.access_token) {
                    //     localStorage.setItem('accessToken', responseData.access_token);
                    // }

                    // Redirect if configured
                    if (redirectUrlAfterLogin) {
                        // Add a small delay so user can see the success message
                        setTimeout(() => {
                            window.location.href = redirectUrlAfterLogin;
                        }, 1000); // 1 second delay
                    }

                } else {
                    // Login අසාර්ථකයි
                    console.error('Login failed:', responseData);
                    let errorMessage = 'ඇතුල් වීමට නොහැක. ';
                    if (responseData.detail) {
                        // FastAPI එකෙන් එන error message එක පෙන්නනවා
                        if (typeof responseData.detail === 'string') {
                           errorMessage += responseData.detail;
                        } else if (Array.isArray(responseData.detail)) {
                           // Handle validation errors (if any)
                           errorMessage += responseData.detail.map(err => `${err.loc.join('.')}: ${err.msg}`).join(', ');
                        } else if (typeof responseData.detail === 'object') {
                            errorMessage += JSON.stringify(responseData.detail);
                        }
                    } else {
                        errorMessage += `Error code: ${response.status}`;
                    }
                     messageDiv.textContent = errorMessage;
                     messageDiv.className = 'message-error';
                     messageDiv.style.display = 'block';
                }

            } catch (error) {
                // Network error or other issue
                console.error('Error during login request:', error);
                messageDiv.textContent = 'Login request එක යැවීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.';
                messageDiv.className = 'message-error';
                messageDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>