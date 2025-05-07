<?php
// header.php

// Detect current page for active navigation highlighting
$current_page_basename = basename($_SERVER['PHP_SELF']);

// Function to check if the current page matches a given page filename
function isActiveNav($page_filename) {
    global $current_page_basename;
    return ($current_page_basename == $page_filename);
}

// Default meta values
$page_title_default = "KDJ Lanka";
$page_description_default = "KDJ Lanka - Digital Solutions for Sri Lanka";
$site_lang_default = "si"; // Default language

// Page-specific overrides
$page_title = isset($title) ? htmlspecialchars($title) . " - KDJ Lanka" : htmlspecialchars($page_title_default);
$page_description = isset($description) ? htmlspecialchars($description) : htmlspecialchars($page_description_default);
$site_lang = isset($lang) ? htmlspecialchars($lang) : $site_lang_default;

// Construct the full URL for OG tags
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$og_image_url = $protocol . $_SERVER['HTTP_HOST'] . '/assets/images/kdj-og-image.jpg'; // Define your default OG image

// Determine if the current page is an authentication-related page
$auth_pages = ['index.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'verify-email.php', 'mfa.php'];
$is_auth_page = in_array($current_page_basename, $auth_pages);

// API Key for Firebase - Consider moving to a configuration file or environment variable for better security practice
// For client-side usage, ensure this key is restricted in Google Cloud Console (HTTP referrers, API restrictions).
$firebase_api_key = "AIzaSyCJFdKtU5AGhDpsTvhWCXh8AaoQ8M4Frt4"; // As provided

?>
<!DOCTYPE html>
<html lang="<?php echo $site_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">

    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon"> <?php // Ensure favicon.ico exists at this path ?>
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png"> <?php // Optional: Apple touch icon ?>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png"> <?php // Optional: 32x32 PNG favicon ?>
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png"> <?php // Optional: 16x16 PNG favicon ?>
    <link rel="manifest" href="/assets/site.webmanifest"> <?php // Optional: Web App Manifest ?>


    <meta name="robots" content="index, follow">
    <meta name="author" content="KDJ Lanka">
    <link rel="canonical" href="<?php echo htmlspecialchars($current_url); ?>" />

    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($current_url); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image_url); ?>">
    <meta property="og:site_name" content="KDJ Lanka">
    <?php if ($site_lang === 'si'): ?>
    <meta property="og:locale" content="si_LK">
    <?php elseif ($site_lang === 'ta'): ?>
    <meta property="og:locale" content="ta_LK">
    <?php else: ?>
    <meta property="og:locale" content="en_US">
    <?php endif; ?>


    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image_url); ?>">
    <?php // <meta name="twitter:site" content="@kdjlanka"> // Optional: Your Twitter handle ?>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'kdj-red': '#cb2127',
                        'kdj-dark': '#141a20', // Main dark color
                        'kdj-white': '#ffffff',
                        'kdj-gray': { // Example gray palette additions
                            100: '#f7fafc',
                            // ... other shades
                            900: '#1a202c',
                        }
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'], // Ensure Nunito is loaded
                    }
                }
            }
        }
    </script>

    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>
    <?php // Add other Firebase SDKs if needed, e.g., Firestore, Storage ?>

    <script>
      // Your web app's Firebase configuration
      const firebaseConfig = {
        apiKey: "<?php echo $firebase_api_key; ?>", // Using PHP variable
        authDomain: "kdj-lanka.firebaseapp.com",
        databaseURL: "https://kdj-lanka-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "kdj-lanka",
        storageBucket: "kdj-lanka.appspot.com",
        messagingSenderId: "812675960947",
        appId: "1:812675960947:web:bc57a1d19da73b9ac51a06",
        measurementId: "G-GGFCJZXE9T" // If using Google Analytics for Firebase
      };

      // Initialize Firebase
      let firebaseApp;
      let firebaseAuth;

      try {
        if (typeof firebase !== 'undefined' && typeof firebase.initializeApp === 'function') {
            firebaseApp = firebase.initializeApp(firebaseConfig);
            if (typeof firebase.auth === 'function') {
                firebaseAuth = firebase.auth(firebaseApp);
                // Set persistence to session (clears when window/tab closes) for Firebase Auth
                firebaseAuth.setPersistence(firebase.auth.Auth.Persistence.SESSION)
                  .then(() => {
                    console.log("Firebase Auth: Persistence set to SESSION.");
                  })
                  .catch((error) => {
                    console.error("Firebase Auth: Error setting auth persistence:", error.code, error.message);
                  });
            } else {
                 console.error("Firebase Auth SDK not found or initialized incorrectly.");
            }
            console.log("Firebase Initialized Successfully!");
        } else {
            console.error("Firebase SDK not loaded. Firebase features might be unavailable.");
        }
      } catch (e) {
        console.error("Error initializing Firebase:", e);
      }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            display: flex; /* Added for sticky footer */
            flex-direction: column; /* Added for sticky footer */
            min-height: 100vh; /* Added for sticky footer */
        }
        main.content { /* Ensure your main content area pushes footer down */
            flex-grow: 1;
        }

        /* Custom Loader (defined here for early availability, also in utils.js if needed dynamically) */
        .loader {
            border-top-color: #cb2127; /* KDJ Red */
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar (Webkit browsers) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cb2127; /* KDJ Red */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a41a1f; /* Darker KDJ Red */
        }
        /* Basic responsive text scaling (optional) */
        /* html { font-size: 14px; } @media (min-width: 768px) { html { font-size: 15px; } } @media (min-width: 1024px) { html { font-size: 16px; } } */
    </style>

    <?php
    // Page specific head elements (passed from individual PHP files)
    if (isset($additional_head) && !empty($additional_head)) {
        echo $additional_head;
    }
    ?>
