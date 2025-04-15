<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - kdj.lk</title>
     <style>
        /* register.php එකේ CSS ටිකම මෙතනට දාන්න */
         body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 350px; }
        .form-container h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .form-group button:hover { background-color: #0056b3; }
        #message { margin-top: 15px; padding: 10px; border-radius: 4px; text-align: center; font-size: 14px; display: none; }
        .message-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
          .login-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .login-link a { color: #007bff; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>නව මුරපදයක් සකසන්න</h2>
        <form id="resetPasswordForm">
             <input type="hidden" id="reset_token" name="reset_token"> <div class="form-group">
                <label for="new_password">නව මුරපදය:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
             <div class="form-group">
                <label for="confirm_password">නව මුරපදය තහවුරු කරන්න:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit">මුරපදය Reset කරන්න</button>
            </div>
        </form>
        <div id="message"></div>
         <div class="login-link" id="loginLink" style="display: none;">
             <a href="login.php">Login පිටුවට යන්න</a>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const apiBaseUrl = 'https://auth.kdj.lk/api/v1'; // Change if needed
        // --------------------

        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const messageDiv = document.getElementById('message');
        const tokenInput = document.getElementById('reset_token');
        const loginLink = document.getElementById('loginLink');

        // Get token from URL query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');

        if (token) {
            tokenInput.value = token;
        } else {
            messageDiv.textContent = 'Reset token එකක් හමුවුනේ නැත. කරුණාකර email එකේ ඇති link එක හරහා පිවිසෙන්න.';
             messageDiv.className = 'message-error';
             messageDiv.style.display = 'block';
             resetPasswordForm.style.display = 'none'; // Hide form if no token
        }

        resetPasswordForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const resetToken = tokenInput.value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = '';
            loginLink.style.display = 'none';


            if (!resetToken) {
                 messageDiv.textContent = 'Reset token එකක් හමුවුනේ නැත.';
                 messageDiv.className = 'message-error';
                messageDiv.style.display = 'block';
                return;
            }

             if (newPassword !== confirmPassword) {
                messageDiv.textContent = 'නව මුරපද දෙක සමාන නොවේ!';
                messageDiv.className = 'message-error';
                messageDiv.style.display = 'block';
                return;
            }

            try {
                 // IMPORTANT: The actual backend endpoint might differ.
                 // Check your `app/api/routes/auth.py` for the correct endpoint.
                 // Assuming it's `/auth/reset-password/confirm` for now.
                const response = await fetch(`${apiBaseUrl}/auth/reset-password/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        token: resetToken,
                        new_password: newPassword
                    })
                });

                const responseData = await response.json();

                if (response.ok) {
                    console.log('Password reset successful:', responseData);
                    messageDiv.textContent = responseData.message || 'මුරපදය සාර්ථකව reset කරන ලදී.';
                     messageDiv.className = 'message-success';
                    messageDiv.style.display = 'block';
                    loginLink.style.display = 'block'; // Show login link
                    resetPasswordForm.reset();
                    resetPasswordForm.style.display = 'none'; // Hide form after success

                } else {
                    console.error('Password reset failed:', responseData);
                    let errorMessage = 'මුරපදය reset කිරීමට නොහැක. ';
                     if (responseData.detail) {
                         if (typeof responseData.detail === 'string') {
                           errorMessage += responseData.detail;
                        } else if (Array.isArray(responseData.detail)) {
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
                 console.error('Error during password reset request:', error);
                 messageDiv.textContent = 'මුරපදය reset කිරීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.';
                 messageDiv.className = 'message-error';
                 messageDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>