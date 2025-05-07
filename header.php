<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session parameters
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Lax');
    
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include configuration file
require_once 'config.php';

// Load environment-specific variables
$env = getenv('APP_ENV') ?: 'production';
$config = require_once "config/{$env}.php";

// Import utility functions
require_once 'includes/functions.php';

// Detect current page for active navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Function to check if we're on a specific page
function isPage($page) {
    global $current_page;
    return ($current_page == $page);
}

// Check if the user is authenticated via API token
function isAuthenticated() {
    return isset($_SESSION['auth_token']) && !empty($_SESSION['auth_token']);
}

// Handle user's preferred language
if (isset($_COOKIE['user_lang'])) {
    $user_lang = sanitize_input($_COOKIE['user_lang']);
} else {
    $user_lang = isset($lang) ? $lang : 'si';
}

// Check if user has preferred theme
$preferred_theme = isset($_COOKIE['theme']) ? sanitize_input($_COOKIE['theme']) : 'light';

// Default meta values
$page_title = "KDJ Lanka";
$page_description = "KDJ Lanka - Digital Solutions for Sri Lanka";

// Override title if set by page
if (isset($title)) {
    $page_title = htmlspecialchars($title) . " - KDJ Lanka";
}

// Override description if set by page
if (isset($description)) {
    $page_description = htmlspecialchars($description);
}

// Determine canonical URL
$canonical_url = 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Load language strings
$lang_path = "lang/{$user_lang}.php";
$lang_strings = file_exists($lang_path) ? include($lang_path) : include('lang/en.php');

// Translation function
function t($key, $placeholders = []) {
    global $lang_strings;
    $text = isset($lang_strings[$key]) ? $lang_strings[$key] : $key;
    
    // Replace placeholders
    foreach ($placeholders as $placeholder => $value) {
        $text = str_replace('{' . $placeholder . '}', htmlspecialchars($value), $text);
    }
    
    return $text;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $user_lang; ?>" class="<?php echo $preferred_theme === 'dark' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    
    <!-- Security headers -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.gstatic.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://*.kdj.lk; connect-src 'self' https://*.kdj.lk https://auth.kdj.lk; frame-src https://accounts.google.com">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="Permissions-Policy" content="camera=(), microphone=(), geolocation=(), payment=()">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
    
    <!-- PWA manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#cb2127">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- SEO Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="KDJ Lanka">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:image" content="/assets/images/og-image.jpg">
    <meta property="og:site_name" content="KDJ Lanka">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="/assets/images/twitter-image.jpg">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'kdj-red': '#cb2127',
                        'kdj-dark': '#141a20',
                        'kdj-white': '#ffffff',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            },
            variants: {
                extend: {
                    backgroundColor: ['dark', 'dark:hover'],
                    textColor: ['dark', 'dark:hover'],
                    borderColor: ['dark', 'dark:hover'],
                }
            },
            plugins: [],
        }
    </script>

    <!-- Firebase initialization - Only included on auth pages -->
    <?php if (in_array($current_page, ['index.php', 'register.php', 'login.php'])): ?>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js" defer></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js" defer></script>
    <script>
        // Firebase configuration is loaded from a secure config file
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/auth/firebase-config')
                .then(response => response.json())
                .then(firebaseConfig => {
                    // Initialize Firebase with config
                    try {
                        let firebaseApp = firebase.initializeApp(firebaseConfig);
                        let firebaseAuth = firebase.auth(firebaseApp);
                        
                        // Set persistence to session (clears when window/tab closes)
                        firebaseAuth.setPersistence(firebase.auth.Auth.Persistence.SESSION)
                            .then(() => {
                                console.log("Firebase Auth initialized successfully");
                            })
                            .catch(error => {
                                console.error("Error setting auth persistence:", error);
                            });
                    } catch (e) {
                        console.error("Error initializing Firebase:", e);
                    }
                })
                .catch(error => {
                    console.error("Failed to load Firebase configuration:", error);
                });
        });
    </script>
    <?php endif; ?>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Base Custom CSS -->
    <style>
        /* Base styles */
        body {
            font-family: 'Nunito', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Dark mode styles */
        .dark body {
            background-color: #1a202c;
            color: #f7fafc;
        }
        
        .dark .bg-white {
            background-color: #2d3748;
        }
        
        .dark .text-gray-700, 
        .dark .text-gray-800, 
        .dark .text-gray-900 {
            color: #e2e8f0;
        }
        
        .dark .text-gray-500, 
        .dark .text-gray-600 {
            color: #a0aec0;
        }
        
        .dark .bg-gray-100 {
            background-color: #2d3748;
        }
        
        .dark .bg-gray-50 {
            background-color: #374151;
        }
        
        .dark .border-gray-200, 
        .dark .border-gray-300 {
            border-color: #4a5568;
        }
        
        /* Custom Loader */
        .loader {
            border-top-color: #cb2127;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Accessibility improvements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
        
        /* Focus visible styles for accessibility */
        :focus-visible {
            outline: 2px solid #cb2127;
            outline-offset: 2px;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cb2127;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a41a1f;
        }
        
        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #2d3748;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #cb2127;
        }
        
        /* Toast animations */
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); }
            to { transform: translateX(100%); }
        }
        
        .toast-slide-in {
            animation: slideIn 0.3s forwards;
        }
        
        .toast-slide-out {
            animation: slideOut 0.3s forwards;
        }
    </style>
    
    <?php if (isset($additional_head)) echo $additional_head; ?>
