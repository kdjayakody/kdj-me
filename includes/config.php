<?php
/**
 * Configuration File
 *
 * Defines constants for application settings, primarily the API endpoint.
 * It's recommended to use environment variables (.env file) for sensitive data
 * or settings that change between environments (development, production).
 */

// --- Error Reporting (Recommended for Development) ---
// Comment these out or set to 0 in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Session Configuration ---
// Secure session settings (adjust domain if needed)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
// Uncomment and set domain for production if needed:
// ini_set('session.cookie_domain', '.yourdomain.com');
// Uncomment for HTTPS only:
// ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Lax'); // Or 'Strict' if appropriate

// --- Application Settings ---

// Base URL of your Python kdj-auth API backend
// IMPORTANT: Change this to your actual API URL.
// Use environment variables in production! Example: getenv('API_BASE_URL') ?: 'http://localhost:8000/api/v1';
define('API_BASE_URL', 'https://auth.kdj.lk/api/v1'); // Default for local dev

// Site Name (Used in templates, emails, etc.)
define('SITE_NAME', 'KDJ Project');

// Base path of the application (useful for constructing URLs)
// Assumes the project root is one level above the 'includes' directory
define('BASE_PATH', dirname(__DIR__)); // Gets the '/kdj-php-frontend' path

// Public path (useful for linking assets)
// Assumes 'public' directory is parallel to 'includes'
define('PUBLIC_PATH', BASE_PATH . '/public');

// Base URL of the frontend application (useful for redirects, links)
// Detect automatically (might need adjustment based on server setup)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'; // Fallback for CLI or missing host
define('APP_URL', $protocol . $host);


// --- Security Settings ---

// CSRF Token Name (used in forms and sessions)
define('CSRF_TOKEN_NAME', 'csrf_token');

// Session key for storing user authentication status/data
define('AUTH_SESSION_KEY', 'user_auth_data');
define('AUTH_TOKEN_KEY', 'auth_access_token');
define('AUTH_REFRESH_TOKEN_KEY', 'auth_refresh_token');
define('AUTH_EXPIRY_KEY', 'auth_token_expiry');


// --- Other Settings ---

// Default Timezone
date_default_timezone_set('Asia/Colombo'); // Set to your timezone (Sri Lanka)


/**
 * Function to load .env file (Simple implementation)
 * Requires a .env file in the project root (BASE_PATH)
 * Consider using a dedicated library like vlucas/phpdotenv via Composer for robustness.
 */
function load_dotenv($path) {
    $envFilePath = $path . '/.env';
    if (!file_exists($envFilePath) || !is_readable($envFilePath)) {
        // Optionally log a warning or error if .env is expected but not found/readable
        // error_log(".env file not found or not readable at: " . $envFilePath);
        return;
    }

    $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Split into name and value
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Remove surrounding quotes (optional)
        if (strlen($value) > 1 && $value[0] == '"' && $value[strlen($value) - 1] == '"') {
            $value = substr($value, 1, -1);
        }
         if (strlen($value) > 1 && $value[0] == "'" && $value[strlen($value) - 1] == "'") {
            $value = substr($value, 1, -1);
        }

        // Set environment variable if not already set
        if (!getenv($name)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value; // Also set in $_ENV
            $_SERVER[$name] = $value; // Also set in $_SERVER
        }
    }
}

// Load the .env file from the project root
// load_dotenv(BASE_PATH);

// Example of overriding a define with an environment variable
// Define API_BASE_URL using environment variable if available, otherwise use the default
if (getenv('API_BASE_URL')) {
    // Undefine the constant first if it was already defined above
    // Note: This shows the pattern, but defining conditionally is cleaner
    // if (defined('API_BASE_URL')) { /* No standard way to undefine */ }
    // It's better to check getenv() *before* defining the constant initially.
    // For simplicity here, we'll just show how to access it:
    $api_base_url = getenv('API_BASE_URL');
    // Or redefine if necessary, though constants aren't meant to be redefined.
    // A better approach is to use a config array or class instead of constants
    // if values need to be dynamically loaded from .env after initial definition.
}


?>
