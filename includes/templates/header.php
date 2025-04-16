<?php
/**
 * Header Template
 *
 * Includes the HTML head, doctype, opening body tag, main navigation,
 * and flash message display.
 *
 * Assumes core files (config, functions, session_manager) are included
 * before this template is required.
 * Expects a $pageTitle variable to be set in the calling script.
 */

// Ensure $pageTitle is set, provide a default if not (though it should always be set)
$pageTitle = isset($pageTitle) ? $pageTitle : 'Welcome';

// Ensure constants are available (defensive check)
if (!defined('SITE_NAME') || !defined('APP_URL')) {
    error_log('CRITICAL: Required constants SITE_NAME or APP_URL not defined before including header.php');
    die('Application configuration error.'); // Fail hard if config isn't loaded
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_html($pageTitle); ?> | <?php echo escape_html(SITE_NAME); ?></title>

    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">

    <?php if (isset($pageDescription) && !empty($pageDescription)): ?>
        <meta name="description" content="<?php echo escape_html($pageDescription); ?>">
    <?php endif; ?>

</head>
<body>

    <header class="main-header">
        <nav class="main-nav">
            <div class="nav-brand">
                <a href="<?php echo base_url('/'); ?>"><?php echo escape_html(SITE_NAME); ?></a>
            </div>
            <ul class="nav-links">
                <li><a href="<?php echo base_url('/'); ?>">Home</a></li>
                <?php if (is_user_logged_in()): ?>
                    <li><a href="<?php echo base_url('/dashboard.php'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url('/profile.php'); ?>">Profile</a></li>
                    <li><a href="<?php echo base_url('/manage_mfa.php'); ?>">Security</a></li>
                    <li>
                        <form action="<?php echo base_url('/logout.php'); ?>" method="post" style="display: inline;">
                            <?php // It's good practice to include CSRF token even for simple POST actions like logout ?>
                            <?php // However, logout often uses GET for simplicity, or a dedicated button/JS call ?>
                            <?php // If using POST, ensure security_helpers.php is included in logout.php and token validated ?>
                            <?php // echo csrf_input_field(); ?>
                            <button type="submit" class="nav-logout-button">Logout (<?php echo escape_html(get_auth_user_data('email') ?: 'User'); ?>)</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo base_url('/login.php'); ?>">Login</a></li>
                    <li><a href="<?php echo base_url('/register.php'); ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <div class="container"> <?php
            // Display any flash messages set in the session
            // Requires functions.php and session_manager.php to be included and session started
            display_flash_messages();
            ?>
            