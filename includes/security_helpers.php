<?php
/**
 * Security Helper Functions
 *
 * Provides functions for CSRF protection and basic input validation/sanitization.
 * Assumes config.php has been included.
 * CSRF functions depend on session_manager.php being included and sessions started.
 */

// --- CSRF Protection Functions ---

/**
 * Generates a cryptographically secure CSRF token and stores it in the session.
 * If a token already exists in the session, it returns the existing one
 * unless forced to regenerate.
 * Requires sessions to be started.
 *
 * @param bool $forceRegenerate If true, always generates a new token, overwriting any existing one.
 * @return string The CSRF token. Returns empty string if session is not active.
 */
function generate_csrf_token(bool $forceRegenerate = false): string
{
    // Ensure session is active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("Error: Cannot generate CSRF token without an active session.");
        return ''; // Cannot generate token without session
    }

    // Ensure CSRF_TOKEN_NAME is defined
    if (!defined('CSRF_TOKEN_NAME')) {
        error_log('CRITICAL: CSRF_TOKEN_NAME constant not defined.');
        die('Configuration error.');
    }
    $tokenName = CSRF_TOKEN_NAME;

    // Return existing token if available and not forcing regeneration
    if (!$forceRegenerate && !empty($_SESSION[$tokenName])) {
        return $_SESSION[$tokenName];
    }

    // Generate a new secure random token
    try {
        $token = bin2hex(random_bytes(32)); // 32 bytes = 64 hex characters
        $_SESSION[$tokenName] = $token;
        return $token;
    } catch (Exception $e) {
        error_log("Error generating CSRF token: " . $e->getMessage());
        // Handle error appropriately, maybe die or return empty
        die('Failed to generate security token.');
    }
}

/**
 * Validates a submitted CSRF token against the one stored in the session.
 * Uses hash_equals for timing-attack resistance.
 * Requires sessions to be started.
 *
 * @param string|null $submittedToken The token received from the user submission (e.g., $_POST).
 * @param bool $unsetAfterValidation If true, removes the token from session after validation (making it one-time use).
 * @return bool True if the token is valid, false otherwise.
 */
function validate_csrf_token(?string $submittedToken, bool $unsetAfterValidation = true): bool
{
    // Ensure session is active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("Error: Cannot validate CSRF token without an active session.");
        return false;
    }

    // Ensure CSRF_TOKEN_NAME is defined
    if (!defined('CSRF_TOKEN_NAME')) {
        error_log('CRITICAL: CSRF_TOKEN_NAME constant not defined.');
        return false; // Cannot validate without knowing the token name
    }
    $tokenName = CSRF_TOKEN_NAME;

    // Check if token exists in session and submitted token is provided
    if (empty($_SESSION[$tokenName]) || empty($submittedToken)) {
        return false;
    }

    $sessionToken = $_SESSION[$tokenName];

    // Compare tokens using hash_equals to prevent timing attacks
    $isValid = hash_equals($sessionToken, $submittedToken);

    // Optionally remove the token from the session after validation attempt
    if ($unsetAfterValidation) {
        unset($_SESSION[$tokenName]);
    }

    return $isValid;
}

/**
 * Generates an HTML hidden input field containing the current CSRF token.
 * This should be included within your HTML forms.
 * Requires sessions to be started.
 *
 * @return string HTML hidden input field string, or empty string if token generation fails.
 */
function csrf_input_field(): string
{
    // Ensure CSRF_TOKEN_NAME is defined
    if (!defined('CSRF_TOKEN_NAME')) {
        error_log('CRITICAL: CSRF_TOKEN_NAME constant not defined for input field.');
        return '';
    }
    $tokenName = CSRF_TOKEN_NAME;
    $token = generate_csrf_token(); // Get or generate the token

    if (empty($token)) {
         error_log("Error: Failed to get/generate CSRF token for input field.");
         return '';
    }

    // Use escape_html (from functions.php) for attributes
    $escapedTokenName = escape_html($tokenName);
    $escapedToken = escape_html($token);

    return "<input type=\"hidden\" name=\"{$escapedTokenName}\" value=\"{$escapedToken}\">";
}


// --- Basic Input Sanitization & Validation Helpers ---

/**
 * Basic string sanitization: trims whitespace and removes HTML/PHP tags.
 * NOTE: This is very basic. For complex inputs or specific formats,
 * use more targeted validation/sanitization.
 *
 * @param string|null $input The input string.
 * @return string The sanitized string. Returns empty string if input is null.
 */
function sanitize_string(?string $input): string
{
    if ($input === null) {
        return '';
    }
    return strip_tags(trim($input));
}

/**
 * Validates an email address using PHP's filter_var.
 *
 * @param string|null $email The email address to validate.
 * @return bool True if the email is valid, false otherwise.
 */
function validate_email(?string $email): bool
{
    if ($email === null || $email === '') {
        return false;
    }
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Checks if a value is provided (not null and not an empty string after trimming).
 *
 * @param mixed $value The value to check.
 * @return bool True if the value is considered present, false otherwise.
 */
function validate_required(mixed $value): bool
{
    if ($value === null) {
        return false;
    }
    // Check for empty string after trimming whitespace
    if (is_string($value) && trim($value) === '') {
        return false;
    }
    // Consider empty arrays as not provided (optional, adjust if needed)
    if (is_array($value) && empty($value)) {
         return false;
    }
    return true;
}

/**
 * Validates that a string matches a minimum length.
 *
 * @param string|null $value The string to check.
 * @param int $minLength The minimum required length.
 * @return bool True if the string meets the minimum length, false otherwise.
 */
function validate_min_length(?string $value, int $minLength): bool
{
    if ($value === null) {
        return false; // Or true if null is acceptable? Depends on requirements. Usually false.
    }
    // Use mb_strlen for multi-byte character support
    return mb_strlen(trim($value), 'UTF-8') >= $minLength;
}


// Add more specific validation functions as needed (e.g., validate_integer, validate_url, etc.)

?>
