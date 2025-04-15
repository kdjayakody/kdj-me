<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KDJ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for animations or specific elements */
        .fade-in {
            animation: fadeIn ease 0.5s;
        }
        @keyframes fadeIn {
            0% {opacity:0;}
            100% {opacity:1;}
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center fade-in">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-4 text-center text-gray-700">Login to KDJ</h2>
        <form id="loginForm">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a href="#" class="inline-block align-baseline font-semibold text-sm text-blue-500 hover:text-blue-800">
                    Forgot Password?
                </a>
            </div>
            <div class="mt-4 text-center">
                <p class="text-gray-600 text-sm">Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-700 font-semibold">Register here</a></p>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Make an API call to your backend for login
            fetch('https://auth.kdj.lk/api/v1/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email, password: password }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.access_token) {
                    // Store the token (e.g., in localStorage or a cookie)
                    localStorage.setItem('accessToken', data.access_token);
                    // Redirect to the dashboard or another authenticated page
                    window.location.href = 'dashboard.php';
                } else {
                    alert('Login failed: ' + data.detail);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Login failed due to a network error.');
            });
        });
    </script>
</body>
</html>