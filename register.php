<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - kdj.lk</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 350px; }
        .form-container h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input[type="email"],
        .form-group input[type="text"],
        .form-group input[type="tel"],
        .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .form-group button:hover { background-color: #218838; }
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
        <h2>ලියාපදිංචි වන්න</h2>
        <form id="registerForm">
            <div class="form-group">
                <label for="email">ඊමේල් ලිපිනය:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="display_name">පෙන්වන නම (Display Name):</label>
                <input type="text" id="display_name" name="display_name">
            </div>
            <div class="form-group">
                <label for="phone_number">දුරකථන අංකය (අත්‍යවශ්‍ය නොවේ - E.164 format: +947...):</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="+947XXXXXXXX">
            </div>
            <div class="form-group">
                <label for="password">මුරපදය:</label>
                <input type="password" id="password" name="password" required>
                <div class="password-requirements">
                    මුරපදය අඩුම තරමින් අක්ෂර 12ක් විය යුතුය, ලොකු අකුරු, කුඩා අකුරු, ඉලක්කම් සහ විශේෂ අක්ෂර අඩංගු විය යුතුය.
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">මුරපදය තහවුරු කරන්න:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit" id="submitButton">ලියාපදිංචි වන්න</button>
            </div>
        </form>
        <div id="message"></div>
        <div class="login-link">
            දැනටමත් ගිණුමක් තිබේද? <a href="index.php">ඇතුල් වන්න</a>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
        // --------------------

        const registerForm = document.getElementById('registerForm');
        const messageDiv = document.getElementById('message');
        const submitButton = document.getElementById('submitButton');

        function showMessage(text, isError = false) {
            messageDiv.textContent = text;
            messageDiv.className = isError ? 'message-error' : 'message-success';
            messageDiv.style.display = 'block';
            
            // Scroll to message
            messageDiv.scrollIntoView({ behavior: 'smooth' });
        }

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

        registerForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const displayName = document.getElementById('display_name').value;
            const phoneNumber = document.getElementById('phone_number').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = '';

            // Validate password match
            if (password !== confirmPassword) {
                showMessage('මුරපද දෙක සමාන නොවේ!', true);
                return;
            }

            // Validate password strength
            const passwordValidation = validatePassword(password);
            if (!passwordValidation.valid) {
                showMessage('මුරපදය ප්‍රමාණවත් තරම් ශක්තිමත් නොවේ: ' + passwordValidation.errors.join(', '), true);
                return;
            }

            // Validate phone number if provided
            if (phoneNumber && !phoneNumber.match(/^\+[1-9]\d{1,14}$/)) {
                showMessage('දුරකථන අංකය E.164 ආකෘතියෙන් විය යුතුය (උදා: +947XXXXXXXX)', true);
                return;
            }

            const registrationData = {
                email: email,
                password: password,
                display_name: displayName || null,
                phone_number: phoneNumber || null
            };

            // Disable the submit button to prevent multiple submissions
            submitButton.disabled = true;
            submitButton.textContent = 'ලියාපදිංචි වෙමින්...';

            try {
                const response = await fetch(`${apiBaseUrl}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(registrationData)
                });

                const responseData = await response.json();

                if (response.status === 201) {
                    showMessage(responseData.message || 'සාර්ථකව ලියාපදිංචි විය! කරුණාකර ඔබගේ ඊමේල් පරීක්ෂා කරන්න.', false);
                    registerForm.reset();
                    
                    // Redirect to login page after 3 seconds
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                } else {
                    let errorMessage = 'ලියාපදිංචි වීමට නොහැක. ';
                    
                    if (responseData.detail) {
                        if (typeof responseData.detail === 'string') {
                            // Map common error messages to Sinhala
                            if (responseData.detail.includes('Email already exists')) {
                                errorMessage = 'මෙම ඊමේල් ලිපිනය දැනටමත් භාවිතා කරයි.';
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
                }
            } catch (error) {
                showMessage('ලියාපදිංචි වීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.', true);
            } finally {
                // Re-enable the submit button
                submitButton.disabled = false;
                submitButton.textContent = 'ලියාපදිංචි වන්න';
            }
        });
    </script>
</body>
</html>