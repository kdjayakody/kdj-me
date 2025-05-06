<?php
// Detect current page for active navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Function to check if we're on a specific page
function isPage($page) {
    global $current_page;
    return ($current_page == $page);
}

// Default meta values
$page_title = "KDJ Lanka";
$page_description = "KDJ Lanka - Digital Solutions for Sri Lanka";

// Override title if set by page
if (isset($title)) {
    $page_title = $title . " - KDJ Lanka";
}

// Override description if set by page
if (isset($description)) {
    $page_description = $description;
}
?>
<!DOCTYPE html>
<html lang="<?php echo isset($lang) ? $lang : 'si'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    
    <!-- SEO Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="KDJ Lanka">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="/assets/images/kdj-og-image.jpg">
    <meta property="og:site_name" content="KDJ Lanka">
    
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'kdj-red': '#cb2127',
                        'kdj-dark': '#141a20',
                        'kdj-white': '#ffffff',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>

<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>

<script>
  const firebaseConfig = {
    apiKey: "AIzaSyCJFdKtU5AGhDpsTvhWCXh8AaoQ8M4Frt4",
    authDomain: "kdj-lanka.firebaseapp.com",
    databaseURL: "https://kdj-lanka-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "kdj-lanka",
    storageBucket: "kdj-lanka.appspot.com",
    messagingSenderId: "812675960947",
    appId: "1:812675960947:web:bc57a1d19da73b9ac51a06",
    measurementId: "G-GGFCJZXE9T"
  };


// Initialize Firebase
let firebaseApp;
  let firebaseAuth;
  try {
    // Initialize Firebase with the config
    firebaseApp = firebase.initializeApp(firebaseConfig);
    firebaseAuth = firebase.auth(); // compat version
    
    // Apply settings to the Auth instance
    firebaseAuth.useDeviceLanguage(); // Use browser language setting
    
    // Set persistence to match your application needs
    // 'local' - persists across browser sessions (default)
    // 'session' - only persists in current tab
    // 'none' - no persistence 
    firebaseAuth.setPersistence(firebase.auth.Auth.Persistence.LOCAL)
      .then(() => {
        console.log("Firebase Auth persistence set!");
      })
      .catch((error) => {
        console.error("Error setting auth persistence:", error);
      });
      
    console.log("Firebase Initialized Successfully!");
  } catch (e) {
    console.error("Error initializing Firebase:", e);
    
    // Check if this is due to Firebase being initialized twice
    if (e.code === 'app/duplicate-app') {
      console.log("Firebase already initialized, retrieving existing app");
      firebaseApp = firebase.app(); // Get the already initialized app
      firebaseAuth = firebase.auth();
    } else {
      // Create a function to show error message that can be called from anywhere
      window.showFirebaseError = function() {
        if (typeof showMessage === 'function') {
          showMessage('Google පිවිසුම ක්‍රියාත්මක කිරීමට නොහැක. කරුණාකර පසුව උත්සහ කරන්න.', 'error');
        } else {
          alert('Google sign-in is currently unavailable. Please try again later.');
        }
      }
    }
  }
</script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
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
    </style>
    
    <?php if (isset($additional_head)) echo $additional_head; ?>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-white bg-opacity-80 z-50" style="display: none;">
        <div class="loader h-16 w-16 border-4 border-gray-200 rounded-full"></div>
    </div>

    <!-- Main Navigation - Only show on non-auth pages -->
    <?php if (!in_array($current_page, ['index.php', 'register.php', 'forgot_password.php', 'reset_password.php'])): ?>
    <nav class="bg-kdj-dark text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-4">
                    <!-- Logo -->
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-bold text-xl text-white">KDJ</span>
                            <span class="font-bold text-xl text-kdj-red">Lanka</span>
                        </a>
                    </div>
                    <!-- Primary Nav -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="/dashboard.php" class="py-4 px-2 <?php echo isPage('dashboard.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>">
                            Dashboard
                        </a>
                        <a href="/profile.php" class="py-4 px-2 <?php echo isPage('profile.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>">
                            Profile
                        </a>
                        <a href="/settings.php" class="py-4 px-2 <?php echo isPage('settings.php') ? 'text-kdj-red border-b-2 border-kdj-red font-bold' : 'text-gray-300 hover:text-kdj-red transition duration-300'; ?>">
                            Settings
                        </a>
                    </div>
                </div>
                <!-- Secondary Nav -->
                <div class="hidden md:flex items-center space-x-4">
                    <div id="userDropdown" class="relative group">
                        <button class="py-2 px-4 rounded-md flex items-center space-x-2 bg-kdj-dark hover:bg-gray-700 focus:outline-none transition duration-300">
                            <span id="userDisplayName">User</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                            <a href="/profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <a href="/settings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <hr class="my-1">
                            <button id="logoutBtn" class="w-full text-left block px-4 py-2 text-kdj-red hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobileMenuBtn" class="mobile-menu-button focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden">
            <a href="/dashboard.php" class="block py-2 px-4 text-sm <?php echo isPage('dashboard.php') ? 'bg-gray-700 text-kdj-red' : 'hover:bg-gray-700'; ?>">Dashboard</a>
            <a href="/profile.php" class="block py-2 px-4 text-sm <?php echo isPage('profile.php') ? 'bg-gray-700 text-kdj-red' : 'hover:bg-gray-700'; ?>">Profile</a>
            <a href="/settings.php" class="block py-2 px-4 text-sm <?php echo isPage('settings.php') ? 'bg-gray-700 text-kdj-red' : 'hover:bg-gray-700'; ?>">Settings</a>
            <button id="mobileLogoutBtn" class="w-full text-left block py-2 px-4 text-sm text-kdj-red hover:bg-gray-700">Logout</button>
        </div>
    </nav>
    <?php endif; ?>

    <div class="toast-container fixed top-4 right-4 z-50" id="toastContainer"></div>
