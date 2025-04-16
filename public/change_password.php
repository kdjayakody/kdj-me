<?php
/**
 * Change Password Page Script
 *
 * Handles displaying the form and processing requests to change the user's password.
 * Requires user to be logged in.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_update_password

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Authentication Check ---
if (!is_user_logged_in()) {
    set_flash_message('error', 'Please log in to change your password.');
    redirect('/login.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Change Password';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'
$errors = []; // Array to hold validation errors

// --- Handle Form Submission (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate CSRF Token
    $submittedToken = get_post_data(CSRF_TOKEN_NAME);
    if (!validate_csrf_token($submittedToken)) {
        error_log('CSRF token validation failed for password change attempt.');
        $messageType = 'error';
        $messageText = 'Invalid security token. Please try submitting the form again.';
        generate_csrf_token(true); // Regenerate token
    } else {
        // 2. Get User Input
        $current_password = get_post_data('current_password');
        $new_password = get_post_data('new_password');
        $confirm_new_password = get_post_data('confirm_new_password');

        // 3. Server-Side Validation
        // Required fields
        if (!validate_required($current_password)) {
            $errors['current_password'] = 'Current password is required.';
        }
        if (!validate_required($new_password)) {
            $errors['new_password'] = 'New password is required.';
        }
        if (!validate_required($confirm_new_password)) {
            $errors['confirm_new_password'] = 'Please confirm your new password.';
        }

        // New password complexity (only if required fields are present)
        if (empty($errors['new_password'])) {
            $passwordMinLength = 12; // Should match config/hints
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{' . $passwordMinLength . ',}$/';
            if (!validate_min_length($new_password, $passwordMinLength)) {
                 $errors['new_password'] = "New password must be at least {$passwordMinLength} characters long.";
            } elseif (!preg_match($passwordPattern, $new_password)) {
                 $errors['new_password'] = 'New password must include uppercase, lowercase, a digit, and a special character.';
            } elseif ($new_password === $current_password) {
                 $errors['new_password'] = 'New password cannot be the same as the current password.';
            } elseif (empty($errors['confirm_new_password']) && $new_password !== $confirm_new_password) {
                 $errors['confirm_new_password'] = 'New passwords do not match.';
            }
        }

        // 4. Call API if Validation Passed
        if (empty($errors)) {
            $result = api_update_password($current_password, $new_password);

            // 5. Process API Response
            if ($result['success']) {
                // Password updated successfully via API
                set_flash_message('success', 'Your password has been updated successfully.');
                // Redirect to profile or dashboard after success
                redirect('/profile.php'); // Or '/dashboard.php'
                exit;
            } else {
                // API update failed
                $messageType = 'error';
                // Use the error message from the API if available (e.g., incorrect current password)
                $messageText = $result['error_message'] ?? 'Failed to update password. Please ensure your current password is correct and try again.';
                error_log("API Password Update Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
            }
        } else {
            // Validation failed
            $messageType = 'error';
            // Combine errors into a single message
            $messageText = 'Please correct the errors below: <ul>';
            foreach ($errors as $field => $error) {
                 $messageText .= '<li>' . escape_html($error) . '</li>';
            }
             $messageText .= '</ul>';
        }
    } // End CSRF valid block
} // End of POST handling


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Change Your Password</h1>

<?php
// Display Messages (from POST handling)
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php
// Include Password Change Form Template
require_once __DIR__ . '/../includes/templates/password_change_form.php';
?>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
