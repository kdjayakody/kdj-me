<?php
/**
 * Manage MFA Page Script
 *
 * Allows users to view MFA status and enable/disable it.
 * Requires user to be logged in.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for profile and MFA API calls

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Authentication Check ---
if (!is_user_logged_in()) {
    set_flash_message('error', 'Please log in to manage your security settings.');
    redirect('/login.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Manage Multi-Factor Authentication';
$messageText = null; // For direct error messages on GET
$messageType = null;
$mfaIsEnabled = null; // Store status: true, false, or null if error fetching

// --- Handle Disable MFA Action (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the action is indeed 'disable_mfa' (if form includes such a field)
    // For simplicity, we assume any POST to this page is for disabling MFA if MFA is enabled.
    // A hidden input field name="action" value="disable_mfa" is recommended.

    // 1. Validate CSRF Token
    $submittedToken = get_post_data(CSRF_TOKEN_NAME);
    // Regenerate token needed for the form redisplay if validation fails (though we redirect)
    // generate_csrf_token(true); // Generate new one for next page load anyway

    if (!validate_csrf_token($submittedToken)) {
        error_log('CSRF token validation failed for MFA disable attempt.');
        set_flash_message('error', 'Invalid security token. Please try again.');
        redirect('/manage_mfa.php'); // Redirect back
        exit;
    } else {
        // 2. Call API to Disable MFA
        $result = api_disable_mfa();

        // 3. Process API Response
        if ($result['success']) {
            set_flash_message('success', $result['data']['message'] ?? 'Multi-Factor Authentication disabled successfully.');
        } else {
            $errorMessage = $result['error_message'] ?? 'Failed to disable MFA. Please try again.';
            set_flash_message('error', $errorMessage);
            error_log("API Disable MFA Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
        }
        // Redirect back to this page to show updated status and flash message
        redirect('/manage_mfa.php');
        exit;
    }
} // End of POST handling


// --- Fetch Current MFA Status (GET Request or after POST redirect) ---
$profileResult = api_get_user_profile();

if ($profileResult['success'] && isset($profileResult['data'])) {
    // Determine MFA status from the profile data
    $mfaIsEnabled = !empty($profileResult['data']['mfa_enabled']); // Check if true
} else {
    // Failed to fetch profile data, cannot determine MFA status
    $messageType = 'error';
    $messageText = 'Could not load your current MFA status. Please try again later.';
    error_log("API Get Profile Failed (for MFA status): Status {$profileResult['status_code']}, Message: {$profileResult['error_message']}");
    $mfaIsEnabled = null; // Indicate status unknown
}


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1><?php echo escape_html($pageTitle); ?></h1>

<?php
// Display flash messages first (from redirects)
display_flash_messages();

// Display direct error messages (e.g., if profile fetch failed)
if (!empty($messageText)) {
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<div class="mfa-status">
    <?php if ($mfaIsEnabled === true): ?>
        <h2>MFA Status: <span style="color: green;">Enabled</span></h2>
        <p>Multi-Factor Authentication is currently active on your account.</p>
        <p>Remember to keep your backup codes safe. They were shown to you during setup.</p>

        <form action="<?php echo escape_html(base_url('/manage_mfa.php')); ?>" method="post" onsubmit="return confirm('Are you sure you want to disable Multi-Factor Authentication? This will reduce your account security.');">
            <?php echo csrf_input_field(); ?>
            <input type="hidden" name="action" value="disable_mfa"> <?php // Explicit action ?>
            <button type="submit" class="button button-danger">Disable MFA</button>
        </form>

    <?php elseif ($mfaIsEnabled === false): ?>
        <h2>MFA Status: <span style="color: red;">Disabled</span></h2>
        <p>Multi-Factor Authentication is not currently active on your account.</p>
        <p>We strongly recommend enabling MFA to enhance your account security.</p>
        <a href="<?php echo base_url('/setup_mfa.php'); ?>" class="button button-primary">Enable MFA Now</a>

    <?php else: // Error fetching status ?>
        <p>Could not determine the current MFA status for your account.</p>
    <?php endif; ?>
</div>

<hr>
<p><a href="<?php echo base_url('/profile.php'); ?>">Back to Profile</a></p>


<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
