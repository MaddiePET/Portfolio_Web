document.addEventListener("DOMContentLoaded", function () {
    const notification = document.querySelector('.notification');
    const errorMessages = document.querySelector('.error-messages');
    const form = document.querySelector('.reg-form');

    function fadeOut(element) {
        if (!element) return;
        setTimeout(() => {
            element.style.opacity = '0'; // Fade out by reducing opacity
        }, 3000);

        setTimeout(() => {
            element.style.display = 'none';
        }, 3500);
    }

    if (notification) fadeOut(notification);
    if (errorMessages) fadeOut(errorMessages);

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const address = document.getElementById('addy').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('ph').value.trim();
        const oldPassword = document.getElementById('password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        let isValid = true;
        let messages = [];

        const addressRegex = /^[A-Za-z0-9\s,]*$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9\-]{7,10}$/;
        const newPasswordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

        // Validate Address
        if (!addressRegex.test(address)) {
            messages.push("Address can only contain letters, numbers, spaces, and commas.");
            isValid = false;
        }

        // Validate Email
        if (!emailRegex.test(email)) {
            messages.push("Please enter a valid email address.");
            isValid = false;
        }

        // Validate Phone
        if (!phoneRegex.test(phone)) {
            messages.push("Please enter a valid phone number (7-10 digits or dashes).");
            isValid = false;
        }

        // Validate New Password
        if (newPassword && !newPasswordRegex.test(newPassword)) {
            messages.push("Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.");
            isValid = false;
        }

        // Check if new password and confirm password match
        if (newPassword !== confirmPassword) {
            messages.push("Passwords do not match.");
            isValid = false;
        }

        // Check if new password and confirm password match
        if (oldPassword && oldPassword === newPassword) {
            messages.push("The old password and the new password must be different.");
            isValid = false;
        }

        // Validate Old Password (check on the server)
        if (oldPassword) {
            const oldPasswordValid = await checkOldPassword(oldPassword);
            if (!oldPasswordValid) {
                messages.push("Old password is incorrect.");
                isValid = false;
            }
        }

        // If form is invalid, display error messages
        if (!isValid) {
            errorMessages.innerHTML = messages.join("<br>");
            errorMessages.style.display = 'block';
            errorMessages.style.opacity = '1';
            fadeOut(errorMessages);
        } else {
            // Hide error messages and submit the form if validation passes
            errorMessages.style.display = 'none';
            form.submit();
        }
    });
});

// Function to send AJAX request to check if the old password is correct
async function checkOldPassword(oldPassword) {
    try {
        const response = await fetch('verify_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ password: oldPassword })
        });

        const result = await response.json();
        return result.isValid; // Returns true if the old password is valid, false if not
    } catch (error) {
        console.error('Error verifying old password:', error);
        return false;
    }
}

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