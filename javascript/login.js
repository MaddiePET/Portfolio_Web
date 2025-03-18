 // Wait for the DOM to fully load
 document.addEventListener("DOMContentLoaded", function() {
    // Find the notification element
    const error = document.querySelector('.error-message');

    // If a notification is present, start the fade-out after 3 seconds
    if (error) {
        setTimeout(function() {
            error.style.opacity = '0'; // Fade out by reducing opacity
        }, 3000); // 3 seconds delay

        // Remove the notification from the DOM after it fades out
        setTimeout(function() {
            error.style.display = 'none';
        }, 3500); // Slight delay to hide after fading out
    }
});

function togglePasswordVisibility(fieldId, icon) {
    const inputField = document.getElementById(fieldId);

    // Toggle the input field type between password and text
    if (inputField.type === 'password') {
        inputField.type = 'text';  // Show the password
        icon.classList.remove('fa-eye'); 
        icon.classList.add('fa-eye-slash');
    } else {
        inputField.type = 'password';  // Hide the password
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye'); 
    }
}