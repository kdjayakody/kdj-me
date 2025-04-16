<?php
/**
 * Login Page Script
 *
 * Handles displaying the login form and processing user login attempts
 * by interacting with the backend API.
 */

// --- Core Includes ---
// Ensure the path is correct relative to the public directory
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_login_user

// --- Session Start ---
// Attempt to start the session securely. Redirect or die on failure.
if (!secure_session_start()) {
    // Optionally redirect to an error page
    die('Failed to start session. Please contact administrator.');
}

// --- Redirect if already logged in ---
// If the user is already logged in, redirect them to the dashboard.
if (is_user_logged_in()) {
    redirect('/dashboard.php'); // Assumes dashboard.php exists
    exit; // Ensure script stops after redirect
}

// --- Initialize Variables ---
$pageTitle = 'Login';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'

// --- Handle Form Submission (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate CSRF Token
    $submittedToken = get_post_data(CSRF_TOKEN_NAME);
    if (!validate_csrf_token($submittedToken)) {
        // CSRF token mismatch - potential attack or session issue
        error_log('CSRF token validation failed for login attempt.');
        $messageType = 'error';
        $messageText = 'Invalid security token. Please try submitting the form again.';
        // Regenerate a new token for the form redisplay
        generate_csrf_token(true);
    } else {
        // 2. Get User Input
        $email = get_post_data('email');
        $password = get_post_data('password');
        $rememberMe = !empty(get_post_data('remember_me')); // Check if checkbox was checked

        // 3. Basic Input Validation
        if (!validate_required($email) || !validate_required($password)) {
            $messageType = 'error';
            $messageText = 'Both email and password are required.';
        } elseif (!validate_email($email)) {
            $messageType = 'error';
            $messageText = 'Please enter a valid email address.';
        } else {
            // 4. Call API Client for Login
            $result = api_login_user($email, $password, $rememberMe);

            // 5. Process API Response
            if ($result['success']) {
                // API login successful, store auth data in session
                if (isset($result['data']) && login_user($result['data'])) {
                    // Session login successful, redirect to dashboard
                    redirect('/dashboard.php');
                    exit;
                } else {
                    // Failed to store session data (should be rare if session started)
                    error_log('Failed to store user data in session after successful API login.');
                    $messageType = 'error';
                    $messageText = 'Login succeeded but failed to initialize your session. Please try again or contact support.';
                }
            } else {
                // API login failed
                $messageType = 'error';
                // Use the error message from the API if available, otherwise generic
                $messageText = $result['error_message'] ?? 'Invalid email or password.';
                
                // Enhanced logging for debugging
                error_log(sprintf(
                    "Login Failed - Email: %s, Status Code: %d, Error: %s", 
                    $email, 
                    $result['status_code'], 
                    $result['error_message'] ?? 'Unknown error'
                ));
            }
        }
    }
} // End of POST handling

// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Login</h1>

<?php
// Display Login Error/Success Messages (if any were set during POST handling)
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php
// Include Login Form Template
require_once __DIR__ . '/../includes/templates/login_form.php';
?>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>