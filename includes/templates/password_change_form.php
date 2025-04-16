<?php
/**
 * Password Change Form Template
 *
 * Provides the HTML structure for the user to change their password.
 * Should be included within a page script (e.g., public/change_password.php).
 * Assumes security_helpers.php, functions.php are included previously.
 *
 * Expects the calling script (e.g., public/change_password.php) to handle the form submission.
 */

// Ensure CSRF function is available (defensive check)
if (!function_exists('csrf_input_field')) {
     error_log('CRITICAL: csrf_input_field() function not available. Include security_helpers.php.');
     echo '<p>Error: Security token function is missing. Cannot display form.</p>';
     return; // Stop rendering this template part
}
// Ensure base_url function is available
if (!function_exists('base_url')) {
     error_log('CRITICAL: base_url() function not available. Include functions.php.');
     echo '<p>Error: URL function is missing. Cannot display form.</p>';
     return;
}
// Ensure escape_html function is available
if (!function_exists('escape_html')) {
     error_log('CRITICAL: escape_html() function not available. Include functions.php.');
     echo '<p>Error: HTML escaping function is missing. Cannot display form.</p>';
     return;
}

// Define the target URL for the form submission
$formActionUrl = base_url('/change_password.php');

// Password policy hints (based on kdj-auth config) - For client-side validation/UX
// These MUST be validated server-side as well.
$passwordMinLength = 12; // From settings.MIN_PASSWORD_LENGTH
// Regex combines requirements: uppercase, lowercase, digit, special char, min length
$passwordPattern = '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{' . $passwordMinLength . ',}';
$passwordTitle = "New password must be at least {$passwordMinLength} characters long and include uppercase, lowercase, a digit, and a special character (!@#$%^&*(),.?\":{}|<>).";

?>
<form action="<?php echo escape_html($formActionUrl); ?>" method="post" class="password-change-form">

    <?php echo csrf_input_field(); // IMPORTANT: Include CSRF protection token ?>

    <div class="form-group">
        <label for="current-password">Current Password:</label>
        <input type="password" id="current-password" name="current_password" required>
    </div>

    <hr> <div class="form-group">
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="new_password" required
               minlength="<?php echo $passwordMinLength; ?>"
               pattern="<?php echo escape_html($passwordPattern); ?>"
               title="<?php echo escape_html($passwordTitle); ?>">
         <small><?php echo escape_html($passwordTitle); ?></small>
    </div>

    <div class="form-group">
        <label for="confirm-new-password">Confirm New Password:</label>
        <input type="password" id="confirm-new-password" name="confirm_new_password" required
               minlength="<?php echo $passwordMinLength; ?>">
        </div>


    <div class="form-group">
        <button type="submit" class="button button-primary">Change Password</button>
    </div>

</form>
