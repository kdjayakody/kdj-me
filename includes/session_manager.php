<?php
/**
 * Session Management Functions
 *
 * Handles secure session starting, user authentication state,
 * storing/retrieving tokens, and logout.
 * Assumes config.php has been included for constants and session settings.
 */

/**
 * Starts a session securely if one is not already active.
 * Relies on session settings configured in config.php (via ini_set).
 * It's recommended to call this at the very beginning of scripts
 * that require session access, before any output.
 *
 * @return bool True if the session was successfully started or was already active, false on failure.
 */
function secure_session_start(): bool
{
    // Check if a session is already active
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }

    // Ensure session cookie parameters are set (relying on config.php's ini_set calls)
    // Example: session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'domain' => '.yourdomain.com', 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
    // It's generally better to use ini_set in config.php as done previously.

    // Start the session
    if (session_start()) {
        return true;
    } else {
        error_log("Error: Failed to start session.");
        return false;
    }
}

/**
 * Regenerates the session ID to prevent session fixation attacks.
 * Call this function AFTER a user successfully authenticates or changes privilege level.
 *
 * @return bool True on success, false on failure.
 */
function regenerate_session(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("Warning: Attempted to regenerate session ID, but session is not active.");
        return false;
    }
    // Regenerate session ID and delete the old session file
    return session_regenerate_id(true);
}

/**
 * Stores user authentication data and tokens in the session after successful login.
 * Also regenerates the session ID for security.
 *
 * Assumes $authData contains keys like 'user_id', 'email', 'access_token', 'refresh_token', 'expires_in'.
 * Uses constants defined in config.php for session keys.
 *
 * @param array $authData Associative array containing authentication details.
 * Required keys: 'access_token', 'refresh_token', 'expires_in'
 * Optional keys: 'user_id', 'email', 'display_name', etc.
 * @return bool True on success, false on failure (e.g., session not active).
 */
function login_user(array $authData): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("Error: Cannot log in user without an active session.");
        return false;
    }

    // Ensure required constants are defined
    if (!defined('AUTH_SESSION_KEY') || !defined('AUTH_TOKEN_KEY') || !defined('AUTH_REFRESH_TOKEN_KEY') || !defined('AUTH_EXPIRY_KEY')) {
        error_log("CRITICAL: Authentication session key constants not defined in config.php.");
        return false;
    }

    // Ensure required token data is present
    if (!isset($authData['access_token']) || !isset($authData['refresh_token']) || !isset($authData['expires_in'])) {
         error_log("Error: Missing required token data in login_user function call.");
         return false;
    }

    // Regenerate session ID upon successful login to prevent fixation
    if (!regenerate_session()) {
        error_log("Error: Failed to regenerate session ID during login.");
        // Continue login process but log the error
    }

    // Clear any previous authentication data first
    unset($_SESSION[AUTH_SESSION_KEY]);
    unset($_SESSION[AUTH_TOKEN_KEY]);
    unset($_SESSION[AUTH_REFRESH_TOKEN_KEY]);
    unset($_SESSION[AUTH_EXPIRY_KEY]);

    // Store essential token information
    $_SESSION[AUTH_TOKEN_KEY] = $authData['access_token'];
    $_SESSION[AUTH_REFRESH_TOKEN_KEY] = $authData['refresh_token'];
    // Calculate and store the expiry timestamp (current time + expires_in seconds)
    $_SESSION[AUTH_EXPIRY_KEY] = time() + (int)$authData['expires_in'];

    // Store other user data in a separate key
    $userDataToStore = [];
    $allowedKeys = ['user_id', 'email', 'display_name', 'roles']; // Define what to store
    foreach ($allowedKeys as $key) {
        if (isset($authData[$key])) {
            $userDataToStore[$key] = $authData[$key];
        }
    }
    $_SESSION[AUTH_SESSION_KEY] = $userDataToStore;

    return true;
}

