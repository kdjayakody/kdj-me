<?php
/**
 * Login Form Template
 *
 * Provides the HTML structure for the user login form.
 * Should be included within a page script (e.g., public/login.php).
 * Assumes security_helpers.php (for csrf_input_field) and functions.php (for base_url)
 * have been included previously.
 *
 * Expects the calling script (e.g., public/login.php) to handle the form submission.
 */

// Ensure CSRF function is available (defensive check)
if (!function_exists('csrf_input_field')) {
     error_log('CRITICAL: csrf_input_field() function not available. Include security_helpers.php.');
     echo '<p>Error: Security token function is missing. Cannot display form.</p>';
     // Optionally die() or return, depending on how includes are handled
     return; // Stop rendering this template part if security function is missing
}
// Ensure base_url function is available
if (!function_exists('base_url')) {
     error_log('CRITICAL: base_url() function not available. Include functions.php.');
     echo '<p>Error: URL function is missing. Cannot display form.</p>';
     return;
}

// Define the target URL for the form submission
// This should typically be the page that includes this form (e.g., login.php itself)
$formActionUrl = base_url('/login.php');

?>
<form action="<?php echo escape_html($formActionUrl); ?>" method="post" class="login-form">

    <?php echo csrf_input_field(); // IMPORTANT: Include CSRF protection token ?>

    <div class="form-group">
        <label for="login-email">Email Address:</label>
        <input type="email" id="login-email" name="email" required>
        </div>

    <div class="form-group">
        <label for="login-password">Password:</label>
        <input type="password" id="login-password" name="password" required>
    </div>

    <div class="form-group form-check">
        <input type="checkbox" id="login-remember-me" name="remember_me" value="1">
        <label for="login-remember-me">Remember Me</label>
    </div>

    <div class="form-group">
        <button type="submit" class="button button-primary">Login</button>
    </div>

    <div class="form-links">
        <p><a href="<?php echo base_url('/forgot_password.php'); ?>">Forgot Password?</a></p>
        <p>Don't have an account? <a href="<?php echo base_url('/register.php'); ?>">Register here</a>.</p>
    </div>

</form>
