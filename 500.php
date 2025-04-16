<?php
// Set page specific variables
$title = "Server Error";
$description = "An unexpected error occurred";
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
            <h2 class="mt-6 text-6xl font-extrabold text-kdj-red">500</h2>
            <h3 class="mt-2 text-3xl font-bold text-kdj-dark">සේවාදායක දෝෂයක්</h3>
            <p class="mt-4 text-lg text-gray-600">
                කණගාටුයි, අපගේ සේවාදායකයේ දෝෂයක් ඇති විය. අපි ඉක්මනින් මෙය විසඳන්නට කටයුතු කරමින් සිටිමු.
            </p>
            <p class="mt-2 text-base text-gray-500">
                We're sorry, but something went wrong on our server. We're working to fix this issue.
            </p>
        </div>
        
        <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="index.php" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-kdj-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                <i class="fas fa-home mr-2"></i>
                මුල් පිටුවට
            </a>
            <button onclick="window.location.reload()" class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kdj-red">
                <i class="fas fa-sync mr-2"></i>
                පිටුව Refresh කරන්න
            </button>
        </div>
        
        <div class="mt-8">
            <p class="text-base text-gray-500">
                ගැටළුව දිගටම පවතී නම්, <a href="mailto:support@kdj.lk" class="font-medium text-kdj-red hover:text-red-800">support@kdj.lk</a> වෙත විද්‍යුත් තැපැල් පණිවුඩයක් යවන්න
            </p>
        </div>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>