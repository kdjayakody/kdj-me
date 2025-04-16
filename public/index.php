<?php
/**
 * Index Page Script (Homepage)
 *
 * Acts as the main entry point. Redirects logged-in users to the dashboard,
 * otherwise displays a welcome message and login/register links.
 */

// --- Core Includes ---
// Ensure the path is correct relative to the public directory
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
// No security helpers or api_client likely needed for simple index page logic

// --- Session Start ---
if (!secure_session_start()) {
    // Critical error if session fails to start
    die('Failed to start session. Please contact administrator.');
}

// --- Redirect if already logged in ---
// If the user is already logged in, redirect them to the dashboard.
if (is_user_logged_in()) {
    redirect('/dashboard.php'); // Assumes dashboard.php exists
    exit; // Ensure script stops after redirect
}

// --- User is NOT logged in ---

// --- Set Page Title ---
$pageTitle = 'Welcome'; // Or your site's tagline

// --- Render Page ---

// Include Header Template
require_once __DIR__ . '/../includes/templates/header.php';

?>

<div class="welcome-container" style="text-align: center; padding: 40px 15px;">

    <h1>Welcome to <?php echo escape_html(SITE_NAME); ?>!</h1>

    <p>This is the authentication portal.</p>
    <p>Please log in to access your account or register if you are a new user.</p>

    <div class="welcome-actions" style="margin-top: 30px;">
        <a href="<?php echo base_url('/login.php'); ?>" class="button button-primary" style="margin-right: 10px;">Login</a>
        <a href="<?php echo base_url('/register.php'); ?>" class="button button-secondary">Register</a>
    </div>

</div>
<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
