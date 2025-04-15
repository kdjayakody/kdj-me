<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - kdj.lk</title>
    <style>
         /* Add styles for dashboard layout, profile info, forms, buttons */
        body { font-family: sans-serif; padding: 20px; background-color: #f8f9fa; }
        .container { max-width: 800px; margin: auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .profile-info p { margin: 5px 0; color: #555; }
        .profile-info strong { color: #333; min-width: 120px; display: inline-block;}
        .section { margin-top: 30px; }
        button { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-right: 10px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-secondary:hover { background-color: #5a6268; }
         .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
         .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input[type="text"],
        .form-group input[type="tel"],
        .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; max-width: 400px;}
         #message { margin-top: 15px; padding: 10px; border-radius: 4px; text-align: center; font-size: 14px; display: none; }
        .message-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
             <h2>Dashboard</h2>
             <button id="logoutButton" class="btn-secondary">Logout</button>
        </div>

        <div id="loadingMessage">Loading user data...</div>
        <div id="profileSection" style="display: none;">

             <div class="section profile-info">
                 <h3>User Profile</h3>
                 <p><strong>UID:</strong> <span id="profileUid"></span></p>
                 <p><strong>Email:</strong> <span id="profileEmail"></span></p>
                 <p><strong>Display Name:</strong> <span id="profileDisplayName"></span></p>
                 <p><strong>Phone Number:</strong> <span id="profilePhoneNumber"></span></p>
                 <p><strong>Email Verified:</strong> <span id="profileEmailVerified"></span></p>
                 <p><strong>MFA Enabled:</strong> <span id="profileMfaEnabled"></span></p>
                 <p><strong>Roles:</strong> <span id="profileRoles"></span></p>
            </div>

             <div class="section">
                 <h3>Update Profile</h3>
                 <form id="updateProfileForm">
                     <div class="form-group">
                         <label for="updateDisplayName">Display Name:</label>
                         <input type="text" id="updateDisplayName" name="display_name">
                    </div>
                     <div class="form-group">
                        <label for="updatePhoneNumber">Phone Number (E.164):</label>
                         <input type="tel" id="updatePhoneNumber" name="phone_number" placeholder="+947XXXXXXXX">
                    </div>
                     <button type="submit" class="btn-primary">Save Profile</button>
                </form>
                 <div id="profileUpdateMessage" class="message" style="display: none;"></div>
            </div>

            <div class="section">
                <h3>Change Password</h3>
                <form id="updatePasswordForm">
                    <div class="form-group">
                         <label for="currentPassword">Current Password:</label>
                         <input type="password" id="currentPassword" name="current_password" required>
                    </div>
                    <div class="form-group">
                         <label for="newPassword">New Password:</label>
                         <input type="password" id="newPassword" name="new_password" required>
                    </div>
                     <div class="form-group">
                         <label for="confirmNewPassword">Confirm New Password:</label>
                        <input type="password" id="confirmNewPassword" required>
                    </div>
                     <button type="submit" class="btn-primary">Update Password</button>
                </form>
                 <div id="passwordUpdateMessage" class="message" style="display: none;"></div>
            </div>

             <div class="section">
                <h3>Delete Account</h3>
                <p style="color: red;">Warning: This action cannot be undone.</p>
                <button id="deleteAccountButton" class="btn-danger">Delete My Account</button>
                 <div id="deleteAccountMessage" class="message" style="display: none;"></div>
            </div>

        </div> <div id="errorMessage" class="message message-error" style="display: none;"></div>

    </div><script>
        // --- Configuration ---
        const apiBaseUrl = 'https://auth.kdj.lk/api/v1'; // Change if needed
        const loginPageUrl = 'login.php'; // URL to redirect if not logged in
        // --------------------

        const loadingMessage = document.getElementById('loadingMessage');
        const profileSection = document.getElementById('profileSection');
        const errorMessage = document.getElementById('errorMessage');
        const logoutButton = document.getElementById('logoutButton');
        const updateProfileForm = document.getElementById('updateProfileForm');
        const profileUpdateMessage = document.getElementById('profileUpdateMessage');
        const updatePasswordForm = document.getElementById('updatePasswordForm');
        const passwordUpdateMessage = document.getElementById('passwordUpdateMessage');
        const deleteAccountButton = document.getElementById('deleteAccountButton');
         const deleteAccountMessage = document.getElementById('deleteAccountMessage');


        // --- Helper function for API calls ---
        async function fetchApi(endpoint, options = {}) {
            const defaultHeaders = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
             // We might need to add Authorization header if using tokens stored in localStorage
             // const token = localStorage.getItem('accessToken');
             // if (token) {
             //     defaultHeaders['Authorization'] = `Bearer ${token}`;
             // }

            const config = {
                ...options,
                headers: {
                    ...defaultHeaders,
                    ...(options.headers || {}),
                },
                 credentials: 'include' // Crucial for sending/receiving cookies
            };

            try {
                const response = await fetch(`${apiBaseUrl}${endpoint}`, config);
                if (response.status === 401) { // Unauthorized
                    // Redirect to login if any API call fails with 401
                     window.location.href = loginPageUrl;
                    throw new Error('Unauthorized'); // Stop further processing
                }
                return response;
            } catch (error) {
                console.error(`API call to ${endpoint} failed:`, error);
                 errorMessage.textContent = `Request failed: ${error.message}. Please check connection or login again.`;
                 errorMessage.style.display = 'block';
                 loadingMessage.style.display = 'none'; // Hide loading message on error
                 profileSection.style.display = 'none'; // Hide profile section on error
                throw error; // Re-throw to stop execution in calling function
            }
        }

         // --- Display Message Utility ---
         function showMessage(element, text, isSuccess = true) {
             element.textContent = text;
             element.className = isSuccess ? 'message message-success' : 'message message-error';
             element.style.display = 'block';
             // Hide message after 5 seconds
             setTimeout(() => { element.style.display = 'none'; }, 5000);
         }

        // --- Load User Data ---
        async function loadUserProfile() {
            try {
                const response = await fetchApi('/users/me'); // GET request by default
                if (!response.ok) {
                    throw new Error(`Failed to fetch profile: ${response.status}`);
                }
                const user = await response.json();

                document.getElementById('profileUid').textContent = user.uid;
                document.getElementById('profileEmail').textContent = user.email;
                document.getElementById('profileDisplayName').textContent = user.display_name || '-';
                document.getElementById('profilePhoneNumber').textContent = user.phone_number || '-';
                document.getElementById('profileEmailVerified').textContent = user.email_verified ? 'Yes' : 'No';
                document.getElementById('profileMfaEnabled').textContent = user.mfa_enabled ? 'Yes' : 'No';
                document.getElementById('profileRoles').textContent = user.roles.join(', ') || 'None';

                // Pre-fill update form
                document.getElementById('updateDisplayName').value = user.display_name || '';
                document.getElementById('updatePhoneNumber').value = user.phone_number || '';

                loadingMessage.style.display = 'none';
                 errorMessage.style.display = 'none';
                profileSection.style.display = 'block';

                 // Handle MFA display based on user.mfa_enabled
                 // (Add logic here if implementing MFA section)

            } catch (error) {
                // Error handling is done in fetchApi, but we catch here to prevent breaking execution
                console.error("Could not load user profile.", error);
                 // Ensure loading is hidden and profile is not shown
                loadingMessage.style.display = 'none';
                profileSection.style.display = 'none';
                 if (error.message !== 'Unauthorized') { // Avoid duplicate message if redirected
                    errorMessage.textContent = 'Failed to load user profile. You might need to log in again.';
                    errorMessage.style.display = 'block';
                 }
            }
        }

        // --- Logout ---
        logoutButton.addEventListener('click', async () => {
             if (!confirm('Are you sure you want to logout?')) return;
            try {
                 // Backend might expect token in body or rely solely on cookie
                 // Check your /auth/logout implementation. Assuming cookie is sufficient.
                const response = await fetchApi('/auth/logout', { method: 'POST' });
                if (response.ok) {
                     // Clear any local storage if used
                     // localStorage.removeItem('accessToken');
                    window.location.href = loginPageUrl; // Redirect to login
                } else {
                    const errorData = await response.json().catch(() => ({ detail: 'Logout failed.' }));
                    showMessage(errorMessage, `Logout failed: ${errorData.detail || response.statusText}`, false);
                }
            } catch (error) {
                // Error already handled by fetchApi, but maybe show a specific logout error message
                 showMessage(errorMessage, 'Logout request failed. Please try again.', false);
            }
        });

         // --- Update Profile ---
         updateProfileForm.addEventListener('submit', async (event) => {
             event.preventDefault();
             const displayName = document.getElementById('updateDisplayName').value;
             const phoneNumber = document.getElementById('updatePhoneNumber').value || null; // Send null if empty

            try {
                const response = await fetchApi('/users/me', {
                    method: 'PUT',
                    body: JSON.stringify({ display_name: displayName, phone_number: phoneNumber })
                });
                const responseData = await response.json();
                if (response.ok) {
                     showMessage(profileUpdateMessage, 'Profile updated successfully!', true);
                     // Update displayed info immediately
                    document.getElementById('profileDisplayName').textContent = responseData.display_name || '-';
                    document.getElementById('profilePhoneNumber').textContent = responseData.phone_number || '-';
                } else {
                     showMessage(profileUpdateMessage, `Profile update failed: ${responseData.detail || response.statusText}`, false);
                 }
             } catch (error) {
                 showMessage(profileUpdateMessage, 'Profile update request failed.', false);
             }
         });

         // --- Update Password ---
        updatePasswordForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

             if (newPassword !== confirmNewPassword) {
                 showMessage(passwordUpdateMessage, 'New passwords do not match!', false);
                return;
            }

            try {
                 // IMPORTANT: Backend requires current_password, new_password
                const response = await fetchApi('/users/me/password', {
                    method: 'PUT',
                    body: JSON.stringify({
                        current_password: currentPassword, // Make sure backend expects this field name
                        new_password: newPassword
                    })
                });
                 const responseData = await response.json();
                 if (response.ok) {
                     showMessage(passwordUpdateMessage, responseData.message || 'Password updated successfully!', true);
                    updatePasswordForm.reset(); // Clear form
                } else {
                     // Check for specific validation errors if backend provides them
                     let detail = responseData.detail || response.statusText;
                     if (response.status === 400 && typeof responseData.detail === 'string') {
                        detail = responseData.detail; // Use detailed message from backend if available (e.g., password strength)
                     }
                     showMessage(passwordUpdateMessage, `Password update failed: ${detail}`, false);
                 }
             } catch (error) {
                 showMessage(passwordUpdateMessage, 'Password update request failed.', false);
             }
         });

         // --- Delete Account ---
         deleteAccountButton.addEventListener('click', async () => {
             if (!confirm('Are you absolutely sure you want to delete your account? This cannot be undone.')) return;
            if (!confirm('Second confirmation: Really delete your account forever?')) return;

            try {
                const response = await fetchApi('/users/me', { method: 'DELETE' });
                 const responseData = await response.json();
                 if (response.ok) {
                     showMessage(deleteAccountMessage, responseData.message || 'Account deleted successfully.', true);
                     // Clear any local storage
                     // localStorage.removeItem('accessToken');
                     // Redirect to login or a "goodbye" page after a delay
                     setTimeout(() => { window.location.href = loginPageUrl; }, 3000);
                 } else {
                    showMessage(deleteAccountMessage, `Account deletion failed: ${responseData.detail || response.statusText}`, false);
                 }
            } catch (error) {
                 showMessage(deleteAccountMessage, 'Account deletion request failed.', false);
             }
         });


        // --- Initial Load ---
        loadUserProfile();

    </script>
</body>
</html>