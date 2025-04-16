<?php
// api_client.php

/**
 * Makes a request to the backend API.
 *
 * This is a private helper function used by other functions in this file
 * to interact with the API endpoints. It handles setting up the cURL request,
 * sending it, and processing the response.
 *
 * @param string $method The HTTP method (e.g., 'GET', 'POST').
 * @param string $endpoint The API endpoint path (e.g., '/users/register').
 * @param array|null $data The data to send with the request (for POST, PUT, etc.).
 * @param array $headers Optional additional headers for the request.
 * @return array An associative array containing 'status' (HTTP status code) and 'body' (decoded JSON response or error message).
 */
function _make_api_request(string $method, string $endpoint, ?array $data = null, array $headers = []): array {
    // Construct the full URL for the API endpoint.
    // It ensures there's exactly one slash between the base URL and the endpoint.
    $url = rtrim(API_BASE_URL, '/') . '/' . ltrim($endpoint, '/');

    // --- DEBUGGING START ---
    // Temporarily log the exact URL being used for the API call.
    // Check your PHP error log for this message.
    error_log('DEBUG: Making API request (' . $method . ') to: ' . $url);
    if ($data) {
         error_log('DEBUG: Request Data: ' . json_encode($data));
    }
    // --- DEBUGGING END ---


    // Initialize cURL session
    $ch = curl_init();

    // Default headers for JSON content
    $default_headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    // Merge default headers with any custom headers provided
    $final_headers = array_merge($default_headers, $headers);

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url); // Set the request URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string instead of outputting it
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); // Set the HTTP method
    curl_setopt($ch, CURLOPT_HTTPHEADER, $final_headers); // Set the request headers
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set a request timeout (30 seconds)
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Set a connection timeout (10 seconds)

    // If data is provided, encode it as JSON and set it as the request body
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    // Execute the cURL request
    $response = curl_exec($ch);
    // Get the HTTP status code of the response
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // Check for cURL errors during the request execution
    $curl_error = curl_error($ch);

    // Close the cURL session
    curl_close($ch);

    // Handle cURL errors
    if ($curl_error) {
        // Log the cURL error
        error_log("cURL Error for $method $url: " . $curl_error);
        // Return an error structure indicating a connection failure
        return ['status' => 503, 'body' => ['error' => 'API connection failed', 'details' => $curl_error]]; // 503 Service Unavailable
    }

    // Decode the JSON response body
    $body = json_decode($response, true);

    // Handle JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Log the decoding error and the raw response
        error_log("JSON Decode Error for $method $url: " . json_last_error_msg() . ". Response: " . $response);
        // Return an error structure indicating an invalid response from the API
        return ['status' => 502, 'body' => ['error' => 'Invalid API response', 'details' => json_last_error_msg()]]; // 502 Bad Gateway
    }

    // Return the status code and the decoded response body
    return ['status' => $http_status, 'body' => $body];
}

// --- Public API Client Functions ---
// These functions provide a simpler interface for common API interactions.

/**
 * Registers a new user via the API.
 *
 * @param string $username The desired username.
 * @param string $email The user's email address.
 * @param string $password The user's chosen password.
 * @return array The API response ('status' and 'body').
 */
function api_register_user(string $username, string $email, string $password): array {
    return _make_api_request('POST', '/users/register', [
        'username' => $username,
        'email' => $email,
        'password' => $password
    ]);
}

/**
 * Logs in a user via the API.
 *
 * @param string $email The user's email address.
 * @param string $password The user's password.
 * @param bool $rememberMe Whether to implement a "remember me" functionality.
 * @return array An associative array with login result details:
 *               - 'success' (bool): Whether login was successful
 *               - 'data' (array|null): User and token data if successful
 *               - 'error_message' (string|null): Error message if login failed
 *               - 'status_code' (int): HTTP status code of the response
 */
function api_login_user(string $email, string $password, bool $rememberMe = false): array {
    try {
        // Log the attempt for debugging
        error_log("Attempting login for email: $email");

        // Prepare login data
        $loginData = [
            'email' => $email,
            'password' => $password,
            'remember_me' => $rememberMe
        ];

        // Make the API request
        $response = _make_api_request('POST', '/users/login', $loginData);

        // Log the full response for debugging
        error_log("Login API Response: " . json_encode($response));

        // Check if the response has the expected structure
        if (!isset($response['status']) || !isset($response['body'])) {
            error_log("Unexpected API response structure");
            return [
                'success' => false,
                'error_message' => 'Unexpected server response',
                'status_code' => 500,
                'data' => null
            ];
        }

        // Handle different response scenarios
        $httpStatus = $response['status'];
        $responseBody = $response['body'];

        // Successful login
        if ($httpStatus >= 200 && $httpStatus < 300) {
            // Check for required login data
            if (!isset($responseBody['access_token']) || !isset($responseBody['user'])) {
                error_log("Missing access token or user data in login response");
                return [
                    'success' => false,
                    'error_message' => 'Invalid login response from server',
                    'status_code' => $httpStatus,
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'access_token' => $responseBody['access_token'],
                    'refresh_token' => $responseBody['refresh_token'] ?? null,
                    'expires_in' => $responseBody['expires_in'] ?? 3600,
                    'user_id' => $responseBody['user']['id'] ?? null,
                    'email' => $responseBody['user']['email'] ?? null,
                    'display_name' => $responseBody['user']['display_name'] ?? null,
                ],
                'error_message' => null,
                'status_code' => $httpStatus
            ];
        }

        // Handle login failure
        return [
            'success' => false,
            'error_message' => $responseBody['message'] ?? 'Login failed',
            'status_code' => $httpStatus,
            'data' => null
        ];

    } catch (Exception $e) {
        // Log any unexpected errors
        error_log("Unexpected error during login: " . $e->getMessage());
        return [
            'success' => false,
            'error_message' => 'An unexpected error occurred',
            'status_code' => 500,
            'data' => null
        ];
    }
}
/**
 * Verifies a user's email address using a token.
 *
 * @param string $token The email verification token.
 * @return array The API response ('status' and 'body').
 */
