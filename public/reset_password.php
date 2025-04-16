<?php
/**
 * Reset Password Page Script
 *
 * Handles the form where users set a new password using a token
 * received via email.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_confirm_password_reset

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Redirect if already logged in ---
// Logged-in users shouldn't typically be resetting passwords this way
if (is_user_logged_in()) {
    redirect('/dashboard.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Reset Password';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'
$errors = []; // Array to hold validation errors
$showForm = false; // Flag to control form display
$resetToken = null; // Store the token from GET request

// --- Get Reset Token from URL (GET Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $resetToken = get_query_data('token');
    if (!validate_required($resetToken)) {
        $messageType = 'error';
        $messageText = 'Invalid or missing password reset token. Please request a new reset link if needed.';
        // Do not show the form if token is missing
    } else {
        // Token found in URL, allow form display
        $showForm = true;
    }
}

// --- Handle Form Submission (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate CSRF Token
    $submittedCsrfToken = get_post_data(CSRF_TOKEN_NAME);
    if (!validate_csrf_token($submittedCsrfToken)) {
        error_log('CSRF token validation failed for password reset attempt.');
        $messageType = 'error';
        $messageText = 'Invalid security token. Please try submitting the form again.';
        generate_csrf_token(true); // Regenerate token
        $showForm = false; // Don't show form on CSRF fail
    } else {
        // 2. Get User Input (including token from hidden field)
        $resetToken = get_post_data('reset_token'); // Get token submitted with the form
        $new_password = get_post_data('new_password');
        $confirm_new_password = get_post_data('confirm_new_password');

        // 3. Server-Side Validation
        // Token from form
        if (!validate_required($resetToken)) {
             $errors['token'] = 'Reset token is missing. Please use the link provided in the email.';
             $showForm = false; // Don't show form if token missing from POST
        }

        // New password
        if (!validate_required($new_password)) {
            $errors['new_password'] = 'New password is required.';
        } else {
            // Check complexity (matches hints in register_form.php)
            $passwordMinLength = 12; // Should match config/hints
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{' . $passwordMinLength . ',}$/';
            if (!validate_min_length($new_password, $passwordMinLength)) {
                 $errors['new_password'] = "New password must be at least {$passwordMinLength} characters long.";
            } elseif (!preg_match($passwordPattern, $new_password)) {
                 $errors['new_password'] = 'New password must include uppercase, lowercase, a digit, and a special character.';
            } elseif (!validate_required($confirm_new_password)) {
                 $errors['confirm_new_password'] = 'Please confirm your new password.';
            } elseif ($new_password !== $confirm_new_password) {
                 $errors['confirm_new_password'] = 'New passwords do not match.';
            }
        }

        // 4. Call API if Validation Passed
        if (empty($errors)) {
            // Note: Your backend API route for confirm currently returns a placeholder.
            // This frontend code assumes it will eventually return success/failure correctly.
            $result = api_confirm_password_reset($resetToken, $new_password);

            // 5. Process API Response
            if ($result['success']) {
                // Password reset successful via API
                set_flash_message('success', 'Your password has been reset successfully. Please log in with your new password.');
                redirect('/login.php');
                exit;
            } else {
                // API reset failed (e.g., invalid/expired token)
                $messageType = 'error';
                $messageText = $result['error_message'] ?? 'Failed to reset password. The reset link may be invalid or expired.';
                error_log("API Password Reset Confirm Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
                $showForm = false; // Hide form after invalid token error
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
             $showForm = true; // Keep form visible on validation errors
        }
    } // End CSRF valid block
} // End of POST handling


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Reset Your Password</h1>

<?php
// Display Messages (from GET token check or POST handling)
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php // Display the form only if token was valid on GET or POST validation failed ?>
<?php if ($showForm): ?>
<form action="<?php echo escape_html(base_url('/reset_password.php?token=' . urlencode($resetToken))); // Keep token in action URL for clarity, though hidden field is primary ?>" method="post" class="reset-password-form">

    <?php echo csrf_input_field(); // Include CSRF token ?>

    <?php // Hidden field to pass the token during POST ?>
    <input type="hidden" name="reset_token" value="<?php echo escape_html($resetToken); ?>">

    <?php
        // Define password policy hints again for the form template part
        $passwordMinLength = 12;
        $passwordPattern = '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{' . $passwordMinLength . ',}';
        $passwordTitle = "New password must be at least {$passwordMinLength} characters long and include uppercase, lowercase, a digit, and a special character (!@#$%^&*(),.?\":{}|<>).";
    ?>

    <div class="form-group">
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="new_password" required
               minlength="<?php echo $passwordMinLength; ?>"
               pattern="<?php echo escape_html($passwordPattern); ?>"
               title="<?php echo escape_html($passwordTitle); ?>">
         <small><?php echo escape_html($passwordTitle); ?></small>
         <?php if (isset($errors['new_password'])) echo '<span class="error-text">' . escape_html($errors['new_password']) . '</span>'; ?>
    </div>

    <div class="form-group">
        <label for="confirm-new-password">Confirm New Password:</label>
        <input type="password" id="confirm-new-password" name="confirm_new_password" required
               minlength="<?php echo $passwordMinLength; ?>">
         <?php if (isset($errors['confirm_new_password'])) echo '<span class="error-text">' . escape_html($errors['confirm_new_password']) . '</span>'; ?>
    </div>


    <div class="form-group">
        <button type="submit" class="button button-primary">Reset Password</button>
    </div>

</form>
<?php elseif ($messageType !== 'success'): // Show link back to forgot password if form isn't shown and it wasn't a success message ?>
    <p><a href="<?php echo base_url('/forgot_password.php'); ?>">Request a new password reset link.</a></p>
<?php endif; ?>


<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