</head>
<body class="bg-gray-100 text-kdj-dark antialiased">
    <div id="loadingIndicator" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-white bg-opacity-80 z-[100]" style="display: none;">
        <div class="loader h-16 w-16 border-4 border-gray-200 rounded-full"></div>
    </div>

    <?php if (!$is_auth_page): ?>
    <nav class="bg-kdj-dark text-white shadow-lg sticky top-0 z-50"> <?php // Added sticky top and z-index ?>
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16"> <?php // Set a fixed height for nav ?>
                <div class="flex space-x-4 items-center">
                    <div>
                        <a href="/" class="flex items-center py-4 px-2"> <?php // Link to homepage ?>
                            <img src="/assets/img/kdjcolorlogo.png" alt="KDJ Lanka Logo" class="h-8 mr-2"> <?php // Added logo image ?>
                            <span class="font-bold text-xl text-white">KDJ</span>
                            <span class="font-bold text-xl text-kdj-red">Lanka</span>
                        </a>
                    </div>
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="/dashboard.php" class="py-2 px-3 rounded-md text-sm font-medium <?php echo isActiveNav('dashboard.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition duration-300'; ?>">
                            Dashboard
                        </a>
                        <a href="/profile.php" class="py-2 px-3 rounded-md text-sm font-medium <?php echo isActiveNav('profile.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition duration-300'; ?>">
                            Profile
                        </a>
                        <a href="/settings.php" class="py-2 px-3 rounded-md text-sm font-medium <?php echo isActiveNav('settings.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition duration-300'; ?>">
                            Settings
                        </a>
                         <a href="/security.php" class="py-2 px-3 rounded-md text-sm font-medium <?php echo isActiveNav('security.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition duration-300'; ?>">
                            Security
                        </a>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <div id="userDropdown" class="relative group">
                        <button aria-haspopup="true" aria-expanded="false" class="py-2 px-4 rounded-md flex items-center space-x-2 bg-kdj-dark hover:bg-gray-700 focus:outline-none transition duration-300 text-sm font-medium text-gray-300 hover:text-white">
                            <i class="fas fa-user-circle mr-1"></i>
                            <span id="userDisplayName" class="truncate max-w-[100px]">User</span> <?php // Added truncate ?>
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden group-hover:block ring-1 ring-black ring-opacity-5">
                            <a href="/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-kdj-red transition duration-150">
                                <i class="fas fa-user mr-2 w-4"></i> Profile
                            </a>
                            <a href="/settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-kdj-red transition duration-150">
                                <i class="fas fa-cog mr-2 w-4"></i> Settings
                            </a>
                            <hr class="my-1 border-gray-200">
                            <button id="logoutBtn" class="w-full text-left block px-4 py-2 text-sm text-kdj-red hover:bg-gray-100 transition duration-150">
                                <i class="fas fa-sign-out-alt mr-2 w-4"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobileMenuBtn" aria-controls="mobileMenu" aria-expanded="false" class="mobile-menu-button p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenu" class="md:hidden hidden bg-kdj-dark border-t border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="/dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium <?php echo isActiveNav('dashboard.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">Dashboard</a>
                <a href="/profile.php" class="block px-3 py-2 rounded-md text-base font-medium <?php echo isActiveNav('profile.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">Profile</a>
                <a href="/settings.php" class="block px-3 py-2 rounded-md text-base font-medium <?php echo isActiveNav('settings.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">Settings</a>
                <a href="/security.php" class="block px-3 py-2 rounded-md text-base font-medium <?php echo isActiveNav('security.php') ? 'bg-kdj-red text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?>">Security</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle text-3xl text-gray-400"></i>
                    </div>
                    <div class="ml-3">
                        <div id="mobileUserDisplayName" class="text-base font-medium leading-none text-white">User Name</div>
                        <div id="mobileUserEmail" class="text-sm font-medium leading-none text-gray-400">user@example.com</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <button id="mobileLogoutBtn" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-kdj-red hover:bg-gray-700 hover:text-white">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <?php // The toast container placeholder is now in footer.php to ensure it's rendered after main content.
          // <div class="toast-container fixed top-4 right-4 z-50" id="toastContainer"></div>
    ?>