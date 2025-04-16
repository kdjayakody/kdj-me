<?php
/**
 * User Profile Page Script
 *
 * Displays user profile information and handles profile updates.
 * Requires user to be logged in.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for profile API calls

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Authentication Check ---
if (!is_user_logged_in()) {
    set_flash_message('error', 'Please log in to view your profile.');
    redirect('/login.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Your Profile';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'
$errors = []; // Array to hold validation errors for POST
$currentUserData = null; // Will hold profile data fetched from API

// --- Handle Profile Update (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate CSRF Token
    $submittedToken = get_post_data(CSRF_TOKEN_NAME);
    if (!validate_csrf_token($submittedToken)) {
        error_log('CSRF token validation failed for profile update attempt.');
        // Set error message to display after fetching profile data
        $messageType = 'error';
        $messageText = 'Invalid security token. Please try submitting the form again.';
        // Regenerate token for potential form redisplay
        generate_csrf_token(true);
    } else {
        // 2. Get User Input
        $display_name = get_post_data('display_name');
        $phone_number = get_post_data('phone_number');

        // 3. Server-Side Validation & Preparation
        $updateData = [];
        $errors = [];

        // Display Name (basic sanitization, optional update)
        // You might add length validation if needed
        $updateData['display_name'] = sanitize_string($display_name); // Sanitize even if empty

        // Phone Number (optional, validate format if provided)
        $trimmed_phone = trim($phone_number);
        if (!empty($trimmed_phone)) {
            $phonePattern = '/^\+\d{1,15}$/'; // E.164 format
            if (!preg_match($phonePattern, $trimmed_phone)) {
                $errors['phone_number'] = 'Phone number must be in E.164 format (e.g., +9471xxxxxxx).';
            } else {
                $updateData['phone_number'] = $trimmed_phone;
            }
        } else {
            // If submitted empty, explicitly set to empty string or null if API allows clearing
            // Assuming API takes empty string to clear, or just don't include if unchanged
             $updateData['phone_number'] = ''; // Send empty string to potentially clear it
             // Or, fetch current data first and only include if changed (more complex)
        }


        // 4. Call API if Validation Passed
        if (empty($errors)) {
            // TODO: Optional - Fetch current profile here to only send changed data.
            // For simplicity now, we send both fields (or empty phone).

            if (!empty($updateData)) { // Only call API if there's something to update
                $result = api_update_user_profile($updateData);

                // 5. Process API Response
                if ($result['success']) {
                    // Profile updated successfully via API
                    set_flash_message('success', 'Profile updated successfully.');
                    // Redirect back to profile page using GET to prevent re-submission (PRG pattern)
                    redirect('/profile.php');
                    exit;
                } else {
                    // API update failed
                    $messageType = 'error';
                    $messageText = $result['error_message'] ?? 'Failed to update profile. Please try again.';
                    error_log("API Profile Update Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
                }
            } else {
                 // Nothing to update (or only whitespace submitted)
                 $messageType = 'info';
                 $messageText = 'No changes submitted.';
            }
        } else {
            // Validation failed
            $messageType = 'error';
            $messageText = 'Please correct the errors below: <ul>';
            foreach ($errors as $field => $error) {
                 $messageText .= '<li>' . escape_html($error) . '</li>';
            }
             $messageText .= '</ul>';
        }
    } // End CSRF valid block
} // End of POST handling


// --- Fetch Current Profile Data (for GET request or after POST failure) ---
// Always fetch fresh data to display unless POST succeeded and redirected
$profileResult = api_get_user_profile();

if ($profileResult['success'] && isset($profileResult['data'])) {
    $currentUserData = $profileResult['data'];
    // If there was a POST error, keep the POST error message, otherwise clear messages
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($errors)) {
       // Clear any message variables if we just loaded the page (GET)
       // or if POST succeeded but API failed (error set above)
       // Keep messages if POST had validation errors or CSRF errors
       // This logic might need refinement based on desired UX for API errors vs validation errors
       // For now, only clear if it wasn't a POST request with errors.
       if($_SERVER['REQUEST_METHOD'] === 'GET') {
           $messageText = null;
           $messageType = null;
       }
    }
} else {
    // Failed to fetch profile data
    error_log("API Get Profile Failed: Status {$profileResult['status_code']}, Message: {$profileResult['error_message']}");
    // Set error message ONLY if no other message (like POST error) is already set
    if ($messageText === null) {
        $messageType = 'error';
        $messageText = 'Could not load your profile data at this time. Please try again later.';
    }
    // $currentUserData remains null
}


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Your Profile</h1>

<?php
// Display Messages (from POST handling or profile fetch error)
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php
// Display Profile Form ONLY if profile data was loaded successfully
if ($currentUserData !== null):
    // The profile_form.php template expects $currentUserData variable
    require_once __DIR__ . '/../includes/templates/profile_form.php';
else:
    // Show an alternative message if profile data couldn't be loaded
    echo '<p>Your profile information could not be displayed.</p>';
endif;
?>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
