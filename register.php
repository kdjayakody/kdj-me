<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - kdj.lk</title>
    <style>
        /* කලින් login page එකේ CSS ටිකම මෙතනට දාන්න (or link කරන්න) */
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
            </div>
             <div class="form-group">
                <label for="confirm_password">මුරපදය තහවුරු කරන්න:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit">ලියාපදිංචි වන්න</button>
            </div>
        </form>
        <div id="message"></div> <div class="login-link">
            දැනටමත් ගිණුමක් තිබේද? <a href="login.php">ඇතුල් වන්න</a>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const apiBaseUrl = 'https://auth.kdj.lk/api/v1'; // Change if needed
        // --------------------

        const registerForm = document.getElementById('registerForm');
        const messageDiv = document.getElementById('message');

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

            if (password !== confirmPassword) {
                messageDiv.textContent = 'මුරපද දෙක සමාන නොවේ!';
                messageDiv.className = 'message-error';
                messageDiv.style.display = 'block';
                return;
            }

            const registrationData = {
                email: email,
                password: password,
                display_name: displayName || null, // Send null if empty
                phone_number: phoneNumber || null // Send null if empty
            };

            try {
                const response = await fetch(`${apiBaseUrl}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(registrationData)
                    // credentials: 'include' // Not needed for register usually
                });

                const responseData = await response.json();

                if (response.status === 201) { // Check for 201 Created status
                    console.log('Registration successful:', responseData);
                    messageDiv.textContent = responseData.message || 'සාර්ථකව ලියාපදිංචි විය! කරුණාකර ඔබගේ ඊමේල් පරීක්ෂා කරන්න.';
                    messageDiv.className = 'message-success';
                    messageDiv.style.display = 'block';
                    registerForm.reset(); // Clear the form
                     // Optionally redirect to login page after a delay
                    // setTimeout(() => { window.location.href = 'login.php'; }, 3000);

                } else {
                    console.error('Registration failed:', responseData);
                    let errorMessage = 'ලියාපදිංචි වීමට නොහැක. ';
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
                console.error('Error during registration request:', error);
                messageDiv.textContent = 'ලියාපදිංචි වීමේදී දෝෂයක් ඇතිවිය. කරුණාකර නැවත උත්සහ කරන්න.';
                messageDiv.className = 'message-error';
                 messageDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>