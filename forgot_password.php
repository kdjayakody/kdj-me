<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - kdj.lk</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 350px; }
        .form-container h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background-color: #ffc107; color: #333; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .form-group button:hover { background-color: #e0a800; }
        .form-group button:disabled { background-color: #f8d775; cursor: not-allowed; }
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
        <h2>මුරපදය අමතකද?</h2>
        <p style="text-align: center; font-size: 14px; color: #666;">ඔබගේ ඊමේල් ලිපිනය ඇතුලත් කරන්න. මුරපදය නැවත සකස් කිරීමේ සබැඳියක් අපි එවන්නෙමු.</p>
        <form id="forgotPasswordForm">
            <div class="form-group">
                <label for="email">ඊමේල් ලිපිනය:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <button type="submit" id="submitButton">Reset Link එක එවන්න</button>
            </div>
        </form>
        <div id="message"></div>
        <div class="login-link">
            <a href="index.php">Login පිටුවට යන්න</a>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const apiBaseUrl = 'https://auth.kdj.lk/api/v1';
        // --------------------

        const forgotPasswordForm = document.getElementById('forgotPasswordForm');
        const messageDiv = document.getElementById('message');
        const submitButton = document.getElementById('submitButton');

        function showMessage(text, isError = false) {
            messageDiv.textContent = text;
            messageDiv.className = isError ? 'message-error' : 'message-success';
            messageDiv.style.display = 'block';
        }

        forgotPasswordForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const email = document.getElementById('email').value;

            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = '';
            
            submitButton.disabled = true;
            submitButton.textContent = 'සකසමින්...';

            try {
                const response = await fetch(`${apiBaseUrl}/auth/reset-password/request`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const responseData = await response.json();

                // Assume success regardless of whether email exists, as per backend logic
                showMessage(responseData.message || 'ඔබගේ ඊමේල් ලිපිනය ලියාපදිංචි වී ඇත්නම්, ඔබට මුරපදය නැවත සකස් කිරීමේ සබැඳියක් ලැබෙනු ඇත.');
                forgotPasswordForm.reset();

            } catch (error) {
                console.error('Error during forgot password request:', error);
                // Still show the generic success message for security
                showMessage('ඔබගේ ඊමේල් ලිපිනය ලියාපදිංචි වී ඇත්නම්, ඔබට මුරපදය නැවත සකස් කිරීමේ සබැඳියක් ලැබෙනු ඇත.');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Reset Link එක එවන්න';
            }
        });
    </script>
</body>
</html>