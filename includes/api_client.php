<?php
/**
 * API Client Functions
 *
 * Functions for interacting with the kdj-auth Python backend API.
 * Uses PHP's cURL extension.
 * Assumes config.php and session_manager.php are included.
 *
 * NOTE: Using a dedicated HTTP client library like GuzzleHttp (via Composer)
 * is generally recommended for more complex scenarios as it simplifies request
 * building, response handling, and error management.
 */

/**
 * Core function to make requests to the API.
 *
 * @param string $method HTTP method (GET, POST, PUT, DELETE).
 * @param string $endpoint API endpoint path (e.g., '/auth/login').
 * @param array|null $data Data payload for POST/PUT requests.
 * @param bool $sendAuthToken Whether to include the Authorization header.
 * @return array ['success' => bool, 'status_code' => int, 'data' => array|null, 'error_message' => string|null]
 */
function _make_api_request(string $method, string $endpoint, ?array $data = null, bool $sendAuthToken = false): array
{
    // Ensure API_BASE_URL is defined
    if (!defined('API_BASE_URL')) {
        error_log('CRITICAL: API_BASE_URL constant not defined.');
        return ['success' => false, 'status_code' => 0, 'data' => null, 'error_message' => 'API client configuration error.'];
    }

    $url = rtrim(API_BASE_URL, '/') . '/' . ltrim($endpoint, '/');
    $method = strtoupper($method);

    $ch = curl_init();
    if ($ch === false) {
        error_log('CRITICAL: Failed to initialize cURL.');
        return ['success' => false, 'status_code' => 0, 'data' => null, 'error_message' => 'cURL initialization failed.'];
    }

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];

    // Add Authorization header if required and token exists
    if ($sendAuthToken) {
        $token = get_auth_token(); // From session_manager.php
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        } else {
            // Optionally handle cases where auth is required but token is missing
             error_log("Warning: Auth token required for {$method} {$endpoint} but not found in session.");
             // Depending on the endpoint, you might return an error here or let the API handle it
        }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as string
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); // Set HTTP method
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Request timeout 30 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Connection timeout 10 seconds
    curl_setopt($ch, CURLOPT_FAILONERROR, false); // Get response body even on 4xx/5xx errors

    // --- Production HTTPS Settings ---
    // These should generally be TRUE in production for security.
    // If you encounter SSL issues, investigate server/certificate config, avoid disabling these.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    // Optional: Specify CA bundle path if needed
    // curl_setopt($ch, CURLOPT_CAINFO, '/path/to/cacert.pem');

    // Add request body for POST/PUT
    if (($method === 'POST' || $method === 'PUT') && $data !== null) {
        $jsonData = json_encode($data);
        if ($jsonData === false) {
             error_log('Error: Failed to encode JSON data for API request.');
             curl_close($ch);
             return ['success' => false, 'status_code' => 0, 'data' => null, 'error_message' => 'Failed to encode request data.'];
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    } elseif ($method !== 'GET' && $method !== 'DELETE' && $data !== null) {
        // Handle other methods if necessary, or log warning
        error_log("Warning: Data provided for unsupported HTTP method '{$method}' in _make_api_request.");
    }


    $responseBody = curl_exec($ch);
    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrorNo = curl_errno($ch);
    $curlError = curl_error($ch);

    curl_close($ch);

    // Handle cURL execution errors (network issues, SSL problems, etc.)
    if ($curlErrorNo !== CURLE_OK) {
        error_log("cURL Error ({$curlErrorNo}) for {$method} {$url}: {$curlError}");
        return ['success' => false, 'status_code' => 0, 'data' => null, 'error_message' => "Network or connection error communicating with API: {$curlError}"];
    }

    // Decode the JSON response body
    $responseData = null;
    if ($responseBody) {
        $responseData = json_decode($responseBody, true); // true for associative array
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error decoding JSON response from {$method} {$url}. Status: {$httpStatusCode}. Response: " . substr($responseBody, 0, 500));
            // Return success based on HTTP code, but indicate data decoding issue
            return [
                'success' => ($httpStatusCode >= 200 && $httpStatusCode < 300),
                'status_code' => $httpStatusCode,
                'data' => null, // Indicate data is unusable
                'error_message' => 'Invalid JSON response received from API.'
            ];
        }
    }

    // Determine success based on HTTP status code (2xx range)
    $isSuccess = ($httpStatusCode >= 200 && $httpStatusCode < 300);

    // Extract API's error detail if available and request failed
    $apiErrorMessage = null;
    if (!$isSuccess && isset($responseData['detail'])) {
        // Handle cases where detail might be an array (like validation errors)
        if (is_array($responseData['detail'])) {
             // Attempt to format validation errors nicely
             $errorMessages = [];
             foreach($responseData['detail'] as $errorItem) {
                 if (isset($errorItem['msg']) && isset($errorItem['loc'])) {
                      $location = implode(' -> ', $errorItem['loc']);
                      $errorMessages[] = "{$location}: {$errorItem['msg']}";
                 } elseif (is_string($errorItem)) {
                     $errorMessages[] = $errorItem;
                 }
             }
             $apiErrorMessage = implode('; ', $errorMessages);
             // Fallback if formatting fails
             if (empty($apiErrorMessage)) {
                 $apiErrorMessage = json_encode($responseData['detail']);
             }

        } elseif (is_string($responseData['detail'])) {
            $apiErrorMessage = $responseData['detail'];
        }
    } elseif (!$isSuccess && $responseBody && empty($responseData)) {
        // Handle cases where the error response wasn't valid JSON but contained text
        $apiErrorMessage = "API returned status {$httpStatusCode}. Response: " . substr(strip_tags($responseBody), 0, 200);
    } elseif (!$isSuccess && empty($apiErrorMessage)) {
         $apiErrorMessage = "API request failed with status code {$httpStatusCode}.";
    }


    return [
        'success' => $isSuccess,
        'status_code' => $httpStatusCode,
        'data' => $responseData,
        'error_message' => $apiErrorMessage // Contains API 'detail' or generic message on failure
    ];
}

