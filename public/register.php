<?php
/**
 * Registration Page Script
 *
 * Handles displaying the registration form and processing user registration attempts
 * by validating input and interacting with the backend API.
 */

// --- Core Includes ---
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security_helpers.php';
require_once __DIR__ . '/../includes/api_client.php'; // Needed for api_register_user

// --- Session Start ---
if (!secure_session_start()) {
    die('Failed to start session. Please contact administrator.');
}

// --- Redirect if already logged in ---
if (is_user_logged_in()) {
    redirect('/dashboard.php');
    exit;
}

// --- Initialize Variables ---
$pageTitle = 'Register';
$messageText = null;
$messageType = null; // 'success', 'error', 'info', 'warning'
$errors = []; // Array to hold validation errors
$formData = [ // To potentially repopulate form on error (template needs modification for this)
    'display_name' => '',
    'email' => '',
    'phone_number' => ''
];

// --- Handle Form Submission (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate CSRF Token
    $submittedToken = get_post_data(CSRF_TOKEN_NAME);
    if (!validate_csrf_token($submittedToken)) {
        error_log('CSRF token validation failed for registration attempt.');
        $messageType = 'error';
        $messageText = 'Invalid security token. Please try submitting the form again.';
        generate_csrf_token(true); // Regenerate token
    } else {
        // 2. Get User Input
        // Store submitted data for potential redisplay on error
        $formData['display_name'] = get_post_data('display_name');
        $formData['email'] = get_post_data('email');
        $formData['phone_number'] = get_post_data('phone_number');
        $password = get_post_data('password');
        $confirm_password = get_post_data('confirm_password');

        // 3. Server-Side Validation
        // Email
        if (!validate_required($formData['email'])) {
            $errors['email'] = 'Email address is required.';
        } elseif (!validate_email($formData['email'])) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        // Password
        if (!validate_required($password)) {
            $errors['password'] = 'Password is required.';
        } else {
            // Check complexity (matches hints in register_form.php)
            $passwordMinLength = 12;
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{' . $passwordMinLength . ',}$/';
            if (!validate_min_length($password, $passwordMinLength)) {
                 $errors['password'] = "Password must be at least {$passwordMinLength} characters long.";
            } elseif (!preg_match($passwordPattern, $password)) {
                 $errors['password'] = 'Password must include uppercase, lowercase, a digit, and a special character.';
            } elseif (!validate_required($confirm_password)) {
                 $errors['confirm_password'] = 'Please confirm your password.';
            } elseif ($password !== $confirm_password) {
                 $errors['confirm_password'] = 'Passwords do not match.';
            }
        }

        // Phone Number (Optional)
        if (validate_required($formData['phone_number'])) { // Only validate if provided
             $phonePattern = '/^\+\d{1,15}$/'; // E.164 format
             if (!preg_match($phonePattern, $formData['phone_number'])) {
                 $errors['phone_number'] = 'Phone number must be in E.164 format (e.g., +9471xxxxxxx).';
             }
        }

        // Display Name (Optional - basic sanitization)
        // Use sanitize_string or more specific validation if needed
        $display_name_sanitized = sanitize_string($formData['display_name']);


        // 4. Process if Validation Passed
        if (empty($errors)) {
            // Prepare data for API (only send required/optional fields)
            $userData = [
                'email' => $formData['email'],
                'password' => $password, // Send the original password
            ];
            if (!empty($display_name_sanitized)) {
                $userData['display_name'] = $display_name_sanitized;
            }
            if (validate_required($formData['phone_number'])) { // Only send if provided and validated
                $userData['phone_number'] = $formData['phone_number'];
            }

            // Call API Client for Registration
            $result = api_register_user($userData);

            // 5. Process API Response
            if ($result['success']) {
                // Registration successful via API
                // Set a flash message and redirect to login page
                // The API response might contain a specific message in $result['data']['message']
                $successMessage = $result['data']['message'] ?? 'Registration successful! Please check your email for verification.';
                set_flash_message('success', $successMessage);
                redirect('/login.php');
                exit;
            } else {
                // API registration failed
                $messageType = 'error';
                // Use the error message from the API if available, otherwise generic
                $messageText = $result['error_message'] ?? 'Registration failed. Please try again.';
                error_log("API Registration Failed: Status {$result['status_code']}, Message: {$result['error_message']}");
            }
        } else {
            // Validation failed, prepare error message(s)
            $messageType = 'error';
            // Combine errors into a single message or display individually (template needs modification)
            $messageText = 'Please correct the errors below: <ul>';
            foreach ($errors as $field => $error) {
                 $messageText .= '<li>' . escape_html($error) . '</li>';
            }
             $messageText .= '</ul>';
        }
    }
} // End of POST handling


// --- Render Page ---

// Include Header Template
// $pageTitle is already set
require_once __DIR__ . '/../includes/templates/header.php';

?>

<h1>Register</h1>

<?php
// Display Registration Error/Success Messages
if (!empty($messageText)) {
    // The variables $messageType and $messageText are already set
    require __DIR__ . '/../includes/templates/message.php';
}
?>

<?php
// Include Registration Form Template
// NOTE: To repopulate the form on error, the register_form.php template
// would need modification to accept and use the $formData array.
// For simplicity now, it will just show an empty form again after error.
require_once __DIR__ . '/../includes/templates/register_form.php';
?>

<?php
// Include Footer Template
require_once __DIR__ . '/../includes/templates/footer.php';
?>
