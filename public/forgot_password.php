<?php
/**
 * Forgot Password Page Script
 *
 * Displays a form for users to request a password reset email.
 * Interacts with the API to trigger the reset process.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_request_password_reset

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Redirect if already logged in ---
// Logged-in users typically shouldn't need this flow
if (is_user_logged_in()) {
    redirect('/dashboard.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Forgot Password';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'
$emailSubmitted = ''; // To potentially repopulate form on error

// --- Handle Form Submission (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate CSRF Token
    $submittedToken = get_post_data(CSRF_TOKEN_NAME);
    if (!validate_csrf_token($submittedToken)) {
        error_log('CSRF token validation failed for forgot password attempt.');
        $messageType = 'error';
        $messageText = 'Invalid security token. Please try submitting the form again.';
        generate_csrf_token(true); // Regenerate token
    } else {
        // 2. Get User Input
        $email = get_post_data('email');
        $emailSubmitted = $email; // Keep for potential repopulation

        // 3. Server-Side Validation
        $errors = [];
        if (!validate_required($email)) {
            $errors['email'] = 'Email address is required.';
        } elseif (!validate_email($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        // 4. Call API if Validation Passed
        if (empty($errors)) {
            $result = api_request_password_reset($email);

            // 5. Process API Response (Security Consideration!)
            // ALWAYS show a generic success message to prevent email enumeration,
            // unless there was a system/network error contacting the API.
            if ($result['status_code'] === 0) {
                // Network or cURL error - show a generic system error
                $messageType = 'error';
                $messageText = 'Could not process your request due to a system error. Please try again later.';
                error_log("API Forgot Password Failed (System Error): {$result['error_message']}");
            } else {
                // API call completed (successfully or with expected 'user not found' which we hide)
                $messageType = 'success'; // Show success regardless of whether email exists
                $messageText = 'If an account with that email address exists, a password reset link has been sent. Please check your inbox (and spam folder).';
                // Clear submitted email after successful processing
                $emailSubmitted = '';
            }
        } else {
            // Validation failed
            $messageType = 'error';
            $messageText = $errors['email']; // Only one field to validate here
        }
    } // End CSRF valid block
} // End of POST handling


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Forgot Your Password?</h1>

<p>Enter the email address associated with your account, and we'll send you a link to reset your password.</p>

<?php
// Display Messages (from POST handling)
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php // Display the form only if a success message isn't being shown ?>
<?php if ($messageType !== 'success'): ?>
<form action="<?php echo escape_html(base_url('/forgot_password.php')); ?>" method="post" class="forgot-password-form">

    <?php echo csrf_input_field(); // Include CSRF token ?>

    <div class="form-group">
        <label for="forgot-email">Email Address:</label>
        <input type="email" id="forgot-email" name="email" required
               value="<?php echo escape_html($emailSubmitted); // Repopulate on error ?>">
    </div>

    <div class="form-group">
        <button type="submit" class="button button-primary">Send Reset Link</button>
    </div>

    <div class="form-links">
        <p><a href="<?php echo base_url('/login.php'); ?>">Back to Login</a></p>
    </div>

</form>
<?php endif; ?>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
