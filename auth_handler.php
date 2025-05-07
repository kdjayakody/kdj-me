<?php
// Auth Handler - Used to handle cross-domain authentication
header('Content-Type: application/json');

// Define allowed origins for CORS (add all your subdomains here)
$allowed_origins = [
    'https://kdj.lk',
    'https://www.kdj.lk',
    'https://events.kdj.lk',
    'https://singlish.kdj.lk',
    'http://localhost',
    'http://localhost:8080'
];

// Get origin
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Set CORS headers if origin is allowed
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// API base URL
$apiBaseUrl = 'https://auth.kdj.lk/api/v1';

// Handle different requests
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'verify':
        // Verify auth token
        verifyToken();
        break;
    
    case 'refresh':
        // Refresh token
        refreshToken();
        break;

    case 'logout':
        // Logout
        logout();
        break;
        
    default:
        // Invalid action
        sendResponse(['success' => false, 'message' => 'Invalid action'], 400);
        break;
}

// Verify token
function verifyToken() {
    global $apiBaseUrl;
    
    // Get auth token from Authorization header
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    
    if (empty($authHeader)) {
        sendResponse(['success' => false, 'message' => 'No authorization token provided'], 401);
        return;
    }
    
    // Forward the request to the auth API
    $ch = curl_init("$apiBaseUrl/users/me");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $authHeader,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Forward the response
    if ($httpCode >= 200 && $httpCode < 300) {
        header('Content-Type: application/json');
        echo $response;
    } else {
        sendResponse(['success' => false, 'message' => 'Invalid or expired token'], $httpCode);
    }
}

// Refresh token
function refreshToken() {
    global $apiBaseUrl;
    
    // Get refresh token from request
    $input = json_decode(file_get_contents('php://input'), true);
    $refreshToken = isset($input['refresh_token']) ? $input['refresh_token'] : '';
    
    if (empty($refreshToken)) {
        sendResponse(['success' => false, 'message' => 'No refresh token provided'], 400);
        return;
    }
    
    // Forward the request to the auth API
    $ch = curl_init("$apiBaseUrl/auth/refresh-token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['refresh_token' => $refreshToken]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Forward the response
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo $response;
}

// Logout
function logout() {
    global $apiBaseUrl;
    
    // Get auth token from Authorization header
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    
    // Forward the request to the auth API
    $ch = curl_init("$apiBaseUrl/auth/logout");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $requestHeaders = ['Accept: application/json'];
    if (!empty($authHeader)) {
        $requestHeaders[] = 'Authorization: ' . $authHeader;
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Return response
    sendResponse(['success' => true, 'message' => 'Logged out successfully'], 200);
}

// Helper function to send JSON response
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}