// --- Public API Functions ---

/** Register a new user */
function api_register_user(array $userData): array {
    // Expects ['email' => ..., 'password' => ..., 'display_name' => (optional), 'phone_number' => (optional)]
    return _make_api_request('POST', '/auth/register', $userData);
}

/** Login user */
function api_login_user(string $email, string $password, bool $rememberMe = false): array {
    return _make_api_request('POST', '/auth/login', [
        'email' => $email,
        'password' => $password,
        'remember_me' => $rememberMe
    ]);
}

/** Logout user (requires valid token) */
function api_logout_user(): array {
    // The API endpoint might not strictly require data, but needs the auth token
    return _make_api_request('POST', '/auth/logout', null, true);
}

/** Refresh access token */
function api_refresh_token(string $refreshToken): array {
    return _make_api_request('POST', '/auth/refresh-token', ['refresh_token' => $refreshToken]);
}

/** Request password reset email */
function api_request_password_reset(string $email): array {
    return _make_api_request('POST', '/auth/reset-password/request', ['email' => $email]);
}

/** Confirm password reset using token */
function api_confirm_password_reset(string $token, string $newPassword): array {
    // NOTE: Your Python API currently returns a placeholder for this endpoint.
    // Adjust expected response/logic when backend is implemented.
    return _make_api_request('POST', '/auth/reset-password/confirm', [
        'token' => $token,
        'new_password' => $newPassword
    ]);
}

/** Get current user's profile (requires valid token) */
function api_get_user_profile(): array {
    return _make_api_request('GET', '/users/me', null, true);
}

/** Update current user's profile (requires valid token) */
function api_update_user_profile(array $updateData): array {
    // Expects ['display_name' => (optional), 'phone_number' => (optional)]
    return _make_api_request('PUT', '/users/me', $updateData, true);
}

/** Update current user's password (requires valid token) */
function api_update_password(string $currentPassword, string $newPassword): array {
    // NOTE: Your Python API expects 'current_password' but doesn't seem to use it for validation yet.
    // The dependency uses get_current_verified_user, implying the user is already authenticated.
    // Sending current_password anyway for potential future use or if backend logic changes.
    return _make_api_request('PUT', '/users/me/password', [
        'current_password' => $currentPassword, // May not be used by current backend logic
        'new_password' => $newPassword
    ], true);
}

/** Delete current user's account (requires valid token) */
function api_delete_account(): array {
    return _make_api_request('DELETE', '/users/me', null, true);
}

/** Initiate MFA setup (requires valid token) */
function api_setup_mfa(): array {
    return _make_api_request('POST', '/auth/mfa/setup', null, true);
}

/** Verify MFA code (requires valid token) */
function api_verify_mfa(string $code, string $method = 'totp'): array {
    // Method can be 'totp' or 'backup'
    return _make_api_request('POST', '/auth/mfa/verify', [
        'code' => $code,
        'method' => $method
    ], true);
}

/** Disable MFA (requires valid token) */
function api_disable_mfa(): array {
    return _make_api_request('POST', '/auth/mfa/disable', null, true);
}

// Add functions for other API endpoints as needed.

?>
