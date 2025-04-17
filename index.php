<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KDJ Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
        p.subtitle {
            text-align: center;
            margin-bottom: 25px;
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group i {
            position: absolute;
            left: 10px;
            top: 38px;
            color: #999;
        }
        .form-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
            color: #999;
        }
        .form-group.remember-me {
            display: flex;
            align-items: center;
        }
        .form-group.remember-me input[type="checkbox"] {
            margin-right: 5px;
        }
        .form-group.remember-me label {
            display: inline-block;
            font-weight: normal;
            color: #666;
        }
        .form-group.links {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff3333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            position: relative;
        }
        button:hover {
            background-color: #cc0000;
        }
        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        button i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            display: none;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .links {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }
        .links a {
            color: #ff3333;
            text-decoration: none;
            margin: 0 5px;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>ඇතුල් වන්න</h2>
    <p class="subtitle">ඔබගේ ගිණුමට ප්‍රවේශ වන්න</p>
    <form id="loginForm">
        <div class="form-group">
            <label for="email">ඊමේල් ලිපිනය</label>
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">මුරපදය</label>
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" id="togglePassword">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        <div class="form-group links">
            <div class="remember-me">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">මතක තබාගන්න</label>
            </div>
            <div>
                <a href="forgot_password.php">මුරපදය අමතකද?</a>
            </div>
        </div>
        <button type="submit" id="submitButton">
            <i class="fas fa-sign-in-alt"></i>
            ඇතුල් වන්න
        </button>
    </form>
    <div id="messageArea" class="message"></div>
    <div class="links">
        ගිණුමක් නැද්ද? <a href="register.php">ලියාපදිංචි වන්න</a>
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
        messageArea.className = 'message';
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> සකසමින්...';

        const email = emailInput.value;
        const password = passwordInput.value;
        const rememberMe = rememberMeInput.checked;

        if (!email || !password) {
            showMessage('කරුණාකර ඊමේල් සහ මුරපදය ඇතුළත් කරන්න', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> ඇතුල් වන්න';
            return;
        }

        if (!isValidEmail(email)) {
            showMessage('කරුණාකර වලංගු ඊමේල් ලිපිනයක් ඇතුළත් කරන්න', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> ඇතුල් වන්න';
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
            submitButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> ඇතුල් වන්න';
        }
    });

    function showMessage(msg, type) {
        messageArea.textContent = msg;
        messageArea.classList.add(type);
        messageArea.style.display = 'block';
    }

    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
</script>

</body>
</html>