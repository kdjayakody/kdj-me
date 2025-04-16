<?php
/**
 * Single Message Display Template
 *
 * Displays a single message block (e.g., for form success or error feedback).
 * Should be included conditionally by a page script when a specific message
 * needs to be shown.
 *
 * Expects the calling script to define:
 * - $messageType (string): 'success', 'error', 'info', 'warning', etc. Used for CSS class.
 * - $messageText (string): The message content to display.
 *
 * Assumes functions.php (for escape_html) is included previously.
 */

// --- Defensive Checks ---
if (!function_exists('escape_html')) {
     error_log('CRITICAL: escape_html() function not available. Include functions.php.');
     echo '';
     return; // Stop rendering this template part
}

// Check if required variables are set by the calling script
if (!isset($messageType) || !is_string($messageType) || trim($messageType) === '') {
    // error_log('Warning: $messageType not set before including message.php.');
    // Default to 'info' or handle as an error if type is critical
    $messageType = 'info'; // Default type if not provided
}
if (!isset($messageText) || !is_string($messageText) || trim($messageText) === '') {
    // Don't display anything if there's no message text
    return;
}

// Sanitize message type for use in class attribute
$allowedTypes = ['success', 'error', 'warning', 'info']; // Define allowed types
$messageClassType = in_array(strtolower($messageType), $allowedTypes) ? strtolower($messageType) : 'info';

?>
<div class="message message-<?php echo escape_html($messageClassType); ?>" role="alert">
    <p><?php echo escape_html($messageText); ?></p>
    </div>

<?php
// Unset the variables after displaying to prevent accidental reuse if included multiple times
unset($messageType, $messageText);
?>
