<?php
/**
 * Configuration File (Production Optimized)
 *
 * Defines constants and settings for the application.
 * Relies heavily on environment variables (.env file or server config)
 * for sensitive data and environment-specific settings.
 */

// --- Error Handling (Production) ---
ini_set('display_errors', 0); // NEVER display errors in production
ini_set('display_startup_errors', 0); // NEVER display startup errors
error_reporting(E_ALL); // Report all errors internally
ini_set('log_errors', 1); // Log errors instead of displaying them

// IMPORTANT: Configure a writable path for your error log file in your php.ini
// or uncomment and set a specific path here (ensure the path is writable by the web server).
// Make sure this path is OUTSIDE your web root.
// ini_set('error_log', BASE_PATH . '/logs/php-error.log'); // Example path

// --- Session Configuration (Production) ---
// Secure session settings
ini_set('session.cookie_httponly', 1); // Prevent JS access to session cookie
ini_set('session.use_only_cookies', 1); // Don't accept session IDs in URLs
ini_set('session.cookie_secure', 1); // IMPORTANT: Transmit cookie only over HTTPS
ini_set('session.cookie_samesite', 'Lax'); // CSRF mitigation ('Strict' or 'Lax')

// IMPORTANT: Set your production domain. Remove leading dot if not needed for subdomains.
// ini_set('session.cookie_domain', '.yourdomain.com');


/**
 * Function to load .env file (Simple implementation)
 * Requires a .env file in the project root (BASE_PATH)
 * NOTE: For robust production use, consider a dedicated library like
 * vlucas/phpdotenv via Composer (if using Composer). This simple function
 * has limitations (e.g., doesn't handle quoted values complexly, comments mid-line).
 */
function load_dotenv($path) {
    $envFilePath = $path . '/.env';
    if (!file_exists($envFilePath) || !is_readable($envFilePath)) {
        // In production, you might want to log this or handle it more gracefully
        // depending on whether .env is absolutely required or if server env vars are used.
        error_log("Warning: .env file not found or not readable at: " . $envFilePath);
        return;
    }

    $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue; // Skip lines without '='
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Basic handling for quoted values
        if (strlen($value) > 1 && (($value[0] == '"' && $value[strlen($value) - 1] == '"') || ($value[0] == "'" && $value[strlen($value) - 1] == "'"))) {
            $value = substr($value, 1, -1);
            $value = str_replace(['\\"', "\\'"], ['"', "'"], $value); // Handle escaped quotes within
        }

        // Set environment variable if not already set by the server
        if (getenv($name) === false && !isset($_ENV[$name]) && !isset($_SERVER[$name])) {
             putenv(sprintf('%s=%s', $name, $value));
             $_ENV[$name] = $value;
             $_SERVER[$name] = $value;
        }
    }
}

// Define BASE_PATH early as load_dotenv needs it
define('BASE_PATH', dirname(__DIR__));

// Load the .env file from the project root
load_dotenv(BASE_PATH);


// --- Application Settings (Loaded from Environment) ---

// Base URL of your Python kdj-auth API backend (REQUIRED from .env or server env)
$apiBaseUrl = getenv('API_BASE_URL');
if (!$apiBaseUrl) {
    error_log('CRITICAL: API_BASE_URL environment variable is not set.');
    // You might want to die() here or handle this more gracefully depending on app structure
    die('Application configuration error. Please contact administrator.');
}
define('API_BASE_URL', rtrim($apiBaseUrl, '/')); // Remove trailing slash if present

// Site Name (Can be from .env or default)
define('SITE_NAME', getenv('SITE_NAME') ?: 'KDJ Project');

// Public path (useful for linking assets)
define('PUBLIC_PATH', BASE_PATH . '/public');

// Base URL of the frontend application (Can be from .env or auto-detected)
$appUrl = getenv('APP_URL');
if (!$appUrl) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'; // Should not be localhost in prod ideally
    $appUrl = $protocol . $host;
     // Log a warning if APP_URL had to be auto-detected in production
     if ($host === 'localhost') {
         error_log("Warning: APP_URL environment variable not set, auto-detected as potentially incorrect value: " . $appUrl);
     }
}
define('APP_URL', rtrim($appUrl, '/'));


// --- Security Settings ---

// CSRF Token Name
define('CSRF_TOKEN_NAME', 'csrf_token');

// Session key names
define('AUTH_SESSION_KEY', 'user_auth_data');
define('AUTH_TOKEN_KEY', 'auth_access_token');
define('AUTH_REFRESH_TOKEN_KEY', 'auth_refresh_token');
define('AUTH_EXPIRY_KEY', 'auth_token_expiry');


// --- Other Settings ---

// Default Timezone
$timezone = getenv('APP_TIMEZONE') ?: 'Asia/Colombo'; // Load from .env or default
date_default_timezone_set($timezone);

?>
