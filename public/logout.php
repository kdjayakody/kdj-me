<?php
/**
 * Logout Script
 *
 * Handles user logout by clearing the session and redirecting to the login page.
 */

// --- Core Includes ---
// Ensure the path is correct relative to the public directory
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_logout_user
// require_once __DIR__ . '/../includes/security_helpers.php'; // Needed if implementing CSRF check

// --- Session Start ---
// Must start session to access session data and then destroy it.
if (!secure_session_start()) {
    // If session can't start, redirecting might be problematic.
    // Log error and perhaps show a minimal error message or redirect differently.
    error_log('Failed to start session in logout.php.');
    // Redirect cautiously without relying on session flash messages
    header('Location: login.php?logout_error=session');
    exit;
}

// --- CSRF Protection (Recommended for POST logout) ---
/*
// Uncomment and adapt if your logout form includes a CSRF token
$submittedToken = get_post_data(CSRF_TOKEN_NAME);
if (!validate_csrf_token($submittedToken)) {
    error_log('CSRF token validation failed for logout attempt.');
    set_flash_message('error', 'Invalid security token. Logout aborted.');
    // Redirect back to where the user came from or dashboard
    redirect('/dashboard.php'); // Or use $_SERVER['HTTP_REFERER'] cautiously
    exit;
}
*/

// --- Call API Logout (Optional but Recommended) ---
// This attempts to invalidate the token on the backend side as well.
// We proceed with local logout even if this API call fails.
$apiLogoutResult = api_logout_user(); // Needs the token from the session
if (!$apiLogoutResult['success']) {
    // Log the error but don't necessarily stop the local logout process
    error_log("API Logout Failed: Status {$apiLogoutResult['status_code']}, Message: {$apiLogoutResult['error_message']}");
    // You might want to inform the user differently if API logout fails,
    // but clearing the local session is the primary goal here.
}

// --- Perform Local Logout ---
// Clears session authentication data, destroys session, clears cookie.
logout_user(); // From session_manager.php

// --- Set Success Message & Redirect ---
// Need to start session *again* briefly just to set the flash message
// Note: This is slightly awkward. An alternative is redirecting with a query param.
// Or, if logout_user() didn't destroy the session immediately, set message before destroy.
// Let's try setting message *before* destroy in session_manager if possible,
// otherwise, start/set/redirect. For now, assume logout_user destroys.

// Re-start session briefly ONLY to set flash message (if needed)
if (secure_session_start()) {
     set_flash_message('success', 'You have been logged out successfully.');
} else {
     // Can't set flash message, redirect without it
     redirect('/login.php?logged_out=1'); // Use query param as fallback
     exit;
}

redirect('/login.php');
exit; // Ensure script stops execution

?>
