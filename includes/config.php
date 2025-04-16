<?php
/**
 * Configuration File
 *
 * Defines constants for application settings, primarily the API endpoint.
 * Uses environment variables for sensitive or environment-specific settings.
 */

// Bring in Composer's autoloader for vlucas/phpdotenv
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// --- Environment Configuration ---
// Initialize dotenv to load environment variables
$dotenvPath = dirname(__DIR__);
if (file_exists($dotenvPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($dotenvPath);
    try {
        $dotenv->load();
    } catch (Exception $e) {
        error_log('Failed to load .env file: ' . $e->getMessage());
    }
}

// --- Error Reporting (Recommended for Development) ---
// Comment these out or set to 0 in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Session Configuration ---
// Secure session settings (adjust domain if needed)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax'); // Or 'Strict' if appropriate

// --- Application Settings ---

// Determine API Base URL
// Priority: 
// 1. Environment Variable
// 2. Local development default
// 3. Fallback localhost
$api_base_url = $_ENV['API_BASE_URL'] 
    ?? getenv('API_BASE_URL') 
    ?? 'http://localhost:8000/api/v1';

// Define the API Base URL constant
define('API_BASE_URL', $api_base_url);

// Site Name (Used in templates, emails, etc.)
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'KDJ Project');

// Base path of the application
define('BASE_PATH', dirname(__DIR__));

// Public path
define('PUBLIC_PATH', BASE_PATH . '/public');

// Automatically detect application URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
              (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('APP_URL', $protocol . $host);

// --- Security Settings ---
define('CSRF_TOKEN_NAME', 'csrf_token');
define('AUTH_SESSION_KEY', 'user_auth_data');
define('AUTH_TOKEN_KEY', 'auth_access_token');
define('AUTH_REFRESH_TOKEN_KEY', 'auth_refresh_token');
define('AUTH_EXPIRY_KEY', 'auth_token_expiry');

// --- Other Settings ---
// Set default timezone
date_default_timezone_set('Asia/Colombo'); // Adjust to your timezone

// Optional: Log the configured API URL for debugging
error_log('Configured API Base URL: ' . API_BASE_URL);

?>