function api_verify_email(string $token): array {
    return _make_api_request('GET', '/users/verify-email/' . urlencode($token));
}

/**
 * Requests a password reset email for a user.
 *
 * @param string $email The email address of the user requesting the reset.
 * @return array The API response ('status' and 'body').
 */
function api_forgot_password(string $email): array {
    return _make_api_request('POST', '/users/forgot-password', ['email' => $email]);
}

/**
 * Resets a user's password using a reset token.
 *
 * @param string $token The password reset token.
 * @param string $new_password The new password chosen by the user.
 * @return array The API response ('status' and 'body').
 */
function api_reset_password(string $token, string $new_password): array {
    return _make_api_request('POST', '/users/reset-password', [
        'token' => $token,
        'password' => $new_password
    ]);
}

/**
 * Changes the password for an authenticated user.
 *
 * @param string $current_password The user's current password.
 * @param string $new_password The desired new password.
 * @param string $auth_token The user's authentication token (JWT).
 * @return array The API response ('status' and 'body').
 */
function api_change_password(string $current_password, string $new_password, string $auth_token): array {
    return _make_api_request('POST', '/users/change-password', [
        'current_password' => $current_password,
        'new_password' => $new_password
    ], ['Authorization: Bearer ' . $auth_token]); // Send auth token in header
}

/**
 * Fetches the profile data for the authenticated user.
 *
 * @param string $auth_token The user's authentication token (JWT).
 * @return array The API response ('status' and 'body'). Expected body includes user details.
 */
function api_get_profile(string $auth_token): array {
    return _make_api_request('GET', '/users/profile', null, ['Authorization: Bearer ' . $auth_token]);
}

/**
 * Updates the profile data for the authenticated user.
 *
 * @param array $profile_data Associative array of data to update (e.g., ['username' => 'new_name']).
 * @param string $auth_token The user's authentication token (JWT).
 * @return array The API response ('status' and 'body').
 */
function api_update_profile(array $profile_data, string $auth_token): array {
    return _make_api_request('PUT', '/users/profile', $profile_data, ['Authorization: Bearer ' . $auth_token]);
}

/**
 * Initiates the MFA setup process for the authenticated user.
 *
 * @param string $auth_token The user's authentication token (JWT).
 * @return array The API response ('status' and 'body'). Expected body includes 'secret' and 'qr_code_url'.
 */
function api_setup_mfa(string $auth_token): array {
    return _make_api_request('POST', '/mfa/setup', null, ['Authorization: Bearer ' . $auth_token]);
}

/**
 * Verifies and enables MFA for the authenticated user.
 *
 * @param string $mfa_code The 6-digit code from the authenticator app.
 * @param string $auth_token The user's authentication token (JWT).
 * @return array The API response ('status' and 'body').
 */
function api_verify_mfa_setup(string $mfa_code, string $auth_token): array {
    return _make_api_request('POST', '/mfa/verify', ['mfa_code' => $mfa_code], ['Authorization: Bearer ' . $auth_token]);
}

/**
 * Verifies an MFA code during login.
 *
 * @param int $user_id The ID of the user attempting to log in.
 * @param string $mfa_code The 6-digit code from the authenticator app.
 * @return array The API response ('status' and 'body'). Expected body includes 'token' and 'user' on success.
 */
function api_verify_mfa_login(int $user_id, string $mfa_code): array {
    // Note: This endpoint might not require a bearer token initially,
    // as it's part of the login flow *before* the final token is issued.
    // Adjust if your API requires a temporary token or different mechanism here.
    return _make_api_request('POST', '/mfa/login-verify', [
        'user_id' => $user_id,
        'mfa_code' => $mfa_code
    ]);
}

/**
 * Disables MFA for the authenticated user.
 * Requires current password for confirmation.
 *
 * @param string $password The user's current password.
 * @param string $auth_token The user's authentication token (JWT).
 * @return array The API response ('status' and 'body').
 */
function api_disable_mfa(string $password, string $auth_token): array {
    return _make_api_request('POST', '/mfa/disable', ['password' => $password], ['Authorization: Bearer ' . $auth_token]);
}

?>
