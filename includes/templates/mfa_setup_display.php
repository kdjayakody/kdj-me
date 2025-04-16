<?php
/**
 * MFA Setup Display Template
 *
 * Shows the QR Code, Secret Key, and Backup Codes for MFA setup.
 * Includes a form to verify the TOTP code.
 * Should be included within a page script (e.g., public/setup_mfa.php).
 * Assumes security_helpers.php, functions.php are included previously.
 *
 * Expects the calling script (e.g., public/setup_mfa.php) to:
 * 1. Call the `api_setup_mfa()` function.
 * 2. Pass the result containing ['qr_code_url', 'secret', 'backup_codes']
 * into this template via a $mfaSetupData variable.
 * 3. Handle the submission of the verification form below.
 */

// --- Defensive Checks ---
if (!function_exists('csrf_input_field')) {
     error_log('CRITICAL: csrf_input_field() function not available. Include security_helpers.php.');
     echo '<p>Error: Security token function is missing. Cannot display setup info.</p>';
     return;
}
if (!function_exists('base_url')) {
     error_log('CRITICAL: base_url() function not available. Include functions.php.');
     echo '<p>Error: URL function is missing. Cannot display setup info.</p>';
     return;
}
if (!function_exists('escape_html')) {
     error_log('CRITICAL: escape_html() function not available. Include functions.php.');
     echo '<p>Error: HTML escaping function is missing. Cannot display setup info.</p>';
     return;
}
if (!isset($mfaSetupData) || !is_array($mfaSetupData) || empty($mfaSetupData['qr_code_url']) || empty($mfaSetupData['secret']) || empty($mfaSetupData['backup_codes'])) {
    error_log('Error: $mfaSetupData variable not set or missing required keys before including mfa_setup_display.php.');
    echo '<p>Error: Could not load MFA setup data. Please try again.</p>';
    // Optionally redirect back or show a more specific error based on context
    return; // Stop rendering if data is missing
}

// Define the target URL for the verification form submission
// This could be the setup page itself or a dedicated verification script
$verificationFormActionUrl = base_url('/verify_mfa.php'); // Or potentially setup_mfa.php

?>

<div class="mfa-setup-container">
    <h2>Set Up Multi-Factor Authentication (MFA)</h2>

    <p>Scan the QR code below with your authenticator app (like Google Authenticator, Authy, etc.).</p>

    <div class="mfa-qr-code">
        <img src="<?php echo escape_html($mfaSetupData['qr_code_url']); ?>" alt="MFA QR Code">
    </div>

    <p>If you cannot scan the code, you can manually enter this secret key into your authenticator app:</p>
    <div class="mfa-secret-key">
        <code><?php echo escape_html($mfaSetupData['secret']); ?></code>
    </div>

    <hr>

    <h3>Save Your Backup Codes</h3>
    <p><strong>IMPORTANT:</strong> Store these backup codes in a safe place (e.g., password manager, printed document). You can use them to log in if you lose access to your authenticator app. Each code can only be used once.</p>
    <div class="mfa-backup-codes">
        <ul>
            <?php foreach ($mfaSetupData['backup_codes'] as $code): ?>
                <li><code><?php echo escape_html($code); ?></code></li>
            <?php endforeach; ?>
        </ul>
        </div>

    <hr>

    <h3>Verify Setup</h3>
    <p>Once your authenticator app is configured, enter the 6-digit code it displays below to complete the setup.</p>

    <form action="<?php echo escape_html($verificationFormActionUrl); ?>" method="post" class="mfa-verify-form">
        <?php echo csrf_input_field(); // IMPORTANT: Include CSRF protection token ?>

        <div class="form-group">
            <label for="mfa-code">Authenticator Code:</label>
            <input type="text" id="mfa-code" name="mfa_code" required
                   maxlength="6" size="6"
                   pattern="\d{6}" inputmode="numeric"
                   title="Enter the 6-digit code from your authenticator app."
                   autocomplete="off">
             <small>Enter the 6-digit code from your app.</small>
        </div>

        <div class="form-group">
            <button type="submit" class="button button-primary">Verify and Enable MFA</button>
        </div>
    </form>

</div>
