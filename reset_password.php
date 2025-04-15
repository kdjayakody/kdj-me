<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - kdj.lk</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 350px; }
        .form-container h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .form-group button:hover { background-color: #0056b3; }
        .form-group button:disabled { background-color: #6c757d; cursor: not-allowed; }
        #message { margin-top: 15px; padding: 10px; border-radius: 4px; text-align: center; font-size: 14px; display: none; }
        .message-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .login-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .login-link a { color: #007bff; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
        .password-requirements { font-size: 12px; color: #6c757d; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>නව මුරපදයක් සකසන්න</h2>
        <form id="resetPasswordForm">
            <input type="hidden" id="reset_token" name="reset_token">
            <div class="form-group">
                <label for="new_password">නව මුරපදය:</label>
                <input type="password" id="new_password" name="new_password" required>
                <div class="password-requirements">
                    මුරපදය අඩුම තරමින් අක්ෂර 12ක් විය යුතුය, ලොකු අකුරු, කුඩා අකුරු, ඉලක්කම් සහ විශේෂ අක්ෂර අඩංගු විය යුතුය.
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">නව මුරපදය තහවුරු කරන්න:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit" id="submitButton">මුරපදය Reset කරන්න</button>
            </div>
        </form>
        <div id="message"></div>
        <div class="login-link" id="loginLink" style="display: none;">
            <a href="index.php">Login පිටුවට යන්න</a>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
        // --------------------

        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const messageDiv = document.getElementById('message');
        const tokenInput = document.getElementById('reset_token');
        const loginLink = document.getElementById('loginLink');
        const submitButton = document.getElementById('submitButton');

        // Simple password strength validation to match backend requirements
        function validatePassword(password) {
            const minLength = 12;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasDigits = /\d/.test(password);
            const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            const errors = [];
            
            if (password.length < minLength) {
                errors.push(`මුරපදය අඩුම තරමින් අක්ෂර ${minLength}ක් විය යුතුය`);
            }
            
            if (!hasUppercase) {
                errors.push("අඩුම තරමින් එක් ලොකු අකුරක් තිබිය යුතුය");
            }
            
            if (!hasLowercase) {
                errors.push("අඩුම තරමින් එක් කුඩා අකුරක් තිබිය යුතුය");
            }
            
            if (!hasDigits) {
                errors.push("අඩුම තරමින් එක් ඉලක්කමක් තිබිය යුතුය");
            }
            
            if (!hasSpecialChars) {
                errors.push("අඩුම තරමින් එක් විශේෂ අක්ෂරයක් තිබිය යුතුය");
            }
            
            return {
                valid: errors.length === 0,
                errors: errors
            };
        }

        function showMessage(text, isError = false) {
            messageDiv.textContent = text;
            messageDiv.className = isError ? 'message-error' : 'message-success';
            messageDiv.style.display = 'block';
        }

        // Get token from URL query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');

        if (token) {
            tokenInput.value = token;
        } else {
            showMessage('Reset token එකක් හමුවුනේ නැත. කරුණාකර email එකේ ඇති link එක හරහා පිවිසෙන්න.', true);
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
                showMessage('Reset token එකක් හමුවුනේ නැත.', true);
                return;
            }

            if (newPassword !== confirmPassword) {
                showMessage('නව මුරපද දෙක සමාන නොවේ!', true);
                return;
            }
            
            // Validate password strength
            const passwordValidation = validatePassword(newPassword);
            if (!passwordValidation.valid) {
                showMessage('මුරපදය ප්‍රමාණවත් තරම් ශක්තිමත් නොවේ: ' + passwordValidation.errors.join(', '), true);
                return;
            }
            
            submitButton.disabled = true;
            submitButton.textContent = 'සකසමින්...';

            try {
                // Make API call to reset password
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
                    showMessage(responseData.message || 'මුරපදය සාර්ථකව reset කරන ලදී.', false);
                    loginLink.style.display = 'block';
                    resetPasswordForm.reset();
                    resetPasswordForm.style.display = 'none';
                } else {
                    let errorMessage = 'මුරපදය reset කිරීමට නොහැක. ';
                    
                    if (responseData.detail) {
                        if (typeof responseData.detail === 'string') {
                            if (responseData.detail.includes('Token expired')) {
                                errorMessage = 'Reset link එක කල් ඉකුත් වී ඇත. කරුණාකර නැවත link එකක් ඉල්ලන්න.';
                            } else if (responseData.detail.includes('Password must')) {
                                errorMessage = responseData.detail.replace('Password must', 'මුරපදය අනිවාර්යයෙන්');
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
                    submitButton.disabled = false;
                    submitButton.textContent = 'මුරපදය Reset කරන්න';
                }
            } catch (error) {
                console.error('Error during password reset request:', error);
                showMessage('මුරපදය reset කිරීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', true);
                submitButton.disabled = false;
                submitButton.textContent = 'මුරපදය Reset කරන්න';
            }
        });
    </script>
</body>
</html>