/**
 * Updates the access token and its expiry time in the session (e.g., after refreshing).
 *
 * @param string $newAccessToken The new access token.
 * @param int $expiresIn Seconds until the new token expires.
 * @return bool True on success, false on failure.
 */
function update_auth_token(string $newAccessToken, int $expiresIn): bool
{
     if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("Error: Cannot update auth token without an active session.");
        return false;
    }
     if (!defined('AUTH_TOKEN_KEY') || !defined('AUTH_EXPIRY_KEY')) {
        error_log("CRITICAL: Authentication token/expiry key constants not defined.");
        return false;
    }

    $_SESSION[AUTH_TOKEN_KEY] = $newAccessToken;
    $_SESSION[AUTH_EXPIRY_KEY] = time() + $expiresIn;

    // Optionally regenerate session ID after token refresh for added security
    // regenerate_session();

    return true;
}


/**
 * Logs the user out by clearing authentication data and destroying the session.
 *
 * @return void
 */
function logout_user(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        // No active session, nothing to do
        return;
    }

    // Unset all session variables related to auth
    if (defined('AUTH_SESSION_KEY')) unset($_SESSION[AUTH_SESSION_KEY]);
    if (defined('AUTH_TOKEN_KEY')) unset($_SESSION[AUTH_TOKEN_KEY]);
    if (defined('AUTH_REFRESH_TOKEN_KEY')) unset($_SESSION[AUTH_REFRESH_TOKEN_KEY]);
    if (defined('AUTH_EXPIRY_KEY')) unset($_SESSION[AUTH_EXPIRY_KEY]);
    if (defined('CSRF_TOKEN_NAME')) unset($_SESSION[CSRF_TOKEN_NAME]); // Also clear CSRF token

    // Optional: Unset all session variables entirely
    // $_SESSION = array();

    // If using session cookies, delete the cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, // Set expiry in the past
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session
    session_destroy();
}

/**
 * Checks if the user is currently logged in based on session data.
 * Verifies the presence and expiry of the access token.
 *
 * @return bool True if the user is considered logged in, false otherwise.
 */
function is_user_logged_in(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }

    // Check if token and expiry keys exist and are defined
    if (!defined('AUTH_TOKEN_KEY') || !defined('AUTH_EXPIRY_KEY') ||
        empty($_SESSION[AUTH_TOKEN_KEY]) || empty($_SESSION[AUTH_EXPIRY_KEY])) {
        return false;
    }

    // Check if the token expiry time is in the future
    return time() < (int)$_SESSION[AUTH_EXPIRY_KEY];
}

/**
 * Retrieves the stored access token from the session.
 *
 * @return string|null The access token, or null if not found or session inactive.
 */
function get_auth_token(): ?string
{
    if (session_status() !== PHP_SESSION_ACTIVE || !defined('AUTH_TOKEN_KEY')) {
        return null;
    }
    return $_SESSION[AUTH_TOKEN_KEY] ?? null;
}

/**
 * Retrieves the stored refresh token from the session.
 *
 * @return string|null The refresh token, or null if not found or session inactive.
 */
function get_refresh_token(): ?string
{
     if (session_status() !== PHP_SESSION_ACTIVE || !defined('AUTH_REFRESH_TOKEN_KEY')) {
        return null;
    }
    return $_SESSION[AUTH_REFRESH_TOKEN_KEY] ?? null;
}

/**
 * Retrieves stored user data from the session.
 *
 * @param string|null $key Specific key to retrieve (e.g., 'user_id', 'email'). If null, returns all stored user data.
 * @return mixed The requested data, the full user data array, or null if not found/not logged in.
 */
function get_auth_user_data(?string $key = null): mixed
{
    if (!is_user_logged_in() || !defined('AUTH_SESSION_KEY') || !isset($_SESSION[AUTH_SESSION_KEY])) {
        return null;
    }

    $userData = $_SESSION[AUTH_SESSION_KEY];

    if ($key === null) {
        return $userData; // Return all stored user data
    }

    return $userData[$key] ?? null; // Return specific key or null if not set
}

?>
