<?php
/**
 * Footer Template
 *
 * Closes the main content area, includes the site footer,
 * and links JavaScript files.
 *
 * Assumes core files (config, functions) are included before this template is required.
 */

// Ensure constants are available (defensive check)
if (!defined('SITE_NAME') || !defined('APP_URL')) {
    error_log('CRITICAL: Required constants SITE_NAME or APP_URL not defined before including footer.php');
    // Avoid die() here if possible, as header might have already been sent.
    // Maybe display a minimal error within the footer.
    echo '';
}

?>
            </div> </main> <footer class="main-footer">
        <div class="container"> <p>
                &copy; <?php echo date('Y'); ?> <?php echo escape_html(SITE_NAME); ?>. All Rights Reserved.
            </p>
            </div>
    </footer>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>

    <?php
    // Example: Include page-specific JS if $pageScript variable is set in the calling page
    // if (isset($pageScript) && !empty($pageScript)) {
    //     echo '<script src="' . asset_url('js/' . $pageScript) . '"></script>';
    // }
    ?>

</body>
</html>