</head>
<body class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:p-4 focus:bg-white focus:text-kdj-red focus:z-50">
        <?php echo t('skip_to_content'); ?>
    </a>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-white dark:bg-gray-900 bg-opacity-80 dark:bg-opacity-80 z-50" style="display: none;">
        <div class="flex flex-col items-center">
            <div class="loader h-16 w-16 border-4 border-gray-200 dark:border-gray-700 rounded-full"></div>
            <p class="mt-4 text-gray-700 dark:text-gray-300 text-lg font-medium" id="loadingMessage"><?php echo t('loading'); ?></p>
        </div>
    </div>

    <!-- Main Navigation - Only show on non-auth pages -->
    <?php if (!in_array($current_page, ['index.php', 'login.php', 'register.php', 'forgot_password.php', 'reset_password.php'])): ?>
    <nav class="bg-kdj-dark text-white shadow-lg sticky top-0 z-40 transition-all duration-200">
        <div class="container mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-4">
                    <!-- Logo -->
                    <div>
                        <a href="/" class="flex items-center py-4 px-2" aria-label="KDJ Lanka Home">
                            <span class="font-bold text-xl text-white">KDJ</span>
                            <span class="font-bold text-xl text-kdj-red">Lanka</span>
                        </a>
                    </div>
                    <!-- Primary Nav -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="/dashboard.php" class="py-4 px-2 <?php echo isPage('dashboard.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>" aria-current="<?php echo isPage('dashboard.php') ? 'page' : 'false'; ?>">
                            <?php echo t('dashboard'); ?>
                        </a>
                        <a href="/profile.php" class="py-4 px-2 <?php echo isPage('profile.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>" aria-current="<?php echo isPage('profile.php') ? 'page' : 'false'; ?>">
                            <?php echo t('profile'); ?>
                        </a>
                        <a href="/settings.php" class="py-4 px-2 <?php echo isPage('settings.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>" aria-current="<?php echo isPage('settings.php') ? 'page' : 'false'; ?>">
                            <?php echo t('settings'); ?>
                        </a>
                        <a href="/security.php" class="py-4 px-2 <?php echo isPage('security.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>" aria-current="<?php echo isPage('security.php') ? 'page' : 'false'; ?>">
                            <?php echo t('security'); ?>
                        </a>
                    </div>
                </div>
                <!-- Secondary Nav -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Theme toggle -->
                    <button id="themeToggle" class="p-2 rounded-full hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-600" aria-label="<?php echo t('toggle_theme'); ?>">
                        <i class="fas fa-sun text-yellow-300 dark:hidden"></i>
                        <i class="fas fa-moon text-blue-300 hidden dark:inline"></i>
                    </button>
                    
                    <!-- Language selector -->
                    <div class="relative group">
                        <button class="p-2 rounded-full hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-600" aria-label="<?php echo t('change_language'); ?>" aria-expanded="false" aria-controls="languageDropdown">
                            <i class="fas fa-globe"></i>
                        </button>
                        <div id="languageDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block" role="menu">
                            <a href="?lang=si" class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem" <?php echo $user_lang === 'si' ? 'aria-current="true"' : ''; ?>>
                                <span class="flag-icon">🇱🇰</span> සිංහල (Sinhala)
                            </a>
                            <a href="?lang=en" class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem" <?php echo $user_lang === 'en' ? 'aria-current="true"' : ''; ?>>
                                <span class="flag-icon">🇬🇧</span> English
                            </a>
                            <a href="?lang=ta" class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem" <?php echo $user_lang === 'ta' ? 'aria-current="true"' : ''; ?>>
                                <span class="flag-icon">🇱🇰</span> தமிழ் (Tamil)
                            </a>
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="relative group">
                        <button class="p-2 rounded-full hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-600 relative" aria-label="<?php echo t('notifications'); ?>" aria-expanded="false" aria-controls="notificationsDropdown">
                            <i class="fas fa-bell"></i>
                            <span id="notificationBadge" class="absolute top-0 right-0 inline-block w-3 h-3 bg-kdj-red rounded-full transform translate-x-1 -translate-y-1 hidden"></span>
                        </button>
                        <div id="notificationsDropdown" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block" role="menu">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo t('notifications'); ?></h3>
                            </div>
                            <div id="notificationsList" class="max-h-60 overflow-y-auto">
                                <div class="py-2 px-4 text-sm text-gray-600 dark:text-gray-400"><?php echo t('no_notifications'); ?></div>
                            </div>
                            <a href="/notifications.php" class="block text-center px-4 py-2 text-sm text-kdj-red hover:bg-gray-100 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
                                <?php echo t('view_all_notifications'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- User dropdown -->
                    <div id="userDropdown" class="relative group">
                        <button class="py-2 px-4 rounded-md flex items-center space-x-2 bg-kdj-dark hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 transition duration-300" aria-expanded="false" aria-controls="userMenu">
                            <span id="userDisplayName"><?php echo t('user'); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block" role="menu">
                            <a href="/profile.php" class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-user mr-2"></i> <?php echo t('profile'); ?>
                            </a>
                            <a href="/settings.php" class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-cog mr-2"></i> <?php echo t('settings'); ?>
                            </a>
                            <a href="/sessions.php" class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-history mr-2"></i> <?php echo t('active_sessions'); ?>
                            </a>
                            <hr class="my-1 border-gray-200 dark:border-gray-700">
                            <button id="logoutBtn" class="w-full text-left block px-4 py-2 text-kdj-red hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-sign-out-alt mr-2"></i> <?php echo t('logout'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobileMenuBtn" class="mobile-menu-button focus:outline-none focus:ring-2 focus:ring-gray-600" aria-expanded="false" aria-controls="mobileMenu" aria-label="<?php echo t('toggle_menu'); ?>">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden transition-all duration-200 animate-fade-in" role="menu">
            <a href="/dashboard.php" class="block py-2 px-4 text-sm <?php echo isPage('dashboard.php') ? 'bg-gray-700 text-kdj-red' : 'text-gray-200 hover:bg-gray-700'; ?>" role="menuitem" aria-current="<?php echo isPage('dashboard.php') ? 'page' : 'false'; ?>">
                <i class="fas fa-tachometer-alt mr-2"></i> <?php echo t('dashboard'); ?>
            </a>
            <a href="/profile.php" class="block py-2 px-4 text-sm <?php echo isPage('profile.php') ? 'bg-gray-700 text-kdj-red' : 'text-gray-200 hover:bg-gray-700'; ?>" role="menuitem" aria-current="<?php echo isPage('profile.php') ? 'page' : 'false'; ?>">
                <i class="fas fa-user mr-2"></i> <?php echo t('profile'); ?>
            </a>
            <a href="/settings.php" class="block py-2 px-4 text-sm <?php echo isPage('settings.php') ? 'bg-gray-700 text-kdj-red' : 'text-gray-200 hover:bg-gray-700'; ?>" role="menuitem" aria-current="<?php echo isPage('settings.php') ? 'page' : 'false'; ?>">
                <i class="fas fa-cog mr-2"></i> <?php echo t('settings'); ?>
            </a>
            <a href="/security.php" class="block py-2 px-4 text-sm <?php echo isPage('security.php') ? 'bg-gray-700 text-kdj-red' : 'text-gray-200 hover:bg-gray-700'; ?>" role="menuitem" aria-current="<?php echo isPage('security.php') ? 'page' : 'false'; ?>">
                <i class="fas fa-shield-alt mr-2"></i> <?php echo t('security'); ?>
            </a>
            
            <div class="border-t border-gray-700 py-2">
                <div class="flex justify-between items-center px-4 py-2">
                    <!-- Theme toggle mobile -->
                    <button id="mobileThemeToggle" class="text-sm text-gray-200 flex items-center" aria-label="<?php echo t('toggle_theme'); ?>">
                        <i class="fas fa-sun text-yellow-300 dark:hidden mr-2"></i>
                        <i class="fas fa-moon text-blue-300 hidden dark:inline mr-2"></i>
                        <span class="dark:hidden"><?php echo t('dark_mode'); ?></span>
                        <span class="hidden dark:inline"><?php echo t('light_mode'); ?></span>
                    </button>
                    
                    <!-- Language selector mobile -->
                    <div class="relative">
                        <button id="mobileLangBtn" class="text-sm text-gray-200 flex items-center" aria-expanded="false" aria-controls="mobileLangMenu">
                            <i class="fas fa-globe mr-2"></i>
                            <span>
                                <?php 
                                    switch($user_lang) {
                                        case 'si': echo 'සිංහල'; break;
                                        case 'ta': echo 'தமிழ்'; break;
                                        default: echo 'English';
                                    }
                                ?>
                            </span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div id="mobileLangMenu" class="hidden absolute left-0 mt-2 py-2 w-40 bg-gray-800 rounded-md shadow-lg" role="menu">
                            <a href="?lang=si" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem" <?php echo $user_lang === 'si' ? 'aria-current="true"' : ''; ?>>
                                <span class="flag-icon">🇱🇰</span> සිංහල (Sinhala)
                            </a>
                            <a href="?lang=en" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem" <?php echo $user_lang === 'en' ? 'aria-current="true"' : ''; ?>>
                                <span class="flag-icon">🇬🇧</span> English
                            </a>
                            <a href="?lang=ta" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem" <?php echo $user_lang === 'ta' ? 'aria-current="true"' : ''; ?>>
                                <span class="flag-icon">🇱🇰</span> தமிழ் (Tamil)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 py-2">
                <button id="mobileLogoutBtn" class="w-full text-left block py-2 px-4 text-sm text-kdj-red hover:bg-gray-700" role="menuitem">
                    <i class="fas fa-sign-out-alt mr-2"></i> <?php echo t('logout'); ?>
                </button>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Toast container -->
    <div class="toast-container fixed top-4 right-4 z-50" id="toastContainer"></div>
    
    <!-- Main content -->
    <main id="main-content" class="flex-grow" role="main">