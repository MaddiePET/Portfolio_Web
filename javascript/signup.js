document.addEventListener("DOMContentLoaded", function() {
    const notification = document.querySelector('.notification');
    const errorMessages = document.querySelector('.error-messages');
    const errors = document.querySelector('.errors');
    
    function fadeOut(element) {
        setTimeout(() => {
            element.style.opacity = '0'; // Fade out by reducing opacity
        }, 3000);
        
        setTimeout(() => {
            element.style.display = 'none';
        }, 3500);
    }

    if (notification) fadeOut(notification);
    if (errorMessages) fadeOut(errorMessages);
    if (errors) fadeOut(errors);


    const form = document.querySelector('.signup-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const fname = document.getElementById('fname').value.trim();
        const lname = document.getElementById('lname').value.trim();
        const dob = document.getElementById('dob').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const address = document.getElementById('address').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        let isValid = true;
        let messages = [];
        
        const nameRegex = /^[A-Za-z]{1,30}$/;
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9\-]{7,10}$/;
        const addressRegex = /^[A-Za-z0-9\s,]*$/;
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

        if (!nameRegex.test(fname)) {
            messages.push("First name must contain only letters and be up to 30 characters.");
            isValid = false;
        }
        if (!nameRegex.test(lname)) {
            messages.push("Last name must contain only letters and be up to 30 characters.");
            isValid = false;
        }
        
        if (dateRegex.test(dob)) {
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;

            if (age < 23 || age > 55) {
                messages.push("You must be between the age of 23 and 55 to register.");
                isValid = false;
            }
        } else {
            messages.push("Please enter a valid date.");
            isValid = false;
        }

        if (!emailRegex.test(email)) {
            messages.push("Please enter a valid email address.");
            isValid = false;
        }

        if (!phoneRegex.test(phone)) {
            messages.push("Please enter a valid phone number (7-10 digits or dashes).");
            isValid = false;
        }

        if (!addressRegex.test(address)) {
            messages.push("Address can only contain letters, numbers, spaces, and commas.");
            isValid = false;
        }

        if (!passwordRegex.test(password)) {
            messages.push("Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.");
            isValid = false;
        }

        if (password !== confirmPassword) {
            messages.push("Passwords do not match.");
            isValid = false;
        }

        if (!isValid) {
            errorMessages.innerHTML = messages.join("<br>");
            errorMessages.style.display = 'block';
            errorMessages.style.opacity = '1';
            fadeOut(errorMessages);
        } else {
            errorMessages.style.display = 'none';
            form.submit();
        }
    });

    // Capitalize first letter of text inputs
    const textFields = document.querySelectorAll('#fname, #lname, #address');
    textFields.forEach(function (field) {
        field.addEventListener('input', function (event) {
            const inputField = event.target;
            const inputValue = inputField.value;
            if (inputValue.length > 0) {
                inputField.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
            }
        });
    });
});

function capitalizeFirstLetter(event) {
    const inputField = event.target;
    const inputValue = inputField.value;

    // Capitalize first letter and keep the rest as is
    if (inputValue.length > 0) {
        inputField.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
    }
}