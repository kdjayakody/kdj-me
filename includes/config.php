<?php
/**
 * Configuration File (Production Optimized)
 *
 * Defines constants and settings for the application.
 * Relies heavily on environment variables (.env file or server config)
 * loaded via the vlucas/phpdotenv library.
 */

// --- Composer Autoloader ---
// Required to load the Dotenv library and potentially other dependencies.
// Ensure composer install has been run.
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Handle missing vendor directory gracefully in production
    error_log('CRITICAL: Composer vendor/autoload.php not found. Run "composer install".');
    die('Application dependencies are missing. Please contact administrator.');
}


// --- Load Environment Variables ---
// Define BASE_PATH early as Dotenv needs it
define('BASE_PATH', dirname(__DIR__));

try {
    // Use vlucas/phpdotenv to load .env file from the project root (BASE_PATH)
    // It populates $_ENV and $_SERVER, and getenv() can usually access them.
    // It does NOT rely on putenv().
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // .env file not found - this might be okay if variables are set directly on the server
    error_log("Warning: .env file not found or not readable: " . $e->getMessage());
    // Continue execution, relying on server-set environment variables or defaults below.
} catch (Exception $e) {
    // Other potential errors during loading
     error_log("Error loading .env file: " . $e->getMessage());
     die('Application configuration error. Please contact administrator.');
}


// --- Error Handling (Production) ---
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
// Configure error log path (ensure path is writable by web server and outside web root)
// ini_set('error_log', BASE_PATH . '/logs/php-error.log');


// --- Session Configuration (Production) ---
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // Requires HTTPS
ini_set('session.cookie_samesite', 'Lax');
// ini_set('session.cookie_domain', '.yourdomain.com'); // Set your production domain


// --- Application Settings (Loaded from Environment) ---

// Base URL of your Python kdj-auth API backend (REQUIRED from .env or server env)
// Use $_ENV or $_SERVER as Dotenv primarily populates these. getenv() might also work depending on config.
$apiBaseUrl = $_ENV['API_BASE_URL'] ?? $_SERVER['API_BASE_URL'] ?? getenv('API_BASE_URL');
if (!$apiBaseUrl) {
    error_log('CRITICAL: API_BASE_URL environment variable is not set.');
    die('Application configuration error: API_BASE_URL missing.');
}
define('API_BASE_URL', rtrim($apiBaseUrl, '/'));

// Site Name (Can be from .env or default)
define('SITE_NAME', $_ENV['SITE_NAME'] ?? $_SERVER['SITE_NAME'] ?? getenv('SITE_NAME') ?: 'KDJ Project');

// Public path
define('PUBLIC_PATH', BASE_PATH . '/public');

// Base URL of the frontend application (Can be from .env or auto-detected)
$appUrl = $_ENV['APP_URL'] ?? $_SERVER['APP_URL'] ?? getenv('APP_URL');
if (!$appUrl) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $appUrl = $protocol . $host;
     if ($host === 'localhost') {
         error_log("Warning: APP_URL environment variable not set, auto-detected as potentially incorrect value: " . $appUrl);
     }
}
define('APP_URL', rtrim($appUrl, '/'));


// --- Security Settings ---
define('CSRF_TOKEN_NAME', 'csrf_token');
define('AUTH_SESSION_KEY', 'user_auth_data');
define('AUTH_TOKEN_KEY', 'auth_access_token');
define('AUTH_REFRESH_TOKEN_KEY', 'auth_refresh_token');
define('AUTH_EXPIRY_KEY', 'auth_token_expiry');


// --- Other Settings ---
// Default Timezone
$timezone = $_ENV['APP_TIMEZONE'] ?? $_SERVER['APP_TIMEZONE'] ?? getenv('APP_TIMEZONE') ?: 'Asia/Colombo';
date_default_timezone_set($timezone);


// NOTE: The custom load_dotenv() function has been removed as we now use vlucas/phpdotenv via Composer.

?>
