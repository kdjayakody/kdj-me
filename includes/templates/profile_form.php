<?php
/**
 * Profile Update Form Template
 *
 * Provides the HTML structure for the user profile update form.
 * Should be included within a page script (e.g., public/profile.php).
 * Assumes security_helpers.php, functions.php are included previously.
 *
 * Expects the calling script (e.g., public/profile.php) to:
 * 1. Fetch the current user's data (e.g., into a $currentUserData array).
 * 2. Handle the form submission.
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

// Check if $currentUserData is set by the calling script
if (!isset($currentUserData) || !is_array($currentUserData)) {
    error_log('Error: $currentUserData variable not set or not an array before including profile_form.php.');
    echo '<p>Error: Could not load user data for the form.</p>';
    // Attempt to get email from session as a fallback for display, but form fields will be empty
    $currentUserData = ['email' => get_auth_user_data('email') ?: 'N/A'];
    // It's better to handle the data fetching error in the calling script (profile.php)
    // and potentially not include this form template at all if data is missing.
}

// Define the target URL for the form submission
$formActionUrl = base_url('/profile.php');

// Phone number format hint (E.164) - consistent with register form
$phonePattern = '\+\d{1,15}'; // Starts with +, followed by 1 to 15 digits
$phoneTitle = "Phone number must be in E.164 format (e.g., +94711234567).";

?>
<form action="<?php echo escape_html($formActionUrl); ?>" method="post" class="profile-form">

    <?php echo csrf_input_field(); // IMPORTANT: Include CSRF protection token ?>

    <div class="form-group">
        <label for="profile-email">Email Address:</label>
        <input type="email" id="profile-email" name="email_display"
               value="<?php echo escape_html($currentUserData['email'] ?? ''); ?>" readonly disabled>
        <small>Email address cannot be changed via this form.</small>
        <?php if (isset($currentUserData['email_verified']) && !$currentUserData['email_verified']): ?>
            <small style="color: orange; display: block; margin-top: 5px;">Your email is not verified.</small>
            <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="profile-display-name">Display Name:</label>
        <input type="text" id="profile-display-name" name="display_name"
               value="<?php echo escape_html($currentUserData['display_name'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="profile-phone">Phone Number:</label>
        <input type="tel" id="profile-phone" name="phone_number"
               value="<?php echo escape_html($currentUserData['phone_number'] ?? ''); ?>"
               placeholder="+94711234567"
               pattern="<?php echo escape_html($phonePattern); ?>"
               title="<?php echo escape_html($phoneTitle); ?>">
         <small>Format: + followed by country code and number (e.g., +9471xxxxxxx)</small>
    </div>

    <div class="form-group">
        <button type="submit" class="button button-primary">Update Profile</button>
    </div>

    <hr>

    <div class="profile-links">
         <p><a href="<?php echo base_url('/change_password.php'); ?>">Change Password</a></p>
         <p><a href="<?php echo base_url('/manage_mfa.php'); ?>">Manage Multi-Factor Authentication</a></p>
         </div>

</form>
