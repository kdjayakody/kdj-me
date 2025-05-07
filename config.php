<?php
/**
 * KDJ Lanka - Configuration File
 * ------------------------------
 * Main configuration settings for the KDJ Lanka Authentication System
 */

// Prevent direct access to this file
if (!defined('APP_PATH') && basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Direct access to this file is forbidden.');
}

// Define application constants
define('APP_NAME', 'KDJ Lanka Authentication');
define('APP_VERSION', '1.2.0');
define('APP_PATH', dirname(__FILE__));
define('APP_URL', 'https://me.kdj.lk');
define('APP_TIMEZONE', 'Asia/Colombo');
define('APP_CHARSET', 'UTF-8');

// Set default timezone
date_default_timezone_set(APP_TIMEZONE);

// Set default encoding
mb_internal_encoding(APP_CHARSET);

// Load environment variables from .env file if it exists
if (file_exists(APP_PATH . '/.env')) {
    $env_file = file_get_contents(APP_PATH . '/.env');
    $lines = explode("\n", $env_file);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments or empty lines
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2) + [null, null];
        if (!empty($key) && !empty($value)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Determine environment
$app_env = getenv('APP_ENV') ?: 'production';
define('APP_ENV', $app_env);

// Error reporting - Disable in production
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    define('DEBUG_MODE', false);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('DEBUG_MODE', true);
}

/**
 * App Configuration
 * 
 * Retrieves configuration values with optional default fallback
 */
function config($key = null, $default = null) {
    static $config = null;
    
    // Load configuration if not already loaded
    if ($config === null) {
        // Base configuration
        $config = [
            // API endpoints
            'api' => [
                'base_url' => getenv('API_BASE_URL') ?: 'https://auth.kdj.lk/api/v1',
                'timeout' => 30,
                'rate_limit' => 100
            ],
            
            // Authentication
            'auth' => [
                'session_lifetime' => 3600, // 1 hour
                'token_lifetime' => 3600, // 1 hour
                'refresh_token_lifetime' => 604800, // 1 week
                'remember_me_lifetime' => 2592000, // 30 days
                'password_reset_lifetime' => 3600, // 1 hour
                'max_login_attempts' => 5,
                'lockout_time' => 900, // 15 minutes
                'require_email_verification' => true,
                'mfa_enabled' => true,
                'min_password_length' => 12
            ],
            
            // Firebase (for social login)
            'firebase' => [
                'api_key' => getenv('FIREBASE_API_KEY'),
                'auth_domain' => getenv('FIREBASE_AUTH_DOMAIN'),
                'project_id' => getenv('FIREBASE_PROJECT_ID'),
                'app_id' => getenv('FIREBASE_APP_ID')
            ],
            
            // Google OAuth (for social login)
            'google' => [
                'client_id' => getenv('GOOGLE_CLIENT_ID'),
                'client_secret' => getenv('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => getenv('GOOGLE_REDIRECT_URI')
            ],
            
            // Database - Only used for debugging
            'database' => [
                'driver' => getenv('DB_DRIVER') ?: 'mysql',
                'host' => getenv('DB_HOST'),
                'port' => getenv('DB_PORT') ?: 3306,
                'database' => getenv('DB_DATABASE'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            
            // Mail settings
            'mail' => [
                'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@kdj.lk',
                'from_name' => getenv('MAIL_FROM_NAME') ?: 'KDJ Lanka',
                'smtp_host' => getenv('MAIL_HOST'),
                'smtp_port' => getenv('MAIL_PORT') ?: 587,
                'smtp_username' => getenv('MAIL_USERNAME'),
                'smtp_password' => getenv('MAIL_PASSWORD'),
                'smtp_encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls'
            ],
            
            // Security settings
            'security' => [
                'csrf_protection' => true,
                'secure_cookies' => true,
                'hashing_cost' => 12, // For password hashing
                'jwt_secret' => getenv('JWT_SECRET'),
                'encryption_key' => getenv('ENCRYPTION_KEY')
            ],
            
            // Logging
            'logging' => [
                'enabled' => true,
                'level' => getenv('LOG_LEVEL') ?: 'error', // debug, info, warning, error
                'path' => APP_PATH . '/logs'
            ],
            
            // Analytics
            'analytics_enabled' => getenv('ANALYTICS_ENABLED') === 'true',
            'analytics_id' => getenv('ANALYTICS_ID')
        ];
        
        // Load environment-specific configs
        $env_config_file = APP_PATH . "/config/{$app_env}.php";
        if (file_exists($env_config_file)) {
            $env_config = include $env_config_file;
            $config = array_merge_recursive($config, $env_config);
        }
    }
    
    // Return all configuration if no key is specified
    if ($key === null) {
        return $config;
    }
    
    // Parse dot notation keys (e.g., 'api.base_url')
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $segment) {
        if (isset($value[$segment])) {
            $value = $value[$segment];
        } else {
            return $default;
        }
    }
    
    return $value;
}

/**
 * Gets a cookie with secure defaults
 */
function getCookie($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
}

/**
 * Sets a cookie with secure defaults
 */
function setCookie($name, $value, $expire_days = 30, $path = '/', $domain = null, $secure = null, $httponly = true) {
    $secure = $secure === null ? config('security.secure_cookies', true) : $secure;
    $domain = $domain ?: $_SERVER['HTTP_HOST'];
    
    $options = [
        'expires' => time() + ($expire_days * 86400),
        'path' => $path,
        'domain' => $domain,
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => 'Lax'
    ];
    
    return setcookie($name, $value, $options);
}

/**
 * Define global constants for API endpoints
 */
define('API_BASE_URL', config('api.base_url'));

/**
 * Load utility functions and helpers
 */
$util_files = glob(APP_PATH . '/includes/*.php');
foreach ($util_files as $file) {
    require_once $file;
}

// Return configuration instance for direct inclusion
return [
    'config' => 'config'
];