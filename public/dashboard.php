<?php
/**
 * Dashboard Page Script
 *
 * A protected page accessible only to logged-in users.
 * Displays a welcome message and basic dashboard content.
 */

// --- Core Includes ---
// Ensure the path is correct relative to the public directory
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
// Security helpers might not be directly needed here unless forms are added
require_once __DIR__ . '/../includes/security_helpers.php';
// API client might be needed if dashboard fetches dynamic data
require_once __DIR__ . '/../includes/api_client.php';

// --- Session Start ---
if (!secure_session_start()) {
    // Critical error if session fails to start
    die('Failed to start session. Please contact administrator.');
}

// --- Authentication Check ---
// If the user is NOT logged in, redirect them to the login page.
if (!is_user_logged_in()) {
    // Set a flash message to inform the user why they were redirected
    set_flash_message('error', 'Please log in to access the dashboard.');
    redirect('/login.php');
    exit; // Stop script execution after redirect
}

// --- Get Logged-in User Data ---
// Retrieve user data stored in the session during login
$userDisplayName = get_auth_user_data('display_name');
$userEmail = get_auth_user_data('email');

// Use display name if available, otherwise fallback to email, otherwise generic
$welcomeName = !empty($userDisplayName) ? $userDisplayName : (!empty($userEmail) ? $userEmail : 'User');

// --- Set Page Title ---
$pageTitle = 'Dashboard';

// --- Render Page ---

// Include Header Template
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Dashboard</h1>

<p>Welcome back, <?php echo escape_html($welcomeName); ?>!</p>

<p>This is your dashboard. You can add widgets, summaries, or links to other features here.</p>

<div class="dashboard-links">
    <ul>
        <li><a href="<?php echo base_url('/profile.php'); ?>">Manage Your Profile</a></li>
        <li><a href="<?php echo base_url('/change_password.php'); ?>">Change Your Password</a></li>
        <li><a href="<?php echo base_url('/manage_mfa.php'); ?>">Manage Security Settings (MFA)</a></li>
        <li>
             <form action="<?php echo base_url('/logout.php'); ?>" method="post" style="display: inline;">
                <?php // Consider adding CSRF if making logout strictly POST ?>
                <button type="submit" class="button button-secondary">Logout</button>
            </form>
        </li>
        </ul>
</div>


<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
