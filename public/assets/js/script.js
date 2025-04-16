/**
 * Main JavaScript File for KDJ PHP Frontend
 *
 * Adds basic client-side validation and enhancements.
 */

// Wait for the DOM to be fully loaded before running scripts
document.addEventListener('DOMContentLoaded', () => {

    console.log('KDJ Frontend Script Loaded'); // Basic check

    // --- Password Confirmation Validation ---
    // Applies to both Registration and Change Password forms

    const passwordFields = [
        // Registration Form
        { pass: '#register-password', confirm: '#register-confirm-password', form: '.register-form' },
        // Change Password Form
        { pass: '#new-password', confirm: '#confirm-new-password', form: '.password-change-form' }
    ];

    passwordFields.forEach(pair => {
        const formElement = document.querySelector(pair.form);
        const passwordInput = formElement?.querySelector(pair.pass);
        const confirmPasswordInput = formElement?.querySelector(pair.confirm);

        if (formElement && passwordInput && confirmPasswordInput) {
            const validatePasswordConfirmation = () => {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    // Passwords don't match - set custom validity message
                    confirmPasswordInput.setCustomValidity("Passwords do not match.");
                    // Optional: Add a visual error class
                    confirmPasswordInput.classList.add('input-error');
                } else {
                    // Passwords match - clear any previous message
                    confirmPasswordInput.setCustomValidity("");
                    // Optional: Remove visual error class
                    confirmPasswordInput.classList.remove('input-error');
                }
            };

            // Validate on input in either field
            passwordInput.addEventListener('input', validatePasswordConfirmation);
            confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);

            // Also validate right before form submission (though HTML5 validation might catch it first)
            // formElement.addEventListener('submit', (event) => {
            //     validatePasswordConfirmation();
            //     // If validity message is set, form won't submit via standard HTML5 validation
            // });
        }
    });

    // --- Disable MFA Confirmation ---
    // Find the form used for disabling MFA (assuming it's within .mfa-status)
    const disableMfaForm = document.querySelector('.mfa-status form[action*="manage_mfa.php"]'); // Adjust selector if needed

    if (disableMfaForm) {
        disableMfaForm.addEventListener('submit', (event) => {
            // Get the confirmation message from the form's onsubmit attribute (if set there)
            // or use a standard message. The PHP template added an onsubmit confirm.
            // This JS listener provides a fallback or alternative way.
            const confirmationMessage = disableMfaForm.getAttribute('onsubmit')?.match(/confirm\('(.+)'\)/)?.[1]
                                      || 'Are you sure you want to disable Multi-Factor Authentication? This will reduce your account security.';

            if (!confirm(confirmationMessage)) {
                event.preventDefault(); // Stop form submission if user clicks Cancel
                console.log('MFA disable cancelled by user.');
            }
        });
        // Remove the inline onsubmit if you prefer handling it purely here
        // disableMfaForm.removeAttribute('onsubmit');
    }


    // --- Add other JS enhancements below ---

    // Example: Toggle Password Visibility (requires adding a button/icon in HTML)
    // const togglePasswordButtons = document.querySelectorAll('.toggle-password-visibility');
    // togglePasswordButtons.forEach(button => {
    //     button.addEventListener('click', () => {
    //         const targetInputId = button.getAttribute('data-target');
    //         const passwordInput = document.getElementById(targetInputId);
    //         if (passwordInput) {
    //             if (passwordInput.type === 'password') {
    //                 passwordInput.type = 'text';
    //                 button.textContent = 'Hide'; // Change button text/icon
    //             } else {
    //                 passwordInput.type = 'password';
    //                 button.textContent = 'Show'; // Change button text/icon
    //             }
    //         }
    //     });
    // });


}); // End DOMContentLoaded
