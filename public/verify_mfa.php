<?php
/**
 * Verify MFA Code Script
 *
 * Handles the submission of the MFA verification code during setup.
 * Validates the code via the API and redirects accordingly.
 * Requires user to be logged in.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_verify_mfa

// --- Session Start ---
if (!secure_session_start()) {
    // Cannot proceed without session, especially for flash messages
    error_log('Failed to start session in verify_mfa.php.');
    // Redirect to a generic error page or login with query param
    header('Location: login.php?error=session_failure');
    exit;
}

// --- Authentication Check ---
if (!is_user_logged_in()) {
    // Should not happen if they reached setup, but check anyway
    set_flash_message('error', 'Please log in again to verify MFA.');
    redirect('/login.php');
    exit;
}

// --- Check Request Method ---
// This script should only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect GET requests away, perhaps to the setup page or dashboard
    set_flash_message('error', 'Invalid request method.');
    redirect('/setup_mfa.php'); // Redirect back to setup page
    exit;
}

// --- Process POST Request ---

// Define redirect targets
$successRedirect = '/manage_mfa.php'; // Or '/dashboard.php' - where to go after successful verification
$failureRedirect = '/setup_mfa.php'; // Where to redirect if verification fails

// 1. Validate CSRF Token
$submittedToken = get_post_data(CSRF_TOKEN_NAME);
if (!validate_csrf_token($submittedToken)) {
    error_log('CSRF token validation failed for MFA verification attempt.');
    set_flash_message('error', 'Invalid security token. Please try submitting the code again.');
    redirect($failureRedirect);
    exit;
}

// 2. Get User Input
$mfa_code = get_post_data('mfa_code');

// 3. Server-Side Validation
$errors = [];
if (!validate_required($mfa_code)) {
    $errors['mfa_code'] = 'MFA code is required.';
} elseif (!preg_match('/^\d{6}$/', $mfa_code)) { // Basic check for 6 digits
    $errors['mfa_code'] = 'MFA code must be 6 digits.';
}

// 4. Call API if Validation Passed
if (empty($errors)) {
    // Assuming this is TOTP verification during setup
    $result = api_verify_mfa($mfa_code, 'totp');

    // 5. Process API Response
    if ($result['success']) {
        // MFA code verified successfully via API
        // The API might have updated the user's custom claims to mark MFA as fully enabled.
        $successMessage = $result['data']['message'] ?? 'Multi-Factor Authentication has been successfully enabled!';
        set_flash_message('success', $successMessage);
        redirect($successRedirect);
        exit;
    } else {
        // API verification failed (e.g., incorrect code)
        $errorMessage = $result['error_message'] ?? 'Invalid MFA code. Please check your authenticator app and try again.';
        set_flash_message('error', $errorMessage);
        error_log("API MFA Verification Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
        redirect($failureRedirect);
        exit;
    }
} else {
    // Validation failed (e.g., code not 6 digits)
    // Combine errors into a single flash message
    $errorMessages = implode(' ', $errors);
    set_flash_message('error', 'Please correct the following: ' . escape_html($errorMessages));
    redirect($failureRedirect);
    exit;
}

?>
