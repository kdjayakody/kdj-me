<?php
/**
 * General Helper Functions
 *
 * Provides common utility functions for the application.
 * Assumes config.php has been included before these functions are called.
 * Some functions may depend on session_manager.php being included and sessions started.
 */

/**
 * Redirects the user to a specified URL.
 *
 * @param string $url The URL to redirect to. Can be relative (within the app) or absolute.
 * @param int $statusCode HTTP status code for the redirect (default: 302 Found).
 * @return void
 */
function redirect(string $url, int $statusCode = 302): void
{
    // Prevent header injection vulnerabilities
    $url = filter_var(trim($url), FILTER_SANITIZE_URL);

    // Check if it's an absolute URL or relative path
    if (!preg_match('#^https?://#i', $url)) {
        // It's a relative path, construct the full URL using APP_URL
        // Ensure APP_URL is defined (should be from config.php)
        if (!defined('APP_URL')) {
            error_log('CRITICAL: APP_URL constant not defined in redirect function.');
            // Handle error appropriately - maybe display a generic error page
            die('Configuration error.');
        }
        // Ensure the relative URL starts with a slash
        if (substr($url, 0, 1) !== '/') {
            $url = '/' . $url;
        }
        $url = rtrim(APP_URL, '/') . $url;
    }

    // Perform the redirect
    header('Location: ' . $url, true, $statusCode);
    exit; // Stop script execution after redirect
}

/**
 * Safely retrieves data from the $_POST superglobal.
 *
 * @param string $key The key to look for in $_POST.
 * @param mixed $default The default value to return if the key is not found (default: null).
 * @return mixed The value from $_POST or the default value. Returns null if key not set.
 */
function get_post_data(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

/**
 * Safely retrieves data from the $_GET superglobal (query string).
 *
 * @param string $key The key to look for in $_GET.
 * @param mixed $default The default value to return if the key is not found (default: null).
 * @return mixed The value from $_GET or the default value. Returns null if key not set.
 */
function get_query_data(string $key, mixed $default = null): mixed
{
    return $_GET[$key] ?? $default;
}

/**
 * Escapes HTML special characters for safe output.
 * Wrapper for htmlspecialchars.
 *
 * @param string|null $string The string to escape.
 * @return string The escaped string. Returns an empty string if input is null.
 */
function escape_html(?string $string): string
{
    if ($string === null) {
        return '';
    }
    // ENT_QUOTES: Escapes both single and double quotes.
    // 'UTF-8': Ensures correct handling of multi-byte characters.
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Constructs an absolute URL for a path within the application.
 *
 * @param string $path The relative path within the application (e.g., '/login', '/dashboard').
 * @return string The full URL.
 */
function base_url(string $path = ''): string
{
    if (!defined('APP_URL')) {
        error_log('CRITICAL: APP_URL constant not defined in base_url function.');
        return '#error-app-url-not-defined'; // Return an invalid URL to indicate error
    }

    // Ensure path starts with a slash and remove trailing slash from APP_URL
    $path = trim($path);
    if ($path !== '' && substr($path, 0, 1) !== '/') {
        $path = '/' . $path;
    }

    return rtrim(APP_URL, '/') . $path;
}

/**
 * Constructs an absolute URL for an asset file (CSS, JS, images).
 *
 * @param string $path The relative path to the asset within the public/assets directory.
 * @return string The full URL to the asset.
 */
function asset_url(string $path = ''): string
{
    if (!defined('APP_URL')) {
        error_log('CRITICAL: APP_URL constant not defined in asset_url function.');
        return '#error-app-url-not-defined';
    }

    // Ensure path starts without a slash for joining
    $path = ltrim(trim($path), '/');

    return rtrim(APP_URL, '/') . '/assets/' . $path;
}


// --- Flash Message Functions ---
// These functions require session_manager.php to be included and sessions started.

/**
 * Sets a flash message in the session.
 * Requires sessions to be started.
 *
 * @param string $type The type of message (e.g., 'success', 'error', 'info', 'warning').
 * @param string $message The message content.
 * @return void
 */
function set_flash_message(string $type, string $message): void
{
    // Ensure session is active before trying to use $_SESSION
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("Warning: Attempted to set flash message ('{$type}') without an active session.");
        return;
    }
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
}

/**
 * Retrieves and clears all flash messages from the session.
 * Requires sessions to be started.
 * Call this function where you want to display messages (e.g., in header.php).
 *
 * @return array An array of message arrays (each with 'type' and 'message'), or an empty array if none exist.
 */
function get_flash_messages(): array
{
    // Ensure session is active
    if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['flash_messages'])) {
        return [];
    }

    $messages = $_SESSION['flash_messages'];
    unset($_SESSION['flash_messages']); // Clear messages after retrieving
    return $messages;
}

/**
 * Displays flash messages using a simple HTML structure.
 * Requires sessions to be started.
 * Includes basic styling classes (you might want to customize these).
 *
 * @return void Outputs HTML directly.
 */
function display_flash_messages(): void
{
    $messages = get_flash_messages();
    if (!empty($messages)) {
        echo '<div class="flash-messages">'; // Container for messages
        foreach ($messages as $msg) {
            $type = escape_html($msg['type']); // e.g., 'success', 'error'
            $message = escape_html($msg['message']);
            // Basic alert styling - adapt classes to your CSS framework or custom styles
            echo "<div class=\"alert alert-{$type}\" role=\"alert\">{$message}</div>";
        }
        echo '</div>';
    }
}


// Add other general-purpose helper functions below as needed.

?>
