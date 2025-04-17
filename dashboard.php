<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KDJ Auth</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 1.5em;
        }
        .navbar button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .navbar button:hover {
            background-color: #c82333;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .welcome-message {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #333;
        }
        .user-details {
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .user-details h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #555;
        }
        .user-details p {
            margin: 8px 0;
            color: #666;
        }
        .user-details strong {
            color: #333;
            min-width: 120px;
            display: inline-block;
        }
        .loading, .error-message {
            text-align: center;
            padding: 20px;
            font-size: 1.1em;
            color: #666;
        }
         .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>My Dashboard</h1>
    <button id="logoutButton">Logout</button>
</div>

<div class="container">
    <div id="loadingMessage" class="loading">Loading dashboard...</div>
    <div id="errorMessage" class="error-message" style="display: none;"></div>

    <div id="dashboardContent" style="display: none;">
        <div class="welcome-message">
            Welcome back, <strong id="displayName">User</strong>!
        </div>
        <div class="user-details">
            <h3>Your Profile</h3>
            <p><strong>User ID:</strong> <span id="userId"></span></p>
            <p><strong>Email:</strong> <span id="userEmail"></span></p>
            <p><strong>Phone Number:</strong> <span id="userPhone"></span></p>
            <p><strong>MFA Enabled:</strong> <span id="userMfa"></span></p>
            <p><strong>Roles:</strong> <span id="userRoles"></span></p>
            </div>
        </div>
</div>

<script>
    const loadingMessage = document.getElementById('loadingMessage');
    const errorMessage = document.getElementById('errorMessage');
    const dashboardContent = document.getElementById('dashboardContent');
    const logoutButton = document.getElementById('logoutButton');

    // Elements to display user data
    const displayNameEl = document.getElementById('displayName');
    const userIdEl = document.getElementById('userId');
    const userEmailEl = document.getElementById('userEmail');
    const userPhoneEl = document.getElementById('userPhone');
    const userMfaEl = document.getElementById('userMfa');
    const userRolesEl = document.getElementById('userRoles');

    // Define the base URL for your API
    const API_BASE_URL = 'https://auth.kdj.lk'; // Use HTTPS

    // --- Authentication Check and Data Fetch ---
    window.addEventListener('DOMContentLoaded', async () => {
        const accessToken = localStorage.getItem('accessToken');

        if (!accessToken) {
            // No token found, redirect to login
            window.location.href = 'login.php'; // Adjust if your login page has a different name
            return;
        }

        try {
            const response = await fetch(`${API_BASE_URL}/api/v1/users/me`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${accessToken}`,
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                const userData = await response.json();
                displayUserData(userData);
                loadingMessage.style.display = 'none';
                dashboardContent.style.display = 'block';
            } else if (response.status === 401) {
                // Unauthorized - Token likely expired or invalid
                handleLogout('Session expired. Please login again.');
            } else {
                // Other errors
                const errorData = await response.json();
                showError(`Failed to load dashboard: ${errorData.detail || response.statusText}`);
            }

        } catch (error) {
            console.error('Dashboard Load Error:', error);
            showError('Could not connect to the server to load dashboard data.');
        }
    });

    // --- Display User Data ---
    function displayUserData(data) {
        displayNameEl.textContent = data.display_name || data.email; // Fallback to email if no display name
        userIdEl.textContent = data.uid || 'N/A';
        userEmailEl.textContent = data.email || 'N/A';
        userPhoneEl.textContent = data.phone_number || 'Not Provided';
        userMfaEl.textContent = data.mfa_enabled ? 'Yes' : 'No';
        userRolesEl.textContent = data.roles && data.roles.length > 0 ? data.roles.join(', ') : 'User';
    }

    // --- Logout Functionality ---
    logoutButton.addEventListener('click', async () => {
        await handleLogout();
    });

    async function handleLogout(logoutMessage = 'You have been logged out.') {
        const accessToken = localStorage.getItem('accessToken');

        // Clear local storage regardless of API call success
        localStorage.removeItem('accessToken');
        localStorage.removeItem('refreshToken');
        localStorage.removeItem('userId');

        if (accessToken) {
            try {
                // Attempt to inform the backend about the logout
                await fetch(`${API_BASE_URL}/api/v1/auth/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${accessToken}`,
                        'Accept': 'application/json',
                    }
                });
                // We don't strictly need to wait for the response or check success,
                // as the client-side session is already cleared.
                console.log("Logout API call initiated.");
            } catch (error) {
                console.error("Error calling logout API:", error);
                // Log the error but proceed with redirect
            }
        }

        // Redirect to login page
        alert(logoutMessage); // Simple alert, replace with a nicer notification if desired
        window.location.href = 'login.php'; // Adjust if needed
    }

    // --- Error Handling ---
    function showError(message) {
        loadingMessage.style.display = 'none';
        dashboardContent.style.display = 'none';
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
    }

</script>

</body>
</html>