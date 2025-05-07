<?php
// Set page specific variables
$title = "Page Not Found";
$description = "The page you are looking for does not exist";
$lang = "si";

// Add page specific scripts/styles
$additional_head = <<<HTML
<style>
    .error-container {
        background-image: url('/assets/images/sl-pattern.png');
        background-size: cover;
        background-position: center;
    }
    .error-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
</style>
HTML;

// Include header
include 'header.php';
?>

<div class="error-container flex items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="error-card max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg text-center">
        <div>
            <h2 class="mt-6 text-6xl font-extrabold text-kdj-red">404</h2>
            <h3 class="mt-2 text-3xl font-bold text-kdj-dark">පිටුව හමු නොවීය</h3>
            <p class="mt-4 text-lg text-gray-600">
                ඔබ සොයන පිටුව හමු නොවීය. ලිපිනය නිවැරදිදැයි පරීක්ෂා කරන්න.
            </p>
            <p class="mt-2 text-base text-gray-500">
                The page you are looking for could not be found. Please check the URL.
            </p>
        </div>
        
        <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="index.php" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                <i class="fas fa-home mr-2"></i>
                මුල් පිටුවට
            </a>
            <button onclick="window.history.back()" class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                <i class="fas fa-arrow-left mr-2"></i>
                ආපසු යන්න
            </button>
        </div>
        
        <div class="mt-8">
            <p class="text-base text-gray-500">
                උදව් අවශ්‍යද? <a href="mailto:support@kdj.lk" class="font-medium text-kdj-red hover:text-red-800">support@kdj.lk</a> වෙත විද්‍යුත් තැපැල් පණිවුඩයක් යවන්න
            </p>
        </div>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>