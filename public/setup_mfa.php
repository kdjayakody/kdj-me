<?php
/**
 * Setup MFA Page Script
 *
 * Fetches MFA setup data (QR code, secret, backup codes) from the API
 * and displays it to the user using a template.
 * Requires user to be logged in.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php'; // Might need later if adding forms here
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_setup_mfa

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Authentication Check ---
if (!is_user_logged_in()) {
    set_flash_message('error', 'Please log in to set up multi-factor authentication.');
    redirect('/login.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Setup Multi-Factor Authentication';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'
$mfaSetupData = null; // Will hold data from API

// --- Call API to get MFA Setup Data ---
$result = api_setup_mfa();

if ($result['success'] && isset($result['data'])) {
    // API call successful, store data needed for the template
    $mfaSetupData = $result['data'];
    // Check if expected keys exist (defensive coding)
    if (!isset($mfaSetupData['qr_code_url']) || !isset($mfaSetupData['secret']) || !isset($mfaSetupData['backup_codes'])) {
        error_log("API Setup MFA response missing required keys.");
        $messageType = 'error';
        $messageText = 'Received incomplete setup data from the server. Please try again later.';
        $mfaSetupData = null; // Invalidate data
    }
} else {
    // API call failed
    $messageType = 'error';
    $messageText = $result['error_message'] ?? 'Could not retrieve MFA setup information at this time. Please try again later.';
    error_log("API Setup MFA Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
    $mfaSetupData = null;
}


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1><?php echo escape_html($pageTitle); ?></h1>

<?php
// Display Messages (e.g., if API call failed)
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php
// Display MFA Setup Info ONLY if data was loaded successfully
if ($mfaSetupData !== null):
    // The mfa_setup_display.php template expects $mfaSetupData variable
    require_once __DIR__ . '/../includes/templates/mfa_setup_display.php';
else:
    // Show an alternative message if setup data couldn't be loaded
    echo '<p>MFA setup information could not be displayed. Please try refreshing the page or contact support if the problem persists.</p>';
    echo '<p><a href="' . base_url('/manage_mfa.php') . '">Back to Security Settings</a></p>'; // Link back
endif;
?>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
