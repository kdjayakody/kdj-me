<?php
/**
 * Registration Form Template
 *
 * Provides the HTML structure for the user registration form.
 * Should be included within a page script (e.g., public/register.php).
 * Assumes security_helpers.php (for csrf_input_field) and functions.php (for base_url)
 * have been included previously.
 *
 * Expects the calling script (e.g., public/register.php) to handle the form submission.
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

// Define the target URL for the form submission
$formActionUrl = base_url('/register.php');

// Password policy hints (based on kdj-auth config) - For client-side validation/UX
// These MUST be validated server-side as well.
$passwordMinLength = 12; // From settings.MIN_PASSWORD_LENGTH
// Regex combines requirements: uppercase, lowercase, digit, special char, min length
$passwordPattern = '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{' . $passwordMinLength . ',}';
$passwordTitle = "Password must be at least {$passwordMinLength} characters long and include uppercase, lowercase, a digit, and a special character (!@#$%^&*(),.?\":{}|<>).";

// Phone number format hint (E.164)
$phonePattern = '\+\d{1,15}'; // Starts with +, followed by 1 to 15 digits
$phoneTitle = "Phone number must be in E.164 format (e.g., +94711234567).";

?>
<form action="<?php echo escape_html($formActionUrl); ?>" method="post" class="register-form">

    <?php echo csrf_input_field(); // IMPORTANT: Include CSRF protection token ?>

    <div class="form-group">
        <label for="register-display-name">Display Name (Optional):</label>
        <input type="text" id="register-display-name" name="display_name">
    </div>

    <div class="form-group">
        <label for="register-email">Email Address:</label>
        <input type="email" id="register-email" name="email" required>
    </div>

    <div class="form-group">
        <label for="register-phone">Phone Number (Optional):</label>
        <input type="tel" id="register-phone" name="phone_number"
               placeholder="+94711234567"
               pattern="<?php echo escape_html($phonePattern); ?>"
               title="<?php echo escape_html($phoneTitle); ?>">
         <small>Format: + followed by country code and number (e.g., +9471xxxxxxx)</small>
    </div>

    <div class="form-group">
        <label for="register-password">Password:</label>
        <input type="password" id="register-password" name="password" required
               minlength="<?php echo $passwordMinLength; ?>"
               pattern="<?php echo escape_html($passwordPattern); ?>"
               title="<?php echo escape_html($passwordTitle); ?>">
         <small><?php echo escape_html($passwordTitle); ?></small>
    </div>

    <div class="form-group">
        <label for="register-confirm-password">Confirm Password:</label>
        <input type="password" id="register-confirm-password" name="confirm_password" required
               minlength="<?php echo $passwordMinLength; ?>">
        </div>


    <div class="form-group">
        <button type="submit" class="button button-primary">Register</button>
    </div>

    <div class="form-links">
        <p>Already have an account? <a href="<?php echo base_url('/login.php'); ?>">Login here</a>.</p>
    </div>

</form>
