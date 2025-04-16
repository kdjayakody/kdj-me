<?php
/**
 * Email Verification Landing Page Script
 *
 * This page is typically accessed via a link sent to the user's email.
 * It acknowledges the verification process and directs the user to log in.
 * Assumes Firebase handles the actual verification via the link's oobCode.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php'; // To check login status, though not required
require_once __DIR__ . '/../includes/functions.php';
// No security helpers or api_client needed for this simple version

// --- Session Start ---
// Start session mainly to check login status or potentially set flash messages if needed
if (!secure_session_start()) {
    // Less critical here than on protected pages, but log it
    error_log('Failed to start session in verify_email.php.');
    // Proceed without session features if start fails
}

// --- Initialize Variables ---
$pageTitle = 'Email Verified';
$messageText = null;
$messageType = null;

// --- Check for expected Firebase parameters (optional but good practice) ---
$mode = get_query_data('mode');
$oobCode = get_query_data('oobCode'); // Out Of Band code

if ($mode === 'verifyEmail' && !empty($oobCode)) {
    // Link looks like a Firebase email verification link
    $messageType = 'success';
    $messageText = 'Thank you for verifying your email address. Your account is now active.';

    // Optional: If user happens to be logged in, maybe update their session data?
    // However, usually verification happens *before* first login or while logged out.

    // Optional: Could attempt to call a backend endpoint here if one existed
    // that could use the oobCode with Firebase Admin SDK to confirm,
    // but the current backend doesn't have such an endpoint.

} else {
    // Parameters missing or mode is different - show a more generic message or info
    $pageTitle = 'Account Action'; // More generic title
    $messageType = 'info';
    $messageText = 'Account action processed. If you were verifying your email, you should now be able to log in.';
    // Log if parameters were unexpected
    if ($mode !== 'verifyEmail') {
         error_log("verify_email.php accessed with unexpected mode: " . $mode);
    }
}


// --- Render Page ---

// Include Header Template
// $pageTitle is set above
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1><?php echo escape_html($pageTitle); ?></h1>

<?php
// Display the Message
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<p>You can now proceed to log in with your credentials.</p>
<p>
    <a href="<?php echo base_url('/login.php'); ?>" class="button button-primary">Go to Login</a>
</p>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
